<?php
    include_once '../02_Actions/GlobalVariables.php';
    include_once '../00_Config/config.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Topbar</title>
    <!-- <link rel="icon" href="/07_Assets/images/logo.png" type="image/png"> -->
    <link href="../07_Assets/bootstrap" rel="stylesheet" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../07_Assets/css/04_Includes CSS/topbar.css" />

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  </head>
  <body>
    <!--  Topbar  -->
    <div class="topbar">
      <!-- Hamburger -->
      <button id="sidebarToggle" class="topbar-hamburger" type="button">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Page Title + Breadcrumb -->
      <!-- <div class="topbar-left">
            <h1 class="topbar-title" id="topbar-page-title">Home</h1>
            <p class="topbar-breadcrumb">
                <a href="/admin/dashboard.html"><i class="fas fa-house me-1"></i>Home</a>
                <span class="mx-1">/</span>
                <span id="topbar-breadcrumb-current">Home</span>
            </p>
        </div> -->

      <!-- Right Controls -->
      <div class="topbar-right">
        <!-- Date -->
        <div class="topbar-date">
          <i class="fas fa-calendar-alt" style="color: #146f50"></i>
          <span id="topbar-date-text"></span>
        </div>

        <!-- Messages -->
        <div class="dropdown">
          <button
            class="topbar-icon-btn"
            type="button"
            id="msgDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            <i class="fas fa-comment-dots"></i>
            <span class="topbar-badge" id="msgBadge">2</span>
          </button>
          <div
            class="dropdown-menu dropdown-menu-end notif-dropdown"
            aria-labelledby="msgDropdown"
          >
            <div class="notif-header">
              <p class="notif-header-title">
                <i class="fas fa-comment-dots me-2" style="color: #146f50"></i
                >Messages
              </p>
              <button
                class="notif-mark-all"
                type="button"
                onclick="
                  markAllRead('msg');
                  event.stopPropagation();
                "
              >
                Mark all read
              </button>
            </div>
            <div class="notif-list" id="msgList"></div>
            <div class="notif-footer"><a href="#">View all messages</a></div>
          </div>
        </div>

        <!-- Notifications -->
        <div class="dropdown">
          <button
            class="topbar-icon-btn"
            type="button"
            id="notifDropdown"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            <i class="fas fa-bell"></i>
            <span class="topbar-badge" id="notifBadge">3</span>
          </button>
          <div
            class="dropdown-menu dropdown-menu-end notif-dropdown"
            aria-labelledby="notifDropdown"
          >
            <div class="notif-header">
              <p class="notif-header-title">
                <i class="fas fa-bell me-2" style="color: #146f50"></i
                >Notifications
              </p>
              <button
                class="notif-mark-all"
                type="button"
                onclick="
                  markAllRead('notif');
                  event.stopPropagation();
                "
              >
                Mark all read
              </button>
            </div>
            <div class="notif-list" id="notifList"></div>
            <div class="notif-footer">
              <a href="#">View all notifications</a>
            </div>
          </div>
        </div>

        <!-- User Info -->
        <div class="topbar-user-info">
          <p class="topbar-user-name" > <?= htmlspecialchars($_SESSION['username'])?></p>
          <p class="topbar-user-role" > <?= htmlspecialchars($_SESSION['role_label'])?></p>
        </div>

        <!-- Avatar with dropdown -->
        <div class="dropdown">
          <button
            class="topbar-avatar-btn"
            type="button"
            data-bs-toggle="dropdown"
          >
            <img
              src="../07_Assets/images/person.jpg"
              alt="User"
              class="topbar-avatar-img"
            />
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="/admin/profile.html"
                ><i class="fas fa-user me-2"></i>Profile</a
              >
            </li>
            <li><hr class="dropdown-divider" /></li>
            <li>
              <a class="dropdown-item text-success" href="../index.php"
                ><i class="fas fa-sign-out-alt me-2"></i>Logout</a
              >
            </li>
          </ul>
        </div>
      </div>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
      const sidebar = document.getElementById("sidebar");
      const sidebarToggle = document.getElementById("sidebarToggle");

      sidebarToggle.addEventListener("click", () => {
        sidebar.classList.toggle("hidden"); // uses your existing #sidebar.hidden CSS
      });
    </script>
    
    <script>
      //  Current date
      const dateEl = document.getElementById("topbar-date-text");
      if (dateEl) {
        const now = new Date();
        dateEl.textContent = now.toLocaleDateString("en-US", {
          weekday: "short",
          year: "numeric",
          month: "short",
          day: "numeric",
        });
      }

      //  Auto page title + breadcrumb
      const pageMap = {
        dashboard: "Dashboard",
        profile: "Profile",
        reports: "Reports",
        analytics: "Analytics",
      };
      const path = window.location.pathname;
      const matched = Object.keys(pageMap).find((key) => path.includes(key));
      if (matched) {
        const label = pageMap[matched];
        const t = document.getElementById("topbar-page-title");
        const c = document.getElementById("topbar-breadcrumb-current");
        if (t) t.textContent = label;
        if (c) c.textContent = label;
      }

      //  NOTIFICATIONS & MESSAGES DATA
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

      function renderNotifications() {
        const list = document.getElementById("notifList");
        const badge = document.getElementById("notifBadge");
        const count = notifications.filter((n) => n.unread).length;
        badge.textContent = count;
        badge.style.display = count > 0 ? "flex" : "none";
        if (!notifications.length) {
          list.innerHTML = `<div class="notif-empty"><i class="fas fa-bell-slash"></i>No notifications</div>`;
          return;
        }
        list.innerHTML = notifications
          .map(
            (n) => `
                <div class="notif-item ${n.unread ? "unread" : ""}" onclick="readNotif(${n.id}); event.stopPropagation();">
                    <div class="notif-avatar" style="background:${n.color};"><i class="${n.icon}"></i></div>
                    <div class="notif-content">
                        <p class="notif-text">${n.text}</p>
                        <span class="notif-time"><i class="fas fa-clock me-1"></i>${n.time}</span>
                    </div>
                    ${n.unread ? '<div class="notif-unread-dot"></div>' : ""}
                </div>
            `,
          )
          .join("");
      }

      function renderMessages() {
        const list = document.getElementById("msgList");
        const badge = document.getElementById("msgBadge");
        const count = messages.filter((m) => m.unread).length;
        badge.textContent = count;
        badge.style.display = count > 0 ? "flex" : "none";
        if (!messages.length) {
          list.innerHTML = `<div class="notif-empty"><i class="fas fa-comment-slash"></i>No messages</div>`;
          return;
        }
        list.innerHTML = messages
          .map(
            (m) => `
                <div class="notif-item ${m.unread ? "unread" : ""}" onclick="readMsg(${m.id}); event.stopPropagation();">
                    <div class="notif-avatar" style="background:${m.color}; font-size:0.75rem; font-weight:700;">${m.initials}</div>
                    <div class="notif-content">
                        <p class="msg-name">${m.name}</p>
                        <p class="msg-preview ${m.unread ? "unread-msg" : ""}">${m.preview}</p>
                        <span class="notif-time"><i class="fas fa-clock me-1"></i>${m.time}</span>
                    </div>
                    ${m.unread ? '<div class="notif-unread-dot"></div>' : ""}
                </div>
            `,
          )
          .join("");
      }

      function readNotif(id) {
        const n = notifications.find((x) => x.id === id);
        if (n) {
          n.unread = false;
          renderNotifications();
        }
      }
      function readMsg(id) {
        const m = messages.find((x) => x.id === id);
        if (m) {
          m.unread = false;
          renderMessages();
        }
      }
      function markAllRead(type) {
        if (type === "notif") {
          notifications.forEach((n) => (n.unread = false));
          renderNotifications();
        } else {
          messages.forEach((m) => (m.unread = false));
          renderMessages();
        }
      }

      renderNotifications();
      renderMessages();
    </script>
  </body>
</html>
