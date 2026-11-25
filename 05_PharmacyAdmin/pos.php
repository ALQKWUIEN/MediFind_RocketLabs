<?php
// 05_PharmacyAdmin/pos.php
include_once __DIR__ . '/../02_Actions/GlobalVariables.php';
include_once __DIR__ . '/../00_Config/config.php';

// ── GUARD: Users must log in ────────────────────────
if (empty($_SESSION['user_id'])) {
  header('Location: ../03_Authentication/login.php');
  exit;
}

$pharmacyID = (int) ($_SESSION['pharmacy_id'] ?? 0);
if (!$pharmacyID) {
  $stmt = $pdo->prepare("SELECT Pharmacy_ID, Pharmacy_name FROM 09_pharmacies WHERE User_ID = ? LIMIT 1");
  $stmt->execute([$_SESSION['user_id']]);
  $p = $stmt->fetch();
  if ($p) {
    $pharmacyID = (int) $p['Pharmacy_ID'];
    $_SESSION['pharmacy_id'] = $pharmacyID;
    $_SESSION['pharmacy_name'] = $p['Pharmacy_name'];
  } else {
    die("Error: Pharmacy ID not found in session. Please log in again.");
  }
}

// Fetch categories for fast filter pills
$catStmt = $pdo->query("SELECT DISTINCT Category_Name FROM 18_meds_categories ORDER BY Category_Name ASC LIMIT 8");
$popularCategories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

// Fallback SVG data-URI placeholder (pill icon on #f0f9f5 light-green tint)
$fallbackSvg = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64' width='64' height='64'><rect width='64' height='64' rx='10' fill='%23f0f9f5'/><path d='M22 42 L42 22 A9 9 0 0 1 55 35 L35 55 A9 9 0 0 1 22 42 Z' fill='%231d9e75' opacity='0.85'/><path d='M22 42 L42 22 A9 9 0 0 0 9 29 L29 49 A9 9 0 0 0 22 42 Z' fill='%23178a63'/><line x1='22' y1='42' x2='42' y2='22' stroke='%23ffffff' stroke-width='2.5'/></svg>";

// Preload top available medicines in stock for initial display
$preloadStmt = $pdo->prepare("
  SELECT 
    i.Inventory_ID, i.Medicine_ID, i.Quantity, i.Price,
    gn.Generic_Name, bn.Brand_Name, df.Dosage_Form, dv.Dosage_Value, du.Unit_Name,
    c.Category_Name, rx.Requirement_Status AS Requires_Rx, up.Unit AS Price_Per,
    COALESCE(img.Image_Path, '') AS Med_Image_URL
  FROM 21_inventory i
  INNER JOIN 12_meds_medicines med ON i.Medicine_ID = med.Medicine_ID
  LEFT JOIN 13_meds_generic_name gn ON med.Gen_ID = gn.Gen_ID
  LEFT JOIN 14_meds_brand_name bn ON med.Brand_ID = bn.Brand_ID
  LEFT JOIN 15_meds_dosage_form df ON med.DosageForm_ID = df.DosageForm_ID
  LEFT JOIN 16_meds_dosage_value dv ON med.DosageVal_ID = dv.DosageVal_ID
  LEFT JOIN 17_meds_dosage_unit du ON med.Unit_ID = du.Unit_ID
  LEFT JOIN 18_meds_categories c ON med.Category_ID = c.Category_ID
  LEFT JOIN 20_meds_requires_rx rx ON med.Require_rx_ID = rx.Require_rx_ID
  LEFT JOIN 22_inventory_unit_price up ON i.Price_Unit_ID = up.Price_Unit_ID
  LEFT JOIN (
    SELECT Medicine_ID, MIN(Image_Path) AS Image_Path 
    FROM 24_medicine_images 
    GROUP BY Medicine_ID
  ) img ON med.Medicine_ID = img.Medicine_ID
  WHERE i.Pharmacy_ID = ? AND i.Quantity > 0 AND (i.IsDeleted = 0 OR i.IsDeleted IS NULL)
  ORDER BY i.Quantity DESC
  LIMIT 50
");
$preloadStmt->execute([$pharmacyID]);
$quickAddMeds = $preloadStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <title>Point of Sale (POS) - MediFind</title>

  <!-- External libraries via CDN with pinned versions -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="../07_Assets/node_modules/material-symbols/outlined.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Scripts & CSS -->
  <script src="../07_Assets/css/js/sidebar_and_topbar.js?v=<?= time() ?>"></script>
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/theme-tokens.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/admin-buttons.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/01_dashboard.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/pos.css?v=<?= time() ?>" />

  <?php include '../01_Includes/page-transition-hardcode.php' ?>
  <style>
    #posCartTableWrap {
      max-height: 310px;
      overflow-y: auto;
      overflow-x: hidden;
      padding-right: 2px;
      margin-bottom: 12px;
    }
    .pos-cart-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .pos-cart-item-card {
      display: flex;
      align-items: center;
      gap: 14px;
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 12px 14px;
      box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    }
    .pos-cart-thumb {
      width: 84px;
      height: 84px;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      background: #ffffff;
      object-fit: contain;
      padding: 6px;
      flex-shrink: 0;
    }
    .pos-cart-item-right {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
    }
    .pos-cart-item-top {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 10px;
    }
    .pos-cart-item-meta {
      min-width: 0;
      flex: 1;
    }
    .pos-cart-item-name {
      font-weight: 700;
      font-size: 0.92rem;
      color: #0f172a;
      line-height: 1.25;
      display: flex;
      align-items: center;
      gap: 6px;
      flex-wrap: wrap;
    }
    .pos-cart-item-sub {
      font-size: 0.78rem;
      color: #64748b;
      margin-top: 2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .pos-cart-item-close {
      border: none;
      background: transparent;
      color: #94a3b8;
      width: 26px;
      height: 26px;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      flex-shrink: 0;
      transition: background 0.15s ease, color 0.15s ease;
    }
    .pos-cart-item-close:hover {
      background: #fee2e2;
      color: #dc2626;
    }
    .pos-cart-item-divider {
      border-top: 1px solid #f1f5f9;
      margin: 10px 0;
    }
    .pos-cart-item-bottom {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }
    .pos-cart-qty-pill {
      display: inline-flex;
      align-items: center;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      background: #ffffff;
      overflow: hidden;
      height: 32px;
    }
    .pos-cart-qty-pill .cart-qty-btn {
      width: 30px;
      height: 30px;
      border: none;
      background: #f8fafc;
      color: #334155;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    .pos-cart-qty-pill .cart-qty-btn:hover {
      background: #e2e8f0;
    }
    .pos-cart-qty-pill .cart-qty-input {
      width: 38px;
      height: 30px;
      border: none;
      text-align: center;
      font-weight: 600;
      font-size: 0.85rem;
      color: #0f172a;
      background: #ffffff;
      outline: none;
      -moz-appearance: textfield;
    }
    .pos-cart-qty-pill .cart-qty-input::-webkit-outer-spin-button,
    .pos-cart-qty-pill .cart-qty-input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    .pos-cart-item-total {
      font-weight: 700;
      font-size: 0.95rem;
      color: #0f172a;
      white-space: nowrap;
    }

    /* Full-Screen Focus Mode Button & Smooth Sidebar Transition */
    .pos-focus-btn {
      display: none;
      align-items: center;
      gap: 6px;
      padding: 6px 13px;
      border-radius: 10px;
      border: 1px solid #d1e3dc;
      background: #f4f9f7;
      color: #1a2e27;
      font-size: 0.82rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      white-space: nowrap;
    }
    body[data-active="pos"] .pos-focus-btn {
      display: inline-flex;
    }
    .pos-focus-btn:hover {
      background: #1d9e75;
      border-color: #1d9e75;
      color: #ffffff;
    }
    body.pos-focus-mode .pos-focus-btn {
      background: #fff3ee;
      border-color: #ffcdb8;
      color: #c2410c;
    }
    body.pos-focus-mode .pos-focus-btn:hover {
      background: #ff5b27;
      border-color: #ff5b27;
      color: #ffffff;
    }

    #pharmacy-sidebar-container {
      transition: width 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                  min-width 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                  max-width 0.28s cubic-bezier(0.22, 1, 0.36, 1),
                  opacity 0.22s ease;
    }
    body.pos-focus-mode #sidebarToggle {
      display: none !important;
    }
    body.pos-focus-mode #pharmacy-sidebar-container {
      width: 0 !important;
      min-width: 0 !important;
      max-width: 0 !important;
      opacity: 0;
      overflow: hidden;
      border-right: none !important;
      pointer-events: none;
    }
    body.pos-focus-mode .main-panel {
      height: 100vh;
      overflow: hidden;
    }
    body.pos-focus-mode #content,
    body.pos-focus-mode .content-body {
      flex: 1 1 auto;
      min-height: 0;
      display: flex;
      flex-direction: column;
      padding-bottom: 1rem !important;
    }
    body.pos-focus-mode .pos-grid {
      flex: 1 1 auto;
      min-height: 0;
      grid-template-columns: clamp(500px, 37vw, 620px) minmax(0, 1fr);
    }
    body.pos-focus-mode .pos-card.pos-catalog-card,
    body.pos-focus-mode .pos-card.pos-order-card {
      height: 100%;
      max-height: none !important;
      min-height: 0;
      display: flex;
      flex-direction: column;
    }
    body.pos-focus-mode .pos-catalog-card > .mt-3 {
      flex: 1 1 auto;
      min-height: 0;
      display: flex;
      flex-direction: column;
    }
    body.pos-focus-mode .pos-results-scrollable {
      flex: 1 1 auto;
      height: auto !important;
      min-height: 0 !important;
      max-height: none !important;
    }
    body.pos-focus-mode .pos-product-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    body.pos-focus-mode .pos-order-body {
      flex: 1 1 auto;
      min-height: 0;
      display: flex;
      flex-direction: column;
    }
    body.pos-focus-mode #posCartTableWrap {
      flex: 1 1 auto;
      max-height: none;
    }

    /* Esc Double-Press Toast */
    .pos-esc-toast {
      position: fixed;
      top: 16px;
      left: 50%;
      transform: translate(-50%, -18px);
      z-index: 2600;
      background: #0f172a;
      color: #ffffff;
      padding: 9px 18px;
      border-radius: 999px;
      font-size: 0.84rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 10px 28px rgba(15, 23, 42, 0.25);
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.22s ease, transform 0.24s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .pos-esc-toast.show {
      opacity: 1;
      transform: translate(-50%, 0);
    }
  </style>
</head>

<body data-active="pos">
  <div class="wrapper d-flex align-items-stretch">
    <div id="pharmacy-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content" style="padding: 0.65rem 1.25rem 0.5rem;">
        <div class="content-body">

          <!-- Page Header -->
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
              <h4 class="fw-bold mb-0 admin-page-title" style="font-size: 1.25rem;">Point of Sale (POS) Counter</h4>
            </div>
          </div>

          <!-- POS Main Grid (Swapped: Cart on Left, Catalog on Right) -->
          <div class="pos-grid">

            <!-- LEFT COLUMN: Counter Cart & Checkout Summary -->
            <div class="pos-card pos-order-card">
              <div class="pos-order-header">
                <div class="pos-card-title mb-0">
                  <span class="material-symbols-outlined">shopping_cart</span>
                  Current Order
                </div>
                <button type="button" id="posClearCartBtn" class="btn-pos-clear">
                  <i class="bi bi-trash3 me-1"></i> Clear Cart
                </button>
              </div>

              <div class="pos-order-body">
                <!-- Customer Name Input -->
                <div>
                  <label for="posCustomerName" class="form-label small fw-semibold text-muted mb-1">Customer / Patient Name (Optional):</label>
                  <input type="text" id="posCustomerName" class="pos-input" placeholder="Walk-in Customer" value="Walk-in Customer">
                </div>

                <!-- Cart Empty State -->
                <div id="posCartEmpty" class="text-center py-4 text-muted">
                  <span class="material-symbols-outlined d-block mb-1 text-muted" style="font-size: 2.75rem;">shopping_bag</span>
                  <h6 class="fw-bold mb-1">Cart is empty</h6>
                  <p class="small text-muted mb-0">Click medicine cards on the right or search to add items.</p>
                </div>

                <!-- Cart Items List -->
                <div id="posCartTableWrap" style="display: none;">
                  <div id="posCartTbody" class="pos-cart-list"></div>
                </div>

                <!-- Totals Calculation -->
                <div class="pos-order-summary-box">
                  <div class="pos-summary-row">
                    <span>Subtotal:</span>
                    <span id="posSubtotal" class="fw-semibold">₱0.00</span>
                  </div>
                  <div class="pos-summary-row total">
                    <span>Grand Total:</span>
                    <span id="posGrandTotal" class="pos-grandTotal pos-grand-total">₱0.00</span>
                  </div>
                </div>

                <!-- Cash Tendered & Change -->
                <div class="payment-grid">
                  <div>
                    <label for="posCashTendered" class="form-label small fw-semibold text-muted mb-1">Cash Tendered (₱):</label>
                    <input type="number" id="posCashTendered" class="pos-input" placeholder="0.00" min="0" step="any">
                  </div>
                  <div>
                    <label class="form-label small fw-semibold text-muted mb-1">Change (₱):</label>
                    <div id="posChange" class="pos-input bg-light fw-bold text-success d-flex align-items-center">₱0.00</div>
                  </div>
                </div>

                <!-- Quick Cash Buttons -->
                <div class="d-flex gap-1">
                  <button type="button" class="btn btn-outline-secondary btn-sm flex-fill quick-cash-btn" data-amount="100">₱100</button>
                  <button type="button" class="btn btn-outline-secondary btn-sm flex-fill quick-cash-btn" data-amount="200">₱200</button>
                  <button type="button" class="btn btn-outline-secondary btn-sm flex-fill quick-cash-btn" data-amount="500">₱500</button>
                  <button type="button" class="btn btn-outline-secondary btn-sm flex-fill quick-cash-btn" data-amount="1000">₱1,000</button>
                </div>

                <!-- Complete Checkout Button -->
                <button type="button" id="posCheckoutBtn" class="btn-checkout" disabled>
                  <span class="material-symbols-outlined">receipt_long</span>
                  Complete &amp; Print Receipt
                </button>
              </div>

            </div>

            <!-- RIGHT COLUMN: Search & Item Selection & Quick Pills -->
            <div class="pos-card pos-catalog-card">
              <div class="pos-card-title">
                <span class="material-symbols-outlined">manage_search</span>
                Medicine Search &amp; Quick Selection
              </div>

              <!-- Live Search Bar -->
              <div class="pos-search-wrapper">
                <div class="pos-search-input-group">
                  <span class="material-symbols-outlined text-muted">search</span>
                  <input type="text" id="posSearchInput"
                    placeholder="Type generic name, brand, or item ID (e.g. Paracetamol, Amox, 10)..."
                    autocomplete="off">
                  <span class="badge bg-light text-muted border small d-none d-sm-inline">Auto-suggest</span>
                </div>

                <!-- Dropdown Search Results -->
                <div id="posSearchResults" class="pos-search-results"></div>
              </div>

              <!-- Category Filter Pills -->
              <div class="pos-filter-pills">
                <button type="button" class="pos-filter-pill active" data-category="">All Categories</button>
                <?php foreach ($popularCategories as $pCat): ?>
                  <button type="button" class="pos-filter-pill" data-category="<?= htmlspecialchars($pCat) ?>">
                    <?= htmlspecialchars($pCat) ?>
                  </button>
                <?php endforeach; ?>
              </div>

              <!-- Quick Click Items Grid -->
              <div class="mt-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span id="posItemsSectionTitle" class="small fw-bold text-muted text-uppercase">Fast Available Stock (Quick Click to Add)</span>
                  <span id="posItemsCounter" class="small text-muted"><?= count($quickAddMeds) ?> items</span>
                </div>

                <div class="pos-results-scrollable">
                  <div id="posProductGrid" class="pos-product-grid">
                    <?php if (!empty($quickAddMeds)): ?>
                      <?php foreach ($quickAddMeds as $qm): ?>
                        <?php
                        $isRx = !empty($qm['Requires_Rx']) && (stripos($qm['Requires_Rx'], 'yes') !== false || stripos($qm['Requires_Rx'], 'require') !== false);
                        $dStr = trim(($qm['Dosage_Value'] ? $qm['Dosage_Value'] . ' ' . $qm['Unit_Name'] : '') . ($qm['Dosage_Form'] ? ' ' . $qm['Dosage_Form'] : ''));
                        $fullName = trim($qm['Generic_Name'] . ($qm['Brand_Name'] ? ' (' . $qm['Brand_Name'] . ')' : ''));

                        $imgSrc = !empty($qm['Med_Image_URL']) ? $qm['Med_Image_URL'] : $fallbackSvg;
                        if ($imgSrc !== $fallbackSvg && !str_starts_with($imgSrc, '..') && !str_starts_with($imgSrc, 'http') && !str_starts_with($imgSrc, 'data:')) {
                          $imgSrc = '../' . ltrim($imgSrc, '/');
                        }
                        $isLow = (int)$qm['Quantity'] <= 10 && (int)$qm['Quantity'] > 0;
                        $isOut = (int)$qm['Quantity'] <= 0;
                        ?>
                        <div class="pos-product-card <?= $isOut ? 'is-out-of-stock' : '' ?>"
                          tabindex="0"
                          role="button"
                          aria-label="<?= htmlspecialchars($fullName) ?>"
                          title="<?= htmlspecialchars($fullName) ?>"
                          onclick='window.mediFindPOS && window.mediFindPOS.addToCart(<?= json_encode($qm) ?>)'
                          onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault(); window.mediFindPOS && window.mediFindPOS.addToCart(<?= json_encode($qm) ?>);}">
                          <div class="pos-card-media">
                            <img src="<?= htmlspecialchars($imgSrc) ?>"
                              alt="<?= htmlspecialchars($fullName) ?>"
                              loading="lazy"
                              class="pos-card-img"
                              onerror="this.onerror=null; this.src='<?= $fallbackSvg ?>';">
                          </div>
                          <div class="pos-card-content">
                            <div class="pos-card-name text-truncate" title="<?= htmlspecialchars($qm['Generic_Name']) ?>">
                              <?= htmlspecialchars($qm['Generic_Name']) ?>
                            </div>
                            <div class="pos-card-sub text-truncate">
                              <span class="text-dark fw-semibold"><?= htmlspecialchars($qm['Brand_Name'] ?? 'Generic') ?></span>
                              <?php if ($dStr): ?>
                                <span class="text-muted">&bull; <?= htmlspecialchars($dStr) ?></span>
                              <?php endif; ?>
                              <?php if ($isRx): ?>
                                <span class="pos-rx-pill">Rx</span>
                              <?php endif; ?>
                            </div>
                          </div>
                          <div class="pos-card-meta">
                            <span class="pos-card-price">₱<?= number_format($qm['Price'], 2) ?></span>
                            <?php if ($isOut): ?>
                              <span class="pos-stock-chip out-stock">Out of stock</span>
                            <?php elseif ($isLow): ?>
                              <span class="pos-stock-chip low-stock">Stock: <?= $qm['Quantity'] ?></span>
                            <?php else: ?>
                              <span class="pos-stock-chip available">Stock: <?= $qm['Quantity'] ?></span>
                            <?php endif; ?>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <div class="pos-empty-grid text-center py-4 text-muted small">
                        No stock currently available. Add stock from "Manage Inventory".
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- ══ PRINTABLE RECEIPT MODAL ═══════════════════════════════ -->
  <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden;">
        <div class="modal-header" style="background: #1d9e75; color: #fff;">
          <h5 class="modal-title d-flex align-items-center gap-2" id="receiptModalLabel">
            <span class="material-symbols-outlined">print</span>
            Official Receipt Preview
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4 bg-light">
          <!-- Receipt Area for Thermal Print -->
          <div id="receiptContentArea"></div>
        </div>
        <div class="modal-footer bg-white">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success px-4" onclick="window.print()"
            style="background: #1d9e75 !important; border-color: #1d9e75 !important;">
            <i class="bi bi-printer me-1"></i> Print Receipt
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Esc Double-Press Toast -->
  <div id="posEscToast" class="pos-esc-toast" role="status" aria-live="polite">
    <span class="material-symbols-outlined" style="font-size:1.05rem;color:#34d399;">info</span>
    <span>Press <strong>Esc</strong> again to continue</span>
  </div>

  <!-- POS JavaScript Module -->
  <script type="module" src="../07_Assets/js/pos.js?v=<?= time() ?>"></script>
</body>

</html>