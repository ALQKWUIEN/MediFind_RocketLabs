<?php
ob_start();
session_start();
require '../00_Config/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    try {
        // 1. Check if user exists
        $stmt = $pdo->prepare("SELECT Email, reset_code FROM `01_user_users` WHERE Email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $reset_code = (string) rand(100000, 999999);

            // 2. Save reset code to DB
            $update = $pdo->prepare("UPDATE `01_user_users` SET reset_code = ? WHERE Email = ?");
            
            if ($update->execute([$reset_code, $email])) {
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'alquiencapuyan18@gmail.com';
                    $mail->Password   = 'rkbz bgup ypiq nwrm';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;
                    $mail->SMTPDebug  = 0;

                    $mail->setFrom('alquiencapuyan18@gmail.com', 'BukSU Linked');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = "Password Reset Code";
                    $mail->Body    = "<p>Your reset code is: <b>$reset_code</b></p>";


                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );



                    $mail->send();

                    $_SESSION['email']       = $email;
                    $_SESSION['reset_email'] = $email;
                    $_SESSION['success']     = "Your password Reset Code has been sent to your email.";
                    header('Location: ../03_Authentication/verify-code.php');
                    exit();

                } catch (Exception $e) {
                    $_SESSION['error'] = "Mailer Error: " . $mail->ErrorInfo;
                    header('Location: ../03_Authentication/forgot-password.php');
                    exit();
                }

            } else {
                $_SESSION['error'] = "Database error. Please try again.";
                header('Location: ../03_Authentication/forgot-password.php');
                exit();
            }

        } else {
            $_SESSION['error'] = "No user found with that email address.";
            header('Location: ../03_Authentication/forgot-password.php');
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header('Location: ../03_Authentication/forgot-password.php');
        exit();
    }
}

ob_end_flush();
?>


<!DOCTYPE html>
<html lang="en" data-swup>
  <head data-swup>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <title>Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <link href="../07_Assets/css/00_Global CSS/Auth-style.css" rel="stylesheet">
    <link href="../07_Assets/css/04_Includes CSS/navbar.css" rel="stylesheet">

    <?php include '../01_Includes/page-transition.php'; ?>

  </head>

<body id="swup" class="transition-fade">


    <?php include_once '../01_Includes/navbar_forgot-reset.php' ?>

    <div class="container-fluid min-vh-100 p-0">
        <div class="row min-vh-100 g-0">


            <div class="col-lg-12 col-md-12 d-flex align-items-center justify-content-center right-panel">
                <div class="card" style="padding-top:75px;">
                    <div class="logo">
                        <img class="logoImage" src="../07_Assets/images/mediFind-tempLogo.png" alt="">
                    </div>
        
                    <div class="card-body">
                        <h1 class="welcomeText">Forgot Password</h1>
                        <p class="loginText">Enter your email address</p>

  

                        <?php include '../01_Includes/Error_Message.php'; ?>

                        <form action="forgot-password.php" method="POST">

                            <div class="form-group mb-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="Enter Your Email Address" required>
                                </div>
                            </div>

                            
                            <button type="submit" class="btn-login w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Send Code
                            </button>

                            <button type="button" class="btn-google w-100 mb-3" style="margin-top: -5px;">
                                <a href="login.php" style="text-decoration: none; color:  #555257; font-weight: 400;">Back to Login</a>
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