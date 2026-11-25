<?php
// ==============================================================================
// 06_SystemAdmin/01_Dashboard.php
// LGU Overview Dashboard - Malaybalay City Healthcare & Pharmacy Monitoring
// ==============================================================================

// Session & Environment
include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

// Auth Guard: Logged in & Role 4 (System Admin / LGU Admin)
if (empty($_SESSION['user_id'])) {
  header('Location: ../03_Authentication/login.php');
  exit;
}

// Preserve existing includes for compatibility
require_once '../02_Actions/04_System-Admin-CRUD/select-count.php';
require_once '../02_Actions/04_System-Admin-CRUD/reports.php';

// Load Dedicated Read-Only LGU Dashboard Data
require_once '../02_Actions/04_System-Admin-CRUD/lgu-dashboard-data.php';
?>
<!doctype html>
<html lang="en" class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <title>LGU Overview Dashboard - MediFind Malaybalay</title>

  <!-- Google Fonts: Inter & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
    rel="stylesheet">

  <!-- Core Icons & Libraries (Pinned) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

  <!-- Design System & Theme Tokens (User-Side Source of Truth) -->
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/theme-tokens.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/admin-buttons.css" />
  <link rel="stylesheet" href="../07_Assets/css/03_SystemAdminCSS/dashboard.css" />

  <!-- Navigation Scripts -->
  <script src="../07_Assets/css/js/sidebar_and_topbar.js?v=<?= time() ?>"></script>

  <!-- Page Transition -->
  <?php include '../01_Includes/page-transition-hardcode.php'; ?>
</head>

<body data-active="01" class="lgu-dashboard-page">
  <div class="wrapper d-flex align-items-stretch">
    <!-- Dynamic System Sidebar -->
    <div id="system-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <!-- Dynamic Topbar -->
      <div id="topbar-container"></div>

      <div class="content flex-grow-1">
        <div class="lgu-dashboard-wrapper">

          <?php if (!empty($lguDbError)): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4 rounded-3" role="alert">
              <i class="bi bi-exclamation-triangle-fill fs-5"></i>
              <div>
                <strong>Database Error:</strong> <?= htmlspecialchars($lguDbError) ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- ══ HERO BANNER ═══════════════════════════════════════ -->
          <div class="lgu-hero-banner">
            <div class="row align-items-center g-3">
              <div class="col-12 col-lg-8">
                <span class="lgu-badge-pill">
                  <span class="material-symbols-outlined" style="font-size:16px;">account_balance</span>
                  City Government of Malaybalay · Health Office
                </span>
                <h1 class="lgu-hero-title">LGU Healthcare &amp; Pharmacy Monitoring</h1>
                <p class="lgu-hero-subtitle">
                  <span><i class="bi bi-geo-alt-fill me-1"></i> Malaybalay City, Bukidnon</span>
                  <span>•</span>
                  <span>Real-time retail pharmacy accreditation, stock surveillance &amp; citizen registries</span>
                </p>
              </div>
              <div class="col-12 col-lg-4 d-flex justify-content-lg-end gap-2 flex-wrap">
                <a href="../06_SystemAdmin/03_Pharmacies.php?status=pending"
                  class="quick-action-btn btn-cta-orange text-nowrap">
                  <span class="material-symbols-outlined" style="font-size:1.1rem;">pending_actions</span>
                  <span>Review Pending (<?= $lguPharmPending ?>)</span>
                </a>
                <a href="../06_SystemAdmin/04_Reports.php" class="quick-action-btn btn-cta-outline text-nowrap">
                  <span class="material-symbols-outlined" style="font-size:1.1rem;">summarize</span>
                  <span>Reports</span>
                </a>
              </div>
            </div>
          </div>

          <!-- ══ SECTION 1: CORE STAT CARDS ════════════════════════ -->
          <div class="lgu-section-header">
            <div>
              <h2 class="lgu-section-title">
                <span class="material-symbols-outlined" style="color:var(--mf-green);">monitoring</span>
                Key Healthcare Indicators
              </h2>
              <div class="lgu-section-subtitle">City-wide surveillance metrics across users and accredited dispensaries
              </div>
            </div>
          </div>

          <div class="row g-3 g-xl-4 mb-4">

            <!-- 1. Total Users -->
            <div class="col-12 col-sm-6 col-xl-3">
              <div class="lgu-card">
                <div>
                  <div class="lgu-card-header">
                    <span class="lgu-card-label">Total Users</span>
                    <div class="lgu-card-icon icon-emerald">
                      <span class="material-symbols-outlined">supervisor_account</span>
                    </div>
                  </div>
                  <div class="lgu-card-value"><?= number_format($lguTotalUsers) ?></div>
                </div>
                <div class="mt-2">
                  <span class="badge-pill-soft badge-pill-green mb-1">
                    <i class="bi bi-arrow-up-short"></i> +<?= number_format($lguNewUsersMonth) ?> this month
                  </span>
                  <div class="lgu-card-sub">Registered patients, pharmacists &amp; staff</div>
                </div>
              </div>
            </div>

            <!-- 2. Total Pharmacies -->
            <div class="col-12 col-sm-6 col-xl-3">
              <div class="lgu-card">
                <div>
                  <div class="lgu-card-header">
                    <span class="lgu-card-label">Total Pharmacies</span>
                    <div class="lgu-card-icon icon-blue">
                      <span class="material-symbols-outlined">local_hospital</span>
                    </div>
                  </div>
                  <div class="lgu-card-value"><?= number_format($lguTotalPharmacies) ?></div>
                </div>
                <div class="mt-2">
                  <span class="badge-pill-soft badge-pill-muted mb-1">
                    <?= $lguPharmApproved ?> Approved · <?= $lguPharmPending ?> Pending · <?= $lguPharmSuspended ?>
                    Suspended
                  </span>
                  <div class="lgu-card-sub">Registered retail drugstores in Malaybalay</div>
                </div>
              </div>
            </div>

            <!-- 3. Pending Pharmacy Requests (Clickable) -->
            <div class="col-12 col-sm-6 col-xl-3">
              <a href="../06_SystemAdmin/03_Pharmacies.php?status=pending" class="lgu-card clickable-card">
                <div>
                  <div class="lgu-card-header">
                    <span class="lgu-card-label">Pending Requests</span>
                    <div class="lgu-card-icon icon-orange">
                      <span class="material-symbols-outlined">hourglass_top</span>
                    </div>
                  </div>
                  <div class="lgu-card-value" style="color:var(--mf-orange);"><?= number_format($lguPharmPending) ?>
                  </div>
                </div>
                <div class="mt-2">
                  <span class="badge-pill-soft badge-pill-orange mb-1">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Click to Review
                  </span>
                  <div class="lgu-card-sub">
                    <?= $lguPharmPending > 0 ? 'Awaiting LGU accreditation & inspection' : 'All applications currently processed' ?>
                  </div>
                </div>
              </a>
            </div>

            <!-- 4. Active & Inactive Users -->
            <div class="col-12 col-sm-6 col-xl-3">
              <div class="lgu-card">
                <div>
                  <div class="lgu-card-header">
                    <span class="lgu-card-label">Active Users</span>
                    <div class="lgu-card-icon icon-emerald">
                      <span class="material-symbols-outlined">verified_user</span>
                    </div>
                  </div>
                  <div class="lgu-card-value"><?= number_format($lguActiveUsers) ?></div>
                </div>
                <div class="mt-2">
                  <span class="badge-pill-soft badge-pill-muted mb-1">
                    <?= $lguActiveUsers ?> Active · <?= ($lguInactiveUsers + $lguSuspendedUsers) ?> Inactive/Suspended
                  </span>
                  <div class="lgu-card-sub">Current account activity distribution</div>
                </div>
              </div>
            </div>

            <!-- 5. Patients registered this month -->
            <div class="col-12 col-sm-6 col-xl-3">
              <div class="lgu-card">
                <div>
                  <div class="lgu-card-header">
                    <span class="lgu-card-label">Patients (<?= date('M') ?>)</span>
                    <div class="lgu-card-icon icon-cyan">
                      <span class="material-symbols-outlined">person_add</span>
                    </div>
                  </div>
                  <div class="lgu-card-value"><?= number_format($lguPatientsThisMonth) ?></div>
                </div>
                <div class="mt-2">
                  <span class="badge-pill-soft badge-pill-green mb-1">
                    <i class="bi bi-calendar-check me-1"></i> New Community Patients
                  </span>
                  <div class="lgu-card-sub">Citizen registrations in <?= date('F Y') ?></div>
                </div>
              </div>
            </div>

            <!-- 6. Pharmacies with low-stock or out-of-stock items -->
            <div class="col-12 col-sm-6 col-xl-3">
              <div class="lgu-card">
                <div>
                  <div class="lgu-card-header">
                    <span class="lgu-card-label">Low Stock Stores</span>
                    <div class="lgu-card-icon icon-amber">
                      <span class="material-symbols-outlined">inventory_2</span>
                    </div>
                  </div>
                  <div class="lgu-card-value" style="color:#b45309;"><?= number_format($lguPharmaciesWithLowStock) ?>
                  </div>
                </div>
                <div class="mt-2">
                  <span class="badge-pill-soft badge-pill-orange mb-1">
                    <i class="bi bi-exclamation-circle me-1"></i> Inventory Restock Needed
                  </span>
                  <div class="lgu-card-sub">Pharmacies carrying items &le; 10 units or expired</div>
                </div>
              </div>
            </div>

            <!-- 7. Medicines available across the city -->
            <div class="col-12 col-sm-6 col-xl-3">
              <div class="lgu-card">
                <div>
                  <div class="lgu-card-header">
                    <span class="lgu-card-label">City Medicine Catalog</span>
                    <div class="lgu-card-icon icon-emerald">
                      <span class="material-symbols-outlined">medication</span>
                    </div>
                  </div>
                  <div class="lgu-card-value"><?= number_format($lguMedicinesAvailableCity) ?></div>
                </div>
                <div class="mt-2">
                  <span class="badge-pill-soft badge-pill-green mb-1">
                    <i class="bi bi-check2-circle me-1"></i> Ready for POS &amp; Dispensary
                  </span>
                  <div class="lgu-card-sub">Distinct medicines with stock &gt; 0 in Malaybalay</div>
                </div>
              </div>
            </div>

            <!-- 8. Medicines out of stock in ALL pharmacies (Shortage Alert) -->
            <div class="col-12 col-sm-6 col-xl-3">
              <div class="lgu-card card-alert-shortage">
                <div>
                  <div class="lgu-card-header">
                    <span class="lgu-card-label text-danger fw-bold">City-Wide Shortages</span>
                    <div class="lgu-card-icon icon-rose">
                      <span class="material-symbols-outlined">warning</span>
                    </div>
                  </div>
                  <div class="lgu-card-value text-danger"><?= number_format($lguMedicinesCityShortage) ?></div>
                </div>
                <div class="mt-2">
                  <span class="badge-pill-soft badge-pill-red mb-1">
                    <i class="bi bi-shield-exclamation me-1"></i> 0 Units in All Stores
                  </span>
                  <div class="lgu-card-sub">
                    <?= $lguMedicinesCityShortage > 0 ? 'Critical supply shortfall requiring LGU procurement' : 'No critical city-wide shortages detected' ?>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- ══ SECTION 2: CHARTS & ANALYTICS ════════════════════ -->
          <div class="lgu-section-header">
            <div>
              <h2 class="lgu-section-title">
                <span class="material-symbols-outlined" style="color:var(--mf-green);">insights</span>
                Healthcare &amp; Supply Analytics
              </h2>
              <div class="lgu-section-subtitle">Visual breakdown of user demographics, pharmacy geographic distribution,
                and supply coverage</div>
            </div>
          </div>

          <!-- Row 1 Charts: Users by Role, Registrations Trend, Pharmacies by Status -->
          <div class="row g-3 g-xl-4 mb-4">

            <!-- Chart 1: Users by Role -->
            <div class="col-12 col-lg-4">
              <div class="lgu-chart-card">
                <div class="lgu-chart-header">
                  <div>
                    <h3 class="lgu-chart-title">Users by Role</h3>
                    <div class="lgu-chart-meta"><?= number_format($lguTotalUsers) ?> total registered accounts</div>
                  </div>
                </div>
                <div class="chart-canvas-wrapper d-flex align-items-center justify-content-center">
                  <?php if (empty($lguUsersByRole)): ?>
                    <div class="empty-state-box">
                      <span class="material-symbols-outlined empty-state-icon">pie_chart</span>
                      <div class="empty-state-text">No user role data available yet</div>
                    </div>
                  <?php else: ?>
                    <canvas id="usersRoleChart" style="max-height:240px;"></canvas>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- Chart 2: New Registrations Trend (Last 6 Months) -->
            <div class="col-12 col-lg-5">
              <div class="lgu-chart-card">
                <div class="lgu-chart-header">
                  <div>
                    <h3 class="lgu-chart-title">Citizen Registrations (Last 6 Months)</h3>
                    <div class="lgu-chart-meta">Monthly adoption trajectory across Malaybalay</div>
                  </div>
                </div>
                <div class="chart-canvas-wrapper">
                  <canvas id="monthlyRegChart" style="max-height:240px;"></canvas>
                </div>
              </div>
            </div>

            <!-- Chart 3: Pharmacies by Status -->
            <div class="col-12 col-lg-3">
              <div class="lgu-chart-card">
                <div class="lgu-chart-header">
                  <div>
                    <h3 class="lgu-chart-title">Accreditation Status</h3>
                    <div class="lgu-chart-meta"><?= number_format($lguTotalPharmacies) ?> pharmacies total</div>
                  </div>
                </div>
                <div class="chart-canvas-wrapper d-flex align-items-center justify-content-center">
                  <canvas id="pharmacyStatusChart" style="max-height:240px;"></canvas>
                </div>
              </div>
            </div>

          </div>

          <!-- Row 2 Charts: Pharmacies by Barangay & Top Medicines -->
          <div class="row g-3 g-xl-4 mb-4">

            <!-- Chart 4: Pharmacies by Barangay -->
            <div class="col-12 col-lg-6">
              <div class="lgu-chart-card">
                <div class="lgu-chart-header">
                  <div>
                    <h3 class="lgu-chart-title">Pharmacy Geographic Distribution</h3>
                    <div class="lgu-chart-meta">Accredited pharmacies grouped by Malaybalay City barangay</div>
                  </div>
                </div>
                <div class="chart-canvas-wrapper">
                  <?php if (empty($lguPharmByBarangay)): ?>
                    <div class="empty-state-box">
                      <span class="material-symbols-outlined empty-state-icon">location_off</span>
                      <div class="empty-state-text">No location data mapped for pharmacies yet</div>
                    </div>
                  <?php else: ?>
                    <canvas id="pharmacyBarangayChart" style="max-height:260px;"></canvas>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <!-- Chart 5: Top Medicines Availability -->
            <div class="col-12 col-lg-6">
              <div class="lgu-chart-card">
                <div class="lgu-chart-header">
                  <div>
                    <h3 class="lgu-chart-title">Top Medicines by Retail Availability</h3>
                    <div class="lgu-chart-meta">Number of distinct pharmacies stocking each essential generic</div>
                  </div>
                </div>
                <div class="chart-canvas-wrapper">
                  <?php if (empty($lguTopMedicines)): ?>
                    <div class="empty-state-box">
                      <span class="material-symbols-outlined empty-state-icon">medication_liquid</span>
                      <div class="empty-state-text">No active inventory stocks recorded yet</div>
                    </div>
                  <?php else: ?>
                    <canvas id="topMedicinesChart" style="max-height:260px;"></canvas>
                  <?php endif; ?>
                </div>
              </div>
            </div>

          </div>

          <!-- ══ SECTION 3: RECENT ACTIVITY & CALENDAR ════════════ -->
          <div class="lgu-section-header">
            <div>
              <h2 class="lgu-section-title">
                <span class="material-symbols-outlined" style="color:var(--mf-green);">history</span>
                Recent Activity &amp; Schedule
              </h2>
              <div class="lgu-section-subtitle">Real-time submissions, user registrations, and municipal calendar</div>
            </div>
          </div>

          <div class="row g-3 g-xl-4">

            <!-- Panel 1: Latest Pharmacy Applications -->
            <div class="col-12 col-xl-4">
              <div class="lgu-table-card">
                <div class="lgu-table-header">
                  <div>
                    <h3 class="lgu-chart-title">Latest Pharmacy Submissions</h3>
                    <div class="lgu-chart-meta">Recent registrations awaiting inspection</div>
                  </div>
                  <a href="../06_SystemAdmin/03_Pharmacies.php" class="dash-see-all-link small">View All &rarr;</a>
                </div>
                <div class="table-responsive">
                  <table class="lgu-table">
                    <thead>
                      <tr>
                        <th>Pharmacy</th>
                        <th>Applicant</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($lguRecentPharmacies)): ?>
                        <tr>
                          <td colspan="3" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox d-block fs-3 mb-1"></i> No pharmacy submissions found.
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($lguRecentPharmacies as $rp): ?>
                          <?php
                          $badge = match ($rp['Approval_Status']) {
                            'Approved' => 'badge-pill-green',
                            'Pending' => 'badge-pill-orange',
                            'Suspended' => 'badge-pill-red',
                            default => 'badge-pill-muted'
                          };
                          ?>
                          <tr>
                            <td class="fw-bold text-dark"><?= htmlspecialchars($rp['Pharmacy_name']) ?></td>
                            <td><?= htmlspecialchars($rp['Owner_name']) ?></td>
                            <td>
                              <span class="badge-pill-soft <?= $badge ?>">
                                <?= htmlspecialchars($rp['Approval_Status']) ?>
                              </span>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Panel 2: Newest Registered Users -->
            <div class="col-12 col-xl-4">
              <div class="lgu-table-card">
                <div class="lgu-table-header">
                  <div>
                    <h3 class="lgu-chart-title">Newest User Registrations</h3>
                    <div class="lgu-chart-meta">Recently created accounts</div>
                  </div>
                  <a href="../06_SystemAdmin/02_Manage-Users.php" class="dash-see-all-link small">Manage &rarr;</a>
                </div>
                <div class="table-responsive">
                  <table class="lgu-table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (empty($lguRecentUsers)): ?>
                        <tr>
                          <td colspan="3" class="text-center py-4 text-muted">
                            <i class="bi bi-people d-block fs-3 mb-1"></i> No users registered yet.
                          </td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($lguRecentUsers as $ru): ?>
                          <?php
                          $statusBadge = match ($ru['UserStatus']) {
                            'Active' => 'badge-pill-green',
                            'Suspended' => 'badge-pill-red',
                            default => 'badge-pill-muted'
                          };
                          ?>
                          <tr>
                            <td class="fw-semibold text-dark"><?= htmlspecialchars($ru['Full_Name']) ?></td>
                            <td><span class="small text-muted"><?= htmlspecialchars($ru['Role']) ?></span></td>
                            <td>
                              <span class="badge-pill-soft <?= $statusBadge ?>">
                                <?= htmlspecialchars($ru['UserStatus']) ?>
                              </span>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Panel 3: Restyled Calendar -->
            <div class="col-12 col-xl-4">
              <div class="lgu-chart-card">
                <div class="lgu-chart-header">
                  <div>
                    <h3 class="lgu-chart-title">Municipal Calendar</h3>
                    <div class="lgu-chart-meta">Current schedule &amp; inspection dates</div>
                  </div>
                  <span class="badge-pill-soft badge-pill-green"><?= date('F Y') ?></span>
                </div>
                <div class="flex-grow-1">
                  <?php include '../02_Actions/04_System-Admin-CRUD/calendar-widget.html'; ?>
                </div>
              </div>
            </div>

          </div>

        </div><!-- /.lgu-dashboard-wrapper -->
      </div><!-- /.content -->
    </div><!-- /.main-panel -->
  </div><!-- /.wrapper -->

  <!-- ══ CHART.JS INITIALIZATION SCRIPTS ═══════════════════════ -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {

      // Universal Font Styling for Chart.js
      Chart.defaults.font.family = "'Inter', sans-serif";
      Chart.defaults.color = '#555555';

      // ── Chart 1: Users by Role (Donut) ──────────────────────
      const roleCanvas = document.getElementById('usersRoleChart');
      if (roleCanvas) {
        const roleLabels = <?= json_encode(array_keys($lguUsersByRole)) ?>;
        const roleData = <?= json_encode(array_values($lguUsersByRole)) ?>;
        const roleColors = ['#185FA5', '#1d9e75', '#ff6a2a', '#7c3aed', '#0891b2'];

        new Chart(roleCanvas, {
          type: 'doughnut',
          data: {
            labels: roleLabels,
            datasets: [{
              data: roleData,
              backgroundColor: roleColors.slice(0, roleLabels.length),
              borderWidth: 3,
              borderColor: '#ffffff',
              hoverOffset: 6
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  boxWidth: 12,
                  padding: 12,
                  font: { size: 12, weight: '500' }
                }
              }
            },
            cutout: '68%'
          }
        });
      }

      // ── Chart 2: Monthly Registrations Trend (Line/Bar) ─────
      const regCanvas = document.getElementById('monthlyRegChart');
      if (regCanvas) {
        new Chart(regCanvas, {
          type: 'bar',
          data: {
            labels: <?= json_encode($lguMonthlyRegLabels) ?>,
            datasets: [{
              label: 'New Registrations',
              data: <?= json_encode($lguMonthlyRegData) ?>,
              backgroundColor: 'rgba(29, 158, 117, 0.85)',
              hoverBackgroundColor: '#178a63',
              borderRadius: 8,
              borderSkipped: false,
              barThickness: 28
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { display: false },
              tooltip: {
                backgroundColor: '#23423a',
                padding: 10,
                cornerRadius: 8
              }
            },
            scales: {
              x: {
                grid: { display: false },
                ticks: { font: { size: 11 } }
              },
              y: {
                beginAtZero: true,
                grid: { color: '#eef2f0' },
                ticks: {
                  precision: 0,
                  font: { size: 11 }
                }
              }
            }
          }
        });
      }

      // ── Chart 3: Pharmacies by Status (Doughnut) ───────────
      const pharmStatusCanvas = document.getElementById('pharmacyStatusChart');
      if (pharmStatusCanvas) {
        new Chart(pharmStatusCanvas, {
          type: 'doughnut',
          data: {
            labels: ['Approved', 'Pending', 'Suspended', 'Rejected'],
            datasets: [{
              data: [
                <?= $lguPharmApproved ?>,
                <?= $lguPharmPending ?>,
                <?= $lguPharmSuspended ?>,
                <?= $lguPharmRejected ?>
              ],
              backgroundColor: ['#1d9e75', '#ff6a2a', '#ca8a04', '#e11d48'],
              borderWidth: 3,
              borderColor: '#ffffff',
              hoverOffset: 6
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                position: 'bottom',
                labels: {
                  boxWidth: 12,
                  padding: 12,
                  font: { size: 12, weight: '500' }
                }
              }
            },
            cutout: '68%'
          }
        });
      }

      // ── Chart 4: Pharmacies by Barangay (Horizontal Bar) ────
      const brgyCanvas = document.getElementById('pharmacyBarangayChart');
      if (brgyCanvas) {
        const brgyLabels = <?= json_encode(array_column($lguPharmByBarangay, 'brgy')) ?>;
        const brgyCounts = <?= json_encode(array_column($lguPharmByBarangay, 'cnt')) ?>;

        new Chart(brgyCanvas, {
          type: 'bar',
          data: {
            labels: brgyLabels,
            datasets: [{
              label: 'Pharmacies',
              data: brgyCounts,
              backgroundColor: 'rgba(255, 106, 42, 0.85)',
              hoverBackgroundColor: '#eb5c20',
              borderRadius: 6,
              barThickness: 18
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { display: false },
              tooltip: { backgroundColor: '#23423a', cornerRadius: 8 }
            },
            scales: {
              x: {
                beginAtZero: true,
                grid: { color: '#eef2f0' },
                ticks: { precision: 0 }
              },
              y: {
                grid: { display: false },
                ticks: { font: { size: 11, weight: '500' } }
              }
            }
          }
        });
      }

      // ── Chart 5: Top Medicines Availability (Horizontal Bar) ─
      const medCanvas = document.getElementById('topMedicinesChart');
      if (medCanvas) {
        const medNames = <?= json_encode(array_column($lguTopMedicines, 'Generic_Name')) ?>;
        const medCounts = <?= json_encode(array_column($lguTopMedicines, 'pharmacy_count')) ?>;

        new Chart(medCanvas, {
          type: 'bar',
          data: {
            labels: medNames,
            datasets: [{
              label: 'Stores Stocking',
              data: medCounts,
              backgroundColor: 'rgba(29, 158, 117, 0.85)',
              hoverBackgroundColor: '#178a63',
              borderRadius: 6,
              barThickness: 18
            }]
          },
          options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { display: false },
              tooltip: { backgroundColor: '#23423a', cornerRadius: 8 }
            },
            scales: {
              x: {
                beginAtZero: true,
                grid: { color: '#eef2f0' },
                ticks: { precision: 0 }
              },
              y: {
                grid: { display: false },
                ticks: { font: { size: 11, weight: '500' } }
              }
            }
          }
        });
      }

    });
  </script>
</body>

</html>