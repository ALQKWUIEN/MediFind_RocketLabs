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

// ─── Store session (same as your normal login) ────────────────────
$_SESSION['user_id']     = $user['User_ID'];
$_SESSION['username']    = $user['Username'];
$_SESSION['full_name']   = $user['First_name'] . ' ' . $user['Middle_name'] . ' ' . $user['Last_name'];
$_SESSION['role_id']     = $user['Role_ID'];
$_SESSION['role_label']  = $user['Role'];
$_SESSION['profile_pic'] = $googlePic; // use Google profile pic
$_SESSION['Email']       = $user['Email'];
$_SESSION['Phone']       = $user['Phone'];
$_SESSION['UserStatus']  = $user['UserStatus'];

// ─── Redirect based on role (same as normal login) ────────────────
switch ($_SESSION['role_id']) {
    case ROLE_PATIENT:
        header('Location: ../../04_User/01_Home.php');
        break;
    case ROLE_PHARMACIST:
    case ROLE_PHARMACY_OWNER:
        header('Location: ../../05_PharmacyAdmin/01_Dashboard.php');
        break;
    case ROLE_SYSTEM_ADMIN:
        header('Location: ../../05_PharmacyAdmin/00_RequestAccess.php');
        break;
    default:
        header('Location: ../../03_Authentication/login.php');
}
exit();
?>