/* ════════════════════════════════════════════════════════════
   HTML INCLUDES
   ════════════════════════════════════════════════════════════ */

function applySidebarHTML(elementId, data) {
  const el = document.getElementById(elementId);
  if (!el) return;
  el.innerHTML = data;
  if (
    elementId === "sidebar-container" ||
    elementId === "pharmacy-sidebar-container" ||
    elementId === "system-sidebar-container"
  ) {
    highlightActiveMenu();
    if (window.bootstrap) {
      document
        .querySelectorAll('[data-bs-toggle="collapse"]')
        .forEach((colEl) => {
          new bootstrap.Collapse(colEl.getAttribute("data-bs-target"), {
            toggle: false,
          });
        });
    }
  }
}

function loadHTML(filePath, elementId) {
  const cacheKey = "mf_html_" + filePath;
  const cached = sessionStorage.getItem(cacheKey);
  if (cached) {
    applySidebarHTML(elementId, cached);
    return Promise.resolve();
  }
  return fetch(filePath)
    .then((response) => response.text())
    .then((data) => {
      sessionStorage.setItem(cacheKey, data);
      applySidebarHTML(elementId, data);
    })
    .catch((error) => console.log(error));
}

/* ════════════════════════════════════════════════════════════
   ACTIVE MENU HIGHLIGHT
   ════════════════════════════════════════════════════════════ */

function highlightActiveMenu() {
  const activeKey = (document.body.getAttribute("data-active") || "").toLowerCase().trim();
  const currentPath = window.location.pathname.toLowerCase();
  const currentFile = currentPath.split("/").pop().replace(".php", "");

  document.querySelectorAll("#sidebar ul li").forEach((li) => {
    const link = li.querySelector("a");
    if (!link) return;
    const href = link.getAttribute("href");
    if (!href || href === "#") return;

    const linkFilename = href.split("/").pop().replace(".php", "").toLowerCase();
    const linkPrefix = linkFilename.split("_")[0]; // e.g. "02"

    let isMatch = false;

    // 1. Direct activeKey matches full filename (e.g. "manage_inventory", "dashboard", "pos", "reports", "profile")
    if (activeKey && linkFilename === activeKey) {
      isMatch = true;
    }
    // 2. Direct activeKey matches prefix (e.g. "01", "02" for user / system admin sidebars)
    else if (activeKey && linkPrefix === activeKey) {
      isMatch = true;
    }
    // 3. Known aliases for Pharmacy Admin (profile / 07_Profile, manage_inventory / 02_ManageInventory)
    else if (
      (activeKey === "profile" || activeKey === "07") &&
      (linkFilename === "profile" || linkFilename === "07_profile")
    ) {
      isMatch = true;
    } else if (
      (activeKey === "manage_inventory" || activeKey === "02" || activeKey === "inventory") &&
      (linkFilename === "manage_inventory" || linkFilename === "02_manageinventory")
    ) {
      isMatch = true;
    }
    // 4. URL fallback: current filename matches link
    else if (
      currentFile &&
      (currentFile === linkFilename ||
        (linkFilename === "manage_inventory" && currentFile === "02_manageinventory") ||
        (linkFilename === "profile" && currentFile === "07_profile"))
    ) {
      isMatch = true;
    }

    if (isMatch) {
      li.classList.add("active");
    } else {
      li.classList.remove("active");
    }
  });
}
/* ════════════════════════════════════════════════════════════
   SIDEBAR
   ════════════════════════════════════════════════════════════ */

function initSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.querySelector(".sidebar-overlay");
  const hamburger = document.getElementById("sidebarToggle"); // ✅ matches topbar button ID
  const closeBtn = document.querySelector(".close-btn");

  if (!sidebar) return;

  // Manually handle dropdown chevrons
  sidebar.querySelectorAll(".sidebar-chevron").forEach((chevron) => {
    chevron.addEventListener("click", function () {
      const targetId = this.getAttribute("data-bs-target"); // e.g. "#inventoryDropdown"
      const submenu = sidebar.querySelector(targetId);
      if (!submenu) return;

      const isOpen = submenu.classList.contains("open");

      // Close all open submenus first
      sidebar.querySelectorAll(".sidebar-submenu.open").forEach((el) => {
        el.classList.remove("open");
      });
      sidebar.querySelectorAll(".sidebar-chevron").forEach((el) => {
        el.setAttribute("aria-expanded", "false");
      });

      // Toggle the clicked one
      if (!isOpen) {
        submenu.classList.add("open");
        this.setAttribute("aria-expanded", "true");
      }
    });
  });

  function openSidebar() {
    sidebar.classList.remove("hidden");
    if (overlay) overlay.classList.add("active");
    if (hamburger) hamburger.classList.add("active");
  }

  function closeSidebar() {
    sidebar.classList.add("hidden");
    if (overlay) overlay.classList.remove("active");
    if (hamburger) hamburger.classList.remove("active");
  }

  function handleResize() {
    if (window.innerWidth <= 991) {
      closeSidebar();
    } else {
      sidebar.classList.remove("hidden");
      if (overlay) overlay.classList.remove("active");
      if (hamburger) hamburger.classList.remove("active");
    }
  }

  // ✅ Attach click listener to hamburger
  if (hamburger) {
    hamburger.addEventListener("click", function () {
      sidebar.classList.contains("hidden") ? openSidebar() : closeSidebar();
    });
  }

  if (overlay) overlay.addEventListener("click", closeSidebar);
  if (closeBtn) closeBtn.addEventListener("click", closeSidebar);

  handleResize();
  window.addEventListener("resize", handleResize);
}

/* ════════════════════════════════════════════════════════════
   PAGE INIT
   ════════════════════════════════════════════════════════════ */

function initPortalChrome() {
  // Pick the right sidebar for this page (skip fetch if already server-rendered)
  let sidebarLoad;
  if (document.getElementById("sidebar")) {
    highlightActiveMenu();
    sidebarLoad = Promise.resolve();
  } else if (document.getElementById("sidebar-container")) {
    sidebarLoad = loadHTML(
      "../01_Includes/01_users-sidebar.php",
      "sidebar-container",
    );
  } else if (document.getElementById("pharmacy-sidebar-container")) {
    sidebarLoad = loadHTML(
      "../01_Includes/02_pharmacy-sidebar.php",
      "pharmacy-sidebar-container",
    );
  } else if (document.getElementById("system-sidebar-container")) {
    sidebarLoad = loadHTML(
      "../01_Includes/03_system-sidebar.php",
      "system-sidebar-container",
    );
  } else {
    sidebarLoad = Promise.resolve();
  }

  const topbarLoad = loadHTML("../01_Includes/topbar.php", "topbar-container");

  // ✅ Wait for BOTH sidebar and topbar before initializing
  Promise.all([sidebarLoad, topbarLoad]).then(() => {
    initTopbar();
    initSidebar(); // ✅ Now both #sidebar and #sidebarToggle exist in DOM

    if (window.bootstrap) {
      document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach((el) => {
        new bootstrap.Dropdown(el);
      });
    }
  });
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initPortalChrome);
} else {
  initPortalChrome();
}

/* ════════════════════════════════════════════════════════════
   TOPBAR
   ════════════════════════════════════════════════════════════ */

function initTopbar() {
  // ── Date display ──────────────────────────────────────────
  const dateEl = document.getElementById("topbar-date-text");
  if (dateEl) {
    dateEl.textContent = new Date().toLocaleDateString("en-US", {
      weekday: "short",
      year: "numeric",
      month: "short",
      day: "numeric",
    });
  }

  // ── Notifications data ────────────────────────────────────
  let notifications = [
    {
      id: 1,
      icon: "fas fa-file-alt",
      color: "#1d4ed8",
      text: "<strong>Mark Reyes</strong> submitted a new incident report.",
      time: "2 min ago",
      unread: true,
    },
    {
      id: 2,
      icon: "fas fa-exclamation-triangle",
      color: "#dc2626",
      text: "<strong>Alert #3</strong> has been flagged as high severity.",
      time: "15 min ago",
      unread: true,
    },
    {
      id: 3,
      icon: "fas fa-check-circle",
      color: "#059669",
      text: "Report <strong>#12</strong> was resolved by Admin Cruz.",
      time: "1 hr ago",
      unread: true,
    },
    {
      id: 4,
      icon: "fas fa-user-plus",
      color: "#d97706",
      text: "<strong>New user</strong> Ella Mendoza has registered.",
      time: "3 hrs ago",
      unread: false,
    },
    {
      id: 5,
      icon: "fas fa-shield-alt",
      color: "#6b7280",
      text: "Suspicious activity detected at <strong>Main Gate</strong>.",
      time: "Yesterday",
      unread: false,
    },
  ];

  // ── Messages data ─────────────────────────────────────────
  let messages = [
    {
      id: 1,
      name: "Maria Santos",
      initials: "MS",
      color: "#1d4ed8",
      preview: "Can you check the incident report I filed?",
      time: "Just now",
      unread: true,
    },
    {
      id: 2,
      name: "Admin Cruz",
      initials: "AC",
      color: "#059669",
      preview: "Report #9 has been assigned to you.",
      time: "5 min ago",
      unread: true,
    },
    {
      id: 3,
      name: "James Tan",
      initials: "JT",
      color: "#d97706",
      preview: "The fire drill schedule has been updated.",
      time: "30 min ago",
      unread: false,
    },
    {
      id: 4,
      name: "Grace Dizon",
      initials: "GD",
      color: "#dc2626",
      preview: "Medical report is ready for your review.",
      time: "2 hrs ago",
      unread: false,
    },
  ];

  // ── Renderers ─────────────────────────────────────────────
  function renderNotifications() {
    const list = document.getElementById("notifList");
    const badge = document.getElementById("notifBadge");
    if (!list || !badge) return;

    const count = notifications.filter((n) => n.unread).length;
    badge.textContent = count;
    badge.style.display = count > 0 ? "flex" : "none";

    list.innerHTML = notifications.length
      ? notifications
        .map(
          (n) => `
                <div class="notif-item ${n.unread ? "unread" : ""}"
                     onclick="readNotif(${n.id}); event.stopPropagation();">
                    <div class="notif-avatar" style="background:${n.color};">
                        <i class="${n.icon}"></i>
                    </div>
                    <div class="notif-content">
                        <p class="notif-text">${n.text}</p>
                        <span class="notif-time"><i class="fas fa-clock me-1"></i>${n.time}</span>
                    </div>
                    ${n.unread ? '<div class="notif-unread-dot"></div>' : ""}
                </div>`,
        )
        .join("")
      : `<div class="notif-empty"><i class="fas fa-bell-slash"></i>No notifications</div>`;
  }

  function renderMessages() {
    const list = document.getElementById("msgList");
    const badge = document.getElementById("msgBadge");
    if (!list || !badge) return;

    const count = messages.filter((m) => m.unread).length;
    badge.textContent = count;
    badge.style.display = count > 0 ? "flex" : "none";

    list.innerHTML = messages.length
      ? messages
        .map(
          (m) => `
                <div class="notif-item ${m.unread ? "unread" : ""}"
                     onclick="readMsg(${m.id}); event.stopPropagation();">
                    <div class="notif-avatar"
                         style="background:${m.color};font-size:.75rem;font-weight:700;">
                        ${m.initials}
                    </div>
                    <div class="notif-content">
                        <p class="msg-name">${m.name}</p>
                        <p class="msg-preview ${m.unread ? "unread-msg" : ""}">${m.preview}</p>
                        <span class="notif-time"><i class="fas fa-clock me-1"></i>${m.time}</span>
                    </div>
                    ${m.unread ? '<div class="notif-unread-dot"></div>' : ""}
                </div>`,
        )
        .join("")
      : `<div class="notif-empty"><i class="fas fa-comment-slash"></i>No messages</div>`;
  }

  // ── Actions (must be on window so onclick="" in HTML can reach them) ──
  window.readNotif = function (id) {
    const n = notifications.find((x) => x.id === id);
    if (n) {
      n.unread = false;
      renderNotifications();
    }
  };

  window.readMsg = function (id) {
    const m = messages.find((x) => x.id === id);
    if (m) {
      m.unread = false;
      renderMessages();
    }
  };

  window.markAllRead = function (type) {
    if (type === "notif") {
      notifications.forEach((n) => (n.unread = false));
      renderNotifications();
    } else {
      messages.forEach((m) => (m.unread = false));
      renderMessages();
    }
  };

  // ── Initial render ────────────────────────────────────────
  renderNotifications();
  renderMessages();
}
