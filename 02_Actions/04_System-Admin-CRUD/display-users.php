<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../../00_Config/config.php';

// ── Handle Actions (Update / Delete) ──────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'update') {
        $id     = (int) $_POST['id'];
        $status = trim($_POST['status'] ?? '');

        $statusMap = [
            'Active'    => 1,
            'Inactive'  => 2,
            'Suspended' => 3,
        ];

        if (!isset($statusMap[$status])) {
            echo 'invalid status';
            exit;
        }

        try {
            // ── Update user status ─────────────────────────────
            $stmt = $pdo->prepare("
                UPDATE 01_user_users 
                SET User_Status_ID = ? 
                WHERE User_ID = ?
            ");
            $stmt->execute([$statusMap[$status], $id]);

            // ── If Suspended, suspend their pharmacy too ───────
            if ($status === 'Suspended') {
                $stmt2 = $pdo->prepare("
                    UPDATE 09_pharmacies 
                    SET Approval_ID = 5 
                    WHERE User_ID = ?
                ");
                $stmt2->execute([$id]);
            }

            echo 'success';

        } catch (PDOException $e) {
            echo 'error';
        }
        exit;
    }

    if ($action === 'delete') {
        $id = (int) $_POST['id'];

        try {
            $stmt = $pdo->prepare("
                UPDATE 01_user_users 
                SET IsDeleted = 1, DeletedAt = NOW() 
                WHERE User_ID = ?
            ");
            $stmt->execute([$id]);
            echo 'success';
        } catch (PDOException $e) {
            echo 'error';
        }
        exit;
    }
}

// ── Fetch Users ────────────────────────────────────────────────────
try {
    $stmt = $pdo->query(
        "SELECT 
            u.User_ID,
            u.Role_ID,
            u.Username,
            u.First_name,
            u.Middle_name,
            u.Last_name,
            CONCAT(u.First_name, ' ', u.Last_name) AS Full_Name,
            u.Email,
            u.Phone,
            u.Role,
            u.UserStatus,
            u.Profilepic_url,
            u.DateCreated,
            u.DateApproved,
            a.Address_ID,
            a.Street,
            a.Barangay_Name,
            a.City_Name,
            a.Province_Name,
            a.Full_Address
        FROM view_01_users u
        LEFT JOIN view_02_address a ON u.User_ID = a.User_ID
        ORDER BY u.DateCreated DESC"
    );
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>