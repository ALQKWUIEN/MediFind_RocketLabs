<?php
        session_start();
        require '../00_Config/config.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Check if session exists first
            if (!isset($_SESSION['email'])) {
                $_SESSION['error'] = "Session expired. Please try again.";
                header('Location: forgot-password.php');
                exit();
            }

            $enteredCode = $_POST['code'];
            $email = $_SESSION['email'];

            // 2. Prepare the MySQLi statement
            $stmt = $conn->prepare("SELECT reset_code FROM 01_user_users WHERE email = ?");
            $stmt->bind_param("s", $email); // "s" means the parameter is a string
            $stmt->execute();
            
            // 3. Get the result
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                // 4. Verify the code
                if (trim((string)$enteredCode) == trim((string)$user['reset_code'])) {
                    $_SESSION['reset_email'] = $email;
                    $_SESSION['reset_code_verified'] = true;

                    // Optional: Clear the code from DB or session if needed
                    header('Location: reset-password.php');
                    exit();
                } else {
                    $_SESSION['error'] = "Invalid Code. Please try again.";
                    header('Location: verify-code.php'); // Redirect back to show error
                    exit();
                }
            } else {
                $_SESSION['error'] = "No user found with that email.";
                header('Location: forgot-password.php');
                exit();
            }
            
            $stmt->close();
        } else {
            // Only redirect if there's no active session (direct access)
            if (!isset($_SESSION['email'])) {
                header('Location: forgot-password.php');
                exit();
            }
            // If session exists, fall through and show the form normally
        
        }
?>

<!DOCTYPE html>
<html lang="en" data-swup>
  <head data-swup>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukSU Linked - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../07_Assets/css/Auth-style.css" rel="stylesheet">
    <link href="../07_Assets/css/navbar.css" rel="stylesheet">

    <?php include '../01_Includes/page-transition.php'; ?>

  </head>
  
<body id="swup" class="transition-fade">


    <?php include_once '../01_Includes/navbar_forgot-reset.php'?>
    <div class="container-fluid min-vh-100 p-0">
        <div class="row min-vh-100 g-0">

            <div class="col-lg-12 col-md-12 d-flex align-items-center justify-content-center right-panel">
                <div class="card" style="padding-top:75px;">
                    <div class="logo">
                        <img class="logoImage" src="../07_Assets/images/mediFind-tempLogo.png" alt="">
                    </div>
        
                    <div class="card-body">
                        <h1 class="welcomeText">Verify Code</h1>
                        <p class="loginText">Enter code sent to your email</p>

                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show text-center position-absolute start-50 translate-middle-x w-75" role="alert" style="margin-top: 110px;">
                                <?php echo $_SESSION['success']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show text-center position-absolute top-0 start-50 translate-middle-x w-75" role="alert" style="margin-top: 180px;">
                                <?php echo $_SESSION['error']; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger text-center position-absolute top-0 start-50 translate-middle-x w-75" role="alert" style="margin-top: 180px;">
                                <?php echo $_SESSION['error']; ?>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>

                        <form action="verify-code.php" method="POST">
                            <div class="form-group mb-3">
                                <div class="input-group mb-3">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="number" name='code' class="form-control" placeholder="Enter Code" required>
                                </div>
                            </div>

                            
                            <button type="submit" class="btn-login w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                               Verify Code
                            </button>

                            <button type="button" class="btn-google w-100 mb-3" style="margin-top: -5px;">
                                <a href="forgot-password.php" style="text-decoration: none; color:  #555257; font-weight: 400;">Back</a>
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
    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            }
        }
    </script>
</body>

</html>