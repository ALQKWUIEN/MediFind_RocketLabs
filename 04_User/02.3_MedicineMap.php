<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MediFind: Find Medicine</title>
    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../07_Assets/node_modules/material-symbols/outlined.css" />
    <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/medicinemap.css"/>

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
            <!-- <a href="02_ScanRX.php" class="back-pill">
              <span class="material-symbols-outlined">arrow_back</span>
              Back
            </a> -->
            <a class="back-btn" onclick="history.back()" href="02_ScanRX.php">
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
    <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
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