<?php
// 05_PharmacyAdmin/inventory_alert_helper.php
// Shared helper for global low-stock threshold schema & crossing-threshold alerts.

function mfEnsureInventoryAlertSchema(PDO $pdo): void
{
    static $ensured = false;
    if ($ensured) {
        return;
    }
    $ensured = true;

    try {
        $colCheck = $pdo->query("SHOW COLUMNS FROM 09_pharmacies LIKE 'Default_Low_Stock_Threshold'");
        if (!$colCheck || !$colCheck->fetch()) {
            $pdo->exec("ALTER TABLE 09_pharmacies ADD COLUMN Default_Low_Stock_Threshold INT(11) NOT NULL DEFAULT 10");
        }
    } catch (Exception $e) {
        // Ignore if already exists
    }

    try {
        $invCol = $pdo->query("SHOW COLUMNS FROM 21_inventory LIKE 'Low_Stock_Threshold'")->fetch(PDO::FETCH_ASSOC);
        if ($invCol && strtoupper($invCol['Null'] ?? '') === 'NO') {
            $pdo->exec("ALTER TABLE 21_inventory MODIFY COLUMN Low_Stock_Threshold INT(11) NULL DEFAULT NULL");
        }
    } catch (Exception $e) {
        // Ignore if already nullable
    }

    try {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS 28_pharmacy_alerts (
                Alert_ID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                Pharmacy_ID INT(11) NOT NULL,
                Inventory_ID INT(11) NOT NULL,
                Medicine_Name VARCHAR(160) NOT NULL,
                Brand_Name VARCHAR(120) DEFAULT NULL,
                Current_Qty INT(11) NOT NULL DEFAULT 0,
                Threshold_Used INT(11) NOT NULL DEFAULT 10,
                Alert_Type VARCHAR(24) NOT NULL DEFAULT 'low_stock',
                Is_Read TINYINT(1) NOT NULL DEFAULT 0,
                Created_At DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_pharm_read (Pharmacy_ID, Is_Read, Created_At)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    } catch (Exception $e) {
        // Ignore
    }
}

function mfGetGlobalLowStockThreshold(PDO $pdo, int $pharmacyId): int
{
    mfEnsureInventoryAlertSchema($pdo);
    $stmt = $pdo->prepare("SELECT COALESCE(Default_Low_Stock_Threshold, 10) FROM 09_pharmacies WHERE Pharmacy_ID = ? LIMIT 1");
    $stmt->execute([$pharmacyId]);
    $val = $stmt->fetchColumn();
    return $val !== false ? max(1, (int) $val) : 10;
}

/**
 * Triggers a low-stock or out-of-stock alert ONLY when crossing the threshold
 * (e.g. 11 -> 10, or 1 -> 0) so repeated sales do not spam duplicate alerts.
 */
function mfCheckAndRecordStockAlert(PDO $pdo, int $pharmacyId, int $inventoryId, int $oldQty, int $newQty): ?array
{
    mfEnsureInventoryAlertSchema($pdo);

    $stmt = $pdo->prepare("
        SELECT
            i.Inventory_ID,
            COALESCE(i.Low_Stock_Threshold, p.Default_Low_Stock_Threshold, 10) AS Effective_Threshold,
            COALESCE(gn.Generic_Name, 'Medicine') AS Generic_Name,
            COALESCE(bn.Brand_Name, '') AS Brand_Name
        FROM 21_inventory i
        INNER JOIN 09_pharmacies p ON i.Pharmacy_ID = p.Pharmacy_ID
        INNER JOIN 12_meds_medicines med ON i.Medicine_ID = med.Medicine_ID
        LEFT JOIN 13_meds_generic_name gn ON med.Gen_ID = gn.Gen_ID
        LEFT JOIN 14_meds_brand_name bn ON med.Brand_ID = bn.Brand_ID
        WHERE i.Inventory_ID = ? AND i.Pharmacy_ID = ?
        LIMIT 1
    ");
    $stmt->execute([$inventoryId, $pharmacyId]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$info) {
        return null;
    }

    $threshold = max(1, (int) $info['Effective_Threshold']);

    // If restocked above threshold, resolve unread alerts for this item
    if ($newQty > $threshold) {
        $pdo->prepare("UPDATE 28_pharmacy_alerts SET Is_Read = 1 WHERE Pharmacy_ID = ? AND Inventory_ID = ? AND Is_Read = 0")
            ->execute([$pharmacyId, $inventoryId]);
        return null;
    }

    $alertType = null;
    if ($oldQty > 0 && $newQty <= 0) {
        $alertType = 'out_of_stock';
    } elseif ($oldQty > $threshold && $newQty <= $threshold && $newQty > 0) {
        $alertType = 'low_stock';
    }

    if ($alertType === null) {
        return null;
    }

    $ins = $pdo->prepare("
        INSERT INTO 28_pharmacy_alerts
            (Pharmacy_ID, Inventory_ID, Medicine_Name, Brand_Name, Current_Qty, Threshold_Used, Alert_Type, Is_Read, Created_At)
        VALUES (?, ?, ?, ?, ?, ?, ?, 0, NOW())
    ");
    $ins->execute([
        $pharmacyId,
        $inventoryId,
        $info['Generic_Name'],
        $info['Brand_Name'],
        $newQty,
        $threshold,
        $alertType
    ]);

    return [
        'alert_id' => (int) $pdo->lastInsertId(),
        'inventory_id' => $inventoryId,
        'medicine_name' => $info['Generic_Name'],
        'brand_name' => $info['Brand_Name'],
        'current_qty' => $newQty,
        'threshold' => $threshold,
        'alert_type' => $alertType
    ];
}

