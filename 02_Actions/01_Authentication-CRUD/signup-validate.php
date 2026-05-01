<?php
session_start();
include_once '../../00_Config/config.php';
include_once '../../02_Actions/GlobalVariables.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../03_Authentication/signup.php');
    exit();
}

// ─── COLLECT INPUT ────────────────────────────────────────────────
$fname       = trim($_POST['Fname']      ?? '');
$mname       = trim($_POST['Mname']      ?? '');
$lname       = trim($_POST['Lname']      ?? '');
$contact     = trim($_POST['ContactNo']  ?? '');
$gender      = trim($_POST['gender']     ?? '');
$birth_date  = trim($_POST['birth_date'] ?? '');
$province    = (int) ($_POST['Province'] ?? 0);
$city        = (int) ($_POST['City']     ?? 0);
$barangay    = (int) ($_POST['Barangay'] ?? 0);
$street      = trim($_POST['Street']     ?? '');
$email       = trim($_POST['email']      ?? '');
$username    = trim($_POST['username']   ?? '');
$password    = $_POST['password']        ?? '';
$confirmpass = $_POST['confirmpass']     ?? '';
$role_id     = $_SESSION['selected_role_id'] ?? null;

// ─── REDIRECT HELPER ──────────────────────────────────────────────
function redirectBackWithError($message, $step = 1) {
    $_SESSION['error']      = $message;
    $_SESSION['error_step'] = $step;
    $saved = $_POST;
    unset($saved['password'], $saved['confirmpass']);
    $_SESSION['form_data']  = $saved;
    header('Location: ../../03_Authentication/signup.php');
    exit();
}

// ─── VALIDATE ─────────────────────────────────────────────────────
if (!$fname || !$lname || !$contact || !$gender || !$birth_date ||
    !$province || !$city || !$barangay || !$street ||
    !$email || !$username || !$password || !$confirmpass || !$role_id) {
    redirectBackWithError('Please fill in all required fields.', 1);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectBackWithError('Invalid email format.', 3);
}

if (strlen($password) < 8) {
    redirectBackWithError('Password must be at least 8 characters.', 3);
}

if ($password !== $confirmpass) {
    redirectBackWithError('Passwords do not match.', 3);
}

try {
    // ─── CHECK DUPLICATES ─────────────────────────────────────────
    $stmt = $pdo->prepare("SELECT User_ID FROM `01_user_users` WHERE Email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        redirectBackWithError('That email is already registered.', 3);
    }

    $stmt = $pdo->prepare("SELECT User_ID FROM `01_user_users` WHERE Username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        redirectBackWithError('That username is already taken.', 3);
    }

    // ✅ NEW — check phone before inserting
    $stmt = $pdo->prepare("SELECT User_ID FROM `01_user_users` WHERE Phone = ?");
    $stmt->execute([$contact]);
    if ($stmt->fetch()) {
        redirectBackWithError('That contact number is already registered.', 1);
    }

    // ─── BEGIN TRANSACTION ────────────────────────────────────────
    $pdo->beginTransaction();

    // ── STEP 1: Insert address ────────────────────────────────────
    $stmt = $pdo->prepare("
        INSERT INTO `05_address_user_address` 
            (User_ID, Province_ID, City_ID, Barangay_ID, Street) 
        VALUES (NULL, ?, ?, ?, ?)
    ");
    $stmt->execute([$province, $city, $barangay, $street]);
    $address_id = $pdo->lastInsertId();

    // ── STEP 2: Insert user ───────────────────────────────────────
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        INSERT INTO `01_user_users` 
            (Username, First_name, Middle_name, Last_name, 
             Email, Phone, Address_ID, Role_ID, User_Status_ID,
             Profilepic_url, Password_hash, DateCreated, DateApproved, reset_code)
        VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, 1, NULL, ?, NOW(), NULL, NULL)
    ");
    $stmt->execute([
        $username,
        $fname,
        $mname ?: null,     // NULL if empty
        $lname,
        $email,
        $contact,
        $address_id,
        $role_id,
        $hashed_password
    ]);
    $user_id = $pdo->lastInsertId();

    // ── STEP 3: Update address with User_ID ───────────────────────
    $stmt = $pdo->prepare("
        UPDATE `05_address_user_address` 
        SET User_ID = ? 
        WHERE Address_ID = ?
    ");
    $stmt->execute([$user_id, $address_id]);

    // ── STEP 4: If pharmacist — create pending pharmacy row ───────
    if (in_array($role_id, [ROLE_PHARMACIST, ROLE_PHARMACY_OWNER])) {
        $stmt = $pdo->prepare("
            INSERT INTO `09_pharmacies` 
                (User_ID, Address_ID, Approval_ID, DateCreated)
                 VALUES (?,?, 4, NOW())
        ");
        $stmt->execute([$user_id, $address_id]);
    }

    // ─── COMMIT ───────────────────────────────────────────────────
    $pdo->commit();

    unset(
        $_SESSION['form_data'],
        $_SESSION['selected_role_id'],
        $_SESSION['selected_role_label']
    );

    $_SESSION['success'] = 'Account created successfully! Please login.';
    header('Location: ../../03_Authentication/login.php');
    exit();

} catch (PDOException $e) {
    $pdo->rollBack();
    redirectBackWithError('Database error: ' . $e->getMessage(), 1);
}
?>