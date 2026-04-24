<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MediFind: Home</title>
    <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
    <link rel="icon" href="/07_Assets/images/logo.png" type="image/png" />
    <link href="/07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/popper.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

  </body>
</html>
