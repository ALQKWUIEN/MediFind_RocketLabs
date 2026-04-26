<!-- filepath: /d:/xampp/htdocs/MediFind_RocketLabs/01_Includes/02_pharmacy-sidebar.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>

    <!-- Fonts and Icons -->
    <link rel="stylesheet" href="../07_Assets/node_modules/material-symbols/outlined.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap and Custom CSS -->
    <link href="../07_Assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../07_Assets/css/04_Includes CSS/sidebar.css">

    <!-- Favicon -->
    <link rel="icon" href="../07_Assets/images/logo.png" type="image/png">
</head>

<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <!-- Close Button (Visible on Mobile) -->
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

        <hr class="sidebar-divider">

        <!-- Main Navigation -->
        <ul class="list-unstyled sidebar-nav">
            <li>
                <a href="../05_PharmacyAdmin/01_Dashboard.php">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="sidebar-label">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="../05_PharmacyAdmin/02_ManageInventory.php">
                    <span class="material-symbols-outlined">table_view</span>
                    <span class="sidebar-label">Manage Inventory</span>
                </a>
            </li>
            <li>
                <a href="../05_PharmacyAdmin/06_Reports.php">
                    <span class="material-symbols-outlined">article</span>
                    <span class="sidebar-label">Reports</span>
                </a>
            </li>
            <li>
                <a href="../05_PharmacyAdmin/07_Profile.php">
                    <span class="material-symbols-outlined">person</span>
                    <span class="sidebar-label">Profile</span>
                </a>
            </li>
        </ul>

        <!-- Logout Section -->
        <div class="sidebar-logout">
            <hr class="sidebar-divider">
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