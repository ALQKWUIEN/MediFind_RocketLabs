<?php
        ob_start(); // Prevents "Headers already sent" errors
        session_start();
        require '../00_Config/config.php';

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        require '../vendor/autoload.php';

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $email = $_POST['email'] ?? '';

            // 1. Check if user exists
            $stmt = $conn->prepare("SELECT email, reset_code FROM 01_user_users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if($user){
                $reset_code = (string)rand(100000, 999999);

                // 3. Save to DB - Changed to "ss" assuming reset_code might be varchar
                $update = $conn->prepare("UPDATE 01_user_users SET reset_code = ? WHERE email = ?");
                $update->bind_param("ss", $reset_code, $email);
                
                if($update->execute()){
                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'alquiencapuyan18@gmail.com';
                        $mail->Password = 'rkbz bgup ypiq nwrm'; // Ensure this is an App Password!
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;
                        $mail->SMTPDebug  = 0; // CRITICAL: Ensure no debug output breaks headers

                        $mail->setFrom('alquiencapuyan18@gmail.com', 'BukSU Linked');
                        $mail->addAddress($email);

                        $mail->isHTML(true);
                        $mail->Subject = "Password Reset Code";
                        $mail->Body = "<p>Your reset code is: <b>$reset_code</b></p>";

                        $mail->send();

                        $_SESSION['email'] = $email;
                        $_SESSION['reset_email'] = $email; 
                        $_SESSION['success'] = "Your password Reset Code has been sent to your email.";
                        header('Location: verify-code.php');
                        exit();

                    } catch (Exception $e) {
                        $_SESSION['error'] = "Mailer Error: " . $mail->ErrorInfo;
                        header('Location: forgot-password.php');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = "Database error. Please try again.";
                    header('Location: forgot-password.php');
                    exit();
                }

            } else {
                $_SESSION['error'] = "No user found with that email address.";
                header('Location: forgot-password.php');
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
    
    <link href="../07_Assets/css/Auth-style.css" rel="stylesheet">
    <link href="../07_Assets/css/navbar.css" rel="stylesheet">

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

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show text-center position-absolute start-50 translate-middle-x w-75" role="alert" style="margin-top: 100px;">
                                <?php echo $_SESSION['success']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show text-center position-absolute top-0 start-50 translate-middle-x w-75" role="alert" style="margin-top: 100px;">
                                <?php echo $_SESSION['error']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger text-center position-absolute top-0 start-50 translate-middle-x w-75" role="alert" style="margin-top: 100px;">
                                <?php echo $_SESSION['error']; ?>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

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