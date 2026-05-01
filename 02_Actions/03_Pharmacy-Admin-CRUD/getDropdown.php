<?php
// ============================================================
//  get_dropdowns.php
//  Fetches all reusable lookup data for inventory modal dropdowns.
//  Include this file on any page that needs the modal form.
//  All data comes from shared lookup tables — no duplicates.
// ============================================================

if (session_status() === PHP_SESSION_NONE) session_start();

// $pdo must already be available (included via config.php before this file)

// ── Generic Names ────────────────────────────────────────────
$genericNames = $pdo->query("SELECT Gen_ID, Generic_Name FROM 13_meds_generic_name ORDER BY Generic_Name ASC")
                    ->fetchAll(PDO::FETCH_ASSOC);

// ── Brand Names ──────────────────────────────────────────────
$brandNames = $pdo->query("SELECT Brand_ID, Brand_Name FROM 14_meds_brand_name ORDER BY Brand_Name ASC")
                  ->fetchAll(PDO::FETCH_ASSOC);

// ── Categories ───────────────────────────────────────────────
$categories = $pdo->query("SELECT Category_ID, Category_Name FROM 18_meds_categories ORDER BY Category_Name ASC")
                  ->fetchAll(PDO::FETCH_ASSOC);

// ── Dosage Forms ─────────────────────────────────────────────
$dosageForms = $pdo->query("SELECT DosageForm_ID, Dosage_Form FROM 15_meds_dosage_form ORDER BY Dosage_Form ASC")
                   ->fetchAll(PDO::FETCH_ASSOC);

// ── Dosage Values ────────────────────────────────────────────
$dosageValues = $pdo->query("SELECT DosageVal_ID, Dosage_Value FROM 16_meds_dosage_value ORDER BY CAST(Dosage_Value AS DECIMAL) ASC")
                    ->fetchAll(PDO::FETCH_ASSOC);

// ── Dosage Units ─────────────────────────────────────────────
$dosageUnits = $pdo->query("SELECT Unit_ID, Unit_Name FROM 17_meds_dosage_unit ORDER BY Unit_Name ASC")
                   ->fetchAll(PDO::FETCH_ASSOC);

// ── Price Units ──────────────────────────────────────────────
$priceUnits = $pdo->query("SELECT Price_Unit_ID, Unit FROM 22_inventory_unit_price ORDER BY Unit ASC")
                  ->fetchAll(PDO::FETCH_ASSOC);