<?php

// Prevent back button after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");



include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

// ── Guard: must be logged in ───────────────────────────────────────
if (!$_SESSION['user_id']) {
    header('Location: ../03_Authentication/login.php');
    exit;
}
?>


<!doctype html>
<html lang="en" class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MediFind: Scan Prescriptions</title>
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <!-- <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" /> -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/scanrx.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/scanner.css" />

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    .scanrx-card {
      background: #ffffff;
      border-radius: 18px;
      border: 1px solid #e5ece9;
      box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
      padding: 24px;
    }
    .rx-dropzone {
      border: 2px dashed #cfe2db;
      border-radius: 16px;
      background: #fafcfb;
      padding: 36px 24px;
      text-align: center;
      transition: border-color 0.2s ease, background 0.2s ease;
    }
    .rx-dropzone.dragover {
      border-color: #1d9e75;
      background: #eef9f4;
    }
    .rx-dropzone-icon {
      width: 54px;
      height: 54px;
      border-radius: 14px;
      background: #e8f7f2;
      color: #1d9e75;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 14px;
    }
    .rx-dropzone-icon .material-symbols-outlined {
      font-size: 1.9rem;
    }
    .rx-btn-camera {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: #1d9e75;
      color: #ffffff;
      border: 1.5px solid #1d9e75;
      border-radius: 99px;
      padding: 9px 20px;
      font-size: 0.86rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.18s;
    }
    .rx-btn-camera:hover {
      background: #178360;
      border-color: #178360;
    }
    .rx-btn-upload {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: #ffffff;
      color: #1a2e27;
      border: 1.5px solid #9ec9ba;
      border-radius: 99px;
      padding: 9px 20px;
      font-size: 0.86rem;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.18s, border-color 0.18s;
    }
    .rx-btn-upload:hover {
      background: #eef9f4;
      border-color: #1d9e75;
    }
    .rx-manual-link {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      color: #1d9e75;
      font-size: 0.84rem;
      font-weight: 600;
      text-decoration: none;
    }
    .rx-manual-link:hover {
      color: #146f50;
      text-decoration: underline;
    }
    .rx-privacy-banner {
      background: #eef8f4;
      border: 1px solid #d2ece2;
      border-radius: 12px;
      padding: 12px 14px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 0.82rem;
      color: #2f5447;
      line-height: 1.5;
    }
    .recent-scan-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding: 12px 0;
      border-bottom: 1px solid #eef2f0;
    }
    .recent-scan-row:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }
    .recent-scan-med {
      font-weight: 600;
      font-size: 0.86rem;
      color: #1a2e27;
    }
    .recent-scan-date {
      font-size: 0.8rem;
      color: #6b8f82;
      white-space: nowrap;
    }
    .recent-view-btn {
      background: #e6f6ef;
      color: #0f6e56;
      border: none;
      border-radius: 99px;
      padding: 4px 14px;
      font-size: 0.78rem;
      font-weight: 600;
      cursor: pointer;
    }
    .recent-view-btn:hover {
      background: #d0efe1;
    }
    .recent-del-btn {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      border: 1px solid #e2e8e6;
      background: #ffffff;
      color: #64748b;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }
    .recent-del-btn:hover {
      background: #fef2f2;
      color: #dc2626;
      border-color: #fecaca;
    }

    .mf-scan-search-bar {
      display: flex;
      align-items: center;
      gap: 8px;
      background: #ffffff;
      border: 1px solid #dfe8e4;
      border-radius: 14px;
      padding: 8px 10px 8px 14px;
      margin: 14px 0 22px;
      box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);
      width: 100%;
      max-width: none;
    }

    .mf-scan-search-bar input {
      border: none;
      outline: none;
      background: transparent;
      font-size: 0.9rem;
      color: #1f2937;
      width: 100%;
    }

    .mf-scan-search-btn {
      border: none;
      background: #1d9e75;
      color: #ffffff;
      border-radius: 10px;
      padding: 6px 14px;
      font-size: 0.82rem;
      font-weight: 600;
      white-space: nowrap;
      cursor: pointer;
      transition: background 0.16s ease;
    }

    .mf-scan-search-btn:hover {
      background: #178764;
    }

    @media (max-width: 767.98px) {
      #content,
      .content-body {
        padding: 0 !important;
        margin: 0 !important;
        background: #ffffff !important;
      }

      .mf-scan-hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #178a65 0%, #1d9e75 100%);
        padding: 18px 16px 34px;
      }

      .mf-scan-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: url("../07_Assets/images/backgrounds/malaybalay-hero-bg.jpg") center / cover no-repeat;
        opacity: 0.22;
        mix-blend-mode: luminosity;
        pointer-events: none;
        z-index: 0;
      }

      .mf-scan-hero > * {
        position: relative;
        z-index: 1;
      }

      .mf-scan-hero .page-title-block {
        margin-bottom: 0 !important;
      }

      .mf-scan-hero .page-title-text h2 {
        color: #ffffff !important;
        font-size: 1.35rem !important;
        font-weight: 700 !important;
        margin: 0 0 2px !important;
      }

      .mf-scan-hero .page-title-text p {
        color: rgba(255, 255, 255, 0.9) !important;
        font-size: 0.84rem !important;
        margin: 0 !important;
      }

      .mf-scan-search-bar {
        border: none !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        max-width: none;
        margin-top: 14px;
      }

      .mf-scan-sheet {
        background: #ffffff;
        border-top-left-radius: 26px;
        border-top-right-radius: 26px;
        margin-top: -18px;
        padding: 18px 14px 96px;
        position: relative;
        z-index: 2;
      }
    }
  </style>

</head>

<body data-active="02">
  <div class="wrapper d-flex align-items-stretch">
    <div id="sidebar-container"><?php include '../01_Includes/users-sidebar-fixed.php'; ?></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content">
        <div class="content-body">
          <!-- Page Title + Manual Medicine Search -->
          <div class="mf-scan-hero">
            <div class="page-title-block">
              <div class="page-title-text">
                <h2>Scan Prescription</h2>
                <p>Upload, scan with camera, or search medicine manually</p>
              </div>
            </div>

            <form class="mf-scan-search-bar" id="rxManualSearchForm">
              <span class="material-symbols-outlined" style="color:#87a199;font-size:1.15rem;">search</span>
              <input type="text" id="rxManualSearchInput" placeholder="Search medicine manually..." autocomplete="off" />
              <button type="submit" class="mf-scan-search-btn">Search</button>
            </form>
          </div>

          <div class="mf-scan-sheet">
          <!-- Two-column layout matching Scan Rx design -->
          <div class="row g-4 align-items-start">
            <!-- LEFT: Prescription Reader -->
            <div class="col-lg-7">
              <div class="scanrx-card">
                <div class="rx-dropzone" id="rxDropzone">
                  <div class="rx-dropzone-icon">
                    <span class="material-symbols-outlined">add_a_photo</span>
                  </div>
                  <h4 style="font-weight:700;color:#1a2e27;margin-bottom:6px;">Prescription Reader</h4>
                  <p style="font-size:0.86rem;color:#5c756c;margin-bottom:2px;">
                    Drag &amp; drop your prescription here, or choose an option below
                  </p>
                  <p style="font-size:0.78rem;color:#87a199;margin-bottom:20px;">
                    PNG or JPEG &bull; Max 5 MB
                  </p>

                  <div class="d-flex flex-wrap justify-content-center gap-2">
                    <button type="button" class="rx-btn-camera" id="rxCameraBtn">
                      <span class="material-symbols-outlined" style="font-size:1.1rem;">photo_camera</span>
                      Scan with Camera
                    </button>
                    <button type="button" class="rx-btn-upload" id="rxUploadBtn">
                      <span class="material-symbols-outlined" style="font-size:1.1rem;">upload</span>
                      Upload PNG / JPEG
                    </button>
                  </div>

                  <input type="file" id="rxCameraInput" accept="image/png,image/jpeg" capture="environment" hidden />
                  <input type="file" id="rxFileInput" accept="image/png,image/jpeg" hidden />
                </div>

                <div class="text-center my-3">
                  <a href="../04_User/03_Medicines.php" class="rx-manual-link">
                    Handwriting unclear or no prescription? Search medicine manually &rarr;
                  </a>
                </div>

                <div class="rx-privacy-banner">
                  <span class="material-symbols-outlined" style="color:#1d9e75;font-size:1.25rem;flex-shrink:0;margin-top:1px;">verified_user</span>
                  <div>
                    <strong>Your privacy is protected:</strong> Prescription images are deleted immediately after scanning, and personal details are removed before AI processing.
                  </div>
                </div>
              </div>
            </div>

            <!-- RIGHT: Recent Scans -->
            <div class="col-lg-5">
              <div class="scanrx-card">
                <h5 style="font-weight:700;color:#1a2e27;margin-bottom:4px;">Recent Scans</h5>
                <p style="font-size:0.8rem;color:#6b8f82;margin-bottom:14px;">
                  Saved medicine text only &bull; Original images are not stored.
                </p>

                <div id="recentScansList">
                  <div class="recent-scan-row">
                    <span class="recent-scan-med">Amoxicillin &bull; 500 mg</span>
                    <div class="d-flex align-items-center gap-2">
                      <span class="recent-scan-date">Dec 31, 2026</span>
                      <button type="button" class="recent-view-btn" onclick="openRxModal('Amoxicillin 500 mg', 'Dec 31, 2026')">View</button>
                      <button type="button" class="recent-del-btn" onclick="this.closest('.recent-scan-row').remove()" title="Delete">
                        <span class="material-symbols-outlined" style="font-size:1rem;">delete</span>
                      </button>
                    </div>
                  </div>

                  <div class="recent-scan-row">
                    <span class="recent-scan-med">Metformin &bull; 850 mg</span>
                    <div class="d-flex align-items-center gap-2">
                      <span class="recent-scan-date">Nov 15, 2026</span>
                      <button type="button" class="recent-view-btn" onclick="openRxModal('Metformin 850 mg', 'Nov 15, 2026')">View</button>
                      <button type="button" class="recent-del-btn" onclick="this.closest('.recent-scan-row').remove()" title="Delete">
                        <span class="material-symbols-outlined" style="font-size:1rem;">delete</span>
                      </button>
                    </div>
                  </div>

                  <div class="recent-scan-row">
                    <span class="recent-scan-med">Losartan &bull; 50 mg</span>
                    <div class="d-flex align-items-center gap-2">
                      <span class="recent-scan-date">Oct 08, 2026</span>
                      <button type="button" class="recent-view-btn" onclick="openRxModal('Losartan 50 mg', 'Oct 08, 2026')">View</button>
                      <button type="button" class="recent-del-btn" onclick="this.closest('.recent-scan-row').remove()" title="Delete">
                        <span class="material-symbols-outlined" style="font-size:1rem;">delete</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          </div><!-- /.mf-scan-sheet -->
        </div>
      </div>
    </div>
  </div>


  <!-- View Prescription Modal -->
  <div class="modal-overlay" id="rxModal" onclick="closeRxModal(event)">
    <div class="modal-card">
      <div class="rxm-header">
        <div class="rxm-header-left">
          <span class="material-symbols-outlined" style="color:#1d9e75;font-size:1.3rem">medication</span>
          <h5 id="modalMedicineName">Scanned Prescription</h5>
        </div>
        <button class="rxm-close-btn" onclick="closeModal()">
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>

      <!-- Desktop 2-column body -->
      <div class="rxm-body rxm-body-with-image">
        <!-- Single Prescription Image (Desktop: Left column) -->
        <div class="modal-image-wrap" id="modalImageWrap">
          <img id="modalRxImage" src="../07_Assets/images/sample-rx/1.jpeg" alt="Scanned Prescription" />
          <p class="modal-image-hint" id="modalImageHint">Scanned Prescription</p>
        </div>

        <!-- Extracted Text (+ Right-side slider on Desktop when multi-med) -->
        <div class="rxm-details-wrap" id="modalSlideText">
          <div class="rxm-details" id="desktopDetailsList">
            <div class="rxm-row">
              <span class="rxm-label">Date Scanned</span>
              <span class="rxm-value" id="modalDateScanned">Nov 25, 2026</span>
            </div>

            <div class="rxm-row">
              <span class="rxm-label">Dosage</span>
              <span class="rxm-value" id="modalDosage">500 mg</span>
            </div>

            <div class="rxm-row">
              <span class="rxm-label">Frequency</span>
              <span class="rxm-value" id="modalFrequency">3x a day</span>
            </div>

            <div class="rxm-row">
              <span class="rxm-label">Duration</span>
              <span class="rxm-value" id="modalDuration">7 days</span>
            </div>

            <div class="rxm-row">
              <span class="rxm-label">Prescribed by</span>
              <span class="rxm-value" id="modalDoctor">Dr. Santos</span>
            </div>
          </div>

          <div class="rxm-slider-nav rxm-desktop-slider-nav" id="desktopSliderNav" style="display:none;">
            <button type="button" class="rxm-slide-arrow" onclick="stepModalMed(-1)" aria-label="Previous medicine">
              <span class="material-symbols-outlined" style="font-size:1.1rem;">chevron_left</span>
            </button>
            <div class="rxm-slide-dots" id="desktopSliderDots"></div>
            <button type="button" class="rxm-slide-arrow" onclick="stepModalMed(1)" aria-label="Next medicine">
              <span class="material-symbols-outlined" style="font-size:1.1rem;">chevron_right</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile smooth horizontal carousel -->
      <div class="rxm-mobile-carousel" id="rxModalSwipeArea">
        <div class="rxm-mobile-track" id="rxModalMobileTrack"></div>
      </div>

      <!-- Mobile Carousel Controls (Slide 1: Prescription Image, Slide 2+: Extracted Text) -->
      <div class="rxm-mobile-carousel-bar" id="mobileCarouselBar">
        <button type="button" class="rxm-slide-arrow" onclick="stepMobileSlide(-1)" aria-label="Previous slide">
          <span class="material-symbols-outlined" style="font-size:1.1rem;">chevron_left</span>
        </button>
        <div class="d-flex flex-column align-items-center gap-1">
          <div class="rxm-slide-dots" id="mobileSlideDots"></div>
          <span class="rxm-mobile-slide-label" id="mobileSlideLabel">1 / 2 &bull; Prescription Image</span>
        </div>
        <button type="button" class="rxm-slide-arrow" onclick="stepMobileSlide(1)" aria-label="Next slide">
          <span class="material-symbols-outlined" style="font-size:1.1rem;">chevron_right</span>
        </button>
      </div>

      <div class="rxm-footer">
        <button type="button" class="btn-map-view" id="modalViewMapBtn" onclick="viewModalInMap()">
          <span class="material-symbols-outlined">map</span>
          View in Map
        </button>
      </div>
    </div>
  </div>


  <script>
    const DEFAULT_RX_IMAGE = "../07_Assets/images/sample-rx/1.jpeg";

    let modalScanItems = [];
    let modalActiveMedIdx = 0;
    let modalMobileSlide = 0; // 0 = single prescription image, 1..N = extracted medicine text
    let modalScanDate = "";
    let modalRxImgSrc = DEFAULT_RX_IMAGE;

    function buildMobileSlides() {
      const track = document.getElementById('rxModalMobileTrack');
      if (!track) return;

      const imgHint = modalScanItems.length > 1
        ? `Prescription (${modalScanItems.length} medicines)`
        : "Scanned Prescription";

      const imageSlide = `
        <div class="rxm-mobile-slide">
          <div class="modal-image-wrap">
            <img src="${modalRxImgSrc || DEFAULT_RX_IMAGE}" alt="Scanned Prescription" />
            <p class="modal-image-hint">${imgHint}</p>
          </div>
        </div>
      `;

      const textSlides = modalScanItems.map(item => `
        <div class="rxm-mobile-slide">
          <div class="rxm-details">
            <div class="rxm-row">
              <span class="rxm-label">Date Scanned</span>
              <span class="rxm-value">${modalScanDate || "Nov 25, 2026"}</span>
            </div>
            <div class="rxm-row">
              <span class="rxm-label">Dosage</span>
              <span class="rxm-value">${item.dosage || item.strength || "500 mg"}</span>
            </div>
            <div class="rxm-row">
              <span class="rxm-label">Frequency</span>
              <span class="rxm-value">${item.frequency || "3x a day"}</span>
            </div>
            <div class="rxm-row">
              <span class="rxm-label">Duration</span>
              <span class="rxm-value">${item.duration || "7 days"}</span>
            </div>
            <div class="rxm-row">
              <span class="rxm-label">Prescribed by</span>
              <span class="rxm-value">${item.doctor || "Dr. Santos"}</span>
            </div>
          </div>
        </div>
      `).join("");

      track.innerHTML = imageSlide + textSlides;
    }

    function renderModalMedState() {
      const medIndexForText = window.innerWidth <= 768
        ? Math.max(0, modalMobileSlide - 1)
        : modalActiveMedIdx;

      const item = modalScanItems[medIndexForText] || {
        med: "Paracetamol",
        strength: "500 mg",
        dosage: "500 mg",
        frequency: "3x a day",
        duration: "7 days",
        doctor: "Dr. Santos"
      };

      const fullLabel = `${item.med}${item.strength ? " " + item.strength : ""}`.trim();
      const isMobile = window.innerWidth <= 768;

      if (isMobile && modalMobileSlide === 0) {
        document.getElementById('modalMedicineName').textContent = modalScanItems.length > 1
          ? `Scanned Prescription (${modalScanItems.length} Meds)`
          : fullLabel;
      } else {
        document.getElementById('modalMedicineName').textContent = modalScanItems.length > 1
          ? `${fullLabel} (${medIndexForText + 1}/${modalScanItems.length})`
          : fullLabel;
      }

      document.getElementById('modalDateScanned').textContent = modalScanDate || "Nov 25, 2026";
      document.getElementById('modalDosage').textContent = item.dosage || item.strength || "500 mg";
      document.getElementById('modalFrequency').textContent = item.frequency || "3x a day";
      document.getElementById('modalDuration').textContent = item.duration || "7 days";
      document.getElementById('modalDoctor').textContent = item.doctor || "Dr. Santos";

      document.getElementById('modalRxImage').src = modalRxImgSrc || DEFAULT_RX_IMAGE;
      document.getElementById('modalImageHint').textContent = modalScanItems.length > 1
        ? `Prescription (${modalScanItems.length} medicines)`
        : "Scanned Prescription";

      // Desktop right-side slider (only if 2+ medicines)
      const desktopNav = document.getElementById('desktopSliderNav');
      const desktopDots = document.getElementById('desktopSliderDots');
      if (modalScanItems.length > 1) {
        desktopNav.style.display = 'flex';
        desktopDots.innerHTML = modalScanItems.map((_, i) => `
          <button type="button" class="rxm-slide-dot ${i === modalActiveMedIdx ? 'active' : ''}" onclick="selectModalMed(${i})" aria-label="Medicine ${i + 1}"></button>
        `).join("");
      } else {
        desktopNav.style.display = 'none';
      }

      // Mobile smooth horizontal track transform
      const track = document.getElementById('rxModalMobileTrack');
      if (track) {
        track.style.transform = `translateX(-${modalMobileSlide * 100}%)`;
      }

      const totalMobileSlides = 1 + modalScanItems.length;
      const mobileDotsEl = document.getElementById('mobileSlideDots');
      mobileDotsEl.innerHTML = Array.from({ length: totalMobileSlides }, (_, i) => `
        <button type="button" class="rxm-slide-dot ${i === modalMobileSlide ? 'active' : ''}" onclick="setMobileSlide(${i})" aria-label="Slide ${i + 1}"></button>
      `).join("");

      document.getElementById('mobileSlideLabel').textContent = modalMobileSlide === 0
        ? `1 / ${totalMobileSlides} • Prescription Image`
        : `${modalMobileSlide + 1} / ${totalMobileSlides} • ${fullLabel}`;
    }

    function selectModalMed(idx) {
      if (!modalScanItems.length) return;
      const detailsEl = document.getElementById('desktopDetailsList');
      if (detailsEl) {
        detailsEl.classList.add('is-switching');
        setTimeout(() => {
          modalActiveMedIdx = (idx + modalScanItems.length) % modalScanItems.length;
          renderModalMedState();
          detailsEl.classList.remove('is-switching');
        }, 120);
      } else {
        modalActiveMedIdx = (idx + modalScanItems.length) % modalScanItems.length;
        renderModalMedState();
      }
    }

    function stepModalMed(delta) {
      selectModalMed(modalActiveMedIdx + delta);
    }

    function setMobileSlide(slideIdx) {
      const totalMobileSlides = 1 + modalScanItems.length;
      modalMobileSlide = (slideIdx + totalMobileSlides) % totalMobileSlides;
      if (modalMobileSlide > 0) {
        modalActiveMedIdx = modalMobileSlide - 1;
      }
      renderModalMedState();
    }

    function stepMobileSlide(delta) {
      setMobileSlide(modalMobileSlide + delta);
    }

    function viewModalInMap() {
      const meds = modalScanItems.map(m => m.med).filter(Boolean);
      const primary = meds[modalActiveMedIdx] || meds[0] || "Paracetamol";
      const params = new URLSearchParams({ q: primary, from: "scan" });
      if (meds.length > 1) {
        params.set("meds", meds.join(","));
      }
      window.location.href = `02.3_MedicineMap.php?${params.toString()}`;
    }

    function openRxModal(scanOrName, dateScanned = '', imageSrc = '') {
      modalMobileSlide = 0; // Always start mobile carousel on Slide 1 (Prescription Image)
      modalActiveMedIdx = 0;

      if (scanOrName && typeof scanOrName === 'object') {
        modalScanDate = scanOrName.date || dateScanned || 'Nov 25, 2026';
        modalRxImgSrc = scanOrName.rxImage || imageSrc || DEFAULT_RX_IMAGE;
        if (Array.isArray(scanOrName.items) && scanOrName.items.length) {
          modalScanItems = scanOrName.items;
        } else {
          modalScanItems = [{
            med: scanOrName.med || 'Paracetamol',
            strength: scanOrName.strength || '500 mg',
            dosage: scanOrName.dosage || scanOrName.strength || '500 mg',
            frequency: scanOrName.frequency || '3x a day',
            duration: scanOrName.duration || '7 days',
            doctor: scanOrName.doctor || 'Dr. Santos'
          }];
        }
      } else {
        modalScanDate = dateScanned || 'Nov 25, 2026';
        modalRxImgSrc = imageSrc || DEFAULT_RX_IMAGE;
        modalScanItems = [{
          med: scanOrName || 'Paracetamol',
          strength: '',
          dosage: '500 mg',
          frequency: '3x a day',
          duration: '7 days',
          doctor: 'Dr. Santos'
        }];
      }

      buildMobileSlides();
      renderModalMedState();
      document.getElementById('rxModal').classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    // Touch swipe support for mobile modal carousel
    (function initModalSwipe() {
      const area = document.getElementById('rxModalSwipeArea');
      if (!area) return;
      let startX = 0;
      area.addEventListener('touchstart', e => {
        startX = e.changedTouches[0].clientX;
      }, { passive: true });
      area.addEventListener('touchend', e => {
        const dx = e.changedTouches[0].clientX - startX;
        if (Math.abs(dx) > 40 && window.innerWidth <= 768) {
          stepMobileSlide(dx < 0 ? 1 : -1);
        }
      }, { passive: true });
    })();

    function closeModal() {
      document.getElementById('rxModal').classList.remove('active');
      document.body.style.overflow = '';
    }

    function closeRxModal(event) {
      if (event.target === document.getElementById('rxModal')) closeModal();
    }
  </script>



  <!-- =============================================
       CHAT PANEL
       ============================================= -->
  <div class="chat-panel hidden" id="chatPanel">

    <div class="chat-header">
      <div class="bot-avatar">
        <span class="material-symbols-outlined" style="font-size:1.2rem; color:#fff">smart_toy</span>
      </div>
      <div class="chat-header-info">
        <h6>MediFind Assistant</h6>
        <span>● Online now</span>
      </div>
      <button class="chat-close-btn" onclick="toggleChat()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div class="chat-messages" id="chatMessages">
      <div class="msg bot">
        <div class="msg-avatar">
          <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
        </div>
        <div>
          <div class="bubble">Hi! I'm your MediFind assistant. Ask me anything about medicines, dosages, or nearby pharmacies 💊</div>
          <div class="msg-time">Just now</div>
        </div>
      </div>
    </div>

    <div class="chat-suggestions" id="chatSuggestions">
      <button class="suggest-chip" onclick="sendSuggestion('What is Paracetamol used for?')">Paracetamol</button>
      <button class="suggest-chip" onclick="sendSuggestion('Find nearby pharmacies')">Nearby pharmacies</button>
      <button class="suggest-chip" onclick="sendSuggestion('Amoxicillin dosage')">Amoxicillin</button>
    </div>

    <div class="chat-input-row">
      <input
        class="chat-input"
        id="chatInput"
        type="text"
        placeholder="Ask about a medicine..."
        onkeydown="if(event.key==='Enter') sendMessage()" />
      <button class="chat-send-btn" onclick="sendMessage()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.269 20.876L5.999 12zm0 0h7.5" />
        </svg>
      </button>
    </div>

  </div>

  <!-- =============================================
       FLOATING BUTTON
       ============================================= -->
  <div class="floating-btns">
    <button class="float-btn" id="floatBtn" onclick="toggleChat()" aria-label="Open chat assistant">
      <span class="material-symbols-outlined bot-icon" id="iconBot">smart_toy</span>
      <span class="material-symbols-outlined bot-icon" id="iconClose" style="display:none">close</span>
    </button>
  </div>

  <!-- =============================================
       CHAT SCRIPT
       ============================================= -->
  <script>
    let isOpen = false;
    const panel = document.getElementById('chatPanel');
    const chatMsgs = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const iconBot = document.getElementById('iconBot');
    const iconClose = document.getElementById('iconClose');

    // ── Sample bot replies (replace with real API call) ──────────────────
    const botReplies = [
      "Paracetamol is commonly used to relieve fever and mild to moderate pain such as headache, body aches, or toothache.",
      "For adults, Paracetamol 500mg is typically taken every 4–6 hours. Don't exceed 4g (8 tablets) in 24 hours.",
      "It is generally gentle on the stomach and can be taken with or without food.",
      "If you miss a dose and take it only when needed, just skip the missed one. If on a schedule, take it as soon as you remember.",
      "I can help you find which nearby pharmacy has this medicine in stock. Want me to search for you?"
    ];
    let replyIdx = 0;

    // ── Toggle open / close ──────────────────────────────────────────────
    function toggleChat() {
      isOpen = !isOpen;
      panel.classList.toggle('hidden', !isOpen);
      panel.classList.toggle('visible', isOpen);
      iconBot.style.display = isOpen ? 'none' : '';
      iconClose.style.display = isOpen ? '' : 'none';
      if (isOpen) setTimeout(() => chatInput.focus(), 300);
    }

    // ── Helpers ───────────────────────────────────────────────────────────
    function getTime() {
      return new Date().toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
      });
    }

    function scrollBottom() {
      chatMsgs.scrollTop = chatMsgs.scrollHeight;
    }

    function addMessage(text, type) {
      const div = document.createElement('div');
      div.className = 'msg ' + type;

      if (type === 'bot') {
        div.innerHTML = `
    <div class="msg-avatar">
      <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
    </div>
    <div>
      <div class="bubble">${text}</div>
      <div class="msg-time">${getTime()}</div>
    </div>`;
      } else {
        div.innerHTML = `
          <div>
            <div class="bubble">${text}</div>
            <div class="msg-time" style="text-align:right">${getTime()}</div>
          </div>`;
      }

      chatMsgs.appendChild(div);
      scrollBottom();
    }

    function showTyping() {
      const t = document.createElement('div');
      t.className = 'msg bot';
      t.id = 'typingIndicator';
      t.innerHTML = `
  <div class="msg-avatar">
    <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
  </div>
  <div class="typing-indicator">
    <div class="typing-dot"></div>
    <div class="typing-dot"></div>
    <div class="typing-dot"></div>
  </div>`;
      chatMsgs.appendChild(t);
      scrollBottom();
    }

    function removeTyping() {
      const t = document.getElementById('typingIndicator');
      if (t) t.remove();
    }

    // ── Send message ──────────────────────────────────────────────────────
    function sendMessage() {
      const text = chatInput.value.trim();
      if (!text) return;
      addMessage(text, 'user');
      chatInput.value = '';

      // Hide suggestions after first user message
      document.getElementById('chatSuggestions').style.display = 'none';

      showTyping();
      setTimeout(() => {
        removeTyping();
        // 🔁 Replace the line below with your actual API call
        addMessage(botReplies[replyIdx % botReplies.length], 'bot');
        replyIdx++;
      }, 1100);
    }

    function sendSuggestion(text) {
      addMessage(text, 'user');
      document.getElementById('chatSuggestions').style.display = 'none';
      showTyping();
      setTimeout(() => {
        removeTyping();
        addMessage(botReplies[replyIdx % botReplies.length], 'bot');
        replyIdx++;
      }, 1100);
    }
  </script>

  <!-- ══ PRESCRIPTION CAMERA SCANNER MODAL (matches inventory scanner design) ══ -->
  <style>
    #rxCameraModal {
      z-index: 1350;
    }
    #rxCameraModal .modal-dialog {
      max-width: 520px;
      margin: 0.75rem auto;
      padding: 0 12px;
    }
    #rxCameraModal .scanner-modal-header {
      padding: 13px 16px;
    }
    #rxCameraModal .modal-title {
      font-size: 0.96rem;
      font-weight: 600;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    #rxScannerViewport {
      width: 100%;
      max-width: 460px;
      height: 360px !important;
      max-height: 48dvh;
      min-height: 280px;
      margin: 0 auto 14px;
      position: relative;
      overflow: hidden;
      border-radius: 14px;
    }
    @media (max-width: 767.98px) {
      #rxCameraModal .modal-dialog {
        margin: 12px auto;
        min-height: calc(100dvh - 108px);
      }
      #rxScannerViewport {
        height: 46dvh !important;
        min-height: 310px;
        max-height: 440px;
      }
    }
    .rx-shutter-flash {
      position: absolute;
      inset: 0;
      background: #ffffff;
      opacity: 0;
      pointer-events: none;
      z-index: 8;
      transition: opacity 0.14s ease;
    }
    .rx-shutter-flash.is-flashing {
      opacity: 0.9;
    }
    .rx-capture-done-badge {
      position: absolute;
      inset: 0;
      z-index: 9;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 6px;
      background: rgba(15, 23, 42, 0.45);
      color: #ffffff;
      font-weight: 700;
      font-size: 0.95rem;
      opacity: 0;
      transform: scale(0.94);
      pointer-events: none;
      transition: opacity 0.24s ease, transform 0.24s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .rx-capture-done-badge.is-shown {
      opacity: 1;
      transform: scale(1);
    }
    .rx-capture-done-icon {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background: #1d9e75;
      color: #ffffff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 6px 20px rgba(29, 158, 117, 0.45);
    }
    .rx-modal-actions {
      display: flex;
      flex-direction: column;
      align-items: stretch;
      gap: 10px;
      max-width: 340px;
      margin: 0 auto;
    }
    .rx-btn-use-orange {
      background: #ff5b27 !important;
      border: none !important;
      color: #ffffff !important;
      transition: background 0.18s ease, transform 0.14s ease;
    }
    .rx-btn-use-orange:hover {
      background: #e64d1c !important;
      transform: translateY(-1px);
    }
  </style>
  <div class="modal fade" id="rxCameraModal" tabindex="-1" aria-labelledby="rxCameraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content scanner-modal-content">
        <div class="modal-header scanner-modal-header">
          <h5 class="modal-title d-flex align-items-center gap-2 text-white" id="rxCameraModalLabel">
            <span class="material-symbols-outlined">qr_code_scanner</span>
            Scan Prescription with Camera
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-3 p-sm-4 text-center">
          <div class="scanner-viewport-wrap" id="rxScannerViewport">
            <video id="rxCameraVideo" autoplay playsinline muted style="width:100%;height:100%;object-fit:cover;"></video>
            <img id="rxCapturePreview" alt="Captured Prescription Preview" style="display:none;width:100%;height:100%;object-fit:contain;background:#0f172a;" />
            <div class="scanner-guide-box" id="rxGuideBox" style="width: 82%; height: 74%;">
              <div class="scanner-laser"></div>
            </div>
            <div class="rx-shutter-flash" id="rxShutterFlash"></div>
            <div class="rx-capture-done-badge" id="rxCaptureDoneBadge">
              <span class="rx-capture-done-icon">
                <span class="material-symbols-outlined" style="font-size:1.75rem;">check</span>
              </span>
              <span>Scan Captured!</span>
            </div>
            <button type="button" id="rxSwitchCamBtn" title="Switch Camera" aria-label="Switch Camera" style="position:absolute;top:12px;right:12px;z-index:5;width:38px;height:38px;border-radius:50%;border:none;background:rgba(15,23,42,0.7);color:#fff;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;">
              <span class="material-symbols-outlined" style="font-size:1.2rem;">flip_camera_android</span>
            </button>
            <div id="rxCameraFallback" style="display:none;position:absolute;inset:0;background:#111827;color:#e2e8f0;flex-direction:column;align-items:center;justify-content:center;padding:20px;gap:10px;z-index:6;">
              <span class="material-symbols-outlined" style="font-size:2.4rem;color:#94a3b8;">videocam_off</span>
              <div id="rxCameraErrorMsg" style="font-size:0.88rem;max-width:340px;">Camera access is unavailable or was denied.</div>
              <button type="button" class="btn btn-sm rounded-pill px-3" style="background:#1d9e75;color:#fff;font-weight:600;" onclick="bootstrap.Modal.getInstance(document.getElementById('rxCameraModal')).hide(); document.getElementById('rxFileInput').click();">
                Upload PNG / JPEG Instead
              </button>
            </div>
          </div>

          <p class="text-muted small mb-3" id="rxCameraHint">
            Align your prescription inside the green frame and click <strong>Capture Prescription</strong>.
          </p>

          <!-- Live capture actions (Capture on top, Cancel at bottom) -->
          <div class="rx-modal-actions" id="rxLiveActions">
            <button type="button" class="btn rounded-pill py-2 px-4 text-white fw-semibold d-inline-flex align-items-center justify-content-center gap-2" id="rxCaptureBtn" style="background:#1d9e75;border:none;">
              <span class="material-symbols-outlined" style="font-size:1.1rem;">photo_camera</span>
              Capture Prescription
            </button>
            <button type="button" class="btn btn-outline-secondary rounded-pill py-2 px-4" data-bs-dismiss="modal">Cancel</button>
          </div>

          <!-- Preview / Retake actions (Use Photo & Scan in orange on top, Retake at bottom) -->
          <div class="rx-modal-actions d-none" id="rxPreviewActions">
            <button type="button" class="btn rounded-pill py-2 px-4 fw-semibold d-inline-flex align-items-center justify-content-center gap-2 rx-btn-use-orange" id="rxUsePhotoBtn">
              <span class="material-symbols-outlined" style="font-size:1.1rem;">check_circle</span>
              Use Photo &amp; Scan
            </button>
            <button type="button" class="btn btn-outline-secondary rounded-pill py-2 px-4 d-inline-flex align-items-center justify-content-center gap-1" id="rxRetakeBtn">
              <span class="material-symbols-outlined" style="font-size:1.05rem;">refresh</span>
              Retake
            </button>
          </div>
          <canvas id="rxCaptureCanvas" hidden></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- ══ OCR PROCESSING OVERLAY ══ -->
  <div id="rxProcessingOverlay" style="display:none;position:fixed;inset:0;z-index:2100;background:rgba(15,23,42,0.55);align-items:center;justify-content:center;padding:16px;">
    <div style="background:#fff;border-radius:18px;max-width:380px;width:100%;padding:26px 24px;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,0.2);">
      <div class="spinner-border text-success mb-3" role="status" style="width:2.4rem;height:2.4rem;color:#1d9e75 !important;"></div>
      <h5 style="font-weight:700;color:#1a2e27;margin-bottom:6px;">Processing Prescription</h5>
      <p id="rxProcessingStep" style="font-size:0.86rem;color:#5c756c;margin-bottom:14px;">Reading prescription text...</p>
      <div style="font-size:0.76rem;color:#0f6e56;background:#eef8f4;border-radius:10px;padding:8px 12px;">
        RA 10173: Personal details are stripped and the image is deleted immediately after OCR.
      </div>
    </div>
  </div>

  <script>
    function proceedWithRxDataUrl(dataUrl) {
      const overlay = document.getElementById("rxProcessingOverlay");
      const stepEl = document.getElementById("rxProcessingStep");
      overlay.style.display = "flex";
      stepEl.textContent = "1/3 · Reading prescription text...";
      setTimeout(() => {
        stepEl.textContent = "2/3 · Stripping personal details (RA 10173)...";
      }, 350);
      setTimeout(() => {
        stepEl.textContent = "3/3 · Matching medicines & preparing review...";
        sessionStorage.setItem("uploadedRx", dataUrl);
      }, 700);
      setTimeout(() => {
        window.location.href = "../04_User/02.2_ScannedRx.php";
      }, 1050);
    }

    function handleRxFile(file) {
      if (!file) return;
      if (!['image/png', 'image/jpeg'].includes(file.type)) {
        alert('Please upload a PNG or JPEG image.');
        return;
      }
      if (file.size > 5 * 1024 * 1024) {
        alert('File size must be 5 MB or less.');
        return;
      }
      const reader = new FileReader();
      reader.onload = (e) => proceedWithRxDataUrl(e.target.result);
      reader.readAsDataURL(file);
    }

    document.getElementById("rxUploadBtn").addEventListener("click", () => {
      document.getElementById("rxFileInput").click();
    });
    document.getElementById("rxFileInput").addEventListener("change", function () {
      handleRxFile(this.files[0]);
    });
    document.getElementById("rxCameraInput").addEventListener("change", function () {
      handleRxFile(this.files[0]);
    });

    // Camera Scanner Modal Logic (with Switch Camera + Preview & Retake)
    let rxStream = null;
    let rxFacingMode = "environment";
    let capturedDataUrl = null;

    const rxCameraModalEl = document.getElementById("rxCameraModal");
    const rxVideo = document.getElementById("rxCameraVideo");
    const rxPreview = document.getElementById("rxCapturePreview");
    const rxGuideBox = document.getElementById("rxGuideBox");
    const rxSwitchCamBtn = document.getElementById("rxSwitchCamBtn");
    const rxFallback = document.getElementById("rxCameraFallback");
    const rxErrorMsg = document.getElementById("rxCameraErrorMsg");
    const rxCameraHint = document.getElementById("rxCameraHint");
    const rxLiveActions = document.getElementById("rxLiveActions");
    const rxPreviewActions = document.getElementById("rxPreviewActions");
    const rxCaptureBtn = document.getElementById("rxCaptureBtn");
    const rxRetakeBtn = document.getElementById("rxRetakeBtn");
    const rxUsePhotoBtn = document.getElementById("rxUsePhotoBtn");

    function showLiveMode() {
      capturedDataUrl = null;
      rxPreview.style.display = "none";
      rxVideo.style.display = "block";
      rxGuideBox.style.display = "block";
      rxSwitchCamBtn.style.display = "inline-flex";
      rxLiveActions.classList.remove("d-none");
      rxLiveActions.classList.add("d-flex");
      rxPreviewActions.classList.add("d-none");
      rxPreviewActions.classList.remove("d-flex");
      rxCameraHint.innerHTML = 'Align your prescription inside the green frame and click <strong>Capture Prescription</strong>.';
    }

    function showPreviewMode(dataUrl) {
      capturedDataUrl = dataUrl;
      rxPreview.src = dataUrl;
      rxPreview.style.display = "block";
      rxVideo.style.display = "none";
      rxGuideBox.style.display = "none";
      rxSwitchCamBtn.style.display = "none";
      rxLiveActions.classList.add("d-none");
      rxLiveActions.classList.remove("d-flex");
      rxPreviewActions.classList.remove("d-none");
      rxPreviewActions.classList.add("d-flex");
      rxCameraHint.innerHTML = 'Check that the medicine names are clear, then click <strong>Use Photo &amp; Scan</strong> or <strong>Retake</strong>.';
    }

    function stopRxCamera() {
      if (rxStream) {
        rxStream.getTracks().forEach(t => t.stop());
        rxStream = null;
      }
      rxVideo.srcObject = null;
    }

    async function startRxCamera() {
      showLiveMode();
      rxFallback.style.display = "none";
      rxCaptureBtn.disabled = false;
      if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        rxErrorMsg.textContent = "Your browser does not support camera access.";
        rxFallback.style.display = "flex";
        rxCaptureBtn.disabled = true;
        return;
      }
      try {
        rxStream = await navigator.mediaDevices.getUserMedia({
          video: { facingMode: rxFacingMode },
          audio: false
        });
        rxVideo.srcObject = rxStream;
      } catch (err) {
        rxErrorMsg.textContent = "Unable to access camera. Please allow camera permission or upload an image.";
        rxFallback.style.display = "flex";
        rxCaptureBtn.disabled = true;
      }
    }

    function openRxCameraModal() {
      if (rxCameraModalEl && rxCameraModalEl.parentElement !== document.body) {
        document.body.appendChild(rxCameraModalEl);
      }
      const modal = new bootstrap.Modal(rxCameraModalEl);
      modal.show();
    }

    document.getElementById("rxCameraBtn").addEventListener("click", openRxCameraModal);

    const manualSearchForm = document.getElementById("rxManualSearchForm");
    const manualSearchInput = document.getElementById("rxManualSearchInput");
    if (manualSearchForm && manualSearchInput) {
      manualSearchForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const q = manualSearchInput.value.trim();
        window.location.href = q ? `02.3_MedicineMap.php?q=${encodeURIComponent(q)}` : "02.3_MedicineMap.php";
      });
    }

    if (new URLSearchParams(window.location.search).get("rescan") === "1") {
      setTimeout(openRxCameraModal, 120);
    }

    rxSwitchCamBtn.addEventListener("click", () => {
      rxFacingMode = rxFacingMode === "environment" ? "user" : "environment";
      stopRxCamera();
      startRxCamera();
    });

    rxCameraModalEl.addEventListener("shown.bs.modal", startRxCamera);
    rxCameraModalEl.addEventListener("hidden.bs.modal", stopRxCamera);

    rxCaptureBtn.addEventListener("click", () => {
      if (!rxStream || !rxVideo.videoWidth) return;
      const canvas = document.getElementById("rxCaptureCanvas");
      canvas.width = rxVideo.videoWidth;
      canvas.height = rxVideo.videoHeight;
      canvas.getContext("2d").drawImage(rxVideo, 0, 0, canvas.width, canvas.height);
      const dataUrl = canvas.toDataURL("image/jpeg", 0.92);

      const flashEl = document.getElementById("rxShutterFlash");
      const badgeEl = document.getElementById("rxCaptureDoneBadge");
      stopRxCamera();
      showPreviewMode(dataUrl);

      if (flashEl) {
        flashEl.classList.add("is-flashing");
        setTimeout(() => flashEl.classList.remove("is-flashing"), 150);
      }
      if (badgeEl) {
        badgeEl.classList.add("is-shown");
        setTimeout(() => badgeEl.classList.remove("is-shown"), 650);
      }
    });

    rxRetakeBtn.addEventListener("click", startRxCamera);
    rxUsePhotoBtn.addEventListener("click", () => {
      if (!capturedDataUrl) return;
      const modalInst = bootstrap.Modal.getInstance(rxCameraModalEl);
      if (modalInst) modalInst.hide();
      proceedWithRxDataUrl(capturedDataUrl);
    });

    const dropzone = document.getElementById("rxDropzone");
    dropzone.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropzone.classList.add("dragover");
    });
    dropzone.addEventListener("dragleave", () => dropzone.classList.remove("dragover"));
    dropzone.addEventListener("drop", (e) => {
      e.preventDefault();
      dropzone.classList.remove("dragover");
      if (e.dataTransfer.files && e.dataTransfer.files[0]) {
        handleRxFile(e.dataTransfer.files[0]);
      }
    });

    // Render persistent text-only Recent Scans from localStorage
    (function initRecentScans() {
      const listEl = document.getElementById("recentScansList");
      if (!listEl) return;

      const multiMedDemo = {
        id: "rx-multi-demo",
        med: "Amoxicillin, Paracetamol +1",
        strength: "3 Meds",
        date: "Nov 25, 2026",
        doctor: "Dr. Santos",
        rxImage: "../07_Assets/images/sample-rx/1.jpeg",
        items: [
          {
            med: "Amoxicillin",
            strength: "500 mg",
            dosage: "500 mg",
            frequency: "3x a day",
            duration: "7 days",
            doctor: "Dr. Santos"
          },
          {
            med: "Paracetamol",
            strength: "500 mg",
            dosage: "500 mg",
            frequency: "Every 4-6 hrs PRN",
            duration: "5 days",
            doctor: "Dr. Santos"
          },
          {
            med: "Carbocisteine",
            strength: "500 mg",
            dosage: "500 mg",
            frequency: "3x a day",
            duration: "5 days",
            doctor: "Dr. Santos"
          }
        ]
      };

      const defaultScans = [
        multiMedDemo,
        { med: "Paracetamol", strength: "500 mg", date: "Nov 25, 2026", dosage: "500 mg", frequency: "Every 4-6 hrs", duration: "5 days", doctor: "Dr. Santos", rxImage: "../07_Assets/images/sample-rx/2.png" },
        { med: "Metformin", strength: "500 mg", date: "Nov 15, 2026", dosage: "500 mg", frequency: "2x a day", duration: "30 days", doctor: "Dr. Reyes", rxImage: "../07_Assets/images/sample-rx/1.jpeg" },
        { med: "Losartan", strength: "50 mg", date: "Oct 08, 2026", dosage: "50 mg", frequency: "1x a day", duration: "30 days", doctor: "Dr. Cruz", rxImage: "../07_Assets/images/sample-rx/2.png" }
      ];

      let scans;
      try {
        const stored = localStorage.getItem("mf_recent_scans");
        scans = stored ? JSON.parse(stored) : defaultScans;
        // Ensure multi-medicine demo prescription is present for presentation
        if (Array.isArray(scans) && !scans.some(s => Array.isArray(s.items) && s.items.length > 1)) {
          scans.unshift(multiMedDemo);
          localStorage.setItem("mf_recent_scans", JSON.stringify(scans));
        } else if (!stored) {
          localStorage.setItem("mf_recent_scans", JSON.stringify(defaultScans));
        }
      } catch (e) {
        scans = defaultScans;
      }

      window.deleteRecentScanByIdx = function (idx) {
        if (idx < 0 || idx >= scans.length) return;
        scans.splice(idx, 1);
        localStorage.setItem("mf_recent_scans", JSON.stringify(scans));
        renderRecentScans();
      };

      function renderRecentScans() {
        if (!scans.length) {
          listEl.innerHTML = `<div class="text-muted small py-3 text-center">No recent scans yet. Scan or upload a prescription to get started.</div>`;
          return;
        }

        listEl.innerHTML = scans.map((s, idx) => {
          const isMulti = Array.isArray(s.items) && s.items.length > 1;
          const badge = isMulti
            ? `<span class="badge rounded-pill ms-1" style="background:#e8f7f2;color:#0f6e56;font-size:0.7rem;font-weight:600;">${s.items.length} Meds</span>`
            : "";
          const label = isMulti
            ? `${s.med}${badge}`
            : `${s.med}${s.strength ? " &bull; " + s.strength : ""}`;
          return `
            <div class="recent-scan-row">
              <span class="recent-scan-med">${label}</span>
              <div class="d-flex align-items-center gap-2">
                <span class="recent-scan-date">${s.date}</span>
                <button type="button" class="recent-view-btn" data-view-idx="${idx}">View</button>
                <button type="button" class="recent-del-btn" data-idx="${idx}" title="Delete">
                  <span class="material-symbols-outlined" style="font-size:1rem;">delete</span>
                </button>
              </div>
            </div>
          `;
        }).join("");

        listEl.querySelectorAll("[data-view-idx]").forEach(btn => {
          btn.addEventListener("click", () => {
            const i = parseInt(btn.dataset.viewIdx, 10);
            const scan = scans[i];
            if (scan) openRxModal(scan, scan.date, '', i);
          });
        });

        listEl.querySelectorAll(".recent-del-btn").forEach(btn => {
          btn.addEventListener("click", () => {
            const i = parseInt(btn.dataset.idx, 10);
            window.deleteRecentScanByIdx(i);
          });
        });
      }

      renderRecentScans();
    })();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
</body>

</html>