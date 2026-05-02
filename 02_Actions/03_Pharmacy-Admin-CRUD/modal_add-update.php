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
          <div class="col-md-6">
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
          <div class="col-md-6">
            <label class="form-label">Manufacturer</label>
            <input type="text" id="manufacturerName" class="form-control" list="manufacturerList"
              placeholder="e.g. Unilab" autocomplete="off">
            <datalist id="manufacturerList">
              <?php foreach ($manufacturers as $m): ?>
                <option value="<?= htmlspecialchars($m['Manufacturer_Name']) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>

          <!-- Dosage Form -->
          <div class="col-md-3">
            <label class="form-label">
              Dosage Form <span class="text-danger">*</span>
            </label>
            <input type="text" id="dosageForm" class="form-control" list="dosageFormList"
              placeholder="e.g. Tablet" autocomplete="off">
            <datalist id="dosageFormList">
              <?php foreach ($dosageForms as $df): ?>
                <option value="<?= htmlspecialchars($df['Dosage_Form']) ?>">
              <?php endforeach; ?>
            </datalist>
          </div>

          <!-- Dosage Value -->
          <div class="col-md-3">
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
          <div class="col-md-3">
            <label class="form-label">
              Dosage Unit <span class="text-danger">*</span>
            </label>
            <select id="dosageUnit" class="form-select">
              <option value="">-- Select --</option>
              <?php foreach ($dosageUnits as $du): ?>
                <option value="<?= htmlspecialchars($du['Unit_Name']) ?>">
                  <?= htmlspecialchars($du['Unit_Name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Requires Prescription -->
          <div class="col-md-3">
            <label class="form-label">
              Requires Prescription <span class="text-danger">*</span>
            </label>
            <select id="requiresRx" class="form-select">
              <?php foreach ($rxOptions as $rx): ?>
                <option value="<?= htmlspecialchars($rx['Requirement_Status']) ?>">
                  <?= htmlspecialchars($rx['Requirement_Status']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

        </div>

        <!-- ── STOCK INFORMATION ─────────────────────────── -->
        <p class="text-muted mb-1 mt-4"
          style="font-size:14px; font-weight:; letter-spacing:.06em; text-transform:uppercase;">
          Stock Information
        </p>
        <hr class="mt-0 mb-3">

        <div class="row g-3">

          <!-- Quantity -->
          <div class="col-md-3">
            <label class="form-label">
              Quantity <span class="text-danger">*</span>
            </label>
            <input type="number" id="qty" class="form-control" min="0" value="0">
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

          <!-- Status (auto) -->
          <div class="col-md-3">
            <label class="form-label">Status</label>
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
                       '#manufacturerName','#dosageForm','#dosageValue',
                       '#dosageUnit','#requiresRx'];
  const stockFields = ['#qty','#unitPrice','#pricePer','#expiryDate'];

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
    const qty    = parseInt($('#qty').val()) || 0;
    const expiry = $('#expiryDate').val();
    const today  = new Date().toISOString().split('T')[0];
    let status   = 'Available';
    if (expiry && expiry < today)  status = 'Expired';
    else if (qty === 0)            status = 'Out of Stock';
    else if (qty <= 10)            status = 'Low Stock';
    $('#statusDisplay').val(status);
  }
  $('#qty, #expiryDate').on('input change', autoStatus);

  // ── Lock fields ─────────────────────────────────────────────
  function lockFields(fields, lock) {
    fields.forEach(f => $(f).prop('disabled', lock));
  }

  // ── Reset ───────────────────────────────────────────────────
  function resetModal() {
    $('#inventoryId').val('');
    [...medFields, ...stockFields].forEach(f => $(f).val(''));
    $('#dosageUnit').val('');
    $('#requiresRx').val('Yes');
    $('#pricePer').val('1');
    $('#qty').val('0');
    $('#unitPrice').val('0.00');
    $('#statusDisplay').val('');
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
    $('#dosageForm').val($el.data('dosageform'));
    $('#dosageValue').val(dosageVal);
    $('#dosageUnit').val(dosageUnit);
    $('#unitPrice').val($el.data('price'));
    $('#qty').val($el.data('qty'));
    $('#expiryDate').val($el.data('expiry'));
    $('#statusDisplay').val($el.data('status'));
    $('#pricePer').val($el.data('priceper') === 'box' ? '2' : '1');

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

    const data = {
      action       : isAdd ? 'add' : 'update',
      id           : inventoryId,
      generic_name : $('#genericName').val().trim(),
      brand        : $('#brandName').val().trim(),
      category     : $('#categoryName').val().trim(),
      manufacturer : $('#manufacturerName').val().trim(),
      dosage_form  : $('#dosageForm').val().trim(),
      dosage_value : $('#dosageValue').val().trim(),
      dosage_unit  : $('#dosageUnit').val(),
      requires_rx  : $('#requiresRx').val(),
      unit_price   : $('#unitPrice').val(),
      price_unit_id: $('#pricePer').val(),
      qty          : $('#qty').val(),
      expiry_date  : $('#expiryDate').val(),
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