<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Medicine Details – MediFind</title>

    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link
      href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="../07_Assets/node_modules/material-symbols/outlined.css"
    />
    <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/medicinedetails.css" />
  </head>
  <body>
    <div class="wrapper d-flex align-items-stretch">
      <div id="sidebar-container"></div>

      <div class="main-panel d-flex flex-column flex-grow-1">
        <div id="topbar-container"></div>

        <div id="content">
          <div class="content-body">
            <!-- Back button -->
            <a class="back-btn" onclick="history.back()" href="#">
              <span class="material-symbols-outlined">arrow_back</span>
              Back
            </a>

            <!-- ── Hero card ── -->
            <div class="hero-card">
              <div class="hero-inner">
                <div class="medicine-img-wrap">
                  <img
                    src="../07_Assets/images/medicines/Ibuprofen.png"
                    alt="Ibuprofen 200mg"
                  />
                </div>
                <div class="hero-info">
                  <div class="badge-row">
                    <span class="medicine-tag no-rx">No Rx</span>
                    <span class="medicine-tag category">Analgesic</span>
                    <span class="medicine-tag form">Tablet</span>
                    
                  </div>
                  <h1 class="medicine-title">Ibuprofen 200mg</h1>
                  <p class="medicine-subtitle">
                    Advil &nbsp;·&nbsp; Manufactured by Unilab
                  </p>
                  <div class="price-row">
                    <span class="price-main">₱2.75</span>
                    <span class="price-unit">/ tablet</span>
                  </div>
                  <p class="price-range-text">
                    Price range: ₱2.75 – ₱5.00 across pharmacies
                  </p>
                </div>
              </div>

              <hr class="hero-divider" />

              <!-- Quick info strip -->
              <div class="info-grid">
                <div class="info-cell">
                  <p class="info-cell-label">Generic name</p>
                  <p class="info-cell-value">Ibuprofen</p>
                </div>
                <div class="info-cell">
                  <p class="info-cell-label">Dosage</p>
                  <p class="info-cell-value">200 mg</p>
                </div>
                <div class="info-cell">
                  <p class="info-cell-label">Form</p>
                  <p class="info-cell-value">Softgel Capsule</p>
                </div>
                <div class="info-cell">
                  <p class="info-cell-label">Best price</p>
                  <p class="info-cell-value accent">₱2.75 / tab</p>
                </div>
              </div>
            </div>

            <!-- ── Rx notice ── -->
            <div class="rx-notice">
              <span class="material-symbols-outlined">info</span>
              <p>
                This medicine does not require a prescription. Over-the-counter
                purchase only.
              </p>
            </div>

            <!-- ── Pharmacy availability ── -->
            <div class="section-head">
              <h2 class="section-title">
                Nearby pharmacies
                <span class="count-pill">9 available</span>
              </h2>
              <button class="sort-btn">
                <span class="material-symbols-outlined">swap_vert</span>
                Sort by price
              </button>
            </div>

            <div class="pharmacy-list">
              <!-- Rose Pharmacy (best) -->
              <div class="pharmacy-card is-best">
                <div
                  class="pharmacy-logo"
                  style="background: #fde8e8; color: #c0392b"
                >
                  R
                </div>
                <div class="pharmacy-body">
                  <div class="pharmacy-name-row">
                    <p class="pharmacy-name">Rose Pharmacy</p>
                    <span class="best-badge">Lowest price</span>
                  </div>
                  <div class="pharmacy-meta">
                    <span
                      ><span class="material-symbols-outlined">schedule</span
                      ><span class="open">Open · closes 10PM</span></span
                    >
                    <span
                      ><span class="material-symbols-outlined">near_me</span>0.8
                      km away</span
                    >
                  </div>
                  <button class="directions-btn">
                    <span class="material-symbols-outlined">directions</span>
                    Get directions
                  </button>
                </div>
                <div class="pharmacy-price-col">
                  <p class="ph-price">₱2.75</p>
                  <p class="ph-price-unit">per tablet</p>
                </div>
              </div>

              <!-- Mercury Drug -->
              <div class="pharmacy-card">
                <div
                  class="pharmacy-logo"
                  style="background: #e8f0fe; color: #1a56a8"
                >
                  M
                </div>
                <div class="pharmacy-body">
                  <div class="pharmacy-name-row">
                    <p class="pharmacy-name">Mercury Drug</p>
                  </div>
                  <div class="pharmacy-meta">
                    <span
                      ><span class="material-symbols-outlined">schedule</span
                      ><span class="open">Open · closes 11PM</span></span
                    >
                    <span
                      ><span class="material-symbols-outlined">near_me</span>1.2
                      km away</span
                    >
                  </div>
                  <button class="directions-btn">
                    <span class="material-symbols-outlined">directions</span>
                    Get directions
                  </button>
                </div>
                <div class="pharmacy-price-col">
                  <p class="ph-price">₱3.25</p>
                  <p class="ph-price-unit">per tablet</p>
                </div>
              </div>

              <!-- Generika -->
              <div class="pharmacy-card">
                <div
                  class="pharmacy-logo"
                  style="background: #e8f6ee; color: #1a7a4a"
                >
                  G
                </div>
                <div class="pharmacy-body">
                  <div class="pharmacy-name-row">
                    <p class="pharmacy-name">Generika</p>
                  </div>
                  <div class="pharmacy-meta">
                    <span
                      ><span class="material-symbols-outlined">schedule</span
                      ><span class="open">Open · closes 9PM</span></span
                    >
                    <span
                      ><span class="material-symbols-outlined">near_me</span>1.5
                      km away</span
                    >
                  </div>
                  <button class="directions-btn">
                    <span class="material-symbols-outlined">directions</span>
                    Get directions
                  </button>
                </div>
                <div class="pharmacy-price-col">
                  <p class="ph-price">₱3.50</p>
                  <p class="ph-price-unit">per tablet</p>
                </div>
              </div>

              <!-- Watsons (closed) -->
              <div class="pharmacy-card is-closed">
                <div
                  class="pharmacy-logo"
                  style="background: #fff3e0; color: #b45309"
                >
                  W
                </div>
                <div class="pharmacy-body">
                  <div class="pharmacy-name-row">
                    <p class="pharmacy-name">Watsons</p>
                  </div>
                  <div class="pharmacy-meta">
                    <span
                      ><span class="material-symbols-outlined">schedule</span
                      ><span class="closed">Closed · opens 9AM</span></span
                    >
                    <span
                      ><span class="material-symbols-outlined">near_me</span>2.1
                      km away</span
                    >
                  </div>
                </div>
                <div class="pharmacy-price-col">
                  <p class="ph-price muted">₱5.00</p>
                  <p class="ph-price-unit">per tablet</p>
                </div>
              </div>
            </div>
            <!-- /pharmacy-list -->
          </div>
          <!-- /content-body -->

          <!-- ── Sticky footer ── -->
          <div class="sticky-footer">
            <button class="btn-back-footer" onclick="history.back()">
              <span class="material-symbols-outlined">arrow_back</span>
              Back
            </button>
            <button class="btn-cta">
              <span class="material-symbols-outlined">location_on</span>
              Find nearest pharmacy
            </button>
          </div>
        </div>
        <!-- /content -->
      </div>
    </div>

    <script src="../07_Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

  </body>
</html>
