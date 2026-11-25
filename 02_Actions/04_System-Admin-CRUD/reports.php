<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
include_once __DIR__ . '/../../00_Config/config.php';

// ── Fetch: User counts by role ─────────────────────────────────────
$stmtRoles = $pdo->query("
    SELECT Role COLLATE utf8mb4_unicode_ci AS Role, COUNT(*) as count
    FROM view_01_users
    GROUP BY Role COLLATE utf8mb4_unicode_ci
");
$roleCounts = $stmtRoles->fetchAll(PDO::FETCH_KEY_PAIR);

$totalUsers = array_sum($roleCounts);
$totalPatients = $roleCounts['Patient/Client'] ?? 0;
$totalPharmacist = $roleCounts['Pharmacy Admin'] ?? 0;
$totalOwner = $roleCounts['Pharmacy Owner'] ?? 0;
$totalAdmin = $roleCounts['System Admin'] ?? 0;

// ── Fetch: Pharmacy counts by approval status ──────────────────────
$stmtPharm = $pdo->query("
    SELECT Approval_Status COLLATE utf8mb4_unicode_ci AS Approval_Status, COUNT(*) as count
    FROM view_04_pharmacy
    GROUP BY Approval_Status COLLATE utf8mb4_unicode_ci
");
$pharmCounts = $stmtPharm->fetchAll(PDO::FETCH_KEY_PAIR);
$totalActive = $pharmCounts['Approved'] ?? 0;
$totalPending = $pharmCounts['Pending'] ?? 0;

// ── Fetch: Expired stock count ─────────────────────────────────────
$stmtExp = $pdo->query("
    SELECT COUNT(*) FROM view_06_inventory_stocks
    WHERE Availability_Status COLLATE utf8mb4_unicode_ci = 'Expired'
");
$totalExpired = $stmtExp->fetchColumn();

// ── Fetch: New users this month ────────────────────────────────────
$stmtNew = $pdo->query("
    SELECT COUNT(*) FROM view_01_users
    WHERE MONTH(DateCreated) = MONTH(CURDATE())
    AND YEAR(DateCreated) = YEAR(CURDATE())
");
$newUsersMonth = $stmtNew->fetchColumn();

// ── Fetch: Pending pharmacies ──────────────────────────────────────
$stmtPending = $pdo->query("
    SELECT Pharmacy_name, Owner_name, DateCreated
    FROM view_04_pharmacy
    WHERE Approval_Status COLLATE utf8mb4_unicode_ci = 'Pending'
    ORDER BY DateCreated DESC
    LIMIT 5
");
$pendingPharmacies = $stmtPending->fetchAll();

// ── Fetch: Suspended pharmacies ────────────────────────────────────
$stmtSuspended = $pdo->query("
    SELECT Pharmacy_name, Owner_name, DateCreated
    FROM view_04_pharmacy
    WHERE Approval_Status COLLATE utf8mb4_unicode_ci = 'Suspended'
    ORDER BY DateCreated DESC
    LIMIT 5
");
$suspendedPharmacies = $stmtSuspended->fetchAll();

// ── Fetch: Users with address ──────────────────────────────────────
$stmtAddr = $pdo->query("
    SELECT u.Full_Name, u.Role, u.UserStatus,
           a.Street, a.Barangay_Name, a.City_Name, a.Province_Name, a.Full_Address
    FROM view_01_users u
    LEFT JOIN view_03_user_address a ON u.User_ID = a.User_ID
    WHERE a.Full_Address IS NOT NULL
      AND a.Full_Address COLLATE utf8mb4_unicode_ci != ''
    ORDER BY u.User_ID DESC
    LIMIT 5
");
$usersWithAddress = $stmtAddr->fetchAll();

// ── Fetch: Full data for CSV export ───────────────────────────────
$allUsers = $pdo->query("
    SELECT Full_Name, Email, Phone, Role, UserStatus, DateCreated
    FROM view_01_users
    ORDER BY DateCreated DESC
")->fetchAll();

$allPharmacies = $pdo->query("
    SELECT Pharmacy_name, Owner_name, Owner_Email, Phone, City_Name, Approval_Status, DateCreated
    FROM view_04_pharmacy
    ORDER BY DateCreated DESC
")->fetchAll();

$allUsersAddr = $pdo->query("
    SELECT u.Full_Name, u.Role, a.Full_Address
    FROM view_01_users u
    LEFT JOIN view_03_user_address a ON u.User_ID = a.User_ID
    WHERE a.Full_Address IS NOT NULL
")->fetchAll();

$pharmacyID = (int) ($_SESSION['pharmacy_id'] ?? 0);
if ($pharmacyID > 0) {
    $stmtInv = $pdo->prepare("
        SELECT Generic_Name, Brand_Name, Category_Name, Dosage_Form,
               Dosage, Quantity, Price, Price_Per,
               Expiry_date, Last_updated, Availability_Status
        FROM view_06_inventory_stocks
        WHERE Pharmacy_ID = ?
    ");
    $stmtInv->execute([$pharmacyID]);
    $allInventory = $stmtInv->fetchAll();
} else {
    $allInventory = $pdo->query("
        SELECT Generic_Name, Brand_Name, Category_Name, Dosage_Form,
               Dosage, Quantity, Price, Price_Per,
               Expiry_date, Last_updated, Availability_Status
        FROM view_06_inventory_stocks
    ")->fetchAll();
}
?>