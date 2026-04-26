<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MediFind: Pharmacies</title>

  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/pharmacies.css" />
  
  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php'?>

</head>

<body>
  <div class="wrapper d-flex align-items-stretch">
    <div id="sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content">
        <div class="content-body">
          <div class="pharmacies-layout">
            <!-- Default view: search + list -->
            <div id="view-list" style="width: 100%; padding: 20px 16px 32px">
              <div class="search-section">
                <div class="welcome-location-block">
                  <h2 class="welcome-user">Browse Pharmacies</h2>
                  <div class="user-location">
                    <span class="material-symbols-outlined">location_on</span>
                    <span>Malaybalay, 8700, Bukidnon, Philippines</span>
                  </div>
                </div>
                <div class="search-row">
                  <div class="search-wrapper">
                    <span class="material-symbols-outlined" style="color: #87a199; font-size: 1.2rem">search</span>
                    <input type="text" placeholder="Search pharmacy by name..." />
                  </div>
                  <button class="search-filter-btn">
                    <span class="material-symbols-outlined">discover_tune</span>
                  </button>
                </div>
              </div>

              <div class="nearby-section">
                <div class="section-header">
                  <h5>Nearby Pharmacies</h5>
                  <a href="#">See all</a>
                </div>

                <div class="pharmacy-list">
                  <div class="pharmacy-card"
                    data-name="Rose Pharmacy"
                    data-address="Sayre Highway, Poblacion, Malaybalay City, Bukidnon"
                    data-distance="0.5 km away"
                    data-status="Open · Closes 10PM"
                    data-lat="8.1548" data-lng="125.1281"
                    data-img="../07_Assets/images/pharmacies/RosePharmacy.png"
                    data-medicines='[
                      {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 8 packs","price":"₱2.75","per":"tablet","status":"in"},
                      {"name":"Amoxicillin","dosage":"500mg · Capsule","stock":"In Stock · 5 packs","price":"₱8.50","per":"capsule","status":"in"},
                      {"name":"Ibuprofen","dosage":"200mg · Tablet","stock":"Low Stock · 2 packs","price":"₱3.00","per":"tablet","status":"low"}
                    ]'>
                    <img src="../07_Assets/images/pharmacies/RosePharmacy.png" alt="Rose Pharmacy" class="pharmacy-card-img" />
                    <div class="pharmacy-card-body">
                      <h6>Rose Pharmacy</h6>
                      <div class="pharmacy-distance">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>0.5 km away</span>
                      </div>
                      <div class="pharmacy-status">
                        <span class="open-text">Open</span><span class="dot">·</span><span>Closes 10PM</span>
                      </div>
                      <button class="direction-btn">
                        <span class="material-symbols-outlined">north_east</span>
                        Get Directions
                      </button>
                    </div>
                  </div>

                  <div class="pharmacy-card"
                    data-name="Generika Drugstore"
                    data-address="Fortich St., Poblacion, Malaybalay City, Bukidnon"
                    data-distance="0.8 km away"
                    data-status="Open · Closes 9PM"
                    data-lat="8.1563" data-lng="125.1285"
                    data-img="../07_Assets/images/pharmacies/Generika.png"
                    data-medicines='[
                      {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 8 packs","price":"₱2.75","per":"tablet","status":"in"},
                      {"name":"Losartan","dosage":"50mg · Tablet","stock":"In Stock · 10 packs","price":"₱6.00","per":"tablet","status":"in"},
                      {"name":"Metformin","dosage":"500mg · Tablet","stock":"In Stock · 6 packs","price":"₱4.50","per":"tablet","status":"in"}
                    ]'>
                    <img src="../07_Assets/images/pharmacies/Generika.png" alt="Generika Drugstore" class="pharmacy-card-img" />
                    <div class="pharmacy-card-body">
                      <h6>Generika Drugstore</h6>
                      <div class="pharmacy-distance">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>0.8 km away</span>
                      </div>
                      <div class="pharmacy-status">
                        <span class="open-text">Open</span><span class="dot">·</span><span>Closes 9PM</span>
                      </div>
                      <button class="direction-btn">
                        <span class="material-symbols-outlined">north_east</span>
                        Get Directions
                      </button>
                    </div>
                  </div>

                  <div class="pharmacy-card"
                    data-name="Mercury Drug"
                    data-address="Sayre Highway, Casisang, Malaybalay City, Bukidnon"
                    data-distance="1.1 km away"
                    data-status="Open · Open 24 Hours"
                    data-lat="8.1547" data-lng="125.1279"
                    data-img="../07_Assets/images/pharmacies/MercuryDrug.png"
                    data-medicines='[
                      {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"Low Stock · 3 packs","price":"₱3.00","per":"tablet","status":"low"},
                      {"name":"Cetirizine","dosage":"10mg · Tablet","stock":"In Stock · 12 packs","price":"₱5.00","per":"tablet","status":"in"},
                      {"name":"Omeprazole","dosage":"20mg · Capsule","stock":"In Stock · 7 packs","price":"₱7.25","per":"capsule","status":"in"}
                    ]'>
                    <img src="../07_Assets/images/pharmacies/MercuryDrug.jpg" alt="Mercury Drug" class="pharmacy-card-img" />
                    <div class="pharmacy-card-body">
                      <h6>Mercury Drug</h6>
                      <div class="pharmacy-distance">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>1.1 km away</span>
                      </div>
                      <div class="pharmacy-status">
                        <span class="open-text">Open</span><span class="dot">·</span><span>Open 24 Hours</span>
                      </div>
                      <button class="direction-btn">
                        <span class="material-symbols-outlined">north_east</span>
                        Get Directions
                      </button>
                    </div>
                  </div>

                  <div class="pharmacy-card"
                    data-name="Watsons Pharmacy"
                    data-address="Centrio Mall, Sayre Highway, Malaybalay City, Bukidnon"
                    data-distance="1.4 km away"
                    data-status="Open · Closes 9PM"
                    data-lat="8.1547" data-lng="125.1275"
                    data-img="../07_Assets/images/pharmacies/Watsons.png"
                    data-medicines='[
                      {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 12 packs","price":"₱2.75","per":"tablet","status":"in"},
                      {"name":"Vitamin C","dosage":"500mg · Tablet","stock":"In Stock · 20 packs","price":"₱2.00","per":"tablet","status":"in"},
                      {"name":"Biogesic","dosage":"500mg · Tablet","stock":"In Stock · 15 packs","price":"₱3.50","per":"tablet","status":"in"}
                    ]'>
                    <img src="../07_Assets/images/pharmacies/Watsons.png" alt="Watsons Pharmacy" class="pharmacy-card-img" />
                    <div class="pharmacy-card-body">
                      <h6>Watsons Pharmacy</h6>
                      <div class="pharmacy-distance">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>1.4 km away</span>
                      </div>
                      <div class="pharmacy-status">
                        <span class="open-text">Open</span><span class="dot">·</span><span>Closes 9PM</span>
                      </div>
                      <button class="direction-btn">
                        <span class="material-symbols-outlined">north_east</span>
                        Get Directions
                      </button>
                    </div>
                  </div>

                  <div class="pharmacy-card"
                    data-name="Rojon Pharmacy"
                    data-address="546C+P3R, Sayre Hwy, Malaybalay City, Bukidnon"
                    data-distance="1.7 km away"
                    data-status="Open · Closes 10PM"
                    data-lat="8.1558" data-lng="125.1269"
                    data-img="../07_Assets/images/pharmacies/RojonPharmacy.png"
                    data-medicines='[
                      {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 5 packs","price":"₱2.50","per":"tablet","status":"in"},
                      {"name":"Amoxicillin","dosage":"250mg · Capsule","stock":"Low Stock · 1 pack","price":"₱6.00","per":"capsule","status":"low"},
                      {"name":"Mefenamic Acid","dosage":"500mg · Capsule","stock":"In Stock · 9 packs","price":"₱4.00","per":"capsule","status":"in"}
                    ]'>
                    <img src="../07_Assets/images/pharmacies/RojonPharmacy.png" alt="Rojon Pharmacy" class="pharmacy-card-img" />
                    <div class="pharmacy-card-body">
                      <h6>Rojon Pharmacy</h6>
                      <div class="pharmacy-distance">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>1.7 km away</span>
                      </div>
                      <div class="pharmacy-status">
                        <span class="open-text">Open</span><span class="dot">·</span><span>Closes 10PM</span>
                      </div>
                      <button class="direction-btn">
                        <span class="material-symbols-outlined">north_east</span>
                        Get Directions
                      </button>
                    </div>
                  </div>

                  <div class="pharmacy-card"
                    data-name="TGP The Generics Pharmacy"
                    data-address="Chavez St., Poblacion, Malaybalay City, Bukidnon"
                    data-distance="2.0 km away"
                    data-status="Open · Closes 8PM"
                    data-lat="8.1567" data-lng="125.1263"
                    data-img="../07_Assets/images/pharmacies/TGP.png"
                    data-medicines='[
                      {"name":"Paracetamol","dosage":"500mg · Tablet","stock":"In Stock · 20 packs","price":"₱2.00","per":"tablet","status":"in"},
                      {"name":"Losartan","dosage":"25mg · Tablet","stock":"In Stock · 8 packs","price":"₱3.50","per":"tablet","status":"in"},
                      {"name":"Amlodipine","dosage":"5mg · Tablet","stock":"In Stock · 11 packs","price":"₱4.00","per":"tablet","status":"in"}
                    ]'>
                    <img src="../07_Assets/images/pharmacies/TGP.png" alt="TGP The Generics Pharmacy" class="pharmacy-card-img" />
                    <div class="pharmacy-card-body">
                      <h6>TGP The Generics Pharmacy</h6>
                      <div class="pharmacy-distance">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>2.0 km away</span>
                      </div>
                      <div class="pharmacy-status">
                        <span class="open-text">Open</span><span class="dot">·</span><span>Closes 8PM</span>
                      </div>
                      <button class="direction-btn">
                        <span class="material-symbols-outlined">north_east</span>
                        Get Directions
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- LEFT PANEL -->
            <div class="left-panel" id="left-panel" style="display: none">
              <div id="view-detail" style="display: none">
                <button class="back-btn" id="back-btn">
                  <span class="material-symbols-outlined">arrow_back</span>
                  Back
                </button>

                <div class="detail-header">
                  <div class="detail-pharmacy-name-row">
                    <div class="detail-icon-box">
                      <span class="material-symbols-outlined">local_pharmacy</span>
                    </div>
                    <div>
                      <h5 id="detail-name">Rojon Pharmacy</h5>
                      <div class="user-location">
                        <span class="material-symbols-outlined">location_on</span>
                        <span id="detail-subtitle">Malaybalay, 8700, Bukidnon, Philippines</span>
                      </div>
                    </div>
                  </div>
                  <img id="detail-img" src="" alt="" class="detail-pharmacy-img" />
                </div>

                <div class="detail-info-block">
                  <h4 id="detail-name-big"></h4>
                  <div class="pharmacy-distance" style="margin-bottom: 6px">
                    <span class="material-symbols-outlined">location_on</span>
                    <span id="detail-distance"></span>
                  </div>
                  <div class="pharmacy-status" style="margin-bottom: 16px">
                    <span id="detail-status"></span>
                  </div>
                  <div class="detail-actions">
                    <button class="direction-btn" id="detail-get-directions">
                      <span class="material-symbols-outlined">north_east</span>
                      Get Directions
                    </button>
                    <button class="save-btn">
                      <span class="material-symbols-outlined">bookmark</span>
                      Save Pharmacy
                    </button>
                  </div>
                </div>

                

                <div class="detail-meta">
                  <div class="detail-meta-item">
                    <span class="material-symbols-outlined">location_on</span>
                    <span id="detail-address"></span>
                  </div>
                  <div class="detail-meta-item">
                    <span class="material-symbols-outlined">label</span>
                    <span>Add a label</span>
                  </div>
                </div>

                <!-- ── AVAILABLE MEDICINES ── -->
                <div class="medicines-section">
                  <div class="medicines-section-header">
                    <span class="material-symbols-outlined">medication</span>
                    Available Medicines
                  </div>
                  <div id="medicines-list"></div>
                </div>

              </div>
            </div>

            

            <!-- RIGHT PANEL: Map -->
            <div class="right-panel" id="map-panel" style="display: none">
              <div id="map" style="width: 100%; height: 100%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="floating-btns">
    <button class="float-btn" onclick="history.back()">
      <span class="material-symbols-outlined bot-icon">smart_toy</span>
    </button>
  </div>

  <script src="../07_Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    let leafletMap = null;

    function renderMedicines(medicines) {
      const list = document.getElementById('medicines-list');
      if (!medicines || medicines.length === 0) {
        list.innerHTML = '<div class="no-medicines">No medicine data available.</div>';
        return;
      }
      list.innerHTML = medicines.map(m => `
        <div class="medicine-item">
          <div class="medicine-item-left">
            <div class="medicine-name">${m.name}</div>
            <div class="medicine-dosage">${m.dosage}</div>
            <div class="medicine-stock ${m.status === 'in' ? 'in-stock' : 'low-stock'}">
              <span class="material-symbols-outlined">${m.status === 'in' ? 'check_circle' : 'warning'}</span>
              ${m.stock}
            </div>
          </div>
          <div class="medicine-item-right">
            <div class="medicine-price">${m.price}</div>
            <div class="medicine-per">/ ${m.per}</div>
          </div>
        </div>
      `).join('');
    }

    function openDirections(card) {
      const name = card.dataset.name;
      const address = card.dataset.address;
      const distance = card.dataset.distance;
      const status = card.dataset.status;
      const lat = parseFloat(card.dataset.lat);
      const lng = parseFloat(card.dataset.lng);
      const img = card.dataset.img;
      const medicines = JSON.parse(card.dataset.medicines || '[]');

      document.getElementById("detail-name").textContent = name;
      document.getElementById("detail-subtitle").textContent = address;
      document.getElementById("detail-name-big").textContent = name;
      document.getElementById("detail-distance").textContent = distance;
      const [openPart, ...rest] = status.split("·");
      const statusEl = document.getElementById("detail-status");
      statusEl.innerHTML = `<span class="open-text">${openPart.trim()}</span><span class="dot"> · </span><span>${rest.join("·").trim()}</span>`;
      document.getElementById("detail-address").textContent = address;
      document.getElementById("detail-img").src = img;

      renderMedicines(medicines);

      document.getElementById("detail-get-directions").onclick = () => {
        window.open(`https://www.openstreetmap.org/directions?to=${lat},${lng}`, "_blank");
      };

      document.getElementById("view-list").style.display = "none";
      document.getElementById("left-panel").style.display = "block";
      document.getElementById("view-detail").style.display = "block";
      document.getElementById("map-panel").style.display = "block";

      setTimeout(() => {
        const icon = L.divIcon({
          className: '',
          html: `<div style="background:#1d9e75;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.25);"></div>`,
          iconSize: [30, 30],
          iconAnchor: [15, 30],
          popupAnchor: [0, -32]
        });

        if (!leafletMap) {
          leafletMap = L.map("map").setView([lat, lng], 16);
          L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "© OpenStreetMap contributors",
          }).addTo(leafletMap);
        } else {
          leafletMap.setView([lat, lng], 16);
          leafletMap.eachLayer((l) => {
            if (l instanceof L.Marker) leafletMap.removeLayer(l);
          });
        }

        L.marker([lat, lng], {
          icon
        }).addTo(leafletMap).bindPopup(`
          <div class="popup-inner">
            <div class="popup-top-bar">
              <div class="popup-name">${name}</div>
            </div>
            <div class="popup-bottom-bar">
              <div class="popup-address">${address}</div>
              <div class="popup-dist">${distance}</div>
            </div>
          </div>
        `, {
          maxWidth: 260
        }).openPopup();
        leafletMap.invalidateSize();
      }, 100);
    }

    document.querySelectorAll(".pharmacy-card .direction-btn").forEach((btn) => {
      btn.addEventListener("click", (e) => {
        e.stopPropagation();
        openDirections(btn.closest(".pharmacy-card"));
      });
    });

    document.querySelectorAll(".pharmacy-card").forEach((card) => {
      card.addEventListener("click", () => openDirections(card));
    });

    document.getElementById("back-btn").addEventListener("click", () => {
      document.getElementById("view-list").style.display = "block";
      document.getElementById("left-panel").style.display = "none";
      document.getElementById("view-detail").style.display = "none";
      document.getElementById("map-panel").style.display = "none";
    });
  </script>
</body>

</html>