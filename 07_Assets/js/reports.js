/**
 * MediFind Reports & PDF/CSV Export Module (ES6)
 * Generates formal A4 Landscape PDF reports via html2pdf.js and CSV exports.
 */

class MediFindReports {
  constructor() {
    this.btnExportPdf = document.getElementById('btnExportReportPdf');
    this.btnExportCsv = document.getElementById('btnExportReportCsv');
    this.reportContainer = document.getElementById('reportPrintArea');
    this.tabButtons = document.querySelectorAll('[data-report-tab]');
    this.statusSelect = document.getElementById('reportStatusFilter');
    this.searchInput = document.getElementById('reportSearchInput');
    this.datePresetSelect = document.getElementById('reportDatePreset');
    this.customDateRow = document.getElementById('customDateRangeRow');
    this.dateStartInput = document.getElementById('reportDateStart');
    this.dateEndInput = document.getElementById('reportDateEnd');
    this.activeTab = 'all';

    this.init();
  }

  init() {
    if (this.btnExportPdf) {
      this.btnExportPdf.addEventListener('click', () => this.exportToPdf());
    }

    if (this.btnExportCsv) {
      this.btnExportCsv.addEventListener('click', () => this.exportToCsv());
    }

    this.tabButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        this.tabButtons.forEach((b) => b.classList.remove('active'));
        btn.classList.add('active');
        this.activeTab = btn.dataset.reportTab || 'all';
        this.handleTypeChange();
      });
    });

    if (this.statusSelect) {
      this.statusSelect.addEventListener('change', () => this.applyFilters());
    }

    if (this.searchInput) {
      this.searchInput.addEventListener('input', () => this.applyFilters());
    }

    if (this.datePresetSelect) {
      this.datePresetSelect.addEventListener('change', () => {
        const isCustom = this.datePresetSelect.value === 'custom';
        if (this.customDateRow) {
          this.customDateRow.style.display = isCustom ? 'flex' : 'none';
        }
        this.applyFilters();
      });
    }

    if (this.dateStartInput) {
      this.dateStartInput.addEventListener('change', () => this.applyFilters());
    }

    if (this.dateEndInput) {
      this.dateEndInput.addEventListener('change', () => this.applyFilters());
    }

    this.applyFilters();
  }

  getDateBounds() {
    const preset = this.datePresetSelect ? this.datePresetSelect.value : 'all';
    const today = new Date();
    const toIso = (d) => d.toISOString().slice(0, 10);

    if (preset === 'today') {
      const t = toIso(today);
      return { start: t, end: t, label: `Today (${t})` };
    }
    if (preset === '7days') {
      const startD = new Date(today);
      startD.setDate(startD.getDate() - 6);
      return { start: toIso(startD), end: toIso(today), label: `Last 7 Days (${toIso(startD)} to ${toIso(today)})` };
    }
    if (preset === 'month') {
      const startD = new Date(today.getFullYear(), today.getMonth(), 1);
      return { start: toIso(startD), end: toIso(today), label: `This Month (${toIso(startD)} to ${toIso(today)})` };
    }
    if (preset === 'custom') {
      const s = this.dateStartInput ? this.dateStartInput.value : '';
      const e = this.dateEndInput ? this.dateEndInput.value : '';
      const lbl = (s || e) ? `${s || 'Start'} to ${e || 'Present'}` : 'Custom Range';
      return { start: s, end: e, label: lbl };
    }
    return { start: '', end: '', label: 'All Time' };
  }

  handleTypeChange() {
    const invSection = document.getElementById('inventoryReportSection');
    const salesSection = document.getElementById('salesReportSection');
    const dateWrap = document.getElementById('reportDateWrap');
    const statusWrap = document.getElementById('reportStatusWrap');

    if (this.activeTab === 'inventory') {
      if (invSection) invSection.style.display = 'block';
      if (salesSection) salesSection.style.display = 'none';
      if (dateWrap) dateWrap.style.display = 'none';
      if (this.customDateRow) this.customDateRow.style.display = 'none';
      if (statusWrap) statusWrap.style.display = 'block';
    } else if (this.activeTab === 'sales') {
      if (invSection) invSection.style.display = 'none';
      if (salesSection) salesSection.style.display = 'block';
      if (dateWrap) dateWrap.style.display = 'block';
      if (this.customDateRow && this.datePresetSelect) {
        this.customDateRow.style.display = this.datePresetSelect.value === 'custom' ? 'flex' : 'none';
      }
      if (statusWrap) statusWrap.style.display = 'none';
    } else {
      if (invSection) invSection.style.display = 'block';
      if (salesSection) salesSection.style.display = 'block';
      if (dateWrap) dateWrap.style.display = 'block';
      if (this.customDateRow && this.datePresetSelect) {
        this.customDateRow.style.display = this.datePresetSelect.value === 'custom' ? 'flex' : 'none';
      }
      if (statusWrap) statusWrap.style.display = 'block';
    }

    this.applyFilters();
  }

  applyFilters() {
    const query = this.searchInput ? this.searchInput.value.toLowerCase().trim() : '';
    const statusVal = this.statusSelect ? this.statusSelect.value.toLowerCase().trim() : 'all';
    const bounds = this.getDateBounds();

    const pdfRangeEl = document.getElementById('pdfDateRangeLabel');
    if (pdfRangeEl) pdfRangeEl.textContent = bounds.label;

    // 1. Filter Inventory Rows
    let visibleInvCount = 0;
    document.querySelectorAll('#inventoryReportSection tbody tr.inv-row').forEach((row) => {
      const rowSearch = (row.dataset.search || row.textContent || '').toLowerCase();
      const rowStatus = (row.dataset.status || '').toLowerCase().trim();

      const matchQuery = !query || rowSearch.includes(query);
      const matchStatus = statusVal === 'all' || rowStatus === statusVal;
      const show = matchQuery && matchStatus;
      row.style.display = show ? '' : 'none';
      if (show) visibleInvCount++;
    });

    // 2. Filter Sales Rows (search + date range) & recalculate totals
    let visibleSalesCount = 0;
    let sumUnits = 0;
    let sumSub = 0;
    let sumDisc = 0;
    let sumGrand = 0;

    document.querySelectorAll('#salesReportSection tbody tr.sale-row').forEach((row) => {
      const rowSearch = (row.dataset.search || row.textContent || '').toLowerCase();
      const saleDate = row.dataset.saleDate || '';

      const matchQuery = !query || rowSearch.includes(query);
      const matchStart = !bounds.start || (saleDate && saleDate >= bounds.start);
      const matchEnd = !bounds.end || (saleDate && saleDate <= bounds.end);
      const show = matchQuery && matchStart && matchEnd;

      row.style.display = show ? '' : 'none';
      if (show) {
        visibleSalesCount++;
        sumUnits += parseInt(row.dataset.qty || '0', 10);
        sumSub += parseFloat(row.dataset.subtotal || '0');
        sumDisc += parseFloat(row.dataset.discount || '0');
        sumGrand += parseFloat(row.dataset.grand || '0');
      }
    });

    // Update section counts, KPI sales card, and Sales Totals footer
    const invCountEl = document.getElementById('invSectionCount');
    if (invCountEl) invCountEl.textContent = `${visibleInvCount} ${visibleInvCount === 1 ? 'record' : 'records'} listed`;

    const salesCountEl = document.getElementById('salesSectionCount');
    if (salesCountEl) salesCountEl.textContent = `${visibleSalesCount} ${visibleSalesCount === 1 ? 'transaction' : 'transactions'} recorded`;

    const badgeEl = document.getElementById('reportRecordBadge');
    if (badgeEl) {
      const totalVisible = (this.activeTab === 'inventory') ? visibleInvCount : (this.activeTab === 'sales') ? visibleSalesCount : (visibleInvCount + visibleSalesCount);
      badgeEl.textContent = `${totalVisible} ${totalVisible === 1 ? 'record' : 'records'} listed`;
    }

    const fmt = (n) => '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const kpiSalesVal = document.getElementById('kpiTotalSalesValue');
    const kpiSalesCnt = document.getElementById('kpiTotalSalesCount');
    const pdfSalesVal = document.getElementById('pdfKpiSalesVal');
    const pdfSalesCnt = document.getElementById('pdfKpiSalesCount');

    if (kpiSalesVal) kpiSalesVal.textContent = fmt(sumGrand);
    if (kpiSalesCnt) kpiSalesCnt.textContent = `${visibleSalesCount} completed POS checkouts`;
    if (pdfSalesVal) pdfSalesVal.textContent = fmt(sumGrand);
    if (pdfSalesCnt) pdfSalesCnt.textContent = `${visibleSalesCount} completed transactions`;

    const footUnits = document.getElementById('footTotalUnits');
    const footSub = document.getElementById('footTotalSubtotal');
    const footDisc = document.getElementById('footTotalDiscount');
    const footGrand = document.getElementById('footTotalGrand');
    if (footUnits) footUnits.textContent = `${sumUnits.toLocaleString()} ${sumUnits === 1 ? 'unit' : 'units'}`;
    if (footSub) footSub.textContent = fmt(sumSub);
    if (footDisc) footDisc.textContent = `-${fmt(sumDisc)}`;
    if (footGrand) footGrand.textContent = fmt(sumGrand);
  }

  exportToCsv() {
    const lines = [];
    const esc = (v) => `"${String(v || '').replace(/"/g, '""').trim()}"`;

    if (this.activeTab === 'all' || this.activeTab === 'inventory') {
      lines.push('INVENTORY STOCK STATUS');
      lines.push(['ID', 'Generic Name', 'Brand', 'Category', 'Dosage', 'Price (PHP)', 'Qty', 'Expiry Date', 'Status'].map(esc).join(','));
      document.querySelectorAll('#inventoryReportSection tbody tr.inv-row').forEach((row) => {
        if (row.style.display === 'none') return;
        const cells = Array.from(row.cells).map((c) => esc(c.textContent.replace(/\s+/g, ' ')));
        lines.push(cells.join(','));
      });
      lines.push('');
    }

    if (this.activeTab === 'all' || this.activeTab === 'sales') {
      lines.push('POS MOVEMENT & TRANSACTIONS');
      lines.push(['Receipt #', 'Customer Name', 'Date / Time', 'Items Sold', 'Subtotal (PHP)', 'Discount (PHP)', 'Grand Total (PHP)', 'Payment'].map(esc).join(','));
      document.querySelectorAll('#salesReportSection tbody tr.sale-row').forEach((row) => {
        if (row.style.display === 'none') return;
        const cells = Array.from(row.cells).map((c) => esc(c.textContent.replace(/\s+/g, ' ')));
        lines.push(cells.join(','));
      });
    }

    const blob = new Blob(['\uFEFF' + lines.join('\r\n')], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `MediFind_Report_${new Date().toISOString().slice(0, 10)}.csv`;
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
  }

  exportToPdf() {
    if (typeof html2pdf === 'undefined') {
      alert('PDF generation library is still loading. Please try again in a moment.');
      return;
    }

    const element = this.reportContainer || document.getElementById('reportPrintArea');
    if (!element) {
      alert('Report content area not found.');
      return;
    }

    const originalBtnText = this.btnExportPdf.innerHTML;
    this.btnExportPdf.disabled = true;
    this.btnExportPdf.innerHTML = `
      <span class="spinner-border spinner-border-sm me-2" role="status"></span>
      Generating PDF...
    `;

    element.classList.add('pdf-exporting');

    const now = new Date();
    const dateFormatted = now.toISOString().slice(0, 10);
    const genTimestamp = now.toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' });
    const filename = `MediFind_Report_${dateFormatted}.pdf`;

    const opt = {
      margin: [12, 12, 16, 12],
      filename: filename,
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: {
        scale: 2,
        useCORS: true,
        letterRendering: true,
        logging: false
      },
      jsPDF: {
        unit: 'mm',
        format: 'a4',
        orientation: 'landscape'
      },
      pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };

    html2pdf()
      .set(opt)
      .from(element)
      .toPdf()
      .get('pdf')
      .then((pdf) => {
        const totalPages = pdf.internal.getNumberOfPages();
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        for (let i = 1; i <= totalPages; i++) {
          pdf.setPage(i);
          pdf.setFontSize(8);
          pdf.setTextColor(100, 116, 139);
          pdf.text('MediFind Pharmacy Management System', 12, pageHeight - 7);
          pdf.text(`Generated: ${genTimestamp}   |   Page ${i} of ${totalPages}`, pageWidth - 12, pageHeight - 7, { align: 'right' });
        }
      })
      .save()
      .then(() => {
        element.classList.remove('pdf-exporting');
        this.btnExportPdf.disabled = false;
        this.btnExportPdf.innerHTML = originalBtnText;
      })
      .catch((err) => {
        console.error('PDF export failed:', err);
        element.classList.remove('pdf-exporting');
        alert('Failed to generate PDF. You can also print the page directly using Ctrl+P.');
        this.btnExportPdf.disabled = false;
        this.btnExportPdf.innerHTML = originalBtnText;
      });
  }
}

window.addEventListener('DOMContentLoaded', () => {
  window.mediFindReports = new MediFindReports();
});

export default MediFindReports;
