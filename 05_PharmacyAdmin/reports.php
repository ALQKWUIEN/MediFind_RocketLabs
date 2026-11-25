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

// Logged-in pharmacist name for formal report letterhead & signature block
$generatedByName = trim((string)($_SESSION['fname'] ?? $_SESSION['username'] ?? 'Pharmacist'));
try {
  $uStmt = $pdo->prepare("SELECT First_name, Last_name, Username FROM view_01_users WHERE User_ID = ? LIMIT 1");
  $uStmt->execute([$_SESSION['user_id']]);
  $uRow = $uStmt->fetch(PDO::FETCH_ASSOC);
  if ($uRow) {
    $full = trim(($uRow['First_name'] ?? '') . ' ' . ($uRow['Last_name'] ?? ''));
    $generatedByName = $full !== '' ? $full : ($uRow['Username'] ?? $generatedByName);
  }
} catch (Exception $e) {
  // Keep session fallback
}
$pharmacyDisplayName = trim((string)($_SESSION['pharmacy_name'] ?? 'MediFind Partner Pharmacy'));
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
  <script src="../07_Assets/css/js/sidebar_and_topbar.js?v=<?= time() ?>"></script>
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

    /* Page Background matching Dashboard (--mf-bg) */
    html,
    body[data-active="reports"],
    body[data-active="reports"] .main-panel,
    body[data-active="reports"] .content {
      background-color: var(--mf-bg, #f5f6f8) !important;
    }

    /* Report View Tabs */
    .report-view-tabs {
      display: inline-flex;
      flex-wrap: wrap;
      gap: 6px;
      background: #f1f5f9;
      padding: 4px;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
    }

    .report-tab-btn {
      border: none;
      background: transparent;
      color: #475569;
      font-size: 0.82rem;
      font-weight: 600;
      padding: 6px 14px;
      border-radius: 9px;
      cursor: pointer;
      transition: all 0.16s ease;
      white-space: nowrap;
    }

    .report-tab-btn.active {
      background: #1d9e75;
      color: #ffffff;
      box-shadow: 0 2px 6px rgba(29, 158, 117, 0.22);
    }

    /* Formal PDF Letterhead, Executive KPI Strip & Signature Block (hidden on screen, shown during PDF export & print) */
    .pdf-only-block {
      display: none;
    }

    .pdf-exporting .pdf-only-block {
      display: block;
    }

    .pdf-exporting .pdf-letterhead {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      border-bottom: 2px solid #1d9e75;
      padding-bottom: 10px;
      margin-bottom: 12px;
    }

    .pdf-exporting .pdf-kpi-strip {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
      margin-bottom: 14px;
    }

    .pdf-kpi-box {
      background: #f8fafc;
      border: 1px solid #d1ded8;
      border-radius: 8px;
      padding: 8px 10px;
    }

    .pdf-kpi-label {
      font-size: 7.5pt;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      color: #64748b;
      font-weight: 700;
    }

    .pdf-kpi-val {
      font-size: 11.5pt;
      font-weight: 700;
      color: #0f172a;
      margin-top: 2px;
    }

    .pdf-exporting .pdf-signature-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 48px;
      margin-top: 24px;
      padding-top: 12px;
      page-break-inside: avoid;
      break-inside: avoid;
    }

    .pdf-sig-line {
      border-top: 1px solid #94a3b8;
      margin-top: 28px;
      padding-top: 5px;
      font-size: 8.5pt;
      color: #334155;
    }

    /* Table readability, zebra striping, right-aligned numeric cols & PDF page-break safety */
    .report-table tbody tr:nth-child(even) {
      background-color: #fafdfb;
    }

    .report-table .num-col {
      text-align: right !important;
      white-space: nowrap;
    }

    .report-table .nowrap-col {
      white-space: nowrap;
    }

    .pdf-exporting {
      background: #ffffff !important;
      font-family: "Plus Jakarta Sans", "DejaVu Sans", Arial, sans-serif !important;
      color: #0f172a !important;
    }

    .pdf-exporting .report-table {
      width: 100% !important;
      font-size: 8.5pt !important;
      border-collapse: collapse !important;
    }

    .pdf-exporting .report-table thead {
      display: table-header-group !important;
      position: static !important;
    }

    .pdf-exporting .report-table th,
    .pdf-exporting .report-table td {
      padding: 5px 7px !important;
      vertical-align: middle !important;
    }

    .pdf-exporting .report-table tr,
    .pdf-exporting .pdf-section-header {
      page-break-inside: avoid !important;
      break-inside: avoid !important;
    }

    .pdf-exporting .pdf-pos-section {
      page-break-before: auto;
      margin-top: 18px;
    }

    @media print {
      @page {
        size: A4 landscape;
        margin: 12mm;
      }
      .pdf-only-block {
        display: block !important;
      }
      .report-table thead {
        display: table-header-group;
      }
      .report-table tr {
        page-break-inside: avoid;
      }
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
                    <div class="card-value" id="kpiTotalSalesValue">₱<?= number_format($totalSalesRevenue, 2) ?></div>
                    <div class="card-sub" id="kpiTotalSalesCount"><?= count($salesReport) ?> completed POS checkouts</div>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- ══ REPORT CONTAINER & TOOLBAR (Matching Manage Inventory) ══ -->
          <div class="inventoryTable mt-2">
            <div class="col-12">
              <div class="stat-card-table">

                <!-- Table Header: Title & Report View Tabs -->
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                  <div class="d-flex align-items-center gap-3 flex-wrap">
                    <h5 class="fw-bold mb-0 text-dark">Pharmacy Reports</h5>
                    <div class="report-view-tabs" id="reportViewTabs" role="tablist">
                      <button type="button" class="report-tab-btn active" data-report-tab="all">All Reports</button>
                      <button type="button" class="report-tab-btn" data-report-tab="inventory">Inventory Stock Status</button>
                      <button type="button" class="report-tab-btn" data-report-tab="sales">POS Sales &amp; Movement</button>
                    </div>
                  </div>
                  <span class="badge bg-light text-secondary border" id="reportRecordBadge"><?= count($inventoryReport) + count($salesReport) ?> records listed</span>
                </div>

                <!-- Toolbars: Clean flex layout with tab-aware filters -->
                <div class="toolbars mb-3">
                  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

                    <div class="d-flex flex-wrap align-items-center gap-2 flex-grow-1" style="min-width: 260px;">
                      <!-- Search Input -->
                      <div class="input-group searchbar-group" style="max-width: 280px; flex: 1 1 210px;">
                        <span class="input-group-text search-icon bg-white border-end-0">
                          <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="reportSearchInput" class="form-control searchbar border-start-0 ps-0"
                          placeholder="Search report items...">
                      </div>

                      <!-- Date Range Filter (for POS Sales) -->
                      <div id="reportDateWrap" style="min-width: 150px;">
                        <select id="reportDatePreset" class="form-select border-secondary-subtle">
                          <option value="all">All Time</option>
                          <option value="today">Today</option>
                          <option value="7days">Last 7 Days</option>
                          <option value="month">This Month</option>
                          <option value="custom">Custom Range</option>
                        </select>
                      </div>

                      <!-- Custom Date Inputs (inline beside preset) -->
                      <div id="customDateRangeRow" class="align-items-center gap-2 bg-light border rounded-3 px-2 py-1" style="display:none;">
                        <span class="small fw-semibold text-muted">From</span>
                        <input type="date" id="reportDateStart" class="form-control form-control-sm border-0 bg-white" style="width: 132px;" />
                        <span class="small fw-semibold text-muted">To</span>
                        <input type="date" id="reportDateEnd" class="form-control form-control-sm border-0 bg-white" style="width: 132px;" />
                      </div>

                      <!-- Status Filter (for Inventory) -->
                      <div id="reportStatusWrap" style="min-width: 155px;">
                        <select id="reportStatusFilter" class="form-select border-secondary-subtle">
                          <option value="all">All Statuses</option>
                          <option value="low stock">Low Stock (&le; 10)</option>
                          <option value="available">Available Stock</option>
                          <option value="out of stock">Out of Stock</option>
                          <option value="expired">Expired</option>
                        </select>
                      </div>
                    </div>

                    <!-- Action Buttons: Download PDF, Export CSV, Hide Summary -->
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                      <button type="button" id="btnExportReportPdf"
                        class="btn scanner-btn-orange px-3 rounded-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined" style="font-size: 1.15rem;">picture_as_pdf</span>
                        <span class="text-nowrap">Download PDF</span>
                      </button>
                      <button type="button" id="btnExportReportCsv"
                        class="btn btn-outline-success px-3 rounded-3 d-flex align-items-center gap-1 fw-semibold"
                        style="border-color:#1d9e75;color:#0f6e56;">
                        <span class="material-symbols-outlined" style="font-size: 1.15rem;">download</span>
                        <span class="text-nowrap">Export CSV</span>
                      </button>

                      <!-- Hide Summary Button -->
                      <button class="btn btn-outline-secondary px-3 rounded-3 d-flex align-items-center gap-1"
                        id="statsToggleBtn" type="button" onclick="toggleStatsCards()" title="Toggle summary KPI cards">
                        <span id="statsChevron" class="material-symbols-outlined"
                          style="color: #87a199; font-size: 1.1rem; transition: transform 0.35s ease;">expand</span>
                        <span id="statsToggleLabel" class="d-none d-sm-inline">Hide Summary</span>
                      </button>
                    </div>

                  </div>
                </div>

                <!-- Scrollable region (PDF export still captures #reportPrintArea at full height) -->
                <div id="reportScrollArea">
                  <!-- Printable Report Area -->
                  <div id="reportPrintArea">

                    <!-- FORMAL DOCUMENT LETTERHEAD (Shown in PDF & Print) -->
                    <div class="pdf-only-block pdf-letterhead">
                      <div class="d-flex align-items-center gap-3">
                        <img src="../07_Assets/images/logo.png" alt="MediFind Logo" style="width:42px;height:42px;object-fit:contain;" />
                        <div>
                          <div style="font-size:13pt;font-weight:800;color:#0f172a;line-height:1.15;">
                            Medi<span style="color:#1d9e75;">Find</span> &mdash; <?= htmlspecialchars($pharmacyDisplayName) ?>
                          </div>
                          <div style="font-size:10pt;font-weight:700;color:#1d9e75;margin-top:2px;">
                            Pharmacy Inventory &amp; Sales Report
                          </div>
                        </div>
                      </div>
                      <div style="text-align:right;font-size:8pt;color:#475569;line-height:1.45;">
                        <div><strong>Date Range:</strong> <span id="pdfDateRangeLabel">All Time</span></div>
                        <div><strong>Generated:</strong> <?= date('M d, Y h:i A') ?></div>
                        <div><strong>Generated by:</strong> <?= htmlspecialchars($generatedByName) ?></div>
                      </div>
                    </div>

                    <!-- EXECUTIVE KPI SUMMARY STRIP (Shown in PDF & Print) -->
                    <div class="pdf-only-block pdf-kpi-strip">
                      <div class="pdf-kpi-box">
                        <div class="pdf-kpi-label">Total Stock Items</div>
                        <div class="pdf-kpi-val"><?= number_format($totalInventoryCount) ?></div>
                      </div>
                      <div class="pdf-kpi-box">
                        <div class="pdf-kpi-label">Low Stock Warnings</div>
                        <div class="pdf-kpi-val" style="color:#b45309;"><?= number_format($totalLowStock) ?></div>
                      </div>
                      <div class="pdf-kpi-box">
                        <div class="pdf-kpi-label">Total Inventory Value</div>
                        <div class="pdf-kpi-val" style="color:#0f6e56;">₱<?= number_format($totalInventoryValue, 2) ?></div>
                      </div>
                      <div class="pdf-kpi-box">
                        <div class="pdf-kpi-label">Total Recorded Sales</div>
                        <div class="pdf-kpi-val" id="pdfKpiSalesVal" style="color:#d97706;">₱<?= number_format($totalSalesRevenue, 2) ?></div>
                        <div style="font-size:7pt;color:#64748b;" id="pdfKpiSalesCount"><?= count($salesReport) ?> completed transactions</div>
                      </div>
                    </div>

                    <!-- SECTION 1: INVENTORY STOCK STATUS TABLE -->
                    <div id="inventoryReportSection" class="mb-5">
                      <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom pdf-section-header">
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                          <span class="material-symbols-outlined text-success"
                            style="font-size: 1.25rem;">inventory</span>
                          Inventory Stock Status Report
                        </h6>
                        <span class="text-muted small" id="invSectionCount"><?= count($inventoryReport) ?> records listed</span>
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
                                <th class="num-col">PRICE (₱)</th>
                                <th class="num-col">QTY</th>
                                <th class="nowrap-col">EXPIRY DATE</th>
                                <th class="nowrap-col">STATUS</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if (!empty($inventoryReport)): ?>
                                <?php foreach ($inventoryReport as $r): ?>
                                  <?php
                                  $dFull = ($r['Dosage_Value'] ? $r['Dosage_Value'] . ' ' . $r['Unit_Name'] : '') . ($r['Dosage_Form'] ? ' (' . $r['Dosage_Form'] . ')' : '');
                                  $isLow = (int) $r['Quantity'] <= 10;
                                  $statusClass = 'badge-' . strtolower(str_replace(' ', '-', $r['Availability_Status']));
                                  ?>
                                  <tr class="inv-row"
                                    data-category="<?= htmlspecialchars(strtolower($r['Category_Name'] ?? '')) ?>"
                                    data-status="<?= htmlspecialchars(strtolower($r['Availability_Status'] ?? '')) ?>"
                                    data-search="<?= htmlspecialchars(strtolower(($r['Generic_Name'] ?? '') . ' ' . ($r['Brand_Name'] ?? '') . ' ' . ($r['Category_Name'] ?? '') . ' ' . ($r['Inventory_ID'] ?? ''))) ?>">
                                    <td class="nowrap-col"><span class="text-muted fw-mono">#<?= htmlspecialchars($r['Inventory_ID']) ?></span>
                                    </td>
                                    <td>
                                      <div class="fw-bold text-dark"><?= htmlspecialchars($r['Generic_Name']) ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($r['Brand_Name'] ?? 'Generics') ?></td>
                                    <td><span
                                        class="text-secondary"><?= htmlspecialchars($r['Category_Name'] ?? 'Uncategorized') ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($dFull) ?></td>
                                    <td class="num-col fw-semibold text-success">₱<?= number_format($r['Price'], 2) ?></td>
                                    <td class="num-col">
                                      <strong class="<?= $isLow ? 'text-danger' : 'text-dark' ?>">
                                        <?= htmlspecialchars($r['Quantity']) ?>
                                      </strong>
                                    </td>
                                    <td class="nowrap-col"><?= date('M d, Y', strtotime($r['Expiry_date'])) ?></td>
                                    <td class="nowrap-col">
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
                    <div id="salesReportSection" class="pdf-pos-section">
                      <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom pdf-section-header">
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                          <span class="material-symbols-outlined text-success"
                            style="font-size: 1.25rem;">receipt_long</span>
                          Point of Sale (POS) Movement &amp; Transactions
                        </h6>
                        <span class="text-muted small" id="salesSectionCount"><?= count($salesReport) ?> transactions recorded</span>
                      </div>

                      <div class="card card-table border">
                        <div class="table-responsive rounded">
                          <table class="table table-hover align-middle mb-0 report-table">
                            <thead class="table-head">
                              <tr>
                                <th class="nowrap-col">RECEIPT #</th>
                                <th>CUSTOMER NAME</th>
                                <th class="nowrap-col">DATE / TIME</th>
                                <th>ITEMS SOLD</th>
                                <th class="num-col">SUBTOTAL (₱)</th>
                                <th class="num-col">DISCOUNT (₱)</th>
                                <th class="num-col">GRAND TOTAL (₱)</th>
                                <th class="nowrap-col">PAYMENT</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $sumUnits = 0;
                              $sumSubtotal = 0.0;
                              $sumDiscount = 0.0;
                              $sumGrand = 0.0;
                              ?>
                              <?php if (!empty($salesReport)): ?>
                                <?php foreach ($salesReport as $s): ?>
                                  <?php
                                  $qtySold = (int) ($s['total_qty'] ?? 1);
                                  $itemTypes = (int) ($s['total_items'] ?? 1);
                                  $subAmt = (float) ($s['Subtotal'] ?? 0);
                                  $discAmt = (float) ($s['Discount_Amount'] ?? 0);
                                  $grandAmt = (float) ($s['Grand_Total'] ?? 0);
                                  $sumUnits += $qtySold;
                                  $sumSubtotal += $subAmt;
                                  $sumDiscount += $discAmt;
                                  $sumGrand += $grandAmt;
                                  $saleIsoDate = date('Y-m-d', strtotime($s['Sale_Date']));
                                  ?>
                                  <tr class="sale-row"
                                    data-sale-date="<?= htmlspecialchars($saleIsoDate) ?>"
                                    data-qty="<?= $qtySold ?>"
                                    data-subtotal="<?= $subAmt ?>"
                                    data-discount="<?= $discAmt ?>"
                                    data-grand="<?= $grandAmt ?>"
                                    data-search="<?= htmlspecialchars(strtolower(($s['Receipt_No'] ?? '') . ' ' . ($s['Customer_Name'] ?? '') . ' ' . ($s['Payment_Method'] ?? ''))) ?>">
                                    <td class="nowrap-col"><span
                                        class="fw-mono fw-bold text-dark"><?= htmlspecialchars($s['Receipt_No']) ?></span>
                                    </td>
                                    <td><?= htmlspecialchars($s['Customer_Name']) ?></td>
                                    <td class="nowrap-col"><?= date('M d, Y h:i A', strtotime($s['Sale_Date'])) ?></td>
                                    <td class="nowrap-col">
                                      <?= $qtySold ?> <?= $qtySold === 1 ? 'unit' : 'units' ?>
                                      (<?= $itemTypes ?> <?= $itemTypes === 1 ? 'item' : 'items' ?>)
                                    </td>
                                    <td class="num-col">₱<?= number_format($subAmt, 2) ?></td>
                                    <td class="num-col">
                                      <?php if ($discAmt > 0): ?>
                                        <span class="text-warning fw-semibold">-₱<?= number_format($discAmt, 2) ?>
                                          (<?= htmlspecialchars($s['Discount_Type']) ?>)</span>
                                      <?php else: ?>
                                        <span class="text-muted">₱0.00</span>
                                      <?php endif; ?>
                                    </td>
                                    <td class="num-col fw-bold text-success">₱<?= number_format($grandAmt, 2) ?></td>
                                    <td class="nowrap-col"><span
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
                            <?php if (!empty($salesReport)): ?>
                            <tfoot id="salesTotalsFooter">
                              <tr style="background:#f0fdf4;border-top:2px solid #86efac;font-weight:700;">
                                <td colspan="3" class="text-dark">Totals (Filtered Transactions)</td>
                                <td class="nowrap-col text-dark" id="footTotalUnits"><?= number_format($sumUnits) ?> <?= $sumUnits === 1 ? 'unit' : 'units' ?></td>
                                <td class="num-col text-dark" id="footTotalSubtotal">₱<?= number_format($sumSubtotal, 2) ?></td>
                                <td class="num-col text-warning" id="footTotalDiscount">-₱<?= number_format($sumDiscount, 2) ?></td>
                                <td class="num-col text-success" id="footTotalGrand">₱<?= number_format($sumGrand, 2) ?></td>
                                <td></td>
                              </tr>
                            </tfoot>
                            <?php endif; ?>
                          </table>
                        </div>
                      </div>
                    </div>

                    <!-- Formal Signature Block (Shown in PDF & Print) -->
                    <div class="pdf-only-block pdf-signature-grid">
                      <div>
                        <div class="pdf-sig-line">
                          <strong>Prepared by:</strong> <?= htmlspecialchars($generatedByName) ?><br />
                          <span style="color:#64748b;">Licensed Pharmacist / Pharmacy Staff</span>
                        </div>
                      </div>
                      <div>
                        <div class="pdf-sig-line">
                          <strong>Verified by:</strong> ___________________________<br />
                          <span style="color:#64748b;">Pharmacy Manager / Authorized Signatory</span>
                        </div>
                      </div>
                    </div>

                    <!-- Document Footer in Print -->
                    <div class="mt-4 pt-3 border-top text-center text-muted small">
                      This document was generated automatically by MediFind Pharmacy Management System
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
      if (label) label.textContent = isVisible ? 'Show Summary' : 'Hide Summary';
      if (chevron) chevron.classList.toggle('rotated', isVisible);
    }
  </script>

  <!-- Reports & PDF Export Module -->
  <script type="module" src="../07_Assets/js/reports.js?v=<?= time() ?>"></script>
</body>

</html>