<?php

// Prevent back button after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");



include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

// ── Guard: must be logged in ───────────────────────────────────────
if (!$_SESSION['user_id']) {
    header('Location: ../03_Authentication/login.php');
    exit;
}
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Medicine Details – MediFind</title>

  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <!-- <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" /> -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link
    href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="../07_Assets/node_modules/material-symbols/outlined.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/medicinedetails.css" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

</head>

<body data-active="03">
  <div class="wrapper d-flex align-items-stretch">
    <div id="sidebar-container"><?php include '../01_Includes/users-sidebar-fixed.php'; ?></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content">
        <div class="content-body">
          <!-- Back button -->
          <a class="back-btn" onclick="history.back()" href="#">
            <span class="material-symbols-outlined">arrow_back</span>
            Back
          </a>

          <!-- ── Hero card ── -->
          <div class="hero-card">
            <div class="hero-inner">
              <div class="medicine-img-wrap">
                <img
                  src="../07_Assets/images/medicines/Ibuprofen.png"
                  alt="Ibuprofen 200mg" />
              </div>
              <div class="hero-info">
                <div class="badge-row">
                  <span class="medicine-tag no-rx">No Rx</span>
                  <span class="medicine-tag category">Analgesic</span>
                  <span class="medicine-tag form">Tablet</span>

                </div>
                <h1 class="medicine-title">Ibuprofen 200mg</h1>
                <p class="medicine-subtitle">
                  Advil &nbsp;·&nbsp; Manufactured by Unilab
                </p>
                <div class="price-row">
                  <span class="price-main">₱2.75</span>
                  <span class="price-unit">/ tablet</span>
                </div>
                <p class="price-range-text">
                  Price range: ₱2.75 – ₱5.00 across pharmacies
                </p>
              </div>
            </div>

            <hr class="hero-divider" />

            <!-- Quick info strip -->
            <div class="info-grid">
              <div class="info-cell">
                <p class="info-cell-label">Generic name</p>
                <p class="info-cell-value">Ibuprofen</p>
              </div>
              <div class="info-cell">
                <p class="info-cell-label">Dosage</p>
                <p class="info-cell-value">200 mg</p>
              </div>
              <div class="info-cell">
                <p class="info-cell-label">Form</p>
                <p class="info-cell-value">Softgel Capsule</p>
              </div>
              <div class="info-cell">
                <p class="info-cell-label">Best price</p>
                <p class="info-cell-value accent">₱2.75 / tab</p>
              </div>
            </div>
          </div>

          <!-- ── Rx notice ── -->
          <div class="rx-notice">
            <span class="material-symbols-outlined">info</span>
            <p>
              This medicine does not require a prescription. Over-the-counter
              purchase only.
            </p>
          </div>

          <!-- ── Pharmacy availability ── -->
          <div class="section-head">
            <h2 class="section-title">
              Nearby pharmacies
              <span class="count-pill">9 available</span>
            </h2>
            <button class="sort-btn">
              <span class="material-symbols-outlined">swap_vert</span>
              Sort by price
            </button>
          </div>

          <div class="pharmacy-list">
            <!-- Rose Pharmacy (best) -->
            <div class="pharmacy-card is-best">
              <div
                class="pharmacy-logo"
                style="background: #fde8e8; color: #c0392b">
                R
              </div>
              <div class="pharmacy-body">
                <div class="pharmacy-name-row">
                  <p class="pharmacy-name">Rose Pharmacy</p>
                  <span class="best-badge">Lowest price</span>
                </div>
                <div class="pharmacy-meta">
                  <span><span class="material-symbols-outlined">schedule</span><span class="open">Open · closes 10PM</span></span>
                  <span><span class="material-symbols-outlined">near_me</span>0.8
                    km away</span>
                </div>
                <button class="directions-btn">
                  <span class="material-symbols-outlined">directions</span>
                  Get directions
                </button>
              </div>
              <div class="pharmacy-price-col">
                <p class="ph-price">₱2.75</p>
                <p class="ph-price-unit">per tablet</p>
              </div>
            </div>

            <!-- Mercury Drug -->
            <div class="pharmacy-card">
              <div
                class="pharmacy-logo"
                style="background: #e8f0fe; color: #1a56a8">
                M
              </div>
              <div class="pharmacy-body">
                <div class="pharmacy-name-row">
                  <p class="pharmacy-name">Mercury Drug</p>
                </div>
                <div class="pharmacy-meta">
                  <span><span class="material-symbols-outlined">schedule</span><span class="open">Open · closes 11PM</span></span>
                  <span><span class="material-symbols-outlined">near_me</span>1.2
                    km away</span>
                </div>
                <button class="directions-btn">
                  <span class="material-symbols-outlined">directions</span>
                  Get directions
                </button>
              </div>
              <div class="pharmacy-price-col">
                <p class="ph-price">₱3.25</p>
                <p class="ph-price-unit">per tablet</p>
              </div>
            </div>

            <!-- Generika -->
            <div class="pharmacy-card">
              <div
                class="pharmacy-logo"
                style="background: #e8f6ee; color: #1a7a4a">
                G
              </div>
              <div class="pharmacy-body">
                <div class="pharmacy-name-row">
                  <p class="pharmacy-name">Generika</p>
                </div>
                <div class="pharmacy-meta">
                  <span><span class="material-symbols-outlined">schedule</span><span class="open">Open · closes 9PM</span></span>
                  <span><span class="material-symbols-outlined">near_me</span>1.5
                    km away</span>
                </div>
                <button class="directions-btn">
                  <span class="material-symbols-outlined">directions</span>
                  Get directions
                </button>
              </div>
              <div class="pharmacy-price-col">
                <p class="ph-price">₱3.50</p>
                <p class="ph-price-unit">per tablet</p>
              </div>
            </div>

            <!-- Watsons (closed) -->
            <div class="pharmacy-card is-closed">
              <div
                class="pharmacy-logo"
                style="background: #fff3e0; color: #b45309">
                W
              </div>
              <div class="pharmacy-body">
                <div class="pharmacy-name-row">
                  <p class="pharmacy-name">Watsons</p>
                </div>
                <div class="pharmacy-meta">
                  <span><span class="material-symbols-outlined">schedule</span><span class="closed">Closed · opens 9AM</span></span>
                  <span><span class="material-symbols-outlined">near_me</span>2.1
                    km away</span>
                </div>
              </div>
              <div class="pharmacy-price-col">
                <p class="ph-price muted">₱5.00</p>
                <p class="ph-price-unit">per tablet</p>
              </div>
            </div>
          </div>
          <!-- /pharmacy-list -->
        </div>
        <!-- /content-body -->

        <!-- ── Sticky footer ── -->
        <div class="sticky-footer">
          <button class="btn-back-footer" onclick="history.back()">
            <span class="material-symbols-outlined">arrow_back</span>
            Back
          </button>
          <button class="btn-cta">
            <span class="material-symbols-outlined">location_on</span>
            Find nearest pharmacy
          </button>
        </div>
      </div>
      <!-- /content -->
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
    document.querySelectorAll('.directions-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const medTitle = (document.querySelector('.medicine-title')?.textContent || 'Ibuprofen').split(' ')[0];
        window.location.href = `02.3_MedicineMap.php?q=${encodeURIComponent(medTitle)}&from=medicines`;
      });
    });
  </script>
  <script src="../07_Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

</body>

</html>