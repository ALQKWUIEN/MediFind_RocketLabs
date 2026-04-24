<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MediFind: Profile</title>

    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
    <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/profile.css" />
  </head>
  <body>
    <div class="wrapper d-flex align-items-stretch">
      <div id="sidebar-container"></div>

      <div class="main-panel d-flex flex-column flex-grow-1">
        <div id="topbar-container"></div>

        <div id="content">
          <div class="content-body">
            <div class="content-body">
              <div class="profile-page">
                <!-- Top Profile Card -->
                <div class="profile-header-card">
                  <div class="profile-header-left">
                    <div class="profile-avatar">
                      <img
                        src="../07_Assets/images/person.jpg"
                        alt="Profile Picture"
                      />
                    </div>

                    <div class="profile-user-details">
                      <h2>AIQUIEN CAPES</h2>
                      <p>alquiencapuyan@gmail.com</p>
                      <p>0991 - 141 - 6498</p>
                    </div>
                  </div>

                  <div class="profile-header-divider"></div>

                  <div class="profile-header-center">
                    <img
                      src="../07_Assets/images/logo.png"
                      alt="MediFind Logo"
                      class="medifind-logo"
                    />
                    <div class="brand-text">
                      <h1>Medi<span>Find</span></h1>
                      <small>Malaybalay Medicine Availability Checker</small>
                    </div>
                  </div>

                  <div class="profile-header-actions">
                    <button class="btn-profile btn-logout">Logout</button>
                  </div>
                </div>

                <div class="profile-tabs-nav">
                  <button class="tab-btn active" data-tab="edit">
                    <svg
                      width="16"
                      height="16"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                      />
                      <path
                        d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                      />
                    </svg>
                    Edit Profile
                  </button>
                  <button class="tab-btn" data-tab="saved">
                    <svg
                      width="16"
                      height="16"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"
                      />
                    </svg>
                    Saved
                  </button>
                  <button class="tab-btn" data-tab="settings">
                    <svg
                      width="16"
                      height="16"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <circle cx="12" cy="12" r="3" />
                      <path
                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"
                      />
                    </svg>
                    Settings
                  </button>
                  <button class="tab-btn" data-tab="help">
                    <svg
                      width="16"
                      height="16"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <circle cx="12" cy="12" r="10" />
                      <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                      <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                    Help & Support
                  </button>
                  <button class="tab-btn" data-tab="about">
                    <svg
                      width="16"
                      height="16"
                      viewBox="0 0 24 24"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <circle cx="12" cy="12" r="10" />
                      <line x1="12" y1="8" x2="12" y2="12" />
                      <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    About
                  </button>
                </div>

                <div class="profile-tab-content">
                  <!-- EDIT PROFILE TAB -->
                  <div class="tab-panel active" id="tab-edit">
                    <div class="profile-info-card">
                      <div class="row g-4">
                        <div class="col-lg-6">
                          <div class="info-section">
                            <h4>Basic Information</h4>
                            <div class="row g-3">
                              <div class="col-md-6">
                                <label>First Name</label>
                                <input
                                  type="text"
                                  class="form-control custom-input"
                                  placeholder="First Name"
                                />
                              </div>
                              <div class="col-md-6">
                                <label>Middle Name</label>
                                <input
                                  type="text"
                                  class="form-control custom-input"
                                  placeholder="Middle Name"
                                />
                              </div>
                              <div class="col-md-6">
                                <label>Last Name</label>
                                <input
                                  type="text"
                                  class="form-control custom-input"
                                  placeholder="Last Name"
                                />
                              </div>

                              <!-- Civil Status — Dropdown -->
                              <div class="col-md-6">
                                <label>Civil Status</label>
                                <select class="form-control custom-input">
                                  <option value="" disabled selected>
                                    Select Civil Status
                                  </option>
                                  <option value="single">Single</option>
                                  <option value="married">Married</option>
                                  <option value="widowed">Widowed</option>
                                  <option value="separated">Separated</option>
                                  <option value="divorced">Divorced</option>
                                </select>
                              </div>

                              <div class="col-md-6">
                                <label>Contact Number</label>
                                <input
                                  type="text"
                                  class="form-control custom-input"
                                  placeholder="Contact Number"
                                />
                              </div>

                              <!-- Gender — Radio Buttons -->
                              <div class="col-md-6">
                                <label class="d-block mb-1">Gender</label>
                                <div class="d-flex flex-wrap gap-3">
                                  <div class="form-check">
                                    <input
                                      class="form-check-input"
                                      type="radio"
                                      name="gender"
                                      id="genderMale"
                                      value="male"
                                    />
                                    <label
                                      class="form-check-label"
                                      for="genderMale"
                                      >Male</label
                                    >
                                  </div>
                                  <div class="form-check">
                                    <input
                                      class="form-check-input"
                                      type="radio"
                                      name="gender"
                                      id="genderFemale"
                                      value="female"
                                    />
                                    <label
                                      class="form-check-label"
                                      for="genderFemale"
                                      >Female</label
                                    >
                                  </div>
                                  <div class="form-check">
                                    <input
                                      class="form-check-input"
                                      type="radio"
                                      name="gender"
                                      id="genderOther"
                                      value="other"
                                    />
                                    <label
                                      class="form-check-label"
                                      for="genderOther"
                                      >Prefer not to say</label
                                    >
                                  </div>
                                </div>
                              </div>

                              <div class="col-md-6">
                                <label>Birth Date</label>
                                <input
                                  type="date"
                                  class="form-control custom-input"
                                />
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-lg-6">
                          <div class="info-section mb-4">
                            <h4>Address Information</h4>
                            <div class="row g-3">
                              <!-- Province — Dropdown -->
                              <div class="col-md-6">
                                <label>Province</label>
                                <select
                                  class="form-control custom-input"
                                  id="provinceSelect"
                                >
                                  <option value="" disabled selected>
                                    Select Province
                                  </option>
                                  <option value="bukidnon">Bukidnon</option>
                                  <option value="camiguin">Camiguin</option>
                                  <option value="lanao-del-norte">
                                    Lanao del Norte
                                  </option>
                                  <option value="misamis-occidental">
                                    Misamis Occidental
                                  </option>
                                  <option value="misamis-oriental">
                                    Misamis Oriental
                                  </option>
                                </select>
                              </div>

                              <!-- City — Dropdown -->
                              <div class="col-md-6">
                                <label>City / Municipality</label>
                                <select
                                  class="form-control custom-input"
                                  id="citySelect"
                                >
                                  <option value="" disabled selected>
                                    Select City
                                  </option>
                                  <option value="malaybalay">
                                    Malaybalay City
                                  </option>
                                  <option value="valencia">
                                    Valencia City
                                  </option>
                                  <option value="cabanglasan">
                                    Cabanglasan
                                  </option>
                                  <option value="damulog">Damulog</option>
                                  <option value="dangcagan">Dangcagan</option>
                                  <option value="don-carlos">Don Carlos</option>
                                  <option value="impasug-ong">
                                    Impasug-ong
                                  </option>
                                  <option value="kadingilan">Kadingilan</option>
                                  <option value="kalilangan">Kalilangan</option>
                                  <option value="kibawe">Kibawe</option>
                                  <option value="kitaotao">Kitaotao</option>
                                  <option value="lantapan">Lantapan</option>
                                  <option value="libona">Libona</option>
                                  <option value="manolo-fortich">
                                    Manolo Fortich
                                  </option>
                                  <option value="maramag">Maramag</option>
                                  <option value="pangantucan">
                                    Pangantucan
                                  </option>
                                  <option value="quezon">Quezon</option>
                                  <option value="san-fernando">
                                    San Fernando
                                  </option>
                                  <option value="sumilao">Sumilao</option>
                                  <option value="talakag">Talakag</option>
                                </select>
                              </div>

                              <div class="col-md-6">
                                <label>Barangay</label>
                                <input
                                  type="text"
                                  class="form-control custom-input"
                                  placeholder="Barangay"
                                />
                              </div>
                              <div class="col-md-6">
                                <label>Purok/Street</label>
                                <input
                                  type="text"
                                  class="form-control custom-input"
                                  placeholder="Purok/Street"
                                />
                              </div>
                            </div>
                          </div>

                          <div class="info-section">
                            <h4>Login Information</h4>
                            <div class="row g-3">
                              <div class="col-md-6">
                                <label>Email</label>
                                <input
                                  type="email"
                                  class="form-control custom-input"
                                  placeholder="Email"
                                />
                              </div>
                              <div class="col-md-6">
                                <label>Password</label>
                                <input
                                  type="password"
                                  class="form-control custom-input"
                                  placeholder="Password"
                                />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="edit-save-row">
                        <button class="btn-profile btn-edit">
                          Save Changes
                        </button>
                        <button class="btn-profile btn-cancel">Cancel</button>
                      </div>
                    </div>
                  </div>

                  <!-- SAVED TAB -->
                  <div class="tab-panel" id="tab-saved">
                    <div class="profile-info-card tab-empty-state">
                      <div class="empty-icon">
                        <svg
                          width="48"
                          height="48"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="#20a16b"
                          stroke-width="1.5"
                        >
                          <path
                            d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"
                          />
                        </svg>
                      </div>
                      <h5>Nothing Saved Yet</h5>
                      <p>Medicines and pharmacies you save will appear here.</p>
                    </div>
                  </div>

                  <!-- SETTINGS TAB -->
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

                  <!-- HELP & SUPPORT TAB -->
                  <div class="tab-panel" id="tab-help">
                    <div class="profile-info-card">
                      <div class="info-section">
                        <h4>Help & Support</h4>
                        <div class="help-list">
                          <div class="help-item">
                            <strong>How do I search for medicines?</strong>
                            <p>
                              Go to the Medicines tab and type the name of the
                              medicine you're looking for.
                            </p>
                          </div>
                          <div class="help-item">
                            <strong>How does Scan RX work?</strong>
                            <p>
                              Take a photo of your prescription and we'll
                              identify the medicines listed.
                            </p>
                          </div>
                          <div class="help-item">
                            <strong>Contact Us</strong>
                            <p>
                              Email us at
                              <a href="mailto:support@medifind.ph"
                                >support@medifind.ph</a
                              >
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- ABOUT TAB -->
                  <div class="tab-panel" id="tab-about">
                    <div class="profile-info-card">
                      <div class="info-section">
                        <h4>About MediFind</h4>
                        <p class="about-text">
                          MediFind is a medicine availability checker for
                          Malaybalay City. It helps residents quickly locate
                          medicines in nearby pharmacies.
                        </p>
                        <div class="about-meta">
                          <span>Version 1.0.0</span>
                          <span>Malaybalay, Bukidnon</span>
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

    <script src="../07_Assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../bootstrap/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>

    <script>
      // Profile tabs
      document.querySelectorAll(".tab-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
          const target = btn.dataset.tab;
          document
            .querySelectorAll(".tab-btn")
            .forEach((b) => b.classList.remove("active"));
          document
            .querySelectorAll(".tab-panel")
            .forEach((p) => p.classList.remove("active"));
          btn.classList.add("active");
          document.getElementById("tab-" + target).classList.add("active");
        });
      });
    </script>
  </body>
</html>
