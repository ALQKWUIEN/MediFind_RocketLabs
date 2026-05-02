<?php
// Global Variable and DB Connection
include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

require '../02_Actions/04_System-Admin-CRUD/select-count.php';
require '../02_Actions/04_System-Admin-CRUD/display-users.php';
require '../02_Actions/04_System-Admin-CRUD/display-users.php';



// ── GUARD: Users must log in ────────────────────────
if (!$_SESSION['user_id']) {
  header('Location: ../03_Authentication/login.php');
  exit;
}


?>

<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <title>Manage Inventory</title>

  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

  <!-- STYLES -->
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/01_dashboard.css">
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/02_ManageInventory.css">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>


  <style>
    body {
      overflow: auto;
    }

    /* ── Stats Toggle ─────────────────────────────────── */
    .hero {
      overflow: hidden;
      max-height: 400px;
      opacity: 1;
      transition: max-height 0.35s ease, opacity 0.3s ease, padding 0.3s ease, margin 0.3s ease;
    }

    .hero.collapsed {
      max-height: 0 !important;
      opacity: 0;
      padding: 0 !important;
      margin: 0 !important;
    }

    .stats-toggle-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      background: transparent;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 5px 12px;
      font-size: 13px;
      color: #6c757d;
      cursor: pointer;
      transition: background 0.15s, color 0.15s;
      line-height: 1;
    }

    .stats-toggle-btn:hover {
      background: #f8f9fa;
      color: #343a40;
    }

    .stats-toggle-btn .chevron {
      display: inline-block;
      transition: transform 0.35s ease;
      font-size: 11px;
      line-height: 1;
    }

    .stats-toggle-btn .chevron.rotated {
      transform: rotate(180deg);
    }

    .stats-header-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.25rem 0.25rem 0.25rem 0.25rem;
      margin-bottom: 2px;
    }

    /* STATUS BADGE DESIGN */
    .badge-status {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 500;
    }

    .badge-active {
      background-color: #d4f5e2;
      color: #1a7a45;
    }

    .badge-inactive {
      background-color: #e8e8e8;
      color: #5a5a5a;
    }

    .badge-suspended {
      background-color: #fde8e8;
      color: #c0392b;
    }


    /* BUTTON ACTIONS */
       /* BUTTON STYLE */
       .btn-action {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 17px;
     
      transition: all 0.2s ease;
      margin: 0 3px;
    }

    .btn-action:hover {
      transform: translateY(-1px);
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-action:active {
      transform: translateY(0);
    }

 
    .btn-view {
      background-color: #dbeafe;
      color: #2563eb;
   
    }

    .btn-view:hover {
      background-color: #2563eb;
      color: #ffffff;
    }

 
    .btn-edit {
      background-color: #fef3c7;
      color: #d97706;
      
    }

    .btn-edit:hover {
      background-color: #d97706;
      color: #ffffff;
    }

    /* ── End Stats Toggle ─────────────────────────────── */
  </style>
</head>

<body>
  <div class="wrapper d-flex align-items-stretch">
    <div id="system-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div class="content">
        <div class="content-inventory px-3">

          <!-- ══ STAT CARDS ═════════════════════════════════════ -->

          <div class="hero" id="statsHero">
            <div style="padding: 1rem 0;">
              <div class="row g-3">

                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">

                        <span class="material-symbols-outlined">supervisor_account</span>
                      </div>
                      <span class="card-label label-green">Total Users</span>
                    </div>
                    <div class="card-value"><?= $totalUsers ?></div>
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge-up">
                        <span class="material-symbols-outlined">trending_up</span>16%
                      </span>
                      <span class="card-sub">this month</span>
                    </div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">admin_meds</span>
                      </div>
                      <span class="card-label label-green">Pharmacy Users</span>
                    </div>
                    <div class="card-value"><?= $totalPharmacies ?></div>
                    <div class="card-sub">Below threshold</div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">check_circle_unread</span>
                      </div>
                      <span class="card-label label-green">Active Users</span>
                    </div>
                    <div class="card-value"> <?= $totalActive ?></div>
                    <div class="card-sub">Estimated total</div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">do_not_disturb_on</span>
                      </div>
                      <span class="card-label label-green">Pending Requests</span>
                    </div>
                    <div class="card-value"><?= $pendingPharmacies ?></div>
                    <div class="card-sub">Within 30 days</div>
                  </div>
                </div>


              </div>
            </div>
          </div>


          <!-- ══ END STAT CARDS ══════════════════════════════════ -->


          <!-- ══ INVENTORY TABLE ════════════════════════════════ -->




          <div class="inventoryTable mt-3">
            <div class="col-12">
              <div class="stat-card-table ">
                <h5>MediFind Users</h5>


                <div class="toolbars px-2">
                  <div class="row g-2 align-items-center mb-3">

                    <!-- Search -->
                    <div class="col-12 col-lg-5">
                      <div class="input-group searchbar-group">
                        <span class="input-group-text search-icon bg-white border-end-0">
                          <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control searchbar border-start-0 ps-0"
                          placeholder="Search user...">
                      </div>
                    </div>

                    <!-- Role -->
                    <div class="col-6 col-lg-2">
                      <select id="filterRole" class="form-select border-secondary-subtle">
                        <option value="">All Roles</option>
                        <option value="Patient/Client">Patient/Client</option>
                        <option value="Pharmacy">Pharmacy Admin</option>
                      </select>
                    </div>

                    <!-- Status -->
                    <div class="col-6 col-lg-2">
                      <select id="filterStatus" class="form-select border-secondary-subtle">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Suspended">Suspended</option>
                        <option value="Inactive">Inactive</option>
                        <!-- <option value="Pending">Pending</option> -->
                      </select>
                    </div>

                    <!-- View Pharmacies + Toggle -->
                    <div class="col-6 col-lg-3 d-flex gap-2 justify-content-end">
                      <a href="../06_SystemAdmin/03_Pharmacies.php" style="text-decoration: none;">
                        <button id="addBTN"
                          class="btn btn-success px-3 rounded-3 d-flex align-items-center justify-content-center gap-1"
                          style="background-color:#1d9e75 !important; border-color:#1d9e75 !important;">
                          <span class="material-symbols-outlined" style="font-size:1.1rem;">local_hospital</span>
                          <span class="text-nowrap small">View Pharmacies</span>
                        </button>
                      </a>

                      <button id="statsToggleBtn" onclick="toggleStatsCards()"
                        class="btn btn-outline-secondary px-3 rounded-3 d-flex align-items-center justify-content-center gap-1 flex-shrink-0">
                        <span id="statsChevron" class="material-symbols-outlined"
                          style="color: #6c757d; font-size: 1.1rem; transition: transform 0.35s ease;">unfold_more</span>
                        <span id="statsToggleLabel" class="ms-1">Expand</span>
                      </button>
                    </div>

                  </div>
                </div>
                <!-- End Toolbar -->

                <div class="card card-table border">
                  <div class="table-responsive rounded table-scroll">
                    <table class="table table-hover align-middle mb-5">
                      <thead class="table-head">
                        <tr>
                          <th><input type="checkbox" id="selectAll"></th>
                          <th>ID</th>
                          <th>FULLNAME</th>
                          <th>EMAIL</th>
                          <th>CONTACT NO.</th>
                          <th>ROLE</th>
                          <th>ADDRESS</th>
                          <th>STATUS</th>
                          <th>CREATED</th>
                          <th>ACTIONS</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php if ($stmt && $stmt->rowCount() > 0): ?>
                          <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr>
                              <td><input type="checkbox" class="form-check-input row-check"></td>
                              <td><?= htmlspecialchars($row['User_ID']) ?></td>
                              <td class="fw-bold"><?= htmlspecialchars($row['Full_Name']) ?></td>
                              <td><?= htmlspecialchars($row['Email']) ?></td>
                              <td><?= htmlspecialchars($row['Phone']) ?></td>
                              <td><?= htmlspecialchars($row['Role']) ?></td>
                              <td><?= htmlspecialchars($row['Full_Address']) ?></td>
                              <td>
                                <?php
                                $status = trim($row['UserStatus']);
                                $badgeClass = match ($status) {
                                  'Active' => 'badge-active',
                                  'Inactive' => 'badge-inactive',
                                  'Suspended' => 'badge-suspended',
                                  default => 'badge-inactive'
                                };
                                ?>
                                <span class="badge-status <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                              </td>
                              <td><?= htmlspecialchars($row['DateCreated']) ?></td>
                              <td class="text-end pe-4">
                              <div class="d-flex gap-2 justify-content-start text-muted" style="margin-left: -17px;">

                                  <!-- View -->
                                  <button class="btn-action btn-view view-btn" title="View"
                                    data-id="<?= htmlspecialchars($row['User_ID']) ?>"
                                    data-firstname="<?= htmlspecialchars($row['First_name']) ?>"
                                    data-lastname="<?= htmlspecialchars($row['Last_name']) ?>"
                                    data-email="<?= htmlspecialchars($row['Email']) ?>"
                                    data-phone="<?= htmlspecialchars($row['Phone']) ?>"
                                    data-role="<?= htmlspecialchars($row['Role']) ?>"
                                    data-status="<?= htmlspecialchars($row['UserStatus']) ?>"
                                    data-address="<?= htmlspecialchars($row['Full_Address']) ?>"
                                    data-created="<?= htmlspecialchars($row['DateCreated']) ?>"
                                    data-approved="<?= htmlspecialchars($row['DateApproved'] ?? 'N/A') ?>"
                                    data-username="<?= htmlspecialchars($row['Username']) ?>"
                                    data-gender="<?= htmlspecialchars($row['Gender'] ?? 'N/A') ?>"
                                    data-age="<?= htmlspecialchars($row['Age'] ?? 'N/A') ?>"
                                    data-pic="<?= htmlspecialchars($row['Profile_Pic'] ?? '') ?>">
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
                                    data-address="<?= htmlspecialchars($row['Full_Address']) ?>"
                                    data-created="<?= htmlspecialchars($row['DateCreated']) ?>"
                                    data-approved="<?= htmlspecialchars($row['DateApproved'] ?? 'N/A') ?>"
                                    data-username="<?= htmlspecialchars($row['Username']) ?>"
                                    data-gender="<?= htmlspecialchars($row['Gender'] ?? 'N/A') ?>"
                                    data-age="<?= htmlspecialchars($row['Age'] ?? 'N/A') ?>"
                                    data-pic="<?= htmlspecialchars($row['Profile_Pic'] ?? '') ?>">
                                    <i class="bi bi-pencil"></i>
                                  </button>

                                  <!-- Delete -->
                                  <!-- <i class="bi bi-trash cursor-pointer delete-btn text-danger" title="Delete"
                                    data-id="<'?= htmlspecialchars($row['User_ID']) ?>">
                                  </i> -->

                                </div>
                              </td>
                            </tr>
                          <?php endwhile; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                              No users found.
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>

                    </table>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <!-- ══ END TABLE ═════════════════════════════════════ -->


        </div>
      </div>
    </div>

  </div>

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