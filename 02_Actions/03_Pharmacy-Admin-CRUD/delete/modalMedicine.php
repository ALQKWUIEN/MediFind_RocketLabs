<!-- ============================================================
     MODAL — Add / Edit / View Medicine
     Requires: dropdown_data.php endpoint
     Place this modal HTML anywhere inside <body>
     ============================================================ -->

<div class="modal fade" id="medicineModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add Medicine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="container">
          <div class="row g-3">

            <!-- LEFT COLUMN -->
            <div class="col-md-6">
              <input type="hidden" id="medicineId">

              <!-- Generic Name -->
              <div class="mb-3 autocomplete-wrapper">
                <label class="form-label">Generic Name</label>
                <input type="hidden" id="genericNameId">
                <input type="text" id="genericName" class="form-control autocomplete-input"
                  placeholder="e.g. Amoxicillin" autocomplete="off"
                  data-type="generic_names" data-id-field="genericNameId">
                <ul class="autocomplete-list" id="genericName-list"></ul>
              </div>

              <!-- Brand Name -->
              <div class="mb-3 autocomplete-wrapper">
                <label class="form-label">Brand</label>
                <input type="hidden" id="brandId">
                <input type="text" id="brand" class="form-control autocomplete-input"
                  placeholder="e.g. Amoxil" autocomplete="off"
                  data-type="brand_names" data-id-field="brandId">
                <ul class="autocomplete-list" id="brand-list"></ul>
              </div>

              <!-- Category -->
              <div class="mb-3 autocomplete-wrapper">
                <label class="form-label">Category</label>
                <input type="hidden" id="categoryId">
                <input type="text" id="category" class="form-control autocomplete-input"
                  placeholder="e.g. Antibiotic" autocomplete="off"
                  data-type="categories" data-id-field="categoryId">
                <ul class="autocomplete-list" id="category-list"></ul>
              </div>

              <!-- Dosage Form -->
              <div class="mb-3 autocomplete-wrapper">
                <label class="form-label">Dosage Form</label>
                <input type="hidden" id="dosageFormId">
                <input type="text" id="dosageForm" class="form-control autocomplete-input"
                  placeholder="e.g. Tablet" autocomplete="off"
                  data-type="dosage_forms" data-id-field="dosageFormId">
                <ul class="autocomplete-list" id="dosageForm-list"></ul>
              </div>

            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-6">

              <!-- Strength = Dosage Value + Unit (two autocomplete side by side) -->
              <div class="mb-3">
                <label class="form-label">Strength</label>
                <div class="d-flex gap-2">

                  <div class="autocomplete-wrapper flex-grow-1">
                    <input type="hidden" id="dosageValueId">
                    <input type="text" id="dosageValue" class="form-control autocomplete-input"
                      placeholder="e.g. 500" autocomplete="off"
                      data-type="dosage_values" data-id-field="dosageValueId">
                    <ul class="autocomplete-list" id="dosageValue-list"></ul>
                  </div>

                  <div class="autocomplete-wrapper" style="width: 100px;">
                    <input type="hidden" id="dosageUnitId">
                    <input type="text" id="dosageUnit" class="form-control autocomplete-input"
                      placeholder="mg" autocomplete="off"
                      data-type="dosage_units" data-id-field="dosageUnitId">
                    <ul class="autocomplete-list" id="dosageUnit-list"></ul>
                  </div>

                </div>
              </div>

              <!-- Unit Price -->
              <div class="mb-3">
                <label class="form-label">Unit Price (₱)</label>
                <div class="d-flex gap-2">
                  <input type="number" id="unitPrice" class="form-control" step="0.01" min="0" placeholder="0.00">

                  <div class="autocomplete-wrapper" style="width: 110px;">
                    <input type="hidden" id="priceUnitId">
                    <input type="text" id="priceUnit" class="form-control autocomplete-input"
                      placeholder="pcs" autocomplete="off"
                      data-type="price_units" data-id-field="priceUnitId">
                    <ul class="autocomplete-list" id="priceUnit-list"></ul>
                  </div>
                </div>
              </div>

              <!-- Quantity -->
              <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" id="qty" class="form-control" min="0" placeholder="0">
              </div>

              <!-- Expiry Date -->
              <div class="mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="date" id="expiryDate" class="form-control">
              </div>

              <!-- Status (auto-managed) -->
              <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" id="status" class="form-control" readonly
                  style="background:#f9fafb; color:#6b7280;">
                <small class="text-muted">Auto-set based on quantity &amp; expiry date</small>
              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="saveBtn">Save</button>
      </div>

    </div>
  </div>
</div>


<!-- ══ AUTOCOMPLETE STYLES ══════════════════════════════════ -->
<style>
  .autocomplete-wrapper {
    position: relative;
  }

  .autocomplete-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1055; /* above modal */
    background: #fff;
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 8px 8px;
    max-height: 180px;
    overflow-y: auto;
    list-style: none;
    padding: 0;
    margin: 0;
    display: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  }

  .autocomplete-list li {
    padding: 0.5rem 0.85rem;
    cursor: pointer;
    font-size: 14px;
    color: #374151;
    transition: background 0.15s;
  }

  .autocomplete-list li:hover,
  .autocomplete-list li.active {
    background: #f0faf6;
    color: #1d9e75;
  }

  .autocomplete-list li.no-match {
    color: #9ca3af;
    cursor: default;
    font-style: italic;
  }

  .autocomplete-list li.no-match:hover {
    background: transparent;
    color: #9ca3af;
  }
</style>


<!-- ══ AUTOCOMPLETE SCRIPT ══════════════════════════════════ -->
<script>
  // ── Load all dropdown data once on page load ───────────────
  let dropdownData = {};

  fetch('../02_Actions/03_Pharmacy-Admin-CRUD/dropdownData.php?type=all')
    .then(res => res.json())
    .then(json => {
      if (json.success) {
        dropdownData = json.data;
      }
    })
    .catch(err => console.error('Dropdown load failed:', err));


  // ── Wire up all autocomplete inputs ───────────────────────
  $(document).on('input', '.autocomplete-input', function () {
    const $input   = $(this);
    const type     = $input.data('type');         // e.g. "categories"
    const idField  = $input.data('id-field');     // e.g. "categoryId"
    const query    = $input.val().toLowerCase().trim();
    const listId   = $input.attr('id') + '-list';
    const $list    = $('#' + listId);

    // Clear hidden ID when user types manually
    $('#' + idField).val('');

    if (!query) {
      $list.hide();
      return;
    }

    const items = dropdownData[type] || [];
    const matches = items.filter(item =>
      item.label.toLowerCase().includes(query)
    );

    $list.empty();

    if (matches.length === 0) {
      $list.append('<li class="no-match">No matches — will be added as new</li>');
    } else {
      matches.forEach(item => {
        $list.append(
          $('<li>')
            .text(item.label)
            .attr('data-id', item.id)
            .attr('data-label', item.label)
        );
      });
    }

    $list.show();
  });


  // ── Select item from dropdown list ────────────────────────
  $(document).on('click', '.autocomplete-list li:not(.no-match)', function () {
    const $li     = $(this);
    const $list   = $li.closest('ul');
    const inputId = $list.attr('id').replace('-list', '');
    const $input  = $('#' + inputId);
    const idField = $input.data('id-field');

    $input.val($li.data('label'));
    $('#' + idField).val($li.data('id'));
    $list.hide();
  });


  // ── Hide list when clicking outside ───────────────────────
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.autocomplete-wrapper').length) {
      $('.autocomplete-list').hide();
    }
  });


  // ── Keyboard navigation ────────────────────────────────────
  $(document).on('keydown', '.autocomplete-input', function (e) {
    const listId = $(this).attr('id') + '-list';
    const $list  = $('#' + listId);
    const $items = $list.find('li:not(.no-match)');
    const $active = $list.find('li.active');

    if (!$list.is(':visible')) return;

    if (e.key === 'ArrowDown') {
      e.preventDefault();
      if (!$active.length) {
        $items.first().addClass('active');
      } else {
        $active.removeClass('active').next('li').addClass('active');
      }
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      $active.removeClass('active').prev('li').addClass('active');
    } else if (e.key === 'Enter') {
      e.preventDefault();
      if ($active.length) $active.trigger('click');
    } else if (e.key === 'Escape') {
      $list.hide();
    }
  });


  // ── Auto-set status from qty & expiry ─────────────────────
  function autoStatus() {
    const qty    = parseInt($('#qty').val()) || 0;
    const expiry = $('#expiryDate').val();
    const today  = new Date().toISOString().split('T')[0];
    let status   = 'Available';

    if (expiry && expiry < today) {
      status = 'Expired';
    } else if (qty === 0) {
      status = 'Out of Stock';
    } else if (qty <= 10) {
      status = 'Low Stock';
    }

    $('#status').val(status);
  }

  $('#qty, #expiryDate').on('input change', autoStatus);


  // ── Reset modal ────────────────────────────────────────────
  function resetModal() {
    // Clear text inputs
    $('#genericName, #brand, #category, #dosageForm').val('');
    $('#dosageValue, #dosageUnit, #priceUnit').val('');
    $('#unitPrice, #qty').val('');
    $('#expiryDate').val('');
    $('#status').val('Available');

    // Clear hidden ID fields
    $('#medicineId, #genericNameId, #brandId, #categoryId').val('');
    $('#dosageFormId, #dosageValueId, #dosageUnitId, #priceUnitId').val('');

    // Hide all lists
    $('.autocomplete-list').hide();
  }


  // ── Populate modal from data-* attributes (view/edit) ─────
  function populateModal($el) {
    $('#medicineId').val($el.data('id'));
    $('#genericName').val($el.data('generic'));
    $('#brand').val($el.data('brand'));
    $('#category').val($el.data('category'));
    $('#dosageForm').val($el.data('dosageform'));
    $('#dosageValue').val($el.data('dosagevalue'));
    $('#dosageUnit').val($el.data('dosageunit'));
    $('#unitPrice').val($el.data('price'));
    $('#priceUnit').val($el.data('priceunit'));
    $('#qty').val($el.data('qty'));
    $('#expiryDate').val($el.data('expiry'));
    $('#status').val($el.data('status'));
  }


  // ── Re-enable inputs on modal close ───────────────────────
  document.getElementById('medicineModal').addEventListener('hidden.bs.modal', function () {
    $('#medicineModal input').prop('disabled', false);
    $('#saveBtn').show();
    $('.autocomplete-list').hide();
  });


  // ── OPEN: ADD ─────────────────────────────────────────────
  $('#addBTN').on('click', function () {
    $('#modalTitle').text('Add Medicine');
    resetModal();
    $('#medicineModal input').prop('disabled', false);
    medicineModal.show();
  });


  // ── OPEN: VIEW (read-only) ────────────────────────────────
  $(document).on('click', '.view-btn', function () {
    $('#modalTitle').text('View Medicine');
    populateModal($(this));
    $('#medicineModal input').prop('disabled', true);
    $('#saveBtn').hide();
    medicineModal.show();
  });


  // ── OPEN: EDIT ────────────────────────────────────────────
  $(document).on('click', '.edit-btn', function () {
    $('#modalTitle').text('Edit Medicine');
    populateModal($(this));
    // Only inventory-level fields are editable
    $('#medicineModal input').prop('disabled', true);
    $('#unitPrice, #qty, #expiryDate').prop('disabled', false);
    $('#saveBtn').show();
    medicineModal.show();
  });


  // ── SAVE ─────────────────────────────────────────────────
  $('#saveBtn').on('click', function () {
    const id = $('#medicineId').val();

    const data = {
      action:          id ? 'update' : 'add',
      id:              id,
      generic_name:    $('#genericName').val(),
      generic_name_id: $('#genericNameId').val(),
      brand:           $('#brand').val(),
      brand_id:        $('#brandId').val(),
      category:        $('#category').val(),
      category_id:     $('#categoryId').val(),
      dosage_form:     $('#dosageForm').val(),
      dosage_form_id:  $('#dosageFormId').val(),
      dosage_value:    $('#dosageValue').val(),
      dosage_value_id: $('#dosageValueId').val(),
      dosage_unit:     $('#dosageUnit').val(),
      dosage_unit_id:  $('#dosageUnitId').val(),
      unit_price:      $('#unitPrice').val(),
      price_unit:      $('#priceUnit').val(),
      price_unit_id:   $('#priceUnitId').val(),
      qty:             $('#qty').val(),
      expiry_date:     $('#expiryDate').val(),
      status:          $('#status').val()
    };

    $.post('../../02_Actions/03_Pharmacy-Admin-CRUD/medicine_actions.php', data, function (response) {
      if (response.trim() === 'success') {
        location.reload();
      } else {
        alert('Something went wrong: ' + response);
      }
    });
  });
</script>