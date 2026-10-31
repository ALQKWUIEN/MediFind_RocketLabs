<?php
// Prevent back button after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Global Variable and DB Connection
include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

require '../02_Actions/04_System-Admin-CRUD/select-count.php';
require '../02_Actions/04_System-Admin-CRUD/display-pharmacy.php';

// ── GUARD: Users must log in ────────────────────────
if (empty($_SESSION['user_id'])) {
  header('Location: ../03_Authentication/login.php');
  exit;
}

// Fetch all pharmacies into array for multiple views (Table, Cards, Map)
$pharmaciesList = ($stmt && $stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
$totalPharmacies = count($pharmaciesList);

// Default coordinates for Malaybalay retail pharmacies & barangays
$knownCoords = [
  'Rose Pharmacy' => [8.1548, 125.1281],
  'Generika Drugstore' => [8.1563, 125.1285],
  'Mercury Drug' => [8.1547, 125.1279],
  'Watsons Pharmacy' => [8.1547, 125.1275],
  'Rojon Pharmacy' => [8.1558, 125.1269],
  'TGP The Generics Pharmacy' => [8.1567, 125.1263],
];

$brgyCoords = [
  'Poblacion' => [8.1560, 125.1278],
  'Casisang' => [8.1415, 125.1290],
  'Sumpong' => [8.1695, 125.1315],
  'Aglayan' => [8.0825, 125.1550],
  'Impalambong' => [8.1630, 125.1210],
  'Kalasungay' => [8.1920, 125.1050],
  'San Jose' => [8.1400, 125.1050],
  'Dalwangan' => [8.2350, 125.0750],
  'Canayan' => [8.1850, 125.1850],
  'Bangcud' => [8.0450, 125.1450],
  'Laguitas' => [8.1810, 125.1520],
  'Kibalabag' => [8.2520, 125.1420],
  'Kulaman' => [8.2800, 125.1900],
  'Managok' => [8.0120, 125.1850],
  'Mapayag' => [8.0650, 125.1800],
  'St. Peter' => [8.2100, 125.2600],
  'Santo Niño' => [8.1150, 125.1600],
  'Simaya' => [8.0300, 125.1800],
  'Sinanglanan' => [8.0750, 125.1650],
  'Violeta' => [8.0950, 125.1450]
];

// Build Map Markers for approved pharmacies
$mapMarkers = [];
foreach ($pharmaciesList as $ph) {
  $status = trim($ph['Approval_Status'] ?? '');
  if ($status === 'Approved') {
    $id = (int) $ph['Pharmacy_ID'];
    $name = trim($ph['Pharmacy_name'] ?? '');
    $displayName = $name !== '' ? $name : 'Approved Pharmacy #' . $id;
    $brgy = trim($ph['Barangay_Name'] ?? '');
    $displayBrgy = $brgy !== '' ? $brgy : 'Poblacion';
    $address = trim($ph['Full_Address'] ?? '');
    $contact = trim($ph['Phone'] ?? '');
    $owner = trim($ph['Owner_name'] ?? '');

    // Assign coordinate
    if (isset($knownCoords[$name])) {
      $lat = $knownCoords[$name][0];
      $lng = $knownCoords[$name][1];
    } elseif (isset($brgyCoords[$displayBrgy])) {
      $lat = $brgyCoords[$displayBrgy][0] + ((($id % 5) - 2) * 0.0015);
      $lng = $brgyCoords[$displayBrgy][1] + ((($id % 7) - 3) * 0.0015);
    } else {
      $lat = 8.1560 + ((($id % 5) - 2) * 0.0018);
      $lng = 125.1278 + ((($id % 7) - 3) * 0.0018);
    }

    $pic = !empty($ph['Pic_URL']) ? $ph['Pic_URL'] : '../07_Assets/images/pharmacies/RosePharmacy.png';

    $mapMarkers[] = [
      'id' => $id,
      'name' => $displayName,
      'owner' => $owner !== '' ? $owner : 'Not provided',
      'contact' => $contact !== '' ? $contact : 'Not provided',
      'address' => $address !== '' ? $address : $displayBrgy . ', Malaybalay City',
      'barangay' => $displayBrgy,
      'status' => 'Approved',
      'lat' => $lat,
      'lng' => $lng,
      'pic' => $pic
    ];
  }
}
?>

<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <title>Pharmacy Center - MediFind Malaybalay</title>

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Core Libraries -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Leaflet Map CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <!-- User-Side Design System & Theme Tokens -->
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/theme-tokens.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/admin-buttons.css" />
  <link rel="stylesheet" href="../07_Assets/css/03_SystemAdminCSS/dashboard.css" />
  <link rel="stylesheet" href="../07_Assets/css/03_SystemAdminCSS/pharmacies.css" />

  <!-- Navigation Scripts -->
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

  <!-- Mock Pharmacy Stock Module for Recommendations (Frontend Only) -->
  <script src="../07_Assets/js/mock/pharmacy_stock.js"></script>

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>
</head>

<body data-active="03" class="lgu-dashboard-page">
  <div class="wrapper d-flex align-items-stretch">
    <!-- Dynamic System Sidebar -->
    <div id="system-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <!-- Dynamic Topbar -->
      <div id="topbar-container"></div>

      <div class="content flex-grow-1">
        <div class="pharmacies-admin-container">

          <!-- ═══════════════════════════════════════════════════════════ -->
          <!-- A) TOP SUMMARY CARDS: LGU OVERVIEW & METRICS                -->
          <!-- ═══════════════════════════════════════════════════════════ -->
          <div class="stat-card-row mb-4">
            <div class="row g-3 g-xl-4 row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-5">

              <!-- 1. Total Pharmacies -->
              <div class="col">
                <div class="pharmacy-stat-card">
                  <div>
                    <div class="pharmacy-stat-header">
                      <div class="pharmacy-stat-icon icon-teal">
                        <span class="material-symbols-outlined">local_pharmacy</span>
                      </div>
                      <span class="pharmacy-stat-label">Total</span>
                    </div>
                    <div class="pharmacy-stat-value"><?= number_format($totalPharmaciesCount ?? $totalPharmacies) ?></div>
                  </div>
                  <div class="pharmacy-stat-sub">
                    <i class="bi bi-buildings"></i> Registered pharmacies
                  </div>
                </div>
              </div>

              <!-- 2. Approved Pharmacies -->
              <div class="col">
                <div class="pharmacy-stat-card">
                  <div>
                    <div class="pharmacy-stat-header">
                      <div class="pharmacy-stat-icon icon-emerald">
                        <span class="material-symbols-outlined">check_circle</span>
                      </div>
                      <span class="pharmacy-stat-label">Approved</span>
                    </div>
                    <div class="pharmacy-stat-value" style="color: #166534;"><?= number_format($approvedCount ?? 0) ?></div>
                  </div>
                  <div class="pharmacy-stat-sub">
                    <i class="bi bi-shield-check"></i> Licensed & active
                  </div>
                </div>
              </div>

              <!-- 3. Pending Requests -->
              <div class="col">
                <div class="pharmacy-stat-card">
                  <div>
                    <div class="pharmacy-stat-header">
                      <div class="pharmacy-stat-icon icon-amber">
                        <span class="material-symbols-outlined">pending_actions</span>
                      </div>
                      <span class="pharmacy-stat-label">Pending</span>
                    </div>
                    <div class="pharmacy-stat-value" style="color: #b45309;"><?= number_format($pendingCount ?? 0) ?></div>
                  </div>
                  <div class="pharmacy-stat-sub">
                    <i class="bi bi-clock-history"></i> Awaiting LGU review
                  </div>
                </div>
              </div>

              <!-- 4. Rejected Requests -->
              <div class="col">
                <div class="pharmacy-stat-card">
                  <div>
                    <div class="pharmacy-stat-header">
                      <div class="pharmacy-stat-icon icon-rose">
                        <span class="material-symbols-outlined">cancel</span>
                      </div>
                      <span class="pharmacy-stat-label">Rejected</span>
                    </div>
                    <div class="pharmacy-stat-value" style="color: #be123c;"><?= number_format($rejectedCount ?? 0) ?></div>
                  </div>
                  <div class="pharmacy-stat-sub">
                    <i class="bi bi-x-circle"></i> Incomplete / denied
                  </div>
                </div>
              </div>

              <!-- 5. Barangays Covered -->
              <div class="col">
                <div class="pharmacy-stat-card">
                  <div>
                    <div class="pharmacy-stat-header">
                      <div class="pharmacy-stat-icon icon-blue">
                        <span class="material-symbols-outlined">location_city</span>
                      </div>
                      <span class="pharmacy-stat-label">Coverage</span>
                    </div>
                    <div class="pharmacy-stat-value" style="color: #0369a1;"><?= number_format($barangaysCoveredCount ?? 0) ?></div>
                  </div>
                  <div class="pharmacy-stat-sub">
                    <i class="bi bi-geo-alt"></i> Barangays covered
                  </div>
                </div>
              </div>

            </div>
          </div>

          <!-- ═══════════════════════════════════════════════════════════ -->
          <!-- TOOLBAR: SEARCH, FILTERS & VIEW MODE TOGGLE                  -->
          <!-- ═══════════════════════════════════════════════════════════ -->
          <div class="pharmacy-toolbar-card">
            <div class="pharmacy-toolbar-header">
              <div class="pharmacy-title-area">
                <h5>Pharmacy Center & Geographic Overview</h5>
                <p>Manage pharmacy licenses, view spatial coverage across Malaybalay City, and recommend medicines.</p>
              </div>

              <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Recommend Pharmacy Action Button (Feature B) -->
                <button type="button" class="btn-recommend-trigger" id="openRecommendModalBtn" data-bs-toggle="modal" data-bs-target="#recommendModal">
                  <span class="material-symbols-outlined" style="font-size: 19px;">recommend</span>
                  Recommend Pharmacy
                </button>

                <!-- View Switcher Toggle -->
                <div class="view-mode-toggle" role="group" aria-label="View Mode">
                  <button type="button" class="view-mode-btn active" id="btnViewTable" data-view="table">
                    <i class="bi bi-table"></i> Table
                  </button>
                  <button type="button" class="view-mode-btn" id="btnViewCards" data-view="cards">
                    <i class="bi bi-grid-fill"></i> Cards
                  </button>
                  <button type="button" class="view-mode-btn" id="btnViewMap" data-view="map">
                    <i class="bi bi-map-fill"></i> Map
                  </button>
                </div>
              </div>
            </div>

            <!-- Toolbar Controls Row -->
            <div class="toolbar-controls-grid">
              <!-- Search -->
              <div class="search-input-group">
                <i class="bi bi-search"></i>
                <input type="text" id="searchPharmacy" placeholder="Search by name, administrator, or address...">
              </div>

              <!-- Status Filter -->
              <select id="filterStatus" class="form-select filter-select">
                <option value="">All Status</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
                <option value="suspended">Suspended</option>
              </select>

              <!-- Barangay Filter (Filters Table, Cards, and Map Markers) -->
              <select id="filterBarangay" class="form-select filter-select">
                <option value="">All Barangays</option>
                <?php
                // Merge distinct barangays from DB and Malaybalay defaults
                $mergedBarangays = array_unique(array_filter(array_merge(
                  $allBarangaysList ?? [],
                  array_keys($brgyCoords)
                )));
                sort($mergedBarangays);
                foreach ($mergedBarangays as $bName):
                ?>
                  <option value="<?= htmlspecialchars($bName) ?>"><?= htmlspecialchars($bName) ?></option>
                <?php endforeach; ?>
              </select>

              <!-- Counter Badge -->
              <div class="ms-auto text-muted fw-semibold small d-flex align-items-center gap-1">
                <span>Showing</span>
                <span id="visibleCount" class="badge bg-secondary-subtle text-secondary-emphasis px-2 py-1 rounded-pill"><?= count($pharmaciesList) ?></span>
                <span>of <?= count($pharmaciesList) ?> pharmacies</span>
              </div>
            </div>
          </div>

          <!-- ═══════════════════════════════════════════════════════════ -->
          <!-- VIEW 1: MAP VIEW (LEAFLET GEO-LOCATIONS)                     -->
          <!-- ═══════════════════════════════════════════════════════════ -->
          <div id="mapViewSection" style="display: none;">
            <div class="map-header-bar">
              <div class="map-header-title">
                <span class="material-symbols-outlined" style="color: #1d9e75;">location_on</span>
                <span>Approved Pharmacies Map (Malaybalay City)</span>
                <span class="badge bg-success-subtle text-success border border-success-subtle ms-2 rounded-pill" id="mapMarkersCountBadge">
                  <?= count($mapMarkers) ?> locations
                </span>
              </div>
              <div class="text-muted small">
                <i class="bi bi-info-circle me-1"></i> Click any pin to view details and contact info
              </div>
            </div>
            <div id="pharmaciesMap"></div>
          </div>

          <!-- ═══════════════════════════════════════════════════════════ -->
          <!-- VIEW 2: CARDS VIEW (USER-SIDE NEARBY PHARMACIES STYLE)       -->
          <!-- ═══════════════════════════════════════════════════════════ -->
          <div id="cardsViewSection" style="display: none;">
            <div class="pharmacy-cards-grid" id="cardsGridContainer">
              <?php if (!empty($pharmaciesList)): ?>
                <?php foreach ($pharmaciesList as $row): ?>
                  <?php
                  $id = $row['Pharmacy_ID'];
                  $rawName = trim($row['Pharmacy_name'] ?? '');
                  $rawOwner = trim($row['Owner_name'] ?? '');
                  $rawEmail = trim($row['Owner_Email'] ?? '');
                  $rawPhone = trim($row['Phone'] ?? '');
                  $rawAddress = trim($row['Full_Address'] ?? '');
                  $rawBarangay = trim($row['Barangay_Name'] ?? '');
                  $status = trim($row['Approval_Status'] ?? 'Pending');

                  $isIncomplete = ($rawName === '' || $rawOwner === '' || $rawPhone === '' || $rawAddress === '');

                  $badgeClass = match ($status) {
                    'Approved' => 'badge-active',
                    'Pending' => 'badge-pending',
                    'Rejected' => 'badge-rejected',
                    'Suspended' => 'badge-suspended',
                    default => 'badge-inactive'
                  };

                  $img = !empty($row['Pic_URL']) ? $row['Pic_URL'] : '../07_Assets/images/pharmacies/RosePharmacy.png';
                  ?>
                  <div class="user-style-pharmacy-card pharmacy-item-card"
                    data-id="<?= htmlspecialchars($id) ?>"
                    data-name="<?= htmlspecialchars($rawName) ?>"
                    data-owner="<?= htmlspecialchars($rawOwner) ?>"
                    data-email="<?= htmlspecialchars($rawEmail) ?>"
                    data-phone="<?= htmlspecialchars($rawPhone) ?>"
                    data-address="<?= htmlspecialchars($rawAddress) ?>"
                    data-barangay="<?= htmlspecialchars($rawBarangay) ?>"
                    data-status="<?= htmlspecialchars($status) ?>"
                    data-created="<?= htmlspecialchars($row['DateCreated'] ?? '') ?>"
                    data-approved="<?= htmlspecialchars($row['Date_Approved'] ?? 'N/A') ?>"
                    data-pic="<?= htmlspecialchars($img) ?>">

                    <div class="pharmacy-card-thumb-wrap">
                      <img src="<?= htmlspecialchars($img) ?>" alt="Pharmacy image" class="pharmacy-card-thumb" onerror="this.src='../07_Assets/images/pharmacies/RosePharmacy.png';">
                      <span class="badge-status <?= $badgeClass ?> pharmacy-card-status-badge">
                        <?= htmlspecialchars($status) ?>
                      </span>
                    </div>

                    <div class="pharmacy-card-content">
                      <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                        <h6>
                          <?php if ($rawName !== ''): ?>
                            <?= htmlspecialchars($rawName) ?>
                          <?php else: ?>
                            <span class="text-not-provided">Not provided</span>
                          <?php endif; ?>
                        </h6>
                        <?php if ($isIncomplete): ?>
                          <span class="badge-incomplete" title="Incomplete profile wizard"><i class="bi bi-exclamation-triangle-fill"></i> Incomplete profile</span>
                        <?php endif; ?>
                      </div>

                      <div class="pharmacy-card-meta">
                        <i class="bi bi-geo-alt"></i>
                        <span>
                          <?= $rawAddress !== '' ? htmlspecialchars($rawAddress) : '<span class="text-not-provided">Not provided</span>' ?>
                        </span>
                      </div>

                      <div class="pharmacy-card-meta">
                        <i class="bi bi-person"></i>
                        <span>
                          <?= $rawOwner !== '' ? htmlspecialchars($rawOwner) : '<span class="text-not-provided">Not provided</span>' ?>
                        </span>
                      </div>

                      <div class="pharmacy-card-meta">
                        <i class="bi bi-telephone"></i>
                        <span>
                          <?= $rawPhone !== '' ? htmlspecialchars($rawPhone) : '<span class="text-not-provided">Not provided</span>' ?>
                        </span>
                      </div>

                      <div class="pharmacy-card-footer">
                        <span class="text-muted small">ID: #<?= htmlspecialchars($id) ?></span>
                        <div class="d-flex gap-2">
                          <button class="btn-action btn-view view-btn" title="View Details"
                            data-id="<?= htmlspecialchars($id) ?>"
                            data-name="<?= htmlspecialchars($rawName) ?>"
                            data-owner="<?= htmlspecialchars($rawOwner) ?>"
                            data-email="<?= htmlspecialchars($rawEmail) ?>"
                            data-phone="<?= htmlspecialchars($rawPhone) ?>"
                            data-address="<?= htmlspecialchars($rawAddress) ?>"
                            data-status="<?= htmlspecialchars($status) ?>"
                            data-created="<?= htmlspecialchars($row['DateCreated'] ?? '') ?>"
                            data-approved="<?= htmlspecialchars($row['Date_Approved'] ?? 'N/A') ?>"
                            data-pic="<?= htmlspecialchars($img) ?>">
                            <i class="bi bi-eye"></i>
                          </button>

                          <button class="btn-action btn-edit edit-btn" title="Edit Status"
                            data-id="<?= htmlspecialchars($id) ?>"
                            data-name="<?= htmlspecialchars($rawName) ?>"
                            data-owner="<?= htmlspecialchars($rawOwner) ?>"
                            data-email="<?= htmlspecialchars($rawEmail) ?>"
                            data-phone="<?= htmlspecialchars($rawPhone) ?>"
                            data-address="<?= htmlspecialchars($rawAddress) ?>"
                            data-status="<?= htmlspecialchars($status) ?>"
                            data-created="<?= htmlspecialchars($row['DateCreated'] ?? '') ?>"
                            data-approved="<?= htmlspecialchars($row['Date_Approved'] ?? 'N/A') ?>"
                            data-pic="<?= htmlspecialchars($img) ?>">
                            <i class="bi bi-pencil"></i>
                          </button>
                        </div>
                      </div>
                    </div>

                  </div>
                <?php endforeach; ?>
              <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                  <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                  No pharmacies available.
                </div>
              <?php endif; ?>
            </div>
          </div>

          <!-- ═══════════════════════════════════════════════════════════ -->
          <!-- VIEW 3: TABLE VIEW (CLASSIC DATA TABLE)                      -->
          <!-- ═══════════════════════════════════════════════════════════ -->
          <div id="tableViewSection">
            <div id="pharmacyTableScrollArea" class="table-scroll">
              <div class="card card-table border">
                <div class="table-responsive rounded">
                  <table class="table table-hover align-middle mb-0" id="pharmacyTableMain">
                  <thead class="table-head">
                    <tr>
                      <th style="width: 40px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                      <th>ID</th>
                      <th>PHARMACY</th>
                      <th>ADMINISTRATOR</th>
                      <th>EMAIL</th>
                      <th>CONTACT NO.</th>
                      <th>LOCATION</th>
                      <th>STATUS</th>
                      <th>CREATED</th>
                      <th class="text-end pe-4">ACTIONS</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if (!empty($pharmaciesList)): ?>
                      <?php foreach ($pharmaciesList as $row): ?>
                        <?php
                        $id = $row['Pharmacy_ID'];
                        $rawName = trim($row['Pharmacy_name'] ?? '');
                        $rawOwner = trim($row['Owner_name'] ?? '');
                        $rawEmail = trim($row['Owner_Email'] ?? '');
                        $rawPhone = trim($row['Phone'] ?? '');
                        $rawAddress = trim($row['Full_Address'] ?? '');
                        $rawBarangay = trim($row['Barangay_Name'] ?? '');
                        $status = trim($row['Approval_Status'] ?? 'Pending');

                        $isIncomplete = ($rawName === '' || $rawOwner === '' || $rawPhone === '' || $rawAddress === '');

                        $badgeClass = match ($status) {
                          'Approved' => 'badge-active',
                          'Pending' => 'badge-pending',
                          'Rejected' => 'badge-rejected',
                          'Suspended' => 'badge-suspended',
                          default => 'badge-inactive'
                        };

                        $img = !empty($row['Pic_URL']) ? $row['Pic_URL'] : '../07_Assets/images/pharmacies/RosePharmacy.png';
                        ?>
                        <tr class="pharmacy-table-row"
                          data-id="<?= htmlspecialchars($id) ?>"
                          data-name="<?= htmlspecialchars($rawName) ?>"
                          data-owner="<?= htmlspecialchars($rawOwner) ?>"
                          data-email="<?= htmlspecialchars($rawEmail) ?>"
                          data-phone="<?= htmlspecialchars($rawPhone) ?>"
                          data-address="<?= htmlspecialchars($rawAddress) ?>"
                          data-barangay="<?= htmlspecialchars($rawBarangay) ?>"
                          data-status="<?= htmlspecialchars($status) ?>">

                          <td><input type="checkbox" class="form-check-input row-check"></td>
                          <td><?= htmlspecialchars($id) ?></td>

                          <!-- Pharmacy Name & Incomplete Badge -->
                          <td class="fw-bold">
                            <?php if ($rawName !== ''): ?>
                              <?= htmlspecialchars($rawName) ?>
                            <?php else: ?>
                              <span class="text-not-provided">Not provided</span>
                            <?php endif; ?>
                            <?php if ($isIncomplete): ?>
                              <span class="badge-incomplete ms-1"><i class="bi bi-exclamation-triangle-fill"></i> Incomplete profile</span>
                            <?php endif; ?>
                          </td>

                          <!-- Administrator -->
                          <td>
                            <?php if ($rawOwner !== ''): ?>
                              <?= htmlspecialchars($rawOwner) ?>
                            <?php else: ?>
                              <span class="text-not-provided">Not provided</span>
                            <?php endif; ?>
                          </td>

                          <!-- Email -->
                          <td>
                            <?php if ($rawEmail !== ''): ?>
                              <?= htmlspecialchars($rawEmail) ?>
                            <?php else: ?>
                              <span class="text-not-provided">Not provided</span>
                            <?php endif; ?>
                          </td>

                          <!-- Contact No. -->
                          <td>
                            <?php if ($rawPhone !== ''): ?>
                              <?= htmlspecialchars($rawPhone) ?>
                            <?php else: ?>
                              <span class="text-not-provided">Not provided</span>
                            <?php endif; ?>
                          </td>

                          <!-- Location -->
                          <td>
                            <?php if ($rawAddress !== ''): ?>
                              <?= htmlspecialchars($rawAddress) ?>
                            <?php else: ?>
                              <span class="text-not-provided">Not provided</span>
                            <?php endif; ?>
                          </td>

                          <!-- Status -->
                          <td>
                            <span class="badge-status <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                          </td>

                          <!-- Created Date -->
                          <td><?= htmlspecialchars($row['DateCreated'] ?? 'N/A') ?></td>

                          <!-- Actions -->
                          <td class="text-end pe-4">
                            <div class="d-flex gap-2 justify-content-end text-muted">
                              <!-- View Button -->
                              <button class="btn-action btn-view view-btn" title="View"
                                data-id="<?= htmlspecialchars($id) ?>"
                                data-name="<?= htmlspecialchars($rawName) ?>"
                                data-owner="<?= htmlspecialchars($rawOwner) ?>"
                                data-email="<?= htmlspecialchars($rawEmail) ?>"
                                data-phone="<?= htmlspecialchars($rawPhone) ?>"
                                data-address="<?= htmlspecialchars($rawAddress) ?>"
                                data-status="<?= htmlspecialchars($status) ?>"
                                data-created="<?= htmlspecialchars($row['DateCreated'] ?? '') ?>"
                                data-approved="<?= htmlspecialchars($row['Date_Approved'] ?? 'N/A') ?>"
                                data-pic="<?= htmlspecialchars($img) ?>">
                                <i class="bi bi-eye"></i>
                              </button>

                              <!-- Edit Button -->
                              <button class="btn-action btn-edit edit-btn" title="Edit"
                                data-id="<?= htmlspecialchars($id) ?>"
                                data-name="<?= htmlspecialchars($rawName) ?>"
                                data-owner="<?= htmlspecialchars($rawOwner) ?>"
                                data-email="<?= htmlspecialchars($rawEmail) ?>"
                                data-phone="<?= htmlspecialchars($rawPhone) ?>"
                                data-address="<?= htmlspecialchars($rawAddress) ?>"
                                data-status="<?= htmlspecialchars($status) ?>"
                                data-created="<?= htmlspecialchars($row['DateCreated'] ?? '') ?>"
                                data-approved="<?= htmlspecialchars($row['Date_Approved'] ?? 'N/A') ?>"
                                data-pic="<?= htmlspecialchars($img) ?>">
                                <i class="bi bi-pencil"></i>
                              </button>
                            </div>
                          </td>

                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                          <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                          No pharmacies found.
                        </td>
                      </tr>
                    <?php endif; ?>
                  </tbody>

                </table>
              </div>
            </div>
            </div><!-- /#pharmacyTableScrollArea -->
          </div>

          <!-- ═══════════════════════════════════════════════════════════ -->
          <!-- RECENT RECOMMENDATIONS WIDGET (SESSION / LOCAL STORAGE)      -->
          <!-- ═══════════════════════════════════════════════════════════ -->
          <div class="recent-rec-card" id="recentRecWidget">
            <div class="recent-rec-header">
              <h6 class="recent-rec-title">
                <span class="material-symbols-outlined" style="color: #1d9e75; font-size: 20px;">history_edu</span>
                Recent Patient Recommendations
              </h6>
              <button class="btn btn-sm btn-outline-secondary" id="clearRecHistoryBtn" style="font-size: 11px; border-radius: 8px;">
                Clear History
              </button>
            </div>
            <div class="recent-rec-list" id="recentRecListContainer">
              <div class="text-muted small py-2 text-center" id="emptyRecNotice">
                <i class="bi bi-clipboard2-check me-1"></i> No recommendations generated yet in this session. Click "Recommend Pharmacy" to match medicines for patients.
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <!-- MODAL: B) RECOMMEND A PHARMACY TO A PATIENT (MOCK ENGINE)           -->
  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <div class="modal fade" id="recommendModal" tabindex="-1" aria-labelledby="recommendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <div>
            <h5 class="modal-title d-flex align-items-center gap-2" id="recommendModalLabel">
              <span class="material-symbols-outlined" style="font-size: 24px;">medication_liquid</span>
              Recommend Pharmacy to Patient
            </h5>
            <div class="small opacity-75 mt-1">Intelligent multi-medicine stock matching and proximity ranking for Malaybalay City</div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!-- Inputs: Patient Name & Patient Barangay -->
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold small text-secondary">Patient Name (Optional)</label>
              <input type="text" id="recPatientName" class="form-control" placeholder="e.g. Maria Santos">
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold small text-secondary">Patient Barangay (Location)</label>
              <select id="recPatientBarangay" class="form-select">
                <?php foreach ($mergedBarangays as $bName): ?>
                  <option value="<?= htmlspecialchars($bName) ?>" <?= $bName === 'Poblacion' ? 'selected' : '' ?>>
                    <?= htmlspecialchars($bName) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <!-- Input: Searchable Multi-Select for Medicines Needed -->
          <div class="mb-3 position-relative">
            <label class="form-label fw-semibold small text-secondary">
              Medicines Needed <span class="text-danger">*</span> (Select one or more)
            </label>

            <div class="med-select-container" id="medSelectContainer">
              <!-- Selected Chips Appended Here -->
              <input type="text" id="medSearchInput" class="med-search-input" placeholder="Search medicine (e.g. Paracetamol, Amoxicillin, Solmux)...">
            </div>

            <!-- Dropdown Options -->
            <div class="med-dropdown-menu" id="medDropdownMenu"></div>
            <div class="form-text small text-muted mt-1">
              <i class="bi bi-info-circle me-1"></i> Type to filter catalog medicines. Click to select multiple items.
            </div>
          </div>

          <!-- Submit Button -->
          <div class="d-flex justify-content-end mb-4">
            <button type="button" class="btn btn-success px-4 d-inline-flex align-items-center gap-2 rounded-3 fw-semibold" id="btnFindPharmacies">
              <i class="bi bi-search"></i>
              Find Pharmacies
            </button>
          </div>

          <!-- Results Area -->
          <div id="recResultsArea" class="recommend-results-area" style="display: none;">
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
              <h6 class="fw-bold mb-0 text-dark">
                <i class="bi bi-trophy text-warning me-1"></i> Ranked Pharmacy Recommendations
              </h6>
              <span class="small text-muted" id="recResultsSummary"></span>
            </div>

            <div id="recResultsList"></div>
          </div>

        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary px-3 rounded-3" data-bs-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>

  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <!-- MODAL: VIEW & EDIT DETAILS (KEEP EXISTING MODAL UNCHANGED)           -->
  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <?php include '../02_Actions/04_System-Admin-CRUD/pharmacy-modal.php'; ?>

  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <!-- JAVASCRIPT CONTROLLERS                                              -->
  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <script>
    // Approved pharmacy map markers generated via server-side coordinates
    const APPROVED_MARKERS = <?= json_encode($mapMarkers) ?>;

    let mapInstance = null;
    let leafletMarkersLayer = null;
    let selectedMedicines = []; // Holds selected medicine objects for recommendation modal

    $(document).ready(function () {

      // ── VIEW SWITCHER (Table vs Cards vs Map) ─────────────────────────
      $('.view-mode-btn').on('click', function () {
        const viewMode = $(this).data('view');
        $('.view-mode-btn').removeClass('active');
        $(this).addClass('active');

        if (viewMode === 'table') {
          $('#tableViewSection').show();
          $('#cardsViewSection').hide();
          $('#mapViewSection').hide();
        } else if (viewMode === 'cards') {
          $('#tableViewSection').hide();
          $('#cardsViewSection').show();
          $('#mapViewSection').hide();
        } else if (viewMode === 'map') {
          $('#tableViewSection').hide();
          $('#cardsViewSection').hide();
          $('#mapViewSection').show();
          initOrRefreshMap();
        }
      });

      // ── FILTERING (Search + Status + Barangay) ─────────────────────────
      function applyCombinedFilters() {
        const query = $('#searchPharmacy').val().toLowerCase().trim();
        const selectedStatus = $('#filterStatus').val().toLowerCase().trim();
        const selectedBrgy = $('#filterBarangay').val().toLowerCase().trim();

        let visibleCount = 0;

        // 1. Filter Table Rows
        $('.pharmacy-table-row').each(function () {
          const rowText = $(this).text().toLowerCase();
          const rowStatus = ($(this).data('status') || '').toLowerCase();
          const rowAddress = ($(this).data('address') || '').toLowerCase();
          const rowBrgy = ($(this).data('barangay') || '').toLowerCase();

          const matchQuery = query === '' || rowText.includes(query);
          const matchStatus = selectedStatus === '' || rowStatus.includes(selectedStatus);
          const matchBrgy = selectedBrgy === '' || rowBrgy.includes(selectedBrgy) || rowAddress.includes(selectedBrgy);

          const isVisible = matchQuery && matchStatus && matchBrgy;
          $(this).toggle(isVisible);
          if (isVisible) visibleCount++;
        });

        // 2. Filter Cards View
        $('.pharmacy-item-card').each(function () {
          const cardText = $(this).text().toLowerCase();
          const cardStatus = ($(this).data('status') || '').toLowerCase();
          const cardAddress = ($(this).data('address') || '').toLowerCase();
          const cardBrgy = ($(this).data('barangay') || '').toLowerCase();

          const matchQuery = query === '' || cardText.includes(query);
          const matchStatus = selectedStatus === '' || cardStatus.includes(selectedStatus);
          const matchBrgy = selectedBrgy === '' || cardBrgy.includes(selectedBrgy) || cardAddress.includes(selectedBrgy);

          $(this).toggle(matchQuery && matchStatus && matchBrgy);
        });

        // 3. Filter Map Markers
        if (mapInstance && leafletMarkersLayer) {
          leafletMarkersLayer.clearLayers();
          const visibleBounds = [];

          APPROVED_MARKERS.forEach(markerData => {
            const markerName = markerData.name.toLowerCase();
            const markerAddress = markerData.address.toLowerCase();
            const markerBrgy = markerData.barangay.toLowerCase();

            const matchQuery = query === '' || markerName.includes(query) || markerAddress.includes(query);
            const matchStatus = selectedStatus === '' || selectedStatus === 'approved';
            const matchBrgy = selectedBrgy === '' || markerBrgy.includes(selectedBrgy) || markerAddress.includes(selectedBrgy);

            if (matchQuery && matchStatus && matchBrgy) {
              const marker = createLeafletMarker(markerData);
              leafletMarkersLayer.addLayer(marker);
              visibleBounds.push([markerData.lat, markerData.lng]);
            }
          });

          $('#mapMarkersCountBadge').text(`${visibleBounds.length} locations`);

          if (visibleBounds.length > 0) {
            mapInstance.fitBounds(visibleBounds, { padding: [50, 50], maxZoom: 16 });
          }
        }

        $('#visibleCount').text(visibleCount);
      }

      $('#searchPharmacy').on('input', applyCombinedFilters);
      $('#filterStatus, #filterBarangay').on('change', applyCombinedFilters);

      // ── LEAFLET MAP INITIALIZATION ─────────────────────────────────────
      function initOrRefreshMap() {
        setTimeout(() => {
          if (!mapInstance) {
            mapInstance = L.map('pharmaciesMap').setView([8.1560, 125.1278], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: '© OpenStreetMap contributors',
              maxZoom: 19
            }).addTo(mapInstance);

            leafletMarkersLayer = L.layerGroup().addTo(mapInstance);
          }

          mapInstance.invalidateSize();
          applyCombinedFilters();
        }, 150);
      }

      function createLeafletMarker(data) {
        // User-side teal marker style
        const customIcon = L.divIcon({
          className: '',
          html: `<div style="background:#1d9e75;width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 3px 10px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;">
                  <span style="transform:rotate(45deg);color:#fff;font-size:14px;font-weight:bold;">+</span>
                 </div>`,
          iconSize: [32, 32],
          iconAnchor: [16, 32],
          popupAnchor: [0, -34]
        });

        const marker = L.marker([data.lat, data.lng], { icon: customIcon });

        const popupHtml = `
          <div class="popup-inner">
            <div class="popup-top-bar">
              <div class="popup-name">${data.name}</div>
              <span class="badge-status badge-active" style="font-size:10.5px; padding:2px 8px;">Approved</span>
            </div>
            <div class="popup-bottom-bar">
              <div class="popup-info-line">
                <i class="bi bi-geo-alt"></i>
                <span>${data.address}</span>
              </div>
              <div class="popup-info-line">
                <i class="bi bi-person"></i>
                <span>${data.owner}</span>
              </div>
              <div class="popup-info-line">
                <i class="bi bi-telephone"></i>
                <span>${data.contact}</span>
              </div>
              <div class="mt-2 pt-2 border-top d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-primary map-view-detail-btn"
                  data-id="${data.id}"
                  style="background:#1d9e75; border-color:#1d9e75; font-size:12px; border-radius:8px; padding:4px 12px;">
                  View details
                </button>
              </div>
            </div>
          </div>
        `;

        marker.bindPopup(popupHtml, { maxWidth: 280 });
        return marker;
      }

      // Open detail modal directly from Map popup
      $(document).on('click', '.map-view-detail-btn', function () {
        const id = $(this).data('id');
        const triggerBtn = $(`.view-btn[data-id="${id}"]`).first();
        if (triggerBtn.length) {
          triggerBtn.trigger('click');
        }
      });

      // ── B) RECOMMEND PHARMACY (FRONTEND MOCK ENGINE) ───────────────────
      const catalog = window.PharmacyStockMock ? window.PharmacyStockMock.getCatalog() : [];

      // Render Medicine Dropdown options
      function renderMedDropdown(filterText = '') {
        const menu = $('#medDropdownMenu');
        menu.empty();

        const filtered = catalog.filter(m => {
          const isSelected = selectedMedicines.some(s => s.medicineId === m.medicineId);
          if (isSelected) return false;
          if (!filterText) return true;
          return m.generic.toLowerCase().includes(filterText.toLowerCase()) ||
                 m.brand.toLowerCase().includes(filterText.toLowerCase()) ||
                 m.category.toLowerCase().includes(filterText.toLowerCase());
        });

        if (filtered.length === 0) {
          menu.append('<div class="p-2 text-muted small text-center">No matching medicines found</div>');
        } else {
          filtered.forEach(m => {
            menu.append(`
              <div class="med-dropdown-item" data-id="${m.medicineId}">
                <div>
                  <strong>${m.generic}</strong>
                  <span class="text-muted ms-1">(${m.brand})</span>
                  <div class="small text-secondary">${m.dosage} · ${m.category}</div>
                </div>
                <span class="badge bg-light text-dark border">+ Add</span>
              </div>
            `);
          });
        }
        menu.show();
      }

      // Add Medicine Chip
      function addMedicine(med) {
        if (selectedMedicines.some(s => s.medicineId === med.medicineId)) return;
        selectedMedicines.push(med);

        const chipHtml = `
          <span class="med-selected-chip" data-id="${med.medicineId}">
            <span>${med.generic} (${med.dosage})</span>
            <span class="chip-remove" data-id="${med.medicineId}">&times;</span>
          </span>
        `;
        $('#medSearchInput').before(chipHtml);
        $('#medSearchInput').val('').focus();
        $('#medDropdownMenu').hide();
      }

      // Remove Medicine Chip
      $(document).on('click', '.chip-remove', function (e) {
        e.stopPropagation();
        const id = parseInt($(this).data('id'));
        selectedMedicines = selectedMedicines.filter(m => m.medicineId !== id);
        $(this).closest('.med-selected-chip').remove();
      });

      // Search input interaction
      $('#medSearchInput').on('focus', function () {
        renderMedDropdown($(this).val());
      });

      $('#medSearchInput').on('input', function () {
        renderMedDropdown($(this).val());
      });

      $(document).on('click', '.med-dropdown-item', function () {
        const id = parseInt($(this).data('id'));
        const med = catalog.find(m => m.medicineId === id);
        if (med) addMedicine(med);
      });

      // Hide dropdown when clicking outside
      $(document).on('click', function (e) {
        if (!$(e.target).closest('.med-select-container, #medDropdownMenu').length) {
          $('#medDropdownMenu').hide();
        }
      });

      // Focus input when container clicked
      $('#medSelectContainer').on('click', function () {
        $('#medSearchInput').focus();
      });

      // Pre-select 1 medicine for instant preview experience
      if (catalog.length > 0) {
        addMedicine(catalog[0]); // Paracetamol 500mg
      }

      // Find Pharmacies Click Handler
      $('#btnFindPharmacies').on('click', function () {
        if (selectedMedicines.length === 0) {
          showToast('Please select at least one medicine needed.', 'warning');
          $('#medSearchInput').focus();
          return;
        }

        const patientName = $('#recPatientName').val();
        const patientBarangay = $('#recPatientBarangay').val();
        const medicineIds = selectedMedicines.map(m => m.medicineId);

        // Run mock ranking engine
        const results = window.PharmacyStockMock.findRecommendations({
          medicineIds: medicineIds,
          patientBarangay: patientBarangay
        });

        renderRecommendationResults(results, patientName);
      });

      // Render Recommendation Result Cards
      function renderRecommendationResults(results, patientName) {
        const container = $('#recResultsList');
        container.empty();

        if (results.length === 0) {
          container.html('<div class="p-4 text-center text-muted">No pharmacies found.</div>');
          $('#recResultsArea').show();
          return;
        }

        $('#recResultsSummary').text(`Showing ${results.length} ranked approved pharmacies`);

        results.forEach((item, index) => {
          // Stock chips HTML
          const stockChipsHtml = item.matchedMeds.map(m => `
            <span class="stock-chip ${m.chip.cssClass}">
              <i class="bi bi-capsule"></i>
              ${m.generic} (${m.dosage}): ${m.chip.label}
              ${m.price ? `<span class="opacity-75 ms-1">₱${m.price.toFixed(2)}</span>` : ''}
            </span>
          `).join('');

          // Generated recommendation text message
          const msgPreview = window.PharmacyStockMock.generateMessage(patientName, item, item.matchedMeds);

          const resultCard = `
            <div class="recommend-result-card" data-idx="${index}">
              <div class="recommend-header-row">
                <div>
                  <h6 class="recommend-pharmacy-name d-flex align-items-center gap-2">
                    <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill" style="font-size:11px;">#${index + 1}</span>
                    ${item.name}
                    <span class="${item.matchBadgeClass}">${item.matchBadgeText}</span>
                  </h6>
                  <div class="text-muted small mt-1">
                    <i class="bi bi-geo-alt text-success"></i> ${item.barangay} · ~${item.distanceKm.toFixed(1)} km away
                    <span class="mx-1">·</span>
                    <span class="${item.openNow ? 'text-success' : 'text-danger'} fw-semibold">${item.openingHours}</span>
                  </div>
                </div>

                <button type="button" class="btn btn-sm btn-outline-success toggle-preview-btn px-3 rounded-pill fw-semibold" data-idx="${index}">
                  <i class="bi bi-chat-text me-1"></i> Recommend
                </button>
              </div>

              <!-- Medicine Stock Chips -->
              <div class="stock-chips-row">
                ${stockChipsHtml}
              </div>

              <!-- Recommendation Message Preview Box (Collapsible) -->
              <div class="message-preview-container preview-box-${index}" style="display: none;">
                <div class="fw-semibold text-secondary small mb-1">
                  <i class="bi bi-chat-quote-fill text-success me-1"></i> Patient Notification Preview:
                </div>
                <div class="preview-text p-2 bg-white rounded border small" id="previewText_${index}">
                  ${msgPreview}
                </div>
                <div class="message-preview-actions">
                  <button type="button" class="btn btn-sm btn-light border copy-msg-btn" data-text="${encodeURIComponent(msgPreview)}">
                    <i class="bi bi-copy me-1"></i> Copy Message
                  </button>
                  <button type="button" class="btn btn-sm btn-success send-msg-btn"
                    data-patient="${encodeURIComponent(patientName || 'Walk-in Patient')}"
                    data-pharmacy="${encodeURIComponent(item.name)}"
                    data-barangay="${encodeURIComponent(item.barangay)}"
                    data-meds="${encodeURIComponent(item.matchedMeds.filter(m => m.qty > 0).map(m => m.generic).join(', ') || 'Prescription')}"
                    data-text="${encodeURIComponent(msgPreview)}">
                    <i class="bi bi-send-fill me-1"></i> Send to Patient
                  </button>
                </div>
              </div>

            </div>
          `;

          container.append(resultCard);
        });

        $('#recResultsArea').slideDown();
      }

      // Toggle recommendation message preview
      $(document).on('click', '.toggle-preview-btn', function () {
        const idx = $(this).data('idx');
        $(`.preview-box-${idx}`).slideToggle(180);
      });

      // Copy message to clipboard
      $(document).on('click', '.copy-msg-btn', function () {
        const text = decodeURIComponent($(this).data('text'));
        navigator.clipboard.writeText(text).then(() => {
          showToast('Message copied to clipboard!', 'success');
        }).catch(() => {
          showToast('Could not copy message automatically.', 'warning');
        });
      });

      // Send Recommendation (Frontend Simulation + Local Storage persistence)
      $(document).on('click', '.send-msg-btn', function () {
        const patient = decodeURIComponent($(this).data('patient'));
        const pharmacy = decodeURIComponent($(this).data('pharmacy'));
        const meds = decodeURIComponent($(this).data('meds'));
        const text = decodeURIComponent($(this).data('text'));

        saveRecentRecommendation({
          patient: patient,
          pharmacy: pharmacy,
          meds: meds,
          text: text,
          timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
        });

        showToast(`Recommendation successfully sent to ${patient}!`, 'success');
        $('#recommendModal').modal('hide');
      });

      // ── RECENT RECOMMENDATIONS LOCAL STORAGE MANAGER ───────────────────
      function loadRecentRecommendations() {
        const container = $('#recentRecListContainer');
        let recs = [];
        try {
          recs = JSON.parse(localStorage.getItem('medifind_recent_recommendations') || '[]');
        } catch (e) {
          recs = [];
        }

        if (recs.length === 0) {
          $('#emptyRecNotice').show();
          container.find('.recent-rec-item').remove();
          return;
        }

        $('#emptyRecNotice').hide();
        container.find('.recent-rec-item').remove();

        recs.slice(0, 5).forEach(rec => {
          container.append(`
            <div class="recent-rec-item">
              <div>
                <span class="recent-rec-patient"><i class="bi bi-person me-1"></i>${rec.patient}</span>
                <span class="text-muted mx-1">→</span>
                <span class="recent-rec-pharmacy"><i class="bi bi-building me-1"></i>${rec.pharmacy}</span>
                <div class="small text-secondary mt-1">
                  <i class="bi bi-capsule text-success me-1"></i> Recommended: ${rec.meds}
                </div>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="recent-rec-time"><i class="bi bi-clock me-1"></i>${rec.timestamp}</span>
                <button type="button" class="btn btn-sm btn-light border py-1 px-2 copy-msg-btn"
                  title="Copy Message" data-text="${encodeURIComponent(rec.text)}">
                  <i class="bi bi-copy"></i>
                </button>
              </div>
            </div>
          `);
        });
      }

      function saveRecentRecommendation(item) {
        let recs = [];
        try {
          recs = JSON.parse(localStorage.getItem('medifind_recent_recommendations') || '[]');
        } catch (e) {
          recs = [];
        }
        recs.unshift(item);
        if (recs.length > 20) recs.pop();
        localStorage.setItem('medifind_recent_recommendations', JSON.stringify(recs));
        loadRecentRecommendations();
      }

      $('#clearRecHistoryBtn').on('click', function () {
        localStorage.removeItem('medifind_recent_recommendations');
        loadRecentRecommendations();
        showToast('Recommendation history cleared.', 'info');
      });

      // Initial load of recommendation history
      loadRecentRecommendations();

    });
  </script>

</body>

</html>