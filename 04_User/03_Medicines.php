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

$medJsonPath = __DIR__ . '/../database/medicines_db.json';
$medJsonData = file_exists($medJsonPath) ? json_decode(file_get_contents($medJsonPath), true) : ['medicines' => []];
$medicinesCatalog = $medJsonData['medicines'] ?? [];
$categoriesList = array_values(array_unique(array_filter(array_map(function ($m) {
    return $m['category'] ?? '';
}, $medicinesCatalog))));
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MediFind: Medicines</title>

  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <!-- <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" /> -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/medicines.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    .med-card-actions {
      display: flex;
      gap: 8px;
      margin-top: 10px;
    }
    .med-card-actions .view-details-btn,
    .med-card-actions .find-map-btn {
      flex: 1;
      text-align: center;
      text-decoration: none;
      padding: 8px 10px;
      border-radius: 10px;
      font-size: 0.78rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 4px;
      transition: all 0.16s ease;
    }
    .med-card-actions .find-map-btn {
      background: #1d9e75;
      color: #ffffff;
      border: 1px solid #1d9e75;
    }
    .med-card-actions .find-map-btn:hover {
      background: #178764;
      color: #ffffff;
    }
    .med-card-actions .view-details-btn {
      margin-top: 0 !important;
      background: #f4f9f7;
      color: #1a2e27;
      border: 1px solid #d5e6df;
    }
    .med-card-actions .view-details-btn:hover {
      background: #e8f7f2;
      color: #0f6e56;
    }

    @media (max-width: 768px) {
      body,
      .wrapper,
      .main-panel,
      #content,
      .content-body,
      .hero,
      .search-section,
      .all-medicines-section {
        background: #ffffff !important;
      }
      #content {
        padding: 0 0 100px !important;
      }
      .content-body {
        padding: 16px 14px 0 !important;
      }
      .hero {
        padding: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
      }
      .search-section {
        padding: 0 0 10px !important;
        border-radius: 0 !important;
        color: #1a2e27 !important;
      }
      .welcome-user {
        color: #1a2e27 !important;
        font-size: 1.35rem !important;
      }
      .user-location {
        color: #6b8f82 !important;
      }
      .search-filter-btn {
        display: none !important;
      }
      .quick-categories-section .section-header h5,
      .all-medicines-section .section-header h5 {
        color: #1a2e27 !important;
      }
      .quick-categories-section .section-header a,
      .all-medicines-section .section-header a {
        color: #1d9e75 !important;
      }
      .category-nav {
        display: none !important;
      }
      .quick-categories-wrapper {
        overflow: hidden !important;
      }
      .quick-categories {
        display: flex !important;
        justify-content: flex-start !important;
        gap: 10px !important;
        overflow-x: auto !important;
        padding: 4px 2px !important;
        scrollbar-width: none !important;
      }
      .quick-categories::-webkit-scrollbar {
        display: none !important;
      }
      .quick-categories .category-item {
        flex: 0 0 auto !important;
      }
      .all-medicines-section {
        padding: 8px 0 16px !important;
      }
      .medicine-list {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 10px !important;
      }
      .medicine-card {
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        gap: 8px !important;
        padding: 10px !important;
        background: #ffffff !important;
        border: 1px solid #e5ece9 !important;
        border-radius: 16px !important;
      }
      .medicine-card-img {
        width: 100% !important;
        height: 76px !important;
        object-fit: contain !important;
        border-radius: 10px !important;
        background: #f8fafc !important;
        padding: 6px !important;
      }
      .medicine-card-body {
        flex: 1 !important;
        min-width: 0 !important;
        padding: 0 !important;
        display: flex !important;
        flex-direction: column !important;
      }
      .medicine-name {
        font-size: 0.86rem !important;
        line-height: 1.2 !important;
      }
      .medicine-dose {
        font-size: 0.68rem !important;
        white-space: nowrap !important;
      }
      .medicine-brand,
      .medicine-meta {
        font-size: 0.72rem !important;
      }
      .medicine-price {
        font-size: 0.88rem !important;
        margin-bottom: 4px !important;
      }
      .med-card-actions {
        margin-top: auto !important;
        padding-top: 8px !important;
        flex-direction: column !important;
        gap: 6px !important;
      }
      .med-card-actions .find-map-btn,
      .med-card-actions .view-details-btn {
        width: 100% !important;
        padding: 7px 8px !important;
        font-size: 0.75rem !important;
      }
    }
  </style>

</head>

<body data-active="03">
  <div class="wrapper d-flex align-items-stretch">
    <div id="sidebar-container"><?php include '../01_Includes/users-sidebar-fixed.php'; ?></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content">
        <div class="content-body">
          <div class="hero">
            <!-- Search Section -->
            <div class="search-section">
              <div class="welcome-location-block">
                <h2 class="welcome-user">Browse Medicines</h2>

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
                    id="medSearchInput"
                    placeholder="Search medicine by name or brand..." />
                </div>

                <button class="search-filter-btn">
                  <span class="material-symbols-outlined">
                    discover_tune
                  </span>
                </button>
              </div>

              <!-- Categories -->
              <div class="quick-categories-section">
                <div class="section-header">
                  <h5>Categories</h5>
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

              <!-- All Medicines -->
              <div class="all-medicines-section">
                <div class="section-header">
                  <h5>All Medicines</h5>
                  <a href="#">See all</a>
                </div>

                <div class="medicine-list" id="medicineCatalogGrid">
                  <?php foreach ($medicinesCatalog as $med): ?>
                    <?php
                    $gName = $med['generic_name'] ?? 'Medicine';
                    $bName = $med['brand_name'] ?? 'Generic';
                    $catName = $med['category'] ?? 'General';
                    $dosage = $med['dosage'] ?? '';
                    $form = $med['dosage_form'] ?? 'Tablet';
                    $price = number_format((float)($med['unit_price'] ?? 0), 2);
                    $isRx = !empty($med['requires_prescription']);
                    $imgPath = '../' . ltrim((string)($med['image'] ?? '07_Assets/images/medicines/Paracetamol.png'), '/');
                    $searchBlob = strtolower($gName . ' ' . $bName . ' ' . $catName);
                    ?>
                    <div class="medicine-card"
                      data-category="<?= htmlspecialchars(strtolower($catName)) ?>"
                      data-search="<?= htmlspecialchars($searchBlob) ?>">
                      <img
                        src="<?= htmlspecialchars($imgPath) ?>"
                        alt="<?= htmlspecialchars($gName) ?>"
                        class="medicine-card-img" />

                      <div class="medicine-card-body">
                        <div class="medicine-top">
                          <div>
                            <h6 class="medicine-name"><?= htmlspecialchars($gName) ?></h6>
                            <p class="medicine-brand"><?= htmlspecialchars($bName) ?></p>
                          </div>
                          <?php if ($dosage): ?>
                            <span class="medicine-dose"><?= htmlspecialchars($dosage) ?></span>
                          <?php endif; ?>
                        </div>

                        <p class="medicine-meta"><?= htmlspecialchars($form) ?> &middot; <?= htmlspecialchars($catName) ?></p>
                        <p class="medicine-price">
                          ₱<?= $price ?> <span>/ <?= htmlspecialchars(strtolower($form)) ?></span>
                        </p>
                        <span class="medicine-tag <?= $isRx ? 'rx-required' : 'no-rx' ?>">
                          <?= $isRx ? 'Rx Required' : 'No Rx' ?>
                        </span>

                        <div class="med-card-actions">
                          <a class="find-map-btn"
                            href="02.3_MedicineMap.php?q=<?= urlencode($gName) ?>&from=medicines">
                            <span class="material-symbols-outlined" style="font-size:0.95rem;">location_on</span>
                            Find on Map
                          </a>
                          <a class="view-details-btn"
                            href="03.2_MedicineDetails.php?med=<?= urlencode($gName) ?>">
                            Details
                          </a>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
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

  <script>
    (function initMedicinesFilter() {
      const searchInput = document.getElementById('medSearchInput');
      const cards = Array.from(document.querySelectorAll('#medicineCatalogGrid .medicine-card'));
      const catItems = Array.from(document.querySelectorAll('#quickCategories .category-item'));
      let activeCat = new URLSearchParams(window.location.search).get('category') || '';

      function applyFilter() {
        const q = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const catQ = activeCat.trim().toLowerCase();
        cards.forEach(card => {
          const blob = card.dataset.search || '';
          const cCat = card.dataset.category || '';
          const matchQ = !q || blob.includes(q);
          const matchCat = !catQ || cCat.includes(catQ) || blob.includes(catQ);
          card.style.display = (matchQ && matchCat) ? '' : 'none';
        });
      }

      if (searchInput) {
        searchInput.addEventListener('input', applyFilter);
      }

      catItems.forEach(item => {
        const lbl = (item.querySelector('.category-label')?.textContent || '').trim();
        if (activeCat && lbl.toLowerCase().includes(activeCat.toLowerCase())) {
          catItems.forEach(c => c.classList.remove('active'));
          item.classList.add('active');
        }
        item.addEventListener('click', (e) => {
          e.preventDefault();
          const wasActive = item.classList.contains('active');
          catItems.forEach(c => c.classList.remove('active'));
          if (wasActive && activeCat) {
            activeCat = '';
          } else {
            item.classList.add('active');
            activeCat = lbl.split('&')[0].trim();
          }
          applyFilter();
        });
      });

      if (activeCat) applyFilter();
    })();
  </script>
  <script src="../07_Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../bootstrap/js/jquery.min.js"></script>
  <script src="../bootstrap/js/popper.js"></script>
  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

</body>

</html>