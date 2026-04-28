<?php
        include_once '../02_Actions/GlobalVariables.php';
        include_once '../00_Config/config.php';

        // ── Guard: must be logged in ───────────────────────────────────────
        if (!$_SESSION['user_id']) {
            header('Location: ../03_Authentication/login.php');
            exit;
        }

        // ── Re-check approval status fresh from DB every load ─────────────
        // This way if admin approves, next refresh auto-redirects to dashboard
        $stmt = $pdo->prepare("
            SELECT Pharmacy_name, Owner_name, Approval_ID 
            FROM 09_pharmacies 
            WHERE User_ID = ? 
            LIMIT 1
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $pharmacy = $stmt->fetch();

        $approval_id   = $pharmacy['Approval_ID']   ?? 1;
        $pharmacy_name = $pharmacy['Pharmacy_name'] ?? '';
        $owner_name    = $pharmacy['Owner_name']    ?? $_SESSION['full_name']; // default to logged in user
        $is_pending    = ($approval_id == 1);
        $is_rejected   = ($approval_id == 3);

        // ── If already approved — skip this page entirely ─────────────────
        // ── If already approved — update session then redirect ────────────
        if ($approval_id == 2) {
            // Update session value so the switch in login won't loop
            $_SESSION['Pharmacy_Approval'] = 2;
            
            // Also update pharmacy_name in session in case it changed
            $_SESSION['pharmacy_name'] = $pharmacy['Pharmacy_name'] ?? null;
            
            header('Location: ../05_PharmacyAdmin/01_Dashboard.php');
            exit;
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediFind | Setup Pharmacy</title>

    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../07_Assets/css/02_PharmacyAdmin CSS/01_Dashboard.css">
    <link rel="stylesheet" href="../07_Assets/css/00_Global CSS/font-style.css">

    

    <?php include '../01_Includes/page-transition-hardcode.php'; ?>

    <style>
        /* ── Dark overlay covering entire screen ────────────────── */
        .approval-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            z-index: 1040;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── White modal card ───────────────────────────────────── */
        .approval-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2.5rem 3rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            text-align: center;
            z-index: 1050;
        }

        /* ── Brand title ────────────────────────────────────────── */
        .approval-title {
            font-size: 1.8rem;
            font-weight: 700;
            /* font-family: 'Poppins', sans-serif; */
            margin-bottom: 4px;
        }
        .approval-title .medi { color: #1d9e75; }
        .approval-title .find { color: #ff5b27; }

        .approval-subtitle {
            font-size: 13px;
            color: #666;
            margin-bottom: 1.4rem;
        }

        /* ── Input groups (copied from Auth-style.css) ──────────── */
        .approval-card .input-group-text {
            background: #ffffff;
            border-right: none;
            color: #aaa;
            border-radius: 32px 0 0 32px !important;
        }

        .approval-card .form-control {
            border-radius: 0 32px 32px 0 !important;
            font-size: 14px;
            color: #333;
            border-left: none;
        }

        .approval-card .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }

        .approval-card .input-group:focus-within .input-group-text,
        .approval-card .input-group:focus-within .form-control {
            border-color: rgb(166, 207, 194);
        }

        /* ── Submit button (copied from Auth-style.css) ─────────── */
        .btn-request {
            display: block;
            width: 100%;
            padding: 0.6rem 1rem;
            border: none;
            border-radius: 32px;
            background-color: #ff5b27;
            color: #ffffff;
            font-size: 15px;
            font-weight: 200;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: opacity 0.2s ease, transform 0.15s ease;
            margin-top: 0.5rem;
        }
        .btn-request:hover { opacity: 0.88; }
        .btn-request:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            opacity: 1;
        }

        /* ── Pending status text ────────────────────────────────── */
        .pending-text {
            font-size: 12px;
            color: #888;
            font-style: italic;
            margin-top: 0.6rem;
            margin-bottom: 0;
        }

        /* ── Logout link ────────────────────────────────────────── */
        .logout-text {
            font-size: 11.5px;
            color: #aaa;
            margin-top: 0.8rem;
            margin-bottom: 0;
        }
        .logout-link {
            color: #1d9e75;
            font-weight: 600;
            text-decoration: none;
        }
        .logout-link:hover { text-decoration: underline; }
    </style>
</head>

<body>
    <div class="wrapper d-flex align-items-stretch">

        <!-- Sidebar — stays visible behind overlay -->
        <div id="pharmacy-sidebar-container">
            <?php include '../01_Includes/pharmacy-sidebar.php'; ?>
        </div>

        <div class="main-panel d-flex flex-column flex-grow-1">

            <!-- Topbar — stays visible behind overlay -->
            <div id="topbar-container">
                <?php include '../01_Includes/topbar.php'; ?>
            </div>

            <!-- Dashboard content blurred behind overlay -->
            <div id="content" style="filter: blur(2px); pointer-events: none; user-select: none;">
                <div class="content-body p-4">
                    <h4 class="text-muted">Dashboard</h4>
                    <p class="text-muted">Your pharmacy dashboard will appear here once approved.</p>
                </div>
            </div>

        </div>
    </div>


    <div class="approval-overlay">
        <div class="approval-card">

            <!-- Brand -->
            <h2 class="approval-title">
                <span class="medi">Welcome to Medi</span><span class="find">Find!</span>
            </h2>
            <p class="approval-subtitle">Setup your pharmacy account!</p>

            <!-- Rejected alert -->
            <?php if ($is_rejected): ?>
                <div class="alert alert-danger py-2 mb-3" style="border-radius: 12px; font-size: 13px;">
                    <i class="bi bi-x-circle-fill me-1"></i>
                    Your application was rejected. Please update and resubmit.
                </div>
            <?php endif; ?>

            <!-- Session error -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger py-2 mb-3" style="border-radius: 12px; font-size: 13px;">
                    <?= htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Setup form -->
            <form method="POST"
                  action="../02_Actions/03_Pharmacy-Admin-CRUD/setup_pharmacy.php">

                <!-- Pharmacy Name -->
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="bi bi-shop"></i>
                    </span>
                    <input type="text"
                           name="pharmacy_name"
                           class="form-control"
                           placeholder="Pharmacy Name"
                           value="<?= htmlspecialchars($pharmacy_name) ?>"
                           <?= $is_pending ? : 'required' ?>>
                </div>

                <!-- Owner Name — editable, defaults to logged in user's full name -->
                <div class="input-group mb-3">
                    <span class="input-group-text">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text"
                           name="owner_name"
                           class="form-control"
                           placeholder="Pharmacy Owner"
                           value="<?= htmlspecialchars($owner_name) ?>"
                           <?= $is_pending ?  : 'required' ?>>
                </div>

                <!-- Submit button -->
                <button type="submit"
                        class="btn-request"
                        <?= $is_pending ? : '' ?>>
                        <?= $is_rejected ? 'Resubmit Application' : 'Request Approval' ?>
                </button>

                <!-- Pending message under button -->
                <?php if ($is_pending): ?>
                    <p class="pending-text">
                        <i class="bi bi-clock me-1"></i>
                        Your pharmacy is pending for approval
                    </p>
                <?php endif; ?>

            </form>

            <!-- Logout -->
            <p class="logout-text mt-3">
                Wrong account?
                <a href="../index.php" class="logout-link">Logout</a>
            </p>

        </div>
    </div>

    <div class="floating-btns">
        <button class="float-btn" onclick="history.back()">
            <span class="material-symbols-outlined bot-icon">smart_toy</span>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../07_Assets/css/js/sidebar_and_topbar.js"></script>
</body>
</html>