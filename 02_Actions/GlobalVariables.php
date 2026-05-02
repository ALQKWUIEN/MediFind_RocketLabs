<?php

// 00_Config/GlobalVariables.php
// Central place for all session variables used across the project
// Include this in every page that needs to read or write these values

use Dotenv\Dotenv;

// ─── LOAD ENV VARIABLES ───────────────────────────────────────────
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// ─── RECAPTCHA KEYS ───────────────────────────────────────────────
define('RECAPTCHA_SITE_KEY',   $_ENV['RECAPTCHA_SITE_KEY']);
define('RECAPTCHA_SECRET_KEY', $_ENV['RECAPTCHA_SECRET_KEY']);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─── ROLE CONSTANTS ───────────────────────────────────────────────
define('ROLE_PATIENT',        1);
define('ROLE_PHARMACIST',     2);
define('ROLE_PHARMACY_OWNER', 3);
define('ROLE_SYSTEM_ADMIN',   4);

define('ROLE_LABELS', [
    ROLE_PATIENT        => 'Patient',
    ROLE_PHARMACIST     => 'Pharmacist',
    ROLE_PHARMACY_OWNER => 'Pharmacy Owner',
    ROLE_SYSTEM_ADMIN   => 'System Admin',
]);

// ─── ROLE ─────────────────────────────────────────────────────────
// Set by: set_role.php
// Used by: signup.php, signup_action.php
$_SESSION['selected_role_id'] = $_SESSION['selected_role_id'] ?? null;
$_SESSION['selected_role_label'] = $_SESSION['selected_role_label'] ?? null;

// ─── LOGGED IN USER ───────────────────────────────────────────────
// Set by: login_action.php
// Used by: every dashboard, profile, navbar

//LOGGED IN USER INFO 
$_SESSION['user_id']    = $_SESSION['user_id']      ?? null;
$_SESSION['username']   = $_SESSION['username']     ?? null;
$_SESSION['full_name']  = $_SESSION['full_name']    ?? null;

$_SESSION['fname']      =   $_SESSION['fname']      ?? null;
$_SESSION['mname']      =   $_SESSION['mname']      ?? null;
$_SESSION['lname']      =   $_SESSION['lname']      ?? null;


$_SESSION['Gender']     = $_SESSION['Gender']        ?? null;
$_SESSION['Birthdate']  = $_SESSION['Birthdate']     ?? null;
$_SESSION['Age']        = $_SESSION['Age']           ?? null;


$_SESSION['role_id']    = $_SESSION['role_id']      ?? null;
$_SESSION['role_label'] = $_SESSION['role_label']   ?? null;
$_SESSION['profile_pic']= $_SESSION['profile_pic']  ?? null;

$_SESSION['Email']      = $_SESSION['Email']        ?? null;
$_SESSION['Phone']      = $_SESSION['Phone']        ?? null;
$_SESSION['UserStatus'] = $_SESSION['UserStatus']   ?? null;


$_SESSION['Street']             = $_SESSION['Street']               ?? null;
$_SESSION['Barangay_Name']      = $_SESSION['Barangay_Name']        ?? null;
$_SESSION['City_Name']          = $_SESSION['City_Name']            ?? null;
$_SESSION['Province_Name']      = $_SESSION['Province_Name']        ?? null;


//FOR PHARMACIEs
$_SESSION['pharmacy_id']        = $_SESSION['pharmacy_id']          ?? null;
$_SESSION['pharmacy_name']      = $_SESSION['pharmacy_name']        ?? null;
$_SESSION['inventory_id']       = $_SESSION['inventory_id']         ?? null;
$_SESSION['Pharmacy_Approval'] = $_SESSION['Pharmacy_Approval']     ?? null; 

$_SESSION['Logo_URL']           = $_SESSION['Logo_URL']             ?? null;
$_SESSION['Pic_URL']            = $_SESSION['Pic_URL']              ?? null;

?>

<!-- Structure Explanation
1. $_SESSION['user_id']:
    This is a session variable that stores the user_id for the current session.
    $_SESSION is a superglobal array in PHP used to store data that persists across multiple pages during a user's session.

2. ?? (Null Coalescing Operator):
    The ?? operator checks if the left-hand side ($_SESSION['user_id']) is set and not null.
    If it is set and not null, it returns the value of $_SESSION['user_id'].
    If it is not set or is null, it returns the right-hand side (null in this case).

3. = $_SESSION['user_id'] ?? null;:
    This assigns the value of $_SESSION['user_id'] to itself if it exists and is not null.
    If $_SESSION['user_id'] does not exist or is null, it assigns null to $_SESSION['user_id']. -->