<?php
include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ─── 0. Verify reCAPTCHA ──────────────────────────────────────
    $recaptcha_token = $_POST['g-recaptcha-response'] ?? '';

    if (empty($recaptcha_token)) {
        $_SESSION['error'] = "Please complete the reCAPTCHA.";
        header('Location: ../03_Authentication/login.php');
        exit;
    }

    $verify = file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify?secret='
        . RECAPTCHA_SECRET_KEY
        . '&response=' . urlencode($recaptcha_token)
        . '&remoteip=' . urlencode($_SERVER['REMOTE_ADDR'])
    );

    $result = json_decode($verify, true);

    if (!$result['success']) {
        $_SESSION['error'] = "reCAPTCHA failed. Please try again.";
        header('Location: ../03_Authentication/login.php');
        exit;
    }

    try {
        // ─── 1. Fetch user from view_01_users ─────────────────────────
        $stmt = $pdo->prepare("
            SELECT 
                User_ID,
                Role_ID,
                Username, 
                First_name, 
                Middle_name, 
                Last_name, 
                Gender,
                Birthdate,
                Age,
                Email, 
                Phone,
                Role, 
                UserStatus, 
                Profilepic_url, 
                Password_hash
            FROM view_01_users 
            WHERE Email = ?
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // ─── 2. Does user exist? ───────────────────────────────────────
        if (!$user) {
            $_SESSION['error'] = "No account found with that email.";
            header('Location: ../03_Authentication/login.php');
            exit;
        }

        // ─── 3. Does password match? ───────────────────────────────────
        if (!password_verify($password, $user['Password_hash'])) {
            $_SESSION['error'] = "Incorrect password.";
            header('Location: ../03_Authentication/login.php');
            exit;
        }

        // ─── 4. Is account active? ─────────────────────────────────────
        if ($user['UserStatus'] !== 'Active') {
            $_SESSION['error'] = "Your account is " . $user['UserStatus'] . ". Contact admin.";
            header('Location: ../03_Authentication/login.php');
            exit;
        }

        // ─── 5. Security — regenerate session ID ──────────────────────
        session_regenerate_id(true);

        // ─── 6. Store user info into session ───────────────────────────
        $_SESSION['user_id'] = $user['User_ID'];
        $_SESSION['username'] = $user['Username'];
        $_SESSION['fname'] = $user['First_name'];
        $_SESSION['mname'] = $user['Middle_name'];
        $_SESSION['lname'] = $user['Last_name'];

        $_SESSION['full_name'] = $user['First_name'] . ' ' . $user['Middle_name'] . ' ' . $user['Last_name'];

        $_SESSION['Gender']     = $user['Gender'];
        $_SESSION['Birthdate']  = $user['Birthdate'];
        $_SESSION['Age']        = $user['Age'];

        
        $_SESSION['role_id'] = $user['Role_ID'];
        $_SESSION['role_label'] = $user['Role'];
        $_SESSION['profile_pic'] = $user['Profilepic_url'];
        $_SESSION['Email'] = $user['Email'];
        $_SESSION['Phone'] = $user['Phone'];
        $_SESSION['UserStatus'] = $user['UserStatus'];


        // ─── 7. Fetch and store address ────────────────────────────────
        $stmtAddr = $pdo->prepare("
            SELECT Street, Barangay_Name, City_Name, Province_Name, Full_Address
            FROM view_03_user_address 
            WHERE User_ID = ?
        ");
        $stmtAddr->execute([$user['User_ID']]);
        $address = $stmtAddr->fetch();

        $_SESSION['Street'] = $address['Street'] ?? null;
        $_SESSION['Barangay_Name'] = $address['Barangay_Name'] ?? null;
        $_SESSION['City_Name'] = $address['City_Name'] ?? null;
        $_SESSION['Province_Name'] = $address['Province_Name'] ?? null;


        // ─── 8. If pharmacist/owner — fetch pharmacy & inventory ───────
        if (in_array($_SESSION['role_id'], [ROLE_PHARMACIST, ROLE_PHARMACY_OWNER])) {
            $stmtPharm = $pdo->prepare("
                SELECT 
                    p.Pharmacy_ID,
                    p.Pharmacy_name,
                    p.Owner_name,
                    p.Address_ID,
                    p.Phone,
                    p.Approval_ID,
                    p.Logo_URL,
                    p.Pic_URL,
                    i.Inventory_ID
                FROM 09_pharmacies p
                LEFT JOIN 21_inventory i ON i.Pharmacy_ID = p.Pharmacy_ID
                WHERE p.User_ID = ?
                LIMIT 1
            ");
            $stmtPharm->execute([$user['User_ID']]);
            $pharmacy = $stmtPharm->fetch();

            if ($pharmacy) {
                $_SESSION['pharmacy_id'] = $pharmacy['Pharmacy_ID'];
                $_SESSION['pharmacy_name'] = $pharmacy['Pharmacy_name'];
                $_SESSION['inventory_id'] = $pharmacy['Inventory_ID'];
                $_SESSION['Logo_URL'] = $pharmacy['Logo_URL'];
                $_SESSION['Pic_URL'] = $pharmacy['Pic_URL'];
                $_SESSION['Pharmacy_Approval'] = $pharmacy['Approval_ID'];
            }
        }


        // ─── 9. Redirect based on role ─────────────────────────────────
        switch ($_SESSION['role_id']) {
            case ROLE_PATIENT:
                header('Location: ../04_User/01_Home.php');
                break;

            case ROLE_PHARMACIST:
            case ROLE_PHARMACY_OWNER:
                // 1 = Pending, 3 = Rejected, 4 = Not Requested → setup page
                // 2 = Approved → dashboard
                if (in_array($_SESSION['Pharmacy_Approval'], [1, 3, 4])) {
                    header('Location: ../05_PharmacyAdmin/00_RequestAccess.php');
                } else {
                    header('Location: ../05_PharmacyAdmin/01_Dashboard.php');
                }
                break;

            case ROLE_SYSTEM_ADMIN:
                header('Location: ../06_SystemAdmin/01_Dashboard.php');
                break;
            default:
                header('Location: ../03_Authentication/login.php');
        }
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Something went wrong: " . $e->getMessage();
        header('Location: ../03_Authentication/login.php');
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../07_Assets/bootstrap/css/bootstrap.min.css" />
    <link href="../07_Assets/css/00_Global CSS/Auth-style.css" rel="stylesheet">
    <link href="../07_Assets/css/04_Includes CSS/navbar.css" rel="stylesheet">

    <!-- Transition Includes -->
    <?php include '../01_Includes/page-transition-hardcode.php'; ?>

</head>

<body>

    <!-- Navbar -->
    <?php include_once '../01_Includes/navbar.php' ?>

    <div class="container-fluid vh-100 p-0">
        <div class="row h-100 g-0">

            <div
                class="col-lg-6 col-md-0 left-bg col-sm-0 d-flex d-none d-lg-flex flex-column align-items-center justify-content-center">
                <div class="logo-section text-center">
                    <img src="../07_Assets/images/logo-white.png" alt="BukSU Linked Logo" class="logo-img mb-3">
                    <h1 class="brand-title">MediFind</h1>
                    <p class="brand-sub">Malaybalay Medicine Availability Checker</p>
                </div>

            </div>

            <div class="col-lg-6 col-md-12 d-flex align-items-center justify-content-center right-panel">
                <div class="card">
                    <div class="card-body">

                        <h1 class="welcomeText">Welcome Back!</h1>
                        <p class="loginText">Login to Continue</p>


                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success text-center position-absolute start-50 translate-middle-x w-75"
                                role="alert">
                                <?php echo $_SESSION['success']; ?>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="mt-5 alert alert-danger text-center position-absolute top-0 start-50 translate-middle-x w-75"
                                role="alert">
                                <?php echo $_SESSION['error']; ?>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
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

                            <!-- Add widget inside your form, before login button -->

                            <div id="recaptcha-container" class="mb-3" data-sitekey="<?= RECAPTCHA_SITE_KEY ?>">
                            </div>


                            <button type="submit" class="btn-login w-100 mb-3 .rounded-pill">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Login
                            </button>


                            <div class="or-divider mb-3">
                                <span>Or</span>
                            </div>

                            <!-- Google login -->
                            <!-- ✅ Use anchor tag instead of button -->
                            <a href="../02_Actions/01_Authentication-CRUD/google-login.php"
                                class="btn-google w-100 mb-3 text-decoration-none d-flex align-items-center justify-content-center">
                                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                                    alt="Google" width="18" class="me-2">
                                Login with Google
                            </a>

                        </form>

                        <!-- Signup -->
                        <p class="report-text text-center">
                            Dont have an account? <a href="../03_UserSignup - Role.php" class="signup-link">Sign Up</a>
                        </p>


                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="floating-btns">
        <button class="float-btn" onclick="history.back()"><i class="bi bi-arrow-left"></i></button>
    </div>

    <!-- Add reCAPTCHA script in <head> -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

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

    <script>
        // Called by reCAPTCHA API once the script loads
        function onRecaptchaLoad() {
            renderRecaptcha();
        }

        function renderRecaptcha() {
            const container = document.getElementById('recaptcha-container');
            if (!container || typeof grecaptcha === 'undefined') return;

            // Clear the container completely before re-rendering
            container.innerHTML = '';

            grecaptcha.render(container, {
                sitekey: '<?= RECAPTCHA_SITE_KEY ?>'
            });
        }

        // Re-render after every Swup page transition
        document.addEventListener('swup:contentReplaced', function () {
            if (typeof grecaptcha !== 'undefined') {
                renderRecaptcha();
            } else {
                // Script not loaded yet, load it dynamically
                const existing = document.querySelector('script[src*="recaptcha"]');
                if (!existing) {
                    const script = document.createElement('script');
                    script.src = 'https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit';
                    script.async = true;
                    script.defer = true;
                    document.head.appendChild(script);
                }
            }
        });
    </script>

    <!-- Load reCAPTCHA in explicit mode so WE control when it renders -->
    <script src="https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoad&render=explicit" async defer></script>



</body>

</html>