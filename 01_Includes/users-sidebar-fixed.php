<?php
// 01_Includes/users-sidebar-fixed.php
// Server-side rendered user sidebar (same design as 01_users-sidebar.php, zero fetch flicker)
$currentFile = basename($_SERVER['PHP_SELF'] ?? '');
$currentPrefix = explode('_', $currentFile)[0] ?? '';
$currentPrefix = explode('.', $currentPrefix)[0] ?? $currentPrefix;

$navItems = [
    ['prefix' => '01', 'href' => '../04_User/01_Home.php', 'icon' => 'home', 'label' => 'Home'],
    ['prefix' => '02', 'href' => '../04_User/02_ScanRX.php', 'icon' => 'document_scanner', 'label' => 'Scan RX'],
    ['prefix' => '04', 'href' => '../04_User/04_Pharmacies.php', 'icon' => 'local_hospital', 'label' => 'Pharmacies'],
    ['prefix' => '05', 'href' => '../04_User/05_Profile.php', 'icon' => 'person', 'label' => 'Profile'],
];
?>
<style>
  .mf-mobile-bottom-nav {
    display: none;
  }

  @media (min-width: 769px) {
    #topbar-container {
      min-height: 64px;
    }
  }

  @media (max-width: 768px) {
    #sidebar-container {
      width: 0 !important;
      min-width: 0 !important;
      flex: 0 0 0 !important;
      overflow: visible !important;
    }

    #sidebar:not(.active) {
      position: fixed !important;
      left: -280px !important;
      top: 0 !important;
      visibility: hidden !important;
      pointer-events: none !important;
      transition: none !important;
    }
  }
</style>
<link rel="stylesheet" href="../07_Assets/node_modules/material-symbols/outlined.css" />
<link rel="stylesheet" href="../07_Assets/css/04_Includes CSS/sidebar.css" media="(min-width: 769px)" />

<style>

  @media (max-width: 768px) {
    /* Hide topbar on mobile view for a cleaner native-app layout */
    #topbar-container {
      display: none !important;
    }

    /* Hide burger button visually on mobile while keeping it in DOM & functional */
    #sidebarToggle {
      position: absolute !important;
      width: 1px !important;
      height: 1px !important;
      padding: 0 !important;
      margin: -1px !important;
      overflow: hidden !important;
      clip: rect(0, 0, 0, 0) !important;
      white-space: nowrap !important;
      border: 0 !important;
    }

    /* Hide duplicate floating bot button on mobile since AI Chatbot is in bottom nav */
    .floating-btns,
    #floatBtn {
      display: none !important;
    }

    #content {
      padding-bottom: 84px;
    }

    .guest-map-view.embedded-mode .guest-map-sheet {
      bottom: 0 !important;
    }

    .mf-mobile-bottom-nav {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      align-items: end;
      position: fixed;
      left: 0;
      right: 0;
      bottom: 0;
      height: 84px;
      padding: 8px 8px calc(24px + env(safe-area-inset-bottom, 0px));
      background: #ffffff;
      border-top: 1px solid #e5ece9;
      box-shadow: 0 -6px 24px rgba(15, 23, 42, 0.08);
      z-index: 1200;
      font-family: "Plus Jakarta Sans", "Poppins", sans-serif;
    }

    .mf-bnav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 2px;
      text-decoration: none;
      color: #4b5563;
      font-size: 0.7rem;
      font-weight: 600;
      background: transparent;
      border: none;
      padding: 2px 0;
      cursor: pointer;
      transition: color 0.18s ease;
    }

    .mf-bnav-item .material-symbols-outlined {
      font-size: 1.45rem;
      line-height: 1;
    }

    .mf-bnav-item.active {
      color: #1d9e75;
    }

    .mf-bnav-center {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-end;
      text-decoration: none;
      color: #4b5563;
      font-size: 0.7rem;
      font-weight: 600;
      padding-bottom: 2px;
    }

    .mf-bnav-center.active {
      color: #1d9e75;
    }

    .mf-bnav-fab {
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background: #475569;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-top: -26px;
      margin-bottom: 4px;
      box-shadow: 0 6px 18px rgba(15, 23, 42, 0.24);
      border: 4px solid #ffffff;
      transition: background 0.18s ease, transform 0.18s ease;
    }

    .mf-bnav-center.active .mf-bnav-fab {
      background: #1d9e75;
    }

    .mf-bnav-fab .material-symbols-outlined {
      font-size: 1.55rem;
    }
  }
</style>

<nav id="sidebar">
  <div class="close-btn">
    <a href="#" class="btn btn-sm" id="closeSidebar"
      onclick="document.getElementById('sidebar').classList.toggle('hidden'); return false;">
      <span class="material-symbols-outlined">close</span>
    </a>
  </div>

  <div class="sidebar-brand">
    <img src="../07_Assets/images/logo.png" alt="MediFind Logo" width="80">
    <div class="hero-title">Medi<span>Find</span></div>
    <div class="hero-subtitle">Malaybalay Medicine Availability Checker</div>
  </div>

  <hr class="sidebar-divider" />

  <ul class="list-unstyled sidebar-nav">
    <?php foreach ($navItems as $item): ?>
      <li class="<?= $currentPrefix === $item['prefix'] ? 'active' : '' ?>">
        <a href="<?= htmlspecialchars($item['href']) ?>">
          <span class="material-symbols-outlined"><?= htmlspecialchars($item['icon']) ?></span>
          <span class="sidebar-label"><?= htmlspecialchars($item['label']) ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>

  <div class="sidebar-logout">
    <hr class="sidebar-divider" />
    <ul class="list-unstyled sidebar-nav">
      <li>
        <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
          <span class="material-symbols-outlined" style="color: #87a199; font-size: 1.5rem">
            logout
          </span>
          <span class="sidebar-label">Log Out</span>
        </a>
      </li>
    </ul>
  </div>
</nav>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden">
      <div class="modal-header" style="background: #1d9e75; border: none;">
        <h5 class="modal-title text-white" id="logoutModalLabel">
          <i class="fa fa-sign-out me-2"></i> Confirm Logout
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4">
        <div style="font-size: 3rem; color: #1d9e75; margin-bottom: 1rem">
          <i class="fa fa-circle-question"></i>
        </div>
        <h6 style="font-weight: 600; color: #374151">
          Are you sure you want to log out?
        </h6>
        <p style="font-size: 0.85rem; color: #9ca3af">
          You will be redirected to the login page.
        </p>
      </div>
      <div class="modal-footer justify-content-center" style="border: none">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
          Cancel
        </button>
        <a href="../03_Authentication/logout.php" class="btn px-4 text-white" style="background: #1d9e75;">
          Yes, Log Out
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Mobile Bottom Navigation Bar -->
<nav class="mf-mobile-bottom-nav" aria-label="Mobile navigation">
  <a href="../04_User/01_Home.php" class="mf-bnav-item <?= $currentPrefix === '01' ? 'active' : '' ?>">
    <span class="material-symbols-outlined">home</span>
    <span>Home</span>
  </a>

  <button type="button" class="mf-bnav-item" id="mfMobileChatBtn">
    <span class="material-symbols-outlined">smart_toy</span>
    <span>AI Chatbot</span>
  </button>

  <a href="../04_User/02_ScanRX.php" class="mf-bnav-center <?= $currentPrefix === '02' ? 'active' : '' ?>">
    <div class="mf-bnav-fab">
      <span class="material-symbols-outlined">center_focus_weak</span>
    </div>
    <span>Scan Rx</span>
  </a>

  <a href="../04_User/04_Pharmacies.php" class="mf-bnav-item <?= $currentPrefix === '04' ? 'active' : '' ?>">
    <span class="material-symbols-outlined">local_hospital</span>
    <span>Pharmacies</span>
  </a>

  <a href="../04_User/05_Profile.php" class="mf-bnav-item <?= $currentPrefix === '05' ? 'active' : '' ?>">
    <span class="material-symbols-outlined">account_circle</span>
    <span>Profile</span>
  </a>
</nav>

<script>
  (function () {
    const mobileNav = document.querySelector('.mf-mobile-bottom-nav');
    if (mobileNav && mobileNav.parentElement !== document.body) {
      document.body.appendChild(mobileNav);
    }

    const logoutModalEl = document.getElementById('logoutModal');
    if (logoutModalEl && logoutModalEl.parentElement !== document.body) {
      document.body.appendChild(logoutModalEl);
    }

    const mobileChatBtn = document.getElementById('mfMobileChatBtn');
    if (mobileChatBtn) {
      mobileChatBtn.addEventListener('click', function () {
        window.location.href = '../04_User/06_AIChatbot.php';
      });
    }
    window.toggleUserSidebar = function () {
      const btn = document.getElementById('sidebarToggle');
      if (btn) btn.click();
    };
  })();
</script>

