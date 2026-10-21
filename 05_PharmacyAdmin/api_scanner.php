<?php
// 05_PharmacyAdmin/api_scanner.php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../00_Config/config.php';

// Auth Guard
if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in.']);
    exit;
}

$pharmacyId = (int) ($_SESSION['pharmacy_id'] ?? 0);
if (!$pharmacyId) {
    $stmt = $pdo->prepare("SELECT Pharmacy_ID, Pharmacy_name FROM 09_pharmacies WHERE User_ID = ? LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $p = $stmt->fetch();
    if ($p) {
        $pharmacyId = (int) $p['Pharmacy_ID'];
        $_SESSION['pharmacy_id'] = $pharmacyId;
        $_SESSION['pharmacy_name'] = $p['Pharmacy_name'];
    }
}

if (!$pharmacyId) {
    echo json_encode(['success' => false, 'message' => 'No active pharmacy associated with this account.']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ── ACTION: LOOKUP MEDICINE BY BARCODE OR TEXT ────────────────────
if ($action === 'lookup') {
    $code = trim($_GET['code'] ?? $_POST['code'] ?? '');

    if ($code === '') {
        echo json_encode(['success' => false, 'message' => 'Scan or query code is required.']);
        exit;
    }

    // Try matching by exact Inventory_ID, Medicine_ID, Generic_Name, or Brand_Name
    $sql = "
        SELECT 
            med.Medicine_ID,
            gn.Generic_Name,
            bn.Brand_Name,
            df.Dosage_Form,
            dv.Dosage_Value,
            du.Unit_Name,
            c.Category_Name,
            i.Inventory_ID,
            i.Quantity,
            i.Price,
            i.Expiry_date,
            i.Last_updated
        FROM 12_meds_medicines med
        LEFT JOIN 13_meds_generic_name gn ON med.Gen_ID = gn.Gen_ID
        LEFT JOIN 14_meds_brand_name bn ON med.Brand_ID = bn.Brand_ID
        LEFT JOIN 15_meds_dosage_form df ON med.DosageForm_ID = df.DosageForm_ID
        LEFT JOIN 16_meds_dosage_value dv ON med.DosageVal_ID = dv.DosageVal_ID
        LEFT JOIN 17_meds_dosage_unit du ON med.Unit_ID = du.Unit_ID
        LEFT JOIN 18_meds_categories c ON med.Category_ID = c.Category_ID
        LEFT JOIN 21_inventory i ON (med.Medicine_ID = i.Medicine_ID AND i.Pharmacy_ID = :pharmacy_id AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL))
        WHERE (
            med.Medicine_ID = :exact_med_id
            OR i.Inventory_ID = :exact_inv_id
            OR gn.Generic_Name LIKE :like_code
            OR bn.Brand_Name LIKE :like_code
        )
        LIMIT 10
    ";

    $params = [
        ':pharmacy_id' => $pharmacyId,
        ':exact_med_id' => is_numeric($code) ? (int)$code : 0,
        ':exact_inv_id' => is_numeric($code) ? (int)$code : 0,
        ':like_code' => "%$code%"
    ];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $matches = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'query' => $code,
        'count' => count($matches),
        'results' => $matches
    ]);
    exit;
}

// ── ACTION: PARSE RECEIPT TEXT (MULTI-ITEM SCAN) ──────────────────
if ($action === 'parse_receipt') {
    $rawText = trim($_POST['text'] ?? '');
    if ($rawText === '') {
        echo json_encode(['success' => false, 'message' => 'Receipt text is empty.']);
        exit;
    }

    $lines = explode("\n", $rawText);
    $parsedResults = [];

    $searchStmt = $pdo->prepare("
        SELECT 
            med.Medicine_ID,
            gn.Generic_Name,
            bn.Brand_Name,
            df.Dosage_Form,
            dv.Dosage_Value,
            du.Unit_Name,
            i.Inventory_ID,
            i.Quantity,
            i.Price
        FROM 12_meds_medicines med
        LEFT JOIN 13_meds_generic_name gn ON med.Gen_ID = gn.Gen_ID
        LEFT JOIN 14_meds_brand_name bn ON med.Brand_ID = bn.Brand_ID
        LEFT JOIN 15_meds_dosage_form df ON med.DosageForm_ID = df.DosageForm_ID
        LEFT JOIN 16_meds_dosage_value dv ON med.DosageVal_ID = dv.DosageVal_ID
        LEFT JOIN 17_meds_dosage_unit du ON med.Unit_ID = du.Unit_ID
        LEFT JOIN 21_inventory i ON (med.Medicine_ID = i.Medicine_ID AND i.Pharmacy_ID = ? AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL))
        WHERE gn.Generic_Name LIKE ? OR bn.Brand_Name LIKE ?
        LIMIT 1
    ");

    foreach ($lines as $line) {
        $cleanLine = trim($line);
        if ($cleanLine === '' || strlen($cleanLine) < 3) continue;

        // Extract potential quantity: e.g. "Amoxicillin 500mg x 10" or "Paracetamol 500mg - Qty: 20" or "10x Biogesic"
        $qty = 1;
        if (preg_match('/(?:x\s*|qty[:\s]*|qty\.\s*)(\d+)/i', $cleanLine, $m)) {
            $qty = (int)$m[1];
        } elseif (preg_match('/^(\d+)\s*x\s+/i', $cleanLine, $m)) {
            $qty = (int)$m[1];
        }

        // Clean medicine search term
        $searchTerm = preg_replace('/(?:x\s*\d+|qty[:\s]*\d+|\b\d+\s*x\b|[0-9]+(?:\.[0-9]{2})?|\bphp\b|\bp\b)/i', '', $cleanLine);
        $searchTerm = trim(preg_replace('/[^\w\s-]/', '', $searchTerm));

        if (strlen($searchTerm) >= 3) {
            $searchStmt->execute([$pharmacyId, "%$searchTerm%", "%$searchTerm%"]);
            $match = $searchStmt->fetch();

            if ($match) {
                $parsedResults[] = [
                    'matched' => true,
                    'raw_line' => $cleanLine,
                    'medicine_id' => $match['Medicine_ID'],
                    'inventory_id' => $match['Inventory_ID'],
                    'generic_name' => $match['Generic_Name'],
                    'brand_name' => $match['Brand_Name'],
                    'dosage' => ($match['Dosage_Value'] ? $match['Dosage_Value'] . ' ' . $match['Unit_Name'] : ''),
                    'current_qty' => (int)($match['Quantity'] ?? 0),
                    'suggested_qty' => $qty,
                    'unit_price' => $match['Price']
                ];
            } else {
                $parsedResults[] = [
                    'matched' => false,
                    'raw_line' => $cleanLine,
                    'medicine_id' => null,
                    'inventory_id' => null,
                    'generic_name' => $searchTerm,
                    'brand_name' => '',
                    'dosage' => '',
                    'current_qty' => 0,
                    'suggested_qty' => $qty,
                    'unit_price' => null
                ];
            }
        }
    }

    echo json_encode(['success' => true, 'items' => $parsedResults]);
    exit;
}

// ── ACTION: DIRECT UPDATE STOCK ──────────────────────────────────
if ($action === 'update_stock') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    if (!$data) {
        $data = $_POST;
    }

    $invId = (int)($data['inventory_id'] ?? 0);
    $medId = (int)($data['medicine_id'] ?? 0);
    $qtyChange = (int)($data['quantity_change'] ?? 0);
    $mode = $data['mode'] ?? 'add'; // 'add' (increment) or 'set' (replace)

    if ($invId <= 0 && $medId <= 0) {
        echo json_encode(['success' => false, 'message' => 'Medicine or Inventory ID is required.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        if ($invId > 0) {
            // Check existing
            $chk = $pdo->prepare("SELECT Quantity FROM 21_inventory WHERE Inventory_ID = ? AND Pharmacy_ID = ?");
            $chk->execute([$invId, $pharmacyId]);
            $currentStock = $chk->fetchColumn();

            if ($currentStock === false) {
                throw new Exception("Inventory item not found for this pharmacy.");
            }

            $newQty = ($mode === 'set') ? max(0, $qtyChange) : max(0, (int)$currentStock + $qtyChange);

            $upd = $pdo->prepare("
                UPDATE 21_inventory 
                SET Quantity = ?, Last_updated = NOW() 
                WHERE Inventory_ID = ? AND Pharmacy_ID = ?
            ");
            $upd->execute([$newQty, $invId, $pharmacyId]);

        } else {
            // Inventory does not exist yet for this medicine, create it
            $newQty = max(0, $qtyChange);
            $expiry = date('Y-m-d', strtotime('+1 year')); // default expiry if unknown
            $ins = $pdo->prepare("
                INSERT INTO 21_inventory (Pharmacy_ID, Medicine_ID, Quantity, Price, Expiry_date, Last_updated)
                VALUES (?, ?, ?, 0.00, ?, NOW())
            ");
            $ins->execute([$pharmacyId, $medId, $newQty, $expiry]);
            $invId = (int)$pdo->lastInsertId();
        }

        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Stock updated successfully.',
            'inventory_id' => $invId,
            'new_quantity' => $newQty
        ]);
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid action.']);
