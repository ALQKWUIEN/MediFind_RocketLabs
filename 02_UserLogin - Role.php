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

    <!-- Transition Includes -->
    <?php include '01_Includes/page-transition.php'; ?>

  </head>
  <body id="swup" class="transition-fade">
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

        <div class="btn-stack">
        <a href="03_Authentication/login.php" class="card-btn card-btn-primary">
        Patient
          </a>
          <a href="03_Authentication/login.php" class="card-btn card-btn-primary">
             Pharmacy Admin
          </a>
          <a href="03_Authentication/login.php" class="card-btn card-btn-primary">
             System Admin
          </a>
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
