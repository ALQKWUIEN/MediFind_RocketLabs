<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pharmacyID = (int)($_SESSION['pharmacy_id'] ?? 0);

if (!$pharmacyID) {
    die("Error: Pharmacy ID not found in session. Please log in again.");
}

try {
    $stmt = $pdo->prepare(
        "SELECT 
            Inventory_ID,
            Pharmacy_ID,
            Pharmacy_name,
            Generic_Name,
            Brand_Name,
            Category_Name,
            Dosage_Form,
            Dosage,
            Quantity,
            Price,
            Price_Per,
            Expiry_date,
            Last_updated,
            Availability_Status
        FROM view_06_inventory_stocks
        WHERE Pharmacy_ID = ?
        ORDER BY Last_updated DESC"
    );
    $stmt->execute([$pharmacyID]);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>