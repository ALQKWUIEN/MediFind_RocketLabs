<?php require_once '../02_Actions/03_Pharmacy-Admin-CRUD/fetch-dropdown-meds.php'; ?>

<!-- ══ ADD / EDIT MEDICINE MODAL ══════════════════════════════════ -->
<div class="modal fade" id="medicineModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 1140px;">
    <div class="modal-content">

      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-semibold" id="modalTitle">Add New Stock</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body px-4 py-3">
        <input type="hidden" id="inventoryId">

        <!-- ── MEDICINE INFORMATION ──────────────────────── -->
        <p class="text-muted mb-1 mt-2"
        style="font-size:14px; font-weight:; letter-spacing:.06em; text-transform:uppercase;">
          Medicine Information
        </p>
        <hr class="mt-0 mb-3">

        <div class="row g-3">

          <!-- ── IMAGE BLOCK ────────────────────────────── -->
          <div class="col-md-12">
            <label class="form-label">Medicine Image</label>

            <!-- VIEW MODE: show image only -->
            <div id="imageViewBlock" style="display:none;">
              <img id="imageViewDisplay" src="" alt="Medicine Image"
                style="height:120px; width:120px; object-fit:cover; border-radius:10px; border:1px solid #dee2e6;">
              <div id="imageViewNone" class="d-flex align-items-center justify-content-center"
                style="height:120px; width:120px; border-radius:10px; border:2px dashed #dee2e6; background:#f8f9fa; display:none !important;">
                <span class="text-muted" style="font-size:12px;">No Image</span>
              </div>
            </div>

            <!-- ADD / EDIT MODE: show upload + preview -->
            <div id="imageEditBlock">
              <!-- Preview of existing image (edit mode) -->
              <div id="imageExistingWrap" style="display:none; margin-bottom:10px;">
                <p class="text-muted mb-1" style="font-size:11px;">Current Image:</p>
                <img id="imageExistingPreview" src="" alt=""
                  style="height:100px; width:100px; object-fit:cover; border-radius:8px; border:1px solid #dee2e6;">
              </div>
              <!-- File input -->
              <input type="file" id="medicineImage" class="form-control" accept="image/*"
                style="max-width:340px;">
              <!-- New file preview -->
              <div class="mt-2">
                <img id="imagePreview" src="" alt=""
                  style="max-height:100px; display:none; border-radius:8px; object-fit:cover;">
              </div>
            </div>
          </div>

          <!-- Generic Name -->
          <div class="col-md-6">
            <label class="form-label">
              Generic Name <span class="text-danger">*</span>
            </label>
            <input type="text" id="genericName" class="form-control" list="genericList"
              placeholder="e.g. Amoxicillin" autocomplete="off">
            <datalist id="genericList">
              <?php foreach ($generics as $g): ?>
                <option value="<?= htmlspecialchars($g['Generic_Name']) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>

          <!-- Brand Name -->
          <div class="col-md-6">
            <label class="form-label">
              Brand Name <span class="text-danger">*</span>
            </label>
            <input type="text" id="brandName" class="form-control" list="brandList"
              placeholder="e.g. Amoxil" autocomplete="off">
            <datalist id="brandList">
              <?php foreach ($brands as $b): ?>
                <option value="<?= htmlspecialchars($b['Brand_Name']) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>

          <!-- Category -->
          <div class="col-md-4">
            <label class="form-label">
              Category <span class="text-danger">*</span>
            </label>
            <input type="text" id="categoryName" class="form-control" list="categoryList"
              placeholder="e.g. Antibiotic" autocomplete="off">
            <datalist id="categoryList">
              <?php foreach ($categories as $c): ?>
                <option value="<?= htmlspecialchars($c['Category_Name']) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>

          <!-- Manufacturer -->
          <div class="col-md-4">
            <label class="form-label d-flex justify-content-between align-items-center">
              <span>Manufacturer</span>
              <span class="text-muted fw-normal" style="font-size:11px;">(Optional)</span>
            </label>
            <input type="text" id="manufacturerName" class="form-control" list="manufacturerList"
              placeholder="e.g. Unilab (Optional)" autocomplete="off">
            <datalist id="manufacturerList">
              <?php foreach ($manufacturers as $m): ?>
                <option value="<?= htmlspecialchars($m['Manufacturer_Name']) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>

          <!-- Requires Prescription -->
          <div class="col-md-2">
            <label class="form-label">
              Requires Rx <span class="text-danger">*</span>
            </label>
            <select id="requiresRx" class="form-select">
              <?php foreach ($rxOptions as $rx): ?>
                <option value="<?= htmlspecialchars($rx['Requirement_Status']) ?>">
                  <?= htmlspecialchars($rx['Requirement_Status']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Drug Classification (PNF Essential) -->
          <div class="col-md-2">
            <label class="form-label d-flex justify-content-between align-items-center">
              <span>Formulary</span>
              <span class="text-muted fw-normal" style="font-size:11px;">(PNF)</span>
            </label>
            <select id="isEssential" class="form-select">
              <option value="1">Essential</option>
              <option value="0">Non-Essential</option>
            </select>
          </div>

          <!-- Dosage Form -->
          <div class="col-md-3">
            <label class="form-label">
              Dosage Form <span class="text-danger">*</span>
            </label>
            <select id="dosageForm" class="form-select">
              <option value="">-- Select Form --</option>
              <?php foreach ($dosageForms as $df): ?>
                <option value="<?= htmlspecialchars($df['Dosage_Form']) ?>">
                  <?= htmlspecialchars($df['Dosage_Form']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Dosage Value -->
          <div class="col-md-2">
            <label class="form-label">
              Dosage Value <span class="text-danger">*</span>
            </label>
            <input type="text" id="dosageValue" class="form-control" list="dosageValueList"
              placeholder="e.g. 500" autocomplete="off">
            <datalist id="dosageValueList">
              <?php foreach ($dosageValues as $dv): ?>
                <option value="<?= htmlspecialchars($dv['Dosage_Value']) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>

          <!-- Dosage Unit -->
          <div class="col-md-2">
            <label class="form-label">
              Dosage Unit <span class="text-danger">*</span>
            </label>
            <select id="dosageUnit" class="form-select">
              <option value="">-- Unit --</option>
              <?php foreach ($dosageUnits as $du): ?>
                <option value="<?= htmlspecialchars($du['Unit_Name']) ?>">
                  <?= htmlspecialchars($du['Unit_Name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Package Size / Net Content -->
          <div class="col-md-3">
            <label class="form-label d-flex justify-content-between align-items-center">
              <span>Package Size / Volume</span>
              <span class="text-muted fw-normal" style="font-size:11px;">(Net Content)</span>
            </label>
            <input type="text" id="packageSize" class="form-control" list="packageSizeList"
              placeholder="e.g. 60 mL, 15 mL, 100's" autocomplete="off">
            <datalist id="packageSizeList">
              <option value="15 mL">
              <option value="30 mL">
              <option value="60 mL">
              <option value="120 mL">
              <option value="250 mL">
              <option value="5 g">
              <option value="10 g">
              <option value="15 g">
              <option value="10's">
              <option value="100's">
            </datalist>
          </div>

          <!-- Pieces Per Box (Unit conversion) -->
          <div class="col-md-2">
            <label class="form-label d-flex justify-content-between align-items-center">
              <span>Pcs / Box</span>
              <span class="text-muted fw-normal" style="font-size:11px;">(Packaging)</span>
            </label>
            <input type="number" id="piecesPerBox" class="form-control" min="1" value="100" placeholder="100">
          </div>

        </div>

        <!-- ── STOCK INFORMATION ─────────────────────────── -->
        <p class="text-muted mb-1 mt-4"
          style="font-size:14px; font-weight:; letter-spacing:.06em; text-transform:uppercase;">
          Stock Information
        </p>
        <hr class="mt-0 mb-3">

        <div class="row g-3">

          <!-- Batch / Lot Number -->
          <div class="col-md-3">
            <label class="form-label d-flex justify-content-between align-items-center">
              <span>Batch / Lot No.</span>
              <span class="text-muted fw-normal" style="font-size:11px;">(Tracing)</span>
            </label>
            <input type="text" id="batchNumber" class="form-control"
              placeholder="e.g. BATCH-2026-01" autocomplete="off">
          </div>

          <!-- Stock Received Qty & Unit Conversion -->
          <div class="col-md-3">
            <label class="form-label d-flex justify-content-between align-items-center">
              <span>Stock Received <span class="text-danger">*</span></span>
              <span id="stockConversionBadge" class="text-primary fw-semibold" style="font-size:11px;"></span>
            </label>
            <div class="input-group">
              <input type="number" id="qty" class="form-control" min="0" value="0">
              <select id="stockInUnit" class="form-select" style="max-width: 105px; font-size: 13px;">
                <option value="pieces">Pcs/Tabs</option>
                <option value="boxes">Boxes</option>
              </select>
            </div>
            <small id="stockConversionText" class="text-muted d-block mt-1" style="font-size: 11px;"></small>
          </div>

          <!-- Price -->
          <div class="col-md-2">
            <label class="form-label">
              Price <span class="text-danger">*</span>
            </label>
            <div class="input-group">
              <span class="input-group-text">₱</span>
              <input type="number" id="unitPrice" class="form-control" step="0.01" min="0" value="0.00">
            </div>
          </div>

          <!-- Price Per -->
          <div class="col-md-2">
            <label class="form-label">
              Price Per <span class="text-danger">*</span>
            </label>
            <select id="pricePer" class="form-select">
              <?php foreach ($priceUnits as $pu): ?>
                <option value="<?= htmlspecialchars($pu['Price_Unit_ID']) ?>">
                  <?= htmlspecialchars($pu['Unit']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Expiry Date -->
          <div class="col-md-2">
            <label class="form-label">
              Expiry Date <span class="text-danger">*</span>
            </label>
            <input type="date" id="expiryDate" class="form-control">
          </div>

          <!-- Low Stock Threshold -->
          <div class="col-md-3">
            <label class="form-label d-flex justify-content-between align-items-center">
              <span>Low Stock Alert at</span>
              <span class="text-muted fw-normal" style="font-size:11px;">(Reorder Level)</span>
            </label>
            <input type="number" id="lowStockThreshold" class="form-control" min="1" value="10" placeholder="10">
          </div>

          <!-- Status (auto) -->
          <div class="col-md-3">
            <label class="form-label">Availability Status</label>
            <input type="text" id="statusDisplay" class="form-control" disabled
              placeholder="Auto-calculated" style="background:#f8f9fa;">
          </div>

        </div>
      </div><!-- end modal-body -->

      <div class="modal-footer border-top">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="saveBtn"
          style="background-color:#1d9e75 !important; border-color:#1d9e75 !important;">
          Save
        </button>
      </div>

    </div>
  </div>
</div>
<!-- ══ END MODAL ══════════════════════════════════════════════════ -->


<!-- ══ SCRIPTS ═══════════════════════════════════════════════════ -->
<script>
  const getModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('medicineModal'));

  const medFields   = ['#genericName','#brandName','#categoryName',
                       '#manufacturerName','#isEssential','#dosageForm','#dosageValue',
                       '#dosageUnit','#packageSize','#piecesPerBox','#requiresRx'];
  const stockFields = ['#batchNumber','#qty','#stockInUnit','#unitPrice','#pricePer','#expiryDate','#lowStockThreshold'];

  // ── Stock In Unit Conversion Helper ─────────────────────────
  function updateStockConversion() {
    const qtyVal = parseInt($('#qty').val()) || 0;
    const unit = $('#stockInUnit').val();
    const pcsPerBox = parseInt($('#piecesPerBox').val()) || 1;

    if (unit === 'boxes' && pcsPerBox > 1) {
      const totalPieces = qtyVal * pcsPerBox;
      $('#stockConversionBadge').text(`= ${totalPieces.toLocaleString()} pcs`).show();
      $('#stockConversionText').html(`<i class="bi bi-box-arrow-in-down me-1 text-primary"></i> ${qtyVal} boxes × ${pcsPerBox} pcs/box = <strong>${totalPieces.toLocaleString()} total dispensing units</strong> in stock.`).show();
    } else {
      $('#stockConversionBadge').text('').hide();
      $('#stockConversionText').text('').hide();
    }
  }

  $('#qty, #stockInUnit, #piecesPerBox').on('input change', function () {
    updateStockConversion();
    autoStatus();
  });

  // ── Dynamic Formulation Suggestion (Drops vs Syrup vs Solid) ──
  function applyDosageFormDefaults(form) {
    form = (form || '').trim().toLowerCase();
    if (!form) return;

    let targetUnit = '';
    let valPlaceholder = 'e.g. 500';
    let pkgPlaceholder = '';

    if (form.includes('syrup') || form.includes('suspension')) {
      targetUnit = 'mg/5mL';
      valPlaceholder = 'e.g. 120 or 250';
      pkgPlaceholder = 'e.g. 60 mL or 120 mL';
      $('#piecesPerBox').val(1);
    } else if (form.includes('drop')) {
      targetUnit = 'mg/mL';
      valPlaceholder = 'e.g. 100 or 15';
      pkgPlaceholder = 'e.g. 15 mL dropper bottle';
      $('#piecesPerBox').val(1);
    } else if (form.includes('ointment') || form.includes('cream') || form.includes('gel')) {
      targetUnit = '%';
      valPlaceholder = 'e.g. 1 or 2';
      pkgPlaceholder = 'e.g. 5 g or 10 g tube';
      $('#piecesPerBox').val(1);
    } else if (form.includes('solution')) {
      targetUnit = '%';
      valPlaceholder = 'e.g. 1';
      pkgPlaceholder = 'e.g. 120 mL bottle';
      $('#piecesPerBox').val(1);
    } else if (form.includes('inhaler')) {
      targetUnit = 'mcg';
      valPlaceholder = 'e.g. 100 or 200';
      pkgPlaceholder = 'e.g. 200 doses';
      $('#piecesPerBox').val(1);
    } else if (form.includes('injection')) {
      targetUnit = 'mg/mL';
      valPlaceholder = 'e.g. 50';
      pkgPlaceholder = 'e.g. 10 mL vial';
      $('#piecesPerBox').val(1);
    } else if (form.includes('tablet') || form.includes('capsule')) {
      targetUnit = 'mg';
      valPlaceholder = 'e.g. 500 or 250';
      pkgPlaceholder = 'e.g. 100 tabs / box';
      if (!$('#piecesPerBox').val() || $('#piecesPerBox').val() === '1') {
        $('#piecesPerBox').val(100);
      }
    }

    if (targetUnit) {
      const $u = $('#dosageUnit');
      $u.val(targetUnit);
      // Fallback matching in case of slight casing mismatch
      if (!$u.val()) {
        $u.find('option').each(function () {
          if ($(this).val().trim().toLowerCase() === targetUnit.toLowerCase()) {
            $(this).prop('selected', true);
            return false;
          }
        });
      }
    }
    $('#dosageValue').attr('placeholder', valPlaceholder);
    if (!$('#packageSize').val()) {
      $('#packageSize').attr('placeholder', pkgPlaceholder);
    }
    updateStockConversion();
  }

  $(document).on('change input', '#dosageForm', function () {
    applyDosageFormDefaults($(this).val());
  });

  // ── Image mode helpers ──────────────────────────────────────
  function showImageViewMode(url) {
    $('#imageEditBlock').hide();
    $('#imageViewBlock').show();
    if (url) {
      $('#imageViewDisplay').attr('src', url).show();
      $('#imageViewNone').hide();
    } else {
      $('#imageViewDisplay').hide();
      $('#imageViewNone').show();
    }
  }

  function showImageEditMode(existingUrl) {
    $('#imageViewBlock').hide();
    $('#imageEditBlock').show();
    $('#medicineImage').val('');         // clear file input
    $('#imagePreview').hide().attr('src','');
    if (existingUrl) {
      $('#imageExistingPreview').attr('src', existingUrl);
      $('#imageExistingWrap').show();
    } else {
      $('#imageExistingWrap').hide();
    }
  }

  // ── Live preview when user picks a new file ─────────────────
  $('#medicineImage').on('change', function () {
    const file = this.files[0];
    if (!file) { $('#imagePreview').hide(); return; }
    const reader = new FileReader();
    reader.onload = e => $('#imagePreview').attr('src', e.target.result).show();
    reader.readAsDataURL(file);
  });

  // ── Auto status ─────────────────────────────────────────────
  function autoStatus() {
    const qtyVal      = parseInt($('#qty').val()) || 0;
    const stockUnit   = $('#stockInUnit').val();
    const pcsPerBox   = parseInt($('#piecesPerBox').val()) || 1;
    const effQty      = (stockUnit === 'boxes' && pcsPerBox > 1) ? (qtyVal * pcsPerBox) : qtyVal;
    const threshold   = parseInt($('#lowStockThreshold').val()) || 10;
    const expiry      = $('#expiryDate').val();
    const today       = new Date().toISOString().split('T')[0];
    let status        = 'Available';
    if (expiry && expiry < today) status = 'Expired';
    else if (effQty === 0)         status = 'Out of Stock';
    else if (effQty <= threshold)  status = 'Low Stock';
    $('#statusDisplay').val(status);
  }
  $('#qty, #stockInUnit, #piecesPerBox, #expiryDate, #lowStockThreshold').on('input change', autoStatus);

  // ── Lock fields ─────────────────────────────────────────────
  function lockFields(fields, lock) {
    fields.forEach(f => $(f).prop('disabled', lock));
  }

  // ── Reset ───────────────────────────────────────────────────
  function resetModal() {
    $('#inventoryId').val('');
    [...medFields, ...stockFields].forEach(f => $(f).val(''));
    $('#batchNumber').val('');
    $('#lowStockThreshold').val('10');
    $('#dosageUnit').val('');
    $('#isEssential').val('1');
    $('#piecesPerBox').val('100');
    $('#stockInUnit').val('pieces');
    $('#requiresRx').val('Yes');
    $('#pricePer').val('1');
    $('#qty').val('0');
    $('#unitPrice').val('0.00');
    $('#statusDisplay').val('');
    updateStockConversion();
    showImageEditMode('');   // default to edit/add mode with no image
  }

  // ── Populate from data-* ────────────────────────────────────
  function populateModal($el) {
    const dosage     = ($el.data('dosage') ?? '').split(' ');
    const dosageVal  = dosage[0] ?? '';
    const dosageUnit = dosage[1] ?? '';

    $('#inventoryId').val($el.data('id'));
    $('#genericName').val($el.data('generic'));
    $('#brandName').val($el.data('brand'));
    $('#categoryName').val($el.data('category'));
    $('#manufacturerName').val($el.data('manufacturer') ?? '');
    $('#isEssential').val($el.data('isessential') !== undefined ? String($el.data('isessential')) : '1');
    $('#dosageForm').val($el.data('dosageform'));
    $('#dosageValue').val(dosageVal);
    $('#dosageUnit').val(dosageUnit);
    $('#packageSize').val($el.data('packagesize') ?? '');
    $('#piecesPerBox').val($el.data('piecesperbox') || '100');
    $('#batchNumber').val($el.data('batch') ?? '');
    $('#unitPrice').val($el.data('price'));
    $('#qty').val($el.data('qty'));
    $('#stockInUnit').val('pieces');
    $('#expiryDate').val($el.data('expiry'));
    $('#lowStockThreshold').val($el.data('lowstock') ?? '10');
    $('#statusDisplay').val($el.data('status'));
    $('#pricePer').val($el.data('priceper') === 'box' ? '2' : '1');
    updateStockConversion();

    return $el.data('image') ?? '';   // return image URL for caller to handle
  }

  // ── Re-enable on close ──────────────────────────────────────
  document.getElementById('medicineModal').addEventListener('hidden.bs.modal', function () {
    lockFields(medFields, false);
    lockFields(stockFields, false);
    $('#statusDisplay').prop('disabled', true);
    $('#saveBtn').show();
    resetModal();
  });

  // ── OPEN: ADD ───────────────────────────────────────────────
  $('#addBTN').on('click', function () {
    $('#modalTitle').text('Add New Stock');
    resetModal();
    showImageEditMode('');
    lockFields(medFields, false);
    lockFields(stockFields, false);
    $('#statusDisplay').prop('disabled', true);
    $('#saveBtn').show();
    getModal().show();
  });

  // ── OPEN: VIEW ──────────────────────────────────────────────
  $(document).on('click', '.view-btn', function () {
    $('#modalTitle').text('View Medicine');
    const imageUrl = populateModal($(this));
    showImageViewMode(imageUrl);      // 👈 display only, no upload
    lockFields(medFields, true);
    lockFields(stockFields, true);
    $('#statusDisplay').prop('disabled', true);
    $('#saveBtn').hide();
    getModal().show();
  });

  // ── OPEN: EDIT ──────────────────────────────────────────────
  $(document).on('click', '.edit-btn', function () {
    $('#modalTitle').text('Edit Stock');
    const imageUrl = populateModal($(this));
    showImageEditMode(imageUrl);      // 👈 show existing + allow change
    lockFields(medFields, true);
    lockFields(stockFields, false);
    $('#statusDisplay').prop('disabled', true);
    $('#saveBtn').show();
    getModal().show();
  });

  // ── VIEW on row dblclick ────────────────────────────────────
  $(document).on('dblclick', 'tbody tr', function (e) {
    if ($(e.target).closest('td').hasClass('text-end')) return;
    $(this).find('.view-btn').trigger('click');
  });

  // ── Validate ────────────────────────────────────────────────
  function validateForm(isAdd) {
    const required = ['#qty', '#unitPrice', '#expiryDate'];
    if (isAdd) {
      required.push('#genericName','#brandName','#categoryName',
                    '#dosageForm','#dosageValue','#dosageUnit');
    }
    for (const f of required) {
      if (!$(f).val().toString().trim()) {
        showToast('Please fill in all required fields.', 'warning');
        $(f).focus();
        return false;
      }
    }
    return true;
  }

  // ── SAVE ────────────────────────────────────────────────────
  $('#saveBtn').off('click').on('click', function () {
    const inventoryId = $('#inventoryId').val();
    const isAdd = !inventoryId;
    if (!validateForm(isAdd)) return;

    const qtyVal      = parseInt($('#qty').val()) || 0;
    const stockUnit   = $('#stockInUnit').val();
    const pcsPerBox   = parseInt($('#piecesPerBox').val()) || 1;
    const finalQuantity = (stockUnit === 'boxes' && pcsPerBox > 1) ? (qtyVal * pcsPerBox) : qtyVal;

    const data = {
      action              : isAdd ? 'add' : 'update',
      id                  : inventoryId,
      generic_name        : $('#genericName').val().trim(),
      brand               : $('#brandName').val().trim(),
      category            : $('#categoryName').val().trim(),
      manufacturer        : $('#manufacturerName').val().trim(),
      is_essential        : $('#isEssential').val() || 1,
      dosage_form         : $('#dosageForm').val().trim(),
      dosage_value        : $('#dosageValue').val().trim(),
      dosage_unit         : $('#dosageUnit').val(),
      package_size        : $('#packageSize').val().trim(),
      pieces_per_box      : pcsPerBox,
      requires_rx         : $('#requiresRx').val(),
      unit_price          : $('#unitPrice').val(),
      price_unit_id       : $('#pricePer').val(),
      qty                 : finalQuantity,
      expiry_date         : $('#expiryDate').val(),
      batch_number        : $('#batchNumber').val().trim(),
      low_stock_threshold : $('#lowStockThreshold').val().trim() || 10,
    };

    $.post('../02_Actions/03_Pharmacy-Admin-CRUD/inventory-add-update.php', data, function (response) {
      console.log('RAW RESPONSE:', response);
      const res = response.trim();
      if (res === 'success') {
        getModal().hide();
        setTimeout(() => {
          showToast(isAdd ? 'Stock added successfully!' : 'Stock updated successfully!', 'success');
          setTimeout(() => location.reload(), 1500);
        }, 400);
      } else if (res === 'missing_fields') {
        showToast('Please fill in all required fields.', 'warning');
      } else {
        showToast('Something went wrong: ' + res, 'danger');
      }
    });
  });

  // ── DELETE ──────────────────────────────────────────────────
  $(document).on('click', '.delete-btn', function () {
    if (!confirm('Are you sure you want to remove this stock entry?')) return;
    $.post('../02_Actions/03_Pharmacy-Admin-CRUD/inventory-add-update.php', {
      action: 'delete',
      id: $(this).data('id')
    }, function (response) {
      const res = response.trim();
      if (res === 'success') {
        showToast('Stock entry deleted.', 'success');
        setTimeout(() => location.reload(), 1500);
      } else {
        showToast('Delete failed: ' + res, 'danger');
      }
    });
  });

  // ── SEARCH ──────────────────────────────────────────────────
  $('.searchbar').on('keyup', function () {
    const value = $(this).val().toLowerCase();
    $('tbody tr').each(function () {
      $(this).toggle($(this).text().toLowerCase().includes(value));
    });
  });

  // ── FILTER: STATUS ──────────────────────────────────────────
  $('#filterStatus').on('change', function () {
    const selected = $(this).val().toLowerCase();
    $('tbody tr').each(function () {
      const rowStatus = $(this).find('td:nth-child(11)').text().toLowerCase().trim();
      $(this).toggle(selected === '' || rowStatus.includes(selected));
    });
  });

  // ── FILTER: CATEGORY ────────────────────────────────────────
  $('#filterCategory').on('change', function () {
    const selected = $(this).val().toLowerCase();
    $('tbody tr').each(function () {
      const rowCat = $(this).find('td:nth-child(5)').text().toLowerCase().trim();
      $(this).toggle(selected === '' || rowCat === selected);
    });
  });

  // ── POPULATE CATEGORY FILTER ────────────────────────────────
  $(document).ready(function () {
    const categories = new Set();
    $('tbody tr td:nth-child(5)').each(function () {
      const cat = $(this).text().trim();
      if (cat) categories.add(cat);
    });
    categories.forEach(cat => {
      $('#filterCategory').append($('<option>', { value: cat.toLowerCase(), text: cat }));
    });
  });

  // ── SELECT ALL ──────────────────────────────────────────────
  $('#selectAll').on('change', function () {
    $('.row-check').prop('checked', $(this).is(':checked'));
  });

  // ── TOAST HELPER ────────────────────────────────────────────
  window.showToast = function (message, type = 'success') {
    const icons      = { success:'bi bi-check-circle-fill', danger:'bi bi-x-circle-fill', warning:'bi bi-exclamation-circle-fill', info:'bi bi-info-circle-fill' };
    const colors     = { success:'#d4f5e2', danger:'#fde8e8', warning:'#fff3cd', info:'#e6f1fb' };
    const textColors = { success:'#1a7a45', danger:'#c0392b', warning:'#856404', info:'#185FA5' };

    $('#appToast').remove();
    const toast = `
      <div id="appToast" class="toast align-items-center border-0 position-fixed top-0 end-0 m-3 shadow-sm"
        role="alert" style="z-index:9999; margin-top:20px !important; min-width:400px;
        background:${colors[type]}; border-radius:10px;">
        <div class="d-flex align-items-center px-3 py-2 gap-2">
          <i class="${icons[type]}" style="font-size:18px; color:${textColors[type]};"></i>
          <div class="toast-body p-0 fw-500" style="color:${textColors[type]}; font-size:14px;">${message}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast" style="filter:none; opacity:0.5;"></button>
        </div>
      </div>`;
    $('body').append(toast);
    const toastEl = new bootstrap.Toast(document.getElementById('appToast'), { delay: 3000 });
    toastEl.show();
    setTimeout(() => $('#appToast').remove(), 3500);
  };
</script>