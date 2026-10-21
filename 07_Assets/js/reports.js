/**
 * MediFind Reports & PDF Export Module (ES6)
 * Generates structured inventory and sales movement reports and
 * downloads them as PDF via html2pdf.js CDN.
 */

class MediFindReports {
  constructor() {
    this.btnExportPdf = document.getElementById('btnExportReportPdf');
    this.reportContainer = document.getElementById('reportPrintArea');
    this.reportTypeSelect = document.getElementById('reportTypeFilter');
    this.statusSelect = document.getElementById('reportStatusFilter');
    this.searchInput = document.getElementById('reportSearchInput');

    this.init();
  }

  init() {
    if (this.btnExportPdf) {
      this.btnExportPdf.addEventListener('click', () => this.exportToPdf());
    }

    if (this.reportTypeSelect) {
      this.reportTypeSelect.addEventListener('change', () => this.handleTypeChange());
    }

    if (this.statusSelect) {
      this.statusSelect.addEventListener('change', () => this.applyFilters());
    }

    if (this.searchInput) {
      this.searchInput.addEventListener('input', () => this.applyFilters());
    }
  }

  handleTypeChange() {
    const selected = this.reportTypeSelect ? this.reportTypeSelect.value : 'all';
    const invSection = document.getElementById('inventoryReportSection');
    const salesSection = document.getElementById('salesReportSection');

    if (selected === 'inventory') {
      if (invSection) invSection.style.display = 'block';
      if (salesSection) salesSection.style.display = 'none';
    } else if (selected === 'sales') {
      if (invSection) invSection.style.display = 'none';
      if (salesSection) salesSection.style.display = 'block';
    } else {
      if (invSection) invSection.style.display = 'block';
      if (salesSection) salesSection.style.display = 'block';
    }

    this.applyFilters();
  }

  applyFilters() {
    const query = this.searchInput ? this.searchInput.value.toLowerCase().trim() : '';
    const statusVal = this.statusSelect ? this.statusSelect.value.toLowerCase().trim() : 'all';

    // 1. Filter Inventory Rows
    const invRows = document.querySelectorAll('#inventoryReportSection tbody tr');
    invRows.forEach(row => {
      // Skip empty state row
      if (row.cells.length < 8) return;

      const generic = row.cells[0]?.textContent?.toLowerCase() || '';
      const brand = row.cells[1]?.textContent?.toLowerCase() || '';
      const category = row.cells[2]?.textContent?.toLowerCase() || '';
      const status = row.cells[7]?.textContent?.toLowerCase().trim() || '';

      const matchQuery = !query || generic.includes(query) || brand.includes(query) || category.includes(query);
      const matchStatus = (statusVal === 'all') || status.includes(statusVal);

      row.style.display = (matchQuery && matchStatus) ? '' : 'none';
    });

    // 2. Filter Sales Rows
    const salesRows = document.querySelectorAll('#salesReportSection tbody tr');
    salesRows.forEach(row => {
      // Skip empty state row
      if (row.cells.length < 8) return;

      const receipt = row.cells[0]?.textContent?.toLowerCase() || '';
      const customer = row.cells[1]?.textContent?.toLowerCase() || '';
      const payment = row.cells[7]?.textContent?.toLowerCase() || '';

      const matchQuery = !query || receipt.includes(query) || customer.includes(query) || payment.includes(query);
      row.style.display = matchQuery ? '' : 'none';
    });
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

    // Set export button loading state
    const originalBtnText = this.btnExportPdf.innerHTML;
    this.btnExportPdf.disabled = true;
    this.btnExportPdf.innerHTML = `
      <span class="spinner-border spinner-border-sm me-2" role="status"></span>
      Generating PDF...
    `;

    const now = new Date();
    const dateFormatted = now.toISOString().slice(0, 10);
    const filename = `MediFind_Report_${dateFormatted}.pdf`;

    const opt = {
      margin: [10, 10, 12, 10], // top, left, bottom, right in mm
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
        orientation: 'portrait'
      },
      pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };

    html2pdf()
      .set(opt)
      .from(element)
      .save()
      .then(() => {
        this.btnExportPdf.disabled = false;
        this.btnExportPdf.innerHTML = originalBtnText;
      })
      .catch((err) => {
        console.error('PDF export failed:', err);
        alert('Failed to generate PDF. You can also print the page directly using Ctrl+P.');
        this.btnExportPdf.disabled = false;
        this.btnExportPdf.innerHTML = originalBtnText;
      });
  }
}

// Global initialization
window.addEventListener('DOMContentLoaded', () => {
  window.mediFindReports = new MediFindReports();
});

export default MediFindReports;
