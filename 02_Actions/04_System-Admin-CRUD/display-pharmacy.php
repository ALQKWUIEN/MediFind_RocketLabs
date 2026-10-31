<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../00_Config/config.php';

// ── Handle Actions (Update / Delete) ──────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'update') {
        $id = (int) $_POST['id'];
        $status = trim($_POST['status'] ?? '');

        $statusMap = [
            'Pending' => 1,
            'Approved' => 2,
            'Rejected' => 3,
            'Suspended' => 5,
        ];

        if (!isset($statusMap[$status])) {
            echo 'invalid status';
            exit;
        }

        $approval_id = $statusMap[$status];
        $date_approved = ($approval_id == 2) ? date('Y-m-d H:i:s') : null;

        try {
            $stmt = $pdo->prepare("
                UPDATE 09_pharmacies 
                SET Approval_ID = ?, Date_Approved = ?
                WHERE Pharmacy_ID = ?
            ");
            $stmt->execute([$approval_id, $date_approved, $id]);

            // ── Send email notification ────────────────────────
            if (in_array($status, ['Approved', 'Rejected', 'Suspended'])) {

                $fetch = $pdo->prepare("
                    SELECT ph.Pharmacy_name, ph.Owner_name, u.Email
                    FROM 09_pharmacies ph
                    JOIN 01_user_users u ON ph.User_ID = u.User_ID
                    WHERE ph.Pharmacy_ID = ?
                ");
                $fetch->execute([$id]);
                $pharmacy = $fetch->fetch();

                if ($pharmacy) {
                    $emailBody = pharmacyApprovalEmail(
                        $pharmacy['Owner_name'],
                        $pharmacy['Pharmacy_name'],
                        $status
                    );

                    sendEmail(
                        $pharmacy['Email'],
                        $pharmacy['Owner_name'],
                        "MediFind — Your Pharmacy Application has been {$status}",
                        $emailBody
                    );
                }
            }

            echo 'success';

        } catch (PDOException $e) {
            echo 'error';
        }
        exit;
    }
}
// ── Fetch Pharmacies ───────────────────────────────────────────────
try {
    $stmt = $pdo->query(
        "SELECT
            Pharmacy_ID,
            Pharmacy_name,
            Owner_name,
            Phone,
            Owner_Email,
            Street,
            Barangay_Name,
            City_Name,
            Province_Name,
            Logo_URL,
            Pic_URL,
            Approval_Status,
            DateCreated,
            Date_Approved,
            CONCAT_WS(', ',
                NULLIF(Street, ''),
                NULLIF(Barangay_Name, ''),
                NULLIF(City_Name, ''),
                NULLIF(Province_Name, '')
            ) AS Full_Address
        FROM view_04_pharmacy
        ORDER BY DateCreated DESC"
    );

    // Summary Statistics for LGU Overview
    $totalPharmaciesCount = (int) $pdo->query("SELECT COUNT(*) FROM view_04_pharmacy")->fetchColumn();
    $approvedCount = (int) $pdo->query("SELECT COUNT(*) FROM view_04_pharmacy WHERE Approval_Status = 'Approved'")->fetchColumn();
    $pendingCount = (int) $pdo->query("SELECT COUNT(*) FROM view_04_pharmacy WHERE Approval_Status = 'Pending'")->fetchColumn();
    $rejectedCount = (int) $pdo->query("SELECT COUNT(*) FROM view_04_pharmacy WHERE Approval_Status = 'Rejected'")->fetchColumn();
    $barangaysCoveredCount = (int) $pdo->query("SELECT COUNT(DISTINCT NULLIF(Barangay_Name, '')) FROM view_04_pharmacy WHERE Approval_Status = 'Approved'")->fetchColumn();

    // Distinct Barangays for filter dropdown
    $stmtBrgy = $pdo->query("SELECT DISTINCT Barangay_Name FROM view_04_pharmacy WHERE Barangay_Name IS NOT NULL AND TRIM(Barangay_Name) != '' ORDER BY Barangay_Name ASC");
    $allBarangaysList = $stmtBrgy ? $stmtBrgy->fetchAll(PDO::FETCH_COLUMN) : [];

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>