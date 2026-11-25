<?php
// 02_Actions/04_System-Admin-CRUD/get_alerts.php
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../00_Config/config.php';

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'alerts' => []]);
    exit;
}

$alerts = [];

try {
    // 1. Pending Pharmacy Applications
    $pharmStmt = $pdo->query("
        SELECT Pharmacy_ID, Pharmacy_name, Owner_name, City_Name, DateCreated
        FROM view_04_pharmacy
        WHERE Approval_Status = 'Pending'
        ORDER BY DateCreated DESC
        LIMIT 5
    ");
    foreach ($pharmStmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
        $loc = !empty($p['City_Name']) ? ' (' . $p['City_Name'] . ')' : '';
        $alerts[] = [
            'id' => 'sys_pharm_' . $p['Pharmacy_ID'],
            'icon' => 'fas fa-clinic-medical',
            'color' => '#d97706',
            'text' => '<strong>Pending Approval:</strong> ' . htmlspecialchars($p['Pharmacy_name'] . $loc) . ' awaiting LGU verification.',
            'time' => !empty($p['DateCreated']) ? date('M d, Y', strtotime($p['DateCreated'])) : 'Pending',
            'unread' => true,
            'actionLabel' => 'Review',
            'actionHref' => '../06_SystemAdmin/03_Pharmacies.php'
        ];
    }

    // 2. City-Wide Medicine Shortage / Low-Stock Summary
    $shortCount = (int) $pdo->query("
        SELECT COUNT(*)
        FROM view_06_inventory_stocks
        WHERE (IsDeleted = 0 OR IsDeleted IS NULL)
          AND Availability_Status IN ('Low Stock', 'Out of Stock')
    ")->fetchColumn();

    if ($shortCount > 0) {
        $alerts[] = [
            'id' => 'sys_shortage_summary',
            'icon' => 'fas fa-exclamation-triangle',
            'color' => '#dc2626',
            'text' => "<strong>City-Wide Stock Alert:</strong> {$shortCount} medicine batches across partner pharmacies are low or out of stock.",
            'time' => 'Live monitor',
            'unread' => true,
            'actionLabel' => 'View Report',
            'actionHref' => '../06_SystemAdmin/04_Reports.php'
        ];
    }

    // 3. Recent User Registrations
    $userStmt = $pdo->query("
        SELECT User_ID, Full_Name, Role, DateCreated
        FROM view_01_users
        ORDER BY DateCreated DESC
        LIMIT 3
    ");
    foreach ($userStmt->fetchAll(PDO::FETCH_ASSOC) as $u) {
        $alerts[] = [
            'id' => 'sys_user_' . $u['User_ID'],
            'icon' => 'fas fa-user-check',
            'color' => '#1d9e75',
            'text' => '<strong>User Registry:</strong> ' . htmlspecialchars($u['Full_Name']) . ' (' . htmlspecialchars($u['Role']) . ').',
            'time' => !empty($u['DateCreated']) ? date('M d, Y', strtotime($u['DateCreated'])) : 'Recent',
            'unread' => false,
            'actionLabel' => 'Manage',
            'actionHref' => '../06_SystemAdmin/02_Manage-Users.php'
        ];
    }

    echo json_encode(['success' => true, 'alerts' => $alerts]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'alerts' => []]);
}

