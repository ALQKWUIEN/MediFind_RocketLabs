<?php
include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Topbar</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="../07_Assets/css/04_Includes CSS/topbar.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
  <div class="topbar">
    <div class="d-flex align-items-center gap-2">
      <button id="sidebarToggle" class="topbar-hamburger" type="button">
        <i class="fas fa-bars"></i>
      </button>
      <button id="posFocusToggleBtn" class="pos-focus-btn" type="button" title="Toggle Full-Screen POS">
        <span class="material-symbols-outlined" id="posFocusIcon" style="font-size:1.1rem;">fullscreen</span>
        <span id="posFocusLabel">Full Screen</span>
      </button>
    </div>

    <div class="topbar-right">
      <!-- Date -->
      <div class="topbar-date">
        <i class="fas fa-calendar-alt" style="color: #146f50"></i>
        <span id="topbar-date-text"></span>
      </div>

      <!-- Notifications -->
      <div class="dropdown">
        <button class="topbar-icon-btn" type="button" id="notifDropdown"
          data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fas fa-bell"></i>
          <span class="topbar-badge" id="notifBadge">0</span>
        </button>
        <div class="dropdown-menu dropdown-menu-end notif-dropdown" aria-labelledby="notifDropdown">
          <div class="notif-header">
            <p class="notif-header-title">
              <i class="fas fa-bell me-2" style="color: #146f50"></i>Notifications
            </p>
            <button class="notif-mark-all" type="button"
              onclick="markAllRead('notif'); event.stopPropagation();">
              Mark all read
            </button>
          </div>
          <div class="notif-list" id="notifList"></div>
          <div class="notif-footer"><a href="#">View all notifications</a></div>
        </div>
      </div>

      <!-- User Info -->
      <div class="topbar-user-info">
        <p class="topbar-user-name"><?= htmlspecialchars($_SESSION['username']) ?></p>
        <p class="topbar-user-role"><?= htmlspecialchars($_SESSION['role_label']) ?></p>
      </div>

      <!-- Avatar -->
      <div class="dropdown">
        <button class="topbar-avatar-btn" type="button" data-bs-toggle="dropdown">
          <img src="../07_Assets/images/person.jpg" alt="User" class="topbar-avatar-img" />
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="/admin/profile.html">
            <i class="fas fa-user me-2"></i>Profile</a></li>
          <li><hr class="dropdown-divider" /></li>
          <li><a class="dropdown-item text-success" href="../index.php">
            <i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>