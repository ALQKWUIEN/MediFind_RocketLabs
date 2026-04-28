<?php
    include_once '../00_Config/config.php';
    include_once '../02_Actions/GlobalVariables.php'; 

    // Restore form data if coming back from an error
    $fd = $_SESSION['form_data'] ?? [];
    unset($_SESSION['form_data']);
?>

<!DOCTYPE html>
<html lang="en" data-swup>
  <head data-swup>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <title>Sign Up</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <link href="../07_Assets/css/00_Global CSS/Auth-style.css" rel="stylesheet">
    <link href="../07_Assets/css/04_Includes CSS/navbar.css" rel="stylesheet">

    <!-- ?php include '../01_Includes/page-transition.php'; ?> -->

    <style>
        .card-signup { position: relative; }
        /* Floating error styling */
        .floating-error-container {
            position: absolute;
            top: 75px; 
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            z-index: 1050;
            pointer-events: none;
        }
        .floating-error-container .alert {
            pointer-events: auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            background-color: rgba(255, 255, 255, 0.98);
            border-left: 5px solid #dc3545;
        }

        
    </style>
    
  </head>
  
<body id="swup" class="transition-fade">

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

            <!-- right panel-->
            <div class="col-lg-6 col-md-6 right-panel-container d-flex flex-column align-items-center justify-content-center">
                <div class="card-signup">
                    <div class="card-body">
                        <h1 class="welcomeText text-center">Create an Account</h1>
                        <p class="loginText text-center">Level up your medicine access!</p>

                        <!-- ERROR MESSAGE -->
                        <?php if (!empty($_SESSION['error'])): ?>
                            
                            <?php include '../01_Includes/Error_Message.php'; ?>
                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    <?php if (isset($_SESSION['error_step'])): ?>
                                        changeStep(<?= $_SESSION['error_step'] ?>);
                                        <?php unset($_SESSION['error_step']); ?>
                                    <?php endif; ?>
                                });
                            </script>
                        <?php endif; ?>
                        
                        <form id="signupForm" action="../02_Actions/01_Authentication-CRUD/signup-validate.php" method="post" onsubmit="return validateForm()">
                        
                            <!-- FORM PERSONAL INFO -->
                            <div class="form-step" id="step1">
                                <div class="step-wizard">
                                    <ul class="step-wizard-list">
                                        <li class="step-wizard-item active"><span class="progress-count">1</span></li>
                                        <li class="step-wizard-item"><span class="progress-count">2</span></li>
                                        <li class="step-wizard-item"><span class="progress-count">3</span></li>
                                    </ul>
                                </div>
                        
                                <div class="row g-2 mb-3">
                                    <div class="col-6 col-lg-12">
                                        <input type="text" name="Fname" id="Fname" class="form-control form-signup"
                                            placeholder="First Name"
                                            value="<?= htmlspecialchars($fd['Fname'] ?? '') ?>"
                                            required>
                                    </div>
                                    <div class="col-6 col-lg-12">
                                        <input type="text" name="Mname" id="Mname" class="form-control form-signup"
                                            placeholder="Middle Name (Optional)"
                                            value="<?= htmlspecialchars($fd['Mname'] ?? '') ?>">
                                    </div>
                                    <div class="col-6 col-lg-12">
                                        <input type="text" name="Lname" id="Lname" class="form-control form-signup"
                                            placeholder="Last Name"
                                            value="<?= htmlspecialchars($fd['Lname'] ?? '') ?>"
                                            required>
                                    </div>
                                    <div class="col-6 col-lg-12">
                                        <input type="tel" name="ContactNo" id="ContactNo" class="form-control form-signup"
                                            placeholder="Contact Number"
                                            value="<?= htmlspecialchars($fd['ContactNo'] ?? '') ?>"
                                            required>
                                    </div>
                                </div>
                        
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <select name="gender" id="gender" class="form-select form-signup" required>
                                            <option value="" disabled <?= empty($fd['gender']) ? 'selected' : '' ?>>Gender</option>
                                            <option value="Male"   <?= ($fd['gender'] ?? '') === 'Male'   ? 'selected' : '' ?>>Male</option>
                                            <option value="Female" <?= ($fd['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <input type="date" name="birth_date" id="birth_date" class="form-control form-signup"
                                            value="<?= htmlspecialchars($fd['birth_date'] ?? '') ?>"
                                            required>
                                    </div>
                                </div>
                        
                                <button type="button" class="btn-login w-100 mb-3 btn-signup-con" onclick="validateStep1()">Continue</button>
                                <a href="../03_UserSignup - Role.php" class="btn form-back-btn w-100" type="button" style="margin-top: -10px;">
                                    Back                         
                                </a>
                            </div>
                        
                            <!-- FORM ADDRESS INFO -->
                            <div class="form-step" id="step2" style="display: none;">
                                <div class="step-wizard">
                                    <ul class="step-wizard-list">
                                        <li class="step-wizard-item"><span class="progress-count">1</span></li>
                                        <li class="step-wizard-item active"><span class="progress-count">2</span></li>
                                        <li class="step-wizard-item"><span class="progress-count">3</span></li>
                                    </ul>
                                </div>
                        
                                <div class="row g-2 mb-3">
                                    <div class="col-12">
                                        <select name="Province" id="Province_ID" class="form-select form-signup" required>
                                            <option value="" disabled selected>Province</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <select name="City" id="City_ID" class="form-select form-signup" required>
                                            <option value="" disabled selected>City</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <select name="Barangay" id="Barangay_ID" class="form-select form-signup" required>
                                            <option value=""  disabled selected>Barangay</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="Street" id="Street" class="form-control form-signup"
                                            placeholder="Street/House No."
                                            value="<?= htmlspecialchars($fd['Street'] ?? '') ?>"
                                            required>
                                    </div>
                                </div>
                        
                                <button type="button" class="btn-login w-100 mt-2 btn-signup-con" onclick="validateStep2()">Continue</button>
                                <button type="button" class="btn form-back-btn w-100 mt-2" onclick="changeStep(1)">Back</button>
                            </div>
                        
                            <!-- FORM LOGIN INFO -->
                            <div class="form-step" id="step3" style="display: none;">
                                <div class="step-wizard">
                                    <ul class="step-wizard-list">
                                        <li class="step-wizard-item"><span class="progress-count">1</span></li>
                                        <li class="step-wizard-item"><span class="progress-count">2</span></li>
                                        <li class="step-wizard-item active"><span class="progress-count">3</span></li>
                                    </ul>
                                </div>
                        
                                <div class="row g-2 mb-3">
                                    <div class="col-12">
                                        <input type="email" name="email" id="email" class="form-control form-signup"
                                            placeholder="Email Address"
                                            value="<?= htmlspecialchars($fd['email'] ?? '') ?>"
                                            required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="username" id="username" class="form-control form-signup"
                                            placeholder="Username"
                                            value="<?= htmlspecialchars($fd['username'] ?? '') ?>"
                                            required>
                                    </div>
                                    <div class="col-12">
                                        <!-- Passwords are intentionally NOT restored for security -->
                                        <input type="password" name="password" id="password" class="form-control form-signup" 
                                         placeholder="Password" 
                                         <?= htmlspecialchars($fd['password_hash'] ?? '') ?>
                                         required>
                                    </div>
                                    <div class="col-12">
                                        <input type="password" name="confirmpass" id="confirmpass" class="form-control form-signup" placeholder="Confirm Password" required>
                                    </div>
                                </div>
                        
                                <button type="submit" class="btn-login w-100 mt-2 btn-signup-con">Register</button>
                                <button type="button" class="btn form-back-btn w-100 mt-2" onclick="changeStep(2)">Back</button>
                            </div>
                        
                            <p class="report-text text-center">Already have an account? <a href="../03_Authentication/login.php" class="signup-link">Login</a></p>
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
    <script src="../07_Assets/css/js/form-steps.js"></script>
    <script src="../07_Assets/css/js/load_address.js"></script>


 
</body>
</html>