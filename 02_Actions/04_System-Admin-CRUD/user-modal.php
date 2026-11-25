<!-- ══ USER MODAL ══════════════════════════════════════════════════ -->
<div class="modal fade" id="userModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="userModalTitle">View User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="container-fluid">
          <div class="row g-4">

            <!-- LEFT: IMAGE + USERNAME + USER ID -->
            <div class="col-md-4 d-flex flex-column align-items-center gap-3">
            <div>
  <img src="../07_Assets/images/MediFind.png" id="userPic2" alt="User Profile"
    style="width: 100%; max-width: 400px; height: 260px; object-fit: cover; 
           border-radius: 16px; background: #e9ecef;
           border: 2px solid #dee2e6;">
</div>

              <div class="w-100">
                <label class="form-label">Username</label>
                <input type="text" id="userUsername" class="form-control" disabled>
              </div>

              <div class="w-100">
                <label class="form-label">User ID</label>
                <input type="text" id="userIdDisplay" class="form-control" disabled>
              </div>

              <input type="hidden" id="userId">
            </div>

            <!-- RIGHT: INPUTS -->
            <div class="col-md-8">
              <div class="row">

                <div class="col-md-6 mb-3">
                  <label class="form-label">Full Name</label>
                  <input type="text" id="userFullname" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Contact No.</label>
                  <input type="text" id="userPhone" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Gender</label>
                  <input type="text" id="userGender" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" id="userEmail" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Age</label>
                  <input type="text" id="userAge" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Role</label>
                  <select id="userRole" class="form-select">
                    <option value="Patient/Client">Patient / Client</option>
                    <option value="Pharmacy Admin">Pharmacy Admin</option>
                    <option value="System Admin">System Admin</option>
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Date Created</label>
                  <input type="text" id="userDateCreated" class="form-control" disabled>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">Status</label>
                  <select id="userStatus" class="form-select" disabled>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Suspended">Suspended</option>
                  </select>
                </div>

                <div class="col-md-12 mb-3">
                  <label class="form-label">Address</label>
                  <input type="text" id="userAddress" class="form-control" disabled>
                </div>

              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-success" id="userSaveBtn">Save</button>
      </div>

    </div>
  </div>
</div>
<!-- ══ END USER MODAL ══════════════════════════════════════════════ -->


<!-- ══ USER SCRIPTS ════════════════════════════════════════════════ -->
<script>
  $(document).ready(function () {

    // ── Init Bootstrap Modal ───────────────────────────────────
    const userModalEl = document.getElementById('userModal');
    const userModal = new bootstrap.Modal(userModalEl);

    // ── Reset Modal Fields ─────────────────────────────────────
    function resetUserModal() {
      $('#userId, #userIdDisplay, #userUsername, #userFullname, #userEmail, #userPhone, #userGender, #userAge, #userAddress, #userDateCreated').val('');
      $('#userRole').val('Patient/Client');
      $('#userStatus').val('Active');
      $('#userPic').attr('src', '../07_Assets/images/191589.jpg');
    }

    // ── Populate Modal Fields ──────────────────────────────────
    function populateUserModal($el) {
      $('#userId').val($el.data('id'));
      $('#userIdDisplay').val($el.data('id'));
      $('#userUsername').val($el.data('username'));
      $('#userFullname').val($el.data('firstname') + ' ' + $el.data('lastname'));
      $('#userEmail').val($el.data('email'));
      $('#userPhone').val($el.data('phone'));
      var gender = $el.data('gender');

      if (gender === 'M') {
        $('#userGender').val('Male');
      } else {
        $('#userGender').val('Female');
      }
      $('#userAge').val($el.data('age'));
      $('#userRole').val($el.data('role'));
      $('#userStatus').val($el.data('status'));
      $('#userAddress').val($el.data('address'));
      $('#userDateCreated').val($el.data('created'));

      // Profile pic with fallback
      const pic = $el.data('pic');
      $('#userPic').attr('src', pic && pic !== '' ? pic : '../07_Assets/images/191589.jpg');
    }

    // ── Re-enable inputs when modal closes ─────────────────────
    userModalEl.addEventListener('hidden.bs.modal', function () {
      $('#userModal input, #userModal select').prop('disabled', false);
      $('#userSaveBtn').show();
      resetUserModal();
    });

    // ── OPEN: VIEW (read-only) ─────────────────────────────────
    $(document).on('click', '.view-btn', function () {
      $('#userModalTitle').text('View User');
      populateUserModal($(this));
      $('#userModal input, #userModal select').prop('disabled', true);
      $('#userSaveBtn').hide();
      userModal.show();
    });

    // ── OPEN: VIEW on row double-click ─────────────────────────
    $(document).on('dblclick', 'tbody tr', function (e) {
      if ($(e.target).closest('td').hasClass('text-end')) return;
      $(this).find('.view-btn').trigger('click');
    });

    // ── OPEN: EDIT ─────────────────────────────────────────────
    $(document).on('click', '.edit-btn', function () {
      $('#userModalTitle').text('Edit User');
      populateUserModal($(this));
      $('#userModal input, #userModal select').prop('disabled', false);
      $('#userStatus').prop('disabled', true);  // only status is editable
      $('#userSaveBtn').show();
      userModal.show();
    });

    // ── SAVE (UPDATE) ──────────────────────────────────────────
    $('#userSaveBtn').on('click', function () {
      const data = {
        action: 'update',
        id: $('#userId').val(),
        status: $('#userStatus').val()
      };

      $.post('../02_Actions/04_System-Admin-CRUD/display-users.php', data, function (response) {
        if (response.trim() === 'success') {
          userModal.hide();
          showToast('User updated successfully!', 'success');
          setTimeout(() => location.reload(), 1500);

        } else {
          showToast('Something went wrong. Please try again.', 'danger');
        }
      });
    });

    // ── DELETE ─────────────────────────────────────────────────
    $(document).on('click', '.delete-btn', function () {
      if (!confirm('Are you sure you want to delete this user?')) return;

      $.post('../02_Actions/04_System-Admin-CRUD/display-users.php', {
        action: 'delete',
        id: $(this).data('id')
      }, function (response) {
        if (response.trim() === 'success') {
          showToast('User deleted successfully!', 'success');
          setTimeout(() => location.reload(), 1500);
        } else {
          showToast('Delete failed. Please try again.', 'danger');
        }
      });
    });

    // ── Unified Multi-Filter & Address Cascading ───────────────
    function updateFilterChips(chips) {
      const $wrapper = $('#activeFilterChips');
      if (!$wrapper.length) return;

      $wrapper.empty();
      if (chips.length > 0) {
        $wrapper.append('<span class="chips-label text-muted small me-1">Active filters:</span>');
        chips.forEach(function (c) {
          $wrapper.append(
            '<span class="filter-chip badge-soft-pill">' +
              '<span class="chip-text">' + c.label + ': <strong>' + c.value + '</strong></span>' +
              '<button type="button" class="btn-close-chip remove-chip" data-target="' + c.target + '" title="Remove ' + c.label + ' filter">&times;</button>' +
            '</span>'
          );
        });
        $('#clearFiltersBtn').removeClass('d-none');
        $wrapper.removeClass('d-none');
      } else {
        $('#clearFiltersBtn').addClass('d-none');
        $wrapper.addClass('d-none');
      }
    }

    function applyFilters() {
      const search = $('#searchInput').val().toLowerCase().trim();
      const role = $('#filterRole').val().toLowerCase().trim();
      const status = $('#filterStatus').val().toLowerCase().trim();
      const provinceId = $('#filterProvince').val();
      const provinceName = $('#filterProvince option:selected').text();
      const cityId = $('#filterCity').val();
      const cityName = $('#filterCity option:selected').text();
      const barangayId = $('#filterBarangay').val();
      const barangayName = $('#filterBarangay option:selected').text();

      const chips = [];
      if (search !== '') chips.push({ label: 'Search', value: '"' + search + '"', target: 'search' });
      if (role !== '') chips.push({ label: 'Role', value: $('#filterRole option:selected').text(), target: 'role' });
      if (status !== '') chips.push({ label: 'Status', value: $('#filterStatus option:selected').text(), target: 'status' });
      if (provinceId) chips.push({ label: 'Province', value: provinceName, target: 'province' });
      if (cityId) chips.push({ label: 'City', value: cityName, target: 'city' });
      if (barangayId) chips.push({ label: 'Barangay', value: barangayName, target: 'barangay' });

      let visibleCount = 0;
      const $rows = $('tbody tr.user-row');
      const totalCount = $rows.length;

      $rows.each(function () {
        const $row = $(this);
        const fullname = $row.find('td:nth-child(3)').text().toLowerCase();
        const email = $row.find('td:nth-child(4)').text().toLowerCase();
        const contact = $row.find('td:nth-child(5)').text().toLowerCase();
        const rowRole = $row.find('td:nth-child(6)').text().toLowerCase().trim();
        const address = $row.find('td:nth-child(7)').text().toLowerCase();
        const rowStatus = $row.find('td:nth-child(8)').text().toLowerCase().trim();

        // 1. Existing search, role, status matches
        const matchSearch = search === '' || fullname.includes(search) || email.includes(search) || contact.includes(search) || address.includes(search);
        const matchRole = role === '' || rowRole.includes(role);
        const matchStatus = status === '' || rowStatus.includes(status);

        // 2. Address Cascading matches
        const rowProvId = String($row.data('province-id') || '');
        const rowCityId = String($row.data('city-id') || '');
        const rowBrgyId = String($row.data('barangay-id') || '');

        const matchProvince = !provinceId || (rowProvId === String(provinceId));
        const matchCity     = !cityId     || (rowCityId === String(cityId));
        const matchBarangay = !barangayId || (rowBrgyId === String(barangayId));

        const isVisible = matchSearch && matchRole && matchStatus && matchProvince && matchCity && matchBarangay;
        $row.toggle(isVisible);
        if (isVisible) visibleCount++;
      });

      // Update empty state row
      $('#noResultsRow').toggle(visibleCount === 0 && totalCount > 0);

      // Update Result Counter: "Showing N of M users"
      $('#visibleUserCount').text(visibleCount);
      $('#totalUserCount').text(totalCount);

      // Render filter chips
      updateFilterChips(chips);
    }

    // ── Input & Select Listeners ───────────────────────────────
    $('#searchInput').on('input', applyFilters);
    $('#filterRole').on('change', applyFilters);
    $('#filterStatus').on('change', applyFilters);

    // ── Cascading Province Change ──────────────────────────────
    $('#filterProvince').on('change', function () {
      const provId = $(this).val();
      const $citySel = $('#filterCity');
      const $brgySel = $('#filterBarangay');

      $citySel.html('<option value="">All Cities / Municipalities</option>');
      $brgySel.html('<option value="">All Barangays</option>').prop('disabled', true);

      if (provId) {
        $citySel.prop('disabled', true).html('<option value="">Loading cities...</option>');
        $.getJSON('../02_Actions/04_System-Admin-CRUD/get-address-filters.php', { action: 'cities', province_id: provId }, function (data) {
          let opts = '<option value="">All Cities / Municipalities</option>';
          if (Array.isArray(data)) {
            data.forEach(function (c) {
              opts += '<option value="' + c.City_ID + '">' + c.City_Name + '</option>';
            });
          }
          $citySel.html(opts).prop('disabled', false);
          applyFilters();
        }).fail(function () {
          $citySel.html('<option value="">All Cities / Municipalities</option>').prop('disabled', false);
          applyFilters();
        });
      } else {
        $citySel.prop('disabled', true);
        applyFilters();
      }
    });

    // ── Cascading City Change ──────────────────────────────────
    $('#filterCity').on('change', function () {
      const cityId = $(this).val();
      const $brgySel = $('#filterBarangay');

      $brgySel.html('<option value="">All Barangays</option>');

      if (cityId) {
        $brgySel.prop('disabled', true).html('<option value="">Loading barangays...</option>');
        $.getJSON('../02_Actions/04_System-Admin-CRUD/get-address-filters.php', { action: 'barangays', city_id: cityId }, function (data) {
          let opts = '<option value="">All Barangays</option>';
          if (Array.isArray(data)) {
            data.forEach(function (b) {
              opts += '<option value="' + b.Barangay_ID + '">' + b.Barangay_Name + '</option>';
            });
          }
          $brgySel.html(opts).prop('disabled', false);
          applyFilters();
        }).fail(function () {
          $brgySel.html('<option value="">All Barangays</option>').prop('disabled', false);
          applyFilters();
        });
      } else {
        $brgySel.prop('disabled', true);
        applyFilters();
      }
    });

    // ── Cascading Barangay Change ──────────────────────────────
    $('#filterBarangay').on('change', applyFilters);

    // ── Clear All Filters ──────────────────────────────────────
    window.resetAllFilters = function () {
      $('#searchInput').val('');
      $('#filterRole').val('');
      $('#filterStatus').val('');
      $('#filterProvince').val('');
      $('#filterCity').html('<option value="">All Cities / Municipalities</option>').prop('disabled', true);
      $('#filterBarangay').html('<option value="">All Barangays</option>').prop('disabled', true);
      applyFilters();
    };

    $('#clearFiltersBtn').on('click', resetAllFilters);

    // ── Remove Individual Filter Chip ──────────────────────────
    $(document).on('click', '.remove-chip', function (e) {
      e.preventDefault();
      const target = $(this).data('target');
      switch (target) {
        case 'search':
          $('#searchInput').val('');
          applyFilters();
          break;
        case 'role':
          $('#filterRole').val('');
          applyFilters();
          break;
        case 'status':
          $('#filterStatus').val('');
          applyFilters();
          break;
        case 'province':
          $('#filterProvince').val('').trigger('change');
          break;
        case 'city':
          $('#filterCity').val('').trigger('change');
          break;
        case 'barangay':
          $('#filterBarangay').val('');
          applyFilters();
          break;
      }
    });

    // ── SELECT ALL CHECKBOX ────────────────────────────────────
    $('#selectAll').on('change', function () {
      $('.row-check').prop('checked', $(this).is(':checked'));
    });

    // ── Toast Helper ───────────────────────────────────────────
    window.showToast = function (message, type = 'success') {
      const icons = {
        success: 'bi bi-check-circle-fill',
        danger: 'bi bi-x-circle-fill',
        warning: 'bi bi-exclamation-circle-fill',
        info: 'bi bi-info-circle-fill'
      };
      const colors = {
        success: '#d4f5e2',
        danger: '#fde8e8',
        warning: '#fff3cd',
        info: '#e6f1fb'
      };
      const textColors = {
        success: '#1a7a45',
        danger: '#c0392b',
        warning: '#856404',
        info: '#185FA5'
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
<!-- ══ END USER SCRIPTS ════════════════════════════════════════════ -->