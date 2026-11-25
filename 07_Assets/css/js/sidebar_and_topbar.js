/* ════════════════════════════════════════════════════════════
   HTML INCLUDES
   ════════════════════════════════════════════════════════════ */

function applySidebarHTML(elementId, data) {
  const el = document.getElementById(elementId);
  if (!el) return;
  if (el.dataset.mfHydrated !== "1") {
    el.innerHTML = data;
    el.dataset.mfHydrated = "1";
  }
  if (
    elementId === "sidebar-container" ||
    elementId === "pharmacy-sidebar-container" ||
    elementId === "system-sidebar-container"
  ) {
    // Move #logoutModal to document.body so it is never trapped behind backdrop inside sidebar-container
    const injectedModal = el.querySelector("#logoutModal");
    if (injectedModal) {
      document.querySelectorAll("body > #logoutModal").forEach((m) => m.remove());
      document.body.appendChild(injectedModal);
    }
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
  const cacheKey = "mf_html_v3_" + filePath;
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

  // ── Notifications data (Pharmacy Stock Outage & Inventory Samples) ──
  let notifications = [
    {
      id: "sample_1",
      icon: "fas fa-times-circle",
      color: "#dc2626",
      text: "<strong>Stock Outage:</strong> Mefenamic Acid (Ponstan) has <strong>0 units</strong> remaining.",
      time: "Just now",
      unread: true,
    },
    {
      id: "sample_2",
      icon: "fas fa-exclamation-triangle",
      color: "#d97706",
      text: "<strong>Low Stock Warning:</strong> Losartan Potassium (Cozaar) is down to <strong>5 / 10 units</strong>.",
      time: "12 min ago",
      unread: true,
    },
    {
      id: "sample_3",
      icon: "fas fa-exclamation-triangle",
      color: "#d97706",
      text: "<strong>Low Stock Warning:</strong> Amoxicillin (Amoxil) reached <strong>8 / 10 units</strong>.",
      time: "45 min ago",
      unread: true,
    },
    {
      id: "sample_4",
      icon: "fas fa-calendar-times",
      color: "#b45309",
      text: "<strong>Expiring Soon:</strong> Cetirizine (Alnix) batch expires in <strong>18 days</strong>.",
      time: "2 hrs ago",
      unread: false,
    },
    {
      id: "sample_5",
      icon: "fas fa-check-circle",
      color: "#059669",
      text: "<strong>Restock Logged:</strong> Paracetamol (Biogesic) inventory synced with POS.",
      time: "Today",
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
                     onclick="readNotif('${String(n.id).replace(/'/g, "\\'")}'); event.stopPropagation();">
                    <div class="notif-avatar" style="background:${n.color};">
                        <i class="${n.icon}"></i>
                    </div>
                    <div class="notif-content">
                        <p class="notif-text">${n.text}</p>
                        <div class="d-flex align-items-center justify-content-between gap-2 mt-1">
                          <span class="notif-time"><i class="fas fa-clock me-1"></i>${n.time}</span>
                          ${n.inventoryId ? `<button type="button" class="btn btn-sm py-0 px-2" style="font-size:0.72rem;background:#e8f7f2;color:#0f6e56;border:1px solid #bce6d7;border-radius:999px;font-weight:600;" onclick="restockFromNotif(${n.inventoryId}, '${String(n.id).replace(/'/g, "\\'")}'); event.stopPropagation();">Restock</button>` : ""}
                        </div>
                    </div>
                    ${n.unread ? '<div class="notif-unread-dot"></div>' : ""}
                </div>`,
        )
        .join("")
      : `<div class="notif-empty"><i class="fas fa-bell-slash"></i>No notifications</div>`;
  }

  // Load live low-stock alerts for Pharmacy Admin portal
  (function loadLiveInventoryAlerts() {
    if (!window.location.pathname.includes("05_PharmacyAdmin")) return;
    fetch("../05_PharmacyAdmin/api_inventory.php?action=get_alerts", { credentials: "same-origin" })
      .then((r) => r.json())
      .then((data) => {
        if (!data || !data.success || !Array.isArray(data.alerts) || !data.alerts.length) return;
        const liveItems = data.alerts.map((a) => ({
          id: "alert_" + a.Alert_ID,
          alertId: a.Alert_ID,
          inventoryId: a.Inventory_ID,
          icon: a.Alert_Type === "out_of_stock" ? "fas fa-times-circle" : "fas fa-exclamation-triangle",
          color: a.Alert_Type === "out_of_stock" ? "#dc2626" : "#d97706",
          text: a.Alert_Type === "out_of_stock"
            ? `<strong>${a.Medicine_Name}</strong> is <strong>Out of Stock</strong> (0 units).`
            : `<strong>${a.Medicine_Name}</strong> is low on stock (<strong>${a.Current_Qty}</strong> / ${a.Threshold_Used} min).`,
          time: a.Created_At ? a.Created_At.slice(0, 16).replace("T", " ") : "Just now",
          unread: Number(a.Is_Read) === 0
        }));
        notifications = liveItems;
        renderNotifications();
      })
      .catch(() => { });
  })();

  window.restockFromNotif = function (inventoryId, notifId) {
    window.readNotif(notifId);
    const inManage = window.location.pathname.toLowerCase().includes("manage_inventory.php");
    if (inManage) {
      const btn = document.querySelector(`.quick-restock-btn[data-id="${inventoryId}"]`);
      if (btn) {
        btn.click();
        return;
      }
    }
    window.location.href = `../05_PharmacyAdmin/manage_inventory.php?restock=${encodeURIComponent(inventoryId)}`;
  };

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
    const n = notifications.find((x) => String(x.id) === String(id));
    if (n) {
      n.unread = false;
      renderNotifications();
      if (n.alertId) {
        fetch("../05_PharmacyAdmin/api_inventory.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          credentials: "same-origin",
          body: JSON.stringify({ action: "mark_alert_read", alert_id: n.alertId })
        }).catch(() => { });
      }
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
      if (window.location.pathname.includes("05_PharmacyAdmin")) {
        fetch("../05_PharmacyAdmin/api_inventory.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          credentials: "same-origin",
          body: JSON.stringify({ action: "mark_all_alerts_read" })
        }).catch(() => { });
      }
    } else {
      messages.forEach((m) => (m.unread = false));
      renderMessages();
    }
  };

  // ── Initial render ────────────────────────────────────────
  renderNotifications();
  renderMessages();
}
