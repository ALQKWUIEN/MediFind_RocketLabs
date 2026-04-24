<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MediFind: Pharmacies</title>

    <link rel="icon" href="/07_Assets/images/logo.png" type="image/png" />
    <link href="/07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/07_Assets/css/pharmacies.css" />
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
                      <span
                        class="material-symbols-outlined"
                        style="color: #87a199; font-size: 1.2rem"
                        >search</span
                      >
                      <input
                        type="text"
                        placeholder="Search pharmacy by name..."
                      />
                    </div>
                    <button class="search-filter-btn">
                      <span class="material-symbols-outlined"
                        >discover_tune</span
                      >
                    </button>
                  </div>
                </div>

                <div class="nearby-section">
                  <div class="section-header">
                    <h5>Nearby Pharmacies</h5>
                    <a href="#">See all</a>
                  </div>

                  <div class="pharmacy-list">
                    <div
                      class="pharmacy-card"
                      data-name="Rose Pharmacy"
                      data-address="Sayre Highway, Poblacion, Malaybalay City, Bukidnon"
                      data-distance="0.5 km away"
                      data-status="Open · Closes 10PM"
                      data-lat="8.1548"
                      data-lng="125.1281"
                      data-img="/07_Assets/images/pharmacies/RosePharmacy.png"
                    >
                      <img
                        src="/07_Assets/images/pharmacies/RosePharmacy.png"
                        alt="Rose Pharmacy"
                        class="pharmacy-card-img"
                      />
                      <div class="pharmacy-card-body">
                        <h6>Rose Pharmacy</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined"
                            >location_on</span
                          >
                          <span>0.5 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span
                          ><span class="dot">·</span><span>Closes 10PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined"
                            >north_east</span
                          >
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div
                      class="pharmacy-card"
                      data-name="Generika Drugstore"
                      data-address="Fortich St., Poblacion, Malaybalay City, Bukidnon"
                      data-distance="0.8 km away"
                      data-status="Open · Closes 9PM"
                      data-lat="8.1563"
                      data-lng="125.1285"
                      data-img="/07_Assets/images/pharmacies/Generika.png"
                    >
                      <img
                        src="/07_Assets/images/pharmacies/Generika.png"
                        alt="Generika Drugstore"
                        class="pharmacy-card-img"
                      />
                      <div class="pharmacy-card-body">
                        <h6>Generika Drugstore</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined"
                            >location_on</span
                          >
                          <span>0.8 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span
                          ><span class="dot">·</span><span>Closes 9PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined"
                            >north_east</span
                          >
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div
                      class="pharmacy-card"
                      data-name="Mercury Drug"
                      data-address="Sayre Highway, Casisang, Malaybalay City, Bukidnon"
                      data-distance="1.1 km away"
                      data-status="Open · Open 24 Hours"
                      data-lat="8.1547"
                      data-lng="125.1279"
                      data-img="/07_Assets/images/pharmacies/MercuryDrug.png"
                    >
                      <img
                        src="/07_Assets/images/pharmacies/MercuryDrug.jpg"
                        alt="Mercury Drug"
                        class="pharmacy-card-img"
                      />
                      <div class="pharmacy-card-body">
                        <h6>Mercury Drug</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined"
                            >location_on</span
                          >
                          <span>1.1 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span
                          ><span class="dot">·</span><span>Open 24 Hours</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined"
                            >north_east</span
                          >
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div
                      class="pharmacy-card"
                      data-name="Watsons Pharmacy"
                      data-address="Centrio Mall, Sayre Highway, Malaybalay City, Bukidnon"
                      data-distance="1.4 km away"
                      data-status="Open · Closes 9PM"
                      data-lat="8.1547"
                      data-lng="125.1275"
                      data-img="/07_Assets/images/pharmacies/Watsons.png"
                    >
                      <img
                        src="/07_Assets/images/pharmacies/Watsons.png"
                        alt="Watsons Pharmacy"
                        class="pharmacy-card-img"
                      />
                      <div class="pharmacy-card-body">
                        <h6>Watsons Pharmacy</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined"
                            >location_on</span
                          >
                          <span>1.4 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span
                          ><span class="dot">·</span><span>Closes 9PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined"
                            >north_east</span
                          >
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div
                      class="pharmacy-card"
                      data-name="Rojon Pharmacy"
                      data-address="546C+P3R, Sayre Hwy, Malaybalay City, Bukidnon"
                      data-distance="1.7 km away"
                      data-status="Open · Closes 10PM"
                      data-lat="8.1558"
                      data-lng="125.1269"
                      data-img="/07_Assets/images/pharmacies/RojonPharmacy.png"
                    >
                      <img
                        src="/07_Assets/images/pharmacies/RojonPharmacy.png"
                        alt="Rojon Pharmacy"
                        class="pharmacy-card-img"
                      />
                      <div class="pharmacy-card-body">
                        <h6>Rojon Pharmacy</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined"
                            >location_on</span
                          >
                          <span>1.7 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span
                          ><span class="dot">·</span><span>Closes 10PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined"
                            >north_east</span
                          >
                          Get Directions
                        </button>
                      </div>
                    </div>

                    <div
                      class="pharmacy-card"
                      data-name="TGP The Generics Pharmacy"
                      data-address="Chavez St., Poblacion, Malaybalay City, Bukidnon"
                      data-distance="2.0 km away"
                      data-status="Open · Closes 8PM"
                      data-lat="8.1567"
                      data-lng="125.1263"
                      data-img="/07_Assets/images/pharmacies/TGP.png"
                    >
                      <img
                        src="/07_Assets/images/pharmacies/TGP.png"
                        alt="TGP The Generics Pharmacy"
                        class="pharmacy-card-img"
                      />
                      <div class="pharmacy-card-body">
                        <h6>TGP The Generics Pharmacy</h6>
                        <div class="pharmacy-distance">
                          <span class="material-symbols-outlined"
                            >location_on</span
                          >
                          <span>2.0 km away</span>
                        </div>
                        <div class="pharmacy-status">
                          <span class="open-text">Open</span
                          ><span class="dot">·</span><span>Closes 8PM</span>
                        </div>
                        <button class="direction-btn">
                          <span class="material-symbols-outlined"
                            >north_east</span
                          >
                          Get Directions
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- LEFT PANEL -->
              <div class="left-panel" id="left-panel" style="display: none">
                <!-- Detail view: shown after clicking Get Directions -->
                <div id="view-detail" style="display: none">
                  <button class="back-btn" id="back-btn" href="/04_User/04_Pharmacies.html">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Back
                  </button>

                  <div class="detail-header">
                    <div class="detail-pharmacy-name-row">
                      <div class="detail-icon-box">
                        <span class="material-symbols-outlined"
                          >local_pharmacy</span
                        >
                      </div>
                      <div>
                        <h5 id="detail-name">Rojon Pharmacy</h5>
                        <div class="user-location">
                          <span class="material-symbols-outlined"
                            >location_on</span
                          >
                          <span id="detail-subtitle"
                            >Malaybalay, 8700, Bukidnon, Philippines</span
                          >
                        </div>
                      </div>
                    </div>
                    <img
                      id="detail-img"
                      src=""
                      alt=""
                      class="detail-pharmacy-img"
                    />
                  </div>

                  <div class="detail-info-block">
                    <h4 id="detail-name-big"></h4>
                    <div class="pharmacy-distance" style="margin-bottom: 6px">
                      <span class="material-symbols-outlined">location_on</span>
                      <span id="detail-distance"></span>
                    </div>
                    <div class="pharmacy-status" style="margin-bottom: 16px">
                      <span class="open-text" id="detail-status"></span>
                    </div>

                    <div class="detail-actions">
                      <button class="direction-btn" id="detail-get-directions">
                        <span class="material-symbols-outlined"
                          >north_east</span
                        >
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
                      <span class="material-symbols-outlined">verified</span>
                      <span>Claim this business</span>
                    </div>
                    <div class="detail-meta-item">
                      <span class="material-symbols-outlined">history</span>
                      <span>Your Maps history</span>
                    </div>
                    <div class="detail-meta-item">
                      <span class="material-symbols-outlined">label</span>
                      <span>Add a label</span>
                    </div>
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

    <script src="/07_Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/bootstrap/js/jquery.min.js"></script>
    <script src="/bootstrap/js/popper.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script src="/07_Assets/css/js/sidebar_and_topbar.js"></script>

    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
      let leafletMap = null;

      function openDirections(card) {
        const name = card.dataset.name;
        const address = card.dataset.address;
        const distance = card.dataset.distance;
        const status = card.dataset.status;
        const lat = parseFloat(card.dataset.lat);
        const lng = parseFloat(card.dataset.lng);
        const img = card.dataset.img;

        // Fill detail panel
        document.getElementById("detail-name").textContent = name;
        document.getElementById("detail-subtitle").textContent = address;
        document.getElementById("detail-name-big").textContent = name;
        document.getElementById("detail-distance").textContent = distance;
        document.getElementById("detail-status").textContent = status;
        document.getElementById("detail-address").textContent = address;
        document.getElementById("detail-img").src = img;

        // Wire "Get Directions" in detail to open OSM
        document.getElementById("detail-get-directions").onclick = () => {
          window.open(
            `https://www.openstreetmap.org/directions?to=${lat},${lng}`,
            "_blank",
          );
        };

        // Switch views
        document.getElementById("view-list").style.display = "none";
        document.getElementById("left-panel").style.display = "block"; // show left panel
        document.getElementById("view-detail").style.display = "block";
        document.getElementById("map-panel").style.display = "block";

        // Init or update map
        setTimeout(() => {
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
          L.marker([lat, lng]).addTo(leafletMap).bindPopup(name).openPopup();
          leafletMap.invalidateSize();
        }, 100);
      }

      // Clicking direction-btn on a card
      document
        .querySelectorAll(".pharmacy-card .direction-btn")
        .forEach((btn) => {
          btn.addEventListener("click", (e) => {
            e.stopPropagation();
            openDirections(btn.closest(".pharmacy-card"));
          });
        });

      // Clicking the card itself also opens it
      document.querySelectorAll(".pharmacy-card").forEach((card) => {
        card.addEventListener("click", () => openDirections(card));
      });

      // Back button
      document.getElementById("back-btn").addEventListener("click", () => {
        document.getElementById("view-list").style.display = "block";
        document.getElementById("left-panel").style.display = "none"; // hide left panel
        document.getElementById("view-detail").style.display = "none";
        document.getElementById("map-panel").style.display = "none";
      });
    </script>
  </body>
</html>
