<?php

// Prevent back button after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");



include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

// ── Guard: must be logged in ───────────────────────────────────────
if (empty($_SESSION['user_id'])) {
    header('Location: ../03_Authentication/login.php');
    exit;
}

$isLoggedIn = true;
$searchQuery = trim($_GET['q'] ?? '');
$medicinesJsonPath = __DIR__ . '/../database/medicines_db.json';
$medicinesData = file_exists($medicinesJsonPath) ? json_decode(file_get_contents($medicinesJsonPath), true) : ['medicines' => []];
$medicinesList = $medicinesData['medicines'] ?? [];

$pharmaciesJsonPath = __DIR__ . '/../database/pharmacies_db.json';
$pharmaciesData = file_exists($pharmaciesJsonPath) ? json_decode(file_get_contents($pharmaciesJsonPath), true) : ['pharmacies' => []];
$pharmaciesList = $pharmaciesData['pharmacies'] ?? [];
?>


<!doctype html>
<html lang="en" class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MediFind: Find Medicine</title>
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <!-- <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" /> -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="../07_Assets/node_modules/material-symbols/outlined.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/medicinemap.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    /* Fix faint white Leaflet tile grid seams */
    #map-full .leaflet-tile-container img {
      width: 256.5px !important;
      height: 256.5px !important;
    }
    .map-locate-btn {
      position: absolute;
      top: 72px;
      left: 56px;
      z-index: 520;
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: #ffffff;
      border: 1px solid #d5e2dd;
      color: #1a2e27;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
      cursor: pointer;
    }
    .map-locate-btn:hover {
      background: #eef9f4;
      color: #1d9e75;
    }
    .map-route-panel {
      display: none;
      position: absolute;
      top: 72px;
      right: 16px;
      z-index: 540;
      width: 320px;
      max-width: calc(100vw - 32px);
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(15, 23, 42, 0.14);
      padding: 12px 14px;
      border: 1px solid #e2e8f0;
      max-height: 240px;
      overflow-y: auto;
    }
    .map-route-panel.open { display: block; }
    .map-route-panel.minimized .map-route-body { display: none; }
    .map-route-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 8px;
      margin-bottom: 6px;
    }
    .map-route-panel.minimized .map-route-header { margin-bottom: 0; }
    .map-route-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #e8f7f2;
      color: #0f6e56;
      font-size: 0.76rem;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 99px;
      margin-bottom: 8px;
    }
    /* Dark slate marker popup */
    #map-full .leaflet-popup-content-wrapper {
      background: #1e293b;
      color: #ffffff;
      border-radius: 16px;
      padding: 0;
    }
    #map-full .leaflet-popup-tip { background: #1e293b; }
    #map-full .leaflet-popup-close-button { color: #cbd5e1 !important; top: 6px !important; right: 8px !important; }
    .mf-slate-popup {
      font-family: "Plus Jakarta Sans", sans-serif;
      min-width: 210px;
      padding: 12px 26px 12px 14px;
    }
    .mf-slate-popup .popup-name { font-weight: 700; font-size: 0.88rem; color: #fff; margin-bottom: 2px; }
    .mf-slate-popup .popup-addr { font-size: 0.74rem; color: #cbd5e1; margin-bottom: 8px; }
    .mf-slate-pill {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 3px 9px;
      border-radius: 99px;
      font-size: 0.72rem;
      font-weight: 600;
      background: rgba(74, 222, 128, 0.16);
      color: #86efac;
    }
    .mf-slate-pill.unavail { background: rgba(248, 113, 113, 0.18); color: #fca5a5; }
    /* Card button hierarchy */
    .pharmacy-result-card { border: 1.5px solid #e2e8f0 !important; }
    .pharmacy-result-card.selected { border-color: #1d9e75 !important; }
    .pharmacy-result-card .btn-directions {
      background: #eef9f4 !important;
      color: #0f6e56 !important;
      border: 1.5px solid #1d9e75 !important;
    }
    .pharmacy-result-card.selected .btn-directions {
      background: #ff5b27 !important;
      color: #ffffff !important;
      border-color: #ff5b27 !important;
    }
    @media (max-width: 768px) {
      .map-route-panel { top: 68px; right: 10px; left: 10px; width: auto; max-height: 170px; }
    }
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { transition: none !important; animation: none !important; }
    }
  </style>
</head>

<body data-active="02">
  <div class="wrapper">

    <?php if ($isLoggedIn): ?>
      <!-- Sidebar -->
      <div id="sidebar-container"><?php include '../01_Includes/users-sidebar-fixed.php'; ?></div>
    <?php endif; ?>

    <!-- Main panel -->
    <div class="main-panel">

      <?php if ($isLoggedIn): ?>
        <!-- Topbar -->
        <div id="topbar-container"></div>
      <?php endif; ?>

      <!-- Content -->
      <div id="content">
        <?php
        $mapBasePath = '../';
        $mapMode = 'embedded';
        $mapInitialQuery = $searchQuery !== '' ? $searchQuery : 'Paracetamol';
        $fromParam = $_GET['from'] ?? '';
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($fromParam === 'scannedrx' || strpos($referer, '02.2_ScannedRx.php') !== false) {
            $mapBackHref = '02.2_ScannedRx.php';
        } elseif ($fromParam === 'medicines' || strpos($referer, '03_Medicines.php') !== false || strpos($referer, '03.2_MedicineDetails.php') !== false) {
            $mapBackHref = '03_Medicines.php';
        } elseif ($fromParam === 'home' || strpos($referer, '01_Home.php') !== false) {
            $mapBackHref = '01_Home.php';
        } elseif ($fromParam === 'scan' || strpos($referer, '02_ScanRX.php') !== false) {
            $mapBackHref = '02_ScanRX.php';
        } else {
            $mapBackHref = '02.2_ScannedRx.php';
        }
        include '../01_Includes/medicine-map-component.php';
        ?>
      </div><!-- /#content -->
    </div><!-- /.main-panel -->
  </div><!-- /.wrapper -->

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

  <?php
  $loginModalBasePath = '../';
  include_once '../01_Includes/login-prompt-modal.php';
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <?php if ($isLoggedIn): ?>
    <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
  <?php endif; ?>
</body>

</html>