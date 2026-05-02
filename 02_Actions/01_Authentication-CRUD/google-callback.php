<?php
include_once '../../02_Actions/GlobalVariables.php';
include_once '../../00_Config/config.php';
require_once '../../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT']);

if (!isset($_GET['code'])) {
    $_SESSION['error'] = 'Invalid login attempt.';
    header('Location: ../../03_Authentication/login.php');
    exit();
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

if (isset($token['error'])) {
    $_SESSION['error'] = 'Google login failed. Please try again.';
    header('Location: ../../03_Authentication/login.php');
    exit();
}

$client->setAccessToken($token['access_token']);

$oauth2   = new Google_Service_Oauth2($client);
$userInfo = $oauth2->userinfo->get();

$googleEmail = $userInfo->email;
$googleName  = $userInfo->name;
$googlePic   = $userInfo->picture;

// ─── Check if email exists in your database ───────────────────────
$stmt = $pdo->prepare("
    SELECT 
        User_ID, Role_ID, Username,
        First_name, Middle_name, Last_name,
        Email, Phone, Role,
        UserStatus, Profilepic_url
    FROM view_01_users 
    WHERE Email = ?
");
$stmt->execute([$googleEmail]);
$user = $stmt->fetch();

// ─── If no account found ──────────────────────────────────────────
if (!$user) {
    $_SESSION['error'] = 'No account found for this Google email. Please sign up first.';
    header('Location: ../../03_Authentication/login.php');
    exit();
}

// ─── If account is not active ─────────────────────────────────────
if ($user['UserStatus'] !== 'Active') {
    $_SESSION['error'] = 'Your account is ' . $user['UserStatus'] . '. Contact admin.';
    header('Location: ../../03_Authentication/login.php');
    exit();
}

// ─── Regenerate session ───────────────────────────────────────────
session_regenerate_id(true);

// ─── Store session ────────────────────────────────────────────────
$_SESSION['user_id']     = $user['User_ID'];
$_SESSION['username']    = $user['Username'];
$_SESSION['full_name']   = $user['First_name'] . ' ' . $user['Middle_name'] . ' ' . $user['Last_name'];
$_SESSION['role_id']     = $user['Role_ID'];
$_SESSION['role_label']  = $user['Role'];
$_SESSION['profile_pic'] = $googlePic;
$_SESSION['Email']       = $user['Email'];
$_SESSION['Phone']       = $user['Phone'];
$_SESSION['UserStatus']  = $user['UserStatus'];

// ─── Fetch and store address ──────────────────────────────────────
$stmtAddr = $pdo->prepare("
    SELECT Street, Barangay_Name, City_Name, Province_Name
    FROM view_03_user_address 
    WHERE User_ID = ?
");
$stmtAddr->execute([$user['User_ID']]);
$address = $stmtAddr->fetch();

$_SESSION['Street']        = $address['Street']        ?? null;
$_SESSION['Barangay_Name'] = $address['Barangay_Name'] ?? null;
$_SESSION['City_Name']     = $address['City_Name']     ?? null;
$_SESSION['Province_Name'] = $address['Province_Name'] ?? null;

// ─── If pharmacist/owner — fetch pharmacy & inventory ─────────────
if (in_array($user['Role_ID'], [ROLE_PHARMACIST, ROLE_PHARMACY_OWNER])) {
    $stmtPharm = $pdo->prepare("
        SELECT Pharmacy_ID, Pharmacy_name, Approval_ID, Logo_URL, Pic_URL
        FROM 09_pharmacies
        WHERE User_ID = ?
        LIMIT 1
    ");
    $stmtPharm->execute([$user['User_ID']]);
    $pharmacy = $stmtPharm->fetch();

    if ($pharmacy) {
        $_SESSION['pharmacy_id']       = $pharmacy['Pharmacy_ID'];
        $_SESSION['pharmacy_name']     = $pharmacy['Pharmacy_name'];
        $_SESSION['Logo_URL']          = $pharmacy['Logo_URL'];
        $_SESSION['Pic_URL']           = $pharmacy['Pic_URL'];
        $_SESSION['Pharmacy_Approval'] = $pharmacy['Approval_ID'];

        // Fetch inventory separately
        $stmtInv = $pdo->prepare("
            SELECT Inventory_ID FROM 21_inventory 
            WHERE Pharmacy_ID = ? LIMIT 1
        ");
        $stmtInv->execute([$pharmacy['Pharmacy_ID']]);
        $inv = $stmtInv->fetch();
        $_SESSION['inventory_id'] = $inv['Inventory_ID'] ?? null;

    } else {
        $_SESSION['pharmacy_id']       = null;
        $_SESSION['pharmacy_name']     = null;
        $_SESSION['inventory_id']      = null;
        $_SESSION['Pharmacy_Approval'] = 4; // Not Requested
    }
}

// ─── Redirect based on role ───────────────────────────────────────
switch ($user['Role_ID']) {
    case ROLE_PATIENT:
        header('Location: ../../04_User/01_Home.php');
        break;
    case ROLE_PHARMACIST:
    case ROLE_PHARMACY_OWNER:
        if (in_array($_SESSION['Pharmacy_Approval'], [1, 3, 4])) {
            header('Location: ../../05_PharmacyAdmin/00_RequestAccess.php');
        } else {
            header('Location: ../../05_PharmacyAdmin/01_Dashboard.php');
        }
        break;
    case ROLE_SYSTEM_ADMIN:
        header('Location: ../../06_SystemAdmin/01_Dashboard.php');
        break;
    default:
        header('Location: ../../03_Authentication/login.php');
}
exit();
?>