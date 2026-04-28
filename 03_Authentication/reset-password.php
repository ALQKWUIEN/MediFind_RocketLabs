<?php
session_start();
require '../00_Config/config.php'; // make sure this returns a PDO instance: $pdo

// Guard: must have verified the code first
if (!isset($_SESSION['reset_code_verified']) || !isset($_SESSION['reset_email'])) {
    header('Location: forgot-password.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        header('Location: reset-password.php');
        exit();
    }

    $hashed = password_hash($new_password, PASSWORD_DEFAULT);
    $email = $_SESSION['reset_email'];

    try {
        $stmt = $pdo->prepare("UPDATE 01_user_users SET password_hash = :password, reset_code = NULL WHERE email = :email");
        
        $stmt->execute([
            ':password' => $hashed,
            ':email' => $email
        ]);

        // Clean up session
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_code_verified']);
        unset($_SESSION['email']);

        $_SESSION['success'] = "Password updated successfully. Please login.";
        header('Location: login.php');
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header('Location: reset-password.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-swup>
  <head data-swup>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <title>Reset Password</title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="../07_Assets/css/00_Global CSS/Auth-style.css" rel="stylesheet">
    <link href="../07_Assets/css/04_Includes CSS/navbar.css" rel="stylesheet">

    <?php include '../01_Includes/page-transition.php'; ?>

  </head>
  
<body id="swup" class="transition-fade">


    <?php include_once '../01_Includes/navbar_forgot-reset.php'?>

    <div class="container-fluid min-vh-100 p-0">
        <div class="row min-vh-100 g-0">
            <div class="col-lg-12 col-md-12 d-flex align-items-center justify-content-center right-panel">
                <div class="card">
                    <div class="logo">
                        <img class="logoImage" src="../07_Assets/images/mediFind-tempLogo.png" alt="">
                    </div>
                    <div class="card-body">
                        <h1 class="welcomeText">Change Password</h1>
                        <p class="loginText">Create a new password.</p>


                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show text-center position-absolute start-50 translate-middle-x w-75" role="alert" style="margin-top: 110px;">
                                <?php echo $_SESSION['success']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show text-center position-absolute top-0 start-50 translate-middle-x w-75" role="alert" style="margin-top: 150px;">
                                <?php echo $_SESSION['error']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger text-center position-absolute top-0 start-50 translate-middle-x w-75" role="alert" style="margin-top: 150px;">
                                <?php echo $_SESSION['error']; ?>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>



                        <form action="reset-password.php" method="POST">
                            <div class="form-group mb-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="bi bi-check-lg"></i></span>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-login w-100 mb-3">
                                Create New Password
                            </button>
                            <button type="button" class="btn-google w-100 mb-3" style="margin-top: -5px;">
                                <a href="login.php" style="text-decoration: none; color: #555257; font-weight: 400;">Cancel</a>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="floating-btns">
        <button class="float-btn" onclick="history.back()"><i class="bi bi-arrow-left"></i></button>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>