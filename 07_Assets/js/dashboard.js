/**
 * MediFind Pharmacy Admin Dashboard Module
 * Handles Chart.js charts, time toggles (Daily/Weekly/Monthly),
 * loading skeletons, and interactive restock links.
 */

class PharmacyDashboard {
  constructor() {
    this.salesTrendChart = null;
    this.topSellingChart = null;
    this.salesCategoryChart = null;
    this.stockCategoryChart = null;
    this.currentPeriod = 'daily';

    this.init();
  }

  init() {
    this.initCharts();
    this.bindPeriodToggle();
    this.hideSkeletons();
  }

  hideSkeletons() {
    setTimeout(() => {
      document.querySelectorAll('.skeleton-box').forEach(el => {
        el.style.display = 'none';
      });
      document.querySelectorAll('.chart-canvas-wrap').forEach(el => {
        el.style.opacity = '1';
      });
    }, 250);
  }

  initCharts() {
    if (typeof Chart === 'undefined') {
      console.warn('Chart.js library is not loaded.');
      return;
    }

    const chartData = window.dashboardChartData || {};

    // ── 1. Sales Trend Line Chart ───────────────────────
    const salesCtx = document.getElementById('salesTrendChartCanvas');
    if (salesCtx) {
      const dailyData = chartData.salesTrendDaily || { labels: [], values: [] };

      this.salesTrendChart = new Chart(salesCtx, {
        type: 'line',
        data: {
          labels: dailyData.labels,
          datasets: [{
            label: 'Sales Revenue (₱)',
            data: dailyData.values,
            borderColor: '#1d9e75',
            backgroundColor: 'rgba(29, 158, 117, 0.12)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.35,
            pointBackgroundColor: '#1d9e75',
            pointRadius: 4,
            pointHoverRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function (ctx) {
                  return ' Sales: ₱' + Number(ctx.parsed.y).toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                  });
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function (val) { return '₱' + Number(val).toLocaleString(); }
              },
              grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
              grid: { display: false }
            }
          }
        }
      });
    }

    // ── 2. Top 5 Selling Medicines Bar Chart ─────────────
    const topCtx = document.getElementById('topSellingChartCanvas');
    if (topCtx) {
      const topData = chartData.topSelling || { labels: [], values: [] };

      this.topSellingChart = new Chart(topCtx, {
        type: 'bar',
        data: {
          labels: topData.labels,
          datasets: [{
            label: 'Units Sold',
            data: topData.values,
            backgroundColor: [
              '#1d9e75',
              '#00ac4f',
              '#ff6a2a',
              '#f59e0b',
              '#8b5cf6'
            ],
            borderRadius: 6,
            borderSkipped: false
          }]
        },
        options: {
          indexAxis: 'y',
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function (ctx) {
                  return ' Dispensed: ' + ctx.parsed.x + ' units';
                }
              }
            }
          },
          scales: {
            x: {
              beginAtZero: true,
              ticks: { stepSize: 5 },
              grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            y: {
              grid: { display: false }
            }
          }
        }
      });
    }

    // ── 3. Sales by Category Donut Chart ────────────────
    const catCtx = document.getElementById('salesCategoryChartCanvas');
    if (catCtx) {
      const catData = chartData.salesByCategory || { labels: [], values: [] };

      this.salesCategoryChart = new Chart(catCtx, {
        type: 'doughnut',
        data: {
          labels: catData.labels,
          datasets: [{
            data: catData.values,
            backgroundColor: [
              '#1d9e75',
              '#d97706',
              '#f59e0b',
              '#ef4444',
              '#8b5cf6',
              '#10b981'
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
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
                font: { size: 11 }
              }
            },
            tooltip: {
              callbacks: {
                label: function (ctx) {
                  const val = ctx.parsed;
                  const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                  const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                  return ` ${ctx.label}: ₱${Number(val).toLocaleString(undefined, { minimumFractionDigits: 2 })} (${pct}%)`;
                }
              }
            }
          },
          cutout: '62%'
        }
      });
    }

    // ── 4. Stock Level by Category Bar Chart ────────────
    const stockCatCtx = document.getElementById('stockCategoryChartCanvas');
    if (stockCatCtx) {
      const stockCatData = chartData.stockByCategory || { labels: [], values: [] };

      this.stockCategoryChart = new Chart(stockCatCtx, {
        type: 'bar',
        data: {
          labels: stockCatData.labels,
          datasets: [{
            label: 'Total Available Quantity',
            data: stockCatData.values,
            backgroundColor: '#d97706',
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              callbacks: {
                label: function (ctx) {
                  return ' In Stock: ' + Number(ctx.parsed.y).toLocaleString() + ' units';
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: { color: 'rgba(0, 0, 0, 0.05)' }
            },
            x: {
              grid: { display: false },
              ticks: { maxRotation: 45, minRotation: 0, font: { size: 10 } }
            }
          }
        }
      });
    }
  }

  bindPeriodToggle() {
    const btns = document.querySelectorAll('.time-toggle-btn');
    const chartData = window.dashboardChartData || {};

    btns.forEach(btn => {
      btn.addEventListener('click', () => {
        btns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const period = btn.dataset.period;
        if (!this.salesTrendChart) return;

        let targetData;
        if (period === 'weekly') {
          targetData = chartData.salesTrendWeekly || { labels: [], values: [] };
        } else if (period === 'monthly') {
          targetData = chartData.salesTrendMonthly || { labels: [], values: [] };
        } else {
          targetData = chartData.salesTrendDaily || { labels: [], values: [] };
        }

        this.salesTrendChart.data.labels = targetData.labels;
        this.salesTrendChart.data.datasets[0].data = targetData.values;
        this.salesTrendChart.update('active');
      });
    });
  }
}

// Global initialization
window.addEventListener('DOMContentLoaded', () => {
  window.pharmacyDashboard = new PharmacyDashboard();
});

export default PharmacyDashboard;
