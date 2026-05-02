<?php
// Global Variable and DB Connection
include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

require '../02_Actions/03_Pharmacy-Admin-CRUD/get_inventory.php';
require '../02_Actions/03_Pharmacy-Admin-CRUD/inventory-add-update.php';
require '../02_Actions/03_Pharmacy-Admin-CRUD/Select_Counts.php';

// ── GUARD: Users must log in ────────────────────────
if (!$_SESSION['user_id']) {
  header('Location: ../03_Authentication/login.php');
  exit;
}

// Ensure ID is an integer
$pharmacyID = (int) ($_SESSION['pharmacy_id'] ?? 0);
if (!$pharmacyID) {
  die("Error: Pharmacy ID not found in session. Please log in again.");
}
$stmt = $pdo->prepare("SELECT * FROM view_06_inventory_stocks WHERE Pharmacy_ID = ?");
$stmt->execute([$pharmacyID]);

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
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


  <!-- jQuery -->


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
    <div id="pharmacy-sidebar-container"></div>

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
                        <span class="material-symbols-outlined">pill</span>
                      </div>
                      <span class="card-label label-green">Total Medicines</span>
                    </div>
                    <div class="card-value"><?= $inStock ?></div>
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
                      <div class="card-icon icon-gray">
                        <span class="material-symbols-outlined">brightness_alert</span>
                      </div>
                      <span class="card-label label-gray">Low Stock</span>
                    </div>
                    <div class="card-value"><?= $outOfStock ?></div>
                    <div class="card-sub">Below threshold</div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-red">
                        <span class="material-symbols-outlined">event_busy</span>
                      </div>
                      <span class="card-label label-red">Expiring Soon</span>
                    </div>
                    <div class="card-value"><?= $expiringSoon ?></div>
                    <div class="card-sub">Within 30 days</div>
                  </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-amber">
                        <span class="material-symbols-outlined">local_atm</span>
                      </div>
                      <span class="card-label label-amber">Inventory Value</span>
                    </div>
                    <div class="card-value">Php <?= $inventoryValue ?></div>
                    <div class="card-sub">Estimated total</div>
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
                <h5>Medicines</h5>

                <!-- Toolbar -->
                <div class="toolbars">
                  <div class="row g-2 align-items-center mb-3">

                    <!-- Search: full width on mobile, 4 cols on lg -->
                    <div class="col-12 col-lg-4">
                      <div class="input-group searchbar-group">
                        <span class="input-group-text search-icon bg-white border-end-0">
                          <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control searchbar border-start-0 ps-0"
                          placeholder="Search medicines...">
                      </div>
                    </div>

                    <!-- Category: half on mobile, 2 cols on lg -->
                    <div class="col-6 col-lg-2">
                      <select id="filterCategory" class="form-select border-secondary-subtle">
                        <option value="">All Categories</option>
                      </select>
                    </div>

                    <!-- Status: half on mobile, 2 cols on lg -->
                    <div class="col-6 col-lg-2">
                      <select id="filterStatus" class="form-select border-secondary-subtle">
                        <option value="">All Statuses</option>
                        <option value="available">Available</option>
                        <option value="low stock">Low Stock</option>
                        <option value="out of stock">Out of Stock</option>
                        <option value="expired">Expired</option>
                      </select>
                    </div>

                    <!-- Buttons: full width on mobile, auto on lg, right-aligned -->
                    <div class="col-12 col-lg-4 d-flex gap-2 justify-content-lg-end justify-content-between">
                      <button id="addBTN"
                        class="btn btn-success flex-grow-1 flex-lg-grow-0 px-3 rounded-3 d-flex align-items-center justify-content-center gap-1"
                        style="background-color:#1d9e75 !important; border-color:#1d9e75 !important;">
                        <i class="bi bi-plus-lg"></i>
                        <span class="text-nowrap">Add New Stock</span>
                      </button>

                      <button class="btn btn-outline-secondary px-3 rounded-3" title="Export">
                        <i class="bi bi-download"></i>
                      </button>

                      <button class="btn btn-outline-secondary px-3 rounded-3 d-flex align-items-center gap-2"
                        id="statsToggleBtn" onclick="toggleStatsCards()">
                        <span id="statsChevron" class="material-symbols-outlined"
                          style="color: #87a199; font-size: 1.1rem; transition: transform 0.35s ease;">expand</span>
                        <span id="statsToggleLabel">Collapse</span>
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
                          <th>GENERIC NAME</th>
                          <th>BRAND</th>
                          <th>CATEGORY</th>
                          <th>DOSAGE FORM</th>
                          <th>STRENGTH</th>
                          <th>UNIT PRICE</th>
                          <th>QTY</th>
                          <th>EXPIRY DATE</th>
                          <th>STATUS</th>
                          <th>LAST UPDATED</th>
                          <th>ACTIONS</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php if ($stmt && $stmt->rowCount() > 0): ?>
                          <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <?php
                            $statusClass = strtolower(str_replace(' ', '-', $row['Availability_Status']));
                            ?>
                            <tr>
                              <td><input type="checkbox" class="form-check-input row-check"></td>
                              <td><?= htmlspecialchars($row['Inventory_ID']) ?></td>
                              <td class="fw-bold"><?= htmlspecialchars($row['Generic_Name']) ?></td>
                              <td><?= htmlspecialchars($row['Brand_Name']) ?></td>
                              <td><?= htmlspecialchars($row['Category_Name']) ?></td>
                              <td><?= htmlspecialchars($row['Dosage_Form']) ?></td>
                              <td><?= htmlspecialchars($row['Dosage']) ?></td>
                              <td>₱<?= number_format($row['Price'], 2) ?> / <?= htmlspecialchars($row['Price_Per']) ?></td>
                              <td><?= htmlspecialchars($row['Quantity']) ?></td>
                              <td><?= date('M d, Y', strtotime($row['Expiry_date'])) ?></td>
                              <td>
                                <span class="badge-status <?= $statusClass ?>">
                                  <?= htmlspecialchars($row['Availability_Status']) ?>
                                </span>
                              </td>
                              <td><?= date('M d, Y', strtotime($row['Last_updated'])) ?></td>

                              <td class="text-sat pe-4">
                                <div class="d-flex gap-2 justify-content-start text-muted" style="margin-left: -17px;">
                                  <!-- View -->
                                  <button class="btn-action btn-view view-btn" title="View"
                                    data-image="<?= htmlspecialchars($row['Med_Image_URL'] ?? '') ?>"
                                    data-id="<?= htmlspecialchars($row['Inventory_ID']) ?>"
                                    data-generic="<?= htmlspecialchars($row['Generic_Name']) ?>"
                                    data-brand="<?= htmlspecialchars($row['Brand_Name']) ?>"
                                    data-category="<?= htmlspecialchars($row['Category_Name']) ?>"
                                    data-dosageform="<?= htmlspecialchars($row['Dosage_Form']) ?>"
                                    data-dosagevalue="<?= htmlspecialchars($row['Dosage_Value']) ?>"
                                    data-dosageunit="<?= htmlspecialchars($row['Unit_Name']) ?>"
                                    data-dosage="<?= htmlspecialchars($row['Dosage']) ?>"
                                    data-price="<?= htmlspecialchars($row['Price']) ?>"
                                    data-priceper="<?= htmlspecialchars($row['Price_Per']) ?>"
                                    data-qty="<?= htmlspecialchars($row['Quantity']) ?>"
                                    data-expiry="<?= htmlspecialchars($row['Expiry_date']) ?>"
                                    data-status="<?= htmlspecialchars($row['Availability_Status']) ?>">
                                    <i class="bi bi-eye"></i>
                                  </button>

                                  <button class="btn-action btn-edit edit-btn" title="Edit"
                                    data-image="<?= htmlspecialchars($row['Med_Image_URL'] ?? '') ?>"
                                    data-id="<?= htmlspecialchars($row['Inventory_ID']) ?>"
                                    data-generic="<?= htmlspecialchars($row['Generic_Name']) ?>"
                                    data-brand="<?= htmlspecialchars($row['Brand_Name']) ?>"
                                    data-category="<?= htmlspecialchars($row['Category_Name']) ?>"
                                    data-dosageform="<?= htmlspecialchars($row['Dosage_Form']) ?>"
                                    data-dosagevalue="<?= htmlspecialchars($row['Dosage_Value']) ?>"
                                    data-dosageunit="<?= htmlspecialchars($row['Unit_Name']) ?>"
                                    data-dosage="<?= htmlspecialchars($row['Dosage']) ?>"
                                    data-price="<?= htmlspecialchars($row['Price']) ?>"
                                    data-priceper="<?= htmlspecialchars($row['Price_Per']) ?>"
                                    data-qty="<?= htmlspecialchars($row['Quantity']) ?>"
                                    data-expiry="<?= htmlspecialchars($row['Expiry_date']) ?>"
                                    data-status="<?= htmlspecialchars($row['Availability_Status']) ?>">
                                    <i class="bi bi-pencil"></i>
                                  </button>

                                  <!-- Delete -->
                                  <!-- <i class="bi bi-trash cursor-pointer delete-btn text-danger" title="Delete"
                                    data-id="<\?= htmlspecialchars($row['Inventory_ID']) ?>">
                                  </i> -->

                                </div>
                              </td>
                            </tr>
                          <?php endwhile; ?>
                        <?php else: ?>
                          <tr>
                            <td colspan="13" class="text-center text-muted py-4">
                              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                              No medicines found.
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
  <?php include '../02_Actions/03_Pharmacy-Admin-CRUD/modal_add-update.php'; ?>

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