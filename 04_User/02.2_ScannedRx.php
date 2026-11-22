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
?>


<!doctype html>
<html lang="en" class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MediFind: Scan Prescriptions</title>
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <!-- <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" /> -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/scannedrx.css" />

  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    rel="stylesheet" />

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    html, body, .wrapper, .main-panel, #content {
      background: #ffffff !important;
      margin-left: 0 !important;
      width: 100% !important;
    }
    #content {
      overflow-y: auto !important;
      padding-bottom: 24px !important;
    }
    .page-wrapper {
      max-width: 1080px;
      margin: 0 auto;
      padding: 18px 24px 36px !important;
    }
    .content-box {
      background: transparent !important;
      box-shadow: none !important;
      border: none !important;
      padding: 0 !important;
    }
    .review-card {
      background: transparent;
      border-radius: 0;
      border: none;
      box-shadow: none;
      padding: 4px 12px;
    }
    .back-circle-btn {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: #ffffff;
      color: #1f2937;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: transform 0.16s ease, box-shadow 0.16s ease, background 0.16s ease;
    }
    .back-circle-btn:hover {
      background: #f8fafc;
      color: #111827;
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
    }
    .btn-rescan-clean {
      width: 100%;
      margin-top: 10px;
      padding: 12px 18px;
      border-radius: 12px;
      background: #1d9e75;
      color: #ffffff;
      border: none;
      font-weight: 600;
      font-size: 0.94rem;
      cursor: pointer;
      transition: background 0.18s ease, transform 0.14s ease;
    }
    .btn-rescan-clean:hover {
      background: #178764;
      transform: translateY(-1px);
    }
    .btn-continue-clean {
      width: 100%;
      margin-top: 0;
      padding: 12px 18px;
      border-radius: 12px;
      background: #ff5b27;
      color: #ffffff;
      border: none;
      font-weight: 600;
      font-size: 0.94rem;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      transition: background 0.18s ease, transform 0.14s ease;
    }
    .btn-continue-clean:hover {
      background: #e64d1c;
      transform: translateY(-1px);
    }
    .rx-summary-table {
      width: 100%;
      border-collapse: collapse;
      margin: 14px 0 8px;
      text-align: left;
    }
    .rx-summary-table tr {
      border-bottom: 1px solid #eef2f0;
    }
    .rx-summary-table tr:last-child {
      border-bottom: none;
    }
    .rx-summary-table td {
      padding: 9px 2px;
      font-size: 0.88rem;
      vertical-align: top;
    }
    .rx-summary-table td:first-child {
      color: #6b7280;
      font-weight: 500;
      width: 42%;
    }
    .rx-summary-table td:last-child {
      color: #1f2937;
      font-weight: 600;
      text-align: right;
    }
    .btn-edit-details {
      background: transparent;
      border: none;
      color: #ff5b27;
      font-weight: 600;
      font-size: 0.88rem;
      padding: 8px 14px;
      margin: 4px auto 10px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.18s ease, color 0.18s ease;
    }
    .btn-edit-details:hover {
      background: #fff3ee;
      color: #e64d1c;
    }
    .rx-edit-panel {
      max-height: 0;
      opacity: 0;
      overflow: hidden;
      transform: translateY(-4px);
      transition: max-height 0.32s cubic-bezier(0.22, 1, 0.36, 1),
                  opacity 0.24s ease,
                  transform 0.24s ease,
                  margin 0.24s ease;
      margin-bottom: 0;
    }
    .rx-edit-panel.is-open {
      max-height: 520px;
      opacity: 1;
      transform: translateY(0);
      margin-bottom: 14px;
    }
    @media (max-width: 767.98px) {
      .pill-icon-wrap,
      .floating-btns,
      #chatPanel {
        display: none !important;
      }
    }
    @media (max-width: 575.98px) {
      .page-wrapper {
        padding: 14px 14px 28px !important;
      }
      .review-card {
        padding: 8px 6px;
      }
    }
  </style>

</head>

<body data-active="02">
  <div class="wrapper d-flex align-items-stretch">
    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="content">
        <div class="content-body">
          <div class="page-wrapper container-fluid">

            <div class="mb-2">
              <a href="02_ScanRX.php" class="back-circle-btn" aria-label="Back to Scan RX" title="Back to Scan RX">
                <span class="material-symbols-outlined" style="font-size:1.3rem;">arrow_back</span>
              </a>
            </div>

            <div class="content-box">
              <div class="row g-4 align-items-start">
                <!-- LEFT PANEL: Mandatory Review & Edit -->
                <div class="col-lg-6">
                  <div class="review-card text-center">
                    <h3 class="scan-title">Prescription scanned!</h3>
                    <p class="scan-subtitle">
                      We extracted the details below. Please review before continuing.
                    </p>

                    <div class="pill-icon-wrap">
                      <span class="material-symbols-outlined">pill</span>
                    </div>

                    <h4 class="medicine-title" id="reviewMedHeading">Paracetamol 500mg</h4>

                    <table class="rx-summary-table" id="rxSummaryTable">
                      <tbody>
                        <tr><td>Medicine Name</td><td id="dispMedName">Paracetamol</td></tr>
                        <tr><td>Strength</td><td id="dispStrength">500mg</td></tr>
                        <tr><td>Form</td><td id="dispForm">Tablet</td></tr>
                        <tr><td>Dose</td><td id="dispDose">1 tablet</td></tr>
                        <tr><td>Frequency</td><td id="dispFrequency">After meals</td></tr>
                        <tr><td>Quantity</td><td id="dispQuantity">30</td></tr>
                        <tr><td>Note</td><td id="dispNote">Take daily after eating</td></tr>
                      </tbody>
                    </table>

                    <button type="button" class="btn-edit-details" id="rxToggleEditBtn" aria-expanded="false" aria-controls="rxEditPanel">
                      Edit Details
                    </button>

                    <div class="rx-edit-panel text-start" id="rxEditPanel">
                      <div class="p-3 rounded-3 border bg-light-subtle">
                        <div class="row g-2">
                          <div class="col-12">
                            <label for="rxMedName" class="form-label small fw-semibold text-muted mb-1">Medicine Name</label>
                            <input type="text" class="form-control form-control-sm rounded-3" id="rxMedName" list="rxMedOptions" value="Paracetamol" />
                            <datalist id="rxMedOptions">
                              <?php foreach ($medicinesList as $med): ?>
                                <option value="<?= htmlspecialchars($med['brand_name']) ?>"><?= htmlspecialchars($med['generic_name'] . ' · ' . $med['dosage']) ?></option>
                                <option value="<?= htmlspecialchars($med['generic_name']) ?>"><?= htmlspecialchars($med['brand_name'] . ' · ' . $med['dosage']) ?></option>
                              <?php endforeach; ?>
                            </datalist>
                          </div>
                          <div class="col-6">
                            <label for="rxStrength" class="form-label small fw-semibold text-muted mb-1">Strength</label>
                            <input type="text" class="form-control form-control-sm rounded-3" id="rxStrength" value="500mg" />
                          </div>
                          <div class="col-6">
                            <label for="rxForm" class="form-label small fw-semibold text-muted mb-1">Form</label>
                            <input type="text" class="form-control form-control-sm rounded-3" id="rxForm" value="Tablet" />
                          </div>
                          <div class="col-6">
                            <label for="rxDose" class="form-label small fw-semibold text-muted mb-1">Dose</label>
                            <input type="text" class="form-control form-control-sm rounded-3" id="rxDose" value="1 tablet" />
                          </div>
                          <div class="col-6">
                            <label for="rxFrequency" class="form-label small fw-semibold text-muted mb-1">Frequency</label>
                            <input type="text" class="form-control form-control-sm rounded-3" id="rxFrequency" value="After meals" />
                          </div>
                          <div class="col-4">
                            <label for="rxQuantity" class="form-label small fw-semibold text-muted mb-1">Quantity</label>
                            <input type="text" class="form-control form-control-sm rounded-3" id="rxQuantity" value="30" />
                          </div>
                          <div class="col-8">
                            <label for="rxNote" class="form-label small fw-semibold text-muted mb-1">Note</label>
                            <input type="text" class="form-control form-control-sm rounded-3" id="rxNote" value="Take daily after eating" />
                          </div>
                        </div>
                      </div>
                    </div>

                    <button type="button" class="btn-continue-clean" id="rxContinueBtn">
                      Continue
                      <span class="material-symbols-outlined" style="font-size:1.15rem;">arrow_right_alt</span>
                    </button>

                    <button type="button" class="btn-rescan-clean" onclick="window.location.href='02_ScanRX.php?rescan=1'">Rescan</button>
                  </div>
                </div>

                <!-- RIGHT PANEL: Zero Image Retention + FDA Match + Mandatory Disclaimer -->
                <div class="col-lg-6">
                  <div class="review-card d-flex flex-column gap-3">
                    <div class="p-3 rounded-4 border" style="background:#eef8f4;border-color:#cde8dd !important;">
                      <div class="d-flex align-items-center gap-2 fw-bold mb-1" style="color:#0f6e56;font-size:0.9rem;">
                        <span class="material-symbols-outlined" style="font-size:1.25rem;">verified_user</span>
                        Original Prescription Image Deleted (RA 10173)
                      </div>
                      <p class="mb-0 small" style="color:#365c4e;">
                        Your prescription image was processed and immediately discarded. Personal identifiers (patient/doctor names, addresses, contact details) are stripped before AI structuring, and only medicine text is kept.
                      </p>
                    </div>

                    <div class="p-3 rounded-4 border bg-white" id="rxFdaMatchCard">
                      <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold" style="color:#1a2e27;font-size:0.92rem;">Philippine FDA Reference Match</span>
                        <span class="badge rounded-pill" id="rxFdaBadge" style="background:#e8f7f2;color:#0f6e56;">FDA-Verified</span>
                      </div>
                      <div id="rxFdaMatchBody" class="small text-muted"></div>
                    </div>

                    <div class="p-3 rounded-4 border" style="background:#fffbeb;border-color:#fde68a !important;">
                      <div class="d-flex align-items-start gap-2 small" style="color:#92400e;">
                        <span class="material-symbols-outlined" style="font-size:1.2rem;flex-shrink:0;">warning</span>
                        <div>
                          <strong>Medical Disclaimer:</strong> OCR and AI extraction may contain errors from unclear handwriting. MediFind does not provide medical diagnosis, modify prescriptions, or approve medicine substitutions. Always confirm with a licensed physician or pharmacist.
                        </div>
                      </div>
                    </div>
                  </div>
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // RA 10173 Zero Image Retention: ensure any temporary uploadedRx image is immediately purged
    sessionStorage.removeItem("uploadedRx");

    const SCANNED_MEDICINES = <?= json_encode($medicinesList, JSON_UNESCAPED_SLASHES) ?>;
    const medNameInput = document.getElementById("rxMedName");
    const strengthInput = document.getElementById("rxStrength");
    const formInput = document.getElementById("rxForm");
    const headingEl = document.getElementById("reviewMedHeading");
    const fdaBadgeEl = document.getElementById("rxFdaBadge");
    const fdaBodyEl = document.getElementById("rxFdaMatchBody");

    function updateFdaMatch() {
      const q = (medNameInput.value || "").trim().toLowerCase();
      headingEl.textContent = `${medNameInput.value.trim() || "Medicine"} ${strengthInput.value.trim()}`.trim();

      const match = q ? SCANNED_MEDICINES.find(m =>
        m.brand_name.toLowerCase() === q ||
        m.generic_name.toLowerCase() === q ||
        m.brand_name.toLowerCase().includes(q) ||
        m.generic_name.toLowerCase().includes(q)
      ) : null;

      if (!match) {
        fdaBadgeEl.textContent = "Manual Review";
        fdaBadgeEl.style.background = "#fef3c7";
        fdaBadgeEl.style.color = "#92400e";
        fdaBodyEl.innerHTML = `No exact match in local FDA prototype list. You can still edit the name or search pharmacy availability manually.`;
        return;
      }

      fdaBadgeEl.textContent = "FDA-Verified";
      fdaBadgeEl.style.background = "#e8f7f2";
      fdaBadgeEl.style.color = "#0f6e56";

      const alts = SCANNED_MEDICINES.filter(m =>
        m.id !== match.id && (
          m.generic_name.toLowerCase() === match.generic_name.toLowerCase() ||
          m.category.toLowerCase() === match.category.toLowerCase()
        )
      ).slice(0, 2);

      fdaBodyEl.innerHTML = `
        <div class="d-flex align-items-center gap-3 mb-2">
          <img src="../${match.image}" alt="${match.brand_name}" style="width:42px;height:42px;object-fit:contain;border-radius:8px;border:1px solid #e6efec;padding:2px;" />
          <div>
            <div class="fw-bold text-dark">${match.brand_name} (${match.generic_name})</div>
            <div class="text-muted" style="font-size:0.78rem;">${match.dosage} &bull; ${match.dosage_form} &bull; ${match.requires_prescription ? "Requires Rx" : "Over-the-Counter"}</div>
          </div>
        </div>
        ${alts.length ? `
          <div class="mt-2 pt-2 border-top" style="font-size:0.78rem;">
            <div class="fw-semibold text-dark mb-2">Informational Alternatives:</div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:8px;">
              ${alts.map(a => `
                <div style="display:flex;align-items:center;gap:9px;padding:7px 9px;border-radius:12px;border:1px solid #e5ece9;background:#f8fafc;">
                  <img src="../${a.image}" alt="${a.brand_name}" style="width:38px;height:38px;object-fit:contain;border-radius:8px;background:#ffffff;border:1px solid #e6efec;padding:2px;flex-shrink:0;" />
                  <div style="min-width:0;flex:1;">
                    <div class="fw-semibold text-dark" style="font-size:0.78rem;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${a.brand_name}</div>
                    <div class="text-muted" style="font-size:0.7rem;line-height:1.25;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${a.generic_name} &bull; ${a.dosage}</div>
                  </div>
                </div>
              `).join("")}
            </div>
            <div class="text-muted mt-2" style="font-size:0.72rem;">For informational purposes only — confirm any substitution with a licensed pharmacist or physician.</div>
          </div>
        ` : ""}
      `;
    }

    medNameInput.addEventListener("input", updateFdaMatch);
    strengthInput.addEventListener("input", () => {
      headingEl.textContent = `${medNameInput.value.trim() || "Medicine"} ${strengthInput.value.trim()}`.trim();
    });
    updateFdaMatch();

    // Sync editable inputs -> clean summary table rows
    const fieldMap = [
      ["rxMedName", "dispMedName"],
      ["rxStrength", "dispStrength"],
      ["rxForm", "dispForm"],
      ["rxDose", "dispDose"],
      ["rxFrequency", "dispFrequency"],
      ["rxQuantity", "dispQuantity"],
      ["rxNote", "dispNote"]
    ];
    fieldMap.forEach(([inputId, dispId]) => {
      const inp = document.getElementById(inputId);
      const disp = document.getElementById(dispId);
      if (inp && disp) {
        inp.addEventListener("input", () => {
          disp.textContent = inp.value.trim() || "—";
        });
      }
    });

    // Smooth Edit Details toggle
    const toggleEditBtn = document.getElementById("rxToggleEditBtn");
    const editPanel = document.getElementById("rxEditPanel");
    if (toggleEditBtn && editPanel) {
      toggleEditBtn.addEventListener("click", () => {
        const isOpen = editPanel.classList.toggle("is-open");
        toggleEditBtn.setAttribute("aria-expanded", String(isOpen));
        toggleEditBtn.textContent = isOpen ? "Done Editing" : "Edit Details";
        if (isOpen) {
          setTimeout(() => medNameInput.focus(), 180);
        }
      });
    }

    document.getElementById("rxContinueBtn").addEventListener("click", () => {
      const medName = medNameInput.value.trim();
      const strength = strengthInput.value.trim();
      if (!medName) {
        medNameInput.focus();
        return;
      }

      // Persist text-only scan record (no images) to localStorage for Recent Scans
      try {
        const existing = JSON.parse(localStorage.getItem("mf_recent_scans") || "[]");
        const dateStr = new Date().toLocaleDateString("en-US", { month: "short", day: "2-digit", year: "numeric" });
        const updated = [{ med: medName, strength: strength || formInput.value.trim(), date: dateStr }, ...existing].slice(0, 8);
        localStorage.setItem("mf_recent_scans", JSON.stringify(updated));
      } catch (e) {}

      window.location.href = `02.3_MedicineMap.php?q=${encodeURIComponent(medName)}`;
    });
  </script>
</body>

</html>