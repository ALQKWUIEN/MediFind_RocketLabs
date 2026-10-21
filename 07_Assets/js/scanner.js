/**
 * MediFind Scanner & Direct Stock Update Module (ES6)
 * Handles Camera Barcode scanning and simulated Receipt OCR parsing
 * with direct stock level updates in 21_inventory.
 */

class MediFindScanner {
  constructor() {
    this.html5QrCode = null;
    this.isCameraActive = false;
    this.currentMatch = null;

    // DOM Elements
    this.modalEl = document.getElementById('scanStockModal');
    this.tabBarcode = document.getElementById('scannerTabBarcode');
    this.tabReceipt = document.getElementById('scannerTabReceipt');
    this.panelBarcode = document.getElementById('scannerPanelBarcode');
    this.panelReceipt = document.getElementById('scannerPanelReceipt');
    
    this.videoContainer = document.getElementById('scannerViewport');
    this.manualCodeInput = document.getElementById('scannerManualCode');
    this.btnLookupManual = document.getElementById('scannerBtnLookup');
    this.receiptTextArea = document.getElementById('scannerReceiptText');
    this.btnParseReceipt = document.getElementById('scannerBtnParseReceipt');

    this.resultBox = document.getElementById('scannerResultBox');
    this.receiptResultsBox = document.getElementById('scannerReceiptResults');
    
    this.init();
  }

  init() {
    this.bindEvents();
  }

  bindEvents() {
    // Tab switching
    if (this.tabBarcode && this.tabReceipt) {
      this.tabBarcode.addEventListener('click', () => {
        this.tabBarcode.classList.add('active');
        this.tabReceipt.classList.remove('active');
        this.panelBarcode.style.display = 'block';
        this.panelReceipt.style.display = 'none';
        this.startCameraScanner();
      });

      this.tabReceipt.addEventListener('click', () => {
        this.tabReceipt.classList.add('active');
        this.tabBarcode.classList.remove('active');
        this.panelBarcode.style.display = 'none';
        this.panelReceipt.style.display = 'block';
        this.stopCameraScanner();
      });
    }

    // Modal lifecycle: stop camera when closed, start when opened
    if (this.modalEl) {
      this.modalEl.addEventListener('shown.bs.modal', () => {
        if (this.panelBarcode.style.display !== 'none') {
          this.startCameraScanner();
        }
      });

      this.modalEl.addEventListener('hidden.bs.modal', () => {
        this.stopCameraScanner();
      });
    }

    // Manual Barcode / Medicine ID input lookup
    if (this.btnLookupManual && this.manualCodeInput) {
      this.btnLookupManual.addEventListener('click', () => {
        const val = this.manualCodeInput.value.trim();
        if (val) this.lookupCode(val);
      });

      this.manualCodeInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          e.preventDefault();
          const val = this.manualCodeInput.value.trim();
          if (val) this.lookupCode(val);
        }
      });
    }

    // Parse receipt text
    if (this.btnParseReceipt && this.receiptTextArea) {
      this.btnParseReceipt.addEventListener('click', () => {
        this.parseReceiptText(this.receiptTextArea.value.trim());
      });
    }
  }

  async startCameraScanner() {
    if (typeof Html5Qrcode === 'undefined') {
      console.warn('Html5Qrcode library not loaded.');
      return;
    }

    const readerEl = document.getElementById('scannerVideoFeed');
    if (!readerEl || this.isCameraActive) return;

    try {
      this.html5QrCode = new Html5Qrcode('scannerVideoFeed');
      const config = { fps: 10, qrbox: { width: 220, height: 140 } };

      await this.html5QrCode.start(
        { facingMode: 'environment' },
        config,
        (decodedText) => {
          console.log('Barcode/QR decoded:', decodedText);
          this.lookupCode(decodedText);
          // Optional: pause briefly to prevent duplicate alerts
          if (this.html5QrCode) {
            this.html5QrCode.pause();
            setTimeout(() => {
              if (this.html5QrCode && this.isCameraActive) this.html5QrCode.resume();
            }, 3000);
          }
        },
        (error) => {
          // Frame scan error (normal during movement)
        }
      );
      this.isCameraActive = true;
    } catch (err) {
      console.warn('Unable to access camera (or permission denied):', err);
      const feed = document.getElementById('scannerVideoFeed');
      if (feed) {
        feed.innerHTML = `
          <div class="p-4 text-center text-white" style="font-size: 0.85rem;">
            <span class="material-symbols-outlined d-block mb-2 text-warning" style="font-size: 2rem;">videocam_off</span>
            Camera unavailable or permission denied.<br>
            Please use manual barcode ID input or receipt scanner.
          </div>
        `;
      }
    }
  }

  async stopCameraScanner() {
    if (this.html5QrCode && this.isCameraActive) {
      try {
        await this.html5QrCode.stop();
        this.html5QrCode.clear();
      } catch (err) {
        console.warn('Error stopping scanner camera:', err);
      } finally {
        this.isCameraActive = false;
      }
    }
  }

  async lookupCode(code) {
    if (!this.resultBox) return;

    this.resultBox.style.display = 'block';
    this.resultBox.innerHTML = `
      <div class="text-center py-3 text-muted">
        <span class="spinner-border spinner-border-sm me-2"></span> Searching for medicine...
      </div>
    `;

    try {
      const res = await fetch(`api_scanner.php?action=lookup&code=${encodeURIComponent(code)}`);
      const data = await res.json();

      if (data.success && data.results && data.results.length > 0) {
        const item = data.results[0];
        this.currentMatch = item;
        this.renderSingleMatch(item);
      } else {
        this.resultBox.innerHTML = `
          <div class="alert alert-warning d-flex align-items-center gap-2 mb-0" style="border-radius: 10px;">
            <span class="material-symbols-outlined">warning</span>
            <div>No matching medicine found for code/query "<strong>${this.escapeHtml(code)}</strong>".</div>
          </div>
        `;
      }
    } catch (err) {
      console.error('Scan lookup failed:', err);
      this.resultBox.innerHTML = `<div class="alert alert-danger mb-0">Lookup failed due to network or server error.</div>`;
    }
  }

  renderSingleMatch(item) {
    const dosage = (item.Dosage_Value ? `${item.Dosage_Value} ${item.Unit_Name || ''}` : '') + (item.Dosage_Form ? ` (${item.Dosage_Form})` : '');
    const currentQty = parseInt(item.Quantity || 0);

    this.resultBox.innerHTML = `
      <div class="scanned-result-box">
        <div class="scanned-result-title">
          <span>${this.escapeHtml(item.Generic_Name)} ${item.Brand_Name ? `(${this.escapeHtml(item.Brand_Name)})` : ''}</span>
          <span class="scanned-status-pill matched">Matched</span>
        </div>
        <div class="text-muted small mt-1">
          Dosage: <strong>${this.escapeHtml(dosage)}</strong> &bull; Current Stock: <span class="badge ${currentQty <= 5 ? 'bg-danger' : 'bg-success'}">${currentQty} units</span>
        </div>

        <div class="scanned-qty-editor">
          <label class="form-label small fw-semibold mb-0">Adjust Stock:</label>
          <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-secondary quick-add-btn" data-add="5">+5</button>
            <button type="button" class="btn btn-outline-secondary quick-add-btn" data-add="10">+10</button>
            <button type="button" class="btn btn-outline-secondary quick-add-btn" data-add="20">+20</button>
            <button type="button" class="btn btn-outline-secondary quick-add-btn" data-add="50">+50</button>
          </div>
          <input type="number" id="scannedStockQtyInput" class="scanned-qty-input" value="10" min="1">
          <button type="button" id="btnApplyStockUpdate" class="btn btn-success btn-sm px-3" style="background-color: #1d9e75 !important; border-color: #1d9e75 !important;">
            <i class="bi bi-check-lg"></i> Apply to Inventory
          </button>
        </div>
        <div id="stockUpdateFeedback" class="mt-2" style="display: none;"></div>
      </div>
    `;

    // Add handlers
    this.resultBox.querySelectorAll('.quick-add-btn').forEach(b => {
      b.addEventListener('click', () => {
        document.getElementById('scannedStockQtyInput').value = b.dataset.add;
      });
    });

    const applyBtn = document.getElementById('btnApplyStockUpdate');
    applyBtn.addEventListener('click', () => {
      const qty = parseInt(document.getElementById('scannedStockQtyInput').value) || 0;
      this.updateStock(item.Inventory_ID, item.Medicine_ID, qty, 'add');
    });
  }

  async parseReceiptText(text) {
    if (!text) {
      alert('Please enter or paste receipt text.');
      return;
    }

    if (!this.receiptResultsBox) return;

    this.receiptResultsBox.style.display = 'block';
    this.receiptResultsBox.innerHTML = `
      <div class="text-center py-3 text-muted">
        <span class="spinner-border spinner-border-sm me-2"></span> Parsing receipt medicines...
      </div>
    `;

    try {
      const formData = new FormData();
      formData.append('action', 'parse_receipt');
      formData.append('text', text);

      const res = await fetch('api_scanner.php', { method: 'POST', body: formData });
      const data = await res.json();

      if (data.success && data.items && data.items.length > 0) {
        this.renderReceiptParsedList(data.items);
      } else {
        this.receiptResultsBox.innerHTML = `
          <div class="alert alert-warning mb-0">No recognizable medicine lines detected. Please check receipt formatting.</div>
        `;
      }
    } catch (err) {
      console.error('Receipt parse failed:', err);
      this.receiptResultsBox.innerHTML = `<div class="alert alert-danger mb-0">Receipt parsing failed.</div>`;
    }
  }

  renderReceiptParsedList(items) {
    let rowsHtml = '';
    items.forEach((it, idx) => {
      rowsHtml += `
        <tr>
          <td>
            <strong>${this.escapeHtml(it.generic_name)}</strong>
            ${it.brand_name ? `<span class="text-muted">(${this.escapeHtml(it.brand_name)})</span>` : ''}
            <div class="small text-muted">${this.escapeHtml(it.dosage || '')}</div>
          </td>
          <td>
            <span class="scanned-status-pill ${it.matched ? 'matched' : 'unmatched'}">
              ${it.matched ? 'Found' : 'Unmatched'}
            </span>
          </td>
          <td>${it.current_qty}</td>
          <td style="width: 100px;">
            <input type="number" class="form-control form-control-sm text-center receipt-item-qty" 
              value="${it.suggested_qty}" min="1" data-index="${idx}" ${!it.matched ? 'disabled' : ''}>
          </td>
          <td class="text-end">
            ${it.matched ? `
              <button type="button" class="btn btn-sm btn-outline-success btn-apply-single-receipt" data-index="${idx}">
                Update
              </button>
            ` : '<span class="text-muted small">N/A</span>'}
          </td>
        </tr>
      `;
    });

    this.receiptResultsBox.innerHTML = `
      <div class="card border mt-3">
        <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
          <strong class="small text-dark">Detected Medicines (${items.length})</strong>
          <button type="button" id="btnApplyAllReceipt" class="btn btn-success btn-sm" style="background: #1d9e75 !important; border-color: #1d9e75 !important;">
            Apply All Found
          </button>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-sm align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Medicine</th>
                <th>Status</th>
                <th>Current</th>
                <th>Add Qty</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody>
              ${rowsHtml}
            </tbody>
          </table>
        </div>
      </div>
      <div id="receiptApplyFeedback" class="mt-2" style="display: none;"></div>
    `;

    // Apply single button
    this.receiptResultsBox.querySelectorAll('.btn-apply-single-receipt').forEach(btn => {
      btn.addEventListener('click', () => {
        const idx = parseInt(btn.dataset.index);
        const item = items[idx];
        const input = this.receiptResultsBox.querySelector(`.receipt-item-qty[data-index="${idx}"]`);
        const qty = parseInt(input.value) || 1;
        this.updateStock(item.inventory_id, item.medicine_id, qty, 'add', btn);
      });
    });

    // Apply all button
    const btnAll = document.getElementById('btnApplyAllReceipt');
    if (btnAll) {
      btnAll.addEventListener('click', async () => {
        btnAll.disabled = true;
        btnAll.textContent = 'Updating...';
        let updatedCount = 0;

        for (let i = 0; i < items.length; i++) {
          const it = items[i];
          if (it.matched) {
            const input = this.receiptResultsBox.querySelector(`.receipt-item-qty[data-index="${i}"]`);
            const qty = parseInt(input.value) || 1;
            await this.updateStock(it.inventory_id, it.medicine_id, qty, 'add');
            updatedCount++;
          }
        }

        const feedback = document.getElementById('receiptApplyFeedback');
        if (feedback) {
          feedback.style.display = 'block';
          feedback.className = 'alert alert-success mt-2';
          feedback.textContent = `Successfully updated stock for ${updatedCount} medicines! Reloading inventory table...`;
        }

        setTimeout(() => window.location.reload(), 1500);
      });
    }
  }

  async updateStock(inventoryId, medicineId, qtyChange, mode = 'add', triggeringBtn = null) {
    if (triggeringBtn) {
      triggeringBtn.disabled = true;
      triggeringBtn.textContent = '...';
    }

    try {
      const res = await fetch('api_scanner.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'update_stock',
          inventory_id: inventoryId,
          medicine_id: medicineId,
          quantity_change: qtyChange,
          mode: mode
        })
      });

      const data = await res.json();

      if (data.success) {
        if (triggeringBtn) {
          triggeringBtn.className = 'btn btn-sm btn-success disabled';
          triggeringBtn.textContent = 'Done ✓';
        }

        const fb = document.getElementById('stockUpdateFeedback');
        if (fb) {
          fb.style.display = 'block';
          fb.className = 'alert alert-success py-2 small mb-0';
          fb.innerHTML = `Stock updated successfully! New quantity: <strong>${data.new_quantity}</strong>. Refreshing table...`;
        }

        setTimeout(() => {
          window.location.reload();
        }, 1200);
      } else {
        alert('Stock update error: ' + (data.message || 'Failed'));
        if (triggeringBtn) {
          triggeringBtn.disabled = false;
          triggeringBtn.textContent = 'Retry';
        }
      }
    } catch (err) {
      console.error('Stock update request failed:', err);
      alert('Failed to update stock due to network error.');
      if (triggeringBtn) {
        triggeringBtn.disabled = false;
        triggeringBtn.textContent = 'Retry';
      }
    }
  }

  escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }
}

// Global initialization
window.addEventListener('DOMContentLoaded', () => {
  window.mediFindScanner = new MediFindScanner();
});

export default MediFindScanner;
