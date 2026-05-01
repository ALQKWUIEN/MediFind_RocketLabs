<!-- ══ MODAL ══════════════════════════════════════════════════ -->
<div class="modal fade" id="medicineModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add Medicine</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="container">
          <div class="row">

            <!-- LEFT COLUMN -->
            <div class="col-md-6">
              <input type="hidden" id="medicineId">

              <div class="mb-3">
                <label class="form-label">Generic Name</label>
                <input type="text" id="genericName" class="form-control">
              </div>

              <div class="mb-3">
                <label class="form-label">Brand</label>
                <input type="text" id="brand" class="form-control">
              </div>

              <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" id="category" class="form-control">
              </div>

              <div class="mb-3">
                <label class="form-label">Dosage Form</label>
                <select id="dosageForm" class="form-select">
                  <option value="Tablet">Tablet</option>
                  <option value="Capsule">Capsule</option>
                  <option value="Syrup">Syrup</option>
                  <option value="Injection">Injection</option>
                </select>
              </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-6">

              <div class="mb-3">
                <label class="form-label">Strength</label>
                <input type="text" id="strength" class="form-control" placeholder="e.g. 500 mg">
              </div>

              <div class="mb-3">
                <label class="form-label">Unit Price</label>
                <input type="number" id="unitPrice" class="form-control" step="0.01" min="0">
              </div>

              <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" id="qty" class="form-control" min="0">
              </div>

              <div class="mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="date" id="expiryDate" class="form-control">
              </div>

              <div class="mb-3">
                <label class="form-label">Status</label>
                <select id="status" class="form-select">
                  <option value="Available">Available</option>
                  <option value="Low Stock">Low Stock</option>
                  <option value="Out of Stock">Out of Stock</option>
                </select>
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
<!-- ══ END MODAL ══════════════════════════════════════════════ -->

<!-- ══ SCRIPTS ══════════════════════════════════════════════ -->
<script>
  const medicineModal = new bootstrap.Modal(document.getElementById('medicineModal'));

  // ── Auto-set status from qty & expiry ──────────────────────
  function autoStatus() {
    const qty = parseInt($('#qty').val()) || 0;
    const expiry = $('#expiryDate').val();
    const today = new Date().toISOString().split('T')[0];
    let status = 'Available';

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

  // ── Helper: reset modal fields ─────────────────────────────
  function resetModal() {
    $('#medicineId').val('');
    $('#genericName, #brand, #category, #strength').val('');
    $('#dosageForm').val('Capsule');
    $('#unitPrice').val('');
    $('#qty').val('');
    $('#expiryDate').val('');
    $('#status').val('Available');
  }

  // ── Helper: populate modal from data-* attributes ──────────
  function populateModal($el) {
    $('#medicineId').val($el.data('id'));
    $('#genericName').val($el.data('generic'));
    $('#brand').val($el.data('brand'));
    $('#category').val($el.data('category'));
    $('#dosageForm').val($el.data('dosageform'));
    $('#strength').val($el.data('dosage'));        // changed from data-strength
    $('#unitPrice').val($el.data('price'));
    $('#qty').val($el.data('qty'));
    $('#expiryDate').val($el.data('expiry'));
    $('#status').val($el.data('status'));
  }
  // ── Re-enable inputs when modal closes ─────────────────────
  document.getElementById('medicineModal').addEventListener('hidden.bs.modal', function () {
    $('#medicineModal input, #medicineModal select').prop('disabled', false);
    // Keep status always disabled (auto-managed)
    $('#status').prop('disabled', true);
    $('#saveBtn').show();
  });

  // ── OPEN: ADD ──────────────────────────────────────────────
  $('#addBTN').on('click', function () {
    $('#modalTitle').text('Add Medicine');
    resetModal();
    // In add mode, generic/brand/category/dosage/strength are editable
    $('#genericName, #brand, #category, #dosageForm, #strength').prop('disabled', false);
    medicineModal.show();
  });

  // ── OPEN: VIEW (read-only) ─────────────────────────────────
  $(document).on('click', '.view-btn', function () {
    $('#modalTitle').text('View Medicine');
    populateModal($(this));
    $('#medicineModal input, #medicineModal select').prop('disabled', true);
    $('#saveBtn').hide();
    medicineModal.show();
  });

  // ── OPEN: EDIT ─────────────────────────────────────────────
  $(document).on('click', '.edit-btn', function () {
    $('#modalTitle').text('Edit Medicine');
    populateModal($(this));
    // Only qty, price, expiry are editable (master data stays read-only)
    $('#medicineModal input, #medicineModal select').prop('disabled', true);
    $('#unitPrice, #qty, #expiryDate').prop('disabled', false);
    $('#saveBtn').show();
    medicineModal.show();
  });

  // ── SAVE (ADD / UPDATE) ────────────────────────────────────
  $('#saveBtn').on('click', function () {
    const id = $('#medicineId').val();

    const data = {
      action: id ? 'update' : 'add',
      id: id,
      generic_name: $('#genericName').val(),
      brand: $('#brand').val(),
      category: $('#category').val(),
      dosage_form: $('#dosageForm').val(),
      strength: $('#strength').val(),
      unit_price: $('#unitPrice').val(),
      qty: $('#qty').val(),
      expiry_date: $('#expiryDate').val(),
      status: $('#status').val()
    };

    $.post('medicine_actions.php', data, function (response) {
      if (response.trim() === 'success') {
        location.reload();
      } else {
        alert('Something went wrong: ' + response);
      }
    });
  });

  // ── DELETE ─────────────────────────────────────────────────
  $(document).on('click', '.delete-btn', function () {
    if (!confirm('Are you sure you want to delete this medicine?')) return;

    $.post('medicine_actions.php', { action: 'delete', id: $(this).data('id') }, function (response) {
      if (response.trim() === 'success') {
        location.reload();
      } else {
        alert('Delete failed: ' + response);
      }
    });
  });

  // ── FILTER: STATUS ─────────────────────────────────────────
  // Status is column 11 (1-indexed), Last Updated is 12, Actions is 13
  $('#filterStatus').on('change', function () {
    const selected = $(this).val().toLowerCase();
    $('tbody tr').each(function () {
      const rowStatus = $(this).find('td:nth-child(11)').text().toLowerCase().trim();
      $(this).toggle(selected === '' || rowStatus === selected);
    });
  });

  // ── FILTER: CATEGORY ───────────────────────────────────────
  // Category is column 5
  $('#filterCategory').on('change', function () {
    const selected = $(this).val().toLowerCase();
    $('tbody tr').each(function () {
      const rowCat = $(this).find('td:nth-child(5)').text().toLowerCase().trim();
      $(this).toggle(selected === '' || rowCat === selected);
    });
  });

  // ── SEARCH ─────────────────────────────────────────────────
  $('.searchbar').on('keyup', function () {
    const value = $(this).val().toLowerCase();
    $('tbody tr').each(function () {
      $(this).toggle($(this).text().toLowerCase().includes(value));
    });
  });

  // ── POPULATE CATEGORY DROPDOWN from table rows ─────────────
  $(document).ready(function () {
    const categories = new Set();
    $('tbody tr td:nth-child(5)').each(function () {
      const cat = $(this).text().trim();
      if (cat) categories.add(cat);
    });
    categories.forEach(function (cat) {
      $('#filterCategory').append($('<option>', { value: cat.toLowerCase(), text: cat }));
    });
  });

  // ── SELECT ALL CHECKBOX ────────────────────────────────────
  $('#selectAll').on('change', function () {
    $('.row-check').prop('checked', $(this).is(':checked'));
  });

</script>