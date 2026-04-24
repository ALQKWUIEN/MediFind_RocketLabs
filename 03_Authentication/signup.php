<?php

session_start();

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include_once '../00_Config/config.php';

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password_hash"];


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM 01_user_users where email = '$email'";
    $result = $conn->query($sql);

    if($result->num_rows >0){
        echo ("Email already exist");
    }else{
        $sql = "INSERT INTO  01_user_users (username, email, password_hash) VALUES ('$name','$email', '$hashed_password');";
        
        if($conn->query($sql) === TRUE){
            echo ("Inserted Successfully");
        }else{
            echo ("Error!");
        }
    }
    $conn->close();

} 

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

    <link href="../07_Assets/css/Auth-style.css" rel="stylesheet">
    <link href="../07_Assets/css/navbar.css" rel="stylesheet">

     <?php include '../01_Includes/page-transition.php'; ?>

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

                        <form id="signupForm" action="signup-validate.php" method="post" onsubmit="return validateForm()">
                        
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
                                        <input type="text" name="Fname" id="Fname" class="form-control form-signup" placeholder="First Name" required>
                                    </div>
                                    <div class="col-6 col-lg-12">
                                        <input type="text" name="Mname" id="Mname" class="form-control form-signup" placeholder="Middle Name (Optional)">
                                    </div>
                                    <div class="col-6 col-lg-12">
                                        <input type="text" name="Lname" id="Lname" class="form-control form-signup" placeholder="Last Name" required>
                                    </div>
                                    <div class="col-6 col-lg-12">
                                        <input type="tel" name="ContactNo" id="ContactNo" class="form-control form-signup" placeholder="Contact Number" required>
                                    </div>
                                </div>
                        
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <select name="gender" id="gender" class="form-select form-signup" required>
                                            <option value="" disabled selected>Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <input type="date" name="birth_date" id="birth_date" class="form-control form-signup" required>
                                    </div>
                                </div>
                        
                                <button type="button" class="btn-login w-100 mb-3 btn-signup-con" onclick="validateStep1()">Continue</button>
                            </div>
                        
                            <!-- FORM ADDRESS INFO------------------------------------------------------------------>
                        
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
                                        <select name="Province" id="Province" class="form-select form-signup" required>
                                            <option value="" disabled selected>Province</option>
                                            <option value="Bukidnon">1</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <select name="City" id="City" class="form-select form-signup" required>
                                            <option value="" disabled selected>City</option>
                                            <option value="Malaybalay">1</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <select name="Barangay" id="Barangay" class="form-select form-signup" required>
                                            <option value="" disabled selected>Barangay</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">Aglayan</option>
                                            <option value="4">Dalwangan</option>
                                            <option value="5">Patpat</option>
                                            <option value="6">Busco</option>
                                            <option value="7">Managok</option>
                                            <option value="8">Kalasungay</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="Street" id="Street" class="form-control form-signup" placeholder="Street/House No." required>
                                    </div>
                                </div>
                        
                                <button type="button" class="btn-login w-100 mt-2 btn-signup-con" onclick="validateStep2()">Continue</button>
                                <button type="button" class="btn form-back-btn w-100 mt-2" onclick="changeStep(1)">Back</button>
                            </div>
                        
                            <!-- FORM LOGIN INFO ------------------------------------------------------------------>
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
                                        <input type="email" name="email" id="email" class="form-control form-signup" placeholder="Email Address" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="username" id="username" class="form-control form-signup" placeholder="Username" required>
                                    </div>
                                    <div class="col-12" class="input-group">
                                        <input type="password" name="password" id="password" class="form-control form-signup" placeholder="Password" required>
                                      
                                    </div>
                                    <div class="col-12" class="input-group">
                                        <input type="password" name="confirmpass" id="confirmpass" class="form-control form-signup" placeholder="Confirm Password" required>
                                      
                                    </div>
                                </div>
                        
                                <button type="submit" class="btn-login w-100 mt-2 btn-signup-con">Register</button>
                                <button type="button" class="btn form-back-btn w-100 mt-2" onclick="changeStep(2)">Back</button>
                            </div>
                        
                            <p class="report-text text-center">Already have an account? <a href="../02_UserLogin - Role.php" class="signup-link">Login</a></p>
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
    <script src="../07_Assets/css/js/form-steps.js"></script>
    
</body>

</html>