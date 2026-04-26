<!doctype html>
<html class="is-animating">
  <head >
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reports</title>

    <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />

    <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Page transition -->
    <?php include '../01_Includes/page-transition-hardcode.php'?>
    
  </head>
  <body>

  
    <div class="wrapper d-flex align-items-stretch">
        <div id="pharmacy-sidebar-container"></div>

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


  </body>
</html>
