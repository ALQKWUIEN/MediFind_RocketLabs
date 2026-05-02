<?php
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
  <title>Inventory Report</title>

  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

  <!-- STYLES -->
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/03_SystemAdminCSS/reports-style.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-body-css.css">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    /* ── Layout fix: only main-panel scrolls ── */
    html, body {
      height: 100%;
      overflow: hidden !important;
    }
    .wrapper {
      height: 100vh;
      overflow: hidden !important;
      display: flex;
    }
    .main-panel {
      flex: 1;
      overflow-y: auto !important;
      height: 100vh;
    }

    /* ── Scrollable table ── */
    .table-wrap {
      overflow-x: auto;
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
      max-height: 520px;
    }
    .table-wrap .section-table {
      min-width: 900px;
      width: 100%;
    }
    .table-wrap .section-table thead th {
      position: sticky;
      top: 0;
      z-index: 1;
      background: var(--bg-light, #f9fafb);
      box-shadow: 0 1px 0 #e5e7eb;
    }

    /* ── Stats Toggle ── */
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
    .stats-toggle-btn:hover { background: #f8f9fa; color: #343a40; }
    .stats-toggle-btn .chevron {
      display: inline-block;
      transition: transform 0.35s ease;
      font-size: 11px;
      line-height: 1;
    }
    .stats-toggle-btn .chevron.rotated { transform: rotate(180deg); }
    .stats-header-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0.25rem;
      margin-bottom: 2px;
    }

    @media (max-width: 991px) {
      .calendar-widget { display: none; }
      .stat-card .card-sub { font-size: 0.8rem; }
    }
  </style>

</head>

<body>

  <div class="wrapper d-flex align-items-stretch">
    <div id="pharmacy-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <!-- ── Content ──────────────────────────────── -->
      <div class="report-content">

        <!-- ══ INVENTORY TAB ════════════════════════ -->

        
        <div id="tab-inventory" class="tab-section active">
          <div class="rcard">

            <!-- Card Header -->
            <div class="rcard-header">
              <span class="rcard-title">Inventory Overview</span>
              <span style="font-size:12px; color:var(--text-muted);">
                <?= number_format(count($allInventory)) ?> records
              </span>
            </div>

            <!-- ── Filters ── -->
            <div class="toolbars px-2 mb-3">
              <div class="row g-2 align-items-center">

                <!-- Search -->
                <div class="col-12 col-lg-5">
                  <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                      <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="invSearchInput"
                      class="form-control border-start-0 ps-0"
                      placeholder="Search medicine, brand, category...">
                  </div>
                </div>

                <!-- Category Filter -->
                <div class="col-6 col-lg-3">
                  <select id="invFilterCategory" class="form-select border-secondary-subtle w-100">
                    <option value="">All Categories</option>
                    <?php
                      $cats = array_unique(array_column($allInventory, 'Category_Name'));
                      sort($cats);
                      foreach ($cats as $cat): ?>
                        <option value="<?= htmlspecialchars(strtolower($cat)) ?>">
                          <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Status Filter -->
                <div class="col-6 col-lg-2">
                  <select id="invFilterStatus" class="form-select border-secondary-subtle w-100">
                    <option value="">All Status</option>
                    <option value="available">Available</option>
                    <option value="low stock">Low Stock</option>
                    <option value="out of stock">Out of Stock</option>
                    <option value="expired">Expired</option>
                  </select>
                </div>

                <!-- Export CSV -->
                <div class="col-12 col-lg-2 d-flex">
                  <button onclick="exportCSV()"
                    class="btn px-3 rounded-3 d-flex align-items-center justify-content-center gap-1 w-100"
                    style="background-color:#1d9e75; border-color:#1d9e75; color:#fff;">
                    <i class="bi bi-download" style="font-size:1.1rem;"></i>
                    <span class="text-nowrap small">Export CSV</span>
                  </button>
                </div>

              </div>
            </div>
            <!-- ── End Filters ── -->

            <!-- ── Table ── -->
            <div class="table-wrap">
              <table class="section-table" id="tbl-inventory">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Generic Name</th>
                    <th>Brand Name</th>
                    <th>Category</th>
                    <th>Dosage Form</th>
                    <th>Dosage</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Expiry Date</th>
                    <th>Last Updated</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (empty($allInventory)): ?>
                    <tr>
                      <td colspan="11" class="text-center text-muted py-4">
                        <i class="bi bi-inbox d-block mb-1" style="font-size:24px;"></i>
                        No inventory records found.
                      </td>
                    </tr>
                  <?php else: ?>
                    <?php foreach ($allInventory as $i => $inv): ?>
                      <?php
                        $statusClass = match ($inv['Availability_Status']) {
                          'Available'    => 'badge-available',
                          'Low Stock'    => 'badge-low',
                          'Out of Stock' => 'badge-suspended',
                          'Expired'      => 'badge-expired',
                          default        => 'badge-admin'
                        };
                      ?>
                      <tr>
                        <td style="color:var(--text-muted);"><?= $i + 1 ?></td>
                        <td><?= htmlspecialchars($inv['Generic_Name']) ?></td>
                        <td><?= htmlspecialchars($inv['Brand_Name']) ?></td>
                        <td><?= htmlspecialchars($inv['Category_Name']) ?></td>
                        <td><?= htmlspecialchars($inv['Dosage_Form']) ?></td>
                        <td><?= htmlspecialchars($inv['Dosage']) ?></td>
                        <td><?= number_format($inv['Quantity']) ?></td>
                        <td>₱<?= number_format($inv['Price'], 2) ?> / <?= htmlspecialchars($inv['Price_Per']) ?></td>
                        <td><?= date('M d, Y', strtotime($inv['Expiry_date'])) ?></td>
                        <td><?= date('M d, Y', strtotime($inv['Last_updated'])) ?></td>
                        <td>
                          <span class="rbadge <?= $statusClass ?>">
                            <?= htmlspecialchars($inv['Availability_Status']) ?>
                          </span>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
            <!-- ── End Table ── -->

          </div>
        </div>
        <!-- ══ END INVENTORY TAB ════════════════════ -->

      </div>
      <!-- ── End Content ── -->

    </div>
  </div>

  <script>
    // ── Filter: Inventory table ─────────────────────────
    function applyInventoryFilters() {
      const search   = document.getElementById('invSearchInput')?.value.toLowerCase().trim() || '';
      const category = document.getElementById('invFilterCategory')?.value.toLowerCase().trim() || '';
      const status   = document.getElementById('invFilterStatus')?.value.toLowerCase().trim() || '';

      const rows = document.querySelectorAll('#tbl-inventory tbody tr');

      rows.forEach(row => {
        const cells    = row.querySelectorAll('td');
        // 0=#, 1=Generic, 2=Brand, 3=Category, 4=DosageForm, 5=Dosage, 6=Qty, 7=Price, 8=Expiry, 9=LastUpdated, 10=Status
        const generic  = cells[1]?.textContent.toLowerCase() || '';
        const brand    = cells[2]?.textContent.toLowerCase() || '';
        const cat      = cells[3]?.textContent.toLowerCase().trim() || '';
        const rowStat  = cells[10]?.textContent.toLowerCase().trim() || '';

        const matchSearch   = !search   || generic.includes(search) || brand.includes(search) || cat.includes(search);
        const matchCategory = !category || cat === category;
        const matchStatus   = !status   || rowStat.includes(status);

        row.style.display = (matchSearch && matchCategory && matchStatus) ? '' : 'none';
      });
    }

    // ── Safe event listeners ────────────────────────────
    const invSearch   = document.getElementById('invSearchInput');
    const invCategory = document.getElementById('invFilterCategory');
    const invStatus   = document.getElementById('invFilterStatus');

    if (invSearch)   invSearch.addEventListener('input',   applyInventoryFilters);
    if (invCategory) invCategory.addEventListener('change', applyInventoryFilters);
    if (invStatus)   invStatus.addEventListener('change',   applyInventoryFilters);

    // ── Export CSV ──────────────────────────────────────
    function exportCSV() {
      const table = document.getElementById('tbl-inventory');
      if (!table) return;

      const rows = [...table.querySelectorAll('tr')].filter(row => {
        // only export visible rows (respects filters)
        const tr = row.closest('tbody tr');
        return !tr || tr.style.display !== 'none';
      });

      let csv = [];
      table.querySelectorAll('thead tr, tbody tr').forEach(row => {
        if (row.closest('tbody') && row.style.display === 'none') return;
        const cols = [...row.querySelectorAll('th, td')].map(c =>
          '"' + c.innerText.trim().replace(/"/g, '""') + '"'
        );
        csv.push(cols.join(','));
      });

      const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
      const a    = document.createElement('a');
      a.href     = URL.createObjectURL(blob);
      a.download = 'inventory_report_' + new Date().toISOString().slice(0, 10) + '.csv';
      a.click();
    }

    // ── Stats toggle ────────────────────────────────────
    function toggleStatsCards() {
      const hero    = document.getElementById('statsHero');
      const label   = document.getElementById('statsToggleLabel');
      const chevron = document.getElementById('statsChevron');
      if (!hero) return;
      const isVisible = !hero.classList.contains('collapsed');
      hero.classList.toggle('collapsed', isVisible);
      label.textContent = isVisible ? 'Expand' : 'Collapse';
      chevron.classList.toggle('rotated', isVisible);
    }
  </script>

</body>
</html>