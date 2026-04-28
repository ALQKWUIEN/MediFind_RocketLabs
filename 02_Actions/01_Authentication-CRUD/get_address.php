<?php
// Returns JSON only — no HTML ever
header('Content-Type: application/json');

include_once '../../00_Config/config.php';


$type = $_GET['type'] ?? '';

try {
    switch ($type) {

        case 'province':
            $stmt = $pdo->query("SELECT Province_ID, Province_Name FROM 06_address_province ORDER BY Province_Name");
            echo json_encode($stmt->fetchAll());
            break;

        case 'city':
            $province_id = (int) ($_GET['province_id'] ?? 0);
            if (!$province_id) { echo json_encode([]); exit; }

            $stmt = $pdo->prepare("SELECT City_ID, City_Name FROM 07_address_city WHERE Province_ID = ? ORDER BY City_Name");
            $stmt->execute([$province_id]);
            echo json_encode($stmt->fetchAll());
            break;

        case 'barangay':
            $city_id = (int) ($_GET['city_id'] ?? 0);
            if (!$city_id) { echo json_encode([]); exit; }

            $stmt = $pdo->prepare("SELECT Barangay_ID, Barangay_Name FROM 08_address_barangay WHERE City_ID = ? ORDER BY Barangay_Name");
            $stmt->execute([$city_id]);
            echo json_encode($stmt->fetchAll());
            break;

        default:
            echo json_encode([]);
            break;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>