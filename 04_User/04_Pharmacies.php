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
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MediFind: Pharmacies</title>

  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <!-- <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" /> -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/pharmacies.css?v=20261128" />

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />

  <style>
    @keyframes mfUserPulse {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.55); }
      70% { transform: scale(1.05); box-shadow: 0 0 0 14px rgba(245, 158, 11, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }

    @keyframes mfPinBounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-6px); }
    }

    .mf-pin-bounce {
      animation: mfPinBounce 1.35s ease-in-out infinite;
      will-change: transform;
    }

    @keyframes mfRouteFlow {
      to { stroke-dashoffset: -48; }
    }

    .mf-route-flow-line {
      animation: mfRouteFlow 0.9s linear infinite !important;
    }

    .mf-route-eta-badge {
      background: #facc15;
      color: #422006;
      border: 2px solid #ffffff;
      border-radius: 999px;
      padding: 4px 10px;
      font-size: 0.74rem;
      font-weight: 700;
      box-shadow: 0 4px 12px rgba(202, 138, 4, 0.35);
      display: inline-flex;
      align-items: center;
      gap: 4px;
      white-space: nowrap;
    }

    .mf-user-beacon {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      background: #f59e0b;
      border: 3px solid #ffffff;
      box-shadow: 0 2px 8px rgba(15, 23, 42, 0.35);
      animation: mfUserPulse 1.8s infinite;
    }

    #view-detail .detail-pharmacy-name-row {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-bottom: 14px;
    }

    #view-detail .detail-icon-box {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    #view-detail .detail-header-text {
      min-width: 0;
      flex: 1;
    }

    #view-detail #detail-name {
      margin: 0 0 3px;
      font-size: 1.12rem;
      font-weight: 700;
      color: #1f2937;
      line-height: 1.25;
    }

    #view-detail .detail-header .user-location {
      margin-top: 0;
      display: flex;
      align-items: flex-start;
      gap: 4px;
      font-size: 0.84rem;
      line-height: 1.38;
      color: #6b7280;
    }

    #view-detail .detail-header .user-location .material-symbols-outlined {
      font-size: 1rem;
      margin-top: 2px;
      flex-shrink: 0;
    }

    #view-list .search-wrapper {
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

    .mf-mobile-map-header,
    .mf-mobile-map-sheet,
    .mf-mobile-sheet-show-btn {
      display: none;
    }

    #map-panel {
      position: relative;
    }

    #map-panel .leaflet-control-zoom {
      display: none !important;
    }

    .mf-mobile-map-search {
      display: flex;
      align-items: center;
      gap: 8px;
      position: absolute;
      top: 14px;
      left: 16px;
      right: 16px;
      z-index: 610;
      background: #ffffff;
      border-radius: 999px;
      padding: 9px 14px;
      box-shadow: 0 4px 16px rgba(15, 23, 42, 0.12);
    }

    .mf-mobile-map-search input {
      border: none;
      outline: none;
      background: transparent;
      font-size: 0.88rem;
      width: 100%;
      color: #1f2937;
    }

    .mf-map-search-clear {
      border: none;
      background: #f1f5f9;
      color: #64748b;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: none;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      cursor: pointer;
    }

    .mf-map-search-clear.is-visible {
      display: inline-flex;
    }

    .mf-map-suggest-dropdown {
      position: absolute;
      top: calc(100% + 6px);
      left: 0;
      right: 0;
      background: #ffffff;
      border-radius: 16px;
      border: 1px solid #e5ece9;
      box-shadow: 0 10px 28px rgba(15, 23, 42, 0.14);
      max-height: 0;
      opacity: 0;
      overflow: hidden;
      pointer-events: none;
      transform: translateY(-6px);
      transition: max-height 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                  opacity 0.2s ease,
                  transform 0.24s cubic-bezier(0.22, 1, 0.36, 1);
      z-index: 650;
    }

    .mf-map-suggest-dropdown.is-open {
      max-height: 240px;
      opacity: 1;
      overflow-y: auto;
      pointer-events: auto;
      transform: translateY(0);
    }

    .mf-map-suggest-item {
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

    .mf-map-suggest-item:last-child {
      border-bottom: none;
    }

    .mf-map-suggest-item:hover {
      background: #f0fdf9;
    }

    .mf-map-suggest-icon {
      width: 30px;
      height: 30px;
      border-radius: 10px;
      background: #e8f7f2;
      color: #1d9e75;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .mf-map-suggest-name {
      font-size: 0.86rem;
      font-weight: 600;
      color: #1f2937;
      line-height: 1.2;
    }

    .mf-map-suggest-sub {
      font-size: 0.74rem;
      color: #6b7280;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    @media (max-width: 767.98px) {
      #content {
        box-sizing: border-box !important;
      }

      #map-panel .leaflet-control-attribution {
        display: none !important;
      }

      #left-panel {
        display: none !important;
      }

      #map-panel {
        position: relative;
        width: 100% !important;
        height: calc(100dvh - 84px) !important;
        max-height: calc(100dvh - 84px) !important;
        flex: 1 1 100% !important;
        flex-direction: column !important;
        background: #ffffff;
        overflow: hidden;
      }

      #map-panel #map {
        flex: 1 1 auto !important;
        min-height: 260px !important;
        width: 100% !important;
      }

      .mf-mobile-map-header {
        display: grid;
        grid-template-columns: 44px 1fr 44px;
        align-items: center;
        padding: 10px 12px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        flex-shrink: 0;
        z-index: 620;
      }

      .mf-mobile-map-back {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #1f2937;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
      }

      .mf-mobile-map-title {
        margin: 0;
        text-align: center;
        font-size: 1.05rem;
        font-weight: 700;
        color: #1d9e75;
      }

      .mf-mobile-map-search {
        top: 70px;
        left: 14px;
        right: 14px;
      }

      .mf-mobile-map-sheet {
        display: block;
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1040;
        background: #ffffff;
        border-top-left-radius: 24px;
        border-top-right-radius: 24px;
        padding: 10px 14px 18px;
        box-shadow: 0 -6px 24px rgba(15, 23, 42, 0.14);
        transform: translateY(0);
        opacity: 1;
        transition: transform 0.34s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.26s ease;
      }

      .mf-mobile-map-sheet.is-hidden {
        transform: translateY(105%);
        opacity: 0;
        pointer-events: none;
      }

      .mf-mobile-sheet-handle {
        width: 36px;
        height: 4px;
        border-radius: 99px;
        background: #d1d5db;
        margin: 0 auto 8px;
      }

      .mf-mobile-sheet-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
      }

      .mf-mobile-sheet-heading {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
      }

      .mf-mobile-sheet-hide-btn {
        width: 28px;
        height: 28px;
        border-radius: 99px;
        border: 1px solid #e5ece9;
        background: #f8fafc;
        color: #4b5563;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
      }

      .mf-mobile-sheet-show-btn {
        position: absolute;
        right: 14px;
        bottom: 20px;
        z-index: 1050;
        background: #1d9e75;
        color: #ffffff;
        border: none;
        border-radius: 999px;
        padding: 9px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        box-shadow: 0 6px 18px rgba(29, 158, 117, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transform: translateY(12px) scale(0.94);
        transition: opacity 0.26s ease, transform 0.26s cubic-bezier(0.22, 1, 0.36, 1), visibility 0.26s;
      }

      .mf-mobile-sheet-show-btn.is-visible {
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        transform: translateY(0) scale(1);
      }

      .mf-mobile-sheet-card {
        border: 1px solid #e5ece9;
        border-radius: 16px;
        padding: 9px 10px;
        background: #ffffff;
      }

      .mf-mobile-sheet-img {
        width: 100%;
        height: 72px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 6px;
      }

      .mf-mobile-sheet-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 4px;
      }

      .mf-mobile-sheet-name {
        font-size: 0.98rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
      }

      .mf-mobile-sheet-badge {
        background: #e8f7f2;
        color: #0f6e56;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 999px;
        white-space: nowrap;
      }

      .mf-mobile-sheet-meta {
        display: flex;
        align-items: flex-start;
        gap: 4px;
        font-size: 0.78rem;
        color: #6b7280;
        margin-bottom: 8px;
      }

      .mf-mobile-sheet-meta .material-symbols-outlined {
        font-size: 0.95rem;
        color: #1d9e75;
        margin-top: 1px;
        flex-shrink: 0;
      }

      .mf-mobile-sheet-dir-btn {
        background: #ff5b27;
        color: #ffffff;
        border: none;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 7px 16px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
      }
      #content,
      .content-body,
      .pharmacies-layout {
        padding: 0 !important;
        margin: 0 !important;
      }

      #view-list {
        padding: 0 !important;
        background: #ffffff !important;
        overflow-y: auto !important;
        height: 100% !important;
      }

      #view-list .search-section {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #178a65 0%, #1d9e75 100%);
        padding: 18px 16px 34px;
        margin-bottom: 0 !important;
      }

      #view-list .search-section::before {
        content: "";
        position: absolute;
        inset: 0;
        background: url("../07_Assets/images/backgrounds/malaybalay-hero-bg.jpg") center / cover no-repeat;
        opacity: 0.22;
        mix-blend-mode: luminosity;
        pointer-events: none;
        z-index: 0;
      }

      #view-list .search-section > * {
        position: relative;
        z-index: 1;
      }

      #view-list .welcome-location-block {
        margin-bottom: 0 !important;
      }

      #view-list .welcome-user {
        color: #ffffff !important;
        font-size: 1.35rem !important;
        font-weight: 700 !important;
        margin: 0 !important;
      }

      #view-list .search-section .user-location {
        display: none !important;
      }

      #view-list .search-row {
        margin-top: 14px !important;
      }

      #view-list .search-wrapper {
        background: #ffffff !important;
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
      }

      #view-list .search-filter-btn {
        background: #ffffff !important;
        color: #1d9e75 !important;
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
      }

      .mf-pharmacies-sheet {
        background: #ffffff;
        border-top-left-radius: 26px;
        border-top-right-radius: 26px;
        margin-top: -18px;
        padding: 20px 16px 96px;
        position: relative;
        z-index: 2;
      }

      .mf-pharmacies-sheet .section-header {
        margin-bottom: 12px !important;
      }

      .mf-pharmacies-sheet .section-header a {
        display: none !important;
      }

      .mf-pharmacies-sheet .pharmacy-list {
        display: flex !important;
        flex-direction: column !important;
        gap: 14px !important;
      }

      .mf-pharmacies-sheet .pharmacy-card {
        width: 100% !important;
        max-width: none !important;
        padding: 12px !important;
        border-radius: 16px !important;
        border: 1px solid #e5ece9 !important;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03) !important;
      }

      .mf-pharmacies-sheet .pharmacy-card-img {
        width: 100% !important;
        height: 132px !important;
        object-fit: cover !important;
        border-radius: 12px !important;
        margin-bottom: 10px !important;
      }

      .mf-pharmacies-sheet .pharmacy-card-body {
        padding: 0 2px 2px !important;
        text-align: left !important;
      }

      .mf-pharmacies-sheet .pharmacy-card-body h6 {
        font-size: 1rem !important;
        font-weight: 700 !important;
        margin: 0 0 3px !important;
      }

      .mf-pharmacies-sheet .pharmacy-distance,
      .mf-pharmacies-sheet .pharmacy-status {
        font-size: 0.78rem !important;
        margin-bottom: 3px !important;
      }

      .mf-pharmacies-sheet .direction-btn {
        background: #ff5b27 !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 999px !important;
        font-weight: 600 !important;
        font-size: 0.78rem !important;
        padding: 6px 14px !important;
        margin-top: 6px !important;
        width: auto !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
      }

      .mf-pharmacies-sheet .direction-btn .material-symbols-outlined {
        font-size: 0.9rem !important;
      }
    }
  </style>
</head>

<body data-active="04">
  <div class="wrapper d-flex align-items-stretch">
    <div id="sidebar-container"><?php include '../01_Includes/users-sidebar-fixed.php'; ?></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content">
        <div class="content-body">
          <div class="pharmacies-layout">
            <!-- Default view: search + list -->
            <div id="view-list" style="width: 100%; padding: 20px 16px 32px">
              <div class="search-section">
                <div class="welcome-location-block">
                  <h2 class="welcome-user">Browse Pharmacies</h2>
                  <div class="user-location">
                    <span class="material-symbols-outlined">location_on</span>
                    <span>Malaybalay, 8700, Bukidnon, Philippines</span>
                  </div>
                </div>
                <div class="search-row">
                  <div class="search-wrapper">
                    <span class="material-symbols-outlined" style="color: #87a199; font-size: 1.2rem">search</span>
                    <input type="text" id="pharmacyListSearchInput" placeholder="Search pharmacy by name..." />
                    <button type="button" class="mf-inline-search-btn" id="pharmacyListSearchBtn">Search</button>
                  </div>
                </div>
              </div>

              <div class="mf-pharmacies-sheet">
              <div class="nearby-section">
                <div class="section-header">
                  <h5>All Pharmacies</h5>
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
              </div><!-- /.mf-pharmacies-sheet -->
            </div>

            <!-- LEFT PANEL -->
            <div class="left-panel" id="left-panel" style="display: none">
              <div id="view-detail" style="display: none">
                <button class="back-btn" id="back-btn">
                  <span class="material-symbols-outlined">arrow_back</span>
                  Back
                </button>

                <div class="detail-header">
                  <div class="detail-pharmacy-name-row">
                    <div class="detail-icon-box">
                      <span class="material-symbols-outlined">local_pharmacy</span>
                    </div>
                    <div class="detail-header-text">
                      <h5 id="detail-name">Rojon Pharmacy</h5>
                      <div class="user-location">
                        <span class="material-symbols-outlined">location_on</span>
                        <span id="detail-subtitle">Malaybalay, 8700, Bukidnon, Philippines</span>
                      </div>
                    </div>
                  </div>
                  <img id="detail-img" src="" alt="" class="detail-pharmacy-img" />
                </div>

                <div class="detail-info-block">
                  <h4 id="detail-name-big"></h4>
                  <div class="pharmacy-distance" style="margin-bottom: 6px">
                    <span class="material-symbols-outlined">location_on</span>
                    <span id="detail-distance"></span>
                  </div>
                  <div class="pharmacy-status" style="margin-bottom: 16px">
                    <span id="detail-status"></span>
                  </div>
                  <div class="detail-actions">
                    <button class="direction-btn" id="detail-get-directions">
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
                    <span id="detail-address"></span>
                  </div>
                </div>

              </div>
            </div>



            <!-- RIGHT PANEL: Map -->
            <div class="right-panel" id="map-panel" style="display: none">
              <div class="mf-mobile-map-header">
                <button type="button" class="mf-mobile-map-back" id="mfMobileMapBack" aria-label="Back to pharmacies">
                  <span class="material-symbols-outlined">arrow_back</span>
                </button>
                <h2 class="mf-mobile-map-title">Pharmacies Nearby</h2>
                <div></div>
              </div>

              <div class="mf-mobile-map-search" id="mfMobileMapSearchBox">
                <span class="material-symbols-outlined" style="color:#87a199;font-size:1.1rem;">search</span>
                <input type="text" id="mfMobileMapSearchInput" placeholder="Search pharmacy..." autocomplete="off" />
                <button type="button" class="mf-map-search-clear is-visible" id="mfMobileMapSearchClear" aria-label="Clear search">
                  <span class="material-symbols-outlined" style="font-size:0.95rem;">close</span>
                </button>
                <div class="mf-map-suggest-dropdown" id="mfMapSuggestDropdown"></div>
              </div>

              <div id="map" style="width: 100%; height: 100%"></div>

              <button type="button" class="mf-mobile-sheet-show-btn" id="mfMobileSheetShowBtn">
                <span class="material-symbols-outlined" style="font-size:1rem;">local_pharmacy</span>
                Show Pharmacy
              </button>

              <div class="mf-mobile-map-sheet" id="mfMobileMapSheet">
                <div class="mf-mobile-sheet-handle"></div>
                <div class="mf-mobile-sheet-top">
                  <p class="mf-mobile-sheet-heading">Participating Pharmacy</p>
                  <button type="button" class="mf-mobile-sheet-hide-btn" id="mfMobileSheetHideBtn" aria-label="Hide pharmacy card">
                    <span class="material-symbols-outlined" style="font-size:1.1rem;">keyboard_arrow_down</span>
                  </button>
                </div>
                <div class="mf-mobile-sheet-card">
                  <img id="mfSheetPharmImg" src="" alt="" class="mf-mobile-sheet-img" />
                  <div class="mf-mobile-sheet-row">
                    <h6 class="mf-mobile-sheet-name" id="mfSheetPharmName"></h6>
                    <span class="mf-mobile-sheet-badge" id="mfSheetPharmStatus"></span>
                  </div>
                  <div class="mf-mobile-sheet-meta" style="margin-bottom:0;">
                    <span class="material-symbols-outlined">location_on</span>
                    <span id="mfSheetPharmMeta"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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

  <script src="../07_Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    let leafletMap = null;
    let userCoords = [8.1574, 125.1277];
    let userMarker = null;
    let pharmacyMarkers = [];
    let routeLayers = [];
    let routeAnimFrame = null;
    let activePharmacyCard = null;

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (pos) => {
          userCoords = [pos.coords.latitude, pos.coords.longitude];
          if (leafletMap && activePharmacyCard) {
            updateMapForPharmacy(activePharmacyCard);
          }
        },
        () => {},
        { enableHighAccuracy: true, timeout: 6000 }
      );
    }

    function clearRouteLayers() {
      if (routeAnimFrame) {
        cancelAnimationFrame(routeAnimFrame);
        routeAnimFrame = null;
      }
      if (!leafletMap) return;
      routeLayers.forEach((l) => leafletMap.removeLayer(l));
      routeLayers = [];
    }

    function createPharmacyPin(isActive) {
      const bg = isActive ? "#ff5b27" : "#1d9e75";
      const size = isActive ? 34 : 28;
      return L.divIcon({
        className: "",
        html: `<div class="mf-pin-bounce"><div style="background:${bg};width:${size}px;height:${size}px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 3px 10px rgba(0,0,0,0.28);display:flex;align-items:center;justify-content:center;"><span class="material-symbols-outlined" style="transform:rotate(45deg);color:#fff;font-size:${isActive ? 16 : 13}px;">local_pharmacy</span></div></div>`,
        iconSize: [size, size],
        iconAnchor: [size / 2, size],
        popupAnchor: [0, -size]
      });
    }

    function updatePharmacyDetailUI(card) {
      const name = card.dataset.name;
      const address = card.dataset.address;
      const distance = card.dataset.distance;
      const status = card.dataset.status;
      const lat = parseFloat(card.dataset.lat);
      const lng = parseFloat(card.dataset.lng);
      const img = card.dataset.img;

      const detailNameEl = document.getElementById("detail-name");
      if (detailNameEl) detailNameEl.textContent = name;
      const detailSubEl = document.getElementById("detail-subtitle");
      if (detailSubEl) detailSubEl.textContent = address;
      document.getElementById("detail-name-big").textContent = name;
      document.getElementById("detail-distance").textContent = distance;
      const [openPart, ...rest] = status.split("·");
      const statusEl = document.getElementById("detail-status");
      statusEl.innerHTML = `<span class="open-text">${openPart.trim()}</span><span class="dot"> · </span><span>${rest.join("·").trim()}</span>`;
      document.getElementById("detail-address").textContent = address;
      document.getElementById("detail-img").src = img;

      document.getElementById("detail-get-directions").onclick = () => {
        window.open(`https://www.openstreetmap.org/directions?to=${lat},${lng}`, "_blank");
      };

      const sheetImg = document.getElementById("mfSheetPharmImg");
      const sheetName = document.getElementById("mfSheetPharmName");
      const sheetStatus = document.getElementById("mfSheetPharmStatus");
      const sheetMeta = document.getElementById("mfSheetPharmMeta");
      const sheetSearch = document.getElementById("mfMobileMapSearchInput");
      if (sheetImg) sheetImg.src = img;
      if (sheetName) sheetName.textContent = name;
      if (sheetStatus) sheetStatus.textContent = status;
      if (sheetMeta) sheetMeta.textContent = `${distance} · ${address}`;
      if (sheetSearch) sheetSearch.value = name;
    }

    function drawRouteToPharmacy(targetLat, targetLng) {
      clearRouteLayers();
      if (!leafletMap) return;

      const [uLat, uLng] = userCoords;
      const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${uLng},${uLat};${targetLng},${targetLat}?overview=full&geometries=geojson`;

      const renderCoords = (coords, durationSec) => {
        if (!leafletMap || coords.length < 2) return;
        const casing = L.polyline(coords, { color: "#ca8a04", weight: 14, opacity: 0.95, lineJoin: "round", lineCap: "round" }).addTo(leafletMap);
        const line = L.polyline(coords, { color: "#facc15", weight: 9, opacity: 1, lineJoin: "round", lineCap: "round" }).addTo(leafletMap);
        const flow = L.polyline(coords, { color: "#92400e", weight: 4, opacity: 0.75, dashArray: "10, 14", className: "mf-route-flow-line", lineJoin: "round", lineCap: "round" }).addTo(leafletMap);
        const destHighlight = L.circleMarker(coords[coords.length - 1], {
          radius: 20,
          color: "#ca8a04",
          weight: 3,
          fillColor: "#facc15",
          fillOpacity: 0.3
        }).addTo(leafletMap);

        const etaMins = Math.max(1, Math.round((durationSec || 60) / 60));
        const midPt = coords[Math.floor(coords.length / 2)];
        const etaMarker = L.marker(midPt, {
          interactive: false,
          icon: L.divIcon({
            className: "",
            html: `<div class="mf-route-eta-badge"><span class="material-symbols-outlined" style="font-size:0.95rem;">directions_car</span>${etaMins} min</div>`,
            iconAnchor: [40, 14]
          })
        }).addTo(leafletMap);

        const dot = L.circleMarker(coords[0], {
          radius: 7,
          color: "#ffffff",
          weight: 3,
          fillColor: "#ff5b27",
          fillOpacity: 1
        }).addTo(leafletMap);

        routeLayers.push(casing, line, flow, destHighlight, etaMarker, dot);

        const bottomPad = window.innerWidth <= 767.98 ? 190 : 60;
        leafletMap.flyToBounds(line.getBounds(), {
          paddingTopLeft: [40, 80],
          paddingBottomRight: [40, bottomPad],
          duration: 0.8
        });

        let progress = 0;
        function animateDot() {
          progress = (progress + 0.008) % 1;
          const idx = Math.min(coords.length - 1, Math.floor(progress * (coords.length - 1)));
          dot.setLatLng(coords[idx]);
          routeAnimFrame = requestAnimationFrame(animateDot);
        }
        routeAnimFrame = requestAnimationFrame(animateDot);
      };

      fetch(osrmUrl)
        .then((r) => r.json())
        .then((data) => {
          const routeObj = data?.routes?.[0];
          const pts = routeObj?.geometry?.coordinates;
          if (Array.isArray(pts) && pts.length > 1) {
            renderCoords(pts.map(([lng, lat]) => [lat, lng]), routeObj.duration);
          } else {
            renderCoords([[uLat, uLng], [targetLat, targetLng]], 60);
          }
        })
        .catch(() => renderCoords([[uLat, uLng], [targetLat, targetLng]], 60));
    }

    function updateMapForPharmacy(selectedCard) {
      activePharmacyCard = selectedCard;
      const targetLat = parseFloat(selectedCard.dataset.lat);
      const targetLng = parseFloat(selectedCard.dataset.lng);

      if (!leafletMap) {
        leafletMap = L.map("map").setView([targetLat, targetLng], 15);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          attribution: "© OpenStreetMap contributors",
        }).addTo(leafletMap);
      }

      if (userMarker) leafletMap.removeLayer(userMarker);
      userMarker = L.marker(userCoords, {
        icon: L.divIcon({
          className: "",
          html: '<div class="mf-user-beacon"></div>',
          iconSize: [18, 18],
          iconAnchor: [9, 9]
        })
      }).addTo(leafletMap).bindTooltip("You are here", { direction: "top" });

      pharmacyMarkers.forEach((m) => leafletMap.removeLayer(m));
      pharmacyMarkers = [];

      document.querySelectorAll("#view-list .pharmacy-card").forEach((c) => {
        const cLat = parseFloat(c.dataset.lat);
        const cLng = parseFloat(c.dataset.lng);
        const isSelected = c === selectedCard;
        const marker = L.marker([cLat, cLng], {
          icon: createPharmacyPin(isSelected),
          zIndexOffset: isSelected ? 500 : 0
        }).addTo(leafletMap);

        const popupImg = c.querySelector(".pharmacy-card-img")?.getAttribute("src") || c.dataset.img || "../07_Assets/images/pharmacies/RosePharmacy.png";
        const statusText = (c.dataset.status || "Open").trim();
        const isClosed = /^closed/i.test(statusText);
        const distLabel = (c.dataset.distance || "").replace(/\s*away$/i, "");

        marker.bindPopup(`
          <div class="mf-popup-inner">
            <div class="mf-popup-hero">
              <img src="${popupImg}" alt="${c.dataset.name}" class="mf-popup-photo" />
              <div class="mf-popup-hero-grad"></div>
              <div class="mf-popup-titlebar">${c.dataset.name}</div>
            </div>
            <div class="mf-popup-body">
              <div class="d-flex align-items-start justify-content-between gap-2 mb-1">
                <div class="mf-popup-addr mb-0">${c.dataset.address}</div>
                <div class="mf-popup-dist">${distLabel}</div>
              </div>
              <div class="mf-popup-hours">${statusText}</div>
              <span class="mf-popup-pill ${isClosed ? 'unavail' : ''}" style="background:${isClosed ? '#fef2f2' : '#e8f7f2'};color:${isClosed ? '#b91c1c' : '#0f6e56'};">
                <span class="material-symbols-outlined" style="font-size:0.85rem;">${isClosed ? 'cancel' : 'check_circle'}</span>
                ${isClosed ? 'Closed' : 'Open Now'}
              </span>
            </div>
          </div>
        `, { maxWidth: 262, autoPan: false });

        marker.on("click", () => {
          updatePharmacyDetailUI(c);
          updateMapForPharmacy(c);
        });

        pharmacyMarkers.push(marker);
        if (isSelected) marker.openPopup();
      });

      drawRouteToPharmacy(targetLat, targetLng);
    }

    function openDirections(card) {
      updatePharmacyDetailUI(card);

      const mobileSheet = document.getElementById("mfMobileMapSheet");
      const mobileSheetShowBtn = document.getElementById("mfMobileSheetShowBtn");
      if (mobileSheet) mobileSheet.classList.remove("is-hidden");
      if (mobileSheetShowBtn) mobileSheetShowBtn.classList.remove("is-visible");

      document.getElementById("view-list").style.display = "none";
      document.getElementById("left-panel").style.display = "block";
      document.getElementById("view-detail").style.display = "block";
      document.getElementById("map-panel").style.display = window.innerWidth <= 767.98 ? "flex" : "block";

      setTimeout(() => {
        updateMapForPharmacy(card);
        if (leafletMap) leafletMap.invalidateSize();
      }, 120);
    }

    document.querySelectorAll(".pharmacy-card .direction-btn").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        openDirections(btn.closest(".pharmacy-card"));
      });
    });

    document.querySelectorAll(".pharmacy-card").forEach((card) => {
      card.addEventListener("click", () => openDirections(card));
    });

    function closeDetailView() {
      document.getElementById("view-list").style.display = "block";
      document.getElementById("left-panel").style.display = "none";
      document.getElementById("view-detail").style.display = "none";
      document.getElementById("map-panel").style.display = "none";
    }

    document.getElementById("back-btn").addEventListener("click", closeDetailView);
    const mobileMapBack = document.getElementById("mfMobileMapBack");
    if (mobileMapBack) mobileMapBack.addEventListener("click", closeDetailView);

    const mfMobileSheet = document.getElementById("mfMobileMapSheet");
    const mfMobileSheetHideBtn = document.getElementById("mfMobileSheetHideBtn");
    const mfMobileSheetShowBtn = document.getElementById("mfMobileSheetShowBtn");
    if (mfMobileSheet && mfMobileSheetHideBtn && mfMobileSheetShowBtn) {
      mfMobileSheetHideBtn.addEventListener("click", () => {
        mfMobileSheet.classList.add("is-hidden");
        mfMobileSheetShowBtn.classList.add("is-visible");
      });
      mfMobileSheetShowBtn.addEventListener("click", () => {
        mfMobileSheet.classList.remove("is-hidden");
        mfMobileSheetShowBtn.classList.remove("is-visible");
      });
    }

    // Live search filter for pharmacy cards
    const pharmacySearchInput = document.getElementById("pharmacyListSearchInput");
    const pharmacySearchBtn = document.getElementById("pharmacyListSearchBtn");
    function filterPharmacyCards() {
      if (!pharmacySearchInput) return;
      const q = pharmacySearchInput.value.trim().toLowerCase();
      document.querySelectorAll("#view-list .pharmacy-card").forEach((card) => {
        const name = (card.dataset.name || "").toLowerCase();
        const addr = (card.dataset.address || "").toLowerCase();
        const match = !q || name.includes(q) || addr.includes(q);
        card.style.display = match ? "" : "none";
      });
    }
    if (pharmacySearchInput) {
      pharmacySearchInput.addEventListener("input", filterPharmacyCards);
    }
    if (pharmacySearchBtn) {
      pharmacySearchBtn.addEventListener("click", () => {
        filterPharmacyCards();
        if (pharmacySearchInput && !pharmacySearchInput.value.trim()) pharmacySearchInput.focus();
      });
    }

    // Mobile Map Search Autosuggest Dropdown
    const mapSearchBox = document.getElementById("mfMobileMapSearchBox");
    const mapSearchInput = document.getElementById("mfMobileMapSearchInput");
    const mapSearchClear = document.getElementById("mfMobileMapSearchClear");
    const mapSuggestDropdown = document.getElementById("mfMapSuggestDropdown");

    function renderMapSuggestions(query) {
      if (!mapSuggestDropdown) return;
      const q = (query || "").trim().toLowerCase();
      const cards = Array.from(document.querySelectorAll("#view-list .pharmacy-card"));
      const matches = cards.filter((c) => {
        const name = (c.dataset.name || "").toLowerCase();
        const addr = (c.dataset.address || "").toLowerCase();
        return !q || name.includes(q) || addr.includes(q);
      });

      if (!matches.length) {
        mapSuggestDropdown.innerHTML = `<div class="px-3 py-2 small text-muted">No matching pharmacy found.</div>`;
      } else {
        mapSuggestDropdown.innerHTML = "";
        matches.forEach((c) => {
          const btn = document.createElement("button");
          btn.type = "button";
          btn.className = "mf-map-suggest-item";
          btn.innerHTML = `
            <span class="mf-map-suggest-icon"><span class="material-symbols-outlined" style="font-size:1rem;">local_pharmacy</span></span>
            <div style="min-width:0;flex:1;">
              <div class="mf-map-suggest-name">${c.dataset.name}</div>
              <div class="mf-map-suggest-sub">${c.dataset.distance} · ${c.dataset.address}</div>
            </div>
          `;
          btn.addEventListener("click", () => {
            mapSuggestDropdown.classList.remove("is-open");
            updatePharmacyDetailUI(c);
            updateMapForPharmacy(c);
            if (mapSearchClear) mapSearchClear.classList.add("is-visible");
          });
          mapSuggestDropdown.appendChild(btn);
        });
      }
      mapSuggestDropdown.classList.add("is-open");
    }

    if (mapSearchInput && mapSuggestDropdown) {
      mapSearchInput.addEventListener("focus", () => {
        renderMapSuggestions("");
      });
      mapSearchInput.addEventListener("input", () => {
        if (mapSearchClear) mapSearchClear.classList.toggle("is-visible", mapSearchInput.value.length > 0);
        renderMapSuggestions(mapSearchInput.value);
      });
      mapSearchInput.addEventListener("keydown", (e) => {
        if (e.key === "Escape") mapSuggestDropdown.classList.remove("is-open");
      });
    }

    if (mapSearchClear && mapSearchInput) {
      mapSearchClear.addEventListener("click", () => {
        mapSearchInput.value = "";
        mapSearchClear.classList.remove("is-visible");
        renderMapSuggestions("");
        mapSearchInput.focus();
      });
    }

    document.addEventListener("click", (e) => {
      if (mapSearchBox && mapSuggestDropdown && !mapSearchBox.contains(e.target)) {
        mapSuggestDropdown.classList.remove("is-open");
      }
    });

    // Auto-open map if ?pharmacy=... is passed (e.g. from Home Nearby Pharmacies)
    const initialPharmacy = new URLSearchParams(window.location.search).get("pharmacy");
    if (initialPharmacy) {
      const targetLower = initialPharmacy.trim().toLowerCase();
      const matched = Array.from(document.querySelectorAll("#view-list .pharmacy-card")).find(
        (c) => (c.dataset.name || "").trim().toLowerCase() === targetLower
      );
      if (matched) {
        openDirections(matched);
      }
    }
  </script>
</body>

</html>