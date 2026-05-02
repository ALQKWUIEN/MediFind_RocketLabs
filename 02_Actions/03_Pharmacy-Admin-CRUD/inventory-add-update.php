<?php
// ============================================================
//  inventory-add-update.php
//  Handles: add | update | delete (soft) for 21_inventory
// ============================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

include_once __DIR__ . '/../../00_Config/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo 'unauthorized';
    exit;
}

$action     = $_POST['action'] ?? '';
$pharmacyID = (int)($_SESSION['pharmacy_id'] ?? 0);

if (!$pharmacyID) {
    echo 'missing_pharmacy';
    exit;
}

// ── Helper: find or insert lookup row ──────────────────────
function findOrInsertPK(PDO $pdo, string $table, string $pkCol, string $valCol, string $val): int {
    $val = trim($val);
    if ($val === '') return 0;

    $s = $pdo->prepare("SELECT `{$pkCol}` FROM `{$table}` WHERE `{$valCol}` = ?");
    $s->execute([$val]);
    $id = $s->fetchColumn();

    if (!$id) {
        $pdo->prepare("INSERT INTO `{$table}` (`{$valCol}`) VALUES (?)")->execute([$val]);
        $id = (int)$pdo->lastInsertId();
    }
    return (int)$id;
}

// ============================================================
//  ADD
// ============================================================
if ($action === 'add') {

    $genericName      = trim($_POST['generic_name']  ?? '');
    $brandName        = trim($_POST['brand']          ?? '');
    $categoryName     = trim($_POST['category']       ?? '');
    $manufacturerName = trim($_POST['manufacturer']   ?? '');
    $dosageForm       = trim($_POST['dosage_form']    ?? '');
    $dosageValue      = trim($_POST['dosage_value']   ?? '');
    $dosageUnit       = trim($_POST['dosage_unit']    ?? '');
    $requiresRx       = trim($_POST['requires_rx']    ?? 'No');
    $unitPrice        = (float)($_POST['unit_price']  ?? 0);
    $priceUnitID      = (int)($_POST['price_unit_id'] ?? 1);
    $quantity         = (int)($_POST['qty']           ?? 0);
    $expiryDate       = $_POST['expiry_date']         ?? null;

    if (!$genericName || !$brandName || !$categoryName ||
        !$dosageForm  || !$dosageValue || !$dosageUnit ||
        !$expiryDate  || $unitPrice <= 0) {
        echo 'missing_fields';
        exit;
    }

    try {
        $pdo->beginTransaction();

        $genID      = findOrInsertPK($pdo, '13_meds_generic_name', 'Gen_ID',         'Generic_Name',      $genericName);
        $brandID    = findOrInsertPK($pdo, '14_meds_brand_name',   'Brand_ID',        'Brand_Name',        $brandName);
        $categoryID = findOrInsertPK($pdo, '18_meds_categories',   'Category_ID',     'Category_Name',     $categoryName);
        $formID     = findOrInsertPK($pdo, '15_meds_dosage_form',  'DosageForm_ID',   'Dosage_Form',       $dosageForm);
        $valID      = findOrInsertPK($pdo, '16_meds_dosage_value', 'DosageVal_ID',    'Dosage_Value',      $dosageValue);
        $unitID     = findOrInsertPK($pdo, '17_meds_dosage_unit',  'Unit_ID',         'Unit_Name',         $dosageUnit);
        $rxID       = findOrInsertPK($pdo, '20_meds_requires_rx',  'Require_rx_ID',   'Requirement_Status',$requiresRx);
        $manuID     = $manufacturerName
                        ? findOrInsertPK($pdo, '19_meds_manufacturer', 'Manufacturer_ID', 'Manufacturer_Name', $manufacturerName)
                        : null;

        // Check if exact medicine combo exists
        $s = $pdo->prepare(
            "SELECT Medicine_ID FROM 12_meds_medicines
             WHERE Gen_ID=? AND Brand_ID=? AND DosageForm_ID=?
               AND DosageVal_ID=? AND Unit_ID=? AND Category_ID=?
               AND IsDeleted=0"
        );
        $s->execute([$genID, $brandID, $formID, $valID, $unitID, $categoryID]);
        $medicineID = $s->fetchColumn();

        if (!$medicineID) {
            $pdo->prepare(
                "INSERT INTO 12_meds_medicines
                 (Gen_ID, Brand_ID, DosageForm_ID, DosageVal_ID, Unit_ID,
                  Category_ID, Manufacturer_ID, Require_rx_ID, IsDeleted)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)"
            )->execute([$genID, $brandID, $formID, $valID, $unitID,
                        $categoryID, $manuID, $rxID]);
            $medicineID = (int)$pdo->lastInsertId();
        }

        // Insert inventory
        $pdo->prepare(
            "INSERT INTO 21_inventory
             (Pharmacy_ID, Medicine_ID, Quantity, Price_Unit_ID, Price, Expiry_date, IsDeleted)
             VALUES (?, ?, ?, ?, ?, ?, 0)"
        )->execute([$pharmacyID, $medicineID, $quantity, $priceUnitID, $unitPrice, $expiryDate]);

        $pdo->commit();
        echo 'success';

    } catch (Exception $e) {
        $pdo->rollBack();
        echo 'error: ' . $e->getMessage();
    }

// ============================================================
//  UPDATE — only 21_inventory columns
// ============================================================
} elseif ($action === 'update') {

    $inventoryID = (int)($_POST['id']           ?? 0);
    $quantity    = (int)($_POST['qty']           ?? 0);
    $unitPrice   = (float)($_POST['unit_price']  ?? 0);
    $priceUnitID = (int)($_POST['price_unit_id'] ?? 1);
    $expiryDate  = $_POST['expiry_date']         ?? null;

    if (!$inventoryID || !$expiryDate || $unitPrice <= 0) {
        echo 'missing_fields';
        exit;
    }

    try {
        $stmt = $pdo->prepare(
            "UPDATE 21_inventory
             SET Quantity=?, Price=?, Price_Unit_ID=?, Expiry_date=?
             WHERE Inventory_ID=? AND Pharmacy_ID=? AND IsDeleted=0"
        );
        $ok = $stmt->execute([
            $quantity, $unitPrice, $priceUnitID,
            $expiryDate, $inventoryID, $pharmacyID
        ]);

        echo ($ok && $stmt->rowCount() > 0) ? 'success' : 'not_found';

    } catch (Exception $e) {
        echo 'error: ' . $e->getMessage();
    }

// ============================================================
//  DELETE — soft delete only
// ============================================================
} elseif ($action === 'delete') {

    $inventoryID = (int)($_POST['id'] ?? 0);

    if (!$inventoryID) {
        echo 'missing_id';
        exit;
    }

    try {
        $stmt = $pdo->prepare(
            "UPDATE 21_inventory
             SET IsDeleted=1, DeletedAt=NOW()
             WHERE Inventory_ID=? AND Pharmacy_ID=? AND IsDeleted=0"
        );
        $ok = $stmt->execute([$inventoryID, $pharmacyID]);

        echo ($ok && $stmt->rowCount() > 0) ? 'success' : 'not_found';

    } catch (Exception $e) {
        echo 'error: ' . $e->getMessage();
    }

} else {
    echo 'invalid_action';
}