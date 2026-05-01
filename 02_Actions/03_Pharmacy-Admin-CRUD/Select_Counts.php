<?php
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Get the ID from the session (the key must match what you used in your Login script)
        $pharmacyID = (int)($_SESSION['pharmacy_id'] ?? 0);
        if (!$pharmacyID) {
            die("Error: Pharmacy ID not found in session. Please log in again.");
        }

    
   // 1. In Stock (Available) for this pharmacy
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM view_06_inventory_stocks 
            WHERE Availability_Status COLLATE utf8mb4_unicode_ci = 'Available' 
            AND Pharmacy_ID = ?");
        $stmt->execute([$pharmacyID]);
        $inStock = $stmt->fetchColumn();

        // 2. Out of Stock for this pharmacy
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM view_06_inventory_stocks 
            WHERE Availability_Status COLLATE utf8mb4_unicode_ci = 'Out of Stock' 
            AND Pharmacy_ID = ?");
        $stmt->execute([$pharmacyID]);
        $outOfStock = $stmt->fetchColumn();

        // 3. Expired for this pharmacy
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM view_06_inventory_stocks 
            WHERE Availability_Status COLLATE utf8mb4_unicode_ci = 'Expired' 
            AND Pharmacy_ID = ?");
        $stmt->execute([$pharmacyID]);
        $expired = $stmt->fetchColumn();

        // 4. Expiring Soon - no status comparison, keep as is
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM view_06_inventory_stocks 
            WHERE Pharmacy_ID = ? 
            AND Expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 DAY)");
        $stmt->execute([$pharmacyID]);
        $expiringSoon = $stmt->fetchColumn();

        // 5. Total Medicines
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) FROM view_06_inventory_stocks 
            WHERE Pharmacy_ID = ?");
        $stmt->execute([$pharmacyID]);
        $totalMedicines = $stmt->fetchColumn();

        // 6. Total Inventory Value
        $stmt = $pdo->prepare(
            "SELECT SUM(Price * Quantity) FROM view_06_inventory_stocks 
            WHERE Pharmacy_ID = ?");
        $stmt->execute([$pharmacyID]);
        $inventoryValue = $stmt->fetchColumn() ?: 0;
?>