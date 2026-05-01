<?php
include_once '../../02_Actions/GlobalVariables.php';
include_once '../../00_Config/config.php';

// ── Guard ─────────────────────────────────────────────────────────
if (!$_SESSION['user_id']) {
    header('Location: ../../03_Authentication/login.php');
    exit;
}

$user_id       = $_SESSION['user_id'];
$pharmacy_name = trim($_POST['pharmacy_name'] ?? '');
$owner_name    = trim($_SESSION['full_name'] ?? '');
$phone         = trim($_SESSION['Phone'] ?? ''); // ← ADD THIS

// ── Validate ───────────────────────────────────────────────────────
if (empty($pharmacy_name)) {
    $_SESSION['error'] = 'Pharmacy name is required.';
    header('Location: ../../05_PharmacyAdmin/00_RequestAccess.php');
    exit;
}

// ── Check if pharmacy record already exists for this user ──────────
$stmt = $pdo->prepare("SELECT Pharmacy_ID, Approval_ID FROM 09_pharmacies WHERE User_ID = ? LIMIT 1");
$stmt->execute([$user_id]);
$existing = $stmt->fetch();

if ($existing) {
    $update = $pdo->prepare("
        UPDATE 09_pharmacies 
        SET Pharmacy_name = ?, Owner_name = ?, Phone = ?, Approval_ID = 1 
        WHERE User_ID = ?
    ");
    $update->execute([$pharmacy_name, $owner_name, $phone, $user_id]);
} else {
    $insert = $pdo->prepare("
        INSERT INTO 09_pharmacies (Pharmacy_name, Owner_name, Phone, User_ID, Approval_ID, DateCreated)
        VALUES (?, ?, ?, ?, 1, NOW())
    ");
    $insert->execute([$pharmacy_name, $owner_name, $phone, $user_id]);
}

// ── Update session ─────────────────────────────────────────────────
$_SESSION['Pharmacy_Approval'] = 1;
$_SESSION['pharmacy_name']     = $pharmacy_name;

// ── Redirect back to setup page ────────────────────────────────────
header('Location: ../../05_PharmacyAdmin/00_RequestAccess.php');
exit;