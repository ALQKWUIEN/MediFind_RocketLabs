<!doctype html>
<html lang="en" data-swup-theme  >
  <head data-swup-theme  >
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="/07_Assets/images/logo.png" type="image/png" />
    <title>MediFind: Malaybalay Medicine Availability Checker</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


    <!-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"/> -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap"rel="stylesheet"/>

   <!-- Assets -->
    <link rel="stylesheet" href="07_Assets/css/landing-style.css"/>
    <link rel="stylesheet" href="07_Assets/css/navbar.css"/>
    <link rel="stylesheet" href="07_Assets/node_modules/material-symbols/outlined.css" />


    <?php include '01_Includes/page-transition.php'; ?>

  </head>
  <body id="swup" class="transition-fade">
    
    <!-- START OF NAVBAR -->
    <?php include_once '01_Includes/navbar_landing-role.php' ?>

    <!-- HERO -->
    <div class="hero mt-5">
      <div class="hero-title">Medi<span>Find</span></div>
      <div class="hero-subtitle">Malaybalay Medicine Availability Checker</div>

      <div class="d-flex align-items-center justify-content-center gap-2">
        <div class="search-wrapper">
          <span class="material-symbols-outlined" style="color: #87a199; font-size: 1.2rem">search</span>
          <input type="text" placeholder="Search medicine by name..." />
        </div>

        <button class="search-bot-btn">
          <span class="material-symbols-outlined">smart_toy</span>
        </button>
      </div>
    </div>

    <!-- NEARBY PHARMACIES -->
    <div class="nearby-section">
      <div class="nearby-label">
        <span class="material-symbols-outlined loc">location_on</span>
        <div class="label-text">
          <strong>Nearby Pharmacies</strong>
          <a
            >Malaybalay, 8700, Bukidnon, Philippines<span
              class="material-symbols-outlined dropdown-ico"
              >arrow_drop_down</span
            ></a
          >
        </div>
      </div>

      <div class="pharmacy-cards">
        <!-- Card 1 -->
        <div class="pharmacy-card">
          <img
            src="07_Assets/images/pharmacies/RosePharmacy.png"
            alt="Rose Pharmacy"
          />
          <div class="card-body-custom">
            <div class="pharmacy-name">Rose Pharmacy</div>
            <!-- <div class="pharmacy-rating">
              3.0
              <span class="stars">★★★</span><span class="star-empty">★★</span>
              (1)
            </div> -->
            <div class="pharmacy-type">Faculty of pharmacy</div>

            <!-- <div class="card-tabs">
              <div class="card-tab active">Overview</div>
              <div class="card-tab">Reviews</div>
            </div> -->

            <div class="card-actions">
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">directions</span>
                </div>
                Directions
              </div>
              <!-- <div class="card-action-btn">
                <div class="card-action-icon">
                  <i class="fa-regular fa-bookmark"></i>
                </div>
                Save
              </div> -->
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">near_me</span>
                </div>
                Nearby
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">phone_iphone</span>
                </div>
                Send to phone
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">share</span>
                </div>
                Share
              </div>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="pharmacy-card">
          <img
            src="07_Assets/images/pharmacies/RosePharmacy.png"
            alt="Rose Pharmacy"
          />
          <div class="card-body-custom">
            <div class="pharmacy-name">Rose Pharmacy</div>
            <!-- <div class="pharmacy-rating">
              3.0
              <span class="stars">★★★</span><span class="star-empty">★★</span>
              (1)
            </div> -->
            <div class="pharmacy-type">Faculty of pharmacy</div>

            <!-- <div class="card-tabs">
              <div class="card-tab active">Overview</div>
              <div class="card-tab">Reviews</div>
            </div> -->

            <div class="card-actions">
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">directions</span>
                </div>
                Directions
              </div>
              <!-- <div class="card-action-btn">
                <div class="card-action-icon">
                  <i class="fa-regular fa-bookmark"></i>
                </div>
                Save
              </div> -->
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">near_me</span>
                </div>
                Nearby
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">phone_iphone</span>
                </div>
                Send to phone
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">share</span>
                </div>
                Share
              </div>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="pharmacy-card">
          <img
            src="07_Assets/images/pharmacies/RosePharmacy.png"
            alt="Rose Pharmacy"
          />
          <div class="card-body-custom">
            <div class="pharmacy-name">Rose Pharmacy</div>
            <!-- <div class="pharmacy-rating">
              3.0
              <span class="stars">★★★</span><span class="star-empty">★★</span>
              (1)
            </div> -->
            <div class="pharmacy-type">Faculty of pharmacy</div>

            <!-- <div class="card-tabs">
              <div class="card-tab active">Overview</div>
              <div class="card-tab">Reviews</div>
            </div> -->

            <div class="card-actions">
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">directions</span>
                </div>
                Directions
              </div>
              <!-- <div class="card-action-btn">
                <div class="card-action-icon">
                  <i class="fa-regular fa-bookmark"></i>
                </div>
                Save
              </div> -->
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">near_me</span>
                </div>
                Nearby
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">phone_iphone</span>
                </div>
                Send to phone
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">share</span>
                </div>
                Share
              </div>
            </div>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="pharmacy-card">
          <img
            src="07_Assets/images/pharmacies/RosePharmacy.png"
            alt="Rose Pharmacy"
          />
          <div class="card-body-custom">
            <div class="pharmacy-name">Rose Pharmacy</div>
            <!-- <div class="pharmacy-rating">
              3.0
              <span class="stars">★★★</span><span class="star-empty">★★</span>
              (1)
            </div> -->
            <div class="pharmacy-type">Faculty of pharmacy</div>

            <!-- <div class="card-tabs">
              <div class="card-tab active">Overview</div>
              <div class="card-tab">Reviews</div>
            </div> -->

            <div class="card-actions">
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">directions</span>
                </div>
                Directions
              </div>
              <!-- <div class="card-action-btn">
                <div class="card-action-icon">
                  <i class="fa-regular fa-bookmark"></i>
                </div>
                Save
              </div> -->
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">near_me</span>
                </div>
                Nearby
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">phone_iphone</span>
                </div>
                Send to phone
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">share</span>
                </div>
                Share
              </div>
            </div>
          </div>
        </div>

        <!-- Card 5 -->
        <div class="pharmacy-card">
          <img
            src="07_Assets/images/pharmacies/RosePharmacy.png"
            alt="Rose Pharmacy"
          />
          <div class="card-body-custom">
            <div class="pharmacy-name">Rose Pharmacy</div>
            <!-- <div class="pharmacy-rating">
              3.0
              <span class="stars">★★★</span><span class="star-empty">★★</span>
              (1)
            </div> -->
            <div class="pharmacy-type">Faculty of pharmacy</div>

            <!-- <div class="card-tabs">
              <div class="card-tab active">Overview</div>
              <div class="card-tab">Reviews</div>
            </div> -->

            <div class="card-actions">
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">directions</span>
                </div>
                Directions
              </div>
              <!-- <div class="card-action-btn">
                <div class="card-action-icon">
                  <i class="fa-regular fa-bookmark"></i>
                </div>
                Save
              </div> -->
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">near_me</span>
                </div>
                Nearby
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">phone_iphone</span>
                </div>
                Send to phone
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">share</span>
                </div>
                Share
              </div>
            </div>
          </div>
        </div>

        <!-- Card 6 -->
        <div class="pharmacy-card">
          <img
            src="07_Assets/images/pharmacies/RosePharmacy.png"
            alt="Rose Pharmacy"
          />
          <div class="card-body-custom">
            <div class="pharmacy-name">Rose Pharmacy</div>
            <!-- <div class="pharmacy-rating">
              3.0
              <span class="stars">★★★</span><span class="star-empty">★★</span>
              (1)
            </div> -->
            <div class="pharmacy-type">Faculty of pharmacy</div>

            <!-- <div class="card-tabs">
              <div class="card-tab active">Overview</div>
              <div class="card-tab">Reviews</div>
            </div> -->

            <div class="card-actions">
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">directions</span>
                </div>
                Directions
              </div>
              <!-- <div class="card-action-btn">
                <div class="card-action-icon">
                  <i class="fa-regular fa-bookmark"></i>
                </div>
                Save
              </div> -->
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">near_me</span>
                </div>
                Nearby
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">phone_iphone</span>
                </div>
                Send to phone
              </div>
              <div class="card-action-btn">
                <div class="card-action-icon">
                  <span class="material-symbols-outlined">share</span>
                </div>
                Share
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- END OF MAIN CONTENT -->

    <!-- ================================================================================================== -->

    <!-- FOOTER -->

    <footer class="site-footer">
      <div class="footer-content">
        <p class="footer-brand">MediFind</p>
        <p class="footer-text">Malaybalay Medicine Availability Checker</p>
        <p class="footer-copy">© 2026 MediFind. All rights reserved.</p>
      </div>
    </footer>

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
