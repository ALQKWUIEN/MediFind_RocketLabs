<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <title>Pharmacy Dashboard</title>

    <!-- Bootstrap and Js Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- CSS and other Assets -->
    <link rel="stylesheet" href="../07_Assets/css/sidebar.css">
    <link rel="stylesheet" href="../07_Assets/css/topbar.css">
    <link rel="stylesheet" href="../07_Assets/css/04_Includes CSS/sidebar.css">    
    <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
    <link rel="stylesheet" href="../07_Assets/node_modules/material-symbols/outlined.css" />
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap"     rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- JavaScript -->

    

    <!-- Page Transition -->
    <?php include '../01_Includes/page-transition-hardcode.php'; ?>

</head>

<body>


    <div class="wrapper d-flex align-items-stretch"> 

        <!-- Include Pharmacy Sidebar -->
        <?php include '../01_Includes/02_pharmacy-sidebar.php'; ?>
    
        <!-- Main Content -->
         <div class="main-panel d-flex flex-column flex-grow-1">

             <!-- Include Bar -->
            <?php include '../01_Includes/topbar.php'; ?>
              
            <!-- Page Content -->

         </div>
       
    </div>

























    <div class="wrapper d-flex align-items-stretch">
      <?php include '../01_Includes/02_pharmacy-sidebar.php'; ?>

      <div class="main-panel d-flex flex-column flex-grow-1">
      <?php include '../01_Includes/topbar.php'; ?>


        <div id="content">
          <div class="content-body">
            <div class="hero">
              <!-- Search Section -->
              <div class="search-section">
                <div class="welcome-location-block">
                  <h2 class="welcome-user">Hello, User 👋</h2>

                  <div class="user-location">
                    <span class="material-symbols-outlined">location_on</span>
                    <span>Malaybalay, 8700, Bukidnon, Philippines</span>
                  </div>
                </div>

                <div class="search-row">
                  <div class="search-wrapper">
                    <span
                      class="material-symbols-outlined"
                      style="color: #87a199; font-size: 1.2rem"
                    >
                      search
                    </span>
                    <input
                      type="text"
                      placeholder="Search medicine by name..."
                    />
                  </div>

                  <button class="search-filter-btn">
                    <span class="material-symbols-outlined">
                      discover_tune
                    </span>
                  </button>
                </div>

                <!-- Quick Categories -->
                <div class="quick-categories-section">
                  <div class="section-header">
                    <h5>Quick Categories</h5>
                    <a href="#">See all</a>
                  </div>

                  <div class="quick-categories-wrapper">
                    <button class="category-nav prev" aria-label="Scroll left">
                      <span class="material-symbols-outlined"
                        >chevron_left</span
                      >
                    </button>

                    <div class="quick-categories" id="quickCategories">
                      <a href="#" class="category-item active">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined"
                            >pulmonology</span
                          >
                        </div>
                        <span class="category-label">Cough & Cold</span>
                      </a>

                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">sick</span>
                        </div>
                        <span class="category-label">Pain Relief</span>
                      </a>

                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined"
                            >medication</span
                          >
                        </div>
                        <span class="category-label">Antibiotics</span>
                      </a>

                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined"
                            >ecg_heart</span
                          >
                        </div>
                        <span class="category-label">Heart Care</span>
                      </a>

                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined"
                            >water_drop</span
                          >
                        </div>
                        <span class="category-label">Diabetes</span>
                      </a>

                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">allergy</span>
                        </div>
                        <span class="category-label">Allergy</span>
                      </a>

                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined"
                            >vaccines</span
                          >
                        </div>
                        <span class="category-label">Vitamins</span>
                      </a>

                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">healing</span>
                        </div>
                        <span class="category-label">First Aid</span>
                      </a>

                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined"
                            >gastroenterology</span
                          >
                        </div>
                        <span class="category-label">Digestive Care</span>
                      </a>

                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">bedtime</span>
                        </div>
                        <span class="category-label">Sleep Care</span>
                      </a>
                    </div>

                    <button class="category-nav next" aria-label="Scroll right">
                      <span class="material-symbols-outlined"
                        >chevron_right</span
                      >
                    </button>
                  </div>
                </div>

                <!-- Nearby Pharmacies -->
                <div class="nearby-section">
                  <div class="section-header">
                    <h5>Nearby Pharmacies</h5>
                    <a href="#">See all</a>
                  </div>

                  <div class="pharmacy-list">
                    <div class="pharmacy-card">
                      <img
                        src="../07_Assets/images/pharmacies/RosePharmacy.png"
                        alt="Rojon Pharmacy"
                        class="pharmacy-card-img"
                      />

                      <div class="pharmacy-card-body">
                        <h6>Rojon Pharmacy</h6>

                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined"
                            >location_on</span
                          >
                          <span>0.8 km away</span>
                        </div>

                        <div class="pharmacy-status">
                          <span class="open-text">Open</span>
                          <span class="dot">·</span>
                          <span>Closes 10PM</span>
                        </div>

                        <button class="direction-btn">
                          <span class="material-symbols-outlined"
                            >north_east</span
                          >
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div class="pharmacy-card">
                      <img
                        src="../07_Assets/images/pharmacies/RosePharmacy.png"
                        alt="Rojon Pharmacy"
                        class="pharmacy-card-img"
                      />

                      <div class="pharmacy-card-body">
                        <h6>Rojon Pharmacy</h6>

                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined"
                            >location_on</span
                          >
                          <span>0.8 km away</span>
                        </div>

                        <div class="pharmacy-status">
                          <span class="open-text">Open</span>
                          <span class="dot">·</span>
                          <span>Closes 10PM</span>
                        </div>

                        <button class="direction-btn">
                          <span class="material-symbols-outlined"
                            >north_east</span
                          >
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div class="pharmacy-card">
                      <img
                        src="../07_Assets/images/pharmacies/RosePharmacy.png"
                        alt="Rojon Pharmacy"
                        class="pharmacy-card-img"
                      />

                      <div class="pharmacy-card-body">
                        <h6>Rojon Pharmacy</h6>

                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined"
                            >location_on</span
                          >
                          <span>0.8 km away</span>
                        </div>

                        <div class="pharmacy-status">
                          <span class="open-text">Open</span>
                          <span class="dot">·</span>
                          <span>Closes 10PM</span>
                        </div>

                        <button class="direction-btn">
                          <span class="material-symbols-outlined"
                            >north_east</span
                          >
                          Get Directions
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="most-searched-section">
              <div class="section-header">
                <h5>Most Searched Today</h5>
                <a href="#">See all</a>
              </div>

              <div class="most-searched-list">
                <div class="medicine-rank-card">
                  <div class="medicine-rank">1</div>
                  <div class="medicine-icon-box">
                    <span class="material-symbols-outlined">vaccines</span>
                  </div>
                  <div class="medicine-info">
                    <h6>Amoxicillin 500mg</h6>
                    <p>Antibiotic · 142 searches</p>
                  </div>
                </div>

                <div class="medicine-rank-card">
                  <div class="medicine-rank">2</div>
                  <div class="medicine-icon-box">
                    <span class="material-symbols-outlined">water_drop</span>
                  </div>
                  <div class="medicine-info">
                    <h6>Metformin 850mg</h6>
                    <p>Diabetes · 98 searches</p>
                  </div>
                </div>

                <div class="medicine-rank-card">
                  <div class="medicine-rank">3</div>
                  <div class="medicine-icon-box">
                    <span class="material-symbols-outlined">ecg_heart</span>
                  </div>
                  <div class="medicine-info">
                    <h6>Losartan 50mg</h6>
                    <p>Heart · 76 searches</p>
                  </div>
                </div>

                <div class="medicine-rank-card">
                  <div class="medicine-rank">4</div>
                  <div class="medicine-icon-box">
                    <span class="material-symbols-outlined">vaccines</span>
                  </div>
                  <div class="medicine-info">
                    <h6>Amoxicillin 500mg</h6>
                    <p>Antibiotic · 142 searches</p>
                  </div>
                </div>

                <div class="medicine-rank-card">
                  <div class="medicine-rank">5</div>
                  <div class="medicine-icon-box">
                    <span class="material-symbols-outlined">water_drop</span>
                  </div>
                  <div class="medicine-info">
                    <h6>Metformin 850mg</h6>
                    <p>Diabetes · 98 searches</p>
                  </div>
                </div>

                <div class="medicine-rank-card">
                  <div class="medicine-rank">6</div>
                  <div class="medicine-icon-box">
                    <span class="material-symbols-outlined">ecg_heart</span>
                  </div>
                  <div class="medicine-info">
                    <h6>Losartan 50mg</h6>
                    <p>Heart · 76 searches</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</body>
</html>