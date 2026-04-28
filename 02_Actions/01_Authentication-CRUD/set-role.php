<?php
    include_once '../../00_Config/config.php';
    include_once '../../02_Actions/GlobalVariables.php';  // ← one level up, inside 02_Actions

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: ../../03_UserSignup - Role.php");
        exit;
    }

    $role_id = (int) ($_POST['role_id'] ?? 0);

    $allowed_roles = [ROLE_PATIENT, ROLE_PHARMACIST, ROLE_PHARMACY_OWNER, ROLE_SYSTEM_ADMIN];

    if (!in_array($role_id, $allowed_roles)) {
        header("Location: ../../03_UserSignup - Role.php?error=invalid_role");
        exit;
    };

    $_SESSION['selected_role_id']    = $role_id;
    $_SESSION['selected_role_label'] = ROLE_LABELS[$role_id];

    $auth_mode = $_POST['auth_mode']; 

    $auth_mode = $_POST['auth_mode'] ?? '';

    if ($auth_mode === 'signup') {
        header("Location: ../../03_Authentication/signup.php");
        exit();
    }
    
    if ($auth_mode === 'login') {
        header("Location: ../../03_Authentication/login.php");
        exit();
    }
    
   
?>


