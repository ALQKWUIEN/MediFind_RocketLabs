<?php
// ── Pharmacy Admin: Availability Status counts ─────────────────────
$statusCounts = ['Available' => 0, 'Low Stock' => 0, 'Out of Stock' => 0, 'Expired' => 0];
foreach ($allInventory as $inv) {
  $s = $inv['Availability_Status'];
  if (isset($statusCounts[$s]))
    $statusCounts[$s]++;
}

// ── Pharmacy Admin: Category counts ────────────────────────────────
$categoryCounts = [];
foreach ($allInventory as $inv) {
  $cat = $inv['Category_Name'] ?? 'Unknown';
  $categoryCounts[$cat] = ($categoryCounts[$cat] ?? 0) + 1;
}
arsort($categoryCounts);
?>

<!-- ══ PIE CHARTS ROW ══════════════════════════════════════════════ -->
<div class="row g-3 mb-3">

  <!-- Availability Status Pie -->
  <!-- Chart -->
  <div class="col-md-6">
    <div class="rcard h-100">
      <div class="rcard-header mb-4">
        <span class="rcard-title">Stock Availability Status</span>
        <span style="font-size:12px; color:var(--text-muted);">
          <?= array_sum($statusCounts) ?> items
        </span>
      </div>
      <div style="position:relative; height:300px; display:flex; align-items:center; justify-content:center;">
        <canvas id="statusPieChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Legend -->
  <div class="col-md-6">
    <div class="rcard h-100 d-flex flex-column justify-content-center">
      <div class="rcard-header">
        <span class="rcard-title">Legend</span>
      </div>

      <div class="d-flex flex-column gap-3 px-2 mt-3">
        <?php
        $statusColors = [
          'Available' => ['dot' => '#1d9e75'],
          'Low Stock' => ['dot' => '#EF9F27'],
          'Out of Stock' => ['dot' => '#E24B4A'],
          'Expired' => ['dot' => '#c0392b'],
        ];
        foreach ($statusCounts as $label => $count):
          $c = $statusColors[$label];
          $total = array_sum($statusCounts);
          $pct = $total > 0 ? round($count / $total * 100) : 0;
          ?>
          <div
            style="display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border-radius:8px; background:#f9fafb; border:1px solid #f3f4f6;">
            <div style="display:flex; align-items:center; gap:8px;">
              <span
                style="width:12px; height:12px; border-radius:50%; background:<?= $c['dot'] ?>; flex-shrink:0;"></span>
              <span style="font-size:13px; color:#374151;"><?= $label ?></span>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
              <strong style="font-size:13px;"><?= $count ?></strong>
              <span style="font-size:12px; color:#6b7280;">(<?= $pct ?>%)</span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  
</div>

<!-- ══ END PIE CHARTS ══════════════════════════════════════════════ -->

<script>
  (function () {

    // ── Availability Status Pie ─────────────────────────────────────
    const statusCtx = document.getElementById('statusPieChart')?.getContext('2d');
    if (statusCtx) {
      new Chart(statusCtx, {
        type: 'doughnut',
        data: {
          labels: <?= json_encode(array_keys($statusCounts)) ?>,
          datasets: [{
            data: <?= json_encode(array_values($statusCounts)) ?>,
            backgroundColor: ['#1d9e75', '#EF9F27', '#E24B4A', '#c0392b'],
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
                label: ctx => ` ${ctx.label}: ${ctx.parsed} items (${Math.round(ctx.parsed / ctx.dataset.data.reduce((a, b) => a + b, 0) * 100)}%)`
              }
            }
          }
        }
      });
    }

    // ── Category Pie ────────────────────────────────────────────────
    const catCtx = document.getElementById('categoryPieChart')?.getContext('2d');
    if (catCtx) {
      const catLabels = <?= json_encode(array_keys($categoryCounts)) ?>;
      const catData = <?= json_encode(array_values($categoryCounts)) ?>;
      const catColors = [
        '#1d9e75', '#185FA5', '#EF9F27', '#7F77DD', '#E24B4A',
        '#3B6D11', '#0f6e56', '#854F0B', '#3C3489', '#c0392b',
        '#5DADE2', '#A569BD', '#45B39D', '#F0B27A', '#EC7063'
      ];

      new Chart(catCtx, {
        type: 'doughnut',
        data: {
          labels: catLabels,
          datasets: [{
            data: catData,
            backgroundColor: catColors.slice(0, catLabels.length),
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
                label: ctx => ` ${ctx.label}: ${ctx.parsed} items`
              }
            }
          }
        }
      });

      // Build custom legend
      const legend = document.getElementById('categoryLegend');
      if (legend) {
        catLabels.forEach((label, i) => {
          legend.innerHTML += `
          <span style="font-size:12px; display:flex; align-items:center; gap:5px;">
            <span style="width:10px; height:10px; border-radius:50%; background:${catColors[i]}; flex-shrink:0;"></span>
            ${label}: <strong>${catData[i]}</strong>
          </span>`;
        });
      }
    }

  })();
</script>