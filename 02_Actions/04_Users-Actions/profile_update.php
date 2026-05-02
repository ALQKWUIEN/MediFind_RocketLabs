<?php
require_once '../../02_Actions/GlobalVariables.php';
require_once '../../00_Config/config.php';

if (!$_SESSION['user_id']) {
    http_response_code(403);
    exit;
}

$user_id = $_SESSION['user_id'];

try {

    // ─── 1. Build user UPDATE — password only included if provided ─────────
    $first_name  = trim($_POST['first_name']  ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name   = trim($_POST['last_name']   ?? '');
    $phone       = trim($_POST['phone']       ?? '');
    $email       = trim($_POST['email']       ?? '');
    $gender      = trim($_POST['gender']      ?? '');
    $birth_date  = trim($_POST['birth_date']  ?? '');
    $street      = trim($_POST['street']      ?? '');
    $province    = trim($_POST['province']    ?? '');
    $city        = trim($_POST['city']        ?? '');
    $barangay    = trim($_POST['barangay']    ?? '');

    $new_password = trim($_POST['password'] ?? '');

    // ─── 2. Update personal info (matches columns from view_01_users / login) ─
    if (!empty($new_password)) {
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt1 = $pdo->prepare("
            UPDATE `01_user_users`
            SET
                First_name    = ?,
                Middle_name   = ?,
                Last_name     = ?,
                Phone         = ?,
                Email         = ?,
                Gender        = ?,
                Birthdate     = ?,
                Password_hash = ?
            WHERE User_ID = ?
        ");
        $stmt1->execute([
            $first_name,
            $middle_name,
            $last_name,
            $phone,
            $email,
            $gender,
            $birth_date,
            $password_hash,
            $user_id
        ]);
    } else {
        $stmt1 = $pdo->prepare("
            UPDATE `01_user_users`
            SET
                First_name  = ?,
                Middle_name = ?,
                Last_name   = ?,
                Phone       = ?,
                Email       = ?,
                Gender      = ?,
                Birthdate   = ?
            WHERE User_ID = ?
        ");
        $stmt1->execute([
            $first_name,
            $middle_name,
            $last_name,
            $phone,
            $email,
            $gender,
            $birth_date,
            $user_id
        ]);
    }

    // ─── 3. Resolve Province_ID ────────────────────────────────────────────
    $province_stmt = $pdo->prepare("
        SELECT Province_ID FROM `06_address_province`
        WHERE Province_Name = ?
        LIMIT 1
    ");
    $province_stmt->execute([$province]);
    $province_id = $province_stmt->fetchColumn();

    // ─── 4. Resolve City_ID ────────────────────────────────────────────────
    $city_stmt = $pdo->prepare("
        SELECT City_ID FROM `07_address_city`
        WHERE City_Name = ? AND Province_ID = ?
        LIMIT 1
    ");
    $city_stmt->execute([$city, $province_id]);
    $city_id = $city_stmt->fetchColumn();

    // ─── 5. Resolve Barangay_ID ────────────────────────────────────────────
    $barangay_stmt = $pdo->prepare("
        SELECT Barangay_ID FROM `08_address_barangay`
        WHERE Barangay_Name = ? AND City_ID = ?
        LIMIT 1
    ");
    $barangay_stmt->execute([$barangay, $city_id]);
    $barangay_id = $barangay_stmt->fetchColumn();

    // ─── 6. Update address — safe UPDATE with JOIN instead of subquery ─────
    // First check if address row exists for this user
    $check_addr = $pdo->prepare("
        SELECT Address_ID FROM `05_address_user_address`
        WHERE User_ID = ?
        LIMIT 1
    ");
    $check_addr->execute([$user_id]);
    $address_id = $check_addr->fetchColumn();

    if ($address_id) {
        // Row exists — update it
        $stmt2 = $pdo->prepare("
            UPDATE `05_address_user_address`
            SET
                Province_ID = ?,
                City_ID     = ?,
                Barangay_ID = ?,
                Street      = ?
            WHERE Address_ID = ?
        ");
        $stmt2->execute([
            $province_id,
            $city_id,
            $barangay_id,
            $street,
            $address_id
        ]);
    } else {
        // No address row yet — insert one
        $stmt2 = $pdo->prepare("
            INSERT INTO `05_address_user_address`
                (User_ID, Province_ID, City_ID, Barangay_ID, Street)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt2->execute([
            $user_id,
            $province_id,
            $city_id,
            $barangay_id,
            $street
        ]);
    }

    // ─── 7. Refresh session — mirrors exactly what login_action.php stores ─
    $_SESSION['fname']         = $first_name;
    $_SESSION['mname']         = $middle_name;
    $_SESSION['lname']         = $last_name;
    $_SESSION['full_name']     = $first_name . ' ' . $middle_name . ' ' . $last_name;
    $_SESSION['Email']         = $email;
    $_SESSION['Phone']         = $phone;
    $_SESSION['Gender']        = $gender;
    $_SESSION['Birthdate']     = $birth_date;
    $_SESSION['Street']        = $street;
    $_SESSION['Barangay_Name'] = $barangay;
    $_SESSION['City_Name']     = $city;
    $_SESSION['Province_Name'] = $province;

    $_SESSION['success'] = "Profile updated successfully.";
    header('Location: ../../04_User/05_Profile.php?saved=1');
    exit;
} catch (PDOException $e) {
    $_SESSION['error'] = "Update failed: " . $e->getMessage();
    header('Location: ../../04_User/05_Profile.php');
    exit;
}
