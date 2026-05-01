<?php


// ============================================================
//  medicine_actions.php
//  Handles: add | update | delete for the inventory view
//  Base table: 21_inventory  (plus lookup tables for add)
// ============================================================


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    return; // ← stops execution of this file, returns to the including page
}

include_once '../00_Config/config.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Guard – must be logged in
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo 'unauthorized';
    exit;
}

$action     = $_POST['action'] ?? '';
$pharmacyID = (int)($_SESSION['pharmacy_id'] ?? 8); // fallback to 8 during dev


// ============================================================
//  ADD  –  insert into lookup tables if needed, then inventory
// ============================================================
if ($action === 'add') {

    $genericName  = trim($_POST['generic_name']  ?? '');
    $brandName    = trim($_POST['brand']          ?? '');
    $categoryName = trim($_POST['category']       ?? '');
    $dosageForm   = trim($_POST['dosage_form']    ?? '');  
    $strength     = trim($_POST['strength']       ?? '');  
    $unitPrice    = (float)($_POST['unit_price']  ?? 0);
    $quantity     = (int)($_POST['qty']           ?? 0);
    $expiryDate   = $_POST['expiry_date']         ?? null;

    if (!$genericName || !$brandName || !$unitPrice || !$expiryDate) {
        echo 'missing_fields';
        exit;
    }

    try {
        $pdo->beginTransaction();

        // ── 1. Generic Name ──────────────────────────────────
        $stmt = $pdo->prepare("SELECT Gen_ID FROM 13_meds_generic_name WHERE Generic_Name = ?");
        $stmt->execute([$genericName]);
        $genID = $stmt->fetchColumn();

        if (!$genID) {
            $pdo->prepare("INSERT INTO 13_meds_generic_name (Generic_Name) VALUES (?)")->execute([$genericName]);
            $genID = (int)$pdo->lastInsertId();
        }

        // ── 2. Brand Name ────────────────────────────────────
        $stmt = $pdo->prepare("SELECT Brand_ID FROM 14_meds_brand_name WHERE Brand_Name = ?");
        $stmt->execute([$brandName]);
        $brandID = $stmt->fetchColumn();

        if (!$brandID) {
            $pdo->prepare("INSERT INTO 14_meds_brand_name (Brand_Name) VALUES (?)")->execute([$brandName]);
            $brandID = (int)$pdo->lastInsertId();
        }

        // ── 3. Category ──────────────────────────────────────
        $stmt = $pdo->prepare("SELECT Category_ID FROM 18_meds_categories WHERE Category_Name = ?");
        $stmt->execute([$categoryName]);
        $categoryID = $stmt->fetchColumn();

        if (!$categoryID) {
            $pdo->prepare("INSERT INTO 18_meds_categories (Category_Name) VALUES (?)")->execute([$categoryName]);
            $categoryID = (int)$pdo->lastInsertId();
        }

        // ── 4. Dosage Form ───────────────────────────────────
        $stmt = $pdo->prepare("SELECT DosageForm_ID FROM 15_meds_dosage_form WHERE Dosage_Form = ?");
        $stmt->execute([$dosageForm]);
        $dosageFormID = $stmt->fetchColumn();

        if (!$dosageFormID) {
            $pdo->prepare("INSERT INTO 15_meds_dosage_form (Dosage_Form) VALUES (?)")->execute([$dosageForm]);
            $dosageFormID = (int)$pdo->lastInsertId();
        }

        // ── 5. Dosage Value + Unit  (parse "500 mg" → value=500, unit=mg)
        $dosageVal  = '';
        $dosageUnit = '';

        if (preg_match('/^([\d.]+)\s*(.*)$/', $strength, $m)) {
            $dosageVal  = $m[1];
            $dosageUnit = trim($m[2]) ?: 'mg';
        } else {
            $dosageVal  = $strength;
            $dosageUnit = 'mg';
        }

        $stmt = $pdo->prepare("SELECT DosageVal_ID FROM 16_meds_dosage_value WHERE Dosage_Value = ?");
        $stmt->execute([$dosageVal]);
        $dosageValID = $stmt->fetchColumn();

        if (!$dosageValID) {
            $pdo->prepare("INSERT INTO 16_meds_dosage_value (Dosage_Value) VALUES (?)")->execute([$dosageVal]);
            $dosageValID = (int)$pdo->lastInsertId();
        }

        $stmt = $pdo->prepare("SELECT Unit_ID FROM 17_meds_dosage_unit WHERE Unit_Name = ?");
        $stmt->execute([$dosageUnit]);
        $unitID = $stmt->fetchColumn();

        if (!$unitID) {
            $pdo->prepare("INSERT INTO 17_meds_dosage_unit (Unit_Name) VALUES (?)")->execute([$dosageUnit]);
            $unitID = (int)$pdo->lastInsertId();
        }

        // ── 6. Medicine record ───────────────────────────────
        // Check if this exact medicine combination already exists
        $stmt = $pdo->prepare("SELECT Medicine_ID FROM 12_meds_medicines 
                                WHERE Gen_ID=? AND Brand_ID=? AND DosageForm_ID=? 
                                  AND DosageVal_ID=? AND Unit_ID=? AND Category_ID=?");
        $stmt->execute([$genID, $brandID, $dosageFormID, $dosageValID, $unitID, $categoryID]);
        $medicineID = $stmt->fetchColumn();

        if (!$medicineID) {
            $pdo->prepare("INSERT INTO 12_meds_medicines 
                            (Gen_ID, Brand_ID, DosageForm_ID, DosageVal_ID, Unit_ID, Category_ID)
                           VALUES (?, ?, ?, ?, ?, ?)")
                ->execute([$genID, $brandID, $dosageFormID, $dosageValID, $unitID, $categoryID]);
            $medicineID = (int)$pdo->lastInsertId();
        }

        // ── 7. Inventory row ─────────────────────────────────
        // Default price unit: 1 = pcs
        $priceUnitID = 1;

        $pdo->prepare("INSERT INTO 21_inventory 
                        (Pharmacy_ID, Medicine_ID, Quantity, Price_Unit_ID, Price, Expiry_date)
                       VALUES (?, ?, ?, ?, ?, ?)")
            ->execute([$pharmacyID, $medicineID, $quantity, $priceUnitID, $unitPrice, $expiryDate]);

        $pdo->commit();
        echo 'success';

    } catch (Exception $e) {
        $pdo->rollBack();
        echo 'error: ' . $e->getMessage();
    }


// ============================================================
//  UPDATE  –  only touches 21_inventory columns directly
// ============================================================
} elseif ($action === 'update') {

    $inventoryID = (int)($_POST['id']           ?? 0);
    $quantity    = (int)($_POST['qty']           ?? 0);
    $unitPrice   = (float)($_POST['unit_price']  ?? 0);
    $expiryDate  = $_POST['expiry_date']         ?? null;

    if (!$inventoryID) {
        echo 'missing_id';
        exit;
    }

    try {
        // Only update the columns that live directly in 21_inventory.
        // Generic name, brand, category etc. are in lookup tables — 
        // editing those is a separate concern (medicine master data).
        $stmt = $pdo->prepare("UPDATE 21_inventory 
                                SET Quantity=?, Price=?, Expiry_date=?
                                WHERE Inventory_ID=? AND Pharmacy_ID=?");
        $ok = $stmt->execute([$quantity, $unitPrice, $expiryDate, $inventoryID, $pharmacyID]);

        echo ($ok && $stmt->rowCount() > 0) ? 'success' : 'not_found';

    } catch (Exception $e) {
        echo 'error: ' . $e->getMessage();
    }


// ============================================================
//  DELETE  –  remove from 21_inventory only
// ============================================================
} elseif ($action === 'delete') {

    $inventoryID = (int)($_POST['id'] ?? 0);

    if (!$inventoryID) {
        echo 'missing_id';
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM 21_inventory 
                                WHERE Inventory_ID=? AND Pharmacy_ID=?");
        $ok = $stmt->execute([$inventoryID, $pharmacyID]);

        echo ($ok && $stmt->rowCount() > 0) ? 'success' : 'not_found';

    } catch (Exception $e) {
        echo 'error: ' . $e->getMessage();
    }


// ============================================================
//  INVALID
// ============================================================
} else {
    echo 'invalid_action';
}