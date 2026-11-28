<?php

// Prevent back button after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");


require_once '../02_Actions/GlobalVariables.php';
require_once '../00_Config/config.php';

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
  <title>MediFind: Profile</title>

  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <!-- <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" /> -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="../07_Assets/css/04_Includes CSS/topbar.css" />
  <link rel="stylesheet" href="../07_Assets/css/04_Includes CSS/sidebar.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/profile.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />

  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    .mf-mobile-profile-hub,
    .mf-mobile-detail-header {
      display: none;
    }

    @media (max-width: 767.98px) {
      #content,
      .content-body,
      .profile-page {
        padding: 0 !important;
        margin: 0 !important;
        background: #ffffff !important;
      }

      .profile-header-card,
      .profile-tabs-nav {
        display: none !important;
      }

      .mf-mobile-profile-hub {
        display: block !important;
        background: #ffffff;
      }

      .mf-mobile-profile-hub.is-hidden {
        display: none !important;
      }

      .mf-mobile-profile-hero {
        position: relative;
        overflow: hidden;
        height: 132px;
        background: linear-gradient(135deg, #178a65 0%, #1d9e75 100%);
      }

      .mf-mobile-profile-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: url("../07_Assets/images/backgrounds/malaybalay-hero-bg.jpg") center / cover no-repeat;
        opacity: 0.22;
        mix-blend-mode: luminosity;
        pointer-events: none;
        z-index: 0;
      }

      .mf-mobile-profile-sheet {
        background: #ffffff;
        border-top-left-radius: 26px;
        border-top-right-radius: 26px;
        margin-top: -24px;
        padding: 0 16px 96px;
        text-align: center;
      }

      .mf-mobile-avatar-wrap {
        position: relative;
        display: inline-block;
        margin-top: -62px;
      }

      .mf-mobile-profile-avatar {
        width: 124px;
        height: 124px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #ffffff;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.14);
        background: #ffffff;
      }

      .mf-mobile-avatar-edit {
        position: absolute;
        right: 2px;
        bottom: 4px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #ffffff;
        color: #1d9e75;
        border: 2px solid #1d9e75;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.12);
        cursor: pointer;
      }

      .mf-mobile-avatar-edit .material-symbols-outlined {
        font-size: 1rem;
      }

      .mf-mobile-profile-name {
        margin: 10px 0 2px;
        font-size: 1.2rem;
        font-weight: 700;
        color: #1d9e75;
      }

      .mf-mobile-profile-sub {
        margin: 0 0 18px;
        font-size: 0.84rem;
        color: #6b7280;
      }

      .mf-profile-menu-card {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        border: 1px solid #eef2f0;
        overflow: hidden;
        text-align: left;
      }

      .mf-profile-acc-item {
        border-bottom: 1px solid #f1f5f9;
      }

      .mf-profile-acc-item:last-child {
        border-bottom: none;
      }

      .mf-profile-menu-item {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 15px 18px;
        background: #ffffff;
        border: none;
        color: #4b5563;
        font-size: 0.94rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.16s ease, color 0.16s ease;
      }

      .mf-profile-menu-item:hover,
      .mf-profile-menu-item.is-open {
        background: #f8fafc;
        color: #1d9e75;
      }

      .mf-profile-menu-item .material-symbols-outlined {
        font-size: 1.3rem;
        color: #6b7280;
        transition: color 0.16s ease;
      }

      .mf-profile-menu-item.is-open .material-symbols-outlined {
        color: #1d9e75;
      }

      .mf-menu-chevron {
        margin-left: auto;
        font-size: 1.15rem !important;
        color: #9ca3af !important;
        transition: transform 0.28s cubic-bezier(0.22, 1, 0.36, 1), color 0.16s ease !important;
      }

      .mf-profile-menu-item.is-open .mf-menu-chevron {
        transform: rotate(90deg);
        color: #1d9e75 !important;
      }

      .mf-profile-dropdown {
        max-height: 0;
        opacity: 0;
        overflow: hidden;
        padding: 0 14px;
        background: #ffffff;
        transition: max-height 0.36s cubic-bezier(0.22, 1, 0.36, 1),
                    opacity 0.24s ease,
                    padding 0.24s ease;
      }

      .mf-profile-dropdown.is-open {
        max-height: 2200px;
        opacity: 1;
        padding: 4px 14px 16px;
      }

      .mf-profile-dropdown .profile-info-card {
        border: none !important;
        box-shadow: none !important;
        padding: 6px 2px !important;
        margin: 0 !important;
      }

      .mf-profile-logout-card {
        margin-top: 14px;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 15px 18px;
        background: #ffffff;
        border: 1px solid #eef2f0;
        border-radius: 18px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
        color: #4b5563;
        font-size: 0.94rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.16s ease, color 0.16s ease;
      }

      .mf-profile-logout-card:hover {
        background: #fef2f2;
        color: #dc2626;
      }

      .mf-profile-logout-card .material-symbols-outlined {
        font-size: 1.3rem;
        color: #6b7280;
      }

      .profile-tab-content {
        display: none;
        padding: 12px 14px 96px !important;
      }

      .profile-tab-content.is-mobile-open {
        display: block;
      }

      .mf-mobile-detail-header.is-open {
        display: flex;
        align-items: center;
        padding: 12px 14px;
        background: #ffffff;
        border-bottom: 1px solid #eef2f0;
        position: sticky;
        top: 0;
        z-index: 20;
      }

      .mf-mobile-detail-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        background: transparent;
        color: #1f2937;
        font-size: 1rem;
        font-weight: 700;
        padding: 4px 2px;
        cursor: pointer;
      }
    }
  </style>
</head>

<body data-active="05">
  <div class="wrapper d-flex align-items-stretch">

    <div id="sidebar-container"><?php include '../01_Includes/users-sidebar-fixed.php'; ?></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content">
        <div class="content-body">
          <?php include '../04_User/User-Profiles.php' ?>
        </div>
      </div>
    </div>

  </div>

  <script src="../07_Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

  <script>
    function activateProfileTab(target) {
      document.querySelectorAll(".tab-btn").forEach((b) => b.classList.toggle("active", b.dataset.tab === target));
      document.querySelectorAll(".tab-panel").forEach((p) => p.classList.toggle("active", p.id === "tab-" + target));
    }

    document.querySelectorAll(".tab-btn").forEach((btn) => {
      btn.addEventListener("click", () => {
        activateProfileTab(btn.dataset.tab);
      });
    });

    // Mobile Profile Smooth Accordion Dropdowns
    const tabKeys = ["edit", "settings", "help", "about"];

    function syncProfilePanelsForViewport() {
      const isMobile = window.innerWidth <= 767.98;
      tabKeys.forEach((key) => {
        const panel = document.getElementById("tab-" + key);
        const drop = document.getElementById("mfDrop-" + key);
        if (!panel || !drop) return;

        if (isMobile) {
          while (panel.firstChild) {
            drop.appendChild(panel.firstChild);
          }
        } else {
          while (drop.firstChild) {
            panel.appendChild(drop.firstChild);
          }
        }
      });
    }

    syncProfilePanelsForViewport();
    window.addEventListener("resize", syncProfilePanelsForViewport);

    function toggleMobileDropdown(targetKey, forceOpen) {
      document.querySelectorAll(".mf-profile-menu-item[data-mobile-tab]").forEach((btn) => {
        const key = btn.getAttribute("data-mobile-tab");
        const drop = document.getElementById("mfDrop-" + key);
        if (!drop) return;

        const shouldOpen = key === targetKey ? (forceOpen !== undefined ? forceOpen : !drop.classList.contains("is-open")) : false;
        drop.classList.toggle("is-open", shouldOpen);
        btn.classList.toggle("is-open", shouldOpen);
        btn.setAttribute("aria-expanded", String(shouldOpen));
      });
    }

    document.querySelectorAll("[data-mobile-tab]").forEach((btn) => {
      btn.addEventListener("click", () => {
        const target = btn.getAttribute("data-mobile-tab");
        const isAvatarBtn = btn.classList.contains("mf-mobile-avatar-edit");
        toggleMobileDropdown(target, isAvatarBtn ? true : undefined);
        if (isAvatarBtn) {
          const targetMenuBtn = document.querySelector(`.mf-profile-menu-item[data-mobile-tab="${target}"]`);
          if (targetMenuBtn) {
            setTimeout(() => targetMenuBtn.scrollIntoView({ behavior: "smooth", block: "start" }), 120);
          }
        }
      });
    });

    // Auto-dismiss alerts
    document.querySelectorAll('.alert').forEach(function(alert) {
      setTimeout(function() {
        new bootstrap.Alert(alert).close();
      }, 4000);
    });
  </script>

  <script>
    const savedProvinceName = "<?= htmlspecialchars($_SESSION['Province_Name'] ?? '') ?>";
    const savedCityName = "<?= htmlspecialchars($_SESSION['City_Name'] ?? '') ?>";
    const savedBarangayName = "<?= htmlspecialchars($_SESSION['Barangay_Name'] ?? '') ?>";

    fetch('../02_Actions/01_Authentication-CRUD/get_address.php?type=province')
      .then(r => r.json())
      .then(data => {
        const sel = document.getElementById('Province_ID');
        data.forEach(p => {
          const opt = document.createElement('option');
          opt.value = p.Province_ID;
          opt.textContent = p.Province_Name;
          if (p.Province_Name === savedProvinceName) {
            opt.selected = true;
            loadCities(p.Province_ID);
          }
          sel.appendChild(opt);
        });
      });

    function loadCities(provinceId) {
      const citySelect = document.getElementById('City_ID');
      const brgySelect = document.getElementById('Barangay_ID');
      citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
      brgySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
      fetch(`../02_Actions/01_Authentication-CRUD/get_address.php?type=city&province_id=${provinceId}`)
        .then(r => r.json())
        .then(data => {
          data.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c.City_ID;
            opt.textContent = c.City_Name;
            if (c.City_Name === savedCityName) {
              opt.selected = true;
              loadBarangays(c.City_ID);
            }
            citySelect.appendChild(opt);
          });
        });
    }

    function loadBarangays(cityId) {
      const brgySelect = document.getElementById('Barangay_ID');
      brgySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
      fetch(`../02_Actions/01_Authentication-CRUD/get_address.php?type=barangay&city_id=${cityId}`)
        .then(r => r.json())
        .then(data => {
          data.forEach(b => {
            const opt = document.createElement('option');
            opt.value = b.Barangay_ID;
            opt.textContent = b.Barangay_Name;
            if (b.Barangay_Name === savedBarangayName) opt.selected = true;
            brgySelect.appendChild(opt);
          });
        });
    }

    document.getElementById('Province_ID').addEventListener('change', function() {
      loadCities(this.value);
    });
    document.getElementById('City_ID').addEventListener('change', function() {
      loadBarangays(this.value);
    });
  </script>

  <script>
    function togglePw(inputId, showId, hideId) {
      var input = document.getElementById(inputId);
      var isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      document.getElementById(showId).style.display = isHidden ? 'none' : 'block';
      document.getElementById(hideId).style.display = isHidden ? 'block' : 'none';
    }

    function checkMatch() {
      var p1 = document.getElementById('pw1').value;
      var p2 = document.getElementById('pw2').value;
      var msg = document.getElementById('match-msg');
      if (!p2) {
        msg.textContent = '';
        return;
      }
      if (p1 === p2) {
        msg.textContent = 'Passwords match';
        msg.style.color = 'green';
      } else {
        msg.textContent = 'Passwords do not match';
        msg.style.color = 'red';
      }
    }
  </script>
</body>

</html>