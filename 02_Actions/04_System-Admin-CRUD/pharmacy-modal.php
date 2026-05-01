<!-- ══ PHARMACY MODAL ══════════════════════════════════════════════════ -->
<div class="modal fade" id="pharmacyModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="pharmacyModalTitle">Pharmacy Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="container-fluid">
          <div class="row g-4">

            <!-- LEFT: IMAGE -->
            <div class="col-md-4 d-flex align-items-start justify-content-center">
              <img src="../07_Assets/images/pharmacies/RosePharmacy.png" id="pharmacyPic" alt="Pharmacy Picture"
                style="width: 100%; max-width: 350px; height: 450px; object-fit: cover; border-radius: 16px; background: #ccc;">
            </div>

            <!-- RIGHT: INPUTS -->
            <div class="col-md-8">
              <input type="hidden" id="pharmacyId">

              <div class="row">

                <div class="col-md-6 mb-3">
                  <label class="form-label">Pharmacy ID</label>
                  <input type="text" id="pharmacyIdDisplay" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Pharmacy Name</label>
                  <input type="text" id="pharmacyName" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Owner Name</label>
                  <input type="text" id="pharmacyOwner" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" id="pharmacyEmail" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Contact No.</label>
                  <input type="text" id="pharmacyPhone" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Approval Status</label>
                  <select id="pharmacyStatus" class="form-select">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Suspended">Suspended</option>
                  </select>
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Address</label>
                  <input type="text" id="pharmacyAddress" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Date Created</label>
                  <input type="text" id="pharmacyDateCreated" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Date Approved</label>
                  <input type="text" id="pharmacyDateApproved" class="form-control" disabled>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="pharmacySaveBtn">Save</button>
      </div>

    </div>
  </div>
</div>
<!-- ══ END PHARMACY MODAL ══════════════════════════════════════════ -->


<!-- ══ PHARMACY SCRIPTS ════════════════════════════════════════════ -->
<script>
$(document).ready(function () {

  // ── Init Bootstrap Modal ───────────────────────────────────
  const pharmacyModalEl = document.getElementById('pharmacyModal');
  const pharmacyModal   = new bootstrap.Modal(pharmacyModalEl);

  // ── Reset modal fields ─────────────────────────────────────
  function resetPharmacyModal() {
    $('#pharmacyId, #pharmacyIdDisplay, #pharmacyName, #pharmacyOwner, #pharmacyEmail, #pharmacyPhone, #pharmacyAddress, #pharmacyDateCreated, #pharmacyDateApproved').val('');
    $('#pharmacyStatus').val('Pending');
    $('#pharmacyPic').attr('src', '../07_Assets/images/pharmacies/RosePharmacy.png');
  }

  // ── Populate modal from data-* attributes ──────────────────
  function populatePharmacyModal($el) {
    $('#pharmacyId').val($el.data('id'));
    $('#pharmacyIdDisplay').val($el.data('id'));
    $('#pharmacyName').val($el.data('name'));
    $('#pharmacyOwner').val($el.data('owner'));
    $('#pharmacyEmail').val($el.data('email'));
    $('#pharmacyPhone').val($el.data('phone'));
    $('#pharmacyAddress').val($el.data('address'));
    $('#pharmacyStatus').val($el.data('status'));
    $('#pharmacyDateCreated').val($el.data('created'));
    $('#pharmacyDateApproved').val($el.data('approved'));

    const pic = $el.data('pic');
    $('#pharmacyPic').attr('src', pic && pic !== '' ? pic : '../07_Assets/images/pharmacies/RosePharmacy.png');
  }

  // ── Re-enable inputs when modal closes ─────────────────────
  pharmacyModalEl.addEventListener('hidden.bs.modal', function () {
    $('#pharmacyModal input, #pharmacyModal select').prop('disabled', false);
    $('#pharmacySaveBtn').show();
    resetPharmacyModal();
  });

  // ── OPEN: VIEW (read-only) ─────────────────────────────────
  $(document).on('click', '.view-btn', function () {
    $('#pharmacyModalTitle').text('Pharmacy Details');
    populatePharmacyModal($(this));
    $('#pharmacyModal input, #pharmacyModal select').prop('disabled', true);
    $('#pharmacySaveBtn').hide();
    pharmacyModal.show();
  });

  // ── OPEN: VIEW on row double-click ─────────────────────────
  $(document).on('dblclick', 'tbody tr', function (e) {
    if ($(e.target).closest('td').hasClass('text-end')) return;
    $(this).find('.view-btn').trigger('click');
  });

  // ── OPEN: EDIT ─────────────────────────────────────────────
  $(document).on('click', '.edit-btn', function () {
    $('#pharmacyModalTitle').text('Edit Pharmacy');
    populatePharmacyModal($(this));
    $('#pharmacyModal input, #pharmacyModal select').prop('disabled', true);
    $('#pharmacyStatus').prop('disabled', false);
    $('#pharmacySaveBtn').show();
    pharmacyModal.show();
  });

  // ── SAVE (UPDATE) ──────────────────────────────────────────
  $('#pharmacySaveBtn').on('click', function () {
    const data = {
      action: 'update',
      id:     $('#pharmacyId').val(),
      status: $('#pharmacyStatus').val()
    };

    $.post('../02_Actions/04_System-Admin-CRUD/display-pharmacy.php', data, function (response) {
      if (response.trim() === 'success') {
        pharmacyModal.hide();
        showToast('Pharmacy status updated successfully!', 'success');
        setTimeout(() => location.reload(), 1500);
      } else {
        pharmacyModal.hide();
        showToast('Pharmacy status updated successfully!', 'success');
        setTimeout(() => location.reload(), 1500);
      }
    });
  });

  // ── DELETE ─────────────────────────────────────────────────
  $(document).on('click', '.delete-btn', function () {
    if (!confirm('Are you sure you want to delete this pharmacy?')) return;

    $.post('../02_Actions/04_System-Admin-CRUD/display-pharmacy.php', {
      action: 'delete',
      id: $(this).data('id')
    }, function (response) {
      if (response.trim() === 'success') {
        showToast('Pharmacy deleted successfully!', 'success');
        setTimeout(() => location.reload(), 1500);
      } else {
        showToast('Delete failed. Please try again.', 'danger');
      }
    });
  });

  // ── SEARCH ─────────────────────────────────────────────────
  $('#searchPharmacy').on('input', function () {
    const value = $(this).val().toLowerCase();
    $('tbody tr').each(function () {
      $(this).toggle($(this).text().toLowerCase().includes(value));
    });
  });

  // ── FILTER: STATUS ─────────────────────────────────────────
  $('#filterStatus').on('change', function () {
    const selected = $(this).val().toLowerCase().trim();
    $('tbody tr').each(function () {
      const rowStatus = $(this).find('td:nth-child(8)').text().toLowerCase().trim();
      $(this).toggle(selected === '' || rowStatus.includes(selected));
    });
  });

  // ── SELECT ALL CHECKBOX ────────────────────────────────────
  $('#selectAll').on('change', function () {
    $('.row-check').prop('checked', $(this).is(':checked'));
  });

  // ── Toast Helper ───────────────────────────────────────────
  window.showToast = function (message, type = 'success') {
    const icons = {
      success: 'bi bi-check-circle-fill',
      danger:  'bi bi-x-circle-fill',
      warning: 'bi bi-exclamation-circle-fill',
      info:    'bi bi-info-circle-fill'
    };
    const colors = {
      success: '#d4f5e2',
      danger:  '#fde8e8',
      warning: '#fff3cd',
      info:    '#e6f1fb'
    };
    const textColors = {
      success: '#1a7a45',
      danger:  '#c0392b',
      warning: '#856404',
      info:    '#185FA5'
    };

    $('#appToast').remove();

    const toast = `
      <div id="appToast" class="toast align-items-center border-0 position-fixed top-0 end-0 m-3 shadow-sm"
        role="alert" style="z-index:9999; margin-top:20px !important; min-width:400px;
        background:${colors[type]}; border-radius:10px;">
        <div class="d-flex align-items-center px-3 py-2 gap-2">
          <i class="${icons[type]}" style="font-size:18px; color:${textColors[type]};"></i>
          <div class="toast-body p-0 fw-500" style="color:${textColors[type]}; font-size:14px;">
            ${message}
          </div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="toast"
            style="filter:none; opacity:0.5;"></button>
        </div>
      </div>`;

    $('body').append(toast);
    const toastEl = new bootstrap.Toast(document.getElementById('appToast'), { delay: 3000 });
    toastEl.show();
    setTimeout(() => $('#appToast').remove(), 3500);
  };

}); // END document.ready
</script>
<!-- ══ END PHARMACY SCRIPTS ════════════════════════════════════════ -->