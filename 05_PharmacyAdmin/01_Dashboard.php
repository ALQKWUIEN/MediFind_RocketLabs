<?php
// Global Variable and DB Connection
include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

// Select Count
require '../02_Actions/03_Pharmacy-Admin-CRUD/Select_Counts.php';
require '../02_Actions/04_System-Admin-CRUD/reports.php';

// ── GUARD: Users must log in ────────────────────────
if (!$_SESSION['user_id']) {
  header('Location: ../03_Authentication/login.php');
  exit;
}

// ── Re-check approval from DB directly ───────────────────────────
$stmt = $pdo->prepare("
                SELECT Approval_ID FROM 09_pharmacies WHERE User_ID = ? LIMIT 1
            ");
$stmt->execute([$_SESSION['user_id']]);
$pharmacy = $stmt->fetch();
$approval_id = $pharmacy['Approval_ID'] ?? 1;

// ── If not approved — send back to request page ───────────────────
if ($approval_id != 2) {
  $_SESSION['Pharmacy_Approval'] = $approval_id;
  //header('Location: ../05_PharmacyAdmin/00_RequestAccess.php');
  //exit;
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

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- STYLES -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />

  <!-- STYLES -->
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/01_dashboard.css">


  <!-- JSCRIPTS -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

</head>

<body>
  <div class="wrapper d-flex align-items-stretch">
    <div id="pharmacy-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content" style="padding: 1rem;">
        <div class="content-body">
          <div class="hero">
            <!-- STATS CARD -->
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
                    <div class="card-value"> <?= $inStock ?> </div>
                    <div class="d-flex align-items-center gap-2">
                      <span class="badge-up">
                        <span class="material-symbols-outlined">trending_up</span>
                        16%
                      </span>
                      <span class="card-sub">this month</span>
                    </div>
                  </div>
                </div>



                <!-- Low Stock -->
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

                <!-- Expiring Soon -->
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

                <!-- Inventory Value -->
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




          <!-- GRAPHS -->
          <div style="padding: 1rem 0;">
            <div class="row g-3">

              <!-- Total Medicines -->
              <div class="col-12 col-sm-12 col-xl-6">
                <div class="stat-card">
                  <?php include '../02_Actions/03_Pharmacy-Admin-CRUD/pie-chart_pharmacy.php'; ?>

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


    <!-- Pie Graph Scrip -->
    <script>
      const ctx = document.getElementById('stockPieChart').getContext('2d');

      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['In Stock', 'Low Stock', 'Out of Stock', 'Expiring Soon'],
          datasets: [{
            data: [
              <?= $inStock ?>,
              <?= $lowStock ?>,
              <?= $outOfStock ?>,
              <?= $expiringSoon ?>
            ],
            backgroundColor: [
              '#4CAF50',  // green  - in stock
              '#FF9800',  // amber  - low stock
              '#F44336',  // red    - out of stock
              '#2196F3',  // blue   - expiring soon
            ],
            borderWidth: 2,
            borderColor: '#fff'
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom',
              labels: {
                padding: 15,
                usePointStyle: true,
              }
            },
            tooltip: {
              callbacks: {
                label: function (context) {
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = ((context.parsed / total) * 100).toFixed(1);
                  return ` ${context.label}: ${context.parsed} (${percentage}%)`;
                }
              }
            }
          }
        }
      });
    </script>

</body>

</html>