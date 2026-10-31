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
require '../02_Actions/04_System-Admin-CRUD/display-users.php';
require_once '../02_Actions/04_System-Admin-CRUD/get-address-filters.php';

// Pre-load provinces for initial SSR dropdown
$provincesList = getProvincesList($pdo);

// ── GUARD: Users must log in ────────────────────────
if (empty($_SESSION['user_id'])) {
  header('Location: ../03_Authentication/login.php');
  exit;
}

$usersList = ($stmt && $stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
$totalUsersCount = count($usersList);
?>

<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <title>Manage Users - MediFind Malaybalay</title>

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

  <!-- Design System & Theme Styles (User-Side Source of Truth) -->
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/theme-tokens.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/admin-buttons.css" />
  <link rel="stylesheet" href="../07_Assets/css/03_SystemAdminCSS/dashboard.css" />
  <link rel="stylesheet" href="../07_Assets/css/03_SystemAdminCSS/manage-users.css" />

  <!-- Navigation Scripts -->
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>


</head>

<body data-active="02" class="lgu-dashboard-page">
  <div class="wrapper d-flex align-items-stretch">
    <!-- Dynamic System Sidebar -->
    <div id="system-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <!-- Dynamic Topbar -->
      <div id="topbar-container"></div>

      <div class="content flex-grow-1">
        <div class="manage-users-container">

          <!-- ══ STAT CARDS (EXPANDABLE) ═════════════════════════ -->
          <div class="hero collapsed" id="statsHero">
            <div class="mb-3">
              <div class="row g-3 g-xl-4">

                <!-- 1. Total Users -->
                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">supervisor_account</span>
                      </div>
                      <span class="card-label">Total Users</span>
                    </div>
                    <div class="card-value"><?= number_format($totalUsers) ?></div>
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge-up">
                        <span class="material-symbols-outlined">trending_up</span> Active
                      </span>
                      <span class="card-sub">registered in system</span>
                    </div>
                  </div>
                </div>

                <!-- 2. Pharmacy Users -->
                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">admin_meds</span>
                      </div>
                      <span class="card-label">Pharmacy Admins</span>
                    </div>
                    <div class="card-value"><?= number_format($totalPharmacies) ?></div>
                    <div class="card-sub">Store management users</div>
                  </div>
                </div>

                <!-- 3. Active Users -->
                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">check_circle_unread</span>
                      </div>
                      <span class="card-label">Active Users</span>
                    </div>
                    <div class="card-value"><?= number_format($totalActiveUsers) ?></div>
                    <div class="card-sub">Verified accounts</div>
                  </div>
                </div>

                <!-- 4. Pending Requests -->
                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">pending_actions</span>
                      </div>
                      <span class="card-label">Pending Stores</span>
                    </div>
                    <div class="card-value"><?= number_format($pendingPharmaciesDash) ?></div>
                    <div class="card-sub">Awaiting verification</div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <!-- ══ END STAT CARDS ══════════════════════════════════ -->

          <!-- ══ MAIN USERS TABLE CARD ═══════════════════════════ -->
          <div class="stat-card-table">

            <!-- Card Header & Top Action Buttons -->
            <div class="users-card-header">
              <div>
                <h2 class="users-card-title">
                  <span class="material-symbols-outlined" style="color:var(--mf-green);">group</span>
                  MediFind Users
                </h2>
                <div class="users-card-subtitle">Manage accounts, view citizen registrations, and filter by municipal jurisdiction</div>
              </div>

              <div class="d-flex align-items-center gap-2">
                <a href="../06_SystemAdmin/03_Pharmacies.php" style="text-decoration: none;">
                  <button id="addBTN" class="btn btn-success px-3 rounded-3 d-flex align-items-center justify-content-center gap-1"
                    style="background-color:#1d9e75 !important; border-color:#1d9e75 !important; font-weight:600;">
                    <span class="material-symbols-outlined" style="font-size:1.1rem;">local_hospital</span>
                    <span class="text-nowrap small">View Pharmacies</span>
                  </button>
                </a>

                <button id="statsToggleBtn" onclick="toggleStatsCards()"
                  class="btn btn-outline-secondary px-3 rounded-3 d-flex align-items-center justify-content-center gap-1">
                  <span id="statsChevron" class="material-symbols-outlined"
                    style="color: #6c757d; font-size: 1.1rem; transition: transform 0.35s ease;">unfold_more</span>
                  <span id="statsToggleLabel" class="ms-1 small fw-semibold">Expand</span>
                </button>
              </div>
            </div>

            <!-- ══ FILTER TOOLBAR ══════════════════════════════════ -->
            <div class="users-filter-box">
              <!-- Row 1: Search, Role, Status -->
              <div class="row g-2 align-items-center mb-2">
                <!-- Search -->
                <div class="col-12 col-md-6 col-lg-5">
                  <div class="input-group searchbar-group">
                    <span class="input-group-text search-icon">
                      <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control searchbar"
                      placeholder="Search user name, email, contact, or address...">
                  </div>
                </div>

                <!-- Role -->
                <div class="col-6 col-md-3 col-lg-3">
                  <select id="filterRole" class="form-select form-select-user w-100">
                    <option value="">All Roles</option>
                    <option value="Patient/Client">Patient/Client</option>
                    <option value="Pharmacy">Pharmacy Admin</option>
                    <option value="System">System Admin</option>
                  </select>
                </div>

                <!-- Status -->
                <div class="col-6 col-md-3 col-lg-4">
                  <select id="filterStatus" class="form-select form-select-user w-100">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Suspended">Suspended</option>
                    <option value="Inactive">Inactive</option>
                  </select>
                </div>
              </div>

              <!-- Row 2: Cascading Address Filters (Province -> City -> Barangay) -->
              <div class="row g-2 align-items-center">
                <!-- Province -->
                <div class="col-12 col-md-4">
                  <select id="filterProvince" class="form-select form-select-user w-100">
                    <option value="">All Provinces</option>
                    <?php if (!empty($provincesList)): ?>
                      <?php foreach ($provincesList as $prov): ?>
                        <option value="<?= (int)$prov['Province_ID'] ?>"><?= htmlspecialchars($prov['Province_Name']) ?></option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>

                <!-- City / Municipality (Cascading from Province) -->
                <div class="col-12 col-md-4">
                  <select id="filterCity" class="form-select form-select-user w-100" disabled>
                    <option value="">All Cities / Municipalities</option>
                  </select>
                </div>

                <!-- Barangay (Cascading from City) -->
                <div class="col-12 col-md-4">
                  <select id="filterBarangay" class="form-select form-select-user w-100" disabled>
                    <option value="">All Barangays</option>
                  </select>
                </div>
              </div>

              <!-- Filter Meta Bar: Results Count, Active Chips, Clear Filters Button -->
              <div class="filter-meta-bar">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                  <span id="userCountBadge" class="user-result-counter">
                    Showing <strong id="visibleUserCount"><?= $totalUsersCount ?></strong> of <strong id="totalUserCount"><?= $totalUsersCount ?></strong> users
                  </span>

                  <!-- Active Filter Chips -->
                  <div id="activeFilterChips" class="d-none d-flex flex-wrap align-items-center gap-2"></div>
                </div>

                <div>
                  <button type="button" id="clearFiltersBtn" class="btn btn-clear-filters d-none">
                    <i class="bi bi-x-circle me-1"></i> Clear filters
                  </button>
                </div>
              </div>
            </div>
            <!-- ══ END FILTER TOOLBAR ══════════════════════════════ -->

            <!-- ══ USERS TABLE ═════════════════════════════════════ -->
            <div id="usersScrollArea" class="table-scroll">
              <div class="card card-table border">
                <div class="table-responsive rounded">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-head">
                    <tr>
                      <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                      <th>ID</th>
                      <th>FULLNAME</th>
                      <th>EMAIL</th>
                      <th>CONTACT NO.</th>
                      <th>ROLE</th>
                      <th>ADDRESS</th>
                      <th>STATUS</th>
                      <th>CREATED</th>
                      <th class="text-end pe-4">ACTIONS</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php if (!empty($usersList)): ?>
                      <?php foreach ($usersList as $row): ?>
                        <?php
                          $bName = trim($row['Barangay_Name'] ?? '');
                          $cName = trim($row['City_Name'] ?? '');
                          $addrText = '';
                          if ($bName !== '' && $cName !== '') {
                              $addrText = $bName . ', ' . $cName;
                          } elseif ($bName !== '') {
                              $addrText = $bName;
                          } elseif ($cName !== '') {
                              $addrText = $cName;
                          }

                          $status = trim($row['UserStatus'] ?? '');
                          $badgeClass = match ($status) {
                            'Active'    => 'badge-active',
                            'Inactive'  => 'badge-inactive',
                            'Suspended' => 'badge-suspended',
                            default     => 'badge-inactive'
                          };

                          $roleText = trim($row['Role'] ?? '');
                          $rolePillClass = 'role-pill-patient';
                          if (stripos($roleText, 'Pharmacy') !== false) {
                              $rolePillClass = 'role-pill-pharmacy';
                          } elseif (stripos($roleText, 'Admin') !== false) {
                              $rolePillClass = 'role-pill-admin';
                          }
                        ?>
                        <tr class="user-row"
                          data-province-id="<?= (int)($row['Province_ID'] ?? 0) ?>"
                          data-province-name="<?= htmlspecialchars(strtolower(trim($row['Province_Name'] ?? ''))) ?>"
                          data-city-id="<?= (int)($row['City_ID'] ?? 0) ?>"
                          data-city-name="<?= htmlspecialchars(strtolower(trim($row['City_Name'] ?? ''))) ?>"
                          data-barangay-id="<?= (int)($row['Barangay_ID'] ?? 0) ?>"
                          data-barangay-name="<?= htmlspecialchars(strtolower(trim($row['Barangay_Name'] ?? ''))) ?>">
                          <td><input type="checkbox" class="form-check-input row-check"></td>
                          <td class="text-muted fw-semibold">#<?= htmlspecialchars($row['User_ID']) ?></td>
                          <td>
                            <span class="user-fullname"><?= htmlspecialchars($row['Full_Name']) ?></span>
                          </td>
                          <td><?= htmlspecialchars($row['Email']) ?></td>
                          <td><?= htmlspecialchars($row['Phone']) ?></td>
                          <td>
                            <span class="role-pill <?= $rolePillClass ?>"><?= htmlspecialchars($row['Role']) ?></span>
                          </td>
                          <td>
                            <?php if (!empty($addrText)): ?>
                              <span class="user-address-text"><?= htmlspecialchars($addrText) ?></span>
                            <?php else: ?>
                              <span class="text-muted fst-italic">Not provided</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <span class="badge-status <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                          </td>
                          <td class="small text-muted"><?= htmlspecialchars($row['DateCreated']) ?></td>
                          <td class="text-end pe-4">
                            <div class="d-flex gap-1 justify-content-end text-muted">

                              <!-- View -->
                              <button class="btn-action btn-view view-btn" title="View"
                                data-id="<?= htmlspecialchars($row['User_ID']) ?>"
                                data-firstname="<?= htmlspecialchars($row['First_name']) ?>"
                                data-lastname="<?= htmlspecialchars($row['Last_name']) ?>"
                                data-email="<?= htmlspecialchars($row['Email']) ?>"
                                data-phone="<?= htmlspecialchars($row['Phone']) ?>"
                                data-role="<?= htmlspecialchars($row['Role']) ?>"
                                data-status="<?= htmlspecialchars($row['UserStatus']) ?>"
                                data-address="<?= htmlspecialchars($row['Full_Address'] ?? '') ?>"
                                data-created="<?= htmlspecialchars($row['DateCreated']) ?>"
                                data-approved="<?= htmlspecialchars($row['DateApproved'] ?? 'N/A') ?>"
                                data-username="<?= htmlspecialchars($row['Username']) ?>"
                                data-gender="<?= htmlspecialchars($row['Gender'] ?? 'N/A') ?>"
                                data-age="<?= htmlspecialchars($row['Age'] ?? 'N/A') ?>"
                                data-pic="<?= htmlspecialchars($row['Profilepic_url'] ?? $row['Profile_Pic'] ?? '') ?>">
                                <i class="bi bi-eye"></i>
                              </button>

                              <!-- Edit -->
                              <button class="btn-action btn-edit edit-btn" title="Edit"
                                data-id="<?= htmlspecialchars($row['User_ID']) ?>"
                                data-firstname="<?= htmlspecialchars($row['First_name']) ?>"
                                data-lastname="<?= htmlspecialchars($row['Last_name']) ?>"
                                data-email="<?= htmlspecialchars($row['Email']) ?>"
                                data-phone="<?= htmlspecialchars($row['Phone']) ?>"
                                data-role="<?= htmlspecialchars($row['Role']) ?>"
                                data-status="<?= htmlspecialchars($row['UserStatus']) ?>"
                                data-address="<?= htmlspecialchars($row['Full_Address'] ?? '') ?>"
                                data-created="<?= htmlspecialchars($row['DateCreated']) ?>"
                                data-approved="<?= htmlspecialchars($row['DateApproved'] ?? 'N/A') ?>"
                                data-username="<?= htmlspecialchars($row['Username']) ?>"
                                data-gender="<?= htmlspecialchars($row['Gender'] ?? 'N/A') ?>"
                                data-age="<?= htmlspecialchars($row['Age'] ?? 'N/A') ?>"
                                data-pic="<?= htmlspecialchars($row['Profilepic_url'] ?? $row['Profile_Pic'] ?? '') ?>">
                                <i class="bi bi-pencil"></i>
                              </button>

                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                      <tr id="noResultsRow" style="display:none;">
                        <td colspan="10" class="text-center text-muted py-5">
                          <i class="bi bi-filter-circle fs-2 d-block mb-2 text-secondary"></i>
                          No users match the selected filters.
                          <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="resetAllFilters()">
                              Clear all filters
                            </button>
                          </div>
                        </td>
                      </tr>
                    <?php else: ?>
                      <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                          <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                          No users found.
                        </td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            </div><!-- /#usersScrollArea -->
            <!-- ══ END USERS TABLE ═════════════════════════════════ -->

          </div><!-- /.stat-card-table -->

        </div><!-- /.manage-users-container -->
      </div><!-- /.content -->
    </div><!-- /.main-panel -->
  </div><!-- /.wrapper -->

  <!-- ══ MODAL ════════════════════════════════════════════════ -->
  <?php include '../02_Actions/04_System-Admin-CRUD/user-modal.php'; ?>

  <!-- ── Stats Toggle Script ─────────────────────────────────── -->
  <script>
    function toggleStatsCards() {
      const hero = document.getElementById('statsHero');
      const label = document.getElementById('statsToggleLabel');
      const chevron = document.getElementById('statsChevron');
      const isVisible = !hero.classList.contains('collapsed');

      hero.classList.toggle('collapsed', isVisible);
      label.textContent = isVisible ? 'Expand' : 'Collapse';
      chevron.classList.toggle('rotated', isVisible);
    }
  </script>
  <!-- ── End Stats Toggle Script ────────────────────────────── -->

</body>

</html>