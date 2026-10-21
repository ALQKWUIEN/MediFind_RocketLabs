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
  LIMIT 12
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
  <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/theme-tokens.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/admin-buttons.css" />
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/01_dashboard.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/pos.css?v=<?= time() ?>" />

  <?php include '../01_Includes/page-transition-hardcode.php' ?>
</head>

<body data-active="pos">
  <div class="wrapper d-flex align-items-stretch">
    <div id="pharmacy-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div id="content" style="padding: 1.25rem;">
        <div class="content-body">

          <!-- Page Header -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <h4 class="fw-bold mb-0 admin-page-title">Point of Sale (POS) Counter</h4>
            </div>
            <div class="d-flex gap-2">
              <span class="badge bg-success-subtle text-success border border-success-subtle py-2 px-3 fw-semibold">
                <i class="bi bi-shop me-1"></i>
                <?= htmlspecialchars($_SESSION['pharmacy_name'] ?? 'Pharmacy Counter') ?>
              </span>
            </div>
          </div>

          <!-- POS Main Grid -->
          <div class="pos-grid">

            <!-- LEFT COLUMN: Search & Item Selection & Quick Pills -->
            <div class="pos-card">
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
                            <div class="pos-card-header">
                              <div class="pos-card-title text-truncate" title="<?= htmlspecialchars($qm['Generic_Name']) ?>">
                                <?= htmlspecialchars($qm['Generic_Name']) ?>
                              </div>
                              <div class="pos-card-brand-dosage text-truncate">
                                <?= htmlspecialchars($qm['Brand_Name'] ?? 'Generic') ?><?= $dStr ? ' &bull; ' . htmlspecialchars($dStr) : '' ?>
                                <?php if ($isRx): ?>
                                  <span class="pos-rx-pill">Rx</span>
                                <?php endif; ?>
                              </div>
                            </div>
                            <div class="pos-card-footer">
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

            <!-- RIGHT COLUMN: Counter Cart & Checkout Summary -->
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
                  <p class="small text-muted mb-0">Click medicine cards on the left or search to add items.</p>
                </div>

                <!-- Cart Table Wrap -->
                <div id="posCartTableWrap" style="display: none; max-height: 250px; overflow-y: auto;">
                  <table class="pos-cart-table">
                    <thead>
                      <tr>
                        <th>Medicine</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody id="posCartTbody"></tbody>
                  </table>
                </div>

                <!-- Discount Options -->
                <div class="discount-box">
                  <label class="form-label small fw-bold text-dark mb-1">Apply Discount:</label>
                  <div class="discount-options">
                    <button type="button" class="discount-btn active" data-type="None">None (0%)</button>
                    <button type="button" class="discount-btn" data-type="Senior/PWD">Senior / PWD (20%)</button>
                    <button type="button" class="discount-btn" data-type="5%">Special (5%)</button>
                  </div>
                </div>

                <!-- Totals Calculation -->
                <div class="pos-order-summary-box">
                  <div class="pos-summary-row">
                    <span>Subtotal:</span>
                    <span id="posSubtotal" class="fw-semibold">₱0.00</span>
                  </div>
                  <div class="pos-summary-row">
                    <span>Applied Discount:</span>
                    <span id="posDiscount" class="text-warning fw-semibold">-₱0.00</span>
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

  <!-- POS JavaScript Module -->
  <script type="module" src="../07_Assets/js/pos.js"></script>
</body>

</html>