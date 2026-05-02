<?php
// ============================================================
//  dropdown_data.php
//  JSON endpoint â€” called via fetch() / $.get() from JS.
//  Returns lookup table data for autocomplete inputs.
//
//  Usage:  GET dropdown_data.php?type=generic_names
//  Types:  generic_names | brand_names | categories |
//          dosage_forms  | dosage_values | dosage_units |
//          price_units   | all
// ============================================================

if (session_status() === PHP_SESSION_NONE) session_start();

include_once '../00_Config/config.php';

header('Content-Type: application/json');

// Guard
if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthorized']);
    exit;
}

$type = $_GET['type'] ?? 'all';

$data = [];

try {
    switch ($type) {

        case 'generic_names':
            $data = $pdo->query("SELECT Gen_ID AS id, Generic_Name AS label 
                                 FROM 13_meds_generic_name 
                                 ORDER BY Generic_Name ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'brand_names':
            $data = $pdo->query("SELECT Brand_ID AS id, Brand_Name AS label 
                                 FROM 14_meds_brand_name 
                                 ORDER BY Brand_Name ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'categories':
            $data = $pdo->query("SELECT Category_ID AS id, Category_Name AS label 
                                 FROM 18_meds_categories 
                                 ORDER BY Category_Name ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'dosage_forms':
            $data = $pdo->query("SELECT DosageForm_ID AS id, Dosage_Form AS label 
                                 FROM 15_meds_dosage_form 
                                 ORDER BY Dosage_Form ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'dosage_values':
            $data = $pdo->query("SELECT DosageVal_ID AS id, Dosage_Value AS label 
                                 FROM 16_meds_dosage_value 
                                 ORDER BY CAST(Dosage_Value AS DECIMAL) ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'dosage_units':
            $data = $pdo->query("SELECT Unit_ID AS id, Unit_Name AS label 
                                 FROM 17_meds_dosage_unit 
                                 ORDER BY Unit_Name ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'price_units':
            $data = $pdo->query("SELECT Price_Unit_ID AS id, Unit AS label 
                                 FROM 22_inventory_unit_price 
                                 ORDER BY Unit ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
            break;

        case 'all':
        default:
            $data = [
                'generic_names' => $pdo->query("SELECT Gen_ID AS id, Generic_Name AS label FROM 13_meds_generic_name ORDER BY Generic_Name ASC")->fetchAll(PDO::FETCH_ASSOC),
                'brand_names'   => $pdo->query("SELECT Brand_ID AS id, Brand_Name AS label FROM 14_meds_brand_name ORDER BY Brand_Name ASC")->fetchAll(PDO::FETCH_ASSOC),
                'categories'    => $pdo->query("SELECT Category_ID AS id, Category_Name AS label FROM 18_meds_categories ORDER BY Category_Name ASC")->fetchAll(PDO::FETCH_ASSOC),
                'dosage_forms'  => $pdo->query("SELECT DosageForm_ID AS id, Dosage_Form AS label FROM 15_meds_dosage_form ORDER BY Dosage_Form ASC")->fetchAll(PDO::FETCH_ASSOC),
                'dosage_values' => $pdo->query("SELECT DosageVal_ID AS id, Dosage_Value AS label FROM 16_meds_dosage_value ORDER BY CAST(Dosage_Value AS DECIMAL) ASC")->fetchAll(PDO::FETCH_ASSOC),
                'dosage_units'  => $pdo->query("SELECT Unit_ID AS id, Unit_Name AS label FROM 17_meds_dosage_unit ORDER BY Unit_Name ASC")->fetchAll(PDO::FETCH_ASSOC),
                'price_units'   => $pdo->query("SELECT Price_Unit_ID AS id, Unit AS label FROM 22_inventory_unit_price ORDER BY Unit ASC")->fetchAll(PDO::FETCH_ASSOC),
            ];
            break;
    }

    echo json_encode(['success' => true, 'data' => $data]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}