<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure ID is an integer
$pharmacyID = (int)($_SESSION['pharmacy_id'] ?? 0);

if (!$pharmacyID) {
    die("Error: Pharmacy ID not found in session. Please log in again.");
}

    $stmt = $pdo->prepare("SELECT * FROM view_06_inventory_stocks WHERE Pharmacy_ID = ?");
    $stmt->execute([$pharmacyID]);

?>