<?php
    session_start();
    include_once '../00_Config/config.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        
        if (!empty($_POST['email']) && !empty($_POST['password'])) {

        
            $sql = "SELECT user_id, username, email, password_hash FROM 01_user_users WHERE email = ?";

            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("s", $param_email);
                $param_email = $_POST['email'];
                $param_username= $_POST['username'];

                if ($stmt->execute()) {
                    $stmt->store_result(); 

                    if ($stmt->num_rows == 1) {
                        
                        $stmt->bind_result($user_id, $username, $email, $hashed_password);

                        if ($stmt->fetch()) {
                            if (password_verify($_POST['password_hash'], $hashed_password)) {
                                $_SESSION['loggedin'] = true;
                                $_SESSION['user_id'] = $user_id;
                                $_SESSION['username'] = $username;
                                $_SESSION['email'] = $email;
                                
                                header('Location: reset-password.php');
                                exit();

                            } else {
                                $_SESSION['error'] = "Invalid email or password. Please try again";
                                header('Location: login.php');
                                exit();
                            }
                        }
                    } else {
                        $_SESSION['error'] = "User does not exist. Please try again";
                        header('Location: login.php');
                        exit();
                    }
                } else {
                    echo "Oops! Something went wrong.";
                }
                $stmt->close();
            }
        }
        $conn->close();
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BukSU Linked - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../07_Assets/css/Auth-style.css" rel="stylesheet">
    <link href="../07_Assets/css/navbar.css" rel="stylesheet">

</head>

<body>
    <?php include_once '../01_Includes/navbar.php' ?>

    <div class="container-fluid vh-100 p-0">
        <div class="row h-100 g-0">

            <div
                class="col-lg-6 col-md-0 left-bg col-sm-0 d-flex d-none d-lg-flex flex-column align-items-center justify-content-center">
                <div class="logo-section text-center">
                    <img src="../07_Assets/images/logo-white.png" alt="BukSU Linked Logo" class="logo-img mb-3">
                    <h1 class="brand-title">MediFind 123</h1>
                    <p class="brand-sub">Malaybalay Medicine Availability Checker</p>
                </div>

            </div>

            <div class="col-lg-6 col-md-12 d-flex align-items-center justify-content-center right-panel">
                <div class="card">
                    <div class="card-body">

                        <h1 class="welcomeText">Welcome Back!</h1>
                        <p class="loginText">Login to Continue</p>


                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="mt-4 alert alert-success text-center position-absolute start-50 translate-middle-x w-75" role="alert">
                                <?php echo $_SESSION['success']; ?>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="mt-4 alert alert-danger text-center position-absolute top-0 start-50 translate-middle-x w-75" role="alert">
                                <?php echo $_SESSION['error']; ?>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>



                        <form action="../03_Authentication/login.php" method="POST">

                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="email" class="form-control" placeholder="Email" required>
                            </div>


                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="passwordInput" class="form-control"
                                    placeholder="Password" required>

                            </div>


                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember Password</label>
                                </div>
                                <a href="forgot-password.php" class="forgotPass">Forgot Password</a>
                            </div>


                            <button type="submit" class="btn-login w-100 mb-3 .rounded-pill">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Login
                            </button>


                            <div class="or-divider mb-3">
                                <span>Or</span>
                            </div>

                            <!-- Google login -->
                            <button type="button" class="btn-google w-100 mb-3">
                                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                                    alt="Google" width="18" class="me-2">
                                Login with Google
                            </button>

                        </form>

                        <!-- Signup -->
                        <p class="report-text text-center">
                            Dont have an account? <a href="signup.php" class="signup-link">Sign Up</a>
                        </p>


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
            const icon = document.getElementById('toggleIcon');
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