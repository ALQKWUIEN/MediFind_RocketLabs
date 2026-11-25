<?php
// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Global Variable and DB Connection
include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

require '../02_Actions/04_System-Admin-CRUD/select-count.php';
require '../02_Actions/04_System-Admin-CRUD/reports.php';

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
  <title>System Reports & LGU Monitoring - MediFind Malaybalay</title>

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Core Libraries (Bootstrap 5.3.0 & Bootstrap Icons) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Chart.js (Pinned CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

  <!-- Design System & Theme Styles (User-Side Source of Truth) -->
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/theme-tokens.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/admin-buttons.css" />
  <link rel="stylesheet" href="../07_Assets/css/03_SystemAdminCSS/dashboard.css" />
  <link rel="stylesheet" href="../07_Assets/css/03_SystemAdminCSS/reports-style.css?v=<?= time() ?>" />

  <!-- Critical Layout Inline Fallback Styles -->
  <style>
    .reports-admin-container {
      padding: 1.25rem 1.5rem 2.5rem 1.5rem;
      width: 100%;
      box-sizing: border-box;
    }
    .reports-toolbar-sticky,
    .reports-toolbar-card {
      position: sticky;
      top: 0;
      z-index: 1020;
      background: rgba(255, 255, 255, 0.96);
      backdrop-filter: blur(10px);
      border: 1px solid #e5e7eb;
      border-radius: 18px;
      padding: 1.15rem 1.35rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
      margin-bottom: 1.25rem;
    }
    .report-tabs,
    .report-tabs-bar {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 16px;
      padding: 5px;
      display: inline-flex;
      gap: 4px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
      margin-bottom: 1.25rem;
    }
    .rtab,
    .report-tab-btn {
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.88rem;
      color: #6b7280;
      padding: 8px 18px;
      border: none;
      background: transparent;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
      text-decoration: none;
    }
    .rtab:hover,
    .report-tab-btn:hover {
      color: #23423a;
      background: #f3f4f6;
    }
    .rtab.active,
    .report-tab-btn.active {
      background: #1d9e75 !important;
      color: #ffffff !important;
      box-shadow: 0 2px 8px rgba(29, 158, 117, 0.3);
    }
    .tab-badge {
      background: rgba(0, 0, 0, 0.08);
      color: inherit;
      font-size: 11px;
      font-weight: 700;
      padding: 2px 8px;
      border-radius: 999px;
    }
    .rtab.active .tab-badge,
    .report-tab-btn.active .tab-badge {
      background: rgba(255, 255, 255, 0.25);
      color: #ffffff;
    }
    .kpi-card {
      background: #ffffff;
      border: 1px solid #e5e7eb;
      border-radius: 18px;
      padding: 1.15rem 1.2rem;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      height: 100%;
      min-height: 135px;
      box-sizing: border-box;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }
    .kpi-header {
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      gap: 10px;
      margin-bottom: 0.65rem;
      width: 100% !important;
    }
    .kpi-icon-wrap {
      width: 42px !important;
      min-width: 42px !important;
      max-width: 42px !important;
      height: 42px !important;
      border-radius: 12px;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      flex-shrink: 0 !important;
      font-size: 20px;
    }
    .icon-teal { background: #eef9f4; color: #1d9e75; }
    .icon-emerald { background: #dcfce7; color: #15803d; }
    .icon-amber { background: #fef3c7; color: #b45309; }
    .icon-rose { background: #fee2e2; color: #b91c1c; }
    .icon-blue { background: #e0f2fe; color: #0369a1; }
    .icon-purple { background: #f3e8ff; color: #7e22ce; }
    .kpi-label {
      font-size: 0.78rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #6b7280;
    }
    .kpi-value {
      font-size: 1.85rem;
      font-weight: 800;
      color: #1f2937;
      font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
      line-height: 1.1;
      margin-bottom: 0.35rem;
    }
    .kpi-sub {
      font-size: 0.78rem;
      font-weight: 500;
      color: #6b7280;
      display: flex;
      align-items: center;
      gap: 4px;
    }
  </style>

  <!-- Navigation Scripts -->
  <script src="../07_Assets/css/js/sidebar_and_topbar.js?v=<?= time() ?>"></script>

  <!-- System Reports Mock Data Module -->
  <script src="../07_Assets/js/mock/system_reports.js?v=<?= time() ?>"></script>

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>
</head>

<body data-active="04" class="lgu-dashboard-page">

  <div class="wrapper d-flex align-items-stretch">
    <!-- Dynamic System Sidebar -->
    <div id="system-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <!-- Dynamic Topbar -->
      <div id="topbar-container"></div>

      <!-- Main Scrollable Content Area -->
      <div class="content flex-grow-1">
        <div class="reports-admin-container">

          <!-- ═══════════════════════════════════════════════════════════ -->
          <!-- 1. STICKY TOP TOOLBAR                                       -->
          <!-- ═══════════════════════════════════════════════════════════ -->
          <div class="reports-toolbar-sticky" id="reportsStickyToolbar">
            <div class="toolbar-inner">

              <!-- Page Title & Subtitle -->
              <div class="toolbar-title-group">
                <h4>
                  <span class="material-symbols-outlined" style="color: #1d9e75; font-size: 26px;">analytics</span>
                  System Reports & LGU Monitoring
                </h4>
                <p>Comprehensive public health intelligence, facility compliance, and supply resilience for Malaybalay City</p>
              </div>

              <!-- Controls: Date Range, Barangay & Export Actions -->
              <div class="toolbar-controls">
                <!-- Date Range Selector -->
                <div class="d-flex align-items-center gap-1">
                  <span class="material-symbols-outlined text-muted" style="font-size: 18px;">calendar_today</span>
                  <select id="reportDateRange" class="toolbar-select">
                    <option value="today">Today</option>
                    <option value="7days">Last 7 Days</option>
                    <option value="30days">Last 30 Days</option>
                    <option value="month" selected>This Month</option>
                  </select>
                </div>

                <!-- Barangay Dropdown -->
                <div class="d-flex align-items-center gap-1">
                  <span class="material-symbols-outlined text-muted" style="font-size: 18px;">location_city</span>
                  <select id="reportBarangay" class="toolbar-select">
                    <option value="all">All Barangays</option>
                    <option value="Poblacion">Poblacion</option>
                    <option value="Casisang">Casisang</option>
                    <option value="Sumpong">Sumpong</option>
                    <option value="Aglayan">Aglayan</option>
                    <option value="Bangcud">Bangcud</option>
                    <option value="Impalambong">Impalambong</option>
                    <option value="Kalasungay">Kalasungay</option>
                    <option value="San Jose">San Jose</option>
                    <option value="Dalwangan">Dalwangan</option>
                    <option value="Canayan">Canayan</option>
                    <option value="Laguitas">Laguitas</option>
                  </select>
                </div>

                <!-- Export Buttons (UI Only) -->
                <div class="btn-export-group ms-lg-2">
                  <button type="button" class="btn-toolbar-action" id="btnDownloadPdf" title="Download PDF Report">
                    <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                    <span>PDF</span>
                  </button>
                  <button type="button" class="btn-toolbar-action" id="btnDownloadCsv" title="Download CSV Spreadsheet">
                    <i class="bi bi-file-earmark-spreadsheet-fill text-success"></i>
                    <span>CSV</span>
                  </button>
                  <button type="button" class="btn-toolbar-action" id="btnPrintReport" title="Print Report">
                    <i class="bi bi-printer-fill text-primary"></i>
                    <span>Print</span>
                  </button>
                </div>

                <!-- Input Mock Data Action Button -->
                <button type="button" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 ms-lg-2 rounded-3 px-3 py-1 fw-semibold text-white shadow-sm" id="btnOpenInputMockModal" onclick="openInputModalWithTab('user')">
                  <i class="bi bi-plus-circle-fill"></i>
                  <span>Input Mock Data</span>
                </button>
              </div>

            </div>
          </div>

          <!-- ═══════════════════════════════════════════════════════════ -->
          <!-- TABS NAVIGATION                                             -->
          <!-- ═══════════════════════════════════════════════════════════ -->
          <div class="report-tabs-wrapper">
            <div class="report-tabs-bar">
              <button type="button" class="report-tab-btn rtab active" onclick="switchTab('overview', this)">
                <i class="bi bi-pie-chart-fill"></i> Overview
              </button>
              <button type="button" class="report-tab-btn rtab" onclick="switchTab('users', this)">
                <i class="bi bi-people-fill"></i> Users
                <span class="tab-badge" id="tabUsersBadge">38</span>
              </button>
              <button type="button" class="report-tab-btn rtab" onclick="switchTab('pharmacies', this)">
                <i class="bi bi-building"></i> Pharmacies
                <span class="tab-badge" id="tabPharmaciesBadge">12</span>
              </button>
              <button type="button" class="report-tab-btn rtab" onclick="switchTab('stock', this)">
                <i class="bi bi-box-seam-fill"></i> Stock Availability
                <span class="badge bg-danger text-white rounded-pill ms-1" style="font-size: 10px; padding: 2px 7px;">LGU Alert</span>
              </button>
            </div>
          </div>

          <!-- ═══════════════════════════════════════════════════════════ -->
          <!-- REPORT CONTENT WRAPPER                                      -->
          <!-- ═══════════════════════════════════════════════════════════ -->
          <div class="report-content">

            <!-- Skeleton Loader Placeholder (Shown briefly during filter transitions) -->
            <div id="reportsSkeletonLoader" style="display: none;">
              <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-6 mb-4">
                <div class="col"><div class="kpi-card skeleton-box" style="height: 135px;"></div></div>
                <div class="col"><div class="kpi-card skeleton-box" style="height: 135px;"></div></div>
                <div class="col"><div class="kpi-card skeleton-box" style="height: 135px;"></div></div>
                <div class="col"><div class="kpi-card skeleton-box" style="height: 135px;"></div></div>
                <div class="col"><div class="kpi-card skeleton-box" style="height: 135px;"></div></div>
                <div class="col"><div class="kpi-card skeleton-box" style="height: 135px;"></div></div>
              </div>
              <div class="row g-3">
                <div class="col-md-6"><div class="rcard skeleton-box" style="height: 300px;"></div></div>
                <div class="col-md-6"><div class="rcard skeleton-box" style="height: 300px;"></div></div>
              </div>
            </div>

            <!-- ═════════════════════════════════════════════════════════ -->
            <!-- TAB 1: OVERVIEW                                           -->
            <!-- ═════════════════════════════════════════════════════════ -->
            <div id="tab-overview" class="tab-section active">

              <!-- 6 KPI Metric Cards (Responsive Grid: 6 columns on desktop) -->
              <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-6 mb-4">
                <!-- 1. Total Users -->
                <div class="col">
                  <div class="kpi-card">
                    <div>
                      <div class="kpi-header">
                        <span class="kpi-label">Total Users</span>
                        <div class="kpi-icon-wrap icon-teal">
                          <i class="bi bi-people-fill"></i>
                        </div>
                      </div>
                      <div class="kpi-value" id="kpiTotalUsers">38</div>
                    </div>
                    <div class="kpi-sub">
                      <i class="bi bi-check-circle-fill text-success"></i> Registered accounts
                    </div>
                  </div>
                </div>

                <!-- 2. New Users -->
                <div class="col">
                  <div class="kpi-card">
                    <div>
                      <div class="kpi-header">
                        <span class="kpi-label">New Users</span>
                        <div class="kpi-icon-wrap icon-emerald">
                          <i class="bi bi-person-plus-fill"></i>
                        </div>
                      </div>
                      <div class="kpi-value" id="kpiNewUsers">6</div>
                    </div>
                    <div class="kpi-sub sub-positive" id="kpiNewUsersSub">
                      <i class="bi bi-arrow-up-short"></i> +18.7% vs last month
                    </div>
                  </div>
                </div>

                <!-- 3. Active Pharmacies -->
                <div class="col">
                  <div class="kpi-card">
                    <div>
                      <div class="kpi-header">
                        <span class="kpi-label">Active Pharmacies</span>
                        <div class="kpi-icon-wrap icon-blue">
                          <i class="bi bi-hospital-fill"></i>
                        </div>
                      </div>
                      <div class="kpi-value" id="kpiActivePharmacies">8</div>
                    </div>
                    <div class="kpi-sub">
                      <i class="bi bi-patch-check-fill text-primary"></i> Licensed & operating
                    </div>
                  </div>
                </div>

                <!-- 4. Pending Approvals -->
                <div class="col">
                  <div class="kpi-card">
                    <div>
                      <div class="kpi-header">
                        <span class="kpi-label">Pending Approvals</span>
                        <div class="kpi-icon-wrap icon-amber">
                          <i class="bi bi-clock-history"></i>
                        </div>
                      </div>
                      <div class="kpi-value text-amber" id="kpiPendingApprovals">3</div>
                    </div>
                    <div class="kpi-sub sub-warning" id="kpiPendingSub">
                      <i class="bi bi-exclamation-circle-fill"></i> Needs LGU review
                    </div>
                  </div>
                </div>

                <!-- 5. Expired Stocks -->
                <div class="col">
                  <div class="kpi-card">
                    <div>
                      <div class="kpi-header">
                        <span class="kpi-label">Expired Stocks</span>
                        <div class="kpi-icon-wrap icon-rose">
                          <i class="bi bi-calendar-x-fill"></i>
                        </div>
                      </div>
                      <div class="kpi-value text-rose" id="kpiExpiredStocks">4</div>
                    </div>
                    <div class="kpi-sub sub-critical">
                      <i class="bi bi-shield-x"></i> Items flagged city-wide
                    </div>
                  </div>
                </div>

                <!-- 6. Low Stock Items -->
                <div class="col">
                  <div class="kpi-card">
                    <div>
                      <div class="kpi-header">
                        <span class="kpi-label">Low Stock Items</span>
                        <div class="kpi-icon-wrap icon-purple">
                          <i class="bi bi-box-seam-fill"></i>
                        </div>
                      </div>
                      <div class="kpi-value text-purple" id="kpiLowStocks">39</div>
                    </div>
                    <div class="kpi-sub">
                      <i class="bi bi-exclamation-triangle-fill text-warning"></i> Qty &le; 10 in inventory
                    </div>
                  </div>
                </div>
              </div>

          <!-- Charts Row 1: Users by Role (Donut) & Registrations Over Time (Line) -->
          <div class="row g-3 mb-4">
            <div class="col-lg-6">
              <div class="rcard">
                <div class="rcard-header">
                  <h6 class="rcard-title">
                    <i class="bi bi-pie-chart text-success"></i> Users by Role Distribution
                  </h6>
                  <span class="badge bg-light text-muted border">All Active Accounts</span>
                </div>
                <div class="chart-wrapper">
                  <canvas id="chartUsersByRole"></canvas>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="rcard">
                <div class="rcard-header">
                  <h6 class="rcard-title">
                    <i class="bi bi-graph-up-arrow text-primary"></i> Registrations Over Time
                  </h6>
                  <span class="badge bg-light text-muted border">6-Month Trend</span>
                </div>
                <div class="chart-wrapper">
                  <canvas id="chartRegistrations"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Charts Row 2: Pharmacies by Status (Donut) & Pharmacies by Barangay (Horizontal Bar) -->
          <div class="row g-3 mb-4">
            <div class="col-lg-5">
              <div class="rcard">
                <div class="rcard-header">
                  <h6 class="rcard-title">
                    <i class="bi bi-shield-check text-success"></i> Pharmacies by Approval Status
                  </h6>
                  <span class="badge bg-light text-muted border" id="pharmacyStatusTotalLabel">12 Total</span>
                </div>
                <div class="chart-wrapper">
                  <canvas id="chartPharmacyStatus"></canvas>
                </div>
              </div>
            </div>

            <div class="col-lg-7">
              <div class="rcard">
                <div class="rcard-header">
                  <h6 class="rcard-title">
                    <i class="bi bi-bar-chart-steps text-info"></i> Pharmacies by Barangay Coverage
                  </h6>
                  <span class="badge bg-light text-muted border">City Coverage</span>
                </div>
                <div class="chart-wrapper">
                  <canvas id="chartPharmacyByBarangay"></canvas>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Lists: Pending Approvals & Suspended Pharmacies -->
          <div class="row g-3">
            <!-- Pending Pharmacy Approvals List -->
            <div class="col-lg-6">
              <div class="rcard">
                <div class="rcard-header">
                  <h6 class="rcard-title">
                    <i class="bi bi-clock-history text-warning"></i> Pending Pharmacy Approvals
                  </h6>
                  <a class="rcard-link" onclick="switchTab('pharmacies', document.querySelectorAll('.rtab, .report-tab-btn')[2])">
                    View in table →
                  </a>
                </div>
                <div id="pendingApprovalsListContainer" class="d-flex flex-column gap-2">
                  <!-- Injected via JavaScript from mock data -->
                </div>
              </div>
            </div>

            <!-- Suspended Pharmacies List (Keeps empty state) -->
            <div class="col-lg-6">
              <div class="rcard">
                <div class="rcard-header">
                  <h6 class="rcard-title">
                    <i class="bi bi-slash-circle text-danger"></i> Suspended Pharmacies
                  </h6>
                  <span class="badge bg-light text-muted border">Enforcement</span>
                </div>
                <div id="suspendedPharmaciesListContainer">
                  <!-- Empty State or Injected Records -->
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- ═════════════════════════════════════════════════════════ -->
        <!-- TAB 2: USERS                                              -->
        <!-- ═════════════════════════════════════════════════════════ -->
        <div id="tab-users" class="tab-section">
          <!-- Summary Chips by Role -->
          <div class="summary-chips-row" id="userRoleChipsBar">
            <button type="button" class="summary-chip active" data-role="all">
              <span>All Roles</span>
              <span class="chip-count" id="chipCountAllUsers">38</span>
            </button>
            <button type="button" class="summary-chip" data-role="Patient">
              <i class="bi bi-person text-primary"></i>
              <span>Patients</span>
              <span class="chip-count" id="chipCountPatients">18</span>
            </button>
            <button type="button" class="summary-chip" data-role="Pharmacist">
              <i class="bi bi-capsule text-success"></i>
              <span>Pharmacists</span>
              <span class="chip-count" id="chipCountPharmacists">14</span>
            </button>
            <button type="button" class="summary-chip" data-role="Owner">
              <i class="bi bi-shop text-warning"></i>
              <span>Pharmacy Owners</span>
              <span class="chip-count" id="chipCountOwners">2</span>
            </button>
            <button type="button" class="summary-chip" data-role="Admin">
              <i class="bi bi-shield-lock text-secondary"></i>
              <span>System Admins</span>
              <span class="chip-count" id="chipCountAdmins">4</span>
            </button>
          </div>

          <!-- Users Table Card -->
          <div class="report-table-card">
            <div class="table-toolbar-inner">
              <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 450px;">
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                  </span>
                  <input type="text" id="usersSearchInput" class="form-control border-start-0 ps-0" placeholder="Search user by name, email, or barangay...">
                </div>
              </div>

              <div class="d-flex align-items-center gap-2">
                <select id="usersStatusFilter" class="form-select filter-select" style="min-width: 140px;">
                  <option value="all">All Status</option>
                  <option value="Active">Active</option>
                  <option value="Suspended">Suspended</option>
                </select>

                <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 rounded-3 px-3 fw-semibold text-nowrap" onclick="openInputModalWithTab('user')">
                  <i class="bi bi-person-plus-fill"></i>
                  <span>Input Mock User</span>
                </button>

                <span class="text-muted small text-nowrap ms-2" id="usersCounterLabel">Showing 38 users</span>
              </div>
            </div>

            <div class="table-scroll" id="usersTableScrollArea">
              <div class="table-responsive">
                <table class="section-table" id="usersTable">
                  <thead>
                    <tr>
                      <th style="width: 50px;">#</th>
                      <th class="sortable" data-col="name">Name <i class="bi bi-arrow-down-up sort-icon"></i></th>
                      <th class="sortable" data-col="role">Role <i class="bi bi-arrow-down-up sort-icon"></i></th>
                      <th class="sortable" data-col="status">Status <i class="bi bi-arrow-down-up sort-icon"></i></th>
                      <th class="sortable" data-col="barangay">Barangay <i class="bi bi-arrow-down-up sort-icon"></i></th>
                      <th class="sortable" data-col="joined">Date Registered <i class="bi bi-arrow-down-up sort-icon"></i></th>
                    </tr>
                  </thead>
                  <tbody id="usersTableBody">
                    <!-- Populated via JavaScript from mock data -->
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Users Pagination -->
            <div class="table-pagination-row">
              <div class="text-muted small" id="usersPaginationInfo">Showing 1 to 10 of 38 users</div>
              <div class="pagination-btn-group" id="usersPaginationBtns">
                <!-- Page buttons injected via JS -->
              </div>
            </div>
          </div>
        </div>

        <!-- ═════════════════════════════════════════════════════════ -->
        <!-- TAB 3: PHARMACIES                                         -->
        <!-- ═════════════════════════════════════════════════════════ -->
        <div id="tab-pharmacies" class="tab-section">
          <div class="report-table-card">
            <div class="table-toolbar-inner">
              <div class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 450px;">
                <div class="input-group">
                  <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                  </span>
                  <input type="text" id="phSearchInput" class="form-control border-start-0 ps-0" placeholder="Search pharmacy or owner...">
                </div>
              </div>

              <div class="d-flex align-items-center gap-2">
                <select id="phStatusFilter" class="form-select filter-select" style="min-width: 150px;">
                  <option value="all">All Status</option>
                  <option value="Approved">Approved</option>
                  <option value="Pending">Pending</option>
                  <option value="Suspended">Suspended</option>
                </select>

                <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 rounded-3 px-3 fw-semibold text-nowrap" onclick="openInputModalWithTab('pharmacy')">
                  <i class="bi bi-building-add"></i>
                  <span>Input Mock Pharmacy</span>
                </button>

                <span class="text-muted small text-nowrap ms-2" id="phCounterLabel">Showing 12 pharmacies</span>
              </div>
            </div>

            <div class="table-scroll" id="pharmaciesTableScrollArea">
              <div class="table-responsive">
                <table class="section-table" id="pharmaciesTable">
                  <thead>
                    <tr>
                      <th style="width: 45px;">#</th>
                      <th>Pharmacy Name</th>
                      <th>Owner / Administrator</th>
                      <th>Barangay</th>
                      <th>Status</th>
                      <th>Date Registered</th>
                      <th class="text-end">Items In Stock</th>
                      <th class="text-end">Low / Out</th>
                      <th class="text-end">Expired Items</th>
                    </tr>
                  </thead>
                  <tbody id="pharmaciesTableBody">
                    <!-- Populated via JavaScript from mock data -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- ═════════════════════════════════════════════════════════ -->
        <!-- TAB 4: NEW "STOCK AVAILABILITY" (LGU CITY MONITORING)     -->
        <!-- ═════════════════════════════════════════════════════════ -->
        <div id="tab-stock" class="tab-section">

          <!-- City Shortage Alert Banner -->
          <div class="shortage-alert-banner">
            <span class="material-symbols-outlined shortage-alert-icon">emergency</span>
            <div>
              <h6 class="fw-bold mb-1 text-danger d-flex align-items-center gap-2">
                <span>City Shortage Alerts: 3 Essential Medicines Out of Stock</span>
                <span class="badge bg-danger text-white" style="font-size: 10px;">Immediate Action</span>
              </h6>
              <p class="small text-secondary mb-0">
                The LGU health monitoring system has identified critical medications currently unavailable across all registered retail pharmacies in Malaybalay City. Emergency procurement or health center reallocation may be necessary.
              </p>
            </div>
          </div>

          <!-- Section 1: Medicines Out of Stock in ALL Pharmacies -->
          <div class="report-table-card mb-4">
            <div class="table-toolbar-inner">
              <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-octagon-fill text-danger"></i>
                Medicines Out of Stock Across ALL Pharmacies (Shortage Alerts)
              </h6>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Critical Shortages</span>
                <button type="button" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 rounded-3 px-3 fw-semibold text-nowrap" onclick="openInputModalWithTab('stock')">
                  <i class="bi bi-exclamation-triangle-fill"></i>
                  <span>Input Stock Shortage</span>
                </button>
              </div>
            </div>
            <div class="table-responsive">
              <table class="section-table">
                <thead>
                  <tr>
                    <th>Medicine / Brand</th>
                    <th>Generic Name</th>
                    <th>Category</th>
                    <th>Severity Level</th>
                    <th>Affected Coverage</th>
                    <th>Last Stock Available</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="shortagesTableBody">
                  <!-- Injected via JavaScript -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Section 2: Medicines Carried by Fewest Pharmacies -->
          <div class="report-table-card mb-4">
            <div class="table-toolbar-inner">
              <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-shield-exclamation text-warning"></i>
                Medicines Carried by the Fewest Pharmacies (Supply Vulnerability Index)
              </h6>
              <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">Vulnerable Supply</span>
            </div>
            <div class="table-responsive">
              <table class="section-table">
                <thead>
                  <tr>
                    <th>Medicine Description</th>
                    <th>Generic Name</th>
                    <th>Category</th>
                    <th>Carried By</th>
                    <th>City Stock Total</th>
                    <th>Vulnerability Level</th>
                  </tr>
                </thead>
                <tbody id="fewestCarriedTableBody">
                  <!-- Injected via JavaScript -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Section 3: Pharmacies with Most Expiring Items -->
          <div class="report-table-card">
            <div class="table-toolbar-inner">
              <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <i class="bi bi-calendar-x text-danger"></i>
                Pharmacies with Most Expiring Items (Waste & Compliance Risk)
              </h6>
              <span class="badge bg-light text-muted border rounded-pill">LGU Compliance Monitoring</span>
            </div>
            <div class="table-responsive">
              <table class="section-table">
                <thead>
                  <tr>
                    <th>Pharmacy Name</th>
                    <th>Barangay Location</th>
                    <th>Owner / Licensee</th>
                    <th>Expiring Batches</th>
                    <th>Affected Medicines</th>
                    <th>LGU Risk Assessment</th>
                  </tr>
                </thead>
                <tbody id="expiringPharmaciesTableBody">
                  <!-- Injected via JavaScript -->
                </tbody>
              </table>
            </div>
          </div>

        </div>

      </div><!-- end report-content -->

        </div><!-- end reports-admin-container -->
      </div><!-- end content -->

    </div>
  </div>

  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <!-- MODAL: REVIEW PENDING APPLICATION (UI PREVIEW ONLY)                -->
  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <div class="modal fade" id="reviewPendingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #23423a 0%, #15805e 100%);">
          <h5 class="modal-title fw-bold" id="reviewModalTitle">Review Pharmacy Application</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <div class="mb-3">
            <label class="form-label text-muted small fw-semibold">Pharmacy Name</label>
            <div class="fw-bold fs-6" id="reviewModalPharmName"></div>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label text-muted small fw-semibold">Owner Name</label>
              <div class="fw-semibold text-dark" id="reviewModalOwner"></div>
            </div>
            <div class="col-6">
              <label class="form-label text-muted small fw-semibold">Barangay Location</label>
              <div class="fw-semibold text-dark" id="reviewModalBarangay"></div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label text-muted small fw-semibold">Contact Email & Phone</label>
            <div class="text-secondary small" id="reviewModalContact"></div>
          </div>
          <div class="p-3 bg-light rounded-3 border small text-muted">
            <i class="bi bi-info-circle text-primary me-1"></i>
            This is a frontend demonstration review panel for LGU administrators.
          </div>
        </div>
        <div class="modal-footer bg-light border-0">
          <button type="button" class="btn btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger rounded-3 px-3" onclick="simulateReviewAction('Reject')">Reject</button>
          <button type="button" class="btn btn-success rounded-3 px-4 fw-semibold" onclick="simulateReviewAction('Approve')">Approve Application</button>
        </div>
      </div>
    </div>
  </div>

  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <!-- MODAL: INPUT MOCK DATA (USER, PHARMACY, STOCK AVAILABILITY)        -->
  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <div class="modal fade" id="inputMockDataModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        <!-- Modal Header -->
        <div class="modal-header text-white" style="background: linear-gradient(135deg, #1d9e75 0%, #15805e 100%);">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-database-fill-add fs-4"></i>
            <div>
              <h5 class="modal-title fw-bold mb-0">Input Mock Data</h5>
              <div class="small text-white-50">Add test records to Users, Pharmacies, or Stock Shortage alerts</div>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- Segmented Form Tabs -->
        <div class="p-3 bg-light border-bottom">
          <div class="nav nav-pills nav-fill gap-2" id="inputModalTabs" role="tablist">
            <button class="nav-link active rounded-3 py-2 fw-semibold" id="tab-btn-input-user" data-bs-toggle="pill" data-bs-target="#panel-input-user" type="button" role="tab">
              <i class="bi bi-person-plus-fill me-1"></i> Input Mock User
            </button>
            <button class="nav-link rounded-3 py-2 fw-semibold" id="tab-btn-input-pharmacy" data-bs-toggle="pill" data-bs-target="#panel-input-pharmacy" type="button" role="tab">
              <i class="bi bi-building-add me-1"></i> Input Mock Pharmacy
            </button>
            <button class="nav-link rounded-3 py-2 fw-semibold" id="tab-btn-input-stock" data-bs-toggle="pill" data-bs-target="#panel-input-stock" type="button" role="tab">
              <i class="bi bi-box-seam-fill me-1"></i> Input Stock Shortage
            </button>
          </div>
        </div>

        <!-- Modal Body: 3 Tab Panels -->
        <div class="modal-body p-4">
          <div class="tab-content" id="inputModalTabContent">

            <!-- ── PANEL 1: INPUT MOCK USER ──────────────────────────── -->
            <div class="tab-pane fade show active" id="panel-input-user" role="tabpanel">
              <form id="formInputMockUser">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="mockUserName" placeholder="e.g. Juan Carlos Dela Cruz" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Email Address <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="mockUserEmail" placeholder="e.g. juan.delacruz@gmail.com" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Phone Number</label>
                    <input type="text" class="form-control" id="mockUserPhone" placeholder="0917-xxx-xxxx" value="0917-555-1234">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">User Role <span class="text-danger">*</span></label>
                    <select class="form-select" id="mockUserRole" required>
                      <option value="Patient" selected>Patient / Client</option>
                      <option value="Pharmacist">Pharmacist</option>
                      <option value="Owner">Pharmacy Owner</option>
                      <option value="Admin">System Admin</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Barangay Location <span class="text-danger">*</span></label>
                    <select class="form-select" id="mockUserBarangay" required>
                      <option value="Poblacion" selected>Poblacion</option>
                      <option value="Casisang">Casisang</option>
                      <option value="Sumpong">Sumpong</option>
                      <option value="Aglayan">Aglayan</option>
                      <option value="Bangcud">Bangcud</option>
                      <option value="Impalambong">Impalambong</option>
                      <option value="Kalasungay">Kalasungay</option>
                      <option value="San Jose">San Jose</option>
                      <option value="Dalwangan">Dalwangan</option>
                      <option value="Canayan">Canayan</option>
                      <option value="Laguitas">Laguitas</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Account Status</label>
                    <select class="form-select" id="mockUserStatus">
                      <option value="Active" selected>Active</option>
                      <option value="Suspended">Suspended</option>
                    </select>
                  </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                  <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold text-white">
                    <i class="bi bi-check2-circle me-1"></i> Add Mock User
                  </button>
                </div>
              </form>
            </div>

            <!-- ── PANEL 2: INPUT MOCK PHARMACY ──────────────────────── -->
            <div class="tab-pane fade" id="panel-input-pharmacy" role="tabpanel">
              <form id="formInputMockPharmacy">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Pharmacy Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="mockPharmName" placeholder="e.g. CarePoint Drugstore" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Owner / Administrator <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="mockPharmOwner" placeholder="e.g. Marivic Mendoza" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Contact Email</label>
                    <input type="email" class="form-control" id="mockPharmEmail" placeholder="e.g. contact@carepoint.ph" value="info@pharmacy.ph">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Phone Number</label>
                    <input type="text" class="form-control" id="mockPharmPhone" placeholder="0928-xxx-xxxx" value="0928-765-4321">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Barangay Location <span class="text-danger">*</span></label>
                    <select class="form-select" id="mockPharmBarangay" required>
                      <option value="Poblacion" selected>Poblacion</option>
                      <option value="Casisang">Casisang</option>
                      <option value="Sumpong">Sumpong</option>
                      <option value="Aglayan">Aglayan</option>
                      <option value="Bangcud">Bangcud</option>
                      <option value="Impalambong">Impalambong</option>
                      <option value="Kalasungay">Kalasungay</option>
                      <option value="San Jose">San Jose</option>
                      <option value="Dalwangan">Dalwangan</option>
                      <option value="Canayan">Canayan</option>
                      <option value="Laguitas">Laguitas</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Approval Status</label>
                    <select class="form-select" id="mockPharmStatus">
                      <option value="Approved" selected>Approved (Operating)</option>
                      <option value="Pending">Pending (Needs LGU Review)</option>
                      <option value="Suspended">Suspended</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label text-muted small fw-semibold">Items in Stock</label>
                    <input type="number" class="form-control" id="mockPharmInStock" value="120" min="0">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label text-muted small fw-semibold">Low Stock Count</label>
                    <input type="number" class="form-control" id="mockPharmLowStock" value="4" min="0">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label text-muted small fw-semibold">Expired Count</label>
                    <input type="number" class="form-control" id="mockPharmExpired" value="0" min="0">
                  </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                  <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-success rounded-3 px-4 fw-semibold text-white">
                    <i class="bi bi-check2-circle me-1"></i> Add Mock Pharmacy
                  </button>
                </div>
              </form>
            </div>

            <!-- ── PANEL 3: INPUT STOCK SHORTAGE ALERT ────────────────── -->
            <div class="tab-pane fade" id="panel-input-stock" role="tabpanel">
              <form id="formInputMockStock">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Medicine / Brand <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="mockStockBrand" placeholder="e.g. Amoxicillin 500mg" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Generic Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="mockStockGeneric" placeholder="e.g. Amoxicillin Trihydrate" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Therapeutic Category</label>
                    <input type="text" class="form-control" id="mockStockCategory" placeholder="e.g. Antibacterial / Penicillin" value="Antibacterial">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Severity Level</label>
                    <select class="form-select" id="mockStockSeverity">
                      <option value="Critical Alert" selected>Critical Alert</option>
                      <option value="Urgent Shortage">Urgent Shortage</option>
                      <option value="Vulnerable Supply">Vulnerable Supply</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Affected Barangays</label>
                    <select class="form-select" id="mockStockBarangay">
                      <option value="All 11 Barangays" selected>All 11 Barangays (City-Wide)</option>
                      <option value="Poblacion, Casisang">Poblacion, Casisang</option>
                      <option value="Sumpong, Impalambong">Sumpong, Impalambong</option>
                      <option value="Aglayan, Bangcud">Aglayan, Bangcud</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label text-muted small fw-semibold">Stock Status</label>
                    <select class="form-select" id="mockStockStatus">
                      <option value="Out of Stock City-Wide" selected>Out of Stock City-Wide</option>
                      <option value="Severe Shortage">Severe Shortage</option>
                      <option value="Low City Stock">Low City Stock</option>
                    </select>
                  </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                  <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger rounded-3 px-4 fw-semibold text-white">
                    <i class="bi bi-exclamation-octagon-fill me-1"></i> Add Shortage Alert
                  </button>
                </div>
              </form>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <!-- JAVASCRIPT CONTROLLERS                                              -->
  <!-- ═══════════════════════════════════════════════════════════════════ -->
  <script>
    // State management for reports page
    const reportsState = {
      dateRange: 'month',
      barangay: 'all',
      usersPage: 1,
      usersLimit: 10,
      usersRoleFilter: 'all',
      usersStatusFilter: 'all',
      usersSearchQuery: '',
      usersSortCol: 'name',
      usersSortDir: 'asc',
      phSearchQuery: '',
      phStatusFilter: 'all'
    };

    // Chart.js instances holder
    let chartUsersByRoleInstance = null;
    let chartRegistrationsInstance = null;
    let chartPharmacyStatusInstance = null;
    let chartPharmacyByBarangayInstance = null;

    // ── TAB SWITCHER (FIXED: RELIABLY CLEANS UP INLINE DISPLAY STYLES) ─
    function switchTab(name, btn) {
      $('.tab-section').removeClass('active').hide();
      $('.rtab, .report-tab-btn').removeClass('active');
      const target = $('#tab-' + name);
      if (target.length) {
        target.addClass('active').show();
      }
      if (btn) $(btn).addClass('active');
    }

    // ── OPEN INPUT MOCK MODAL DIRECTLY TO SPECIFIC TAB ──────────────────
    function openInputModalWithTab(tab) {
      if (tab === 'user') {
        const triggerEl = document.querySelector('#tab-btn-input-user');
        if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
      } else if (tab === 'pharmacy') {
        const triggerEl = document.querySelector('#tab-btn-input-pharmacy');
        if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
      } else if (tab === 'stock') {
        const triggerEl = document.querySelector('#tab-btn-input-stock');
        if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();
      }
      const modal = new bootstrap.Modal(document.getElementById('inputMockDataModal'));
      modal.show();
    }

    // ── TOAST HELPER (MATCHING USER-SIDE STYLE) ─────────────────────────
    function showToast(message, type = 'success') {
      const icons = {
        success: 'bi bi-check-circle-fill',
        info: 'bi bi-info-circle-fill',
        warning: 'bi bi-exclamation-triangle-fill',
        danger: 'bi bi-x-circle-fill'
      };
      const colors = {
        success: { bg: '#d4f5e2', text: '#1a7a45' },
        info: { bg: '#e0f2fe', text: '#0369a1' },
        warning: { bg: '#fef3c7', text: '#b45309' },
        danger: { bg: '#fee2e2', text: '#b91c1c' }
      };

      $('#appToast').remove();

      const toastHtml = `
        <div id="appToast" class="toast align-items-center border-0 position-fixed top-0 end-0 m-3 shadow"
          role="alert" style="z-index: 1080; min-width: 320px; background: ${colors[type].bg}; border-radius: 12px;">
          <div class="d-flex align-items-center px-3 py-2 gap-2">
            <i class="${icons[type]}" style="font-size: 18px; color: ${colors[type].text};"></i>
            <div class="toast-body p-0 fw-semibold" style="color: ${colors[type].text}; font-size: 13.5px;">
              ${message}
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast"></button>
          </div>
        </div>
      `;

      $('body').append(toastHtml);
      const toastEl = new bootstrap.Toast(document.getElementById('appToast'), { delay: 3000 });
      toastEl.show();
      setTimeout(() => $('#appToast').remove(), 3500);
    }

    // ── RENDER OVERVIEW TAB ─────────────────────────────────────────────
    function renderOverview(data) {
      // 1. KPI Cards
      $('#kpiTotalUsers').text(data.kpis.totalUsers);
      $('#kpiNewUsers').text(data.kpis.newUsers);
      $('#kpiNewUsersSub').html(`<i class="bi bi-arrow-up-short"></i> ${data.kpis.newUsersPct} vs last month`);
      $('#kpiActivePharmacies').text(data.kpis.activePharmacies);
      $('#kpiPendingApprovals').text(data.kpis.pendingApprovals);
      $('#kpiExpiredStocks').text(data.kpis.expiredStocks);
      $('#kpiLowStocks').text(data.kpis.lowStockItems);

      // 2. Chart 1: Users by Role (Donut)
      if (chartUsersByRoleInstance) chartUsersByRoleInstance.destroy();
      const ctxUsers = document.getElementById('chartUsersByRole');
      if (ctxUsers) {
        chartUsersByRoleInstance = new Chart(ctxUsers.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: ['Patients', 'Pharmacists', 'Owners', 'Admins'],
            datasets: [{
              data: [
                data.charts.usersByRole.Patient,
                data.charts.usersByRole.Pharmacist,
                data.charts.usersByRole.Owner,
                data.charts.usersByRole.Admin
              ],
              backgroundColor: ['#0284c7', '#1d9e75', '#f59e0b', '#64748b'],
              borderWidth: 2,
              borderColor: '#ffffff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { position: 'bottom', labels: { boxWidth: 12, font: { family: 'Inter', size: 12 } } }
            },
            cutout: '68%'
          }
        });
      }

      // 3. Chart 2: Registrations Timeline (Line)
      if (chartRegistrationsInstance) chartRegistrationsInstance.destroy();
      const ctxReg = document.getElementById('chartRegistrations');
      if (ctxReg) {
        chartRegistrationsInstance = new Chart(ctxReg.getContext('2d'), {
          type: 'line',
          data: {
            labels: data.charts.timeline.labels,
            datasets: [{
              label: 'New Registrations',
              data: data.charts.timeline.data,
              borderColor: '#1d9e75',
              backgroundColor: 'rgba(29, 158, 117, 0.08)',
              borderWidth: 2.5,
              fill: true,
              tension: 0.35,
              pointBackgroundColor: '#1d9e75',
              pointRadius: 4
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
              x: { grid: { display: false } }
            }
          }
        });
      }

      // 4. Chart 3: Pharmacies by Status (Donut)
      if (chartPharmacyStatusInstance) chartPharmacyStatusInstance.destroy();
      const ctxPharm = document.getElementById('chartPharmacyStatus');
      if (ctxPharm) {
        chartPharmacyStatusInstance = new Chart(ctxPharm.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: ['Approved', 'Pending', 'Suspended'],
            datasets: [{
              data: [
                data.charts.pharmacyStatus.Approved,
                data.charts.pharmacyStatus.Pending,
                data.charts.pharmacyStatus.Suspended
              ],
              backgroundColor: ['#16a34a', '#f59e0b', '#dc2626'],
              borderWidth: 2,
              borderColor: '#ffffff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { position: 'bottom', labels: { boxWidth: 12, font: { family: 'Inter', size: 12 } } }
            },
            cutout: '68%'
          }
        });
      }

      // 5. Chart 4: Pharmacies by Barangay (Horizontal Bar)
      if (chartPharmacyByBarangayInstance) chartPharmacyByBarangayInstance.destroy();
      const ctxBrgy = document.getElementById('chartPharmacyByBarangay');
      if (ctxBrgy) {
        const brgyLabels = Object.keys(data.charts.pharmacyByBarangay);
        const brgyCounts = Object.values(data.charts.pharmacyByBarangay);

        chartPharmacyByBarangayInstance = new Chart(ctxBrgy.getContext('2d'), {
          type: 'bar',
          data: {
            labels: brgyLabels,
            datasets: [{
              label: 'Registered Pharmacies',
              data: brgyCounts,
              backgroundColor: '#0ea5e9',
              borderRadius: 6
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
              y: { grid: { display: false } }
            }
          }
        });
      }

      // 6. Pending Approvals List
      const pendingContainer = $('#pendingApprovalsListContainer');
      pendingContainer.empty();
      const pendingItems = data.pharmacies.filter(p => p.status === 'Pending');

      if (pendingItems.length === 0) {
        pendingContainer.html(`
          <div class="empty-state py-4">
            <i class="bi bi-check-circle text-success fs-3 d-block mb-1"></i>
            <div>All applications reviewed. No pending approvals.</div>
          </div>
        `);
      } else {
        pendingItems.forEach(p => {
          pendingContainer.append(`
            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border bg-light">
              <div>
                <div class="fw-bold text-dark small">${p.name}</div>
                <div class="text-muted" style="font-size: 11.5px;">
                  Owner: ${p.owner} · <i class="bi bi-geo-alt"></i> ${p.barangay}
                </div>
                <div class="text-secondary" style="font-size: 11px;">
                  Applied: ${p.registered} · ${p.email}
                </div>
              </div>
              <button type="button" class="btn-cta-review" onclick="openReviewModal('${encodeURIComponent(JSON.stringify(p))}')">
                Review
              </button>
            </div>
          `);
        });
      }

      // 7. Suspended Pharmacies List
      const suspendedContainer = $('#suspendedPharmaciesListContainer');
      suspendedContainer.empty();
      const suspendedItems = data.pharmacies.filter(p => p.status === 'Suspended');

      if (suspendedItems.length === 0) {
        suspendedContainer.html(`
          <div class="empty-state py-4">
            <i class="bi bi-shield-check text-success fs-3 d-block mb-1"></i>
            <div>No suspended pharmacies in Malaybalay City. Compliance is 100%.</div>
          </div>
        `);
      } else {
        suspendedItems.forEach(p => {
          const pName = p.name ? p.name : '<span class="text-not-provided">Not provided</span> <span class="badge-incomplete">Incomplete</span>';
          suspendedContainer.append(`
            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border bg-light">
              <div>
                <div class="fw-bold small text-dark">${pName}</div>
                <div class="text-muted" style="font-size: 11.5px;">
                  Status: Suspended · Registered: ${p.registered}
                </div>
              </div>
              <span class="badge-status badge-suspended">Suspended</span>
            </div>
          `);
        });
      }
    }

    // ── RENDER USERS TAB ────────────────────────────────────────────────
    function renderUsersTab(allUsers) {
      // Filter by role chip, status, and search query
      let filtered = allUsers.filter(u => {
        const matchRole = reportsState.usersRoleFilter === 'all' || u.role === reportsState.usersRoleFilter;
        const matchStatus = reportsState.usersStatusFilter === 'all' || u.status === reportsState.usersStatusFilter;
        const q = reportsState.usersSearchQuery.toLowerCase();
        const matchSearch = !q || u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q) || u.barangay.toLowerCase().includes(q);
        return matchRole && matchStatus && matchSearch;
      });

      // Sorting
      filtered.sort((a, b) => {
        let valA = a[reportsState.usersSortCol] || '';
        let valB = b[reportsState.usersSortCol] || '';
        if (typeof valA === 'string') valA = valA.toLowerCase();
        if (typeof valB === 'string') valB = valB.toLowerCase();

        if (valA < valB) return reportsState.usersSortDir === 'asc' ? -1 : 1;
        if (valA > valB) return reportsState.usersSortDir === 'asc' ? 1 : -1;
        return 0;
      });

      // Pagination slice
      const total = filtered.length;
      const totalPages = Math.ceil(total / reportsState.usersLimit) || 1;
      if (reportsState.usersPage > totalPages) reportsState.usersPage = 1;

      const startIndex = (reportsState.usersPage - 1) * reportsState.usersLimit;
      const paginated = filtered.slice(startIndex, startIndex + reportsState.usersLimit);

      // Table rows
      const tbody = $('#usersTableBody');
      tbody.empty();

      if (paginated.length === 0) {
        tbody.html('<tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox fs-3 d-block mb-1"></i>No matching users found.</td></tr>');
      } else {
        paginated.forEach((u, i) => {
          const roleClass = u.role === 'Patient' ? 'badge-role-patient' :
                            (u.role === 'Pharmacist' ? 'badge-role-pharmacist' :
                            (u.role === 'Owner' ? 'badge-role-owner' : 'badge-role-admin'));

          const statusClass = u.status === 'Active' ? 'badge-active' : 'badge-suspended';

          tbody.append(`
            <tr>
              <td class="text-muted small">${startIndex + i + 1}</td>
              <td class="fw-semibold text-dark">${u.name}</td>
              <td><span class="badge-status ${roleClass}">${u.role}</span></td>
              <td><span class="badge-status ${statusClass}">${u.status}</span></td>
              <td><i class="bi bi-geo-alt text-success me-1"></i>${u.barangay}</td>
              <td class="text-muted small">${u.joined}</td>
            </tr>
          `);
        });
      }

      // Update counters
      $('#usersCounterLabel').text(`Showing ${total} user${total === 1 ? '' : 's'}`);
      $('#usersPaginationInfo').text(`Showing ${total ? startIndex + 1 : 0} to ${Math.min(startIndex + reportsState.usersLimit, total)} of ${total} users`);

      // Render pagination buttons
      const paginationBtns = $('#usersPaginationBtns');
      paginationBtns.empty();

      if (totalPages > 1) {
        paginationBtns.append(`
          <button type="button" class="page-num-btn" ${reportsState.usersPage === 1 ? 'disabled' : ''} onclick="goToUsersPage(${reportsState.usersPage - 1})">
            &laquo;
          </button>
        `);

        for (let p = 1; p <= totalPages; p++) {
          paginationBtns.append(`
            <button type="button" class="page-num-btn ${p === reportsState.usersPage ? 'active' : ''}" onclick="goToUsersPage(${p})">
              ${p}
            </button>
          `);
        }

        paginationBtns.append(`
          <button type="button" class="page-num-btn" ${reportsState.usersPage === totalPages ? 'disabled' : ''} onclick="goToUsersPage(${reportsState.usersPage + 1})">
            &raquo;
          </button>
        `);
      }

      // Role chip counts
      $('#chipCountAllUsers').text(allUsers.length);
      $('#chipCountPatients').text(allUsers.filter(u => u.role === 'Patient').length);
      $('#chipCountPharmacists').text(allUsers.filter(u => u.role === 'Pharmacist').length);
      $('#chipCountOwners').text(allUsers.filter(u => u.role === 'Owner').length);
      $('#chipCountAdmins').text(allUsers.filter(u => u.role === 'Admin').length);
    }

    function goToUsersPage(p) {
      reportsState.usersPage = p;
      refreshReports();
    }

    // ── RENDER PHARMACIES TAB ───────────────────────────────────────────
    function renderPharmaciesTab(allPharmacies) {
      let filtered = allPharmacies.filter(p => {
        const matchStatus = reportsState.phStatusFilter === 'all' || p.status.toLowerCase() === reportsState.phStatusFilter.toLowerCase();
        const q = reportsState.phSearchQuery.toLowerCase();
        const matchSearch = !q || (p.name && p.name.toLowerCase().includes(q)) || (p.owner && p.owner.toLowerCase().includes(q)) || (p.barangay && p.barangay.toLowerCase().includes(q));
        return matchStatus && matchSearch;
      });

      const tbody = $('#pharmaciesTableBody');
      tbody.empty();

      if (filtered.length === 0) {
        tbody.html('<tr><td colspan="9" class="text-center text-muted py-4"><i class="bi bi-inbox fs-3 d-block mb-1"></i>No matching pharmacies found.</td></tr>');
      } else {
        filtered.forEach((p, i) => {
          const isMissingName = !p.name || p.name.trim() === '';
          const isMissingOwner = !p.owner || p.owner.trim() === '';
          const isMissingBrgy = !p.barangay || p.barangay.trim() === '';

          const nameCol = isMissingName ?
            '<span class="text-not-provided">Not provided</span> <span class="badge-incomplete"><i class="bi bi-exclamation-triangle-fill"></i> Incomplete</span>' :
            `<span class="fw-bold text-dark">${p.name}</span>`;

          const ownerCol = isMissingOwner ?
            '<span class="text-not-provided">Not provided</span>' : p.owner;

          const brgyCol = isMissingBrgy ?
            '<span class="text-not-provided">Not provided</span>' : `<i class="bi bi-geo-alt text-success me-1"></i>${p.barangay}`;

          const statusClass = p.status === 'Approved' ? 'badge-active' :
                              (p.status === 'Pending' ? 'badge-pending' : 'badge-suspended');

          tbody.append(`
            <tr>
              <td class="text-muted small">${i + 1}</td>
              <td>${nameCol}</td>
              <td>${ownerCol}</td>
              <td>${brgyCol}</td>
              <td><span class="badge-status ${statusClass}">${p.status}</span></td>
              <td class="text-muted small">${p.registered}</td>
              <td class="text-end fw-semibold">${p.itemsInStock ? p.itemsInStock + ' items' : '0'}</td>
              <td class="text-end ${p.lowStockCount > 0 ? 'text-warning fw-bold' : 'text-muted'}">${p.lowStockCount}</td>
              <td class="text-end ${p.expiredCount > 0 ? 'text-danger fw-bold' : 'text-muted'}">${p.expiredCount}</td>
            </tr>
          `);
        });
      }

      $('#phCounterLabel').text(`Showing ${filtered.length} pharmac${filtered.length === 1 ? 'y' : 'ies'}`);
    }

    // ── RENDER STOCK AVAILABILITY TAB (LGU MONITORING) ──────────────────
    function renderStockTab(stockData) {
      // 1. Shortages Table
      const shortBody = $('#shortagesTableBody');
      shortBody.empty();
      stockData.shortages.forEach(s => {
        shortBody.append(`
          <tr>
            <td class="fw-bold text-danger"><i class="bi bi-capsule me-1"></i>${s.brand}</td>
            <td>${s.generic}</td>
            <td class="text-muted">${s.category}</td>
            <td><span class="shortage-badge-critical">${s.severity}</span></td>
            <td><i class="bi bi-geo-alt-fill text-danger me-1"></i>${s.affectedBarangays}</td>
            <td class="text-muted small">${s.lastReported}</td>
            <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle">${s.status}</span></td>
          </tr>
        `);
      });

      // 2. Fewest Carried Table
      const fewBody = $('#fewestCarriedTableBody');
      fewBody.empty();
      stockData.fewestCarried.forEach(f => {
        fewBody.append(`
          <tr>
            <td class="fw-bold text-dark">${f.medicine}</td>
            <td>${f.generic}</td>
            <td class="text-muted">${f.category}</td>
            <td><span class="fw-semibold text-success">${f.pharmacyCount} of ${f.totalPharmacies} pharmacies</span> (${f.carriedBy})</td>
            <td><span class="badge bg-light text-dark border">${f.cityStockTotal} pcs total</span></td>
            <td><span class="vulnerability-badge-high">${f.vulnerability}</span></td>
          </tr>
        `);
      });

      // 3. Expiring Pharmacies Table
      const expBody = $('#expiringPharmaciesTableBody');
      expBody.empty();
      stockData.expiringPharmacies.forEach(e => {
        expBody.append(`
          <tr>
            <td class="fw-bold text-dark">${e.pharmacy}</td>
            <td><i class="bi bi-geo-alt text-success me-1"></i>${e.barangay}</td>
            <td>${e.owner}</td>
            <td class="fw-bold text-danger">${e.expiringCount} item${e.expiringCount > 1 ? 's' : ''}</td>
            <td class="small text-secondary">${e.items}</td>
            <td><span class="badge-status badge-pending">${e.riskLevel}</span></td>
          </tr>
        `);
      });
    }

    // ── MAIN REFRESH CONTROLLER ─────────────────────────────────────────
    function refreshReports() {
      // Remember which tab is currently active
      const currentTabId = $('.tab-section.active').attr('id') || 'tab-overview';

      // Simulate brief loading feel
      $('#reportsSkeletonLoader').show();
      $('.tab-section').hide();

      setTimeout(() => {
        const rawData = window.SystemReportsMock.getFilteredData({
          dateRange: reportsState.dateRange,
          barangay: reportsState.barangay
        });

        renderOverview(rawData);
        renderUsersTab(rawData.users);
        renderPharmaciesTab(rawData.pharmacies);
        renderStockTab(rawData.stockMonitoring);

        // Update Tab Badges
        $('#tabUsersBadge').text(rawData.users.length);
        $('#tabPharmaciesBadge').text(rawData.pharmacies.length);

        $('#reportsSkeletonLoader').hide();
        $('#' + currentTabId).addClass('active').show();
      }, 140);
    }

    // ── REVIEW APPLICATION ACTION SIMULATION ────────────────────────────
    function openReviewModal(encodedJson) {
      const p = JSON.parse(decodeURIComponent(encodedJson));
      $('#reviewModalPharmName').text(p.name);
      $('#reviewModalOwner').text(p.owner);
      $('#reviewModalBarangay').text(p.barangay);
      $('#reviewModalContact').text(`${p.email} · ${p.phone}`);

      const modal = new bootstrap.Modal(document.getElementById('reviewPendingModal'));
      modal.show();
    }

    function simulateReviewAction(action) {
      $('#reviewPendingModal').modal('hide');
      showToast(`Application has been marked as ${action} (Demonstration)`, 'info');
    }

    // ── DOCUMENT READY LISTENERS ────────────────────────────────────────
    $(document).ready(function () {
      // 1. Sticky toolbar shadow on scroll
      $('.main-panel').on('scroll', function () {
        if ($(this).scrollTop() > 10) {
          $('#reportsStickyToolbar').addClass('is-scrolled');
        } else {
          $('#reportsStickyToolbar').removeClass('is-scrolled');
        }
      });

      // 2. Date Range & Barangay Filter Change
      $('#reportDateRange').on('change', function () {
        reportsState.dateRange = $(this).val();
        refreshReports();
      });

      $('#reportBarangay').on('change', function () {
        reportsState.barangay = $(this).val();
        refreshReports();
      });

      // 3. Export Buttons (UI Only)
      $('#btnDownloadPdf, #btnDownloadCsv').on('click', function () {
        showToast('Export will be available soon. Please use Print for PDF generation.', 'info');
      });

      $('#btnPrintReport').on('click', function () {
        window.print();
      });

      // 4. Users Tab Role Chips
      $('#userRoleChipsBar .summary-chip').on('click', function () {
        $('#userRoleChipsBar .summary-chip').removeClass('active');
        $(this).addClass('active');
        reportsState.usersRoleFilter = $(this).data('role');
        reportsState.usersPage = 1;
        const rawData = window.SystemReportsMock.getFilteredData({
          dateRange: reportsState.dateRange,
          barangay: reportsState.barangay
        });
        renderUsersTab(rawData.users);
      });

      // 5. Users Tab Search & Status Filter
      $('#usersSearchInput').on('input', function () {
        reportsState.usersSearchQuery = $(this).val().trim();
        reportsState.usersPage = 1;
        const rawData = window.SystemReportsMock.getFilteredData({
          dateRange: reportsState.dateRange,
          barangay: reportsState.barangay
        });
        renderUsersTab(rawData.users);
      });

      $('#usersStatusFilter').on('change', function () {
        reportsState.usersStatusFilter = $(this).val();
        reportsState.usersPage = 1;
        const rawData = window.SystemReportsMock.getFilteredData({
          dateRange: reportsState.dateRange,
          barangay: reportsState.barangay
        });
        renderUsersTab(rawData.users);
      });

      // 6. Users Table Sorting
      $('#usersTable th.sortable').on('click', function () {
        const col = $(this).data('col');
        if (reportsState.usersSortCol === col) {
          reportsState.usersSortDir = reportsState.usersSortDir === 'asc' ? 'desc' : 'asc';
        } else {
          reportsState.usersSortCol = col;
          reportsState.usersSortDir = 'asc';
        }
        const rawData = window.SystemReportsMock.getFilteredData({
          dateRange: reportsState.dateRange,
          barangay: reportsState.barangay
        });
        renderUsersTab(rawData.users);
      });

      // 7. Pharmacies Tab Search & Status Filter
      $('#phSearchInput').on('input', function () {
        reportsState.phSearchQuery = $(this).val().trim();
        const rawData = window.SystemReportsMock.getFilteredData({
          dateRange: reportsState.dateRange,
          barangay: reportsState.barangay
        });
        renderPharmaciesTab(rawData.pharmacies);
      });

      $('#phStatusFilter').on('change', function () {
        reportsState.phStatusFilter = $(this).val();
        const rawData = window.SystemReportsMock.getFilteredData({
          dateRange: reportsState.dateRange,
          barangay: reportsState.barangay
        });
        renderPharmaciesTab(rawData.pharmacies);
      });

      // 8. Input Mock User Form Submission
      $('#formInputMockUser').on('submit', function (e) {
        e.preventDefault();
        const newUser = {
          name: $('#mockUserName').val(),
          email: $('#mockUserEmail').val(),
          phone: $('#mockUserPhone').val(),
          role: $('#mockUserRole').val(),
          barangay: $('#mockUserBarangay').val(),
          status: $('#mockUserStatus').val()
        };
        window.SystemReportsMock.addMockUser(newUser);
        bootstrap.Modal.getInstance(document.getElementById('inputMockDataModal'))?.hide();
        this.reset();
        showToast(`Mock User "${newUser.name}" added successfully!`, 'success');
        refreshReports();
        switchTab('users', document.querySelectorAll('.rtab, .report-tab-btn')[1]);
      });

      // 9. Input Mock Pharmacy Form Submission
      $('#formInputMockPharmacy').on('submit', function (e) {
        e.preventDefault();
        const newPharm = {
          name: $('#mockPharmName').val(),
          owner: $('#mockPharmOwner').val(),
          email: $('#mockPharmEmail').val(),
          phone: $('#mockPharmPhone').val(),
          barangay: $('#mockPharmBarangay').val(),
          status: $('#mockPharmStatus').val(),
          itemsInStock: $('#mockPharmInStock').val(),
          lowStockCount: $('#mockPharmLowStock').val(),
          expiredCount: $('#mockPharmExpired').val()
        };
        window.SystemReportsMock.addMockPharmacy(newPharm);
        bootstrap.Modal.getInstance(document.getElementById('inputMockDataModal'))?.hide();
        this.reset();
        showToast(`Mock Pharmacy "${newPharm.name}" added successfully!`, 'success');
        refreshReports();
        switchTab('pharmacies', document.querySelectorAll('.rtab, .report-tab-btn')[2]);
      });

      // 10. Input Mock Stock Shortage Form Submission
      $('#formInputMockStock').on('submit', function (e) {
        e.preventDefault();
        const newShortage = {
          brand: $('#mockStockBrand').val(),
          generic: $('#mockStockGeneric').val(),
          category: $('#mockStockCategory').val(),
          severity: $('#mockStockSeverity').val(),
          affectedBarangays: $('#mockStockBarangay').val(),
          status: $('#mockStockStatus').val()
        };
        window.SystemReportsMock.addMockShortage(newShortage);
        bootstrap.Modal.getInstance(document.getElementById('inputMockDataModal'))?.hide();
        this.reset();
        showToast(`Shortage alert for "${newShortage.brand}" added!`, 'warning');
        refreshReports();
        switchTab('stock', document.querySelectorAll('.rtab, .report-tab-btn')[3]);
      });

      // Initial Render
      refreshReports();
    });
  </script>

</body>

</html>