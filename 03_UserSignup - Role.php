<!doctype html>

<html lang="en" data-swup-theme  >
  <head data-swup-theme>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="07_Assets/images/logo.png" type="image/png" />

    <title>MediFind: Malaybalay Medicine Availability Checker</title>

    <link rel="icon" href="07_Assets/images/logo.png" type="image/png" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap"rel="stylesheet"/>
  
    <!-- Assets -->
    <link href="07_Assets/css/00_Global CSS/login-user-role.css" rel="stylesheet">
    <link href="07_Assets/css/04_Includes CSS/navbar.css" rel="stylesheet">

    <link rel="stylesheet" href="07_Assets/node_modules/material-symbols/outlined.css" />

  <!-- Page transition -->
  <?php include '01_Includes/page-transition-hardcode.php' ?>

  </head>
  <body class="auth-page">
    <!-- START OF NAVBAR -->
    <?php include_once '01_Includes/navbar_landing-role.php' ?>

    <!-- ================================================================================================== -->

    <!-- START OF MAIN CONTENT -->

    <main class="main-content">
      <div class="hero-card mt-5" style="margin-top: 50px !important;">
        <div class="user-role-mobile">
          <div class="hero-title user-role">Medi<span>Find</span></div>
          <div class="hero-subtitle">
            Malaybalay Medicine Availability Checker
          </div>
        </div>

        <p>
          Select an Account Type to Continue
        </p>

        <!-- set role code is in  02_Actions/01_Authentication-CRUD/set-role.php -->
        <div class="btn-stack">
            <form method="POST" action="02_Actions/01_Authentication-CRUD/set-role.php">
            <input type="hidden" name="auth_mode" value="signup">
                <button type="submit" name="role_id" value="1" class="card-btn card-btn-primary w-100 mb-3 mt-2">
                    Patient
                </button>
                <button type="submit" name="role_id" value="2" class="card-btn card-btn-primary w-100">
                    Pharmacy Admin
                </button>
               <!-- <button type="submit" name="role_id" value="4" class="card-btn card-btn-primary w-100">
                    System Admin
                </button> -->
            </form>
        </div>

      </div>
    </main>

    <!-- END OF MAIN CONTENT -->
    <!-- ================================================================================================== -->

    <script>
      // Tab switching
      document.querySelectorAll(".card-tab").forEach((tab) => {
        tab.addEventListener("click", () => {
          const siblings = tab.parentElement.querySelectorAll(".card-tab");
          siblings.forEach((t) => t.classList.remove("active"));
          tab.classList.add("active");
        });
      });
    </script>

  </body>
</html>
