<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MediFind: Home</title>

  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />

  <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

</head>

<body>
  <div class="wrapper d-flex align-items-stretch">
    <div id="sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content">
        <div class="content-body">
          <div class="hero">
            <!-- Search Section -->
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
                  <span
                    class="material-symbols-outlined"
                    style="color: #87a199; font-size: 1.2rem">
                    search
                  </span>
                  <input
                    type="text"
                    placeholder="Search medicine by name..." />
                </div>

                <button class="search-filter-btn">
                  <span class="material-symbols-outlined">
                    discover_tune
                  </span>
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
                  <div class="pharmacy-card">
                    <img
                      src="../07_Assets/images/pharmacies/RosePharmacy.png"
                      alt="Rojon Pharmacy"
                      class="pharmacy-card-img" />

                    <div class="pharmacy-card-body">
                      <h6>Rojon Pharmacy</h6>

                      <div class="pharmacy-distance">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>0.8 km away</span>
                      </div>

                      <div class="pharmacy-status">
                        <span class="open-text">Open</span>
                        <span class="dot">·</span>
                        <span>Closes 10PM</span>
                      </div>

                      <button class="direction-btn">
                        <span class="material-symbols-outlined">north_east</span>
                        Get Directions
                      </button>
                    </div>
                  </div>

                  <div class="pharmacy-card">
                    <img
                      src="../07_Assets/images/pharmacies/RosePharmacy.png"
                      alt="Rojon Pharmacy"
                      class="pharmacy-card-img" />

                    <div class="pharmacy-card-body">
                      <h6>Rojon Pharmacy</h6>

                      <div class="pharmacy-distance">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>0.8 km away</span>
                      </div>

                      <div class="pharmacy-status">
                        <span class="open-text">Open</span>
                        <span class="dot">·</span>
                        <span>Closes 10PM</span>
                      </div>

                      <button class="direction-btn">
                        <span class="material-symbols-outlined">north_east</span>
                        Get Directions
                      </button>
                    </div>
                  </div>

                  <div class="pharmacy-card">
                    <img
                      src="../07_Assets/images/pharmacies/RosePharmacy.png"
                      alt="Rojon Pharmacy"
                      class="pharmacy-card-img" />

                    <div class="pharmacy-card-body">
                      <h6>Rojon Pharmacy</h6>

                      <div class="pharmacy-distance">
                        <span class="material-symbols-outlined">location_on</span>
                        <span>0.8 km away</span>
                      </div>

                      <div class="pharmacy-status">
                        <span class="open-text">Open</span>
                        <span class="dot">·</span>
                        <span>Closes 10PM</span>
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
          </div>

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
    </div>
  </div>

  <!-- =============================================
       CHAT PANEL
       ============================================= -->
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
      <button class="suggest-chip" onclick="sendSuggestion('What is Paracetamol used for?')">Paracetamol</button>
      <button class="suggest-chip" onclick="sendSuggestion('Find nearby pharmacies')">Nearby pharmacies</button>
      <button class="suggest-chip" onclick="sendSuggestion('Amoxicillin dosage')">Amoxicillin</button>
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

  <!-- =============================================
       FLOATING BUTTON
       ============================================= -->
  <div class="floating-btns">
    <button class="float-btn" id="floatBtn" onclick="toggleChat()" aria-label="Open chat assistant">
      <span class="material-symbols-outlined bot-icon" id="iconBot">smart_toy</span>
      <span class="material-symbols-outlined bot-icon" id="iconClose" style="display:none">close</span>
    </button>
  </div>

  <!-- =============================================
       CHAT SCRIPT
       ============================================= -->
  <script>
    let isOpen = false;
    const panel = document.getElementById('chatPanel');
    const chatMsgs = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const iconBot = document.getElementById('iconBot');
    const iconClose = document.getElementById('iconClose');

    // ── Sample bot replies (replace with real API call) ──────────────────
    const botReplies = [
      "Paracetamol is commonly used to relieve fever and mild to moderate pain such as headache, body aches, or toothache.",
      "For adults, Paracetamol 500mg is typically taken every 4–6 hours. Don't exceed 4g (8 tablets) in 24 hours.",
      "It is generally gentle on the stomach and can be taken with or without food.",
      "If you miss a dose and take it only when needed, just skip the missed one. If on a schedule, take it as soon as you remember.",
      "I can help you find which nearby pharmacy has this medicine in stock. Want me to search for you?"
    ];
    let replyIdx = 0;

    // ── Toggle open / close ──────────────────────────────────────────────
    function toggleChat() {
      isOpen = !isOpen;
      panel.classList.toggle('hidden', !isOpen);
      panel.classList.toggle('visible', isOpen);
      iconBot.style.display = isOpen ? 'none' : '';
      iconClose.style.display = isOpen ? '' : 'none';
      if (isOpen) setTimeout(() => chatInput.focus(), 300);
    }

    // ── Helpers ───────────────────────────────────────────────────────────
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

      if (type === 'bot') {
        div.innerHTML = `
    <div class="msg-avatar">
      <span class="material-symbols-outlined" style="font-size:0.95rem; color:#1d9e75">smart_toy</span>
    </div>
    <div>
      <div class="bubble">${text}</div>
      <div class="msg-time">${getTime()}</div>
    </div>`;
      } else {
        div.innerHTML = `
          <div>
            <div class="bubble">${text}</div>
            <div class="msg-time" style="text-align:right">${getTime()}</div>
          </div>`;
      }

      chatMsgs.appendChild(div);
      scrollBottom();
    }

    function showTyping() {
      const t = document.createElement('div');
      t.className = 'msg bot';
      t.id = 'typingIndicator';
      t.innerHTML = `
  <div class="msg-avatar">
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

    // ── Send message ──────────────────────────────────────────────────────
    function sendMessage() {
      const text = chatInput.value.trim();
      if (!text) return;
      addMessage(text, 'user');
      chatInput.value = '';

      // Hide suggestions after first user message
      document.getElementById('chatSuggestions').style.display = 'none';

      showTyping();
      setTimeout(() => {
        removeTyping();
        // 🔁 Replace the line below with your actual API call
        addMessage(botReplies[replyIdx % botReplies.length], 'bot');
        replyIdx++;
      }, 1100);
    }

    function sendSuggestion(text) {
      addMessage(text, 'user');
      document.getElementById('chatSuggestions').style.display = 'none';
      showTyping();
      setTimeout(() => {
        removeTyping();
        addMessage(botReplies[replyIdx % botReplies.length], 'bot');
        replyIdx++;
      }, 1100);
    }
  </script>


  <script>
    const categories = document.getElementById("quickCategories");
    const prevBtn = document.querySelector(".category-nav.prev");
    const nextBtn = document.querySelector(".category-nav.next");
    const categoryItems = document.querySelectorAll(".category-item");

    function updateNavVisibility() {
      const isOverflowing = categories.scrollWidth > categories.clientWidth;
      prevBtn.style.display = isOverflowing ? "" : "none";
      nextBtn.style.display = isOverflowing ? "" : "none";
    }

    if (prevBtn && nextBtn && categories) {
      prevBtn.addEventListener("click", () => {
        categories.scrollBy({
          left: -220,
          behavior: "smooth"
        });
      });

      nextBtn.addEventListener("click", () => {
        categories.scrollBy({
          left: 220,
          behavior: "smooth"
        });
      });

      // Run on load and whenever the container resizes
      updateNavVisibility();
      new ResizeObserver(updateNavVisibility).observe(categories);
    }

    categoryItems.forEach((item) => {
      item.addEventListener("click", function() {
        categoryItems.forEach((el) => el.classList.remove("active"));
        this.classList.add("active");
      });
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <script src="../bootstrap/js/jquery.min.js"></script>
  <script src="../bootstrap/js/popper.js"></script>
  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

</body>

  
</html>