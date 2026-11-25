<?php
// No require_once here — already loaded by the shell
?>

<div class="profile-page">

  <!-- Alerts -->
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
      <?= htmlspecialchars($_SESSION['success']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
      <?= htmlspecialchars($_SESSION['error']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <!-- Profile Header Card (Desktop) -->
  <div class="profile-header-card">
    <div class="profile-header-left">
      <div class="profile-avatar">
        <img src="../07_Assets/images/person.jpg" alt="Profile Picture" />
      </div>
      <div class="profile-user-details">
        <h2><?= htmlspecialchars(strtoupper($_SESSION['full_name'] ?? '')) ?></h2>
        <p><?= htmlspecialchars($_SESSION['Email'] ?? '') ?></p>
        <p><?= htmlspecialchars($_SESSION['Phone'] ?? '') ?></p>
      </div>
    </div>
  </div>

  <!-- Mobile Profile Hub (<= 767.98px) -->
  <style>
    @media (min-width: 768px) {
      .mf-mobile-profile-hub {
        display: none;
      }
    }
  </style>
  <div class="mf-mobile-profile-hub" id="mfMobileProfileHub">
    <div class="mf-mobile-profile-hero"></div>
    <div class="mf-mobile-profile-sheet">
      <div class="mf-mobile-avatar-wrap">
        <img src="../07_Assets/images/person.jpg" alt="Profile Picture" class="mf-mobile-profile-avatar" />
        <button type="button" class="mf-mobile-avatar-edit" data-mobile-tab="edit" aria-label="Edit Profile">
          <span class="material-symbols-outlined">edit</span>
        </button>
      </div>
      <h2 class="mf-mobile-profile-name"><?= htmlspecialchars($_SESSION['full_name'] ?? 'User') ?></h2>
      <p class="mf-mobile-profile-sub"><?= htmlspecialchars($_SESSION['Email'] ?? '') ?></p>

      <div class="mf-profile-menu-card">
        <div class="mf-profile-acc-item">
          <button type="button" class="mf-profile-menu-item" data-mobile-tab="edit" aria-expanded="false">
            <span class="material-symbols-outlined">edit_square</span>
            <span>Edit Profile</span>
            <span class="material-symbols-outlined mf-menu-chevron">chevron_right</span>
          </button>
          <div class="mf-profile-dropdown" id="mfDrop-edit"></div>
        </div>

        <div class="mf-profile-acc-item">
          <button type="button" class="mf-profile-menu-item" data-mobile-tab="settings" aria-expanded="false">
            <span class="material-symbols-outlined">settings</span>
            <span>Settings</span>
            <span class="material-symbols-outlined mf-menu-chevron">chevron_right</span>
          </button>
          <div class="mf-profile-dropdown" id="mfDrop-settings"></div>
        </div>

        <div class="mf-profile-acc-item">
          <button type="button" class="mf-profile-menu-item" data-mobile-tab="help" aria-expanded="false">
            <span class="material-symbols-outlined">help</span>
            <span>Help &amp; Support</span>
            <span class="material-symbols-outlined mf-menu-chevron">chevron_right</span>
          </button>
          <div class="mf-profile-dropdown" id="mfDrop-help"></div>
        </div>

        <div class="mf-profile-acc-item">
          <button type="button" class="mf-profile-menu-item" data-mobile-tab="about" aria-expanded="false">
            <span class="material-symbols-outlined">info</span>
            <span>About</span>
            <span class="material-symbols-outlined mf-menu-chevron">chevron_right</span>
          </button>
          <div class="mf-profile-dropdown" id="mfDrop-about"></div>
        </div>
      </div>

      <button type="button" class="mf-profile-logout-card" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <span class="material-symbols-outlined">logout</span>
        <span>Log out</span>
      </button>
    </div>
  </div>

  <!-- Tabs Nav -->
  <div class="profile-tabs-nav">
    <button class="tab-btn active" data-tab="edit">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
      </svg>
      Edit Profile
    </button>
    <button class="tab-btn" data-tab="settings">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="3" />
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
      </svg>
      Settings
    </button>
    <button class="tab-btn" data-tab="help">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10" />
        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
        <line x1="12" y1="17" x2="12.01" y2="17" />
      </svg>
      Help & Support
    </button>
    <button class="tab-btn" data-tab="about">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10" />
        <line x1="12" y1="8" x2="12" y2="12" />
        <line x1="12" y1="16" x2="12.01" y2="16" />
      </svg>
      About
    </button>
  </div>

  <!-- Tab Content -->
  <div class="profile-tab-content">

    <!-- EDIT PROFILE -->
    <div class="tab-panel active" id="tab-edit" style="margin-bottom: 70px;">
      <div class="profile-info-card">
        <form method="POST" action="../02_Actions/04_Users-Actions/profile_update.php" id="profileForm">
          <div class="row g-4">

            <div class="col-lg-6">
              <div class="info-section">
                <h4>Basic Information</h4>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control custom-input"
                      value="<?= htmlspecialchars($_SESSION['fname'] ?? '') ?>" placeholder="First Name" required />
                  </div>
                  <div class="col-md-6">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" class="form-control custom-input"
                      value="<?= htmlspecialchars($_SESSION['mname'] ?? '') ?>" placeholder="Middle Name" />
                  </div>
                  <div class="col-md-6">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control custom-input"
                      value="<?= htmlspecialchars($_SESSION['lname'] ?? '') ?>" placeholder="Last Name" required />
                  </div>
                  <div class="col-md-6">
                    <label>Contact Number</label>
                    <input type="text" name="phone" class="form-control custom-input"
                      value="<?= htmlspecialchars($_SESSION['Phone'] ?? '') ?>" placeholder="Contact Number" />
                  </div>
                  <div class="col-md-6">
                    <label class="d-block mb-1">Gender</label>
                    <div class="d-flex flex-wrap gap-3">
                      <?php foreach (['M' => 'Male', 'F' => 'Female'] as $val => $label): ?>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="gender" value="<?= $val ?>"
                            <?= isset($_SESSION['Gender']) && strtoupper($_SESSION['Gender']) === $val ? 'checked' : '' ?>>
                          <label class="form-check-label"><?= $label ?></label>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label>Birth Date</label>
                    <input type="date" name="birth_date" class="form-control custom-input"
                      value="<?= htmlspecialchars(substr($_SESSION['Birthdate'] ?? '', 0, 10)) ?>" />
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="info-section mb-4">
                <h4>Address Information</h4>
                <div class="row g-3">
                  <div class="col-md-6">
                    <label>Province</label>
                    <select class="form-control custom-input" id="Province_ID" name="province">
                      <option value="" disabled selected>Select Province</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>City / Municipality</label>
                    <select class="form-control custom-input" id="City_ID" name="city">
                      <option value="" disabled selected>Select City</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>Barangay</label>
                    <select class="form-control custom-input" id="Barangay_ID" name="barangay">
                      <option value="" disabled selected>Select Barangay</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label>Purok/Street</label>
                    <input type="text" name="street" class="form-control custom-input"
                      value="<?= htmlspecialchars($_SESSION['Street'] ?? '') ?>" placeholder="Purok/Street" />
                  </div>
                </div>
              </div>

              <div class="info-section">
                <h4>Login Information</h4>
                <div class="row g-3">
                  <div class="col-12">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control custom-input"
                      value="<?= htmlspecialchars($_SESSION['Email'] ?? '') ?>" placeholder="Email" required />
                  </div>
                  <div class="col-md-6">
                    <label>New Password</label>
                    <div class="input-group">
                      <input type="password" name="password" id="pw1" class="form-control custom-input"
                        placeholder="Leave blank to keep current" />
                      <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pw1','e1s','e1h')">
                        <svg id="e1s" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                          <circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg id="e1h" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                          <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                          <line x1="1" y1="1" x2="23" y2="23" />
                        </svg>
                      </button>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label>Confirm Password</label>
                    <div class="input-group">
                      <input type="password" name="confirm_password" id="pw2" class="form-control custom-input"
                        placeholder="Confirm password" oninput="checkMatch()" />
                      <button type="button" class="btn btn-outline-secondary" onclick="togglePw('pw2','e2s','e2h')">
                        <svg id="e2s" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                          <circle cx="12" cy="12" r="3" />
                        </svg>
                        <svg id="e2h" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                          <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                          <line x1="1" y1="1" x2="23" y2="23" />
                        </svg>
                      </button>
                    </div>
                    <small id="match-msg" class="mt-1 d-block"></small>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <div class="edit-save-row">
            <button type="submit" class="btn-profile btn-edit">Save Changes</button>
            <button type="button" class="btn-profile btn-cancel"
              onclick="window.location.href='07_Profile.php'">Cancel</button>
          </div>
        </form>
      </div>
    </div>


    <!-- SETTINGS -->
    <div class="tab-panel" id="tab-settings">
      <div class="profile-info-card">
        <div class="info-section">
          <h4>Settings</h4>
          <div class="settings-list">
            <div class="settings-item">
              <span>Notifications</span>
              <label class="toggle-switch">
                <input type="checkbox" checked />
                <span class="toggle-slider"></span>
              </label>
            </div>
            <div class="settings-item">
              <span>Location Access</span>
              <label class="toggle-switch">
                <input type="checkbox" />
                <span class="toggle-slider"></span>
              </label>
            </div>
            <div class="settings-item">
              <span>Dark Mode</span>
              <label class="toggle-switch">
                <input type="checkbox" />
                <span class="toggle-slider"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- HELP -->
    <div class="tab-panel" id="tab-help">
      <div class="profile-info-card">
        <div class="info-section">
          <h4>Help & Support</h4>
          <div class="help-list">
            <div class="help-item">
              <strong>How do I search for medicines?</strong>
              <p>Go to the Medicines tab and type the name of the medicine you're looking for.</p>
            </div>
            <div class="help-item">
              <strong>How does Scan RX work?</strong>
              <p>Take a photo of your prescription and we'll identify the medicines listed.</p>
            </div>
            <div class="help-item">
              <strong>Contact Us</strong>
              <p>Email us at <a href="mailto:support@medifind.ph">support@medifind.ph</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ABOUT -->
    <div class="tab-panel" id="tab-about">
      <div class="profile-info-card">
        <div class="info-section">
          <h4>About MediFind</h4>
          <p class="about-text">
            MediFind is a medicine availability checker for Malaybalay City.
            It helps residents quickly locate medicines in nearby pharmacies.
          </p>
          <div class="about-meta">
            <span>Version 1.0.0</span>
            <span>Malaybalay, Bukidnon</span>
          </div>
        </div>
      </div>
    </div>

  </div><!-- /.profile-tab-content -->
</div><!-- /.profile-page -->