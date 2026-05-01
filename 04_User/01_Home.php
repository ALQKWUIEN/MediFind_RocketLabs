<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MediFind: Home</title>

  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

  <?php include '../01_Includes/page-transition-hardcode.php' ?>
</head>

<body>
  <div class="wrapper d-flex align-items-stretch">
    <div id="sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content">
        <div class="content-body">
          <div class="home-layout">

            <!-- DEFAULT VIEW: full list -->
            <div id="home-view-list">
              <div class="search-section">
                <div class="welcome-location-block">
                  <h2 class="welcome-user">Hello, User 👋</h2>
                  <div class="user-location">
                    <span class="material-symbols-outlined">location_on</span>
                    <span>Malaybalay, 8700, Bukidnon, Philippines</span>
                  </div>
                </div>

                <div class="search-row">
                  <div class="search-wrapper">
                    <span class="material-symbols-outlined" style="color: #87a199; font-size: 1.2rem">search</span>
                    <input type="text" placeholder="Search medicine by name..." />
                  </div>
                  <button class="search-filter-btn">
                    <span class="material-symbols-outlined">discover_tune</span>
                  </button>
                </div>

                <!-- Quick Categories -->
                <div class="quick-categories-section">
                  <div class="section-header">
                    <h5>Quick Categories</h5>
                    <a href="#">See all</a>
                  </div>
                  <div class="quick-categories-wrapper">
                    <button class="category-nav prev" aria-label="Scroll left">
                      <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <div class="quick-categories" id="quickCategories">
                      <a href="#" class="category-item active">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">pulmonology</span>
                        </div>
                        <span class="category-label">Cough & Cold</span>
                      </a>
                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">sick</span>
                        </div>
                        <span class="category-label">Pain Relief</span>
                      </a>
                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">medication</span>
                        </div>
                        <span class="category-label">Antibiotics</span>
                      </a>
                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">ecg_heart</span>
                        </div>
                        <span class="category-label">Heart Care</span>
                      </a>
                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">water_drop</span>
                        </div>
                        <span class="category-label">Diabetes</span>
                      </a>
                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">allergy</span>
                        </div>
                        <span class="category-label">Allergy</span>
                      </a>
                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">vaccines</span>
                        </div>
                        <span class="category-label">Vitamins</span>
                      </a>
                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">healing</span>
                        </div>
                        <span class="category-label">First Aid</span>
                      </a>
                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">gastroenterology</span>
                        </div>
                        <span class="category-label">Digestive Care</span>
                      </a>
                      <a href="#" class="category-item">
                        <div class="category-icon-circle">
                          <span class="material-symbols-outlined">bedtime</span>
                        </div>
                        <span class="category-label">Sleep Care</span>
                      </a>
                    </div>
                    <button class="category-nav next" aria-label="Scroll right">
                      <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                  </div>
                </div>

                <!-- Nearby Pharmacies -->
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

                <!-- Most Searched -->
                <div class="most-searched-section">
                  <div class="section-header">
                    <h5>Most Searched Today</h5>
                    <a href="#">See all</a>
                  </div>
                  <div class="most-searched-list">
                    <div class="medicine-rank-card">
                      <div class="medicine-rank">1</div>
                      <div class="medicine-icon-box">
                        <span class="material-symbols-outlined">vaccines</span>
                      </div>
                      <div class="medicine-info">
                        <h6>Amoxicillin 500mg</h6>
                        <p>Antibiotic · 142 searches</p>
                      </div>
                    </div>
                    <div class="medicine-rank-card">
                      <div class="medicine-rank">2</div>
                      <div class="medicine-icon-box">
                        <span class="material-symbols-outlined">water_drop</span>
                      </div>
                      <div class="medicine-info">
                        <h6>Metformin 850mg</h6>
                        <p>Diabetes · 98 searches</p>
                      </div>
                    </div>
                    <div class="medicine-rank-card">
                      <div class="medicine-rank">3</div>
                      <div class="medicine-icon-box">
                        <span class="material-symbols-outlined">ecg_heart</span>
                      </div>
                      <div class="medicine-info">
                        <h6>Losartan 50mg</h6>
                        <p>Heart · 76 searches</p>
                      </div>
                    </div>
                    <div class="medicine-rank-card">
                      <div class="medicine-rank">4</div>
                      <div class="medicine-icon-box">
                        <span class="material-symbols-outlined">vaccines</span>
                      </div>
                      <div class="medicine-info">
                        <h6>Amoxicillin 500mg</h6>
                        <p>Antibiotic · 142 searches</p>
                      </div>
                    </div>
                    <div class="medicine-rank-card">
                      <div class="medicine-rank">5</div>
                      <div class="medicine-icon-box">
                        <span class="material-symbols-outlined">water_drop</span>
                      </div>
                      <div class="medicine-info">
                        <h6>Metformin 850mg</h6>
                        <p>Diabetes · 98 searches</p>
                      </div>
                    </div>
                    <div class="medicine-rank-card">
                      <div class="medicine-rank">6</div>
                      <div class="medicine-icon-box">
                        <span class="material-symbols-outlined">ecg_heart</span>
                      </div>
                      <div class="medicine-info">
                        <h6>Losartan 50mg</h6>
                        <p>Heart · 76 searches</p>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- LEFT PANEL: pharmacy detail -->
            <div class="left-panel" id="home-left-panel" style="display:none;">
              <div id="home-view-detail">
                <button class="back-btn" id="home-back-btn">
                  <span class="material-symbols-outlined">arrow_back</span>
                  Back
                </button>
                <div class="detail-header">
                  <div class="detail-pharmacy-name-row">
                    <div class="detail-icon-box">
                      <span class="material-symbols-outlined">local_pharmacy</span>
                    </div>
                    <div>
                      <h5 id="home-detail-name"></h5>
                      <div class="user-location">
                        <span class="material-symbols-outlined">location_on</span>
                        <span id="home-detail-subtitle"></span>
                      </div>
                    </div>
                  </div>
                  <img id="home-detail-img" src="" alt="" class="detail-pharmacy-img" />
                </div>
                <div class="detail-info-block">
                  <h4 id="home-detail-name-big"></h4>
                  <div class="pharmacy-distance" style="margin-bottom:6px">
                    <span class="material-symbols-outlined">location_on</span>
                    <span id="home-detail-distance"></span>
                  </div>
                  <div class="pharmacy-status" style="margin-bottom:16px">
                    <span id="home-detail-status"></span>
                  </div>
                  <div class="detail-actions">
                    <button class="direction-btn" id="home-detail-get-directions">
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
                    <span id="home-detail-address"></span>
                  </div>
                  <div class="detail-meta-item">
                    <span class="material-symbols-outlined">label</span>
                    <span>Add a label</span>
                  </div>
                </div>
                <div class="medicines-section">
                  <div class="medicines-section-header">
                    <span class="material-symbols-outlined">medication</span>
                    Available Medicines
                  </div>
                  <div id="home-medicines-list"></div>
                </div>
              </div>
            </div>

            <!-- RIGHT PANEL: Map -->
            <div class="right-panel" id="home-map-panel" style="display:none;">
              <div id="home-map" style="width:100%;height:100%;"></div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CHAT PANEL -->
  <div class="chat-panel hidden" id="chatPanel">
    <div class="chat-header">
      <div class="bot-avatar">
        <span class="material-symbols-outlined" style="font-size:1.2rem; color:#fff">smart_toy</span>
      </div>
      <div class="chat-header-info">
        <h6>MediFind Assistant</h6>
        <span>● Online now</span>
      </div>
      <button class="chat-close-btn" onclick="toggleChat()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
    <div class="chat-messages" id="chatMessages">
      <div class="msg bot">
        <div class="msg-avatar">
          <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
        </div>
        <div>
          <div class="bubble">Hi! I'm your MediFind assistant. Ask me anything about medicines, dosages, or nearby pharmacies 💊</div>
          <div class="msg-time">Just now</div>
        </div>
      </div>
    </div>
    <div class="chat-suggestions" id="chatSuggestions">
      <button class="suggest-chip" onclick="sendMessage('What is Paracetamol used for?')">Paracetamol</button>
      <button class="suggest-chip" onclick="sendMessage('Find nearby pharmacies')">Nearby pharmacies</button>
      <button class="suggest-chip" onclick="sendMessage('Amoxicillin dosage')">Amoxicillin</button>
    </div>
    <div class="chat-input-row">
      <input
        class="chat-input"
        id="chatInput"
        type="text"
        placeholder="Ask about a medicine..."
        onkeydown="if(event.key==='Enter') sendMessage()" />
      <button class="chat-send-btn" onclick="sendMessage()">
        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.269 20.876L5.999 12zm0 0h7.5" />
        </svg>
      </button>
    </div>
  </div>

  <!-- FLOATING BUTTON -->
  <div class="floating-btns">
    <button class="float-btn" id="floatBtn" onclick="toggleChat()" aria-label="Open chat assistant">
      <span class="material-symbols-outlined" id="iconBot">smart_toy</span>
      <span class="material-symbols-outlined" id="iconClose" style="display:none">close</span>
    </button>
  </div>

  <script src="../bootstrap/js/jquery.min.js"></script>
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- CHAT SCRIPT -->
  <script>
    let isOpen = false;
    const panel = document.getElementById('chatPanel');
    const chatMsgs = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const iconBot = document.getElementById('iconBot');
    const iconClose = document.getElementById('iconClose');

    function toggleChat() {
      isOpen = !isOpen;
      panel.classList.toggle('hidden', !isOpen);
      panel.classList.toggle('visible', isOpen);
      iconBot.style.display = isOpen ? 'none' : '';
      iconClose.style.display = isOpen ? '' : 'none';
      if (isOpen) setTimeout(() => chatInput.focus(), 300);
    }

    function getTime() {
      return new Date().toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
      });
    }

    function scrollBottom() {
      chatMsgs.scrollTop = chatMsgs.scrollHeight;
    }

    function addMessage(text, type) {
      const div = document.createElement('div');
      div.className = 'msg ' + type;
      div.innerHTML = type === 'bot' ?
        `<div class="msg-avatar">
             <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
           </div>
           <div><div class="bubble">${text}</div><div class="msg-time">${getTime()}</div></div>` :
        `<div><div class="bubble">${text}</div><div class="msg-time" style="text-align:right">${getTime()}</div></div>`;
      chatMsgs.appendChild(div);
      scrollBottom();
    }

    function showTyping() {
      const t = document.createElement('div');
      t.className = 'msg bot';
      t.id = 'typingIndicator';
      t.innerHTML = `<div class="msg-avatar">
          <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
        </div>
        <div class="typing-indicator">
          <div class="typing-dot"></div>
          <div class="typing-dot"></div>
          <div class="typing-dot"></div>
        </div>`;
      chatMsgs.appendChild(t);
      scrollBottom();
    }

    function removeTyping() {
      const t = document.getElementById('typingIndicator');
      if (t) t.remove();
    }

    function sendMessage(prefill) {
      const text = prefill || chatInput.value.trim();
      if (!text) return;
      addMessage(text, 'user');
      chatInput.value = '';
      document.getElementById('chatSuggestions').style.display = 'none';
      showTyping();
      setTimeout(() => {
        removeTyping();
        // TODO: replace with real API call
        addMessage('I received your message. This is a placeholder response.', 'bot');
      }, 1100);
    }
  </script>

  <!-- CATEGORIES SCRIPT -->
  <script>
    const categories = document.getElementById('quickCategories');
    const prevBtn = document.querySelector('.category-nav.prev');
    const nextBtn = document.querySelector('.category-nav.next');
    const categoryItems = document.querySelectorAll('.category-item');

    function updateNavVisibility() {
      const isOverflowing = categories.scrollWidth > categories.clientWidth;
      prevBtn.style.display = isOverflowing ? '' : 'none';
      nextBtn.style.display = isOverflowing ? '' : 'none';
    }

    prevBtn.addEventListener('click', () => categories.scrollBy({
      left: -220,
      behavior: 'smooth'
    }));
    nextBtn.addEventListener('click', () => categories.scrollBy({
      left: 220,
      behavior: 'smooth'
    }));

    updateNavVisibility();
    new ResizeObserver(updateNavVisibility).observe(categories);

    categoryItems.forEach(item => {
      item.addEventListener('click', function() {
        categoryItems.forEach(el => el.classList.remove('active'));
        this.classList.add('active');
      });
    });
  </script>

  <!-- MAP / PHARMACY DETAIL SCRIPT -->
  <script>
    let homeLeafletMap = null;

    function renderHomeMedicines(medicines) {
      const list = document.getElementById('home-medicines-list');
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

    function openHomeDirections(card) {
      const {
        name,
        address,
        distance,
        status,
        lat,
        lng,
        img
      } = card.dataset;
      const medicines = JSON.parse(card.dataset.medicines || '[]');

      document.getElementById('home-detail-name').textContent = name;
      document.getElementById('home-detail-subtitle').textContent = address;
      document.getElementById('home-detail-name-big').textContent = name;
      document.getElementById('home-detail-distance').textContent = distance;
      document.getElementById('home-detail-address').textContent = address;
      document.getElementById('home-detail-img').src = img;

      const [openPart, ...rest] = status.split('·');
      document.getElementById('home-detail-status').innerHTML =
        `<span class="open-text">${openPart.trim()}</span><span class="dot"> · </span><span>${rest.join('·').trim()}</span>`;

      document.getElementById('home-detail-get-directions').onclick = () =>
        window.open(`https://www.openstreetmap.org/directions?to=${lat},${lng}`, '_blank');

      renderHomeMedicines(medicines);

      document.getElementById('home-view-list').style.display = 'none';
      document.getElementById('home-left-panel').style.display = 'block';
      document.getElementById('home-map-panel').style.display = 'block';

      const latF = parseFloat(lat);
      const lngF = parseFloat(lng);

      setTimeout(() => {
        const icon = L.divIcon({
          className: '',
          html: `<div style="background:#1d9e75;width:30px;height:30px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.25);"></div>`,
          iconSize: [30, 30],
          iconAnchor: [15, 30],
          popupAnchor: [0, -32]
        });

        if (!homeLeafletMap) {
          homeLeafletMap = L.map('home-map').setView([latF, lngF], 16);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
          }).addTo(homeLeafletMap);
        } else {
          homeLeafletMap.setView([latF, lngF], 16);
          homeLeafletMap.eachLayer(l => {
            if (l instanceof L.Marker) homeLeafletMap.removeLayer(l);
          });
        }

        L.marker([latF, lngF], {
            icon
          })
          .addTo(homeLeafletMap)
          .bindPopup(`
            <div class="popup-inner">
              <div class="popup-top-bar"><div class="popup-name">${name}</div></div>
              <div class="popup-bottom-bar">
                <div class="popup-address">${address}</div>
                <div class="popup-dist">${distance}</div>
              </div>
            </div>`, {
            maxWidth: 260
          })
          .openPopup();

        homeLeafletMap.invalidateSize();
      }, 100);
    }

    document.getElementById('home-back-btn').addEventListener('click', () => {
      document.getElementById('home-view-list').style.display = 'block';
      document.getElementById('home-left-panel').style.display = 'none';
      document.getElementById('home-map-panel').style.display = 'none';
    });

    document.querySelectorAll('.nearby-section .direction-btn').forEach(btn => {
      btn.addEventListener('click', e => {
        e.stopPropagation();
        openHomeDirections(btn.closest('.pharmacy-card'));
      });
    });

    document.querySelectorAll('.nearby-section .pharmacy-card').forEach(card => {
      card.addEventListener('click', () => openHomeDirections(card));
    });
  </script>

</body>

</html>