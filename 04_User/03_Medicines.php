<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MediFind: Medicines</title>

    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/medicines.css" />
    
    <!-- Page transition -->
    <?php include '../01_Includes/page-transition-hardcode.php'?>
    
  </head>
  <body>
    <div class="wrapper d-flex align-items-stretch">
      <div id="sidebar-container"></div>

      <div class="main-panel d-flex flex-column flex-grow-1">
        <div id="topbar-container"></div>

        <div id="content">
          <div class="content-body">
            <div class="hero">
              <!-- Search Section -->
              <div class="search-section">
                <div class="welcome-location-block">
                  <h2 class="welcome-user">Browse Medicines</h2>

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

                <!-- Categories -->
                <div class="quick-categories-section">
                  <div class="section-header">
                    <h5>Categories</h5>
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

                <!-- All Medicines -->
                <div class="all-medicines-section">
                  <div class="section-header">
                    <h5>All Medicines</h5>
                    <a href="#">See all</a>
                  </div>

                  <div class="medicine-list">
                    <div class="medicine-card">
                      <img
                        src="../07_Assets/images/medicines/Paracetamol.png"
                        alt="Paracetamol"
                        class="medicine-card-img"
                      />

                      <div class="medicine-card-body">
                        <div class="medicine-top">
                          <div>
                            <h6 class="medicine-name">Paracetamol</h6>
                            <p class="medicine-brand">Biogesic</p>
                          </div>
                          <span class="medicine-dose">500mg</span>
                        </div>

                        <p class="medicine-meta">Tablet · Analgesic</p>
                        <p class="medicine-price">
                          ₱5.00 <span>/ tablet</span>
                        </p>
                        <p class="medicine-stock">Available at 12 pharmacies</p>
                        <span class="medicine-tag no-rx">No Rx</span>

                        <a
                          class="view-details-btn"
                          style="text-decoration: none"
                          href="../04_User/03.2_MedicineDetails.php"
                          >View Details</a
                        >
                      </div>
                    </div>

                    <div class="medicine-card">
                      <img
                        src="../07_Assets/images/medicines/Ibuprofen.png"
                        alt="Ibuprofen"
                        class="medicine-card-img"
                      />

                      <div class="medicine-card-body">
                        <div class="medicine-top">
                          <div>
                            <h6 class="medicine-name">Ibuprofen</h6>
                            <p class="medicine-brand">Advil</p>
                          </div>
                          <span class="medicine-dose">200mg</span>
                        </div>

                        <p class="medicine-meta">Tablet · Analgesic</p>
                        <p class="medicine-price">
                          ₱7.50 <span>/ tablet</span>
                        </p>
                        <p class="medicine-stock">Available at 9 pharmacies</p>
                        <span class="medicine-tag no-rx">No Rx</span>

                        <a
                          class="view-details-btn"
                          style="text-decoration: none"
                          href="../04_User/03.2_MedicineDetails.php"
                          >View Details</a
                        >
                      </div>
                    </div>

                    <div class="medicine-card">
                      <img
                        src="../07_Assets/images/medicines/Amoxicilin.png"
                        alt="Amoxicillin"
                        class="medicine-card-img"
                      />

                      <div class="medicine-card-body">
                        <div class="medicine-top">
                          <div>
                            <h6 class="medicine-name">Amoxicillin</h6>
                            <p class="medicine-brand">Amoxil</p>
                          </div>
                          <span class="medicine-dose">500mg</span>
                        </div>

                        <p class="medicine-meta">Capsule · Antibiotic</p>
                        <p class="medicine-price">
                          ₱20.00 <span>/ capsule</span>
                        </p>
                        <p class="medicine-stock">Available at 8 pharmacies</p>
                        <span class="medicine-tag rx-required"
                          >Rx Required</span
                        >

                        <a
                          class="view-details-btn"
                          style="text-decoration: none"
                          href="../04_User/03.2_MedicineDetails.php"
                          >View Details</a
                        >
                      </div>
                    </div>

                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="floating-btns">
      <button class="float-btn" onclick="history.back()">
        <span class="material-symbols-outlined bot-icon">smart_toy</span>
      </button>
    </div>

    <script>
      const categories = document.getElementById("quickCategories");
      const prevBtn = document.querySelector(".category-nav.prev");
      const nextBtn = document.querySelector(".category-nav.next");
      const categoryItems = document.querySelectorAll(".category-item");

      function updateNavVisibility() {
        const isOverflowing = categories.scrollWidth > categories.clientWidth;
        prevBtn.style.display = isOverflowing ? "" : "none";
        nextBtn.style.display = isOverflowing ? "" : "none";
      }

      if (prevBtn && nextBtn && categories) {
        prevBtn.addEventListener("click", () => {
          categories.scrollBy({ left: -220, behavior: "smooth" });
        });

        nextBtn.addEventListener("click", () => {
          categories.scrollBy({ left: 220, behavior: "smooth" });
        });

        // Run on load and whenever the container resizes
        updateNavVisibility();
        new ResizeObserver(updateNavVisibility).observe(categories);
      }

      categoryItems.forEach((item) => {
        item.addEventListener("click", function () {
          categoryItems.forEach((el) => el.classList.remove("active"));
          this.classList.add("active");
        });
      });
    </script>

    <script src="../07_Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/popper.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

  </body>
</html>
