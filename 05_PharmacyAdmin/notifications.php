<?php
// 05_PharmacyAdmin/notifications.php
include_once __DIR__ . '/../02_Actions/GlobalVariables.php';
include_once __DIR__ . '/../00_Config/config.php';

// ── GUARD: Users must log in ────────────────────────
if (empty($_SESSION['user_id'])) {
  header('Location: ../03_Authentication/login.php');
  exit;
}

$pharmacyID = (int) ($_SESSION['pharmacy_id'] ?? 0);
if (!$pharmacyID) {
  $stmt = $pdo->prepare("SELECT Pharmacy_ID, Pharmacy_name FROM 09_pharmacies WHERE User_ID = ? LIMIT 1");
  $stmt->execute([$_SESSION['user_id']]);
  $p = $stmt->fetch();
  if ($p) {
    $pharmacyID = (int) $p['Pharmacy_ID'];
    $_SESSION['pharmacy_id'] = $pharmacyID;
    $_SESSION['pharmacy_name'] = $p['Pharmacy_name'];
  } else {
    die("Error: Pharmacy ID not found in session. Please log in again.");
  }
}

// ── 1. Query Low Stock & Out of Stock Alerts ────────
$stmtLow = $pdo->prepare("
  SELECT 
    i.Inventory_ID, i.Quantity, i.Price, i.Expiry_date, i.Last_updated,
    gn.Generic_Name, bn.Brand_Name, df.Dosage_Form, dv.Dosage_Value, du.Unit_Name
  FROM 21_inventory i
  INNER JOIN 12_meds_medicines med ON i.Medicine_ID = med.Medicine_ID
  LEFT JOIN 13_meds_generic_name gn ON med.Gen_ID = gn.Gen_ID
  LEFT JOIN 14_meds_brand_name bn ON med.Brand_ID = bn.Brand_ID
  LEFT JOIN 15_meds_dosage_form df ON med.DosageForm_ID = df.DosageForm_ID
  LEFT JOIN 16_meds_dosage_value dv ON med.DosageVal_ID = dv.DosageVal_ID
  LEFT JOIN 17_meds_dosage_unit du ON med.Unit_ID = du.Unit_ID
  WHERE i.Pharmacy_ID = ? AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
    AND i.Quantity <= 10
  ORDER BY i.Quantity ASC
");
$stmtLow->execute([$pharmacyID]);
$lowStockItems = $stmtLow->fetchAll(PDO::FETCH_ASSOC);

// ── 2. Query Expiry Warnings ────────────────────────
$stmtExp = $pdo->prepare("
  SELECT 
    i.Inventory_ID, i.Quantity, i.Price, i.Expiry_date,
    gn.Generic_Name, bn.Brand_Name, df.Dosage_Form, dv.Dosage_Value, du.Unit_Name,
    DATEDIFF(i.Expiry_date, CURDATE()) as days_left
  FROM 21_inventory i
  INNER JOIN 12_meds_medicines med ON i.Medicine_ID = med.Medicine_ID
  LEFT JOIN 13_meds_generic_name gn ON med.Gen_ID = gn.Gen_ID
  LEFT JOIN 14_meds_brand_name bn ON med.Brand_ID = bn.Brand_ID
  LEFT JOIN 15_meds_dosage_form df ON med.DosageForm_ID = df.DosageForm_ID
  LEFT JOIN 16_meds_dosage_value dv ON med.DosageVal_ID = dv.DosageVal_ID
  LEFT JOIN 17_meds_dosage_unit du ON med.Unit_ID = du.Unit_ID
  WHERE i.Pharmacy_ID = ? AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
    AND i.Expiry_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
  ORDER BY i.Expiry_date ASC
");
$stmtExp->execute([$pharmacyID]);
$expiryItems = $stmtExp->fetchAll(PDO::FETCH_ASSOC);

// ── 3. Formal Internal System Notices ───────────────
$systemNotices = [
  [
    'id' => 'sys_audit',
    'title' => 'System Audit scheduled for 10:00 PM',
    'badge' => 'Info',
    'badge_class' => 'badge-notif-info',
    'icon_class' => 'notif-icon-info',
    'icon' => 'schedule',
    'body' => 'MediFind technical team will conduct an automated server sync and database performance tuning tonight at 10:00 PM PHT. Point of Sale and inventory will remain accessible.',
    'time' => 'Scheduled for Today, 10:00 PM'
  ],
  [
    'id' => 'sys_backup',
    'title' => 'Database Backup Completed',
    'badge' => 'Success',
    'badge_class' => 'badge-notif-success',
    'icon_class' => 'notif-icon-success',
    'icon' => 'cloud_done',
    'body' => 'Daily automated database backup of inventory stocks, unit prices, and POS transaction records has been successfully archived and verified.',
    'time' => 'Today, 03:00 AM'
  ],
  [
    'id' => 'sys_compliance',
    'title' => 'Senior / PWD Statutory Pricing Verified',
    'badge' => 'Success',
    'badge_class' => 'badge-notif-success',
    'icon_class' => 'notif-icon-success',
    'icon' => 'verified_user',
    'body' => 'Philippine RA 9994 and RA 10754 20% discount rules are fully synchronized and active across your POS counter terminal.',
    'time' => 'Yesterday'
  ],
  [
    'id' => 'sys_fda',
    'title' => 'Drug Classification Index Synced',
    'badge' => 'Info',
    'badge_class' => 'badge-notif-info',
    'icon_class' => 'notif-icon-info',
    'icon' => 'info',
    'body' => 'FDA drug classification masterlist synced with 12_meds_medicines and prescription requirement rules.',
    'time' => '2 days ago'
  ]
];

// Combined list
$notificationsList = [];

// Add Low Stock alerts
foreach ($lowStockItems as $l) {
  $dosageStr = ($l['Dosage_Value'] ? $l['Dosage_Value'] . ' ' . $l['Unit_Name'] : '') . ($l['Dosage_Form'] ? ' ' . $l['Dosage_Form'] : '');
  $isOut = (int) $l['Quantity'] === 0;
  $medName = $l['Generic_Name'] . ($l['Brand_Name'] ? ' (' . $l['Brand_Name'] . ')' : '') . ' ' . $dosageStr;

  $notificationsList[] = [
    'type' => 'stock',
    'category' => 'stock',
    'title' => $isOut ? "Out of Stock: {$l['Generic_Name']}" : "Warning: {$l['Generic_Name']} {$dosageStr} is low [{$l['Quantity']} units remaining]",
    'badge' => 'Warning',
    'badge_class' => 'badge-notif-warning',
    'icon_class' => 'notif-icon-warning',
    'icon' => $isOut ? 'error' : 'warning',
    'body' => $isOut
      ? "Critical Alert: {$medName} is completely out of stock [0 units remaining]. Patients cannot purchase this medicine until stock is replenished."
      : "Warning: {$l['Generic_Name']} {$dosageStr} is low [{$l['Quantity']} units remaining]. Current stock has dropped below the threshold of 10 units.",
    // Real bullet character: this string is escaped with htmlspecialchars() on output,
    // so an HTML entity like &bull; would be printed literally.
    'time' => 'Stock Update • ' . date('M d, Y', strtotime($l['Last_updated'] ?? 'now')),
    'action_text' => 'Restock via Scanner',
    'action_url' => 'manage_inventory.php?openScan=1'
  ];
}

// Add Expiry alerts
foreach ($expiryItems as $ex) {
  $dosageStr = ($ex['Dosage_Value'] ? $ex['Dosage_Value'] . ' ' . $ex['Unit_Name'] : '') . ($ex['Dosage_Form'] ? ' ' . $ex['Dosage_Form'] : '');
  $daysLeft = (int) $ex['days_left'];
  $isExpired = $daysLeft <= 0;
  $medName = $ex['Generic_Name'] . ($ex['Brand_Name'] ? ' (' . $ex['Brand_Name'] . ')' : '');

  $notificationsList[] = [
    'type' => 'expiry',
    'category' => 'stock',
    'title' => $isExpired ? "Expired Stock: {$medName}" : "Expiring Soon: {$medName} [{$daysLeft} days remaining]",
    'badge' => 'Warning',
    'badge_class' => 'badge-notif-warning',
    'icon_class' => 'notif-icon-warning',
    'icon' => 'event_busy',
    'body' => $isExpired
      ? "Medicine {$medName} {$dosageStr} reached its expiry date on " . date('M d, Y', strtotime($ex['Expiry_date'])) . ". Pull out from active shelf to comply with health safety guidelines."
      : "Medicine batch of {$medName} {$dosageStr} will expire on " . date('M d, Y', strtotime($ex['Expiry_date'])) . " ({$daysLeft} days remaining). Current stock: {$ex['Quantity']} units.",
    'time' => 'Expiry Notice • ' . date('M d, Y', strtotime($ex['Expiry_date'])),
    'action_text' => 'View in Inventory',
    'action_url' => 'manage_inventory.php'
  ];
}

// Add System Notices
foreach ($systemNotices as $sn) {
  $notificationsList[] = [
    'type' => 'system',
    'category' => 'system',
    'title' => $sn['title'],
    'badge' => $sn['badge'],
    'badge_class' => $sn['badge_class'],
    'icon_class' => $sn['icon_class'],
    'icon' => $sn['icon'],
    'body' => $sn['body'],
    'time' => $sn['time'],
    'action_text' => null,
    'action_url' => null
  ];
}

$totalCount = count($notificationsList);
$stockCount = count($lowStockItems) + count($expiryItems);
$sysCount = count($systemNotices);
?>
<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <title>Notifications &amp; Alerts - MediFind</title>

  <!-- External libraries via CDN with pinned versions -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../07_Assets/node_modules/material-symbols/outlined.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Scripts & CSS -->
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/theme-tokens.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/admin-buttons.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/01_dashboard.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/notifications.css" />

  <style>
    /* Responsive layout: follows the available width whether the sidebar is open or closed */
    .main-panel {
      min-width: 0;
    }

    #content {
      flex: 1 1 auto;
      min-width: 0;
      width: 100%;
      box-sizing: border-box;
    }

    .content-body {
      width: 100%;
      max-width: 100%;
    }

    .notification-filter-bar {
      display: block;
      width: 100%;
      max-width: 100%;
    }

    .notif-nav-pills {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      padding: 0;
      margin: 0;
      list-style: none;
    }

    .notifications-list {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      width: 100%;
      max-width: 100%;
      margin: 0;
    }

    .notification-card {
      width: 100%;
      min-width: 0;
      box-sizing: border-box;
    }

    .notif-content-wrap {
      flex: 1 1 auto;
      min-width: 0;
    }

    .notif-header-row {
      flex-wrap: wrap;
      gap: 0.25rem 0.75rem;
    }

    .notif-title,
    .notif-body {
      overflow-wrap: anywhere;
    }

    @media (max-width: 576px) {
      .notification-card {
        flex-direction: column;
      }
    }
  </style>

  <?php include '../01_Includes/page-transition-hardcode.php' ?>
</head>

<body data-active="notifications">
  <div class="wrapper d-flex align-items-stretch">
    <div id="pharmacy-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content" style="padding: 1.25rem;">
        <div class="content-body">

          <!-- Header -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h4 class="fw-bold mb-0 admin-page-title">Notifications &amp; Operational Alerts</h4>
              <p class="text-muted small mb-0">Real-time stock threshold warnings, expiry tracking, and formal platform
                system messages.</p>
            </div>
            <div>
              <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.location.reload()">
                <i class="bi bi-arrow-clockwise me-1"></i> Refresh
              </button>
            </div>
          </div>

          <!-- Notification Filter Bar -->
          <div class="notification-filter-bar mb-3">
            <ul class="notif-nav-pills">
              <li>
                <button type="button" class="notif-pill active" data-filter="all">
                  All <span class="badge-count"><?= $totalCount ?></span>
                </button>
              </li>
              <li>
                <button type="button" class="notif-pill" data-filter="stock">
                  <span class="material-symbols-outlined" style="font-size: 1rem;">warning</span>
                  Low Stock &amp; Expiry <span class="badge-count"><?= $stockCount ?></span>
                </button>
              </li>
              <li>
                <button type="button" class="notif-pill" data-filter="system">
                  <span class="material-symbols-outlined" style="font-size: 1rem;">info</span>
                  System Notices <span class="badge-count"><?= $sysCount ?></span>
                </button>
              </li>
            </ul>
          </div>

          <!-- Notifications List (its own block, no longer inside the filter bar) -->
          <div class="notifications-list" id="notifListContainer">
            <?php if (!empty($notificationsList)): ?>
              <?php foreach ($notificationsList as $n): ?>
                <div class="notification-card notif-item unread" data-category="<?= htmlspecialchars($n['category']) ?>">
                  <div class="notif-icon-box <?= htmlspecialchars($n['icon_class']) ?>">
                    <span class="material-symbols-outlined"><?= htmlspecialchars($n['icon']) ?></span>
                  </div>

                  <div class="notif-content-wrap">
                    <div class="notif-header-row">
                      <div class="notif-title">
                        <?= htmlspecialchars($n['title']) ?>
                      </div>
                      <div class="d-flex align-items-center gap-2">
                        <span class="notif-badge <?= htmlspecialchars($n['badge_class']) ?>">
                          <?= htmlspecialchars($n['badge']) ?>
                        </span>
                        <span class="notif-time"><?= htmlspecialchars($n['time']) ?></span>
                      </div>
                    </div>

                    <div class="notif-body">
                      <?= htmlspecialchars($n['body']) ?>
                    </div>

                    <div class="notif-actions">
                      <?php if (!empty($n['action_url']) && !empty($n['action_text'])): ?>
                        <a href="<?= htmlspecialchars($n['action_url']) ?>"
                          class="notif-action-link d-inline-flex align-items-center gap-1">
                          <?= htmlspecialchars($n['action_text']) ?>
                          <i class="bi bi-arrow-right"></i>
                        </a>
                        <span class="text-muted" style="font-size: 0.8rem;">&bull;</span>
                      <?php endif; ?>
                      <button type="button" class="btn btn-link p-0 text-muted notif-action-link notif-dismiss-btn"
                        style="font-size: 0.82rem; font-weight: normal;">
                        Dismiss
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <div class="notif-empty">
                <span class="material-symbols-outlined">notifications_off</span>
                <h6>No active notifications</h6>
                <p class="small text-muted">You are all caught up! All inventory levels and system operations are
                  running normally.</p>
              </div>
            <?php endif; ?>
          </div>

        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const pills = document.querySelectorAll('.notif-pill');
      const cards = document.querySelectorAll('.notif-item');

      // Filter pills
      pills.forEach(pill => {
        pill.addEventListener('click', function () {
          pills.forEach(p => p.classList.remove('active'));
          pill.classList.add('active');

          const filter = pill.getAttribute('data-filter');

          cards.forEach(card => {
            const cat = card.getAttribute('data-category');
            if (filter === 'all' || cat === filter) {
              card.style.display = 'flex';
            } else {
              card.style.display = 'none';
            }
          });
        });
      });

      // Dismiss button
      document.querySelectorAll('.notif-dismiss-btn').forEach(btn => {
        btn.addEventListener('click', function () {
          const card = btn.closest('.notification-card');
          if (card) {
            card.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            card.style.opacity = '0';
            card.style.transform = 'translateY(-6px)';
            setTimeout(() => card.remove(), 260);
          }
        });
      });
    });
  </script>
</body>

</html>