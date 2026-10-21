<?php
// 05_PharmacyAdmin/reports.php
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

// ── 1. Fetch Inventory Report Data ─────────────────
$invStmt = $pdo->prepare("
  SELECT 
    i.Inventory_ID, i.Quantity, i.Price, i.Expiry_date, i.Last_updated,
    gn.Generic_Name, bn.Brand_Name, df.Dosage_Form, dv.Dosage_Value, du.Unit_Name,
    c.Category_Name, up.Unit AS Price_Per,
    CASE 
      WHEN i.Expiry_date <= CURDATE() THEN 'Expired'
      WHEN i.Quantity = 0 THEN 'Out of Stock'
      WHEN i.Quantity <= 10 THEN 'Low Stock'
      ELSE 'Available'
    END AS Availability_Status
  FROM 21_inventory i
  INNER JOIN 12_meds_medicines med ON i.Medicine_ID = med.Medicine_ID
  LEFT JOIN 13_meds_generic_name gn ON med.Gen_ID = gn.Gen_ID
  LEFT JOIN 14_meds_brand_name bn ON med.Brand_ID = bn.Brand_ID
  LEFT JOIN 15_meds_dosage_form df ON med.DosageForm_ID = df.DosageForm_ID
  LEFT JOIN 16_meds_dosage_value dv ON med.DosageVal_ID = dv.DosageVal_ID
  LEFT JOIN 17_meds_dosage_unit du ON med.Unit_ID = du.Unit_ID
  LEFT JOIN 18_meds_categories c ON med.Category_ID = c.Category_ID
  LEFT JOIN 22_inventory_unit_price up ON i.Price_Unit_ID = up.Price_Unit_ID
  WHERE i.Pharmacy_ID = ? AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
  ORDER BY gn.Generic_Name ASC
");
$invStmt->execute([$pharmacyID]);
$inventoryReport = $invStmt->fetchAll(PDO::FETCH_ASSOC);

// ── 2. Fetch Sales Movement / POS Transactions Data ─
$salesReport = [];
$totalSalesRevenue = 0.00;
try {
  $salesStmt = $pdo->prepare("
    SELECT 
      s.Sale_ID, s.Receipt_No, s.Customer_Name, s.Subtotal, s.Discount_Type,
      s.Discount_Amount, s.Grand_Total, s.Payment_Method, s.Sale_Date,
      COUNT(si.Item_ID) as total_items,
      SUM(si.Quantity) as total_qty
    FROM 26_pos_sales s
    LEFT JOIN 27_pos_sale_items si ON s.Sale_ID = si.Sale_ID
    WHERE s.Pharmacy_ID = ?
    GROUP BY s.Sale_ID
    ORDER BY s.Sale_Date DESC
    LIMIT 100
  ");
  $salesStmt->execute([$pharmacyID]);
  $salesReport = $salesStmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($salesReport as $sr) {
    $totalSalesRevenue += (float) $sr['Grand_Total'];
  }
} catch (Exception $e) {
  $salesReport = [];
}

// Summary Metrics
$totalInventoryCount = count($inventoryReport);
$totalLowStock = 0;
$totalInventoryValue = 0.00;

foreach ($inventoryReport as $it) {
  $totalInventoryValue += ((int) $it['Quantity'] * (float) $it['Price']);
  if ((int) $it['Quantity'] <= 10)
    $totalLowStock++;
}
?>
<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <title>Reports &amp; PDF Export - MediFind</title>

  <!-- External libraries via CDN with pinned versions -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../07_Assets/node_modules/material-symbols/outlined.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- html2pdf.js CDN (pinned version) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

  <!-- Scripts & CSS -->
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/theme-tokens.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/admin-buttons.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/01_dashboard.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/02_ManageInventory.css" />

  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    html,
    body {
      height: 100%;
      overflow-y: auto !important;
      background-color: #f8faf9;
    }

    .wrapper {
      min-height: 100vh;
      display: flex;
    }

    .main-panel {
      flex: 1;
      min-height: 100vh;
      overflow-y: auto !important;
      display: flex;
      flex-direction: column;
    }

    .scanner-btn-orange {
      background: #d97706 !important;
      border-color: #d97706 !important;
      color: #ffffff !important;
      box-shadow: 0 2px 8px rgba(217, 119, 6, 0.25);
    }

    .scanner-btn-orange:hover {
      background: #b45309 !important;
      border-color: #b45309 !important;
      color: #ffffff !important;
    }

    .icon-orange {
      background: #fef3c7;
      color: #d97706;
      width: 40px;
      height: 40px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .icon-orange .material-symbols-outlined {
      color: #d97706;
      font-size: 24px;
    }

    .label-orange {
      color: #d97706;
      font-weight: 600;
      font-size: 13px;
    }

    /* Collapsible stat cards (same behavior as Manage Inventory) */
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

    /* Status badges (same as Manage Inventory) */
    .badge-status {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      display: inline-block;
    }

    .badge-available {
      background-color: #d4f5e2;
      color: #1a7a45;
    }

    .badge-low-stock {
      background-color: #fef3c7;
      color: #b45309;
    }

    .badge-out-of-stock {
      background-color: #fde8e8;
      color: #c0392b;
    }

    .badge-expired {
      background-color: #fee2e2;
      color: #991b1b;
    }

    /* ── Fixed layout: topbar, stat cards, title and toolbar stay put; only the report rows scroll ── */
    @media (min-width: 992px) {
      body[data-active="reports"] .wrapper {
        height: 100vh;
        min-height: 0;
      }

      body[data-active="reports"] .main-panel {
        height: 100vh;
        min-height: 0;
        overflow: hidden !important;
      }

      body[data-active="reports"] .main-panel>.content {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
      }

      body[data-active="reports"] .content-inventory {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
      }

      body[data-active="reports"] .hero {
        flex: 0 0 auto;
      }

      body[data-active="reports"] .inventoryTable {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
      }

      body[data-active="reports"] .inventoryTable>.col-12 {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
      }

      body[data-active="reports"] .stat-card-table {
        flex: 1 1 auto;
        min-height: 0;
        height: auto;
        max-height: none;
        display: flex;
        flex-direction: column;
      }

      body[data-active="reports"] .stat-card-table>* {
        flex: 0 0 auto;
      }

      /* The only scrolling region */
      body[data-active="reports"] #reportScrollArea {
        flex: 1 1 auto !important;
        min-height: 0;
        overflow-y: auto;
        overflow-x: auto;
        padding-right: 2px;
      }

      /* Let the scroll area (not .table-responsive) own scrolling so sticky headers work */
      body[data-active="reports"] #reportScrollArea .table-responsive {
        overflow: visible;
      }

      body[data-active="reports"] #reportScrollArea .card-table {
        overflow: visible;
      }

      body[data-active="reports"] #reportScrollArea .report-table thead {
        position: sticky;
        top: 0;
        z-index: 3;
        box-shadow: 0 1px 0 #d1ded8;
      }
    }

    /* Section title above each report table */
    .report-section-title {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 0.75rem;
    }
  </style>
</head>

<body data-active="reports">
  <div class="wrapper d-flex align-items-stretch">
    <div id="pharmacy-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div class="content">
        <div class="content-inventory px-3 py-2">

          <!-- ══ HERO STAT CARDS WITH ICONS & CONSISTENT TEXT COLOR ══ -->
          <div class="hero" id="statsHero">
            <div style="padding: 1rem 0;">
              <div class="row g-3">

                <!-- Card 1: Total Stock Items -->
                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">inventory_2</span>
                      </div>
                      <span class="card-label label-green">Total Stock Items</span>
                    </div>
                    <div class="card-value"><?= number_format($totalInventoryCount) ?></div>
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge-up">
                        <span class="material-symbols-outlined">verified</span>Active Masterlist
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Card 2: Low Stock Warnings -->
                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-amber">
                        <span class="material-symbols-outlined">warning</span>
                      </div>
                      <span class="card-label label-amber">Low Stock Warnings</span>
                    </div>
                    <div class="card-value"><?= number_format($totalLowStock) ?></div>
                    <div class="card-sub">&le; 10 units safety threshold</div>
                  </div>
                </div>

                <!-- Card 3: Total Inventory Value -->
                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-green">
                        <span class="material-symbols-outlined">payments</span>
                      </div>
                      <span class="card-label label-green">Total Inventory Value</span>
                    </div>
                    <div class="card-value">₱<?= number_format($totalInventoryValue, 2) ?></div>
                    <div class="card-sub">Estimated retail valuation</div>
                  </div>
                </div>

                <!-- Card 4: Total Recorded Sales (Warm Orange instead of blue) -->
                <div class="col-12 col-sm-6 col-xl-3">
                  <div class="stat-card">
                    <div class="card-header-row">
                      <div class="card-icon icon-orange">
                        <span class="material-symbols-outlined">point_of_sale</span>
                      </div>
                      <span class="card-label label-orange">Total Recorded Sales</span>
                    </div>
                    <div class="card-value">₱<?= number_format($totalSalesRevenue, 2) ?></div>
                    <div class="card-sub"><?= count($salesReport) ?> completed POS checkouts</div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- ══ REPORT CONTAINER & TOOLBAR (Matching Manage Inventory) ══ -->
          <div class="inventoryTable mt-2">
            <div class="col-12">
              <div class="stat-card-table">

                <!-- Table Header: Title matching Manage Inventory -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h5 class="fw-bold mb-0 text-dark">Pharmacy Reports</h5>
                  <span class="badge bg-light text-secondary border"><?= count($inventoryReport) ?> records
                    listed</span>
                </div>

                <!-- Toolbars: Filter alongside sidebar -->
                <div class="toolbars mb-3">
                  <div class="row g-2 align-items-center">

                    <!-- Search Input -->
                    <div class="col-12 col-lg-3">
                      <div class="input-group searchbar-group">
                        <span class="input-group-text search-icon bg-white border-end-0">
                          <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="reportSearchInput" class="form-control searchbar border-start-0 ps-0"
                          placeholder="Search report items...">
                      </div>
                    </div>

                    <!-- Report Section Selector -->
                    <div class="col-6 col-lg-2">
                      <select id="reportTypeFilter" class="form-select border-secondary-subtle">
                        <option value="all">All Reports</option>
                        <option value="inventory">Inventory Only</option>
                        <option value="sales">Sales &amp; POS Only</option>
                      </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-6 col-lg-2">
                      <select id="reportStatusFilter" class="form-select border-secondary-subtle">
                        <option value="all">All Records</option>
                        <option value="low stock">Low Stock (&le; 10)</option>
                        <option value="available">Available Stock</option>
                        <option value="out of stock">Out of Stock</option>
                        <option value="expired">Expired</option>
                      </select>
                    </div>

                    <!-- Action Buttons: Warm Orange replaces blue -->
                    <div class="col-12 col-lg-5 d-flex gap-2 justify-content-lg-end justify-content-between">
                      <button type="button" id="btnExportReportPdf"
                        class="btn scanner-btn-orange px-3 rounded-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 1.15rem;">picture_as_pdf</span>
                        <span class="text-nowrap">Download PDF</span>
                      </button>
                      <a href="pos.php"
                        class="btn btn-outline-secondary px-3 rounded-3 d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined"
                          style="font-size: 1.15rem; color: #d97706;">point_of_sale</span>
                        <span class="text-nowrap">POS Counter</span>
                      </a>

                      <!-- Collapse Stats Button -->
                      <button class="btn btn-outline-secondary px-3 rounded-3 d-flex align-items-center gap-1"
                        id="statsToggleBtn" type="button" onclick="toggleStatsCards()">
                        <span id="statsChevron" class="material-symbols-outlined"
                          style="color: #87a199; font-size: 1.1rem; transition: transform 0.35s ease;">expand</span>
                        <span id="statsToggleLabel" class="d-none d-sm-inline">Collapse</span>
                      </button>
                    </div>

                  </div>
                </div>

                <!-- Scrollable region (PDF export still captures #reportPrintArea at full height) -->
                <div id="reportScrollArea">
                  <!-- Printable Report Area -->
                  <div id="reportPrintArea">

                    <!-- SECTION 1: INVENTORY STOCK STATUS TABLE -->
                    <div id="inventoryReportSection" class="mb-5">
                      <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                          <span class="material-symbols-outlined text-success"
                            style="font-size: 1.25rem;">inventory</span>
                          Inventory Stock Status Report
                        </h6>
                        <span class="text-muted small"><?= count($inventoryReport) ?> records listed</span>
                      </div>

                      <div class="card card-table border">
                        <div class="table-responsive rounded">
                          <table class="table table-hover align-middle mb-0 report-table">
                            <thead class="table-head">
                              <tr>
                                <th>ID</th>
                                <th>GENERIC NAME</th>
                                <th>BRAND</th>
                                <th>CATEGORY</th>
                                <th>DOSAGE</th>
                                <th>PRICE (₱)</th>
                                <th>QTY</th>
                                <th>EXPIRY DATE</th>
                                <th>STATUS</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($inventoryReport)): ?>
                                <?php foreach ($inventoryReport as $r): ?>
                                  <?php
                                  $dFull = ($r['Dosage_Value'] ? $r['Dosage_Value'] . ' ' . $r['Unit_Name'] : '') . ($r['Dosage_Form'] ? ' (' . $r['Dosage_Form'] . ')' : '');
                                  $isLow = (int) $r['Quantity'] <= 10;
                                  $statusClass = 'badge-' . strtolower(str_replace(' ', '-', $r['Availability_Status']));
                                  $pricePer = $r['Price_Per'] ? ' / ' . $r['Price_Per'] : '';
                                  ?>
                                  <tr class="inv-row"
                                    data-category="<?= htmlspecialchars(strtolower($r['Category_Name'] ?? '')) ?>"
                                    data-status="<?= htmlspecialchars(strtolower($r['Availability_Status'] ?? '')) ?>"
                                    data-search="<?= htmlspecialchars(strtolower(($r['Generic_Name'] ?? '') . ' ' . ($r['Brand_Name'] ?? '') . ' ' . ($r['Category_Name'] ?? '') . ' ' . ($r['Inventory_ID'] ?? ''))) ?>">
                                    <td><span class="text-muted fw-mono">#<?= htmlspecialchars($r['Inventory_ID']) ?></span>
                                    </td>
                                    <td>
                                      <div class="fw-bold text-dark"><?= htmlspecialchars($r['Generic_Name']) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($r['Brand_Name'] ?? 'Generics') ?></td>
                                    <td><span
                                        class="text-secondary"><?= htmlspecialchars($r['Category_Name'] ?? 'Uncategorized') ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($dFull) ?></td>
                                    <td class="fw-semibold text-success">₱<?= number_format($r['Price'], 2) ?><span
                                        class="text-muted small fw-normal"><?= htmlspecialchars($pricePer) ?></span></td>
                                    <td>
                                      <strong class="<?= $isLow ? 'text-danger' : 'text-dark' ?>">
                                        <?= htmlspecialchars($r['Quantity']) ?>
                                      </strong>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($r['Expiry_date'])) ?></td>
                                    <td>
                                      <span class="badge-status <?= $statusClass ?>">
                                        <?= htmlspecialchars($r['Availability_Status']) ?>
                                      </span>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                  <td colspan="9" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-muted"></i>
                                    No inventory items recorded.
                                  </td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <!-- SECTION 2: SALES MOVEMENT & POS TRANSACTIONS TABLE -->
                    <div id="salesReportSection">
                      <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                          <span class="material-symbols-outlined text-success"
                            style="font-size: 1.25rem;">receipt_long</span>
                          Point of Sale (POS) Movement &amp; Transactions
                        </h6>
                        <span class="text-muted small"><?= count($salesReport) ?> transactions recorded</span>
                      </div>

                      <div class="card card-table border">
                        <div class="table-responsive rounded">
                          <table class="table table-hover align-middle mb-0 report-table">
                            <thead class="table-head">
                              <tr>
                                <th>RECEIPT #</th>
                                <th>CUSTOMER NAME</th>
                                <th>DATE / TIME</th>
                                <th>ITEMS SOLD</th>
                                <th>SUBTOTAL (₱)</th>
                                <th>DISCOUNT</th>
                                <th>GRAND TOTAL (₱)</th>
                                <th>PAYMENT</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($salesReport)): ?>
                                <?php foreach ($salesReport as $s): ?>
                                  <tr class="sale-row"
                                    data-search="<?= htmlspecialchars(strtolower(($s['Receipt_No'] ?? '') . ' ' . ($s['Customer_Name'] ?? '') . ' ' . ($s['Payment_Method'] ?? ''))) ?>">
                                    <td><span
                                        class="fw-mono fw-bold text-dark"><?= htmlspecialchars($s['Receipt_No']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($s['Customer_Name']) ?></td>
                                    <td><?= date('M d, Y h:i A', strtotime($s['Sale_Date'])) ?></td>
                                    <td><?= $s['total_qty'] ?? 1 ?> units (<?= $s['total_items'] ?? 1 ?> types)</td>
                                    <td>₱<?= number_format($s['Subtotal'], 2) ?></td>
                                    <td>
                                      <?php if ($s['Discount_Amount'] > 0): ?>
                                        <span
                                          class="text-warning fw-semibold">-₱<?= number_format($s['Discount_Amount'], 2) ?>
                                          (<?= htmlspecialchars($s['Discount_Type']) ?>)</span>
                                      <?php else: ?>
                                        <span class="text-muted">None</span>
                                      <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-success">₱<?= number_format($s['Grand_Total'], 2) ?></td>
                                    <td><span
                                        class="badge bg-light text-dark border"><?= htmlspecialchars($s['Payment_Method'] ?? 'Cash') ?></span>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                  <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-receipt fs-2 d-block mb-2 text-muted"></i>
                                    No POS sales recorded yet. Process checkouts in the POS counter to track sales
                                    movements.
                                  </td>
                                </tr>
                              <?php endif; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>

                    <!-- Document Footer in Print -->
                    <div class="mt-4 pt-3 border-top text-center text-muted small">
                      This document was generated automatically by MediFind_RocketLabs Pharmacy Management System
                      &bull;
                      <?= date('Y') ?>
                    </div>

                  </div> <!-- End #reportPrintArea -->
                </div> <!-- End #reportScrollArea -->

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Stats Hero Collapse (same behavior as Manage Inventory)
    function toggleStatsCards() {
      const hero = document.getElementById('statsHero');
      const label = document.getElementById('statsToggleLabel');
      const chevron = document.getElementById('statsChevron');
      const isVisible = !hero.classList.contains('collapsed');

      hero.classList.toggle('collapsed', isVisible);
      if (label) label.textContent = isVisible ? 'Expand' : 'Collapse';
      if (chevron) chevron.classList.toggle('rotated', isVisible);
    }
  </script>

  <!-- Reports & PDF Export Module -->
  <script type="module" src="../07_Assets/js/reports.js"></script>
</body>

</html>