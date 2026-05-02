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

  <!-- Page transition -->
  <?php include '01_Includes/page-transition-hardcode.php' ?>

</head>

<body class="landing-page scrollable">

  <!-- START OF NAVBAR -->
  <?php include_once '01_Includes/navbar_landing-role.php' ?>

  <!-- HERO -->
  <div class="hero mt-5 mobile-mt">

    <div class="webname">
      <div class="hero-title">Medi<span>Find</span></div>
      <div class="hero-subtitle">Malaybalay Medicine Availability Checker</div>
    </div>

    <div class="d-flex align-items-center justify-content-center gap-2">
      <div class="search-wrapper">
        <span class="material-symbols-outlined" style="color: #87a199; font-size: 1.2rem">search</span>
        <input type="text" placeholder="Search medicine by name..." />
      </div>

      <button class="search-bot-btn">
        <span class="material-symbols-outlined">smart_toy</span>
      </button>
    </div>
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

  <!-- NEARBY PHARMACIES -->
  <div class="nearby-section">
    <div class="nearby-label">
      <span class="material-symbols-outlined loc">location_on</span>
      <div class="label-text">
        <strong>Nearby Pharmacies</strong>
        <a>Malaybalay, 8700, Bukidnon, Philippines<span
            class="material-symbols-outlined dropdown-ico">arrow_drop_down</span></a>
      </div>
    </div>

    <div class="pharmacy-cards">

      <!-- Card 1: Rose Pharmacy -->
      <div class="pharmacy-card">
        <img src="07_Assets/images/pharmacies/RosePharmacy.png" alt="Rose Pharmacy" />
        <div class="card-body-custom">
          <div class="pharmacy-name">Rose Pharmacy</div>
          <div class="pharmacy-distance">
            <span class="material-symbols-outlined">location_on</span>
            <span>0.5 km away</span>
          </div>
          <div class="pharmacy-status">
            <span class="open-text">Open</span><span class="dot">·</span><span>Closes 10PM</span>
          </div>
          <div class="card-actions">
            <div class="card-action-btn" onclick="window.open('https://www.openstreetmap.org/directions?to=8.1548,125.1281','_blank')">
              <div class="card-action-icon"><span class="material-symbols-outlined">directions</span></div>
              Directions
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">near_me</span></div>
              Nearby
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">phone_iphone</span></div>
              Send to phone
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">share</span></div>
              Share
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2: Generika Drugstore -->
      <div class="pharmacy-card">
        <img src="07_Assets/images/pharmacies/Generika.png" alt="Generika Drugstore" />
        <div class="card-body-custom">
          <div class="pharmacy-name">Generika Drugstore</div>
          <div class="pharmacy-distance">
            <span class="material-symbols-outlined">location_on</span>
            <span>0.8 km away</span>
          </div>
          <div class="pharmacy-status">
            <span class="open-text">Open</span><span class="dot">·</span><span>Closes 9PM</span>
          </div>
          <div class="card-actions">
            <div class="card-action-btn" onclick="window.open('https://www.openstreetmap.org/directions?to=8.1563,125.1285','_blank')">
              <div class="card-action-icon"><span class="material-symbols-outlined">directions</span></div>
              Directions
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">near_me</span></div>
              Nearby
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">phone_iphone</span></div>
              Send to phone
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">share</span></div>
              Share
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3: Mercury Drug -->
      <div class="pharmacy-card">
        <img src="07_Assets/images/pharmacies/MercuryDrug.jpg" alt="Mercury Drug" />
        <div class="card-body-custom">
          <div class="pharmacy-name">Mercury Drug</div>
          <div class="pharmacy-distance">
            <span class="material-symbols-outlined">location_on</span>
            <span>1.1 km away</span>
          </div>
          <div class="pharmacy-status">
            <span class="open-text">Open</span><span class="dot">·</span><span>Open 24 Hours</span>
          </div>
          <div class="card-actions">
            <div class="card-action-btn" onclick="window.open('https://www.openstreetmap.org/directions?to=8.1547,125.1279','_blank')">
              <div class="card-action-icon"><span class="material-symbols-outlined">directions</span></div>
              Directions
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">near_me</span></div>
              Nearby
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">phone_iphone</span></div>
              Send to phone
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">share</span></div>
              Share
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4: Watsons -->
      <div class="pharmacy-card">
        <img src="07_Assets/images/pharmacies/Watsons.png" alt="Watsons Pharmacy" />
        <div class="card-body-custom">
          <div class="pharmacy-name">Watsons Pharmacy</div>
          <div class="pharmacy-distance">
            <span class="material-symbols-outlined">location_on</span>
            <span>1.4 km away</span>
          </div>
          <div class="pharmacy-status">
            <span class="open-text">Open</span><span class="dot">·</span><span>Closes 9PM</span>
          </div>
          <div class="card-actions">
            <div class="card-action-btn" onclick="window.open('https://www.openstreetmap.org/directions?to=8.1547,125.1275','_blank')">
              <div class="card-action-icon"><span class="material-symbols-outlined">directions</span></div>
              Directions
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">near_me</span></div>
              Nearby
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">phone_iphone</span></div>
              Send to phone
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">share</span></div>
              Share
            </div>
          </div>
        </div>
      </div>

      <!-- Card 5: Rojon Pharmacy -->
      <div class="pharmacy-card">
        <img src="07_Assets/images/pharmacies/RojonPharmacy.png" alt="Rojon Pharmacy" />
        <div class="card-body-custom">
          <div class="pharmacy-name">Rojon Pharmacy</div>
          <div class="pharmacy-distance">
            <span class="material-symbols-outlined">location_on</span>
            <span>1.7 km away</span>
          </div>
          <div class="pharmacy-status">
            <span class="open-text">Open</span><span class="dot">·</span><span>Closes 10PM</span>
          </div>
          <div class="card-actions">
            <div class="card-action-btn" onclick="window.open('https://www.openstreetmap.org/directions?to=8.1558,125.1269','_blank')">
              <div class="card-action-icon"><span class="material-symbols-outlined">directions</span></div>
              Directions
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">near_me</span></div>
              Nearby
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">phone_iphone</span></div>
              Send to phone
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">share</span></div>
              Share
            </div>
          </div>
        </div>
      </div>

      <!-- Card 6: TGP -->
      <div class="pharmacy-card">
        <img src="07_Assets/images/pharmacies/TGP.png" alt="TGP The Generics Pharmacy" />
        <div class="card-body-custom">
          <div class="pharmacy-name">TGP The Generics Pharmacy</div>
          <div class="pharmacy-distance">
            <span class="material-symbols-outlined">location_on</span>
            <span>2.0 km away</span>
          </div>
          <div class="pharmacy-status">
            <span class="open-text">Open</span><span class="dot">·</span><span>Closes 8PM</span>
          </div>
          <div class="card-actions">
            <div class="card-action-btn" onclick="window.open('https://www.openstreetmap.org/directions?to=8.1567,125.1263','_blank')">
              <div class="card-action-icon"><span class="material-symbols-outlined">directions</span></div>
              Directions
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">near_me</span></div>
              Nearby
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">phone_iphone</span></div>
              Send to phone
            </div>
            <div class="card-action-btn">
              <div class="card-action-icon"><span class="material-symbols-outlined">share</span></div>
              Share
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

  <!-- STATS SECTION -->
  <section class="stats-section">
    <div class="stats-inner">
      <div class="stat-item">
        <div class="stat-number">50+</div>
        <div class="stat-label">Pharmacies Listed</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <div class="stat-number">1,200+</div>
        <div class="stat-label">Medicines Tracked</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <div class="stat-number">5,000+</div>
        <div class="stat-label">Residents Served</div>
      </div>
      <div class="stat-divider"></div>
      <div class="stat-item">
        <div class="stat-number">24/7</div>
        <div class="stat-label">Always Available</div>
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
      <h2>Can't find your medicine?</h2>
      <p>Sign up and get notified when your medicine becomes available at a nearby pharmacy.</p>
      <div class="cta-buttons">
        <a href="03_Authentication/signup.php" class="btn-cta-primary">Create Free Account</a>
        <a href="#" class="btn-cta-secondary">Learn More</a>
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
      <p class="footer-copy">© 2026 MediFind. All rights reserved.</p>
    </div>
  </footer>

  <script>
    let landingChatOpen = false;
    const landingOverlay = document.getElementById('landingChatOverlay');
    const landingMsgs = document.getElementById('landingChatMessages');
    const landingInput = document.getElementById('landingChatInput');

    // Wire up the search bar bot button
    document.querySelector('.search-bot-btn').addEventListener('click', toggleLandingChat);

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

</body>

</html>