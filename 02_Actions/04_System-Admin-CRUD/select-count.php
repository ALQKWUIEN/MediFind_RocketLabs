<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 0. Total Users
$stmt = $pdo->query("SELECT COUNT(*) FROM view_01_users");
$totalUsers = $stmt->fetchColumn();

// 1. Active Users
$stmt = $pdo->query(
    "SELECT COUNT(*) FROM view_01_users 
     WHERE UserStatus COLLATE utf8mb4_unicode_ci = 'Active'"
);
$totalActive = $stmt->fetchColumn();

// 2. Total Pharmacies
$stmt = $pdo->query("SELECT COUNT(*) FROM view_04_pharmacy");
$totalPharmacies = $stmt->fetchColumn();

// 3. Pending Pharmacies
$stmt = $pdo->query(
    "SELECT COUNT(*) FROM view_04_pharmacy 
     WHERE Approval_Status COLLATE utf8mb4_unicode_ci = 'Pending'"
);
$pendingPharmacies = $stmt->fetchColumn();

// 4. Approved Pharmacies
$stmt = $pdo->query(
    "SELECT COUNT(*) FROM view_04_pharmacy 
     WHERE Approval_Status COLLATE utf8mb4_unicode_ci = 'Approved'"
);
$approvedPharmacies = $stmt->fetchColumn();

// 5. Suspended Accounts
$stmt = $pdo->query(
    "SELECT COUNT(*) FROM view_04_pharmacy
     WHERE Approval_Status COLLATE utf8mb4_unicode_ci = 'Suspended'"
);
$suspendedAccounts = $stmt->fetchColumn();



// 06. Count Inventory
$stmt = $pdo->query(
    "SELECT COUNT(*) FROM 21_inventory"
);
$totalInventory = $stmt->fetchColumn();
?>

