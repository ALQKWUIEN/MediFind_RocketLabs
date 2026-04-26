/* ════════════════════════════════════════════════════════════
   HTML INCLUDES
   ════════════════════════════════════════════════════════════ */

   function loadHTML(filePath, elementId) {
    return fetch(filePath)
        .then(response => response.text())
        .then(data => {
            document.getElementById(elementId).innerHTML = data;
            if (elementId === 'sidebar-container' || elementId === 'pharmacy-sidebar-container' || elementId === 'system-sidebar-container') {
                highlightActiveMenu();

             
                document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(el => {
                    new bootstrap.Collapse(el.getAttribute('data-bs-target'), { toggle: false });
                });
            }
        })
        .catch(error => console.log(error));
}

/* ════════════════════════════════════════════════════════════
   ACTIVE MENU HIGHLIGHT
   ════════════════════════════════════════════════════════════ */

   function highlightActiveMenu() {
    const currentPage = window.location.pathname;
    let matched = false;

    document.querySelectorAll('#sidebar ul li').forEach(li => {
        const link = li.querySelector('a');
        if (!link) return;
        const href = link.getAttribute('href');
        if (!href || href === '#') return;

        const linkPath = new URL(link.href, window.location.origin).pathname;
        if (linkPath === currentPage) {
            li.classList.add('active');
            matched = true;
        } else {
            li.classList.remove('active');
        }
    });

    // ✅ If no match found, highlight the first nav item by default
    if (!matched) {
        const firstItem = document.querySelector('#sidebar ul li');
        if (firstItem) firstItem.classList.add('active');
    }
}
/* ════════════════════════════════════════════════════════════
   SIDEBAR
   ════════════════════════════════════════════════════════════ */

function initSidebar() {
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.querySelector('.sidebar-overlay');
    const hamburger = document.getElementById('sidebarToggle');  // ✅ matches topbar button ID
    const closeBtn  = document.querySelector('.close-btn');

    if (!sidebar) return;

    // Manually handle dropdown chevrons
    sidebar.querySelectorAll('.sidebar-chevron').forEach(chevron => {
        chevron.addEventListener('click', function () {
            const targetId = this.getAttribute('data-bs-target'); // e.g. "#inventoryDropdown"
            const submenu  = sidebar.querySelector(targetId);
            if (!submenu) return;

            const isOpen = submenu.classList.contains('open');

            // Close all open submenus first
            sidebar.querySelectorAll('.sidebar-submenu.open').forEach(el => {
                el.classList.remove('open');
            });
            sidebar.querySelectorAll('.sidebar-chevron').forEach(el => {
                el.setAttribute('aria-expanded', 'false');
            });

            // Toggle the clicked one
            if (!isOpen) {
                submenu.classList.add('open');
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });

    function openSidebar() {
        sidebar.classList.remove('hidden');
        if (overlay)   overlay.classList.add('active');
        if (hamburger) hamburger.classList.add('active');
    }

    function closeSidebar() {
        sidebar.classList.add('hidden');
        if (overlay)   overlay.classList.remove('active');
        if (hamburger) hamburger.classList.remove('active');
    }

    function handleResize() {
        if (window.innerWidth <= 991) {
            closeSidebar();
        } else {
            sidebar.classList.remove('hidden');
            if (overlay)   overlay.classList.remove('active');
            if (hamburger) hamburger.classList.remove('active');
        }
    }

    // ✅ Attach click listener to hamburger
    if (hamburger) {
        hamburger.addEventListener('click', function () {
            sidebar.classList.contains('hidden') ? openSidebar() : closeSidebar();
        });
    }

    if (overlay) overlay.addEventListener('click', closeSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);

    handleResize();
    window.addEventListener('resize', handleResize);
}

/* ════════════════════════════════════════════════════════════
   PAGE INIT
   ════════════════════════════════════════════════════════════ */

window.onload = function () {

    // Pick the right sidebar for this page
    let sidebarLoad;
    if (document.getElementById('sidebar-container')) {
        sidebarLoad = loadHTML('../01_Includes/01_users-sidebar.php', 'sidebar-container');
    } else if (document.getElementById('pharmacy-sidebar-container')) {
        sidebarLoad = loadHTML('../01_Includes/02_pharmacy-sidebar.php', 'pharmacy-sidebar-container');
    } else if (document.getElementById('system-sidebar-container')) {
        sidebarLoad = loadHTML('../01_Includes/03_system-sidebar.php', 'system-sidebar-container');
    } else {
        sidebarLoad = Promise.resolve();
    }

    const topbarLoad = loadHTML('../01_Includes/topbar.php', 'topbar-container');

    // ✅ Wait for BOTH sidebar and topbar before initializing
    Promise.all([sidebarLoad, topbarLoad]).then(() => {
        initTopbar();
        initSidebar(); // ✅ Now both #sidebar and #sidebarToggle exist in DOM

        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
            new bootstrap.Dropdown(el);
        });
    });
};

/* ════════════════════════════════════════════════════════════
   TOPBAR
   ════════════════════════════════════════════════════════════ */

function initTopbar() {
    // ... rest of your initTopbar() stays exactly the same
}