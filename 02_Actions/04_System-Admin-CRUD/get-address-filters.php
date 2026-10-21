<?php
// ==============================================================================
// 02_Actions/04_System-Admin-CRUD/get-address-filters.php
// Read-only cascading address queries using PDO prepared statements
// ==============================================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../00_Config/config.php';

// Helper function to fetch provinces for SSR
function getProvincesList($pdo = null) {
    if (!$pdo && isset($GLOBALS['pdo'])) {
        $pdo = $GLOBALS['pdo'];
    }
    if (!$pdo) {
        return [];
    }
    try {
        $stmt = $pdo->query("SELECT Province_ID, Province_Name FROM `06_address_province` ORDER BY Province_Name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        return [];
    }
}

// If invoked as an AJAX endpoint
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'] ?? '';

    try {
        switch ($action) {
            case 'provinces':
                $stmt = $pdo->prepare("SELECT Province_ID, Province_Name FROM `06_address_province` ORDER BY Province_Name ASC");
                $stmt->execute();
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
                break;

            case 'cities':
                $provinceId = (int)($_GET['province_id'] ?? 0);
                if ($provinceId <= 0) {
                    echo json_encode([]);
                    exit;
                }
                $stmt = $pdo->prepare("SELECT City_ID, City_Name FROM `07_address_city` WHERE Province_ID = ? ORDER BY City_Name ASC");
                $stmt->execute([$provinceId]);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
                break;

            case 'barangays':
                $cityId = (int)($_GET['city_id'] ?? 0);
                if ($cityId <= 0) {
                    echo json_encode([]);
                    exit;
                }
                $stmt = $pdo->prepare("SELECT Barangay_ID, Barangay_Name FROM `08_address_barangay` WHERE City_ID = ? ORDER BY Barangay_Name ASC");
                $stmt->execute([$cityId]);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
                break;

            default:
                echo json_encode([]);
                break;
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
    exit;
}
