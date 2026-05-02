<?php
// ============================================================
//  fetch_dropdowns.php
//  Returns all lookup table data for modal dropdowns
// ============================================================
if (session_status() === PHP_SESSION_NONE) session_start();

include_once '../00_Config/config.php';

try {
    $generics      = $pdo->query("SELECT Gen_ID, Generic_Name FROM 13_meds_generic_name ORDER BY Generic_Name")->fetchAll(PDO::FETCH_ASSOC);
    $brands        = $pdo->query("SELECT Brand_ID, Brand_Name FROM 14_meds_brand_name ORDER BY Brand_Name")->fetchAll(PDO::FETCH_ASSOC);
    $categories    = $pdo->query("SELECT Category_ID, Category_Name FROM 18_meds_categories ORDER BY Category_Name")->fetchAll(PDO::FETCH_ASSOC);
    $manufacturers = $pdo->query("SELECT Manufacturer_ID, Manufacturer_Name FROM 19_meds_manufacturer ORDER BY Manufacturer_Name")->fetchAll(PDO::FETCH_ASSOC);
    $dosageForms   = $pdo->query("SELECT DosageForm_ID, Dosage_Form FROM 15_meds_dosage_form ORDER BY Dosage_Form")->fetchAll(PDO::FETCH_ASSOC);
    $dosageValues  = $pdo->query("SELECT DosageVal_ID, Dosage_Value FROM 16_meds_dosage_value ORDER BY CAST(Dosage_Value AS UNSIGNED)")->fetchAll(PDO::FETCH_ASSOC);
    $dosageUnits   = $pdo->query("SELECT Unit_ID, Unit_Name FROM 17_meds_dosage_unit ORDER BY Unit_Name")->fetchAll(PDO::FETCH_ASSOC);
    $rxOptions     = $pdo->query("SELECT Require_rx_ID, Requirement_Status FROM 20_meds_requires_rx")->fetchAll(PDO::FETCH_ASSOC);
    $priceUnits    = $pdo->query("SELECT Price_Unit_ID, Unit FROM 22_inventory_unit_price")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Dropdown fetch failed: " . $e->getMessage());
}
?>