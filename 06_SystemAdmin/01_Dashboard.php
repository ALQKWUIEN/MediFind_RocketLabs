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
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/01_dashboard.css">
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/02_ManageInventory.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-body-css.css">

  <link rel="stylesheet" href="../07_Assets/css/03_SystemAdminCSS/reports-style.css">

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
                      <i class="bi bi-arrow-up-short"></i> <?= $newUsersMonth ?> 
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
                    <div class="card-value"><?= $pendingPharmaciesDash?></div>
                    <div class="card-sub">Within 30 days</div>
                  </div>
                </div>


              </div>
            </div>
          </div>

          <div style="padding: 1rem 0;">
            <div class="row g-3">

          <!-- GRAPHS -->
          <div style="padding: 1rem 0;">
            <div class="row g-3">

              <!-- Total Medicines -->
              <div class="col-12 col-sm-12 col-xl-6">
                <div class="stat-card">
                  <?php include '../02_Actions/04_System-Admin-CRUD/pie-chart-systemaAd.php'; ?>

                </div>
              </div>


              <!-- Calendar -->
              <div class="col-11 col-sm-12 col-xl-6" style="height: 100%;">
                <div class="stat-card calendar-widget">
                  <h6 class="mb-3">Calendar</h6>
                  <?php include '../02_Actions/04_System-Admin-CRUD/calendar-widget.html'; ?>
                </div>
              </div>



            </div>
          </div>



            </div>
          </div>

        </div>

      </div>
    </div>
  </div>


  </div>



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

  <script>
    const ctx = document.getElementById('myBarChart').getContext('2d');
    const myBarChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
          label: 'Total Users:', // Even if this is here, the code below hides it
          data: [340, 500, 495, 200, 240],
          backgroundColor: '#178a63', // The green from your image
        }]
      },
      options: {
        plugins: {
          legend: {
            display: false // This hides the "Total Users:" label
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            max: 600 // Matches your image scale
          }
        }
      }
    });
  </script>

</body>

</html>