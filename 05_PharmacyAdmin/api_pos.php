<?php
// 05_PharmacyAdmin/api_pos.php
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
    // Attempt lookup from 09_pharmacies
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

// ── Ensure POS tables exist ──────────────────────────────────────
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `26_pos_sales` (
          `Sale_ID` INT AUTO_INCREMENT PRIMARY KEY,
          `Receipt_No` VARCHAR(50) NOT NULL UNIQUE,
          `Pharmacy_ID` INT NOT NULL,
          `User_ID` INT NOT NULL,
          `Customer_Name` VARCHAR(150) DEFAULT 'Walk-in Customer',
          `Subtotal` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
          `Discount_Type` VARCHAR(50) DEFAULT 'None',
          `Discount_Amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
          `Grand_Total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
          `Cash_Tendered` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
          `Change_Amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
          `Payment_Method` VARCHAR(50) DEFAULT 'Cash',
          `Sale_Date` DATETIME DEFAULT CURRENT_TIMESTAMP,
          INDEX (`Pharmacy_ID`),
          INDEX (`Sale_Date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

        CREATE TABLE IF NOT EXISTS `27_pos_sale_items` (
          `Item_ID` INT AUTO_INCREMENT PRIMARY KEY,
          `Sale_ID` INT NOT NULL,
          `Inventory_ID` INT NOT NULL,
          `Medicine_ID` INT NOT NULL,
          `Quantity` INT NOT NULL,
          `Unit_Price` DECIMAL(10,2) NOT NULL,
          `Total_Price` DECIMAL(10,2) NOT NULL,
          INDEX (`Sale_ID`),
          INDEX (`Inventory_ID`),
          INDEX (`Medicine_ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");
} catch (Exception $e) {
    // Non-fatal if already exist or permission restricted
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ── ACTION: SEARCH MEDICINES ─────────────────────────────────────
if ($action === 'search') {
    $q = trim($_GET['q'] ?? '');
    $category = trim($_GET['category'] ?? '');

    $sql = "
        SELECT 
            i.Inventory_ID,
            i.Pharmacy_ID,
            i.Medicine_ID,
            i.Quantity,
            i.Price,
            i.Expiry_date,
            gn.Generic_Name,
            bn.Brand_Name,
            df.Dosage_Form,
            dv.Dosage_Value,
            du.Unit_Name,
            c.Category_Name,
            m.Manufacturer_Name,
            rx.Requirement_Status AS Requires_Rx,
            up.Unit AS Price_Per,
            COALESCE(img.Image_Path, '') AS Med_Image_URL
        FROM 21_inventory i
        INNER JOIN 12_meds_medicines med ON i.Medicine_ID = med.Medicine_ID
        LEFT JOIN 13_meds_generic_name gn ON med.Gen_ID = gn.Gen_ID
        LEFT JOIN 14_meds_brand_name bn ON med.Brand_ID = bn.Brand_ID
        LEFT JOIN 15_meds_dosage_form df ON med.DosageForm_ID = df.DosageForm_ID
        LEFT JOIN 16_meds_dosage_value dv ON med.DosageVal_ID = dv.DosageVal_ID
        LEFT JOIN 17_meds_dosage_unit du ON med.Unit_ID = du.Unit_ID
        LEFT JOIN 18_meds_categories c ON med.Category_ID = c.Category_ID
        LEFT JOIN 19_meds_manufacturer m ON med.Manufacturer_ID = m.Manufacturer_ID
        LEFT JOIN 20_meds_requires_rx rx ON med.Require_rx_ID = rx.Require_rx_ID
        LEFT JOIN 22_inventory_unit_price up ON i.Price_Unit_ID = up.Price_Unit_ID
        LEFT JOIN (
            SELECT Medicine_ID, MIN(Image_Path) AS Image_Path 
            FROM 24_medicine_images 
            GROUP BY Medicine_ID
        ) img ON med.Medicine_ID = img.Medicine_ID
        WHERE i.Pharmacy_ID = :pharmacy_id
          AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
    ";

    $params = [':pharmacy_id' => $pharmacyId];

    if ($q !== '') {
        $sql .= " AND (gn.Generic_Name LIKE :q OR bn.Brand_Name LIKE :q OR c.Category_Name LIKE :q OR i.Inventory_ID = :exact_id)";
        $params[':q'] = "%$q%";
        $params[':exact_id'] = is_numeric($q) ? (int) $q : 0;
    }

    if ($category !== '') {
        $sql .= " AND c.Category_Name = :category";
        $params[':category'] = $category;
    }

    $sql .= " ORDER BY gn.Generic_Name ASC LIMIT 30";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll();

    echo json_encode(['success' => true, 'items' => $results]);
    exit;
}

// ── ACTION: PROCESS CHECKOUT ─────────────────────────────────────
if ($action === 'checkout') {
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);

    if (!$data || empty($data['items']) || !is_array($data['items'])) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty or invalid data provided.']);
        exit;
    }

    $items = $data['items'];
    $customerName = trim($data['customer_name'] ?? 'Walk-in Customer');
    if ($customerName === '')
        $customerName = 'Walk-in Customer';

    $discountType = $data['discount_type'] ?? 'None';
    $discountAmount = max(0, (float) ($data['discount_amount'] ?? 0));
    $subtotal = max(0, (float) ($data['subtotal'] ?? 0));
    $grandTotal = max(0, (float) ($data['grand_total'] ?? 0));
    $cashTendered = max(0, (float) ($data['cash_tendered'] ?? 0));
    $changeAmount = max(0, (float) ($data['change_amount'] ?? 0));
    $paymentMethod = $data['payment_method'] ?? 'Cash';

    if ($cashTendered < $grandTotal) {
        echo json_encode(['success' => false, 'message' => 'Cash tendered is less than the grand total.']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        // 1. Verify stock availability for each item
        $checkStmt = $pdo->prepare("
            SELECT Quantity 
            FROM 21_inventory 
            WHERE Inventory_ID = ? AND Pharmacy_ID = ? AND (IsDeleted = 0 OR IsDeleted IS NULL)
            FOR UPDATE
        ");

        foreach ($items as $item) {
            $invId = (int) ($item['inventory_id'] ?? 0);
            $reqQty = (int) ($item['quantity'] ?? 0);

            if ($invId <= 0 || $reqQty <= 0) {
                throw new Exception("Invalid item or quantity in cart.");
            }

            $checkStmt->execute([$invId, $pharmacyId]);
            $currentQty = $checkStmt->fetchColumn();

            if ($currentQty === false) {
                throw new Exception("Medicine inventory item #{$invId} not found in this pharmacy.");
            }

            if ((int) $currentQty < $reqQty) {
                throw new Exception("Insufficient stock for item #{$invId}. Available: {$currentQty}, Requested: {$reqQty}.");
            }
        }

        // 2. Generate Unique Receipt Number
        $receiptNo = 'MF-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // 3. Insert into 26_pos_sales
        $saleStmt = $pdo->prepare("
            INSERT INTO 26_pos_sales 
            (Receipt_No, Pharmacy_ID, User_ID, Customer_Name, Subtotal, Discount_Type, Discount_Amount, Grand_Total, Cash_Tendered, Change_Amount, Payment_Method, Sale_Date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $saleStmt->execute([
            $receiptNo,
            $pharmacyId,
            $_SESSION['user_id'],
            $customerName,
            $subtotal,
            $discountType,
            $discountAmount,
            $grandTotal,
            $cashTendered,
            $changeAmount,
            $paymentMethod
        ]);
        $saleId = (int) $pdo->lastInsertId();

        // 4. Decrement Stock & Insert into 27_pos_sale_items
        $itemStmt = $pdo->prepare("
            INSERT INTO 27_pos_sale_items (Sale_ID, Inventory_ID, Medicine_ID, Quantity, Unit_Price, Total_Price)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $updateStockStmt = $pdo->prepare("
            UPDATE 21_inventory 
            SET Quantity = Quantity - ?, Last_updated = NOW()
            WHERE Inventory_ID = ? AND Pharmacy_ID = ?
        ");

        $updatedStockList = [];

        foreach ($items as $item) {
            $invId = (int) $item['inventory_id'];
            $medId = (int) $item['medicine_id'];
            $qty = (int) $item['quantity'];
            $unitPrice = (float) $item['unit_price'];
            $lineTotal = (float) ($item['total_price'] ?? ($qty * $unitPrice));

            $itemStmt->execute([$saleId, $invId, $medId, $qty, $unitPrice, $lineTotal]);
            $updateStockStmt->execute([$qty, $invId, $pharmacyId]);

            // Query new quantity
            $getNewQty = $pdo->prepare("SELECT Quantity FROM 21_inventory WHERE Inventory_ID = ?");
            $getNewQty->execute([$invId]);
            $updatedStockList[$invId] = (int) $getNewQty->fetchColumn();
        }

        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => 'Checkout completed successfully.',
            'receipt' => [
                'sale_id' => $saleId,
                'receipt_no' => $receiptNo,
                'pharmacy_name' => $_SESSION['pharmacy_name'] ?? 'MediFind Pharmacy',
                'customer_name' => $customerName,
                'items' => $items,
                'subtotal' => $subtotal,
                'discount_type' => $discountType,
                'discount_amount' => $discountAmount,
                'grand_total' => $grandTotal,
                'cash_tendered' => $cashTendered,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
                'date' => date('M d, Y h:i A')
            ],
            'updated_stock' => $updatedStockList
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
