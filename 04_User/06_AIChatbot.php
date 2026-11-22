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
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
  <title>MediFind: AI Chatbot</title>
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />

  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    * {
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      background: #ffffff;
      font-family: "Plus Jakarta Sans", "Poppins", sans-serif;
      color: #1f2937;
    }

    .chat-page {
      display: flex;
      flex-direction: column;
      height: 100dvh;
      max-width: 720px;
      margin: 0 auto;
      background: #ffffff;
    }

    /* Sticky Top Header */
    .chat-page-header {
      display: grid;
      grid-template-columns: 44px 1fr 44px;
      align-items: center;
      padding: 12px 14px;
      background: #ffffff;
      border-bottom: 1px solid #f1f5f9;
      flex-shrink: 0;
      z-index: 10;
    }

    .chat-back-btn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: none;
      background: transparent;
      color: #1f2937;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background 0.16s ease, transform 0.14s ease;
    }

    .chat-back-btn:hover {
      background: #f3f4f6;
      transform: translateX(-1px);
    }

    .chat-page-title {
      text-align: center;
      font-size: 1.08rem;
      font-weight: 700;
      color: #1d9e75;
      margin: 0;
      letter-spacing: -0.01em;
    }

    /* Scrollable Thread */
    .chat-thread {
      flex: 1;
      overflow-y: auto;
      padding: 16px 14px 12px;
      display: flex;
      flex-direction: column;
      gap: 14px;
      scroll-behavior: smooth;
    }

    .chat-intro-profile {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      padding: 12px 18px 8px;
      gap: 6px;
    }

    .chat-intro-avatar {
      width: 74px;
      height: 74px;
      border-radius: 50%;
      background: #eef8f4;
      border: 2px solid #cde8dd;
      box-shadow: 0 6px 18px rgba(29, 158, 117, 0.12);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 4px;
    }

    .chat-intro-name {
      font-size: 1.05rem;
      font-weight: 700;
      color: #1a2e27;
      margin: 0;
    }

    .chat-intro-note {
      font-size: 0.75rem;
      color: #6b7280;
      line-height: 1.4;
      max-width: 290px;
      margin: 0;
    }

    .chat-intro-time {
      font-size: 0.72rem;
      color: #9ca3af;
      margin-top: 6px;
    }

    .chat-row {
      display: flex;
      flex-direction: column;
      gap: 4px;
      animation: msgFadeIn 0.22s cubic-bezier(0.22, 1, 0.36, 1);
    }

    @keyframes msgFadeIn {
      from {
        opacity: 0;
        transform: translateY(6px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .chat-row-inner {
      display: flex;
      align-items: flex-start;
      gap: 10px;
    }

    .chat-row.user .chat-row-inner {
      justify-content: flex-end;
    }

    .chat-row.bot .chat-row-inner {
      justify-content: flex-start;
    }

    .chat-avatar {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      flex-shrink: 0;
      object-fit: cover;
      border: 1.5px solid #e5ece9;
      background: #ffffff;
    }

    .chat-bot-avatar {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      flex-shrink: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #ffffff;
      border: 1.5px solid #e5ece9;
      box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
    }

    .chat-bubble {
      max-width: 76%;
      padding: 11px 15px;
      border-radius: 14px;
      font-size: 0.9rem;
      line-height: 1.48;
      word-wrap: break-word;
    }

    .chat-row.user .chat-bubble {
      background: #f1f3f5;
      color: #1f2937;
      border-top-right-radius: 4px;
    }

    .chat-row.bot .chat-bubble {
      background: #1d9e75;
      color: #ffffff;
      border-top-left-radius: 4px;
      box-shadow: 0 3px 10px rgba(29, 158, 117, 0.16);
    }

    .chat-time {
      font-size: 0.72rem;
      color: #9ca3af;
    }

    .chat-row.user .chat-time {
      text-align: right;
      padding-right: 52px;
    }

    .chat-row.bot .chat-time {
      text-align: left;
      padding-left: 52px;
    }

    /* Typing Indicator */
    .typing-dots {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 4px 2px;
    }

    .typing-dots span {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.85);
      animation: typingBounce 1.1s infinite ease-in-out;
    }

    .typing-dots span:nth-child(2) {
      animation-delay: 0.15s;
    }

    .typing-dots span:nth-child(3) {
      animation-delay: 0.3s;
    }

    @keyframes typingBounce {
      0%, 80%, 100% {
        transform: scale(0.75);
        opacity: 0.55;
      }
      40% {
        transform: scale(1);
        opacity: 1;
      }
    }

    /* Quick Suggestion Chips */
    .chat-suggestions-wrap {
      max-height: 0;
      opacity: 0;
      overflow: hidden;
      transition: max-height 0.26s ease, opacity 0.2s ease, padding 0.2s ease;
      padding: 0 14px;
      background: #ffffff;
    }

    .chat-suggestions-wrap.is-open {
      max-height: 90px;
      opacity: 1;
      padding: 6px 14px 8px;
    }

    .chat-suggestions-list {
      display: flex;
      gap: 8px;
      overflow-x: auto;
      scrollbar-width: none;
    }

    .chat-suggestions-list::-webkit-scrollbar {
      display: none;
    }

    .suggest-pill {
      white-space: nowrap;
      border: 1px solid #d1e7df;
      background: #eef8f4;
      color: #0f6e56;
      font-size: 0.78rem;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 999px;
      cursor: pointer;
      transition: background 0.16s ease, transform 0.12s ease;
    }

    .suggest-pill:hover {
      background: #dcf2ea;
      transform: translateY(-1px);
    }

    /* Bottom Composer */
    .chat-composer-wrap {
      padding: 8px 14px calc(12px + env(safe-area-inset-bottom, 0px));
      background: #ffffff;
      border-top: 1px solid #f1f5f9;
      flex-shrink: 0;
    }

    .chat-composer {
      display: flex;
      align-items: center;
      gap: 10px;
      background: #f1f3f5;
      border-radius: 999px;
      padding: 6px 8px 6px 8px;
    }

    .composer-plus-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: none;
      background: #9ca3af;
      color: #ffffff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      cursor: pointer;
      transition: background 0.18s ease, transform 0.18s ease;
    }

    .composer-plus-btn.is-active {
      background: #1d9e75;
      transform: rotate(45deg);
    }

    .composer-input {
      flex: 1;
      border: none;
      background: transparent;
      outline: none;
      font-size: 0.94rem;
      color: #1f2937;
      padding: 6px 2px;
    }

    .composer-input::placeholder {
      color: #9ca3af;
    }

    .composer-send-btn {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: none;
      background: #1d9e75;
      color: #ffffff;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      cursor: pointer;
      opacity: 0;
      transform: scale(0.85);
      pointer-events: none;
      transition: opacity 0.18s ease, transform 0.18s ease, background 0.16s ease;
    }

    .composer-send-btn.is-ready {
      opacity: 1;
      transform: scale(1);
      pointer-events: auto;
    }

    .composer-send-btn:hover {
      background: #178764;
    }
  </style>
</head>

<body>
  <div class="chat-page">
    <header class="chat-page-header">
      <button type="button" class="chat-back-btn" id="chatBackBtn" aria-label="Go back">
        <span class="material-symbols-outlined">arrow_back</span>
      </button>
      <h1 class="chat-page-title">AI Chatbot</h1>
      <div></div>
    </header>

    <main class="chat-thread" id="chatThread">
      <div class="chat-intro-profile">
        <div class="chat-intro-avatar" aria-hidden="true">
          <svg width="42" height="42" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="6" y="9" width="20" height="14" rx="7" fill="#1d9e75" fill-opacity="0.14" stroke="#1d9e75" stroke-width="2"/>
            <rect x="10" y="13" width="12" height="6" rx="3" fill="#0f6e56"/>
            <circle cx="13.5" cy="16" r="1.4" fill="#ffffff"/>
            <circle cx="18.5" cy="16" r="1.4" fill="#ffffff"/>
            <path d="M16 5V9" stroke="#1d9e75" stroke-width="2" stroke-linecap="round"/>
            <circle cx="16" cy="4.5" r="1.5" fill="#1d9e75"/>
          </svg>
        </div>
        <h2 class="chat-intro-name">MediFind Assistant</h2>
        <p class="chat-intro-note">
          <span class="material-symbols-outlined align-middle" style="font-size:0.88rem;vertical-align:-2px;">lock</span>
          For informational purposes only. Do not share personal medical details — always consult a licensed physician or pharmacist.
        </p>
        <div class="chat-intro-time" id="chatIntroTime"></div>
      </div>
    </main>

    <div class="chat-suggestions-wrap is-open" id="chatSuggestionsWrap">
      <div class="chat-suggestions-list">
        <button type="button" class="suggest-pill" data-prompt="What is Paracetamol used for?">What is Paracetamol used for?</button>
        <button type="button" class="suggest-pill" data-prompt="Which pharmacies in Malaybalay are participating?">Malaybalay pharmacies</button>
        <button type="button" class="suggest-pill" data-prompt="How should adults usually take Biogesic 500mg?">Biogesic 500mg</button>
        <button type="button" class="suggest-pill" data-prompt="Can I ask about Amoxicillin availability?">Amoxicillin</button>
      </div>
    </div>

    <footer class="chat-composer-wrap">
      <div class="chat-composer">
        <button type="button" class="composer-plus-btn is-active" id="composerPlusBtn" aria-label="Toggle quick prompts">
          <span class="material-symbols-outlined" style="font-size:1.25rem;">add</span>
        </button>
        <input
          type="text"
          class="composer-input"
          id="chatInput"
          placeholder="Message..."
          autocomplete="off" />
        <button type="button" class="composer-send-btn" id="chatSendBtn" aria-label="Send message">
          <span class="material-symbols-outlined" style="font-size:1.15rem;">arrow_upward</span>
        </button>
      </div>
    </footer>
  </div>

  <script>
    const MEDICINES_DB = <?= json_encode($medicinesList, JSON_UNESCAPED_SLASHES) ?>;
    const PHARMACIES_DB = <?= json_encode($pharmaciesList, JSON_UNESCAPED_SLASHES) ?>;
    const USER_AVATAR = '../07_Assets/images/person.jpg';

    const chatThread = document.getElementById('chatThread');
    const chatInput = document.getElementById('chatInput');
    const chatSendBtn = document.getElementById('chatSendBtn');
    const composerPlusBtn = document.getElementById('composerPlusBtn');
    const suggestionsWrap = document.getElementById('chatSuggestionsWrap');

    document.getElementById('chatBackBtn').addEventListener('click', () => {
      try {
        if (document.referrer && new URL(document.referrer).origin === window.location.origin && !document.referrer.includes('06_AIChatbot.php')) {
          window.location.href = document.referrer;
          return;
        }
      } catch (e) {}
      window.location.href = '01_Home.php';
    });

    composerPlusBtn.addEventListener('click', () => {
      const open = suggestionsWrap.classList.toggle('is-open');
      composerPlusBtn.classList.toggle('is-active', open);
    });

    chatInput.addEventListener('input', () => {
      chatSendBtn.classList.toggle('is-ready', chatInput.value.trim().length > 0);
    });

    chatInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        e.preventDefault();
        sendUserMessage();
      }
    });

    chatSendBtn.addEventListener('click', sendUserMessage);

    document.querySelectorAll('.suggest-pill').forEach((btn) => {
      btn.addEventListener('click', () => {
        const prompt = btn.getAttribute('data-prompt') || '';
        if (!prompt) return;
        chatInput.value = prompt;
        sendUserMessage();
      });
    });

    function getTimeString() {
      return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function escapeHtml(str) {
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
    }

    function botAvatarHtml() {
      return `
        <div class="chat-bot-avatar" aria-hidden="true">
          <svg width="26" height="26" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="6" y="9" width="20" height="14" rx="7" fill="#1d9e75" fill-opacity="0.14" stroke="#1d9e75" stroke-width="2"/>
            <rect x="10" y="13" width="12" height="6" rx="3" fill="#0f6e56"/>
            <circle cx="13.5" cy="16" r="1.4" fill="#ffffff"/>
            <circle cx="18.5" cy="16" r="1.4" fill="#ffffff"/>
            <path d="M16 5V9" stroke="#1d9e75" stroke-width="2" stroke-linecap="round"/>
            <circle cx="16" cy="4.5" r="1.5" fill="#1d9e75"/>
          </svg>
        </div>
      `;
    }

    function appendMessage(text, sender, customTime) {
      const row = document.createElement('div');
      row.className = `chat-row ${sender}`;
      const timeLabel = customTime || getTimeString();

      if (sender === 'user') {
        row.innerHTML = `
          <div class="chat-row-inner">
            <div class="chat-bubble">${escapeHtml(text)}</div>
            <img src="${USER_AVATAR}" alt="You" class="chat-avatar" />
          </div>
          <div class="chat-time">${timeLabel}</div>
        `;
      } else {
        row.innerHTML = `
          <div class="chat-row-inner">
            ${botAvatarHtml()}
            <div class="chat-bubble">${escapeHtml(text)}</div>
          </div>
          <div class="chat-time">${timeLabel}</div>
        `;
      }

      chatThread.appendChild(row);
      chatThread.scrollTop = chatThread.scrollHeight;
    }

    function showTyping() {
      const row = document.createElement('div');
      row.className = 'chat-row bot';
      row.id = 'typingRow';
      row.innerHTML = `
        <div class="chat-row-inner">
          ${botAvatarHtml()}
          <div class="chat-bubble">
            <div class="typing-dots"><span></span><span></span><span></span></div>
          </div>
        </div>
      `;
      chatThread.appendChild(row);
      chatThread.scrollTop = chatThread.scrollHeight;
    }

    function removeTyping() {
      const row = document.getElementById('typingRow');
      if (row) row.remove();
    }

    function buildReply(query) {
      const q = query.toLowerCase();

      if (q.includes('pharmac') || q.includes('malaybalay') || q.includes('where') || q.includes('open')) {
        if (PHARMACIES_DB.length) {
          const names = PHARMACIES_DB.map((p) => `${p.name} (${p.address})`).join(', ');
          return `MediFind currently connects with ${PHARMACIES_DB.length} participating pharmacies in Malaybalay City: ${names}. You can check live availability and routes from the Pharmacies or Map tab.`;
        }
      }

      const matchedMed = MEDICINES_DB.find((m) =>
        q.includes((m.brand_name || '').toLowerCase()) ||
        q.includes((m.generic_name || '').toLowerCase())
      );

      if (matchedMed) {
        const rxNote = matchedMed.requires_prescription
          ? 'This medicine requires a valid prescription.'
          : 'This is an over-the-counter medicine.';
        return `${matchedMed.brand_name} (${matchedMed.generic_name} ${matchedMed.dosage}, ${matchedMed.dosage_form}) is categorized under ${matchedMed.category}. ${rxNote} Always confirm dosage and safety with a licensed physician or pharmacist.`;
      }

      if (q.includes('empty stomach') || q.includes('food') || q.includes('meal')) {
        return `Many common medicines like Paracetamol are generally gentle on the stomach and can often be taken with or without food. If you have stomach sensitivity or other conditions, please ask a doctor or pharmacist.`;
      }

      if (q.includes('miss a dose') || q.includes('missed')) {
        return `If a medicine is taken only when needed for pain or fever, there may not be a strict schedule. For regular prescriptions, ask your pharmacist or physician before doubling a dose.`;
      }

      return `I can help explain medicine details, prescription status, or participating pharmacies in Malaybalay City. For personal medical advice or dosage changes, please consult a licensed physician or pharmacist.`;
    }

    function sendUserMessage() {
      const text = chatInput.value.trim();
      if (!text) return;

      appendMessage(text, 'user');
      chatInput.value = '';
      chatSendBtn.classList.remove('is-ready');
      suggestionsWrap.classList.remove('is-open');
      composerPlusBtn.classList.remove('is-active');

      showTyping();
      setTimeout(() => {
        removeTyping();
        appendMessage(buildReply(text), 'bot');
      }, 650);
    }

    // Initial welcome greeting
    const introTimeEl = document.getElementById('chatIntroTime');
    if (introTimeEl) introTimeEl.textContent = getTimeString();

    appendMessage(
      "Hi! I'm your MediFind Assistant. Ask me about medicines, dosages, or participating pharmacies in Malaybalay City.",
      'bot'
    );
  </script>
</body>

</html>

