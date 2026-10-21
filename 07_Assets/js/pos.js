/**
 * MediFind Point of Sale (POS) ES6 Module
 * Handles live item search, cart state management, discount calculations (PHP ₱),
 * stock verification, checkout requests, and thermal receipt generation.
 */

const FALLBACK_MED_SVG = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64' width='64' height='64'><rect width='64' height='64' rx='10' fill='%23f0f9f5'/><path d='M22 42 L42 22 A9 9 0 0 1 55 35 L35 55 A9 9 0 0 1 22 42 Z' fill='%231d9e75' opacity='0.85'/><path d='M22 42 L42 22 A9 9 0 0 0 9 29 L29 49 A9 9 0 0 0 22 42 Z' fill='%23178a63'/><line x1='22' y1='42' x2='42' y2='22' stroke='%23ffffff' stroke-width='2.5'/></svg>";

function resolveMedImageUrl(path) {
  if (!path) return FALLBACK_MED_SVG;
  if (path.startsWith('data:') || path.startsWith('http://') || path.startsWith('https://') || path.startsWith('..')) {
    return path;
  }
  return '../' + path.replace(/^\/+/, '');
}

class MediFindPOS {
  constructor() {
    this.cart = [];
    this.discountType = 'None';
    this.discountPercent = 0;
    this.customDiscount = 0;
    this.selectedSearchIndex = -1;
    this.searchResults = [];

    // DOM Elements
    this.searchInput = document.getElementById('posSearchInput');
    this.resultsDropdown = document.getElementById('posSearchResults');
    this.productGridEl = document.getElementById('posProductGrid');
    this.itemsCounterEl = document.getElementById('posItemsCounter');
    this.itemsSectionTitleEl = document.getElementById('posItemsSectionTitle');

    this.cartTbody = document.getElementById('posCartTbody');
    this.cartEmptyState = document.getElementById('posCartEmpty');
    this.cartTableWrap = document.getElementById('posCartTableWrap');

    this.subtotalEl = document.getElementById('posSubtotal');
    this.discountEl = document.getElementById('posDiscount');
    this.grandTotalEl = document.getElementById('posGrandTotal');
    this.cashInput = document.getElementById('posCashTendered');
    this.changeEl = document.getElementById('posChange');
    this.customerInput = document.getElementById('posCustomerName');
    this.checkoutBtn = document.getElementById('posCheckoutBtn');
    this.clearCartBtn = document.getElementById('posClearCartBtn');

    this.init();
  }

  init() {
    this.bindEvents();
    this.renderCart();
  }

  bindEvents() {
    // Live Search with Debounce
    let searchTimeout = null;
    if (this.searchInput) {
      this.searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        const query = e.target.value.trim();
        const activeCategory = document.querySelector('.pos-filter-pill.active')?.dataset.category || '';
        if (query.length === 0) {
          this.hideSearchResults();
          this.performSearch('', activeCategory);
          return;
        }
        searchTimeout = setTimeout(() => this.performSearch(query, activeCategory), 200);
      });

      // Key navigation in search
      this.searchInput.addEventListener('keydown', (e) => {
        if (!this.resultsDropdown.classList.contains('show')) return;
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          this.selectedSearchIndex = Math.min(this.selectedSearchIndex + 1, this.searchResults.length - 1);
          this.highlightSearchResult();
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          this.selectedSearchIndex = Math.max(this.selectedSearchIndex - 1, 0);
          this.highlightSearchResult();
        } else if (e.key === 'Enter') {
          e.preventDefault();
          if (this.selectedSearchIndex >= 0 && this.searchResults[this.selectedSearchIndex]) {
            this.addToCart(this.searchResults[this.selectedSearchIndex]);
            this.hideSearchResults();
            this.searchInput.value = '';
          }
        } else if (e.key === 'Escape') {
          this.hideSearchResults();
        }
      });
    }

    // Hide search dropdown on click outside
    document.addEventListener('click', (e) => {
      if (this.searchInput && !this.searchInput.contains(e.target) && !this.resultsDropdown.contains(e.target)) {
        this.hideSearchResults();
      }
    });

    // Category Pill Filters
    document.querySelectorAll('.pos-filter-pill').forEach(pill => {
      pill.addEventListener('click', () => {
        document.querySelectorAll('.pos-filter-pill').forEach(p => p.classList.remove('active'));
        pill.classList.add('active');
        const cat = pill.dataset.category || '';
        this.performSearch(this.searchInput.value.trim(), cat);
      });
    });

    // Discount Selectors
    document.querySelectorAll('.discount-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.discount-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        this.discountType = btn.dataset.type;

        if (this.discountType === 'Senior/PWD') {
          this.discountPercent = 0.20; // 20% Philippine Statutory Senior/PWD discount
          this.customDiscount = 0;
        } else if (this.discountType === '5%') {
          this.discountPercent = 0.05;
          this.customDiscount = 0;
        } else {
          this.discountPercent = 0;
          this.customDiscount = 0;
        }
        this.updateTotals();
      });
    });

    // Cash Tendered Input
    if (this.cashInput) {
      this.cashInput.addEventListener('input', () => this.updateTotals());
    }

    // Quick Cash Buttons
    document.querySelectorAll('.quick-cash-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const val = parseFloat(btn.dataset.amount || 0);
        this.cashInput.value = val.toFixed(2);
        this.updateTotals();
      });
    });

    // Clear Cart
    if (this.clearCartBtn) {
      this.clearCartBtn.addEventListener('click', () => {
        if (this.cart.length === 0) return;
        if (confirm('Clear all items from current cart?')) {
          this.cart = [];
          this.renderCart();
        }
      });
    }

    // Checkout Submission
    if (this.checkoutBtn) {
      this.checkoutBtn.addEventListener('click', () => this.processCheckout());
    }
  }

  async performSearch(query, category = '') {
    try {
      const url = `api_pos.php?action=search&q=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}`;
      const res = await fetch(url);
      const data = await res.json();

      if (data.success) {
        this.searchResults = data.items || [];
        if (query) {
          this.renderSearchResults();
        } else {
          this.hideSearchResults();
        }
        
        let title = 'Fast Available Stock';
        if (query) {
          title = `Search: "${query}"`;
        } else if (category) {
          title = `Category: ${category}`;
        }
        this.renderProductGrid(this.searchResults, title);
      }
    } catch (err) {
      console.error('POS search failed:', err);
    }
  }

  renderSearchResults() {
    this.resultsDropdown.innerHTML = '';
    this.selectedSearchIndex = -1;

    if (this.searchResults.length === 0) {
      this.resultsDropdown.innerHTML = `
        <div class="p-3 text-muted text-center" style="font-size: 0.88rem;">
          <span class="material-symbols-outlined d-block mb-1 text-muted" style="font-size: 1.75rem;">search_off</span>
          No medicines found in stock.
        </div>`;
      this.resultsDropdown.classList.add('show');
      return;
    }

    this.searchResults.forEach((item, idx) => {
      const div = document.createElement('div');
      div.className = 'pos-search-item';
      div.dataset.index = idx;

      const isRx = item.Requires_Rx && (item.Requires_Rx.toLowerCase().includes('yes') || item.Requires_Rx.toLowerCase().includes('require'));
      const dosageStr = (item.Dosage_Value ? `${item.Dosage_Value} ${item.Unit_Name || ''}` : '') + (item.Dosage_Form ? ` (${item.Dosage_Form})` : '');
      const stock = parseInt(item.Quantity || 0);
      const price = parseFloat(item.Price || 0);
      const imgSrc = resolveMedImageUrl(item.Med_Image_URL);
      const isOut = stock <= 0;
      const isLow = stock > 0 && stock <= 10;

      div.innerHTML = `
        <div class="pos-search-item-left">
          <div class="pos-search-item-thumb">
            <img src="${this.escapeHtml(imgSrc)}"
                 alt="${this.escapeHtml(item.Generic_Name || '')}"
                 loading="lazy"
                 onerror="this.onerror=null; this.src='${FALLBACK_MED_SVG}';">
          </div>
          <div>
            <div class="pos-item-title">
              ${this.escapeHtml(item.Generic_Name || 'Unknown')}
              ${item.Brand_Name ? `<span class="text-muted fw-normal">(${this.escapeHtml(item.Brand_Name)})</span>` : ''}
              ${isRx ? `<span class="pos-rx-badge">Rx</span>` : ''}
            </div>
            <div class="pos-item-sub">
              ${this.escapeHtml(dosageStr)} &bull; <span class="text-secondary">${this.escapeHtml(item.Category_Name || 'General')}</span>
            </div>
          </div>
        </div>
        <div class="text-end">
          <div class="pos-item-price">₱${price.toFixed(2)}</div>
          <div class="pos-item-stock ${isOut ? 'text-danger fw-bold' : (isLow ? 'text-warning fw-semibold' : '')}">
            ${isOut ? 'Out of stock' : `Stock: ${stock}`}
          </div>
        </div>
      `;

      div.addEventListener('click', () => {
        this.addToCart(item);
        this.hideSearchResults();
        this.searchInput.value = '';
      });

      this.resultsDropdown.appendChild(div);
    });

    this.resultsDropdown.classList.add('show');
  }

  renderProductGrid(items, sectionTitle = null) {
    if (!this.productGridEl) return;
    if (sectionTitle && this.itemsSectionTitleEl) {
      this.itemsSectionTitleEl.textContent = sectionTitle;
    }
    if (this.itemsCounterEl) {
      this.itemsCounterEl.textContent = `${items.length} items`;
    }

    if (items.length === 0) {
      this.productGridEl.innerHTML = `
        <div class="pos-empty-grid text-center py-4 text-muted small w-100" style="grid-column: 1 / -1;">
          <span class="material-symbols-outlined d-block mb-1 text-muted" style="font-size: 2.2rem;">medication_liquid</span>
          No medicines currently available in this selection.
        </div>
      `;
      return;
    }

    this.productGridEl.innerHTML = '';
    items.forEach(item => {
      const isRx = item.Requires_Rx && (item.Requires_Rx.toLowerCase().includes('yes') || item.Requires_Rx.toLowerCase().includes('require'));
      const dosageStr = (item.Dosage_Value ? `${item.Dosage_Value} ${item.Unit_Name || ''}` : '') + (item.Dosage_Form ? ` (${item.Dosage_Form})` : '');
      const fullName = `${item.Generic_Name || 'Unknown'}${item.Brand_Name ? ` (${item.Brand_Name})` : ''}`;
      const stock = parseInt(item.Quantity || 0);
      const price = parseFloat(item.Price || 0);
      const isOut = stock <= 0;
      const isLow = stock > 0 && stock <= 10;
      const imgSrc = resolveMedImageUrl(item.Med_Image_URL);

      const card = document.createElement('div');
      card.className = `pos-product-card ${isOut ? 'is-out-of-stock' : ''}`;
      card.tabIndex = 0;
      card.setAttribute('role', 'button');
      card.setAttribute('aria-label', fullName);

      let stockChipHtml = '';
      if (isOut) {
        stockChipHtml = '<span class="pos-stock-chip out-stock">Out of stock</span>';
      } else if (isLow) {
        stockChipHtml = `<span class="pos-stock-chip low-stock">Stock: ${stock}</span>`;
      } else {
        stockChipHtml = `<span class="pos-stock-chip available">Stock: ${stock}</span>`;
      }

      card.innerHTML = `
        <div class="pos-card-media">
          <img src="${this.escapeHtml(imgSrc)}"
               alt="${this.escapeHtml(fullName)}"
               loading="lazy"
               class="pos-card-img"
               onerror="this.onerror=null; this.src='${FALLBACK_MED_SVG}';">
        </div>
        <div class="pos-card-content">
          <div class="pos-card-header">
            <div class="pos-card-title" title="${this.escapeHtml(item.Generic_Name || '')}">
              ${this.escapeHtml(item.Generic_Name || 'Unknown')}
            </div>
            <div class="pos-card-brand-dosage">
              <span>${this.escapeHtml(item.Brand_Name || 'Generic')}${dosageStr ? ` &bull; ${this.escapeHtml(dosageStr)}` : ''}</span>
              ${isRx ? '<span class="pos-rx-pill">Rx</span>' : ''}
            </div>
          </div>
          <div class="pos-card-footer">
            <span class="pos-card-price">₱${price.toFixed(2)}</span>
            ${stockChipHtml}
          </div>
        </div>
      `;

      card.addEventListener('click', () => {
        if (isOut) {
          alert(`"${item.Generic_Name}" is currently out of stock.`);
          return;
        }
        this.addToCart(item);
      });

      card.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          if (isOut) {
            alert(`"${item.Generic_Name}" is currently out of stock.`);
            return;
          }
          this.addToCart(item);
        }
      });

      this.productGridEl.appendChild(card);
    });
  }

  highlightSearchResult() {
    const items = this.resultsDropdown.querySelectorAll('.pos-search-item');
    items.forEach((item, idx) => {
      if (idx === this.selectedSearchIndex) {
        item.classList.add('active');
        item.scrollIntoView({ block: 'nearest' });
      } else {
        item.classList.remove('active');
      }
    });
  }

  hideSearchResults() {
    this.resultsDropdown.classList.remove('show');
    this.selectedSearchIndex = -1;
  }

  addToCart(item) {
    const maxStock = parseInt(item.Quantity || 0);
    if (maxStock <= 0) {
      alert(`"${item.Generic_Name}" is currently out of stock.`);
      return;
    }

    const existingIndex = this.cart.findIndex(c => c.inventory_id === parseInt(item.Inventory_ID));

    if (existingIndex > -1) {
      if (this.cart[existingIndex].quantity + 1 > maxStock) {
        alert(`Cannot add more. Available stock for "${item.Generic_Name}" is ${maxStock}.`);
        return;
      }
      this.cart[existingIndex].quantity += 1;
      this.cart[existingIndex].total_price = this.cart[existingIndex].quantity * this.cart[existingIndex].unit_price;
    } else {
      const dosageStr = (item.Dosage_Value ? `${item.Dosage_Value} ${item.Unit_Name || ''}` : '') + (item.Dosage_Form ? ` (${item.Dosage_Form})` : '');
      const isRx = item.Requires_Rx && (item.Requires_Rx.toLowerCase().includes('yes') || item.Requires_Rx.toLowerCase().includes('require'));

      this.cart.push({
        inventory_id: parseInt(item.Inventory_ID),
        medicine_id: parseInt(item.Medicine_ID),
        generic_name: item.Generic_Name,
        brand_name: item.Brand_Name || '',
        dosage: dosageStr,
        requires_rx: isRx,
        price_per: item.Price_Per || 'unit',
        unit_price: parseFloat(item.Price || 0),
        quantity: 1,
        max_stock: maxStock,
        total_price: parseFloat(item.Price || 0)
      });
    }

    this.renderCart();
  }

  updateQuantity(index, newQty) {
    if (newQty <= 0) {
      this.removeFromCart(index);
      return;
    }

    const item = this.cart[index];
    if (newQty > item.max_stock) {
      alert(`Max available stock is ${item.max_stock}.`);
      item.quantity = item.max_stock;
    } else {
      item.quantity = newQty;
    }

    item.total_price = item.quantity * item.unit_price;
    this.renderCart();
  }

  removeFromCart(index) {
    this.cart.splice(index, 1);
    this.renderCart();
  }

  renderCart() {
    if (this.cart.length === 0) {
      if (this.cartEmptyState) this.cartEmptyState.style.display = 'block';
      if (this.cartTableWrap) this.cartTableWrap.style.display = 'none';
      if (this.checkoutBtn) this.checkoutBtn.disabled = true;
    } else {
      if (this.cartEmptyState) this.cartEmptyState.style.display = 'none';
      if (this.cartTableWrap) this.cartTableWrap.style.display = 'block';
      if (this.checkoutBtn) this.checkoutBtn.disabled = false;
    }

    if (!this.cartTbody) return;
    this.cartTbody.innerHTML = '';

    this.cart.forEach((item, idx) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>
          <div class="fw-semibold text-dark">${this.escapeHtml(item.generic_name)}</div>
          <div class="text-muted" style="font-size: 0.78rem;">
            ${item.brand_name ? this.escapeHtml(item.brand_name) + ' &bull; ' : ''}${this.escapeHtml(item.dosage)}
            ${item.requires_rx ? '<span class="pos-rx-badge">Rx</span>' : ''}
          </div>
        </td>
        <td class="text-nowrap">₱${item.unit_price.toFixed(2)}</td>
        <td>
          <div class="cart-qty-ctrl">
            <button type="button" class="cart-qty-btn btn-minus" data-index="${idx}">-</button>
            <input type="number" class="cart-qty-input" value="${item.quantity}" min="1" max="${item.max_stock}" data-index="${idx}">
            <button type="button" class="cart-qty-btn btn-plus" data-index="${idx}">+</button>
          </div>
        </td>
        <td class="fw-bold text-nowrap">₱${item.total_price.toFixed(2)}</td>
        <td class="text-end">
          <button type="button" class="cart-remove-btn" data-index="${idx}" title="Remove item">
            <span class="material-symbols-outlined" style="font-size: 1.15rem;">delete</span>
          </button>
        </td>
      `;

      // Event listeners for quantity controls
      tr.querySelector('.btn-minus').addEventListener('click', () => {
        this.updateQuantity(idx, item.quantity - 1);
      });

      tr.querySelector('.btn-plus').addEventListener('click', () => {
        this.updateQuantity(idx, item.quantity + 1);
      });

      const qtyInput = tr.querySelector('.cart-qty-input');
      qtyInput.addEventListener('change', (e) => {
        const val = parseInt(e.target.value) || 1;
        this.updateQuantity(idx, val);
      });

      tr.querySelector('.cart-remove-btn').addEventListener('click', () => {
        this.removeFromCart(idx);
      });

      this.cartTbody.appendChild(tr);
    });

    this.updateTotals();
  }

  updateTotals() {
    const subtotal = this.cart.reduce((acc, item) => acc + item.total_price, 0);
    const discountAmount = subtotal * this.discountPercent;
    const grandTotal = Math.max(0, subtotal - discountAmount);

    if (this.subtotalEl) this.subtotalEl.textContent = `₱${subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    if (this.discountEl) this.discountEl.textContent = `-₱${discountAmount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    if (this.grandTotalEl) this.grandTotalEl.textContent = `₱${grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;

    const cashTendered = parseFloat((this.cashInput && this.cashInput.value) ? this.cashInput.value : 0);
    const change = Math.max(0, cashTendered - grandTotal);

    if (this.changeEl) {
      this.changeEl.textContent = `₱${change.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
      if (cashTendered < grandTotal && cashTendered > 0) {
        this.changeEl.classList.add('text-danger');
      } else {
        this.changeEl.classList.remove('text-danger');
      }
    }

    if (this.checkoutBtn) {
      if (this.cart.length === 0 || cashTendered < grandTotal) {
        this.checkoutBtn.disabled = true;
      } else {
        this.checkoutBtn.disabled = false;
      }
    }
  }

  async processCheckout() {
    if (this.cart.length === 0) {
      alert('Cart is empty.');
      return;
    }

    const subtotal = this.cart.reduce((acc, item) => acc + item.total_price, 0);
    const discountAmount = subtotal * this.discountPercent;
    const grandTotal = Math.max(0, subtotal - discountAmount);
    const cashTendered = parseFloat((this.cashInput && this.cashInput.value) ? this.cashInput.value : 0);

    if (cashTendered < grandTotal) {
      alert(`Insufficient cash tendered. Total is ₱${grandTotal.toFixed(2)}, but tendered is ₱${cashTendered.toFixed(2)}.`);
      return;
    }

    const customerName = (this.customerInput && this.customerInput.value) ? this.customerInput.value.trim() : 'Walk-in Customer';
    const changeAmount = cashTendered - grandTotal;

    const payload = {
      action: 'checkout',
      customer_name: customerName,
      discount_type: this.discountType,
      discount_amount: discountAmount,
      subtotal: subtotal,
      grand_total: grandTotal,
      cash_tendered: cashTendered,
      change_amount: changeAmount,
      payment_method: 'Cash',
      items: this.cart.map(c => ({
        inventory_id: c.inventory_id,
        medicine_id: c.medicine_id,
        quantity: c.quantity,
        unit_price: c.unit_price,
        total_price: c.total_price
      }))
    };

    try {
      this.checkoutBtn.disabled = true;
      this.checkoutBtn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
        Processing...
      `;

      const res = await fetch('api_pos.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const data = await res.json();

      if (data.success) {
        // Reset Cart
        const completedCart = [...this.cart];
        this.cart = [];
        this.cashInput.value = '';
        this.renderCart();

        // Show Printable Thermal Receipt Modal
        this.showReceiptModal(data.receipt, completedCart);
      } else {
        alert('Checkout error: ' + (data.message || 'Unable to process checkout.'));
      }
    } catch (err) {
      console.error('Checkout failed:', err);
      alert('An error occurred during checkout processing.');
    } finally {
      if (this.checkoutBtn) {
        this.checkoutBtn.disabled = false;
        this.checkoutBtn.innerHTML = `
          <span class="material-symbols-outlined">receipt_long</span>
          Complete &amp; Print Receipt
        `;
      }
    }
  }

  showReceiptModal(receipt, items) {
    const receiptContainer = document.getElementById('receiptContentArea');
    if (!receiptContainer) return;

    let itemsHtml = '';
    items.forEach(it => {
      itemsHtml += `
        <tr>
          <td>
            <strong>${this.escapeHtml(it.generic_name)}</strong><br>
            <span style="font-size: 11px; color: #555;">${this.escapeHtml(it.dosage || '')}</span>
          </td>
          <td style="text-align: center;">${it.quantity}</td>
          <td style="text-align: right;">₱${it.unit_price.toFixed(2)}</td>
          <td style="text-align: right;">₱${it.total_price.toFixed(2)}</td>
        </tr>
      `;
    });

    receiptContainer.innerHTML = `
      <div class="receipt-paper">
        <div class="receipt-header">
          <div class="receipt-logo">${this.escapeHtml(receipt.pharmacy_name || 'MediFind Pharmacy')}</div>
          <div style="font-size: 11px; color: #555;">Official Point of Sale Receipt</div>
          <div style="font-size: 11px; margin-top: 4px;">Receipt #: <strong>${this.escapeHtml(receipt.receipt_no)}</strong></div>
          <div style="font-size: 11px;">Date: ${this.escapeHtml(receipt.date)}</div>
          <div style="font-size: 11px;">Customer: ${this.escapeHtml(receipt.customer_name)}</div>
        </div>

        <table class="receipt-items-table">
          <thead>
            <tr>
              <th>Item</th>
              <th style="text-align: center;">Qty</th>
              <th style="text-align: right;">Price</th>
              <th style="text-align: right;">Total</th>
            </tr>
          </thead>
          <tbody>
            ${itemsHtml}
          </tbody>
        </table>

        <div class="receipt-divider"></div>

        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 3px;">
          <span>Subtotal:</span>
          <strong>₱${parseFloat(receipt.subtotal).toFixed(2)}</strong>
        </div>

        ${receipt.discount_amount > 0 ? `
          <div style="display: flex; justify-content: space-between; font-size: 12px; color: #b45309; margin-bottom: 3px;">
            <span>Discount (${this.escapeHtml(receipt.discount_type)}):</span>
            <span>-₱${parseFloat(receipt.discount_amount).toFixed(2)}</span>
          </div>
        ` : ''}

        <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: bold; margin-top: 6px; border-top: 1px dashed #333; padding-top: 6px;">
          <span>TOTAL:</span>
          <span>₱${parseFloat(receipt.grand_total).toFixed(2)}</span>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-top: 4px;">
          <span>Cash Tendered:</span>
          <span>₱${parseFloat(receipt.cash_tendered).toFixed(2)}</span>
        </div>

        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-top: 2px;">
          <span>Change:</span>
          <span>₱${parseFloat(receipt.change_amount).toFixed(2)}</span>
        </div>

        <div class="receipt-divider"></div>

        <div class="receipt-footer">
          <div>Thank you for choosing ${this.escapeHtml(receipt.pharmacy_name || 'MediFind')}!</div>
          <div style="margin-top: 2px;">Powered by MediFind RocketLabs</div>
        </div>
      </div>
    `;

    const modalEl = document.getElementById('receiptModal');
    if (modalEl) {
      const bsModal = new bootstrap.Modal(modalEl);
      bsModal.show();
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
  window.mediFindPOS = new MediFindPOS();
});

export default MediFindPOS;
