<?php
// ── System Admin: Users by Role counts ─────────────────────────────
// $roleCounts is already fetched in reports.php as:
// $roleCounts = ['Patient' => x, 'Pharmacist' => x, 'Pharmacy Owner' => x, 'System Admin' => x]
// Make sure reports.php has already been required before including this file.
?>

<!-- ══ PIE CHART ROW ═══════════════════════════════════════════════ -->

<div class="row g-3 mb-3">

  <!-- Users by Role Pie -->
  <!-- Chart -->
  <div class="col-md-6">
    <div class="rcard h-100" style="border:none;">
      <div class="rcard-header mb-4" style="border:none;">
        <span class="rcard-title">Users by Role</span>
     
      </div>
      <div style="position:relative; height:317px; display:flex; align-items:center; justify-content:center;">
        <canvas id="rolesPieChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Legend -->
  <div class="col-md-6">
    <div class="rcard h-100 d-flex flex-column justify-content-center" style="border:none;">
      <div class="rcard-header">
        <span class="rcard-title">Legend</span>
        <span style="font-size:12px; color:var(--text-muted);">
          <?= number_format($totalUsers) ?> total users
        </span>
      </div>

      <div class="d-flex flex-column gap-3 px-2 mt-3">
        <?php
        $roleColors = [
          'Patient' => ['dot' => '#185FA5'],
          'Pharmacist' => ['dot' => '#1d9e75'],
          'Pharmacy Owner' => ['dot' => '#EF9F27'],
          'System Admin' => ['dot' => '#7F77DD'],
        ];
        foreach ($roleCounts as $role => $count):
          $c = $roleColors[$role] ?? ['dot' => '#aaa'];
          $pct = $totalUsers > 0 ? round($count / $totalUsers * 100) : 0;
          ?>
          <div
            style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:8px; background:#f9fafb; border:1px solid #f3f4f6;">
            <div style="display:flex; align-items:center; gap:8px;">
              <span
                style="width:12px; height:12px; border-radius:50%; background:<?= $c['dot'] ?>; flex-shrink:0;"></span>
              <span style="font-size:13px; color:#374151;"><?= htmlspecialchars($role) ?></span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
              <strong style="font-size:13px;"><?= number_format($count) ?></strong>
              <span style="font-size:12px; color:#6b7280;">(<?= $pct ?>%)</span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>

</div>


<!-- ══ END PIE CHART ROW ═══════════════════════════════════════════ -->

<?php
// ── Fetch: Users by Status ──────────────────────────────────────────
$stmtUserStatus = $pdo->query("
    SELECT UserStatus, COUNT(*) as count
    FROM view_01_users
    GROUP BY UserStatus
");
$userStatusCounts = $stmtUserStatus->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<script>
  (function () {

    // ── Roles Pie ───────────────────────────────────────────────────
    const rolesCtx = document.getElementById('rolesPieChart')?.getContext('2d');
    if (rolesCtx) {
      new Chart(rolesCtx, {
        type: 'doughnut',
        data: {
          labels: <?= json_encode(array_keys($roleCounts)) ?>,
          datasets: [{
            data: <?= json_encode(array_values($roleCounts)) ?>,
            backgroundColor: ['#185FA5', '#1d9e75', '#EF9F27', '#7F77DD'],
            borderColor: '#fff',
            borderWidth: 3,
            hoverOffset: 8
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '62%',
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: ctx => {
                  const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                  const pct = total > 0 ? Math.round(ctx.parsed / total * 100) : 0;
                  return ` ${ctx.label}: ${ctx.parsed.toLocaleString()} users (${pct}%)`;
                }
              }
            }
          }
        }
      });
    }

    // ── Users by Status Pie ─────────────────────────────────────────
    const usCtx = document.getElementById('statusUsersPieChart')?.getContext('2d');
    if (usCtx) {
      const usLabels = <?= json_encode(array_keys($userStatusCounts)) ?>;
      const usData = <?= json_encode(array_values($userStatusCounts)) ?>;
      const usColors = {
        'Active': '#1d9e75',
        'Inactive': '#9CA3AF',
        'Suspended': '#E24B4A',
      };
      const usBg = usLabels.map(l => usColors[l] ?? '#aaa');

      new Chart(usCtx, {
        type: 'doughnut',
        data: {
          labels: usLabels,
          datasets: [{
            data: usData,
            backgroundColor: usBg,
            borderColor: '#fff',
            borderWidth: 3,
            hoverOffset: 8
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '62%',
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: ctx => {
                  const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                  const pct = total > 0 ? Math.round(ctx.parsed / total * 100) : 0;
                  return ` ${ctx.label}: ${ctx.parsed.toLocaleString()} users (${pct}%)`;
                }
              }
            }
          }
        }
      });

      // Build legend
      const legend = document.getElementById('userStatusLegend');
      if (legend) {
        const total = usData.reduce((a, b) => a + b, 0);
        usLabels.forEach((label, i) => {
          const pct = total > 0 ? Math.round(usData[i] / total * 100) : 0;
          legend.innerHTML += `
          <span style="font-size:12px; display:flex; align-items:center; gap:5px;">
            <span style="width:10px; height:10px; border-radius:50%; background:${usBg[i]}; flex-shrink:0;"></span>
            ${label}: <strong>${usData[i].toLocaleString()}</strong>
            <span style="color:#6b7280;">(${pct}%)</span>
          </span>`;
        });
      }
    }

  })();
</script>