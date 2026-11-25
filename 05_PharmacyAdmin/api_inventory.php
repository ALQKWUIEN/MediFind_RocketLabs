<?php
// 05_PharmacyAdmin/api_inventory.php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../00_Config/config.php';
include_once __DIR__ . '/inventory_alert_helper.php';

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in.']);
    exit;
}

$pharmacyId = (int) ($_SESSION['pharmacy_id'] ?? 0);
if (!$pharmacyId) {
    $stmt = $pdo->prepare("SELECT Pharmacy_ID, Pharmacy_name FROM 09_pharmacies WHERE User_ID = ? LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $p = $stmt->fetch(PDO::FETCH_ASSOC);
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

mfEnsureInventoryAlertSchema($pdo);

$rawInput = file_get_contents('php://input');
$jsonBody = json_decode($rawInput, true) ?: [];
$action = $_GET['action'] ?? $_POST['action'] ?? $jsonBody['action'] ?? '';

try {
    // ── 1. Update Global Default Low-Stock Threshold ─────────────────
    if ($action === 'set_global_threshold') {
        $threshold = max(1, min(9999, (int) ($jsonBody['threshold'] ?? $_POST['threshold'] ?? 10)));
        $stmt = $pdo->prepare("UPDATE 09_pharmacies SET Default_Low_Stock_Threshold = ? WHERE Pharmacy_ID = ?");
        $stmt->execute([$threshold, $pharmacyId]);
        echo json_encode(['success' => true, 'threshold' => $threshold]);
        exit;
    }

    // ── 2. Quick Restock (+ action) ──────────────────────────────────
    if ($action === 'quick_restock') {
        $invId = (int) ($jsonBody['inventory_id'] ?? $_POST['inventory_id'] ?? 0);
        $addQty = (int) ($jsonBody['add_qty'] ?? $_POST['add_qty'] ?? 0);
        $batch = trim((string) ($jsonBody['batch_number'] ?? $_POST['batch_number'] ?? ''));
        $expiry = trim((string) ($jsonBody['expiry_date'] ?? $_POST['expiry_date'] ?? ''));

        if ($invId <= 0 || $addQty <= 0) {
            echo json_encode(['success' => false, 'message' => 'Inventory ID and positive restock quantity are required.']);
            exit;
        }

        $curStmt = $pdo->prepare("SELECT Quantity, Batch_Number, Expiry_date FROM 21_inventory WHERE Inventory_ID = ? AND Pharmacy_ID = ? AND (IsDeleted = 0 OR IsDeleted IS NULL) LIMIT 1");
        $curStmt->execute([$invId, $pharmacyId]);
        $cur = $curStmt->fetch(PDO::FETCH_ASSOC);
        if (!$cur) {
            echo json_encode(['success' => false, 'message' => 'Inventory item not found.']);
            exit;
        }

        $oldQty = (int) $cur['Quantity'];
        $newQty = $oldQty + $addQty;
        $newBatch = $batch !== '' ? $batch : ($cur['Batch_Number'] ?? null);
        $newExpiry = $expiry !== '' ? $expiry : ($cur['Expiry_date'] ?? null);

        $upd = $pdo->prepare("
            UPDATE 21_inventory
            SET Quantity = ?, Batch_Number = ?, Expiry_date = ?, Last_updated = NOW()
            WHERE Inventory_ID = ? AND Pharmacy_ID = ?
        ");
        $upd->execute([$newQty, $newBatch, $newExpiry, $invId, $pharmacyId]);

        mfCheckAndRecordStockAlert($pdo, $pharmacyId, $invId, $oldQty, $newQty);

        echo json_encode([
            'success' => true,
            'inventory_id' => $invId,
            'new_qty' => $newQty,
            'batch_number' => $newBatch,
            'expiry_date' => $newExpiry
        ]);
        exit;
    }

    // ── 3. Add / Edit Medicine Stock (+ optional Low_Stock_Threshold) ──
    if ($action === 'save_medicine') {
        $invId = (int) ($jsonBody['inventory_id'] ?? 0);
        $generic = trim((string) ($jsonBody['generic_name'] ?? ''));
        $brand = trim((string) ($jsonBody['brand_name'] ?? ''));
        $category = trim((string) ($jsonBody['category_name'] ?? 'General'));
        $dosageForm = trim((string) ($jsonBody['dosage_form'] ?? 'Tablet'));
        $dosageVal = trim((string) ($jsonBody['dosage_value'] ?? '500'));
        $dosageUnit = trim((string) ($jsonBody['unit_name'] ?? 'mg'));
        $batch = trim((string) ($jsonBody['batch_number'] ?? ''));
        $price = max(0, (float) ($jsonBody['price'] ?? 0));
        $qty = max(0, (int) ($jsonBody['quantity'] ?? 0));
        $expiry = trim((string) ($jsonBody['expiry_date'] ?? date('Y-m-d', strtotime('+180 days'))));
        $rawThreshold = trim((string) ($jsonBody['low_stock_threshold'] ?? ''));
        $thresholdVal = ($rawThreshold !== '' && is_numeric($rawThreshold)) ? max(1, (int) $rawThreshold) : null;

        if ($invId > 0) {
            $curStmt = $pdo->prepare("SELECT Quantity, Medicine_ID FROM 21_inventory WHERE Inventory_ID = ? AND Pharmacy_ID = ? LIMIT 1");
            $curStmt->execute([$invId, $pharmacyId]);
            $cur = $curStmt->fetch(PDO::FETCH_ASSOC);
            if (!$cur) {
                echo json_encode(['success' => false, 'message' => 'Inventory item not found.']);
                exit;
            }
            $oldQty = (int) $cur['Quantity'];

            $upd = $pdo->prepare("
                UPDATE 21_inventory
                SET Batch_Number = ?, Quantity = ?, Low_Stock_Threshold = ?, Price = ?, Expiry_date = ?, Last_updated = NOW()
                WHERE Inventory_ID = ? AND Pharmacy_ID = ?
            ");
            $upd->execute([$batch !== '' ? $batch : null, $qty, $thresholdVal, $price, $expiry, $invId, $pharmacyId]);

            mfCheckAndRecordStockAlert($pdo, $pharmacyId, $invId, $oldQty, $qty);
            echo json_encode(['success' => true, 'inventory_id' => $invId]);
            exit;
        }

        if ($generic === '') {
            echo json_encode(['success' => false, 'message' => 'Generic name is required.']);
            exit;
        }

        $pdo->beginTransaction();

        $getOrCreate = function ($table, $idCol, $nameCol, $val) use ($pdo) {
            $val = $val !== '' ? $val : 'Standard';
            $s = $pdo->prepare("SELECT {$idCol} FROM {$table} WHERE {$nameCol} = ? LIMIT 1");
            $s->execute([$val]);
            $id = $s->fetchColumn();
            if ($id) return (int) $id;
            $pdo->prepare("INSERT INTO {$table} ({$nameCol}) VALUES (?)")->execute([$val]);
            return (int) $pdo->lastInsertId();
        };

        $genId = $getOrCreate('13_meds_generic_name', 'Gen_ID', 'Generic_Name', $generic);
        $brandId = $getOrCreate('14_meds_brand_name', 'Brand_ID', 'Brand_Name', $brand !== '' ? $brand : 'Generic');
        $formId = $getOrCreate('15_meds_dosage_form', 'DosageForm_ID', 'Dosage_Form', $dosageForm);
        $valId = $getOrCreate('16_meds_dosage_value', 'DosageVal_ID', 'Dosage_Value', $dosageVal);
        $unitId = $getOrCreate('17_meds_dosage_unit', 'Unit_ID', 'Unit_Name', $dosageUnit);
        $catId = $getOrCreate('18_meds_categories', 'Category_ID', 'Category_Name', $category);

        $mStmt = $pdo->prepare("SELECT Medicine_ID FROM 12_meds_medicines WHERE Gen_ID = ? AND Brand_ID = ? AND DosageForm_ID = ? AND DosageVal_ID = ? AND Unit_ID = ? LIMIT 1");
        $mStmt->execute([$genId, $brandId, $formId, $valId, $unitId]);
        $medId = $mStmt->fetchColumn();
        if (!$medId) {
            $pdo->prepare("INSERT INTO 12_meds_medicines (Gen_ID, Brand_ID, DosageForm_ID, DosageVal_ID, Unit_ID, Category_ID, Manufacturer_ID, Require_rx_ID) VALUES (?, ?, ?, ?, ?, ?, 1, 1)")
                ->execute([$genId, $brandId, $formId, $valId, $unitId, $catId]);
            $medId = (int) $pdo->lastInsertId();
        }

        $ins = $pdo->prepare("
            INSERT INTO 21_inventory (Pharmacy_ID, Medicine_ID, Batch_Number, Quantity, Low_Stock_Threshold, Price, Expiry_date, Last_updated)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $ins->execute([$pharmacyId, $medId, $batch !== '' ? $batch : null, $qty, $thresholdVal, $price, $expiry]);
        $newInvId = (int) $pdo->lastInsertId();

        $pdo->commit();
        mfCheckAndRecordStockAlert($pdo, $pharmacyId, $newInvId, 999999, $qty);
        echo json_encode(['success' => true, 'inventory_id' => $newInvId]);
        exit;
    }

    // ── 4. Get Low-Stock Alerts for Topbar Notification Bell ─────────
    if ($action === 'get_alerts') {
        $stmt = $pdo->prepare("
            SELECT Alert_ID, Inventory_ID, Medicine_Name, Brand_Name, Current_Qty, Threshold_Used, Alert_Type, Is_Read, Created_At
            FROM 28_pharmacy_alerts
            WHERE Pharmacy_ID = ?
            ORDER BY Is_Read ASC, Created_At DESC
            LIMIT 15
        ");
        $stmt->execute([$pharmacyId]);
        $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If no crossing alerts logged yet, surface current stock outages & low-stock items
        if (empty($alerts)) {
            $curStmt = $pdo->prepare("
                SELECT
                    0 AS Alert_ID,
                    i.Inventory_ID,
                    COALESCE(gn.Generic_Name, 'Medicine') AS Medicine_Name,
                    COALESCE(bn.Brand_Name, '') AS Brand_Name,
                    i.Quantity AS Current_Qty,
                    COALESCE(i.Low_Stock_Threshold, p.Default_Low_Stock_Threshold, 10) AS Threshold_Used,
                    CASE WHEN i.Quantity <= 0 THEN 'out_of_stock' ELSE 'low_stock' END AS Alert_Type,
                    0 AS Is_Read,
                    COALESCE(i.Last_updated, NOW()) AS Created_At
                FROM 21_inventory i
                INNER JOIN 09_pharmacies p ON i.Pharmacy_ID = p.Pharmacy_ID
                INNER JOIN 12_meds_medicines med ON i.Medicine_ID = med.Medicine_ID
                LEFT JOIN 13_meds_generic_name gn ON med.Gen_ID = gn.Gen_ID
                LEFT JOIN 14_meds_brand_name bn ON med.Brand_ID = bn.Brand_ID
                WHERE i.Pharmacy_ID = ?
                  AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
                  AND i.Quantity <= COALESCE(i.Low_Stock_Threshold, p.Default_Low_Stock_Threshold, 10)
                ORDER BY i.Quantity ASC
                LIMIT 10
            ");
            $curStmt->execute([$pharmacyId]);
            $alerts = $curStmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode(['success' => true, 'alerts' => $alerts]);
        exit;
    }

    // ── 5. Mark Alert(s) Read ────────────────────────────────────────
    if ($action === 'mark_alert_read') {
        $alertId = (int) ($jsonBody['alert_id'] ?? 0);
        if ($alertId > 0) {
            $pdo->prepare("UPDATE 28_pharmacy_alerts SET Is_Read = 1 WHERE Alert_ID = ? AND Pharmacy_ID = ?")
                ->execute([$alertId, $pharmacyId]);
        }
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'mark_all_alerts_read') {
        $pdo->prepare("UPDATE 28_pharmacy_alerts SET Is_Read = 1 WHERE Pharmacy_ID = ?")
            ->execute([$pharmacyId]);
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Unknown action.']);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

