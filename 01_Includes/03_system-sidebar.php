<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sidebar</title>
  <link rel="stylesheet" href="../07_Assets/node_modules/material-symbols/outlined.css" />
  <link rel="icon" href="../07_Assets/images/logo.png" type="image/png" />
  <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../07_Assets/css/04_Includes CSS/sidebar.css" />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap"
    rel="stylesheet" />
</head>

<body>

<nav id="sidebar">
    <!-- Close Button -->
    <div class="close-btn">
        <a href="#" class="btn btn-sm" id="closeSidebar" onclick="document.getElementById('sidebar').classList.toggle('hidden')">
            <span class="material-symbols-outlined">close</span>
        </a>
    </div>

    <!-- Sidebar Brand / Logo -->
    <div class="sidebar-brand">
        <img src="../07_Assets/images/logo.png" alt="MediFind Logo" width="80">
        <div class="hero-title">Medi<span>Find</span></div>
        <div class="hero-subtitle">Malaybalay Medicine Availability Checker</div>
    </div>

    <hr class="sidebar-divider" />

    <!-- Main Navigation -->
<!-- Main Navigation -->
<ul class="list-unstyled sidebar-nav">
    <li>
        <a href="../06_SystemAdmin/01_Dashboard.php">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="sidebar-label">Dashboard</span>
        </a>
    </li>

    <li>
        <a href="../06_SystemAdmin/02_Manage-Users.php">
            <span class="material-symbols-outlined">supervisor_account</span>
            <span class="sidebar-label">Manage Users</span>
        </a>
    </li>

    <li>
        <a href="../06_SystemAdmin/03_Pharmacies.php">
            <span class="material-symbols-outlined">local_hospital</span>
            <span class="sidebar-label">Pharmacies</span>
        </a>
    </li>

    <li>
        <a href="../06_SystemAdmin/04_Reports.php">
            <span class="material-symbols-outlined">note_stack</span>
            <span class="sidebar-label">Reports</span>
        </a>
    </li>

    
    <li>
        <a href="../06_SystemAdmin/05_Settings.php">
            <span class="material-symbols-outlined">Settings</span>
            <span class="sidebar-label">Settings</span>
        </a>
    </li>


    
    <li>
        <a href="../06_SystemAdmin/06_Profile.php">
            <span class="material-symbols-outlined">person</span>
            <span class="sidebar-label">Profile</span>
        </a>
    </li>
</ul>

    <!-- Logout Section -->
    <div class="sidebar-logout">
        <hr class="sidebar-divider" />
        <ul class="list-unstyled sidebar-nav">
            <li>
                <a href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <span class="material-symbols-outlined" style="color: #87a199; font-size: 1.5rem">logout</span>
                    <span class="sidebar-label">Log Out</span>
                </a>
            </li>
        </ul>
    </div>
</nav>



<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden">
            <div class="modal-header" style="background: #1d9e75; border: none;">
                <h5 class="modal-title text-white" id="logoutModalLabel">
                    <i class="fa fa-sign-out me-2"></i> Confirm Logout
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div style="font-size: 3rem; color: #1d9e75; margin-bottom: 1rem">
                    <i class="fa fa-circle-question"></i>
                </div>
                <h6 style="font-weight: 600; color: #374151">Are you sure you want to log out?</h6>
                <p style="font-size: 0.85rem; color: #9ca3af">You will be redirected to the login page.</p>
            </div>
            <div class="modal-footer justify-content-center" style="border: none">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                <a href="../index.php" class="btn px-4 text-white" style="background: #1d9e75;">Yes, Log Out</a>
            </div>
        </div>
    </div>
</div>


<!-- JavaScript -->
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    // Highlight the active page in the sidebar
    const currentPage = window.location.pathname;
    document.querySelectorAll("#sidebar ul li").forEach((li) => {
        const link = li.querySelector("a");
        if (link && link.getAttribute("href") === currentPage) {
            li.classList.add("active");
        } else {
            li.classList.remove("active");
        }
    });
</script>
  
</body>

</html>

  <!-- Manage Inventory — split nav + dropdown
  li class="has-dropdown">

div class="sidebar-nav-row">

    Main link — navigates to Manage Inventory page
    a href="../05_PharmacyAdmin/02_ManageInventory.php" class="sidebar-nav-link">
        span class="material-symbols-outlined">table_view</>
        span class="sidebar-label">Inventory</>
    a>

    Separate chevron button — only toggles the sub-menu
    <button class="sidebar-chevron"
            data-bs-toggle="collapse"
            data-bs-target="#inventoryDropdown"
            aria-expanded="false"
            aria-label="Toggle inventory submenu">
        <span class="material-symbols-outlined">chevron_right</span>
    </button> -->

<!-- div> -->

<!-- Sub-menu -->
<!-- <ul class="collapse list-unstyled sidebar-submenu" id="inventoryDropdown">
        <li>
            <a href="../05_PharmacyAdmin/03_Add_Inventory.php">
                <span class="material-symbols-outlined">add_circle</span>
                <span class="sidebar-label">Add Medicine</span>
            </a>
        </li>
        <li>
            <a href="../05_PharmacyAdmin/04_Update_Inventory.php">
                <span class="material-symbols-outlined">edit</span>
                <span class="sidebar-label">Update Stock</span>
            </a>
        </li>
        <li>
            <a href="../05_PharmacyAdmin/05_View_Inventory.php">
                <span class="material-symbols-outlined">visibility</span>
                <span class="sidebar-label">View Inventory</span>
            </a>
        </li>
</ul> -->
<!-- 
/li> -->