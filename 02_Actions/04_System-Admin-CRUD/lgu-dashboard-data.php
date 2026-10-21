<?php
// ==============================================================================
// lgu-dashboard-data.php
// Read-only SELECT queries using PDO prepared statements for LGU Admin Dashboard
// ==============================================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . '/../../00_Config/config.php';

$lguTotalUsers = 0;
$lguNewUsersMonth = 0;
$lguTotalPharmacies = 0;
$lguPharmApproved = 0;
$lguPharmPending = 0;
$lguPharmSuspended = 0;
$lguPharmRejected = 0;
$lguActiveUsers = 0;
$lguInactiveUsers = 0;
$lguSuspendedUsers = 0;
$lguPatientsThisMonth = 0;
$lguPharmaciesWithLowStock = 0;
$lguMedicinesAvailableCity = 0;
$lguMedicinesCityShortage = 0;
$lguUsersByRole = [];
$lguMonthlyRegLabels = [];
$lguMonthlyRegData = [];
$lguPharmByBarangay = [];
$lguTopMedicines = [];
$lguRecentPharmacies = [];
$lguRecentUsers = [];
$lguDbError = null;

try {
    // 1. Total Users & New Users this month
    $stmt = $pdo->query("SELECT COUNT(*) FROM view_01_users");
    $lguTotalUsers = (int)$stmt->fetchColumn();

    $stmt = $pdo->query("
        SELECT COUNT(*) FROM view_01_users
        WHERE MONTH(DateCreated) = MONTH(CURDATE())
          AND YEAR(DateCreated) = YEAR(CURDATE())
    ");
    $lguNewUsersMonth = (int)$stmt->fetchColumn();

    // 2. Total Pharmacies + Breakdown
    $stmt = $pdo->query("
        SELECT 
            COALESCE(Approval_Status, 'Pending') AS status, 
            COUNT(*) AS cnt 
        FROM view_04_pharmacy 
        GROUP BY status
    ");
    $pharmStatusRows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
    $lguPharmApproved  = (int)($pharmStatusRows['Approved'] ?? 0);
    $lguPharmPending   = (int)($pharmStatusRows['Pending'] ?? 0);
    $lguPharmSuspended = (int)($pharmStatusRows['Suspended'] ?? 0);
    $lguPharmRejected  = (int)($pharmStatusRows['Rejected'] ?? 0);
    $lguTotalPharmacies = (int)$pdo->query("SELECT COUNT(*) FROM 09_pharmacies")->fetchColumn();

    // 3. User Status breakdown
    $stmt = $pdo->query("
        SELECT 
            COALESCE(UserStatus, 'Inactive') AS ustatus, 
            COUNT(*) AS cnt 
        FROM view_01_users 
        GROUP BY ustatus
    ");
    $userStatusRows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
    $lguActiveUsers    = (int)($userStatusRows['Active'] ?? 0);
    $lguInactiveUsers  = (int)($userStatusRows['Inactive'] ?? 0);
    $lguSuspendedUsers = (int)($userStatusRows['Suspended'] ?? 0);

    // 4. Patients registered this month
    $stmt = $pdo->query("
        SELECT COUNT(*) FROM view_01_users
        WHERE (Role LIKE '%Patient%' OR Role LIKE '%Client%')
          AND MONTH(DateCreated) = MONTH(CURDATE())
          AND YEAR(DateCreated) = YEAR(CURDATE())
    ");
    $lguPatientsThisMonth = (int)$stmt->fetchColumn();

    // 5. Pharmacies with low-stock or out-of-stock items (count)
    $stmt = $pdo->query("
        SELECT COUNT(DISTINCT i.Pharmacy_ID) 
        FROM 21_inventory i
        WHERE (i.Quantity <= 10 OR (i.Expiry_date IS NOT NULL AND i.Expiry_date <= CURDATE()))
          AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
    ");
    $lguPharmaciesWithLowStock = (int)$stmt->fetchColumn();

    // 6. Distinct medicines available across the city (stock > 0)
    $stmt = $pdo->query("
        SELECT COUNT(DISTINCT i.Medicine_ID) 
        FROM 21_inventory i
        WHERE i.Quantity > 0 
          AND (i.Expiry_date > CURDATE() OR i.Expiry_date IS NULL)
          AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
    ");
    $lguMedicinesAvailableCity = (int)$stmt->fetchColumn();

    // 7. Medicines out of stock in ALL pharmacies (city-wide shortage alert)
    $stmt = $pdo->query("
        SELECT COUNT(*) 
        FROM 12_meds_medicines m
        WHERE (m.IsDeleted = 0 OR m.IsDeleted IS NULL)
          AND NOT EXISTS (
              SELECT 1 FROM 21_inventory i 
              WHERE i.Medicine_ID = m.Medicine_ID 
                AND i.Quantity > 0 
                AND (i.Expiry_date > CURDATE() OR i.Expiry_date IS NULL)
                AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
          )
    ");
    $lguMedicinesCityShortage = (int)$stmt->fetchColumn();

    // 8. Users by Role (for doughnut chart)
    $stmt = $pdo->query("
        SELECT Role, COUNT(*) as cnt 
        FROM view_01_users 
        GROUP BY Role
    ");
    $lguUsersByRole = $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];

    // 9. New user registrations per month (last 6 months)
    $stmt = $pdo->query("
        SELECT 
            DATE_FORMAT(DateCreated, '%b %Y') as month_label,
            DATE_FORMAT(DateCreated, '%Y-%m') as ym,
            COUNT(*) as count
        FROM view_01_users
        WHERE DateCreated >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
        GROUP BY ym, month_label
        ORDER BY ym ASC
    ");
    $regRows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    for ($i = 5; $i >= 0; $i--) {
        $ym = date('Y-m', strtotime("-$i months"));
        $label = date('M Y', strtotime("-$i months"));
        $cnt = 0;
        foreach ($regRows as $r) {
            if ($r['ym'] === $ym) {
                $cnt = (int)$r['count'];
                break;
            }
        }
        $lguMonthlyRegLabels[] = $label;
        $lguMonthlyRegData[]   = $cnt;
    }

    // 10. Pharmacies by Barangay (top barangays)
    $stmt = $pdo->query("
        SELECT 
            COALESCE(NULLIF(TRIM(addr.Barangay_Name), ''), 'Unassigned') AS brgy,
            COUNT(*) AS cnt
        FROM 09_pharmacies ph
        LEFT JOIN view_02_address addr ON ph.Address_ID = addr.Address_ID
        GROUP BY brgy
        ORDER BY cnt DESC
        LIMIT 6
    ");
    $lguPharmByBarangay = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    // 11. Top medicines by availability (distinct pharmacies carrying them)
    $stmt = $pdo->query("
        SELECT 
            m.Generic_Name,
            COUNT(DISTINCT i.Pharmacy_ID) AS pharmacy_count
        FROM 21_inventory i
        JOIN view_05_medicine_master m ON i.Medicine_ID = m.Medicine_ID
        WHERE i.Quantity > 0 
          AND (i.Expiry_date > CURDATE() OR i.Expiry_date IS NULL)
          AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
        GROUP BY m.Generic_Name
        ORDER BY pharmacy_count DESC, m.Generic_Name ASC
        LIMIT 6
    ");
    $lguTopMedicines = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    // 12. Recent Pharmacy Requests (latest 5)
    $stmt = $pdo->query("
        SELECT 
            ph.Pharmacy_ID,
            COALESCE(NULLIF(ph.Pharmacy_name, ''), 'Pending Setup') AS Pharmacy_name,
            COALESCE(NULLIF(ph.Owner_name, ''), 'Registered Applicant') AS Owner_name,
            COALESCE(app.Approval_Status, 'Pending') AS Approval_Status,
            ph.DateCreated
        FROM 09_pharmacies ph
        LEFT JOIN 04_user_approval app ON ph.Approval_ID = app.Approval_ID
        ORDER BY ph.DateCreated DESC
        LIMIT 5
    ");
    $lguRecentPharmacies = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    // 13. Recent Users (latest 5)
    $stmt = $pdo->query("
        SELECT 
            User_ID,
            Full_Name,
            Role,
            UserStatus,
            DateCreated
        FROM view_01_users
        ORDER BY DateCreated DESC
        LIMIT 5
    ");
    $lguRecentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

} catch (PDOException $e) {
    $lguDbError = $e->getMessage();
}
