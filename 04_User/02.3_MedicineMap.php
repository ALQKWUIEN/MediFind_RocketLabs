<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MediFind: Find Medicine</title>
    <link rel="icon" href="/07_Assets/images/logo.png" type="image/png" />
    <link href="/07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="/07_Assets/node_modules/material-symbols/outlined.css" />
    <style>
      * { box-sizing: border-box; margin: 0; padding: 0; }

      body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f4f7f6;
        height: 100vh;
        overflow: hidden;
      }

      /* ── Same wrapper/main-panel pattern as all other pages ── */
      .wrapper {
        display: flex;
        height: 100vh;
        overflow: hidden;
      }

      #sidebar-container { flex-shrink: 0; }

      .main-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        min-width: 0;
      }

      #topbar-container { flex-shrink: 0; z-index: 10; }

      /*
       * #content fills whatever height remains after the topbar.
       * position:relative makes it the anchor for all absolute overlays.
       */
      #content {
        flex: 1;
        position: relative;
        overflow: hidden;
      }

      /* MAP stretches to fill #content completely */
      #map-full {
        position: absolute;
        inset: 0;
        z-index: 0;
      }

      /* ── TOP SEARCH BAR — floats at top of #content ── */
      .top-bar {
        position: absolute;
        top: 0; left: 0; right: 0;
        z-index: 600;
        padding: 12px 16px 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.97);
        backdrop-filter: blur(8px);
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
      }

      .back-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #1d9e75;
        color: #fff;
        border: none;
        border-radius: 99px;
        padding: 7px 14px 7px 10px;
        font-size: 0.82rem;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        white-space: nowrap;
        flex-shrink: 0;
        text-decoration: none;
      }
      .back-pill .material-symbols-outlined { font-size: 1rem; }

      .search-bar {
        flex: 1;
        display: flex;
        align-items: center;
        background: #f0f4f2;
        border-radius: 99px;
        padding: 7px 16px;
        gap: 8px;
        border: 1.5px solid #e2e8e6;
        transition: border-color 0.2s;
      }
      .search-bar:focus-within { border-color: #1d9e75; }
      .search-bar .material-symbols-outlined { color: #87a199; font-size: 1.1rem; }
      .search-bar input {
        border: none;
        background: transparent;
        outline: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.88rem;
        color: #2d3748;
        width: 100%;
      }

      /* ── BOTTOM SHEET — floats at bottom of #content ── */
      .bottom-sheet {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        z-index: 500;
        background: #fff;
        border-radius: 20px 20px 0 0;
        box-shadow: 0 -4px 24px rgba(0, 0, 0, 0.13);
        transition: height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        height: 320px;
        display: flex;
        flex-direction: column;
      }
      .bottom-sheet.expanded { height: 70%; }

      .sheet-handle-row {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px 0 4px;
        cursor: pointer;
        flex-shrink: 0;
      }
      .sheet-handle {
        width: 40px; height: 4px;
        background: #d1d5db;
        border-radius: 99px;
      }

      .sheet-header { padding: 0 16px 8px; flex-shrink: 0; }
      .result-summary {
        font-size: 0.9rem; color: #374151; font-weight: 600;
        display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
      }
      .medicine-badge {
        background: #e8f7f2; color: #1d9e75;
        border-radius: 99px; padding: 2px 10px;
        font-size: 0.75rem; font-weight: 700;
      }
      .result-count { font-size: 0.78rem; color: #87a199; margin-top: 2px; }

      .filter-chips {
        display: flex; gap: 8px;
        padding: 0 16px 8px;
        overflow-x: auto; flex-shrink: 0;
        scrollbar-width: none;
      }
      .filter-chips::-webkit-scrollbar { display: none; }
      .chip {
        display: flex; align-items: center; gap: 4px;
        border: 1.5px solid #e2e8e6; background: #fff;
        border-radius: 99px; padding: 4px 12px;
        font-size: 0.75rem; font-weight: 600; color: #4b5563;
        cursor: pointer; white-space: nowrap; transition: all 0.18s;
        font-family: 'Plus Jakarta Sans', sans-serif;
      }
      .chip.active, .chip:hover { background: #1d9e75; border-color: #1d9e75; color: #fff; }
      .chip .material-symbols-outlined { font-size: 0.9rem; }

      .pharmacy-scroll {
        flex: 1; overflow-y: auto;
        padding: 4px 16px 20px;
        scrollbar-width: thin; scrollbar-color: #d1d5db transparent;
      }

      .pharmacy-result-card {
        background: #fff; border-radius: 14px;
        border: 1.5px solid #e8eeec; padding: 12px;
        margin-bottom: 10px;
        display: flex; gap: 12px; align-items: flex-start;
        cursor: pointer;
        transition: box-shadow 0.18s, border-color 0.18s, transform 0.15s;
      }
      .pharmacy-result-card:hover {
        box-shadow: 0 4px 18px rgba(29,158,117,0.12);
        border-color: #1d9e75; transform: translateY(-1px);
      }
      .pharmacy-result-card.selected { border-color: #1d9e75; background: #f0fbf7; }

      .pharmacy-logo-placeholder {
        width: 48px; height: 48px; border-radius: 10px;
        background: #e8f7f2;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
      }
      .pharmacy-logo-placeholder .material-symbols-outlined { color: #1d9e75; font-size: 1.4rem; }

      .pharmacy-info { flex: 1; min-width: 0; }
      .pharmacy-name {
        font-size: 0.9rem; font-weight: 700; color: #1a202c;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
      }
      .pharmacy-addr {
        font-size: 0.72rem; color: #87a199; margin-top: 2px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
      }
      .pharmacy-meta {
        display: flex; align-items: center; gap: 6px;
        margin-top: 5px; flex-wrap: wrap;
      }
      .meta-pill { font-size: 0.68rem; font-weight: 600; padding: 2px 8px; border-radius: 99px; }
      .open-pill { background: #e8f7f2; color: #1d9e75; }
      .closed-pill { background: #fee2e2; color: #ef4444; }
      .dist-pill { background: #f0f4f2; color: #4b5563; display: flex; align-items: center; gap: 2px; }
      .dist-pill .material-symbols-outlined { font-size: 0.72rem; }

      .medicine-stock-row {
        margin-top: 7px; background: #f8fffe;
        border-radius: 10px; padding: 7px 10px;
        display: flex; align-items: center; justify-content: space-between;
        border: 1px solid #d1f0e6;
      }
      .stock-info { display: flex; flex-direction: column; }
      .med-name-sm { font-size: 0.75rem; font-weight: 700; color: #2d3748; }
      .stock-badge {
        font-size: 0.67rem; font-weight: 600; margin-top: 2px;
        display: flex; align-items: center; gap: 3px;
      }
      .in-stock { color: #1d9e75; }
      .low-stock { color: #f59e0b; }
      .price-tag { font-size: 0.9rem; font-weight: 800; color: #1d9e75; text-align: right; }
      .per-unit { font-size: 0.63rem; color: #87a199; font-weight: 400; }

      .card-action-row { display: flex; gap: 8px; margin-top: 8px; }
      .btn-directions {
        flex: 1; background: #1d9e75; color: #fff; border: none;
        border-radius: 8px; padding: 6px 12px;
        font-size: 0.76rem; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer; display: flex; align-items: center;
        justify-content: center; gap: 4px; transition: background 0.18s;
      }
      .btn-directions:hover { background: #178a63; }
      .btn-directions .material-symbols-outlined { font-size: 0.9rem; }
      .btn-save {
        background: #f0f4f2; color: #4b5563; border: none;
        border-radius: 8px; padding: 6px 10px;
        font-size: 0.76rem; font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer; display: flex; align-items: center; gap: 4px;
        transition: background 0.18s;
      }
      .btn-save:hover { background: #e8f7f2; color: #1d9e75; }
      .btn-save .material-symbols-outlined { font-size: 0.9rem; }

      /* Floating button — also absolute inside #content */
      .floating-btns {
        position: absolute;
        bottom: 330px; right: 16px;
        z-index: 550;
      }
      .float-btn {
        width: 44px; height: 44px; border-radius: 50%;
        background: #1d9e75; color: #fff; border: none;
        box-shadow: 0 4px 14px rgba(29,158,117,0.35);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: transform 0.18s, background 0.18s;
      }
      .float-btn:hover { transform: scale(1.08); background: #178a63; }
      .float-btn .material-symbols-outlined { font-size: 1.25rem; }

      /* Leaflet popup */
      .leaflet-popup-content-wrapper {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.14);
      }
      .popup-inner { font-family: 'Plus Jakarta Sans', sans-serif; }
      .popup-name { font-weight: 700; font-size: 0.85rem; color: #1a202c; }
      .popup-avail { font-size: 0.73rem; color: #1d9e75; font-weight: 600; margin-top: 3px; }
      .popup-price { font-size: 0.78rem; font-weight: 800; color: #1d9e75; margin-top: 2px; }
    </style>
  </head>
  <body>
    <div class="wrapper">

      <!-- Sidebar -->
      <div id="sidebar-container"></div>

      <!-- Main panel -->
      <div class="main-panel">

        <!-- Topbar -->
        <div id="topbar-container"></div>

        <!-- Content: map + all overlays live here, respects sidebar & topbar -->
        <div id="content">

          <!-- MAP (z-index 0, behind everything) -->
          <div id="map-full"></div>

          <!-- TOP SEARCH BAR (absolute top of #content) -->
          <div class="top-bar">
            <a href="02_ScanRX.html" class="back-pill">
              <span class="material-symbols-outlined">arrow_back</span>
              Back
            </a>
            <div class="search-bar">
              <span class="material-symbols-outlined">search</span>
              <input type="text" id="searchInput" value="Paracetamol 500mg" placeholder="Search medicine..." />
            </div>
          </div>

          <!-- BOTTOM SHEET (absolute bottom of #content) -->
          <div class="bottom-sheet" id="bottomSheet">
            <div class="sheet-handle-row" id="sheetHandleRow">
              <div class="sheet-handle"></div>
            </div>

            <div class="sheet-header">
              <div class="result-summary">
                <span>12 nearby pharmacies with</span>
                <span class="medicine-badge">Paracetamol 500mg</span>
              </div>
              <div class="result-count">Sorted by distance · Malaybalay, Bukidnon</div>
            </div>

            <div class="filter-chips">
              <div class="chip active"><span class="material-symbols-outlined">near_me</span>Nearest</div>
              <div class="chip"><span class="material-symbols-outlined">check_circle</span>In Stock</div>
              <div class="chip"><span class="material-symbols-outlined">schedule</span>Open Now</div>
              <div class="chip"><span class="material-symbols-outlined">payments</span>Lowest Price</div>
              <div class="chip"><span class="material-symbols-outlined">star</span>Top Rated</div>
            </div>

            <div class="pharmacy-scroll">

              <div class="pharmacy-result-card selected" data-lat="8.1548" data-lng="125.1281" data-name="Rose Pharmacy">
                <div class="pharmacy-logo-placeholder"><span class="material-symbols-outlined">local_pharmacy</span></div>
                <div class="pharmacy-info">
                  <div class="pharmacy-name">Rose Pharmacy</div>
                  <div class="pharmacy-addr">Fortich St., Barangay 8, Malaybalay City</div>
                  <div class="pharmacy-meta">
                    <span class="meta-pill open-pill">Open · Closes 10PM</span>
                    <span class="meta-pill dist-pill"><span class="material-symbols-outlined">near_me</span>0.8 km</span>
                  </div>
                  <div class="medicine-stock-row">
                    <div class="stock-info">
                      <span class="med-name-sm">Paracetamol 500mg · Tablet</span>
                      <span class="stock-badge in-stock"><span class="material-symbols-outlined" style="font-size:.72rem">check_circle</span>In Stock · 8 packs left</span>
                    </div>
                    <div><div class="price-tag">₱2.75</div><div class="per-unit">/ tablet</div></div>
                  </div>
                  <div class="card-action-row">
                    <button class="btn-directions"><span class="material-symbols-outlined">north_east</span>Get Directions</button>
                    <button class="btn-save"><span class="material-symbols-outlined">bookmark</span>Save</button>
                  </div>
                </div>
              </div>

              <div class="pharmacy-result-card" data-lat="8.1563" data-lng="125.1285" data-name="Generika Drugstore">
                <div class="pharmacy-logo-placeholder"><span class="material-symbols-outlined">local_pharmacy</span></div>
                <div class="pharmacy-info">
                  <div class="pharmacy-name">Generika Drugstore</div>
                  <div class="pharmacy-addr">Fortich St., Poblacion, Malaybalay City</div>
                  <div class="pharmacy-meta">
                    <span class="meta-pill open-pill">Open · Closes 9PM</span>
                    <span class="meta-pill dist-pill"><span class="material-symbols-outlined">near_me</span>0.8 km</span>
                  </div>
                  <div class="medicine-stock-row">
                    <div class="stock-info">
                      <span class="med-name-sm">Paracetamol 500mg · Tablet</span>
                      <span class="stock-badge in-stock"><span class="material-symbols-outlined" style="font-size:.72rem">check_circle</span>In Stock · 8 packs left</span>
                    </div>
                    <div><div class="price-tag">₱2.75</div><div class="per-unit">/ tablet</div></div>
                  </div>
                  <div class="card-action-row">
                    <button class="btn-directions"><span class="material-symbols-outlined">north_east</span>Get Directions</button>
                    <button class="btn-save"><span class="material-symbols-outlined">bookmark</span>Save</button>
                  </div>
                </div>
              </div>

              <div class="pharmacy-result-card" data-lat="8.1547" data-lng="125.1279" data-name="Mercury Drug">
                <div class="pharmacy-logo-placeholder"><span class="material-symbols-outlined">local_pharmacy</span></div>
                <div class="pharmacy-info">
                  <div class="pharmacy-name">Mercury Drug</div>
                  <div class="pharmacy-addr">Sayre Highway, Casisang, Malaybalay City</div>
                  <div class="pharmacy-meta">
                    <span class="meta-pill open-pill">Open 24 Hours</span>
                    <span class="meta-pill dist-pill"><span class="material-symbols-outlined">near_me</span>1.1 km</span>
                  </div>
                  <div class="medicine-stock-row">
                    <div class="stock-info">
                      <span class="med-name-sm">Paracetamol 500mg · Tablet</span>
                      <span class="stock-badge low-stock"><span class="material-symbols-outlined" style="font-size:.72rem">warning</span>Low Stock · 3 packs left</span>
                    </div>
                    <div><div class="price-tag">₱3.00</div><div class="per-unit">/ tablet</div></div>
                  </div>
                  <div class="card-action-row">
                    <button class="btn-directions"><span class="material-symbols-outlined">north_east</span>Get Directions</button>
                    <button class="btn-save"><span class="material-symbols-outlined">bookmark</span>Save</button>
                  </div>
                </div>
              </div>

              <div class="pharmacy-result-card" data-lat="8.1547" data-lng="125.1275" data-name="Watsons Pharmacy">
                <div class="pharmacy-logo-placeholder"><span class="material-symbols-outlined">local_pharmacy</span></div>
                <div class="pharmacy-info">
                  <div class="pharmacy-name">Watsons Pharmacy</div>
                  <div class="pharmacy-addr">Centrio Mall, Sayre Highway, Malaybalay City</div>
                  <div class="pharmacy-meta">
                    <span class="meta-pill open-pill">Open · Closes 9PM</span>
                    <span class="meta-pill dist-pill"><span class="material-symbols-outlined">near_me</span>1.4 km</span>
                  </div>
                  <div class="medicine-stock-row">
                    <div class="stock-info">
                      <span class="med-name-sm">Paracetamol 500mg · Tablet</span>
                      <span class="stock-badge in-stock"><span class="material-symbols-outlined" style="font-size:.72rem">check_circle</span>In Stock · 12 packs left</span>
                    </div>
                    <div><div class="price-tag">₱2.75</div><div class="per-unit">/ tablet</div></div>
                  </div>
                  <div class="card-action-row">
                    <button class="btn-directions"><span class="material-symbols-outlined">north_east</span>Get Directions</button>
                    <button class="btn-save"><span class="material-symbols-outlined">bookmark</span>Save</button>
                  </div>
                </div>
              </div>

              <div class="pharmacy-result-card" data-lat="8.1558" data-lng="125.1269" data-name="Rojon Pharmacy">
                <div class="pharmacy-logo-placeholder"><span class="material-symbols-outlined">local_pharmacy</span></div>
                <div class="pharmacy-info">
                  <div class="pharmacy-name">Rojon Pharmacy</div>
                  <div class="pharmacy-addr">546C+P3R, Sayre Hwy, Malaybalay City</div>
                  <div class="pharmacy-meta">
                    <span class="meta-pill open-pill">Open · Closes 10PM</span>
                    <span class="meta-pill dist-pill"><span class="material-symbols-outlined">near_me</span>1.7 km</span>
                  </div>
                  <div class="medicine-stock-row">
                    <div class="stock-info">
                      <span class="med-name-sm">Paracetamol 500mg · Tablet</span>
                      <span class="stock-badge in-stock"><span class="material-symbols-outlined" style="font-size:.72rem">check_circle</span>In Stock · 5 packs left</span>
                    </div>
                    <div><div class="price-tag">₱2.50</div><div class="per-unit">/ tablet</div></div>
                  </div>
                  <div class="card-action-row">
                    <button class="btn-directions"><span class="material-symbols-outlined">north_east</span>Get Directions</button>
                    <button class="btn-save"><span class="material-symbols-outlined">bookmark</span>Save</button>
                  </div>
                </div>
              </div>

              <div class="pharmacy-result-card" data-lat="8.1567" data-lng="125.1263" data-name="TGP The Generics Pharmacy">
                <div class="pharmacy-logo-placeholder"><span class="material-symbols-outlined">local_pharmacy</span></div>
                <div class="pharmacy-info">
                  <div class="pharmacy-name">TGP The Generics Pharmacy</div>
                  <div class="pharmacy-addr">Chavez St., Poblacion, Malaybalay City</div>
                  <div class="pharmacy-meta">
                    <span class="meta-pill open-pill">Open · Closes 8PM</span>
                    <span class="meta-pill dist-pill"><span class="material-symbols-outlined">near_me</span>2.0 km</span>
                  </div>
                  <div class="medicine-stock-row">
                    <div class="stock-info">
                      <span class="med-name-sm">Paracetamol 500mg · Tablet</span>
                      <span class="stock-badge in-stock"><span class="material-symbols-outlined" style="font-size:.72rem">check_circle</span>In Stock · 20 packs left</span>
                    </div>
                    <div><div class="price-tag">₱2.00</div><div class="per-unit">/ tablet</div></div>
                  </div>
                  <div class="card-action-row">
                    <button class="btn-directions"><span class="material-symbols-outlined">north_east</span>Get Directions</button>
                    <button class="btn-save"><span class="material-symbols-outlined">bookmark</span>Save</button>
                  </div>
                </div>
              </div>

            </div><!-- /pharmacy-scroll -->
          </div><!-- /bottom-sheet -->

          <!-- Floating bot button -->
          <div class="floating-btns" id="floatingBtns">
            <button class="float-btn" title="AI Assistant">
              <span class="material-symbols-outlined">smart_toy</span>
            </button>
          </div>

        </div><!-- /#content -->
      </div><!-- /.main-panel -->
    </div><!-- /.wrapper -->

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/07_Assets/css/js/sidebar_and_topbar.js"></script>
    <script>
      // ── MAP INIT ──
      const map = L.map('map-full').setView([8.1555, 125.1275], 15);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(map);

      function makeIcon(color = '#1d9e75') {
        return L.divIcon({
          className: '',
          html: `<div style="background:${color};width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.25);"></div>`,
          iconSize: [30, 30], iconAnchor: [15, 30], popupAnchor: [0, -32]
        });
      }

      const pharmacyData = [
        { name: 'Rose Pharmacy',            lat: 8.1548, lng: 125.1281, stock: 'In Stock · 8 packs',   price: '₱2.75' },
        { name: 'Generika Drugstore',        lat: 8.1563, lng: 125.1285, stock: 'In Stock · 8 packs',   price: '₱2.75' },
        { name: 'Mercury Drug',              lat: 8.1547, lng: 125.1279, stock: 'Low Stock · 3 packs',  price: '₱3.00' },
        { name: 'Watsons Pharmacy',          lat: 8.1547, lng: 125.1275, stock: 'In Stock · 12 packs',  price: '₱2.75' },
        { name: 'Rojon Pharmacy',            lat: 8.1558, lng: 125.1269, stock: 'In Stock · 5 packs',   price: '₱2.50' },
        { name: 'TGP The Generics Pharmacy', lat: 8.1567, lng: 125.1263, stock: 'In Stock · 20 packs',  price: '₱2.00' },
      ];

      const markers = {};
      pharmacyData.forEach(p => {
        const m = L.marker([p.lat, p.lng], { icon: makeIcon() }).addTo(map)
          .bindPopup(`<div class="popup-inner"><div class="popup-name">${p.name}</div><div class="popup-avail">${p.stock}</div><div class="popup-price">Paracetamol 500mg · ${p.price}/tablet</div></div>`);
        markers[p.name] = m;
      });
      markers['Rose Pharmacy'].openPopup();

      // ── BOTTOM SHEET TOGGLE ──
      const sheet = document.getElementById('bottomSheet');
      const floatingBtns = document.getElementById('floatingBtns');

      document.getElementById('sheetHandleRow').addEventListener('click', () => {
        sheet.classList.toggle('expanded');
        floatingBtns.style.bottom = sheet.classList.contains('expanded') ? 'calc(70% + 10px)' : '330px';
        setTimeout(() => map.invalidateSize(), 360);
      });

      // ── CARD CLICK → PAN MAP ──
      document.querySelectorAll('.pharmacy-result-card').forEach(card => {
        card.addEventListener('click', () => {
          document.querySelectorAll('.pharmacy-result-card').forEach(c => c.classList.remove('selected'));
          card.classList.add('selected');
          const lat = parseFloat(card.dataset.lat);
          const lng = parseFloat(card.dataset.lng);
          const name = card.dataset.name;
          map.setView([lat, lng], 16);
          if (markers[name]) markers[name].openPopup();
          sheet.classList.remove('expanded');
          floatingBtns.style.bottom = '330px';
          setTimeout(() => map.invalidateSize(), 360);
        });

        card.querySelector('.btn-directions').addEventListener('click', e => {
          e.stopPropagation();
          window.open(`https://www.openstreetmap.org/directions?to=${card.dataset.lat},${card.dataset.lng}`, '_blank');
        });
      });

      // ── FILTER CHIPS ──
      document.querySelectorAll('.chip').forEach(chip => {
        chip.addEventListener('click', () => {
          document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
          chip.classList.add('active');
        });
      });
    </script>
  </body>
</html>