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
  <title>Dashboard</title>

  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>


  <!-- STYLES -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />


  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/03_SystemAdminCSS/reports-style.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-body-css.css">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    @media (max-width: 991px) {
      .calendar-widget {
        display: hidden;
      }

      .stat-card .card-sub {
        font-size: 0.8rem;
        /* Adjust as needed for smaller screens */
      }
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
  </style>

</head>

<body>



  <div class="wrapper d-flex align-items-stretch">
    <div id="system-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>


      <!-- ── Page Header ───────────────────────────────────────── -->
      <!-- <div class="page-header">
                <h4><i class="bi bi-bar-chart-line me-2" style="color:var(--green);"></i>Reports</h4>
                <div class="controls">
                    <select id="dateFilter" onchange="applyDateFilter()">
                        <option value="all">All Time</option>
                        <option value="month" selected>This Month</option>
                        <option value="year">This Year</option>
                    </select>
                    <button class="btn-export" onclick="exportCSV()">
                        <i class="bi bi-download"></i> Export CSV
                    </button>
                </div>
            </div> -->

      <!-- ── Tabs ─────────────────────────────────────────────── -->
      <div class="report-tabs">
        <button class="rtab active" onclick="switchTab('overview', this)">Overview</button>
        <button class="rtab" onclick="switchTab('users', this)">Users</button>
        <button class="rtab" onclick="switchTab('pharmacies', this)">Pharmacies</button>
        <button class="rtab" onclick="switchTab('inventory', this)">Inventory</button>
      </div>

      <!-- ── Content ──────────────────────────────────────────── -->
      <div class="report-content">

        <!-- ══ OVERVIEW TAB ════════════════════════════════════ -->
        <div id="tab-overview" class="tab-section active">

          <!-- Stat Cards -->
          <div class="stat-grid">
            <div class="stat-card">
              <div class="sc-label">Total Users</div>
              <div class="sc-value"><?= number_format($totalUsers) ?></div>
              <div class="sc-sub sc-up"><i class="bi bi-arrow-up-short"></i> <?= $newUsersMonth ?> this
                month</div>
            </div>
            <div class="stat-card">
              <div class="sc-label">Active Pharmacies</div>
              <div class="sc-value"><?= number_format($totalActive) ?></div>
              <div class="sc-sub sc-up"><i class="bi bi-check-circle"></i> Approved</div>
            </div>
            <div class="stat-card">
              <div class="sc-label">Pending Approvals</div>
              <div class="sc-value"><?= number_format($totalPending) ?></div>
              <div class="sc-sub <?= $totalPending > 0 ? 'sc-down' : 'sc-up' ?>">
                <?= $totalPending > 0 ? '<i class="bi bi-exclamation-circle"></i> Needs action' : '<i class="bi bi-check"></i> All clear' ?>
              </div>
            </div>
            <div class="stat-card">
              <div class="sc-label">Expired Stocks</div>
              <div class="sc-value"><?= number_format($totalExpired) ?></div>
              <div class="sc-sub <?= $totalExpired > 0 ? 'sc-down' : 'sc-up' ?>">
                <?= $totalExpired > 0 ? '<i class="bi bi-exclamation-triangle"></i> Across pharmacies' : '<i class="bi bi-check"></i> None expired' ?>
              </div>
            </div>
          </div>


          <!-- 2-col cards -->
          <div class="row g-3">

          
            <!-- Users by Role Bar Chart -->
            <div class="col-md-12">
              <div class="rcard">
                <div class="rcard-header">
                  <span class="rcard-title">Users by Role</span>
                </div>
                <?php
                $maxCount = max(1, $totalUsers);
                $bars = [
                  ['label' => 'Patients', 'count' => $totalPatients, 'color' => '#378ADD'],
                  ['label' => 'Pharmacists', 'count' => $totalPharmacist, 'color' => '#1D9E75'],
                  ['label' => 'Owners', 'count' => $totalOwner, 'color' => '#EF9F27'],
                  ['label' => 'Admins', 'count' => $totalAdmin, 'color' => '#7F77DD'],
                ];
                ?>
                <!-- MAO NIIIIIII -->
                <?php foreach ($bars as $bar): ?>
                  <div class="bar-row">
                    <span class="bar-label"><?= $bar['label'] ?></span>
                    <div class="bar-track">
                      <div class="bar-fill"
                        style="width:<?= round($bar['count'] / $maxCount * 100) ?>%; background:<?= $bar['color'] ?>;">
                      </div>
                    </div>
                    <span class="bar-count"><?= number_format($bar['count']) ?></span>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>


            

            <!-- Pending Pharmacies -->
            <div class="col-md-6">
              <div class="rcard">
                <div class="rcard-header">
                  <span class="rcard-title">Pending Pharmacy Approvals</span>
                  <a class="rcard-link" onclick="switchTab('pharmacies', document.querySelectorAll('.rtab')[2])">View
                    all</a>
                </div>
                <?php if (empty($pendingPharmacies)): ?>
                  <div class="empty-state"><i class="bi bi-check-circle text-success d-block mb-1"
                      style="font-size:20px;"></i>No pending approvals</div>
                <?php else: ?>
                  <?php foreach ($pendingPharmacies as $p): ?>
                    <div class="rrow">
                      <div>
                        <div class="rrow-name"><?= htmlspecialchars($p['Pharmacy_name']) ?></div>
                        <div class="rrow-meta">Owner: <?= htmlspecialchars($p['Owner_name']) ?></div>
                      </div>
                      <span class="rbadge badge-pending">Pending</span>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>

            <!-- Suspended Pharmacies -->
            <div class="col-md-6">
              <div class="rcard">
                <div class="rcard-header">
                  <span class="rcard-title">Suspended Pharmacies</span>
                  <a class="rcard-link" onclick="switchTab('pharmacies', document.querySelectorAll('.rtab')[2])">View
                    all</a>
                </div>
                <?php if (empty($suspendedPharmacies)): ?>
                  <div class="empty-state">
                    <i class="bi bi-check-circle text-success d-block mb-1" style="font-size:20px;"></i>
                    No suspended pharmacies
                  </div>
                <?php else: ?>
                  <?php foreach ($suspendedPharmacies as $p): ?>
                    <div class="rrow">
                      <div>
                        <div class="rrow-name"><?= htmlspecialchars($p['Pharmacy_name']) ?></div>
                        <div class="rrow-meta">Owner: <?= htmlspecialchars($p['Owner_name']) ?></div>
                      </div>
                      <span class="rbadge badge-suspended">Suspended</span>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>




            <!-- Suspended Users
          <div class="col-md-6">
            <div class="rcard">
              <div class="rcard-header">
                <span class="rcard-title">Suspended Users</span>
                <a class="rcard-link" onclick="switchTab('users', document.querySelectorAll('.rtab')[1])">View all</a>
              </div>
              <?php if (empty($suspendedUsers)): ?>
                <div class="empty-state"><i class="bi bi-check-circle text-success d-block mb-1"
                    style="font-size:20px;"></i>No suspended users</div>
              <?php else: ?>
                <?php foreach ($suspendedUsers as $u): ?>
                  <?php
                  $initials = strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', trim($u['Full_Name'])))));
                  $initials = substr($initials, 0, 2);
                  $roleClass = match (strtolower($u['Role'] ?? '')) {
                    'patient' => 'badge-patient',
                    'pharmacist' => 'badge-pharmacist',
                    'pharmacy owner' => 'badge-owner',
                    default => 'badge-admin'
                  };
                  ?>
                  <div class="rrow">
                    <div>
                      <div class="rrow-name"><?= htmlspecialchars($u['Full_Name']) ?></div>
                      <div class="rrow-meta"><?= htmlspecialchars($u['Role']) ?></div>
                    </div>
                    <span class="rbadge badge-suspended">Suspended</span>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div> -->



          </div>
        </div><!-- end overview -->


        <!-- ══ USERS TAB ═══════════════════════════════════════ -->
        <div id="tab-users" class="tab-section">
          <div class="rcard">
            <div class="rcard-header">
              <span class="rcard-title">All Users</span>
              <span style="font-size:12px; color:var(--text-muted);"><?= number_format(count($allUsers)) ?>
                total</span>
            </div>

            <!-- FILTERS -->

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
                  <select id="filterRole" class="form-select border-secondary-subtle w-100">
                    <option value="">All Roles</option>
                    <option value="Patient/Client">Patient/Client</option>
                    <option value="Pharmacy">Pharmacy Admin</option>
                  </select>
                </div>

                <!-- Status -->
                <div class="col-6 col-lg-3">
                  <select id="filterStatus" class="form-select border-secondary-subtle w-100">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Suspended">Suspended</option>
                    <option value="Inactive">Inactive</option>
                  </select>
                </div>

                <!-- Export CSV -->
                <div class="col-12 col-lg-2 d-flex">
                  <button id="exportBtn" onclick="exportCSV()"
                    class="btn px-3 rounded-3 d-flex align-items-center justify-content-center gap-1 w-100"
                    style="background-color:#1d9e75 !important; border-color:#1d9e75 !important; color:#fff;">
                    <i class="bi bi-download" style="font-size:1.1rem;"></i>
                    <span class="text-nowrap small">Export CSV</span>
                  </button>
                </div>

              </div>
            </div>

            <!-- END FILTER -->




            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; overflow-y: auto;">
              <table class="section-table" id="tbl-users">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date Registered</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($allUsers as $i => $u): ?>
                    <?php
                    $roleClass = match (strtolower($u['Role'] ?? '')) {
                      'patient' => 'badge-patient',
                      'pharmacist' => 'badge-pharmacist',
                      'pharmacy owner' => 'badge-owner',
                      default => 'badge-admin'
                    };
                    $statusClass = match (strtolower($u['UserStatus'] ?? '')) {
                      'active' => 'badge-approved',
                      'suspended' => 'badge-suspended',
                      default => 'badge-admin'
                    };
                    ?>
                    <tr>
                      <td style="color:var(--text-muted);"><?= $i + 1 ?></td>
                      <td><?= htmlspecialchars($u['Full_Name']) ?></td>
                      <td><?= htmlspecialchars($u['Email']) ?></td>
                      <td><?= htmlspecialchars($u['Phone'] ?? '—') ?></td>
                      <td><span class="rbadge <?= $roleClass ?>"><?= htmlspecialchars($u['Role']) ?></span>
                      </td>
                      <td><span class="rbadge <?= $statusClass ?>"><?= htmlspecialchars($u['UserStatus']) ?></span>
                      </td>
                      <td><?= htmlspecialchars(date('M d, Y', strtotime($u['DateCreated']))) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div><!-- end users -->


        <!-- ══ PHARMACIES TAB ══════════════════════════════════ -->
        <div id="tab-pharmacies" class="tab-section">
          <div class="rcard">
            <div class="rcard-header">
              <span class="rcard-title">All Pharmacies</span>
              <span style="font-size:12px; color:var(--text-muted);"><?= number_format(count($allPharmacies)) ?>
                total</span>
            </div>

            <!-- FILTERS -->
            <div class="toolbars px-2">
              <div class="row g-2 align-items-center mb-3">

                <!-- Search -->
                <div class="col-12 col-lg-5">
                  <div class="input-group searchbar-group">
                    <span class="input-group-text search-icon bg-white border-end-0">
                      <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="phSearchInput" class="form-control searchbar border-start-0 ps-0"
                      placeholder="Search pharmacy...">
                  </div>
                </div>

                <!-- City -->
                <div class="col-6 col-lg-3">
                  <select id="phFilterCity" class="form-select border-secondary-subtle w-100">
                    <option value="">All Cities</option>
                    <?php
                    $cities = array_unique(array_column($allPharmacies, 'City_Name'));
                    sort($cities);
                    foreach ($cities as $city): ?>
                      <option value="<?= htmlspecialchars($city) ?>"><?= htmlspecialchars($city) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <!-- Status -->
                <div class="col-6 col-lg-2">
                  <select id="phFilterStatus" class="form-select border-secondary-subtle w-100">
                    <option value="">All Status</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                    <option value="rejected">Rejected</option>
                  </select>
                </div>

                <!-- Export CSV -->
                <div class="col-12 col-lg-2 d-flex">
                  <button onclick="exportCSV()"
                    class="btn px-3 rounded-3 d-flex align-items-center justify-content-center gap-1 w-100"
                    style="background-color:#1d9e75 !important; border-color:#1d9e75 !important; color:#fff;">
                    <i class="bi bi-download" style="font-size:1.1rem;"></i>
                    <span class="text-nowrap small">Export CSV</span>
                  </button>
                </div>

              </div>
            </div>
            <!-- END FILTER -->


            <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
              <table class="section-table" id="tbl-pharmacies">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Pharmacy</th>
                    <th>Owner</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Status</th>
                    <th>Date Created</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($allPharmacies as $i => $p): ?>
                    <?php
                    $statusClass = match (strtolower($p['Approval_Status'] ?? '')) {
                      'approved' => 'badge-approved',
                      'pending' => 'badge-pending',
                      'rejected' => 'badge-suspended',
                      default => 'badge-admin'
                    };
                    ?>
                    <tr>
                      <td style="color:var(--text-muted);"><?= $i + 1 ?></td>
                      <td><?= htmlspecialchars($p['Pharmacy_name']) ?></td>
                      <td><?= htmlspecialchars($p['Owner_name']) ?></td>
                      <td><?= htmlspecialchars($p['Owner_Email'] ?? '—') ?></td>
                      <td><?= htmlspecialchars($p['Phone'] ?? '—') ?></td>
                      <td><?= htmlspecialchars($p['City_Name'] ?? '—') ?></td>
                      <td><span class="rbadge <?= $statusClass ?>"><?= htmlspecialchars($p['Approval_Status']) ?></span>
                      </td>
                      <td><?= htmlspecialchars(date('M d, Y', strtotime($p['DateCreated']))) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>


          </div>
        </div><!-- end pharmacies -->



      </div><!-- end report-content -->

    </div>
  </div>



  <!-- FILTER SCRIPT -->
  <script>
    // ── Users Table Filter ───────────────────────────────────
    function filterUsersTable() {
      const search = document.getElementById('searchInput').value.toLowerCase().trim();
      const role = document.getElementById('filterRole').value.toLowerCase().trim();
      const status = document.getElementById('filterStatus').value.toLowerCase().trim();

      const rows = document.querySelectorAll('#tbl-users tbody tr');

      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const name = cells[1]?.innerText.toLowerCase() ?? '';
        const email = cells[2]?.innerText.toLowerCase() ?? '';
        const phone = cells[3]?.innerText.toLowerCase() ?? '';
        const rowRole = cells[4]?.innerText.toLowerCase() ?? '';
        const rowStat = cells[5]?.innerText.toLowerCase() ?? '';

        const matchSearch = !search || name.includes(search) || email.includes(search) || phone.includes(
          search);
        const matchRole = !role || rowRole.includes(role);
        const matchStatus = !status || rowStat.includes(status);

        row.style.display = (matchSearch && matchRole && matchStatus) ? '' : 'none';
      });
    }

    // Wire up all three filters to the same function
    document.getElementById('searchInput').addEventListener('input', filterUsersTable);
    document.getElementById('filterRole').addEventListener('change', filterUsersTable);
    document.getElementById('filterStatus').addEventListener('change', filterUsersTable);
  </script>
  <!-- Filter Pharmacy -->

  <script>
    // Filter function for Pharmacies table
    function applyPharmacyFilters() {
      const search = document.getElementById('phSearchInput').value.toLowerCase().trim();
      const city = document.getElementById('phFilterCity').value.toLowerCase().trim();
      const status = document.getElementById('phFilterStatus').value.toLowerCase().trim();

      const rows = document.querySelectorAll('#tbl-pharmacies tbody tr');

      rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        // 0=#, 1=Pharmacy, 2=Owner, 3=Email, 4=Phone, 5=City, 6=Status, 7=Date
        const pharmacy = cells[1]?.textContent.toLowerCase() || '';
        const owner = cells[2]?.textContent.toLowerCase() || '';
        const email = cells[3]?.textContent.toLowerCase() || '';
        const rowCity = cells[5]?.textContent.toLowerCase().trim() || '';
        const rowStat = cells[6]?.textContent.toLowerCase().trim() || '';

        const matchSearch = !search || pharmacy.includes(search) || owner.includes(search) || email.includes(search) || rowCity.includes(search);
        const matchCity = !city || rowCity === city;
        const matchStatus = !status || rowStat.includes(status);

        row.style.display = (matchSearch && matchCity && matchStatus) ? '' : 'none';
      });
    }

    document.getElementById('phSearchInput').addEventListener('input', applyPharmacyFilters);
    document.getElementById('phFilterCity').addEventListener('change', applyPharmacyFilters);
    document.getElementById('phFilterStatus').addEventListener('change', applyPharmacyFilters);


  </script>

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


  <script>
    // ── Tab Switching ────────────────────────────────────────
    function switchTab(name, btn) {
      document.querySelectorAll('.tab-section').forEach(s => s.classList.remove('active'));
      document.querySelectorAll('.rtab').forEach(t => t.classList.remove('active'));
      document.getElementById('tab-' + name).classList.add('active');
      btn.classList.add('active');
    }

    // ── Export CSV ───────────────────────────────────────────
    function exportCSV() {
      const activeTab = document.querySelector('.tab-section.active');
      const table = activeTab.querySelector('table');
      if (!table) {
        alert('No table to export on this tab.');
        return;
      }

      let csv = [];
      table.querySelectorAll('tr').forEach(row => {
        const cols = [...row.querySelectorAll('th, td')].map(c =>
          '"' + c.innerText.trim().replace(/"/g, '""') + '"'
        );
        csv.push(cols.join(','));
      });

      const tabName = document.querySelector('.rtab.active').innerText.trim();
      const blob = new Blob([csv.join('\n')], {
        type: 'text/csv'
      });
      const a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = 'medifind_report_' + tabName.toLowerCase() + '_' + new Date().toISOString().slice(0, 10) + '.csv';
      a.click();
    }

    // ── Date Filter (visual hint — full server-side filter optional) ──
    function applyDateFilter() {
      const val = document.getElementById('dateFilter').value;
      console.log('Filter by:', val);
      // Extend: reload page with ?filter=month etc. for server-side filtering
    }
  </script>



</body>

</html>