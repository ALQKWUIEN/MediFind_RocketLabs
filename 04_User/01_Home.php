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

$medicinesJsonPath = __DIR__ . '/../database/medicines_db.json';
$medicinesData = file_exists($medicinesJsonPath) ? json_decode(file_get_contents($medicinesJsonPath), true) : ['medicines' => []];
$medicinesList = $medicinesData['medicines'] ?? [];
?>

<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MediFind: Home</title>

  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    @keyframes mfPinBounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }

    .mf-pin-bounce {
      animation: mfPinBounce 1.35s ease-in-out infinite;
      will-change: transform;
    }

    .mf-mobile-avatar,
    .mf-mobile-notif-btn {
      display: none;
    }

    .search-wrapper {
      position: relative;
      flex: 1;
      padding: 6px 8px 6px 14px !important;
    }

    .mf-inline-search-btn {
      border: none;
      background: #1d9e75;
      color: #ffffff;
      border-radius: 10px;
      padding: 6px 14px;
      font-size: 0.82rem;
      font-weight: 600;
      white-space: nowrap;
      cursor: pointer;
      flex-shrink: 0;
      transition: background 0.16s ease;
    }

    .mf-inline-search-btn:hover {
      background: #178764;
    }

    .mf-home-search-suggest {
      position: absolute;
      top: calc(100% + 6px);
      left: 0;
      right: 0;
      background: #ffffff;
      border-radius: 16px;
      border: 1px solid #e5ece9;
      box-shadow: 0 12px 28px rgba(15, 23, 42, 0.14);
      max-height: 0;
      opacity: 0;
      overflow: hidden;
      pointer-events: none;
      transform: translateY(-6px);
      transition: max-height 0.26s cubic-bezier(0.22, 1, 0.36, 1),
                  opacity 0.2s ease,
                  transform 0.22s cubic-bezier(0.22, 1, 0.36, 1);
      z-index: 600;
    }

    .mf-home-search-suggest.is-open {
      max-height: 260px;
      opacity: 1;
      overflow-y: auto;
      pointer-events: auto;
      transform: translateY(0);
    }

    .mf-home-suggest-item {
      width: 100%;
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 14px;
      background: #ffffff;
      border: none;
      border-bottom: 1px solid #f1f5f9;
      text-align: left;
      cursor: pointer;
      transition: background 0.15s ease;
    }

    .mf-home-suggest-item:last-child {
      border-bottom: none;
    }

    .mf-home-suggest-item:hover {
      background: #f0fdf9;
    }

    .mf-home-suggest-item img {
      width: 34px;
      height: 34px;
      object-fit: contain;
      border-radius: 8px;
      background: #f8fafc;
      padding: 2px;
      flex-shrink: 0;
    }

    @media (max-width: 767.98px) {
      #home-view-list {
        padding: 0 !important;
        background: #ffffff !important;
      }

      .mf-home-hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #178a65 0%, #1d9e75 100%);
        padding: 18px 16px 34px;
      }

      .mf-home-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: url("../07_Assets/images/backgrounds/malaybalay-hero-bg.jpg") center / cover no-repeat;
        opacity: 0.22;
        mix-blend-mode: luminosity;
        pointer-events: none;
        z-index: 0;
      }

      .mf-home-hero > * {
        position: relative;
        z-index: 1;
      }

      .mf-mobile-hero-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
      }

      .mf-mobile-user-left {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
      }

      .mf-mobile-avatar {
        display: block;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(255, 255, 255, 0.5);
        flex-shrink: 0;
      }

      .mf-mobile-notif-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.22);
        color: #ffffff;
        border: none;
        position: relative;
        flex-shrink: 0;
        text-decoration: none;
        transition: background 0.18s ease, transform 0.14s ease;
      }

      .mf-mobile-notif-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.03);
      }

      .mf-mobile-notif-dot {
        position: absolute;
        top: 9px;
        right: 10px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ff3b30;
        border: 1.5px solid #1d9e75;
      }

      .mf-home-hero .welcome-location-block {
        margin-bottom: 0 !important;
      }

      .mf-home-hero .welcome-user {
        color: rgba(255, 255, 255, 0.92) !important;
        font-size: 0.85rem !important;
        font-weight: 500 !important;
      }

      .mf-home-hero .user-location {
        color: #ffffff !important;
        font-size: 1rem !important;
        font-weight: 700 !important;
        margin-top: 2px !important;
      }

      .mf-home-hero .user-location .material-symbols-outlined {
        color: #ffffff !important;
        font-size: 1.15rem !important;
      }

      .mf-home-hero .search-row {
        margin-top: 16px !important;
      }

      .mf-home-hero .search-wrapper {
        background: #ffffff !important;
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
      }

      .mf-home-hero .search-filter-btn {
        background: #ffffff !important;
        color: #1d9e75 !important;
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
      }

      .mf-home-sheet {
        background: #ffffff;
        border-top-left-radius: 26px;
        border-top-right-radius: 26px;
        margin-top: -18px;
        padding: 22px 16px 90px;
        position: relative;
        z-index: 2;
      }

      .mf-home-sheet .nearby-section {
        margin-top: 0 !important;
        padding-top: 0 !important;
      }

      .category-nav {
        display: none !important;
      }

      .quick-categories {
        display: flex !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        gap: 14px !important;
        scrollbar-width: none;
      }

      .quick-categories::-webkit-scrollbar {
        display: none;
      }

      .pharmacy-list {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        gap: 12px !important;
        padding-bottom: 4px;
        scrollbar-width: none;
      }

      .pharmacy-list::-webkit-scrollbar {
        display: none;
      }

      .pharmacy-card {
        min-width: 215px !important;
        max-width: 225px !important;
        flex: 0 0 auto !important;
        padding: 10px !important;
        border-radius: 14px !important;
        border: 1px solid #e5ece9 !important;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03) !important;
      }

      .pharmacy-card-img {
        width: 100% !important;
        height: 98px !important;
        object-fit: cover !important;
        border-radius: 10px !important;
        margin-bottom: 8px !important;
      }

      .pharmacy-card-body {
        padding: 0 2px 2px !important;
        text-align: left !important;
      }

      .pharmacy-card-body h6 {
        font-size: 0.9rem !important;
        font-weight: 700 !important;
        margin: 0 0 3px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      .pharmacy-card .pharmacy-distance,
      .pharmacy-card .pharmacy-status {
        font-size: 0.76rem !important;
        margin-bottom: 3px !important;
        line-height: 1.3 !important;
      }

      .pharmacy-card .direction-btn {
        background: #ff5b27 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 999px !important;
        font-weight: 600 !important;
        font-size: 0.76rem !important;
        padding: 6px 14px !important;
        margin-top: 6px !important;
        width: auto !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
      }

      .pharmacy-card .direction-btn .material-symbols-outlined {
        font-size: 0.9rem !important;
      }
    }
  </style>
</head>

<body data-active="01">
  <div class="wrapper d-flex align-items-stretch">
    <div id="sidebar-container"><?php include '../01_Includes/users-sidebar-fixed.php'; ?></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content">
        <div class="content-body">
          <div class="home-layout">

            <!-- DEFAULT VIEW: full list -->
            <div id="home-view-list">
              <div class="search-section">
                <div class="mf-home-hero">
                  <div class="mf-mobile-hero-row">
                    <div class="mf-mobile-user-left">
                      <img src="../07_Assets/images/person.jpg" alt="Profile" class="mf-mobile-avatar" />
                      <div class="welcome-location-block">
                        <h2 class="welcome-user">Hello, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h2>
                        <div class="user-location">
                          <span class="material-symbols-outlined">location_on</span>
                          <span>Malaybalay, Bukidnon</span>
                        </div>
                      </div>
                    </div>
                    <a href="05_Profile.php" class="mf-mobile-notif-btn" aria-label="Notifications">
                      <span class="material-symbols-outlined">notifications</span>
                      <span class="mf-mobile-notif-dot"></span>
                    </a>
                  </div>

                  <div class="search-row">
                    <div class="search-wrapper" id="homeSearchWrapper">
                      <span class="material-symbols-outlined" style="color: #87a199; font-size: 1.2rem">search</span>
                      <input type="text" id="homeMedicineSearch" placeholder="Search medicine by name..." autocomplete="off" />
                      <button type="button" class="mf-inline-search-btn" id="homeSearchBtn">Search</button>
                      <div class="mf-home-search-suggest" id="homeSearchSuggest"></div>
                    </div>
                  </div>
                </div>

                <div class="mf-home-sheet">
                <!-- Nearby Pharmacies -->
                <div class="nearby-section">
                  <div class="section-header">
                    <h5>Nearby Pharmacies</h5>
                    <a href="#">See all</a>
                  </div>
                  <div class="pharmacy-list">

                    <div class="pharmacy-card"
                      data-name="Rodel Pharmacy"
                      data-address="Market Site, Barangay 9, Malaybalay City, 8700 Bukidnon (44XM+34M)"
                      data-distance="1.2 km away"
                      data-status="Open · Closes 8PM"
                      data-lat="8.147688" data-lng="125.132813"
                      data-img="../07_Assets/images/pharmacies/RodelPharmacy.jpg"
                      data-medicines='[
                        {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 14 packs","price":"₱2.50","per":"tablet","status":"in"},
                        {"name":"Amoxicillin","dosage":"500mg · Capsule","stock":"In Stock · 7 packs","price":"₱7.50","per":"capsule","status":"in"},
                        {"name":"Mefenamic Acid","dosage":"500mg · Capsule","stock":"In Stock · 10 packs","price":"₱3.75","per":"capsule","status":"in"}
                      ]'>
                      <img src="../07_Assets/images/pharmacies/RodelPharmacy.jpg" alt="Rodel Pharmacy" class="pharmacy-card-img" />
                      <div class="pharmacy-card-body">
                        <h6>Rodel Pharmacy</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined">location_on</span>
                          <span>1.2 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span><span class="dot">·</span><span>Closes 8PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined">north_east</span>
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div class="pharmacy-card"
                      data-name="Rodel Pharmacy (Sumpong Branch)"
                      data-address="546C+HFG, Sta. Cruz St, Sumpong, Malaybalay City, Bukidnon"
                      data-distance="0.9 km away"
                      data-status="Open · Closes 8PM"
                      data-lat="8.161438" data-lng="125.121188"
                      data-img="../07_Assets/images/pharmacies/RodelPharmacySumpong.jpg"
                      data-medicines='[
                        {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 11 packs","price":"₱2.50","per":"tablet","status":"in"},
                        {"name":"Ibuprofen","dosage":"200mg · Tablet","stock":"In Stock · 8 packs","price":"₱3.25","per":"tablet","status":"in"},
                        {"name":"Amoxicillin","dosage":"500mg · Capsule","stock":"Low Stock · 3 packs","price":"₱8.00","per":"capsule","status":"low"}
                      ]'>
                      <img src="../07_Assets/images/pharmacies/RodelPharmacySumpong.jpg" alt="Rodel Pharmacy Sumpong Branch" class="pharmacy-card-img" />
                      <div class="pharmacy-card-body">
                        <h6>Rodel Pharmacy (Sumpong)</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined">location_on</span>
                          <span>0.9 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span><span class="dot">·</span><span>Closes 8PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined">north_east</span>
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div class="pharmacy-card"
                      data-name="A&L Pharma"
                      data-address="543H+WHF, San Isidro St, Malaybalay City, Bukidnon · 0915 496 9350"
                      data-distance="0.4 km away"
                      data-status="Open · Closes 8PM"
                      data-lat="8.154813" data-lng="125.128938"
                      data-img="../07_Assets/images/pharmacies/ALPharma.jpg"
                      data-medicines='[
                        {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 12 packs","price":"₱2.50","per":"tablet","status":"in"},
                        {"name":"Cetirizine","dosage":"10mg · Tablet","stock":"In Stock · 9 packs","price":"₱4.50","per":"tablet","status":"in"},
                        {"name":"Losartan","dosage":"50mg · Tablet","stock":"In Stock · 6 packs","price":"₱6.00","per":"tablet","status":"in"}
                      ]'>
                      <img src="../07_Assets/images/pharmacies/ALPharma.jpg" alt="A&L Pharma" class="pharmacy-card-img" />
                      <div class="pharmacy-card-body">
                        <h6>A&amp;L Pharma</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined">location_on</span>
                          <span>0.4 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span><span class="dot">·</span><span>Closes 8PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined">north_east</span>
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div class="pharmacy-card"
                      data-name="Rose Pharmacy"
                      data-address="Sayre Highway, Poblacion, Malaybalay City, Bukidnon"
                      data-distance="0.5 km away"
                      data-status="Open · Closes 10PM"
                      data-lat="8.1548" data-lng="125.1281"
                      data-img="../07_Assets/images/pharmacies/RosePharmacy.png"
                      data-medicines='[
                        {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 8 packs","price":"₱2.75","per":"tablet","status":"in"},
                        {"name":"Amoxicillin","dosage":"500mg · Capsule","stock":"In Stock · 5 packs","price":"₱8.50","per":"capsule","status":"in"},
                        {"name":"Ibuprofen","dosage":"200mg · Tablet","stock":"Low Stock · 2 packs","price":"₱3.00","per":"tablet","status":"low"}
                      ]'>
                      <img src="../07_Assets/images/pharmacies/RosePharmacy.png" alt="Rose Pharmacy" class="pharmacy-card-img" />
                      <div class="pharmacy-card-body">
                        <h6>Rose Pharmacy</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined">location_on</span>
                          <span>0.5 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span><span class="dot">·</span><span>Closes 10PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined">north_east</span>
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div class="pharmacy-card"
                      data-name="Generika Drugstore"
                      data-address="Fortich St., Poblacion, Malaybalay City, Bukidnon"
                      data-distance="0.8 km away"
                      data-status="Open · Closes 9PM"
                      data-lat="8.1563" data-lng="125.1285"
                      data-img="../07_Assets/images/pharmacies/Generika.png"
                      data-medicines='[
                        {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 8 packs","price":"₱2.75","per":"tablet","status":"in"},
                        {"name":"Losartan","dosage":"50mg · Tablet","stock":"In Stock · 10 packs","price":"₱6.00","per":"tablet","status":"in"},
                        {"name":"Metformin","dosage":"500mg · Tablet","stock":"In Stock · 6 packs","price":"₱4.50","per":"tablet","status":"in"}
                      ]'>
                      <img src="../07_Assets/images/pharmacies/Generika.png" alt="Generika Drugstore" class="pharmacy-card-img" />
                      <div class="pharmacy-card-body">
                        <h6>Generika Drugstore</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined">location_on</span>
                          <span>0.8 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span><span class="dot">·</span><span>Closes 9PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined">north_east</span>
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div class="pharmacy-card"
                      data-name="Mercury Drug"
                      data-address="Sayre Highway, Casisang, Malaybalay City, Bukidnon"
                      data-distance="1.1 km away"
                      data-status="Open · Open 24 Hours"
                      data-lat="8.1547" data-lng="125.1279"
                      data-img="../07_Assets/images/pharmacies/MercuryDrug.png"
                      data-medicines='[
                        {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"Low Stock · 3 packs","price":"₱3.00","per":"tablet","status":"low"},
                        {"name":"Cetirizine","dosage":"10mg · Tablet","stock":"In Stock · 12 packs","price":"₱5.00","per":"tablet","status":"in"},
                        {"name":"Omeprazole","dosage":"20mg · Capsule","stock":"In Stock · 7 packs","price":"₱7.25","per":"capsule","status":"in"}
                      ]'>
                      <img src="../07_Assets/images/pharmacies/MercuryDrug.jpg" alt="Mercury Drug" class="pharmacy-card-img" />
                      <div class="pharmacy-card-body">
                        <h6>Mercury Drug</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined">location_on</span>
                          <span>1.1 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span><span class="dot">·</span><span>Open 24 Hours</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined">north_east</span>
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div class="pharmacy-card"
                      data-name="Watsons Pharmacy"
                      data-address="Centrio Mall, Sayre Highway, Malaybalay City, Bukidnon"
                      data-distance="1.4 km away"
                      data-status="Open · Closes 9PM"
                      data-lat="8.1547" data-lng="125.1275"
                      data-img="../07_Assets/images/pharmacies/Watsons.png"
                      data-medicines='[
                        {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 12 packs","price":"₱2.75","per":"tablet","status":"in"},
                        {"name":"Vitamin C","dosage":"500mg · Tablet","stock":"In Stock · 20 packs","price":"₱2.00","per":"tablet","status":"in"},
                        {"name":"Biogesic","dosage":"500mg · Tablet","stock":"In Stock · 15 packs","price":"₱3.50","per":"tablet","status":"in"}
                      ]'>
                      <img src="../07_Assets/images/pharmacies/Watsons.png" alt="Watsons Pharmacy" class="pharmacy-card-img" />
                      <div class="pharmacy-card-body">
                        <h6>Watsons Pharmacy</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined">location_on</span>
                          <span>1.4 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span><span class="dot">·</span><span>Closes 9PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined">north_east</span>
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div class="pharmacy-card"
                      data-name="Rojon Pharmacy"
                      data-address="546C+P3R, Sayre Hwy, Malaybalay City, Bukidnon"
                      data-distance="1.7 km away"
                      data-status="Open · Closes 10PM"
                      data-lat="8.1558" data-lng="125.1269"
                      data-img="../07_Assets/images/pharmacies/RojonPharmacy.png"
                      data-medicines='[
                        {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 5 packs","price":"₱2.50","per":"tablet","status":"in"},
                        {"name":"Amoxicillin","dosage":"250mg · Capsule","stock":"Low Stock · 1 pack","price":"₱6.00","per":"capsule","status":"low"},
                        {"name":"Mefenamic Acid","dosage":"500mg · Capsule","stock":"In Stock · 9 packs","price":"₱4.00","per":"capsule","status":"in"}
                      ]'>
                      <img src="../07_Assets/images/pharmacies/RojonPharmacy.png" alt="Rojon Pharmacy" class="pharmacy-card-img" />
                      <div class="pharmacy-card-body">
                        <h6>Rojon Pharmacy</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined">location_on</span>
                          <span>1.7 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span><span class="dot">·</span><span>Closes 10PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined">north_east</span>
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div class="pharmacy-card"
                      data-name="TGP The Generics Pharmacy"
                      data-address="Chavez St., Poblacion, Malaybalay City, Bukidnon"
                      data-distance="2.0 km away"
                      data-status="Open · Closes 8PM"
                      data-lat="8.1567" data-lng="125.1263"
                      data-img="../07_Assets/images/pharmacies/TGP.png"
                      data-medicines='[
                        {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 20 packs","price":"₱2.00","per":"tablet","status":"in"},
                        {"name":"Losartan","dosage":"25mg · Tablet","stock":"In Stock · 8 packs","price":"₱3.50","per":"tablet","status":"in"},
                        {"name":"Amlodipine","dosage":"5mg · Tablet","stock":"In Stock · 11 packs","price":"₱4.00","per":"tablet","status":"in"}
                      ]'>
                      <img src="../07_Assets/images/pharmacies/TGP.png" alt="TGP The Generics Pharmacy" class="pharmacy-card-img" />
                      <div class="pharmacy-card-body">
                        <h6>TGP The Generics Pharmacy</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined">location_on</span>
                          <span>2.0 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span><span class="dot">·</span><span>Closes 8PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined">north_east</span>
                          Get Directions
                        </button>
                      </div>
                    </div>

                  </div>
                </div>

                <!-- Most Searched -->
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

                </div><!-- /.mf-home-sheet -->
              </div>
            </div>

            <!-- LEFT PANEL: pharmacy detail -->
            <div class="left-panel" id="home-left-panel" style="display:none;">
              <div id="home-view-detail">
                <button class="back-btn" id="home-back-btn">
                  <span class="material-symbols-outlined">arrow_back</span>
                  Back
                </button>
                <div class="detail-header">
                  <div class="detail-pharmacy-name-row">
                    <div class="detail-icon-box">
                      <span class="material-symbols-outlined">local_pharmacy</span>
                    </div>
                    <div>
                      <h5 id="home-detail-name"></h5>
                      <div class="user-location">
                        <span class="material-symbols-outlined">location_on</span>
                        <span id="home-detail-subtitle"></span>
                      </div>
                    </div>
                  </div>
                  <img id="home-detail-img" src="" alt="" class="detail-pharmacy-img" />
                </div>
                <div class="detail-info-block">
                  <h4 id="home-detail-name-big"></h4>
                  <div class="pharmacy-distance" style="margin-bottom:6px">
                    <span class="material-symbols-outlined">location_on</span>
                    <span id="home-detail-distance"></span>
                  </div>
                  <div class="pharmacy-status" style="margin-bottom:16px">
                    <span id="home-detail-status"></span>
                  </div>
                  <div class="detail-actions">
                    <button class="direction-btn" id="home-detail-get-directions">
                      <span class="material-symbols-outlined">north_east</span>
                      Get Directions
                    </button>
                    <button class="save-btn">
                      <span class="material-symbols-outlined">bookmark</span>
                      Save Pharmacy
                    </button>
                  </div>
                </div>
                <div class="detail-meta">
                  <div class="detail-meta-item">
                    <span class="material-symbols-outlined">location_on</span>
                    <span id="home-detail-address"></span>
                  </div>
                  <div class="detail-meta-item">
                    <span class="material-symbols-outlined">label</span>
                    <span>Add a label</span>
                  </div>
                </div>
                <div class="medicines-section">
                  <div class="medicines-section-header">
                    <span class="material-symbols-outlined">medication</span>
                    Available Medicines
                  </div>
                  <div id="home-medicines-list"></div>
                </div>
              </div>
            </div>

            <!-- RIGHT PANEL: Map -->
            <div class="right-panel" id="home-map-panel" style="display:none;">
              <div id="home-map" style="width:100%;height:100%;"></div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CHAT PANEL -->
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
      <button class="suggest-chip" onclick="sendMessage('What is Paracetamol used for?')">Paracetamol</button>
      <button class="suggest-chip" onclick="sendMessage('Find nearby pharmacies')">Nearby pharmacies</button>
      <button class="suggest-chip" onclick="sendMessage('Amoxicillin dosage')">Amoxicillin</button>
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

  <!-- FLOATING BUTTON -->
  <div class="floating-btns">
    <button class="float-btn" id="floatBtn" onclick="toggleChat()" aria-label="Open chat assistant">
      <span class="material-symbols-outlined" id="iconBot">smart_toy</span>
      <span class="material-symbols-outlined" id="iconClose" style="display:none">close</span>
    </button>
  </div>

  <script src="../bootstrap/js/jquery.min.js"></script>
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- CHAT SCRIPT -->
  <script>
    let isOpen = false;
    const panel = document.getElementById('chatPanel');
    const chatMsgs = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const iconBot = document.getElementById('iconBot');
    const iconClose = document.getElementById('iconClose');

    function toggleChat() {
      isOpen = !isOpen;
      panel.classList.toggle('hidden', !isOpen);
      panel.classList.toggle('visible', isOpen);
      iconBot.style.display = isOpen ? 'none' : '';
      iconClose.style.display = isOpen ? '' : 'none';
      if (isOpen) setTimeout(() => chatInput.focus(), 300);
    }

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
      div.innerHTML = type === 'bot' ?
        `<div class="msg-avatar">
             <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
           </div>
           <div><div class="bubble">${text}</div><div class="msg-time">${getTime()}</div></div>` :
        `<div><div class="bubble">${text}</div><div class="msg-time" style="text-align:right">${getTime()}</div></div>`;
      chatMsgs.appendChild(div);
      scrollBottom();
    }

    function showTyping() {
      const t = document.createElement('div');
      t.className = 'msg bot';
      t.id = 'typingIndicator';
      t.innerHTML = `<div class="msg-avatar">
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

    function sendMessage(prefill) {
      const text = prefill || chatInput.value.trim();
      if (!text) return;
      addMessage(text, 'user');
      chatInput.value = '';
      document.getElementById('chatSuggestions').style.display = 'none';
      showTyping();
      setTimeout(() => {
        removeTyping();
        // TODO: replace with real API call
        addMessage('I received your message. This is a placeholder response.', 'bot');
      }, 1100);
    }
  </script>

  <!-- MAP / PHARMACY DETAIL SCRIPT -->
  <script>
    let homeLeafletMap = null;

    function renderHomeMedicines(medicines) {
      const list = document.getElementById('home-medicines-list');
      if (!medicines || medicines.length === 0) {
        list.innerHTML = '<div class="no-medicines">No medicine data available.</div>';
        return;
      }
      list.innerHTML = medicines.map(m => `
        <div class="medicine-item">
          <div class="medicine-item-left">
            <div class="medicine-name">${m.name}</div>
            <div class="medicine-dosage">${m.dosage}</div>
            <div class="medicine-stock ${m.status === 'in' ? 'in-stock' : 'low-stock'}">
              <span class="material-symbols-outlined">${m.status === 'in' ? 'check_circle' : 'warning'}</span>
              ${m.stock}
            </div>
          </div>
          <div class="medicine-item-right">
            <div class="medicine-price">${m.price}</div>
            <div class="medicine-per">/ ${m.per}</div>
          </div>
        </div>
      `).join('');
    }

    function openHomeDirections(card) {
      const {
        name,
        address,
        distance,
        status,
        lat,
        lng,
        img
      } = card.dataset;
      const medicines = JSON.parse(card.dataset.medicines || '[]');

      document.getElementById('home-detail-name').textContent = name;
      document.getElementById('home-detail-subtitle').textContent = address;
      document.getElementById('home-detail-name-big').textContent = name;
      document.getElementById('home-detail-distance').textContent = distance;
      document.getElementById('home-detail-address').textContent = address;
      document.getElementById('home-detail-img').src = img;

      const [openPart, ...rest] = status.split('·');
      document.getElementById('home-detail-status').innerHTML =
        `<span class="open-text">${openPart.trim()}</span><span class="dot"> · </span><span>${rest.join('·').trim()}</span>`;

      document.getElementById('home-detail-get-directions').onclick = () =>
        window.open(`https://www.openstreetmap.org/directions?to=${lat},${lng}`, '_blank');

      renderHomeMedicines(medicines);

      document.getElementById('home-view-list').style.display = 'none';
      document.getElementById('home-left-panel').style.display = 'block';
      document.getElementById('home-map-panel').style.display = 'block';

      const latF = parseFloat(lat);
      const lngF = parseFloat(lng);

      setTimeout(() => {
        const icon = L.divIcon({
          className: '',
          html: `<div class="mf-pin-bounce"><div style="background:#1d9e75;width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 3px 10px rgba(0,0,0,0.28);display:flex;align-items:center;justify-content:center;"><span class="material-symbols-outlined" style="transform:rotate(45deg);color:#fff;font-size:15px;">local_pharmacy</span></div></div>`,
          iconSize: [32, 32],
          iconAnchor: [16, 32],
          popupAnchor: [0, -34]
        });

        if (!homeLeafletMap) {
          homeLeafletMap = L.map('home-map').setView([latF, lngF], 16);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
          }).addTo(homeLeafletMap);
        } else {
          homeLeafletMap.setView([latF, lngF], 16);
          homeLeafletMap.eachLayer(l => {
            if (l instanceof L.Marker) homeLeafletMap.removeLayer(l);
          });
        }

        L.marker([latF, lngF], {
            icon
          })
          .addTo(homeLeafletMap)
          .bindPopup(`
            <div class="popup-inner">
              <div class="popup-top-bar"><div class="popup-name">${name}</div></div>
              <div class="popup-bottom-bar">
                <div class="popup-address">${address}</div>
                <div class="popup-dist">${distance}</div>
              </div>
            </div>`, {
            maxWidth: 260
          })
          .openPopup();

        homeLeafletMap.invalidateSize();
      }, 100);
    }

    document.getElementById('home-back-btn').addEventListener('click', () => {
      document.getElementById('home-view-list').style.display = 'block';
      document.getElementById('home-left-panel').style.display = 'none';
      document.getElementById('home-map-panel').style.display = 'none';
    });

    function goToPharmacyMap(card) {
      if (!card) return;
      const name = card.dataset.name || '';
      window.location.href = `04_Pharmacies.php?pharmacy=${encodeURIComponent(name)}`;
    }

    document.querySelectorAll('.nearby-section .direction-btn').forEach(btn => {
      btn.addEventListener('click', e => {
        e.stopPropagation();
        goToPharmacyMap(btn.closest('.pharmacy-card'));
      });
    });

    document.querySelectorAll('.nearby-section .pharmacy-card').forEach(card => {
      card.addEventListener('click', () => goToPharmacyMap(card));
    });

    // Functional Medicine Search + Autosuggest
    const HOME_MEDICINES = <?= json_encode($medicinesList, JSON_UNESCAPED_SLASHES) ?>;
    const homeSearchWrapper = document.getElementById('homeSearchWrapper');
    const homeSearchInput = document.getElementById('homeMedicineSearch');
    const homeSearchSuggest = document.getElementById('homeSearchSuggest');

    function goToMedicineMap(query) {
      const q = (query || '').trim();
      if (!q) return;
      window.location.href = `02.3_MedicineMap.php?q=${encodeURIComponent(q)}`;
    }

    function renderHomeSuggestions(query) {
      if (!homeSearchSuggest) return;
      const q = (query || '').trim().toLowerCase();
      if (!q) {
        homeSearchSuggest.classList.remove('is-open');
        return;
      }
      const matches = HOME_MEDICINES.filter(m =>
        (m.brand_name || '').toLowerCase().includes(q) ||
        (m.generic_name || '').toLowerCase().includes(q) ||
        (m.category || '').toLowerCase().includes(q)
      ).slice(0, 6);

      if (!matches.length) {
        homeSearchSuggest.innerHTML = `<div class="px-3 py-2 small text-muted">No matching medicine. Press Enter to search map.</div>`;
      } else {
        homeSearchSuggest.innerHTML = '';
        matches.forEach(m => {
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'mf-home-suggest-item';
          btn.innerHTML = `
            <img src="../${m.image}" alt="${m.brand_name}" />
            <div style="min-width:0;flex:1;">
              <div style="font-size:0.86rem;font-weight:600;color:#1f2937;">${m.brand_name} (${m.generic_name})</div>
              <div style="font-size:0.74rem;color:#6b7280;">${m.dosage} · ${m.dosage_form} · ${m.requires_prescription ? 'Requires Rx' : 'Over-the-Counter'}</div>
            </div>
          `;
          btn.addEventListener('click', () => goToMedicineMap(m.brand_name));
          homeSearchSuggest.appendChild(btn);
        });
      }
      homeSearchSuggest.classList.add('is-open');
    }

    if (homeSearchInput && homeSearchSuggest) {
      homeSearchInput.addEventListener('input', () => renderHomeSuggestions(homeSearchInput.value));
      homeSearchInput.addEventListener('focus', () => {
        if (homeSearchInput.value.trim()) renderHomeSuggestions(homeSearchInput.value);
      });
      homeSearchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          goToMedicineMap(homeSearchInput.value);
        } else if (e.key === 'Escape') {
          homeSearchSuggest.classList.remove('is-open');
        }
      });
    }

    const homeSearchBtn = document.getElementById('homeSearchBtn');
    if (homeSearchBtn && homeSearchInput) {
      homeSearchBtn.addEventListener('click', () => {
        if (homeSearchInput.value.trim()) {
          goToMedicineMap(homeSearchInput.value);
        } else {
          homeSearchInput.focus();
        }
      });
    }

    document.addEventListener('click', (e) => {
      if (homeSearchWrapper && homeSearchSuggest && !homeSearchWrapper.contains(e.target)) {
        homeSearchSuggest.classList.remove('is-open');
      }
    });

    document.querySelectorAll('.most-searched-section .medicine-rank-card').forEach(card => {
      card.style.cursor = 'pointer';
      card.addEventListener('click', () => {
        const title = (card.querySelector('h6')?.textContent || '').replace(/\s+\d+\s*mg$/i, '').trim();
        if (title) goToMedicineMap(title);
      });
    });
  </script>

</body>

</html>