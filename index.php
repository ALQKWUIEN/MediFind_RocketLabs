<?php
include_once '02_Actions/GlobalVariables.php';
$isLoggedIn = !empty($_SESSION['user_id']);
$medicinesJsonPath = __DIR__ . '/database/medicines_db.json';
$medicinesData = file_exists($medicinesJsonPath) ? json_decode(file_get_contents($medicinesJsonPath), true) : ['medicines' => []];
$medicinesList = $medicinesData['medicines'] ?? [];

$pharmaciesJsonPath = __DIR__ . '/database/pharmacies_db.json';
$pharmaciesData = file_exists($pharmaciesJsonPath) ? json_decode(file_get_contents($pharmaciesJsonPath), true) : ['pharmacies' => []];
$pharmaciesList = $pharmaciesData['pharmacies'] ?? [];
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="07_Assets/images/logo.png" type="image/png" />
  <title>MediFind: Malaybalay Medicine Availability Checker</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet" />

  <!-- Assets -->
  <link href="07_Assets/css/00_Global CSS/landing-style2.css" rel="stylesheet" />
  <link href="07_Assets/css/04_Includes CSS/navbar.css" rel="stylesheet" />

  <link rel="stylesheet" href="07_Assets/node_modules/material-symbols/outlined.css" />

  <style>
    /* Hide visible vertical scrollbar while preserving scroll */
    html, body {
      scrollbar-width: none;
      -ms-overflow-style: none;
    }
    html::-webkit-scrollbar, body::-webkit-scrollbar {
      display: none;
    }

    .landing-floating-bot-btn {
      display: none;
      position: fixed;
      right: 20px;
      bottom: 20px;
      z-index: 1200;
      width: 54px;
      height: 54px;
      border-radius: 50%;
      border: none;
      background: #1d9e75;
      color: #ffffff;
      align-items: center;
      justify-content: center;
      box-shadow: 0 6px 20px rgba(29, 158, 117, 0.35);
      cursor: pointer;
      transition: transform 0.2s ease, background 0.2s ease;
    }
    .landing-floating-bot-btn:hover {
      background: #178360;
      transform: translateY(-2px);
    }
    @media (max-width: 768px) {
      .search-bot-btn {
        display: none !important;
      }
      .landing-floating-bot-btn {
        display: inline-flex;
      }
      .guest-route-panel {
        top: 10px;
        right: 10px;
        left: 10px;
        width: auto;
        max-height: 150px;
      }
      .guest-cards-row {
        grid-template-columns: repeat(3, 250px);
      }
    }
  </style>

  <!-- Page transition -->
  <?php include '01_Includes/page-transition-hardcode.php' ?>

  </head>
  <body class="landing-page">
    
    <!-- START OF NAVBAR -->
    <?php include_once '01_Includes/navbar_landing-role.php' ?>

  <!-- HERO -->
  <div class="hero mt-5 mobile-mt">

    <div class="webname">
      <div class="hero-title">Medi<span>Find</span></div>
      <div class="hero-subtitle">Malaybalay Medicine Availability Checker</div>
    </div>

    <div class="d-flex align-items-center justify-content-center gap-2">
      <div class="search-wrapper position-relative" id="landingSearchWrapper">
        <span id="landingSearchBtn" class="material-symbols-outlined" style="color: #87a199; font-size: 1.2rem; cursor: pointer;" title="Search">search</span>
        <input
          type="text"
          id="landingMedSearch"
          placeholder="Search medicine by name..."
          autocomplete="off" />
        <div id="landingSearchDropdown" style="display:none;position:absolute;top:calc(100% + 8px);left:0;right:0;background:#fff;border:1px solid #dce9e3;border-radius:14px;box-shadow:0 12px 30px rgba(0,0,0,0.1);z-index:1000;max-height:320px;overflow-y:auto;text-align:left;"></div>
      </div>

      <button class="search-bot-btn" type="button" title="Ask MediFind Assistant">
        <span class="material-symbols-outlined">smart_toy</span>
      </button>
    </div>

    <!-- Mobile floating bot button (matches users page) -->
    <button class="landing-floating-bot-btn" id="landingFloatingBotBtn" type="button" aria-label="Open MediFind Assistant" title="Ask MediFind Assistant">
      <span class="material-symbols-outlined" style="font-size:1.45rem;">smart_toy</span>
    </button>
  </div>

  <!-- CHAT MODAL OVERLAY -->
  <div class="landing-chat-overlay" id="landingChatOverlay" onclick="closeLandingChat(event)">
    <div class="landing-chat-modal" id="landingChatModal">
      <div class="landing-chat-header">
        <div class="landing-bot-avatar">
          <span class="material-symbols-outlined" style="font-size:1.2rem; color:#fff">smart_toy</span>
        </div>
        <div class="landing-chat-header-info">
          <h6>MediFind Assistant</h6>
          <span>&#9679; Online now</span>
        </div>
        <button class="landing-chat-close-btn" onclick="toggleLandingChat()">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div class="landing-chat-messages" id="landingChatMessages">
        <div class="landing-msg bot">
          <div class="landing-msg-avatar">
            <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
          </div>
          <div>
            <div class="landing-bubble">Hi! I'm your MediFind assistant. Ask me anything about medicines or nearby pharmacies in Malaybalay 💊</div>
            <div class="landing-msg-time">Just now</div>
          </div>
        </div>
      </div>

      <div class="landing-chat-suggestions" id="landingChatSuggestions">
        <button class="landing-suggest-chip" onclick="landingSendMessage('What is Paracetamol used for?')">Paracetamol</button>
        <button class="landing-suggest-chip" onclick="landingSendMessage('Find nearby pharmacies')">Nearby pharmacies</button>
        <button class="landing-suggest-chip" onclick="landingSendMessage('Amoxicillin dosage')">Amoxicillin</button>
        <button class="landing-suggest-chip" onclick="landingSendMessage('What medicines treat fever?')">Fever medicines</button>
      </div>

      <div class="landing-chat-input-row">
        <input
          class="landing-chat-input"
          id="landingChatInput"
          type="text"
          placeholder="Ask about a medicine..."
          onkeydown="if(event.key==='Enter') landingSendMessage()" />
        <button class="landing-chat-send-btn" onclick="landingSendMessage()">
          <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.269 20.876L5.999 12zm0 0h7.5" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- PARTNERED PHARMACIES -->
  <div class="nearby-section">
    <div class="nearby-label">
      <span class="material-symbols-outlined loc">location_on</span>
      <div class="label-text">
        <strong>Participating Local Pharmacies</strong>
        <a>Malaybalay City, 8700, Bukidnon, Philippines</a>
      </div>
    </div>

    <div class="container px-0" style="max-width: 1140px;">
      <div class="row row-cols-1 row-cols-md-3 g-4">

        <!-- Card 1: Malaybalay Community Pharmacy -->
        <div class="col">
          <div class="pharmacy-card h-100 w-100" data-lat="8.1548" data-lng="125.1281">
            <img src="07_Assets/images/pharmacies/RosePharmacy.png" alt="Malaybalay Community Pharmacy" />
            <div class="card-body-custom">
              <div class="pharmacy-name">Malaybalay Community Pharmacy</div>
              <div class="pharmacy-distance">
                <span class="material-symbols-outlined">location_on</span>
                <span class="geo-distance">Fortich St., Brgy. 8</span>
              </div>
              <div class="pharmacy-status d-flex align-items-center gap-2 flex-wrap">
                <span class="badge rounded-pill d-inline-flex align-items-center gap-1" style="background:#e8f7f2;color:#0f6e56;font-weight:600;">
                  <span class="material-symbols-outlined" style="font-size:0.9rem;">check_circle</span>
                  Open
                </span>
                <span>Closes 9:00 PM</span>
              </div>
              <div class="card-actions">
                <div class="card-action-btn" role="button" tabindex="0" onclick="handleDirections(8.1548, 125.1281, 'Malaybalay Community Pharmacy')">
                  <div class="card-action-icon"><span class="material-symbols-outlined">directions</span></div>
                  Directions
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 2: Bukidnon Care Pharmacy -->
        <div class="col">
          <div class="pharmacy-card h-100 w-100" data-lat="8.1563" data-lng="125.1285">
            <img src="07_Assets/images/pharmacies/Generika.png" alt="Bukidnon Care Pharmacy" />
            <div class="card-body-custom">
              <div class="pharmacy-name">Bukidnon Care Pharmacy</div>
              <div class="pharmacy-distance">
                <span class="material-symbols-outlined">location_on</span>
                <span class="geo-distance">Sayre Hwy, Casisang</span>
              </div>
              <div class="pharmacy-status d-flex align-items-center gap-2 flex-wrap">
                <span class="badge rounded-pill d-inline-flex align-items-center gap-1" style="background:#e8f7f2;color:#0f6e56;font-weight:600;">
                  <span class="material-symbols-outlined" style="font-size:0.9rem;">check_circle</span>
                  Open
                </span>
                <span>Closes 8:00 PM</span>
              </div>
              <div class="card-actions">
                <div class="card-action-btn" role="button" tabindex="0" onclick="handleDirections(8.1563, 125.1285, 'Bukidnon Care Pharmacy')">
                  <div class="card-action-icon"><span class="material-symbols-outlined">directions</span></div>
                  Directions
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 3: Poblacion Healthmart Pharmacy -->
        <div class="col">
          <div class="pharmacy-card h-100 w-100" data-lat="8.1558" data-lng="125.1269">
            <img src="07_Assets/images/pharmacies/RojonPharmacy.png" alt="Poblacion Healthmart Pharmacy" />
            <div class="card-body-custom">
              <div class="pharmacy-name">Poblacion Healthmart Pharmacy</div>
              <div class="pharmacy-distance">
                <span class="material-symbols-outlined">location_on</span>
                <span class="geo-distance">Bonifacio St., Poblacion</span>
              </div>
              <div class="pharmacy-status d-flex align-items-center gap-2 flex-wrap">
                <span class="badge rounded-pill d-inline-flex align-items-center gap-1" style="background:#fef2f2;color:#b91c1c;font-weight:600;">
                  <span class="material-symbols-outlined" style="font-size:0.9rem;">schedule</span>
                  Closed
                </span>
                <span>Opens 8:00 AM</span>
              </div>
              <div class="card-actions">
                <div class="card-action-btn" role="button" tabindex="0" onclick="handleDirections(8.1558, 125.1269, 'Poblacion Healthmart Pharmacy')">
                  <div class="card-action-icon"><span class="material-symbols-outlined">directions</span></div>
                  Directions
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- HOW IT WORKS SECTION -->
  <section class="how-it-works-section">
    <div class="how-it-works-inner">
      <div class="section-label">Simple. Fast. Reliable.</div>
      <h2 class="section-title">How MediFind Works</h2>
      <div class="steps-grid">
        <div class="step-card">
          <div class="step-icon">
            <span class="material-symbols-outlined">search</span>
          </div>
          <div class="step-number">01</div>
          <h3>Search a Medicine</h3>
          <p>Type the name of any medicine and instantly see which pharmacies in Malaybalay have it in stock.</p>
        </div>
        <div class="step-card">
          <div class="step-icon">
            <span class="material-symbols-outlined">location_on</span>
          </div>
          <div class="step-number">02</div>
          <h3>Find Nearby Pharmacies</h3>
          <p>Browse pharmacies near you, check their hours, and get directions — all in one place.</p>
        </div>
        <div class="step-card">
          <div class="step-icon">
            <span class="material-symbols-outlined">document_scanner</span>
          </div>
          <div class="step-number">03</div>
          <h3>Scan Your Prescription</h3>
          <p>Use Scan RX to photograph your prescription and we'll identify and locate every medicine on it.</p>
        </div>
        <div class="step-card">
          <div class="step-icon">
            <span class="material-symbols-outlined">check_circle</span>
          </div>
          <div class="step-number">04</div>
          <h3>Get Your Medicine</h3>
          <p>Head straight to the right pharmacy knowing your medicine is available. No more wasted trips.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- STATS / PLATFORM HIGHLIGHTS SECTION -->
  <section class="stats-section">
    <div class="stats-inner">
      <div class="stat-item">
        <div class="stat-number" style="font-size:1.55rem;">FDA-Verified</div>
        <div class="stat-label">Philippine FDA Drug Registry</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <div class="stat-number" style="font-size:1.55rem;">OCR + LLM</div>
        <div class="stat-label">Prescription Scanning</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <div class="stat-number" style="font-size:1.55rem;">Multilingual AI</div>
        <div class="stat-label">English, Tagalog &amp; Cebuano</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <div class="stat-number" style="font-size:1.55rem;">24/7 Access</div>
        <div class="stat-label">Mobile &amp; Web PWA</div>
      </div>
    </div>
  </section>

  <!-- FEATURES SECTION -->
  <section class="features-section">
    <div class="features-inner">
      <div class="features-text">
        <div class="section-label">Why Choose Us</div>
        <h2 class="section-title">Everything You Need,<br />Right Here</h2>
        <p class="features-desc">MediFind is built for the people of Malaybalay — a free, easy-to-use tool that saves you time and stress when finding medicines.</p>
        <a href="03_Authentication/login.php" class="btn-get-started">
          Get Started Free
          <span class="material-symbols-outlined">arrow_forward</span>
        </a>
      </div>
      <div class="features-list">
        <div class="feature-item">
          <div class="feature-icon">
            <span class="material-symbols-outlined">bolt</span>
          </div>
          <div class="feature-content">
            <h4>Real-Time Availability</h4>
            <p>Pharmacies update their stock so you always see accurate, current information.</p>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <span class="material-symbols-outlined">smart_toy</span>
          </div>
          <div class="feature-content">
            <h4>AI-Powered Search</h4>
            <p>Our smart search understands generic names, brand names, and even partial spelling.</p>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <span class="material-symbols-outlined">directions</span>
          </div>
          <div class="feature-content">
            <h4>Built-In Directions</h4>
            <p>Get turn-by-turn directions to any pharmacy directly from the app.</p>
          </div>
        </div>
        <div class="feature-item">
          <div class="feature-icon">
            <span class="material-symbols-outlined">lock</span>
          </div>
          <div class="feature-content">
            <h4>Private & Secure</h4>
            <p>Your search history and personal data are protected and never shared.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA SECTION -->
  <section class="cta-section">
    <div class="cta-inner">
      <div class="cta-icon">
        <span class="material-symbols-outlined">medication</span>
      </div>
      <h2>Ready to find your medicine?</h2>
      <p>Create a free account to scan prescriptions, check local availability, and get directions to participating pharmacies.</p>
      <div class="cta-buttons">
        <a href="03_Authentication/signup.php" class="btn-cta-primary">Create Free Account</a>
        <a href="about.php" class="btn-cta-secondary">Learn More</a>
      </div>
    </div>
  </section>

  <!-- END OF MAIN CONTENT -->

  <!-- ================================================================================================== -->

  <!-- FOOTER -->

  <footer class="site-footer">
    <div class="footer-content">
      <p class="footer-brand">MediFind</p>
      <p class="footer-text">Malaybalay Medicine Availability Checker</p>
      <p class="footer-text" style="max-width:780px;margin:8px auto;font-size:0.82rem;opacity:0.9;">
        MediFind is for informational purposes only. It does not provide medical diagnosis, treatment advice, or approve medicine substitutions. Always consult a licensed physician or pharmacist.
      </p>
      <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap my-2" style="font-size:0.85rem;">
        <a href="#" class="text-decoration-none" style="color:#1d9e75;font-weight:600;" data-bs-toggle="modal" data-bs-target="#privacyNoticeModal">
          Privacy Notice (RA 10173)
        </a>
        <span>·</span>
        <a href="about.php" class="text-decoration-none text-muted">About Us</a>
        <span>·</span>
        <a href="faqs.php" class="text-decoration-none text-muted">FAQs</a>
      </div>
      <p class="footer-copy">© 2026 MediFind. All rights reserved.</p>
    </div>
  </footer>

  <!-- PRIVACY NOTICE MODAL (RA 10173) -->
  <div class="modal fade" id="privacyNoticeModal" tabindex="-1" aria-labelledby="privacyNoticeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius:18px;border:none;box-shadow:0 20px 50px rgba(0,0,0,0.2);">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold" id="privacyNoticeLabel" style="color:#1a2e27;">
            <span class="material-symbols-outlined align-middle me-1" style="color:#1d9e75;">verified_user</span>
            Privacy Notice (RA 10173)
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-start" style="font-size:0.9rem;color:#4a635a;line-height:1.6;">
          <p class="mb-2">
            MediFind complies with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong> to protect your personal and health-related information:
          </p>
          <ul class="mb-0 ps-3">
            <li class="mb-2"><strong>Minimal Data Collection:</strong> We only collect essential account details required to provide pharmacy routing and prescription lookup features.</li>
            <li class="mb-2"><strong>Immediate Image Deletion:</strong> Uploaded prescription images are processed for OCR extraction and deleted immediately after scanning—they are never permanently stored.</li>
            <li><strong>Anonymized Text Processing:</strong> Medicine queries and extracted prescription text are processed in anonymized form without linking sensitive health data to third parties.</li>
          </ul>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn rounded-pill px-4" data-bs-dismiss="modal" style="background:#1d9e75;color:#fff;font-weight:600;">I Understand</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let landingChatOpen = false;
    const landingOverlay = document.getElementById('landingChatOverlay');
    const landingMsgs = document.getElementById('landingChatMessages');
    const landingInput = document.getElementById('landingChatInput');

    // Wire up the search bar bot button & mobile floating bot button
    document.querySelector('.search-bot-btn').addEventListener('click', toggleLandingChat);
    const floatingBotBtn = document.getElementById('landingFloatingBotBtn');
    if (floatingBotBtn) floatingBotBtn.addEventListener('click', toggleLandingChat);

    // Close on overlay click (outside modal)
    function closeLandingChat(e) {
      if (e.target === landingOverlay) toggleLandingChat();
    }

    // Close on Escape key
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && landingChatOpen) toggleLandingChat();
    });

    function toggleLandingChat() {
      landingChatOpen = !landingChatOpen;
      landingOverlay.classList.toggle('visible', landingChatOpen);
      if (landingChatOpen) setTimeout(() => landingInput.focus(), 300);
    }

    function landingGetTime() {
      return new Date().toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
      });
    }

    function landingScrollBottom() {
      landingMsgs.scrollTop = landingMsgs.scrollHeight;
    }

    function landingAddMessage(text, type) {
      const div = document.createElement('div');
      div.className = 'landing-msg ' + type;
      div.innerHTML = type === 'bot' ?
        `<div class="landing-msg-avatar">
           <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
         </div>
         <div><div class="landing-bubble">${text}</div><div class="landing-msg-time">${landingGetTime()}</div></div>` :
        `<div><div class="landing-bubble">${text}</div><div class="landing-msg-time" style="text-align:right">${landingGetTime()}</div></div>`;
      landingMsgs.appendChild(div);
      landingScrollBottom();
    }

    function landingShowTyping() {
      const t = document.createElement('div');
      t.className = 'landing-msg bot';
      t.id = 'landingTyping';
      t.innerHTML = `<div class="landing-msg-avatar">
        <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
      </div>
      <div class="landing-typing-indicator">
        <div class="landing-typing-dot"></div>
        <div class="landing-typing-dot"></div>
        <div class="landing-typing-dot"></div>
      </div>`;
      landingMsgs.appendChild(t);
      landingScrollBottom();
    }

    function landingRemoveTyping() {
      const t = document.getElementById('landingTyping');
      if (t) t.remove();
    }

    function landingSendMessage(prefill) {
      const text = prefill || landingInput.value.trim();
      if (!text) return;
      landingAddMessage(text, 'user');
      landingInput.value = '';
      document.getElementById('landingChatSuggestions').style.display = 'none';
      landingShowTyping();
      setTimeout(() => {
        landingRemoveTyping();
        // TODO: replace with real API call
        landingAddMessage('I received your message. This is a placeholder response.', 'bot');
      }, 1100);
    }
  </script>

  <script>
    // Tab switching
    document.querySelectorAll(".card-tab").forEach((tab) => {
      tab.addEventListener("click", () => {
        const siblings = tab.parentElement.querySelectorAll(".card-tab");
        siblings.forEach((t) => t.classList.remove("active"));
        tab.classList.add("active");
      });
    });
  </script>

  <script>
    window.addEventListener('scroll', () => {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 10) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  </script>

  <?php
  $mapBasePath = '';
  $mapMode = 'overlay';
  $mapInitialQuery = '';
  include_once '01_Includes/medicine-map-component.php';

  $loginModalBasePath = '';
  include_once '01_Includes/login-prompt-modal.php';
  ?>

  <script>
    const LANDING_IS_LOGGED_IN = <?= json_encode($isLoggedIn) ?>;
    const LANDING_MEDICINES = <?= json_encode($medicinesList, JSON_UNESCAPED_SLASHES) ?>;

    function handleDirections(lat, lng, pharmacyName) {
      if (!LANDING_IS_LOGGED_IN) {
        openLoginPromptModal(pharmacyName);
        return;
      }
      window.open(`https://www.openstreetmap.org/directions?to=${lat},${lng}`, '_blank');
    }

    (function () {
      const searchInput = document.getElementById('landingMedSearch');
      const searchBtn = document.getElementById('landingSearchBtn');
      const dropdown = document.getElementById('landingSearchDropdown');
      const wrapper = document.getElementById('landingSearchWrapper');

      function goToMap(query) {
        const trimmed = (query || searchInput.value || '').trim();
        if (!trimmed) {
          searchInput.focus();
          return;
        }
        dropdown.style.display = 'none';
        if (window.openMediFindMap) {
          window.openMediFindMap(trimmed);
        }
      }

      function renderDropdown(term) {
        const q = term.trim().toLowerCase();
        const matches = q
          ? LANDING_MEDICINES.filter(m =>
              m.brand_name.toLowerCase().includes(q) ||
              m.generic_name.toLowerCase().includes(q) ||
              m.category.toLowerCase().includes(q)
            )
          : LANDING_MEDICINES.slice(0, 6);

        if (!matches.length) {
          dropdown.innerHTML = `<div style="padding:12px 16px;font-size:0.86rem;color:#6b8f82;">No matching medicines in local catalog. Press Enter to search anyway.</div>`;
          dropdown.style.display = 'block';
          return;
        }

        dropdown.innerHTML = matches.map(m => `
          <div class="landing-med-option" data-name="${m.brand_name}" style="display:flex;align-items:center;gap:12px;padding:10px 14px;cursor:pointer;border-bottom:1px solid #f1f5f4;">
            <img src="${m.image}" alt="${m.brand_name}" style="width:36px;height:36px;object-fit:contain;border-radius:8px;background:#f8fbfa;padding:3px;border:1px solid #e6efec;">
            <div style="flex:1;min-width:0;">
              <div style="font-weight:600;font-size:0.9rem;color:#1a2e27;">${m.brand_name} <span style="font-weight:400;color:#6b8f82;">(${m.dosage})</span></div>
              <div style="font-size:0.78rem;color:#6b8f82;">${m.generic_name} · ${m.category}</div>
            </div>
            <span style="font-size:0.72rem;font-weight:600;padding:3px 8px;border-radius:99px;background:${m.requires_prescription ? '#fff3cd' : '#e8f7f2'};color:${m.requires_prescription ? '#9a6700' : '#0f6e56'};">
              ${m.requires_prescription ? 'Rx' : 'OTC'}
            </span>
          </div>
        `).join('');

        dropdown.style.display = 'block';
        dropdown.querySelectorAll('.landing-med-option').forEach(opt => {
          opt.addEventListener('mouseenter', () => opt.style.background = '#f4fbf8');
          opt.addEventListener('mouseleave', () => opt.style.background = '#fff');
          opt.addEventListener('click', () => goToMap(opt.dataset.name));
        });
      }

      searchInput.addEventListener('focus', () => renderDropdown(searchInput.value));
      searchInput.addEventListener('input', () => renderDropdown(searchInput.value));
      searchInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
          e.preventDefault();
          goToMap();
        }
      });
      searchBtn.addEventListener('click', () => goToMap());

      document.addEventListener('click', e => {
        if (!wrapper.contains(e.target)) {
          dropdown.style.display = 'none';
        }
      });
    })();

    // Update pharmacy card distances on landing page if geolocation is permitted
    if ('geolocation' in navigator) {
      navigator.geolocation.getCurrentPosition(pos => {
        const toRad = d => d * Math.PI / 180;
        const uLat = pos.coords.latitude;
        const uLng = pos.coords.longitude;
        document.querySelectorAll('.pharmacy-card[data-lat][data-lng]').forEach(card => {
          const pLat = parseFloat(card.dataset.lat);
          const pLng = parseFloat(card.dataset.lng);
          const dLat = toRad(pLat - uLat);
          const dLng = toRad(pLng - uLng);
          const a = Math.sin(dLat / 2) ** 2 + Math.cos(toRad(uLat)) * Math.cos(toRad(pLat)) * Math.sin(dLng / 2) ** 2;
          const km = 6371 * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
          const el = card.querySelector('.geo-distance');
          if (el) el.textContent = `${km.toFixed(1)} km away`;
        });
      });
    }
  </script>

</body>

</html>