// assets/js/main.js

// ── Load HTML includes ──
function loadHTML(filePath, elementId) {
    return fetch(filePath)
        .then(response => response.text())
        .then(data => {
            document.getElementById(elementId).innerHTML = data;
            if (elementId === 'sidebar-container') {
                highlightActiveMenu();
            }
        })
        .catch(error => console.log(error));
}

// ── Highlight active sidebar link ──
function highlightActiveMenu() {
    const currentPage = window.location.pathname;
    document.querySelectorAll('#sidebar ul li').forEach(li => {
        const link = li.querySelector('a');
        if (link && link.getAttribute('href') === currentPage) {
            li.classList.add('active');
        } else {
            li.classList.remove('active');
        }
    });
}

function initTopbar() {

    // ── Date ──
    const dateEl = document.getElementById('topbar-date-text');
    if (dateEl) {
        const now = new Date();
        dateEl.textContent = now.toLocaleDateString('en-US', {
            weekday: 'short', year: 'numeric', month: 'short', day: 'numeric'
        });
    }


    // ── Notifications & Messages data ──
    let notifications = [
        { id:1, icon:'fas fa-file-alt',            color:'#1d4ed8', text:'<strong>Mark Reyes</strong> submitted a new incident report.', time:'2 min ago',  unread:true  },
        { id:2, icon:'fas fa-exclamation-triangle', color:'#dc2626', text:'<strong>Alert #3</strong> has been flagged as high severity.',  time:'15 min ago', unread:true  },
        { id:3, icon:'fas fa-check-circle',         color:'#059669', text:'Report <strong>#12</strong> was resolved by Admin Cruz.',       time:'1 hr ago',   unread:true  },
        { id:4, icon:'fas fa-user-plus',            color:'#d97706', text:'<strong>New user</strong> Ella Mendoza has registered.',        time:'3 hrs ago',  unread:false },
        { id:5, icon:'fas fa-shield-alt',           color:'#6b7280', text:'Suspicious activity detected at <strong>Main Gate</strong>.',   time:'Yesterday',  unread:false },
    ];

    let messages = [
        { id:1, name:'Maria Santos', initials:'MS', color:'#1d4ed8', preview:'Can you check the incident report I filed?', time:'Just now',   unread:true  },
        { id:2, name:'Admin Cruz',   initials:'AC', color:'#059669', preview:'Report #9 has been assigned to you.',        time:'5 min ago',  unread:true  },
        { id:3, name:'James Tan',    initials:'JT', color:'#d97706', preview:'The fire drill schedule has been updated.',   time:'30 min ago', unread:false },
        { id:4, name:'Grace Dizon',  initials:'GD', color:'#dc2626', preview:'Medical report is ready for your review.',    time:'2 hrs ago',  unread:false },
    ];

    function renderNotifications() {
        const list  = document.getElementById('notifList');
        const badge = document.getElementById('notifBadge');
        if (!list || !badge) return;
        const count = notifications.filter(n => n.unread).length;
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
        list.innerHTML = notifications.map(n => `
            <div class="notif-item ${n.unread ? 'unread' : ''}" onclick="readNotif(${n.id}); event.stopPropagation();">
                <div class="notif-avatar" style="background:${n.color};"><i class="${n.icon}"></i></div>
                <div class="notif-content">
                    <p class="notif-text">${n.text}</p>
                    <span class="notif-time"><i class="fas fa-clock me-1"></i>${n.time}</span>
                </div>
                ${n.unread ? '<div class="notif-unread-dot"></div>' : ''}
            </div>
        `).join('');
    }

    function renderMessages() {
        const list  = document.getElementById('msgList');
        const badge = document.getElementById('msgBadge');
        if (!list || !badge) return;
        const count = messages.filter(m => m.unread).length;
        badge.textContent = count;
        badge.style.display = count > 0 ? 'flex' : 'none';
        list.innerHTML = messages.map(m => `
            <div class="notif-item ${m.unread ? 'unread' : ''}" onclick="readMsg(${m.id}); event.stopPropagation();">
                <div class="notif-avatar" style="background:${m.color}; font-size:0.75rem; font-weight:700;">${m.initials}</div>
                <div class="notif-content">
                    <p class="msg-name">${m.name}</p>
                    <p class="msg-preview ${m.unread ? 'unread-msg' : ''}">${m.preview}</p>
                    <span class="notif-time"><i class="fas fa-clock me-1"></i>${m.time}</span>
                </div>
                ${m.unread ? '<div class="notif-unread-dot"></div>' : ''}
            </div>
        `).join('');
    }

    // expose to global scope so onclick attributes work
    window.readNotif = function(id) {
        const n = notifications.find(x => x.id === id);
        if (n) { n.unread = false; renderNotifications(); }
    };
    window.readMsg = function(id) {
        const m = messages.find(x => x.id === id);
        if (m) { m.unread = false; renderMessages(); }
    };
    window.markAllRead = function(type) {
        if (type === 'notif') { notifications.forEach(n => n.unread = false); renderNotifications(); }
        else { messages.forEach(m => m.unread = false); renderMessages(); }
    };

    renderNotifications();
    renderMessages();
}

window.onload = function () {
    loadHTML('../01_Includes/sidebar.php', 'sidebar-container');
    loadHTML('../01_Includes/topbar.php',  'topbar-container').then(() => {
        initTopbar();

        // ← Reinitialize Bootstrap dropdowns after topbar is injected
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
            new bootstrap.Dropdown(el);
        });
    });

    setTimeout(() => {
        document.addEventListener('click', function (e) {
            if (e.target.closest('#sidebarToggle')) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar) sidebar.classList.toggle('hidden');
            }
        });
    }, 300);
};