<?php
// Global Variable and DB Connection
include_once '../02_Actions/GlobalVariables.php';
include_once '../00_Config/config.php';

require '../02_Actions/04_System-Admin-CRUD/select-count.php';

// ── GUARD: Users must log in ────────────────────────
if (!$_SESSION['user_id']) {
  header('Location: ../03_Authentication/login.php');
  exit;
}

// ── Handle Settings Save (POST) ────────────────────
$successMsg = '';
$errorMsg   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
  // TODO: Persist to DB / config file
  $successMsg = 'Settings saved successfully.';
}
?>

<!doctype html>
<html class="is-animating">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <title>Settings</title>

  <script src="../07_Assets/css/js/sidebar_and_topbar.js?v=<?= time() ?>"></script>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

  <!-- jQuery -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

  <!-- STYLES -->
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-fonts.css" />
  <link rel="stylesheet" href="../07_Assets/css/01_PatientUser CSS/01_Home.css" />
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/01_dashboard.css">
  <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/02_ManageInventory.css">
  <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/override-body-css.css">
  

  <!-- Page transition -->
  <?php include '../01_Includes/page-transition-hardcode.php' ?>

  <style>
    body {
      overflow: auto;
    }

    /* ─────────────────────────────────────────────
       PAGE HEADER
    ───────────────────────────────────────────── */
    .settings-page-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.1rem 1.5rem 0.6rem;
    }

    .settings-page-title {
      font-size: 18px;
      font-weight: 600;
      color: #212529;
    }

    .settings-page-sub {
      font-size: 12px;
      color: #6c757d;
      margin-top: 2px;
    }

    /* ─────────────────────────────────────────────
       TABS
    ───────────────────────────────────────────── */
    .settings-tabs {
      display: flex;
      gap: 4px;
      padding: 0 1.5rem;
      border-bottom: 1.5px solid #e9ecef;
      background: #fff;
      margin-bottom: 0;
      flex-wrap: wrap;
    }

    .stab {
      padding: 12px 18px;
      font-size: 13px;
      font-weight: 500;
      color: #6c757d;
      background: transparent;
      border: none;
      border-bottom: 2.5px solid transparent;
      margin-bottom: -1.5px;
      cursor: pointer;
      transition: color .15s, border-color .15s;
      display: flex;
      align-items: center;
      gap: 7px;
    }

    .stab:hover {
      color: #343a40;
    }

    .stab.active {
      color: #1d9e75;
      border-bottom-color: #1d9e75;
    }

    /* ─────────────────────────────────────────────
       CONTENT WRAPPER
    ───────────────────────────────────────────── */
    .settings-content {
      padding: 1.5rem;
    }

    .settings-panel {
      display: none;
    }

    .settings-panel.active {
      display: block;
    }

    /* ─────────────────────────────────────────────
       SECTION CARDS
    ───────────────────────────────────────────── */
    .settings-section {
      background: #fff;
      border: 1px solid #e9ecef;
      border-radius: 12px;
      padding: 1.25rem 1.5rem;
      margin-bottom: 1rem;
    }

    .settings-section-title {
      font-size: 14px;
      font-weight: 600;
      color: #212529;
      margin-bottom: 3px;
    }

    .settings-section-desc {
      font-size: 12px;
      color: #6c757d;
      margin-bottom: 1.1rem;
    }

    /* ─────────────────────────────────────────────
       FORM FIELDS
    ───────────────────────────────────────────── */
    .settings-label {
      font-size: 12px;
      font-weight: 500;
      color: #495057;
      margin-bottom: 5px;
      display: block;
    }

    .settings-input,
    .settings-select {
      width: 100%;
      padding: 8px 11px;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      font-size: 13px;
      color: #212529;
      background: #fff;
      transition: border-color .15s;
      outline: none;
    }

    .settings-input:focus,
    .settings-select:focus {
      border-color: #1d9e75;
      box-shadow: 0 0 0 3px rgba(29, 158, 117, .1);
    }

    .settings-input[readonly] {
      background: #f8f9fa;
      color: #6c757d;
      cursor: default;
    }

    /* ─────────────────────────────────────────────
       TOGGLE ROWS
    ───────────────────────────────────────────── */
    .toggle-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 11px 0;
      border-bottom: 1px solid #f1f3f5;
    }

    .toggle-row:last-child {
      border-bottom: none;
    }

    .toggle-label {
      font-size: 13px;
      font-weight: 500;
      color: #212529;
    }

    .toggle-desc {
      font-size: 12px;
      color: #6c757d;
      margin-top: 2px;
    }

    .tog {
      position: relative;
      width: 38px;
      height: 21px;
      flex-shrink: 0;
    }

    .tog input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    .tog-slider {
      position: absolute;
      inset: 0;
      background: #ced4da;
      border-radius: 21px;
      cursor: pointer;
      transition: background .2s;
    }

    .tog-slider:before {
      content: '';
      position: absolute;
      width: 15px;
      height: 15px;
      left: 3px;
      top: 3px;
      background: #fff;
      border-radius: 50%;
      transition: transform .2s;
    }

    .tog input:checked+.tog-slider {
      background: #1d9e75;
    }

    .tog input:checked+.tog-slider:before {
      transform: translateX(17px);
    }

    /* ─────────────────────────────────────────────
       DIVIDER
    ───────────────────────────────────────────── */
    .settings-divider {
      border: none;
      border-top: 1px solid #f1f3f5;
      margin: 1rem 0;
    }

    /* ─────────────────────────────────────────────
       SYSTEM INFO GRID
    ───────────────────────────────────────────── */
    .sys-info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 10px;
      margin-top: 4px;
    }

    .sys-info-card {
      background: #f8f9fa;
      border-radius: 8px;
      padding: 10px 14px;
    }

    .sys-info-card .si-label {
      font-size: 11px;
      color: #6c757d;
      margin-bottom: 4px;
    }

    .sys-info-card .si-value {
      font-size: 14px;
      font-weight: 600;
      color: #212529;
    }

    /* ─────────────────────────────────────────────
       ADMIN USER ROWS
    ───────────────────────────────────────────── */
    .admin-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 9px 0;
      border-bottom: 1px solid #f1f3f5;
    }

    .admin-row:last-of-type {
      border-bottom: none;
    }

    .admin-avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 12px;
      font-weight: 600;
      flex-shrink: 0;
    }

    .admin-name {
      font-size: 13px;
      font-weight: 500;
      color: #212529;
    }

    .admin-email {
      font-size: 12px;
      color: #6c757d;
    }

    /* ─────────────────────────────────────────────
       BADGES
    ───────────────────────────────────────────── */
    .badge-role {
      padding: 4px 11px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 500;
    }

    .badge-superadmin {
      background: #d4f5e2;
      color: #1a7a45;
    }

    .badge-staffadmin {
      background: #fff3cd;
      color: #856404;
    }

    /* ─────────────────────────────────────────────
       BUTTONS
    ───────────────────────────────────────────── */
    .btn-settings-save {
      background-color: #1d9e75;
      border-color: #1d9e75;
      color: #fff;
      font-size: 13px;
      padding: 8px 22px;
      border-radius: 8px;
      font-weight: 500;
      border: none;
      cursor: pointer;
      transition: background .15s;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-settings-save:hover {
      background-color: #178a63;
    }

    .btn-settings-outline {
      background: transparent;
      border: 1px solid #dee2e6;
      color: #495057;
      font-size: 13px;
      padding: 7px 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: background .15s;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .btn-settings-outline:hover {
      background: #f8f9fa;
    }

    .btn-settings-danger {
      background: transparent;
      border: 1px solid #f5c2c7;
      color: #c0392b;
      font-size: 13px;
      padding: 7px 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: background .15s;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }

    .btn-settings-danger:hover {
      background: #fde8e8;
    }

    /* ─────────────────────────────────────────────
       ALERT
    ───────────────────────────────────────────── */
    .settings-alert {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 1rem;
    }

    .settings-alert.success {
      background: #d4f5e2;
      color: #1a7a45;
      border: 1px solid #a3e4c1;
    }

    .settings-alert.error {
      background: #fde8e8;
      color: #c0392b;
      border: 1px solid #f7c1c1;
    }

    /* ─────────────────────────────────────────────
       ACTIVITY LOG TABLE
    ───────────────────────────────────────────── */
    .log-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    .log-table thead th {
      background: #f8f9fa;
      color: #6c757d;
      font-weight: 600;
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .5px;
      padding: 8px 12px;
      border-bottom: 1px solid #e9ecef;
      white-space: nowrap;
    }

    .log-table tbody td {
      padding: 10px 12px;
      border-bottom: 1px solid #f1f3f5;
      color: #212529;
      vertical-align: middle;
    }

    .log-table tbody tr:last-child td {
      border-bottom: none;
    }

    .log-table tbody tr:hover td {
      background: #f8f9fa;
    }

    .log-badge {
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 500;
    }

    .log-badge-login {
      background: #d4f5e2;
      color: #1a7a45;
    }

    .log-badge-settings {
      background: #dbeafe;
      color: #1d4ed8;
    }

    .log-badge-user {
      background: #fff3cd;
      color: #856404;
    }

    .log-badge-system {
      background: #f3e8ff;
      color: #6b21a8;
    }

    /* ─────────────────────────────────────────────
       DANGER ZONE
    ───────────────────────────────────────────── */
    .danger-zone {
      border: 1px solid #f5c2c7;
      border-radius: 12px;
      padding: 1.25rem 1.5rem;
      margin-bottom: 1rem;
      background: #fff;
    }

    .danger-zone .settings-section-title {
      color: #c0392b;
    }

    .danger-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #f9e0e0;
    }

    .danger-item:last-child {
      border-bottom: none;
    }

    .danger-item-label {
      font-size: 13px;
      font-weight: 500;
      color: #212529;
    }

    .danger-item-desc {
      font-size: 12px;
      color: #6c757d;
      margin-top: 2px;
    }

    
  </style>

  
</head>

<body data-active="05">
  <div class="wrapper d-flex align-items-stretch">
    <div id="system-sidebar-container"></div>

    <div class="main-panel d-flex flex-column flex-grow-1">
      <div id="topbar-container"></div>

      <div class="content">

        <!-- ── Page Header ── -->
        <div class="settings-page-header">
          <div>
            <div class="settings-page-title">
              <span class="material-symbols-outlined" style="font-size:20px; vertical-align:-4px; color:#1d9e75; margin-right:6px;">settings</span>
              System Settings
            </div>
            <div class="settings-page-sub">Manage system-wide configuration, users, and preferences</div>
          </div>
          <button class="btn-settings-save" onclick="saveSettings()">
            <span class="material-symbols-outlined" style="font-size:15px;">save</span>
            Save Changes
          </button>
        </div>

        <!-- ── Tabs ── -->
        <div class="settings-tabs">
          <button class="stab active" onclick="switchSettingsTab('general', this)">
            <span class="material-symbols-outlined" style="font-size:15px;">tune</span> General
          </button>
          <button class="stab" onclick="switchSettingsTab('security', this)">
            <span class="material-symbols-outlined" style="font-size:15px;">lock</span> Security
          </button>
          <button class="stab" onclick="switchSettingsTab('notifications', this)">
            <span class="material-symbols-outlined" style="font-size:15px;">notifications</span> Notifications
          </button>
          <button class="stab" onclick="switchSettingsTab('admins', this)">
            <span class="material-symbols-outlined" style="font-size:15px;">manage_accounts</span> Admins & Roles
          </button>
          <button class="stab" onclick="switchSettingsTab('system', this)">
            <span class="material-symbols-outlined" style="font-size:15px;">dns</span> System
          </button>
          <button class="stab" onclick="switchSettingsTab('logs', this)">
            <span class="material-symbols-outlined" style="font-size:15px;">history</span> Activity Log
          </button>
        </div>

        <!-- ── Settings Content ── -->
        <div class="settings-content">

          <?php if ($successMsg): ?>
            <div class="settings-alert success">
              <span class="material-symbols-outlined" style="font-size:16px;">check_circle</span>
              <?= htmlspecialchars($successMsg) ?>
            </div>
          <?php endif; ?>

          <?php if ($errorMsg): ?>
            <div class="settings-alert error">
              <span class="material-symbols-outlined" style="font-size:16px;">error</span>
              <?= htmlspecialchars($errorMsg) ?>
            </div>
          <?php endif; ?>

          <form method="POST" id="settingsForm">
            <input type="hidden" name="save_settings" value="1">


            <!-- ══════════════════════════════════════════════════
                 GENERAL PANEL
            ══════════════════════════════════════════════════════ -->
            <div class="settings-panel active" id="panel-general">

              <!-- System Identity -->
              <div class="settings-section">
                <div class="settings-section-title">System Identity</div>
                <div class="settings-section-desc">Basic details displayed across the platform</div>
                <div class="row g-3">
                  <div class="col-12 col-md-6">
                    <label class="settings-label">System Name</label>
                    <input type="text" name="system_name" class="settings-input" value="MediFind" />
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Support Contact Number</label>
                    <input type="text" name="contact_phone" class="settings-input" value="+63 912 345 6789" />
                  </div>
                  <div class="col-12">
                    <label class="settings-label">Office Address</label>
                    <input type="text" name="contact_address" class="settings-input" value="Cagayan de Oro City, Misamis Oriental" />
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Admin Email Address</label>
                    <input type="email" name="contact_email" class="settings-input" value="admin@medifind.com" />
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Website URL</label>
                    <input type="text" name="contact_website" class="settings-input" placeholder="https://..." />
                  </div>
                </div>
              </div>

              <!-- Regional Preferences -->
              <div class="settings-section">
                <div class="settings-section-title">Regional Preferences</div>
                <div class="settings-section-desc">Localization and display defaults for the system</div>
                <div class="row g-3">
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Timezone</label>
                    <select name="timezone" class="settings-select">
                      <option value="Asia/Manila" selected>Asia/Manila (UTC+8)</option>
                      <option value="UTC">UTC</option>
                    </select>
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Date Format</label>
                    <select name="date_format" class="settings-select">
                      <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                      <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                      <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                    </select>
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Language</label>
                    <select name="language" class="settings-select">
                      <option value="en" selected>English</option>
                      <option value="fil">Filipino</option>
                    </select>
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Currency</label>
                    <select name="currency" class="settings-select">
                      <option value="PHP" selected>PHP — Philippine Peso</option>
                      <option value="USD">USD — US Dollar</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Pharmacy Approval Settings -->
              <div class="settings-section">
                <div class="settings-section-title">Pharmacy Approval Workflow</div>
                <div class="settings-section-desc">Control how pharmacy registration requests are handled</div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Auto-approve pharmacy registrations</div>
                    <div class="toggle-desc">Skip manual review; pharmacies go active immediately upon sign-up</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="auto_approve_pharmacy" /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Require document upload for registration</div>
                    <div class="toggle-desc">Pharmacies must submit business permits before approval</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="require_docs" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Notify admin on new pharmacy request</div>
                    <div class="toggle-desc">Send an email alert whenever a pharmacy submits a registration</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="notify_pharmacy_request" checked /><span class="tog-slider"></span></label>
                </div>
              </div>

            </div>
            <!-- ══ END GENERAL ══ -->


            <!-- ══════════════════════════════════════════════════
                 SECURITY PANEL
            ══════════════════════════════════════════════════════ -->
            <div class="settings-panel" id="panel-security">

              <div class="settings-section">
                <div class="settings-section-title">Authentication</div>
                <div class="settings-section-desc">Control how system admin accounts are accessed</div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Two-factor authentication (2FA)</div>
                    <div class="toggle-desc">Require a one-time code on every admin login</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="enable_2fa" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Force password reset on first login</div>
                    <div class="toggle-desc">New admin accounts must change their password immediately</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="force_pw_reset" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Allow multiple active sessions</div>
                    <div class="toggle-desc">Permit admins to be logged in from more than one device</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="multi_session" /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">IP whitelist enforcement</div>
                    <div class="toggle-desc">Restrict admin panel access to specific IP addresses only</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="ip_whitelist" /><span class="tog-slider"></span></label>
                </div>
              </div>

              <div class="settings-section">
                <div class="settings-section-title">Session & Password Policy</div>
                <div class="settings-section-desc">Define expiry rules and password strength requirements</div>
                <div class="row g-3">
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Session Timeout (minutes)</label>
                    <input type="number" name="session_timeout" class="settings-input" value="30" min="5" max="480" />
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Max Failed Login Attempts</label>
                    <input type="number" name="max_login_attempts" class="settings-input" value="5" min="3" max="20" />
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Minimum Password Length</label>
                    <input type="number" name="min_pw_length" class="settings-input" value="8" min="6" max="32" />
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Password Expiry (days)</label>
                    <input type="number" name="pw_expiry_days" class="settings-input" value="90" min="30" max="365" />
                  </div>
                  <div class="col-12">
                    <label class="settings-label">IP Whitelist (comma-separated)</label>
                    <input type="text" name="ip_whitelist_ips" class="settings-input" placeholder="e.g. 192.168.1.1, 10.0.0.5" />
                  </div>
                </div>
              </div>

              <div class="settings-section">
                <div class="settings-section-title">Account Lockout Policy</div>
                <div class="settings-section-desc">Automatically suspend or lock accounts after suspicious activity</div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Auto-lock after failed attempts</div>
                    <div class="toggle-desc">Lock account temporarily when max failed login attempts is reached</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="auto_lockout" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Notify admin on locked accounts</div>
                    <div class="toggle-desc">Send an email whenever a user account is locked out</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="notify_lockout" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="row g-3 mt-1">
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Lockout Duration (minutes)</label>
                    <input type="number" name="lockout_duration" class="settings-input" value="15" min="5" max="1440" />
                  </div>
                </div>
              </div>

            </div>
            <!-- ══ END SECURITY ══ -->


            <!-- ══════════════════════════════════════════════════
                 NOTIFICATIONS PANEL
            ══════════════════════════════════════════════════════ -->
            <div class="settings-panel" id="panel-notifications">

              <div class="settings-section">
                <div class="settings-section-title">System Event Alerts</div>
                <div class="settings-section-desc">Choose which events trigger email notifications to the admin</div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">New user registration</div>
                    <div class="toggle-desc">Notify when a new patient or pharmacy account is created</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="notif_new_user" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">New pharmacy application</div>
                    <div class="toggle-desc">Alert when a pharmacy submits a registration request</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="notif_pharmacy_app" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Suspended accounts</div>
                    <div class="toggle-desc">Alert when a user or pharmacy account is suspended</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="notif_suspension" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">System error alerts</div>
                    <div class="toggle-desc">Critical server or application errors</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="notif_system_errors" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Expired inventory reports</div>
                    <div class="toggle-desc">Weekly digest of expired stock items across all pharmacies</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="notif_expired_stock" /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Scheduled backup completion</div>
                    <div class="toggle-desc">Confirm when automated database backups finish</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="notif_backup" checked /><span class="tog-slider"></span></label>
                </div>
              </div>

              <div class="settings-section">
                <div class="settings-section-title">Notification Recipients</div>
                <div class="settings-section-desc">Email addresses that receive system alerts (comma-separated for multiple)</div>
                <div class="row g-3">
                  <div class="col-12 col-md-8">
                    <label class="settings-label">Primary Admin Email</label>
                    <input type="email" name="notif_email" class="settings-input" value="admin@medifind.com" />
                  </div>
                  <div class="col-12 col-md-8">
                    <label class="settings-label">CC Recipients (optional)</label>
                    <input type="text" name="notif_cc" class="settings-input" placeholder="e.g. devops@medifind.com, manager@medifind.com" />
                  </div>
                </div>
              </div>

            </div>
            <!-- ══ END NOTIFICATIONS ══ -->


            <!-- ══════════════════════════════════════════════════
                 ADMINS & ROLES PANEL
            ══════════════════════════════════════════════════════ -->
            <div class="settings-panel" id="panel-admins">

              <div class="settings-section">
                <div class="settings-section-title">Active System Administrators</div>
                <div class="settings-section-desc">Accounts with access to this admin panel</div>

                <!-- Current logged-in admin -->
                <div class="admin-row">
                  <div class="d-flex align-items-center gap-2">
                    <div class="admin-avatar" style="background:#d4f5e2; color:#1a7a45;">
                      <?= strtoupper(substr($_SESSION['first_name'] ?? 'A', 0, 1) . substr($_SESSION['last_name'] ?? 'D', 0, 1)) ?>
                    </div>
                    <div>
                      <div class="admin-name"><?= htmlspecialchars(($_SESSION['first_name'] ?? 'Admin') . ' ' . ($_SESSION['last_name'] ?? 'User')) ?> <span style="font-size:11px;color:#1d9e75;">(You)</span></div>
                      <div class="admin-email"><?= htmlspecialchars($_SESSION['email'] ?? 'admin@medifind.com') ?></div>
                    </div>
                  </div>
                  <span class="badge-role badge-superadmin">Super Admin</span>
                </div>

                <hr class="settings-divider">
                <button type="button" class="btn-settings-outline">
                  <span class="material-symbols-outlined" style="font-size:14px;">person_add</span>
                  Invite Administrator
                </button>
              </div>

              <div class="settings-section">
                <div class="settings-section-title">Staff Admin Permissions</div>
                <div class="settings-section-desc">Control what staff-level admin accounts can access within the system</div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">View user records</div>
                    <div class="toggle-desc">Read-only access to all registered user data</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="perm_view_users" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Manage user accounts</div>
                    <div class="toggle-desc">Suspend, activate, or edit user accounts</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="perm_manage_users" /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Approve or reject pharmacies</div>
                    <div class="toggle-desc">Handle pharmacy registration requests</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="perm_approve_pharmacy" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Generate and export reports</div>
                    <div class="toggle-desc">Download CSV and analytics reports</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="perm_reports" /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Access system settings</div>
                    <div class="toggle-desc">View and edit general system configuration</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="perm_manage_settings" /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">View activity logs</div>
                    <div class="toggle-desc">Access the admin activity audit trail</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="perm_logs" /><span class="tog-slider"></span></label>
                </div>
              </div>

            </div>
            <!-- ══ END ADMINS ══ -->


            <!-- ══════════════════════════════════════════════════
                 SYSTEM PANEL
            ══════════════════════════════════════════════════════ -->
            <div class="settings-panel" id="panel-system">

              <!-- Maintenance Mode -->
              <div class="settings-section">
                <div class="settings-section-title">Maintenance Mode</div>
                <div class="settings-section-desc">Temporarily disable the platform for all non-admin users</div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Enable maintenance mode</div>
                    <div class="toggle-desc">Only system admins can log in while this is active</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="maintenance_mode" /><span class="tog-slider"></span></label>
                </div>

                <div class="toggle-row">
                  <div>
                    <div class="toggle-label">Show maintenance notice to users</div>
                    <div class="toggle-desc">Display a scheduled downtime banner on the login page</div>
                  </div>
                  <label class="tog"><input type="checkbox" name="show_maintenance_banner" checked /><span class="tog-slider"></span></label>
                </div>

                <div class="row g-3 mt-1">
                  <div class="col-12">
                    <label class="settings-label">Maintenance Message (shown to users)</label>
                    <input type="text" name="maintenance_msg" class="settings-input" value="MediFind is currently undergoing scheduled maintenance. We'll be back shortly." />
                  </div>
                </div>
              </div>

              <!-- Data & Backups -->
              <div class="settings-section">
                <div class="settings-section-title">Data & Backups</div>
                <div class="settings-section-desc">Automated database backup schedule and retention policy</div>

                <div class="row g-3 mb-3">
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Backup Frequency</label>
                    <select name="backup_frequency" class="settings-select">
                      <option value="daily" selected>Daily</option>
                      <option value="weekly">Weekly</option>
                      <option value="monthly">Monthly</option>
                    </select>
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Retention Period (days)</label>
                    <input type="number" name="backup_retention" class="settings-input" value="30" min="7" max="365" />
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Last Backup</label>
                    <input type="text" class="settings-input" value="<?= date('M d, Y') ?>" readonly />
                  </div>
                  <div class="col-12 col-md-6">
                    <label class="settings-label">Next Scheduled Backup</label>
                    <input type="text" class="settings-input" value="<?= date('M d, Y', strtotime('+1 day')) ?>" readonly />
                  </div>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                  <button type="button" class="btn-settings-outline" onclick="triggerManualBackup()">
                    <span class="material-symbols-outlined" style="font-size:14px;">cloud_upload</span>
                    Run Backup Now
                  </button>
                  <button type="button" class="btn-settings-outline" onclick="downloadLastBackup()">
                    <span class="material-symbols-outlined" style="font-size:14px;">download</span>
                    Download Last Backup
                  </button>
                </div>
              </div>

              <!-- System Information -->
              <div class="settings-section">
                <div class="settings-section-title">System Information</div>
                <div class="settings-section-desc">Read-only environment details</div>

                <div class="sys-info-grid">
                  <div class="sys-info-card">
                    <div class="si-label">PHP Version</div>
                    <div class="si-value"><?= phpversion() ?></div>
                  </div>
                  <div class="sys-info-card">
                    <div class="si-label">Server Software</div>
                    <div class="si-value"><?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Apache') ?></div>
                  </div>
                  <div class="sys-info-card">
                    <div class="si-label">Database</div>
                    <div class="si-value">MySQL 8.0</div>
                  </div>
                  <div class="sys-info-card">
                    <div class="si-label">System Version</div>
                    <div class="si-value">v1.0.0</div>
                  </div>
                  <div class="sys-info-card">
                    <div class="si-label">Environment</div>
                    <div class="si-value">Production</div>
                  </div>
                  <div class="sys-info-card">
                    <div class="si-label">Server Time</div>
                    <div class="si-value"><?= date('h:i A') ?></div>
                  </div>
                </div>
              </div>

              <!-- Danger Zone -->
              <div class="danger-zone">
                <div class="settings-section-title" style="color:#c0392b; margin-bottom:3px;">
                  <span class="material-symbols-outlined" style="font-size:15px; vertical-align:-3px; margin-right:4px;">warning</span>
                  Danger Zone
                </div>
                <div class="settings-section-desc">These actions are irreversible. Proceed with extreme caution.</div>

                <div class="danger-item">
                  <div>
                    <div class="danger-item-label">Clear All System Logs</div>
                    <div class="danger-item-desc">Permanently delete all admin activity logs. Cannot be undone.</div>
                  </div>
                  <button type="button" class="btn-settings-danger" onclick="confirmClearLogs()">
                    <span class="material-symbols-outlined" style="font-size:14px;">delete_sweep</span>
                    Clear Logs
                  </button>
                </div>

                <div class="danger-item">
                  <div>
                    <div class="danger-item-label">Reset System to Default Settings</div>
                    <div class="danger-item-desc">Restore all configuration to factory defaults. This will not delete user data.</div>
                  </div>
                  <button type="button" class="btn-settings-danger" onclick="confirmResetSettings()">
                    <span class="material-symbols-outlined" style="font-size:14px;">restart_alt</span>
                    Reset Settings
                  </button>
                </div>
              </div>

            </div>
            <!-- ══ END SYSTEM ══ -->


            <!-- ══════════════════════════════════════════════════
                 ACTIVITY LOG PANEL
            ══════════════════════════════════════════════════════ -->
            <div class="settings-panel" id="panel-logs">

              <div class="settings-section">
                <div class="settings-section-title">Admin Activity Log</div>
                <div class="settings-section-desc">A record of significant actions performed by system administrators</div>

                <!-- Toolbar -->
                <div class="row g-2 align-items-center mb-3">
                  <div class="col-12 col-lg-5">
                    <div class="input-group searchbar-group">
                      <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                      </span>
                      <input type="text" id="logSearch" class="form-control border-start-0 ps-0" style="font-size:13px;" placeholder="Search logs...">
                    </div>
                  </div>
                  <div class="col-6 col-lg-3">
                    <select id="logTypeFilter" class="form-select border-secondary-subtle" style="font-size:13px;">
                      <option value="">All Types</option>
                      <option value="login">Login</option>
                      <option value="settings">Settings</option>
                      <option value="user">User Action</option>
                      <option value="system">System</option>
                    </select>
                  </div>
                  <div class="col-6 col-lg-2 d-flex">
                    <button type="button" onclick="exportLogCSV()"
                      class="btn px-3 rounded-3 d-flex align-items-center justify-content-center gap-1 w-100"
                      style="background-color:#1d9e75; border-color:#1d9e75; color:#fff; font-size:13px;">
                      <i class="bi bi-download"></i>
                      <span class="text-nowrap small">Export</span>
                    </button>
                  </div>
                </div>

                <div style="overflow-x:auto;">
                  <table class="log-table" id="logTable">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Admin</th>
                        <th>Action</th>
                        <th>Type</th>
                        <th>IP Address</th>
                        <th>Date & Time</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      // TODO: Replace with real DB query — e.g. SELECT * FROM admin_logs ORDER BY created_at DESC LIMIT 50
                      $sampleLogs = [
                        ['admin' => ($_SESSION['first_name'] ?? 'Admin') . ' ' . ($_SESSION['last_name'] ?? 'User'), 'action' => 'Logged in successfully',              'type' => 'login',    'ip' => '192.168.1.1',  'time' => date('M d, Y h:i A')],
                        ['admin' => ($_SESSION['first_name'] ?? 'Admin') . ' ' . ($_SESSION['last_name'] ?? 'User'), 'action' => 'Updated notification settings',       'type' => 'settings', 'ip' => '192.168.1.1',  'time' => date('M d, Y h:i A', strtotime('-1 hour'))],
                        ['admin' => ($_SESSION['first_name'] ?? 'Admin') . ' ' . ($_SESSION['last_name'] ?? 'User'), 'action' => 'Approved pharmacy: MedPlus CDO',      'type' => 'user',     'ip' => '192.168.1.1',  'time' => date('M d, Y h:i A', strtotime('-2 hours'))],
                        ['admin' => ($_SESSION['first_name'] ?? 'Admin') . ' ' . ($_SESSION['last_name'] ?? 'User'), 'action' => 'Suspended user: Juan Dela Cruz',      'type' => 'user',     'ip' => '192.168.1.1',  'time' => date('M d, Y h:i A', strtotime('-3 hours'))],
                        ['admin' => ($_SESSION['first_name'] ?? 'Admin') . ' ' . ($_SESSION['last_name'] ?? 'User'), 'action' => 'Manual backup triggered',             'type' => 'system',   'ip' => '192.168.1.1',  'time' => date('M d, Y h:i A', strtotime('-1 day'))],
                        ['admin' => ($_SESSION['first_name'] ?? 'Admin') . ' ' . ($_SESSION['last_name'] ?? 'User'), 'action' => 'Rejected pharmacy: RX Corner',        'type' => 'user',     'ip' => '192.168.1.2',  'time' => date('M d, Y h:i A', strtotime('-1 day -2 hours'))],
                        ['admin' => ($_SESSION['first_name'] ?? 'Admin') . ' ' . ($_SESSION['last_name'] ?? 'User'), 'action' => 'Enabled maintenance mode',            'type' => 'system',   'ip' => '192.168.1.1',  'time' => date('M d, Y h:i A', strtotime('-2 days'))],
                        ['admin' => ($_SESSION['first_name'] ?? 'Admin') . ' ' . ($_SESSION['last_name'] ?? 'User'), 'action' => 'Updated security settings',           'type' => 'settings', 'ip' => '192.168.1.1',  'time' => date('M d, Y h:i A', strtotime('-3 days'))],
                      ];
                      $typeBadgeMap = ['login' => 'log-badge-login', 'settings' => 'log-badge-settings', 'user' => 'log-badge-user', 'system' => 'log-badge-system'];
                      foreach ($sampleLogs as $i => $log):
                      ?>
                        <tr data-type="<?= $log['type'] ?>">
                          <td style="color:#6c757d;"><?= $i + 1 ?></td>
                          <td><?= htmlspecialchars($log['admin']) ?></td>
                          <td><?= htmlspecialchars($log['action']) ?></td>
                          <td><span class="log-badge <?= $typeBadgeMap[$log['type']] ?>"><?= ucfirst($log['type']) ?></span></td>
                          <td style="font-family:monospace; font-size:12px;"><?= htmlspecialchars($log['ip']) ?></td>
                          <td style="color:#6c757d;"><?= htmlspecialchars($log['time']) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
            <!-- ══ END LOGS ══ -->

          </form>
        </div><!-- end settings-content -->
      </div><!-- end content -->
    </div><!-- end main-panel -->
  </div><!-- end wrapper -->


  <!-- ── Scripts ── -->
  <script>
    // ── Tab switching ──────────────────────────────
    function switchSettingsTab(name, btn) {
      document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
      document.querySelectorAll('.stab').forEach(t => t.classList.remove('active'));
      document.getElementById('panel-' + name).classList.add('active');
      btn.classList.add('active');
    }

    // ── Save form ──────────────────────────────────
    function saveSettings() {
      document.getElementById('settingsForm').submit();
    }

    // ── Backup actions ─────────────────────────────
    function triggerManualBackup() {
      if (confirm('Start a manual backup now?')) {
        alert('Backup started. You will receive an email notification when it completes.');
      }
    }

    function downloadLastBackup() {
      alert('Preparing download... (TODO: link to actual backup file)');
    }

    // ── Danger zone ────────────────────────────────
    function confirmClearLogs() {
      if (confirm('Are you sure you want to permanently delete all activity logs? This cannot be undone.')) {
        alert('Logs cleared. (TODO: POST to clear-logs endpoint)');
      }
    }

    function confirmResetSettings() {
      if (confirm('Are you sure you want to reset all settings to their defaults? This cannot be undone.')) {
        alert('Settings reset. (TODO: POST to reset-settings endpoint)');
      }
    }

    // ── Activity Log filter ────────────────────────
    document.getElementById('logSearch').addEventListener('input', filterLogs);
    document.getElementById('logTypeFilter').addEventListener('change', filterLogs);

    function filterLogs() {
      const search = document.getElementById('logSearch').value.toLowerCase().trim();
      const type = document.getElementById('logTypeFilter').value.toLowerCase().trim();
      document.querySelectorAll('#logTable tbody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowType = (row.dataset.type || '').toLowerCase();
        const matchS = !search || text.includes(search);
        const matchT = !type || rowType === type;
        row.style.display = (matchS && matchT) ? '' : 'none';
      });
    }

    // ── Export log CSV ─────────────────────────────
    function exportLogCSV() {
      const table = document.getElementById('logTable');
      const rows = [...table.querySelectorAll('tr')]
        .filter(r => r.style.display !== 'none')
        .map(r => [...r.querySelectorAll('th,td')].map(c => '"' + c.innerText.trim().replace(/"/g, '""') + '"').join(','));
      const blob = new Blob([rows.join('\n')], {
        type: 'text/csv'
      });
      const a = document.createElement('a');
      a.href = URL.createObjectURL(blob);
      a.download = 'medifind_activity_log_' + new Date().toISOString().slice(0, 10) + '.csv';
      a.click();
    }
  </script>

</body>

</html>