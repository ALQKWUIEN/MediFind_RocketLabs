<!-- filepath: /d:/xampp/htdocs/MediFind_RocketLabs/01_Includes/page-transition-hardcode.php -->
<?php
$mfTransitionPrefix = is_dir('07_Assets') ? '' : '../';
$mfIsPortalSubdir = (strpos($_SERVER['PHP_SELF'] ?? '', '/04_User/') !== false
    || strpos($_SERVER['PHP_SELF'] ?? '', '/05_PharmacyAdmin/') !== false
    || strpos($_SERVER['PHP_SELF'] ?? '', '/06_SystemAdmin/') !== false);
?>
<?php if ($mfIsPortalSubdir): ?>
<link rel="stylesheet" href="<?= $mfTransitionPrefix ?>07_Assets/css/04_Includes CSS/sidebar.css" media="(min-width: 769px)" />
<link rel="stylesheet" href="<?= $mfTransitionPrefix ?>07_Assets/css/04_Includes CSS/topbar.css" />
<?php endif; ?>
<script>
    if ("onpagereveal" in window) {
        document.documentElement.classList.add("mf-vt-supported");
    }

    // Synchronously hydrate cached sidebar & topbar as soon as their containers are parsed (prevents View Transition / FOUC glitch)
    (function () {
        if (!window.sessionStorage) return;
        var targets = {
            "pharmacy-sidebar-container": "../01_Includes/02_pharmacy-sidebar.php",
            "system-sidebar-container": "../01_Includes/03_system-sidebar.php",
            "topbar-container": "../01_Includes/topbar.php"
        };

        function hydrateEarly(el, filePath) {
            if (!el || el.dataset.mfHydrated === "1" || el.children.length > 0) return;
            var cached = sessionStorage.getItem("mf_html_v3_" + filePath);
            if (!cached) return;
            el.innerHTML = cached;
            el.dataset.mfHydrated = "1";
            if (el.id !== "topbar-container") {
                var activeKey = (document.body && document.body.getAttribute("data-active") || "").toLowerCase().trim();
                var currentFile = window.location.pathname.toLowerCase().split("/").pop().replace(".php", "");
                el.querySelectorAll("#sidebar ul li").forEach(function (li) {
                    var a = li.querySelector("a");
                    if (!a) return;
                    var href = a.getAttribute("href") || "";
                    var lf = href.split("/").pop().replace(".php", "").toLowerCase();
                    var lp = lf.split("_")[0];
                    var match = (activeKey && (lf === activeKey || lp === activeKey)) || (currentFile && lf === currentFile);
                    li.classList.toggle("active", Boolean(match));
                });
            }
        }

        var obs = new MutationObserver(function () {
            var remaining = 0;
            Object.keys(targets).forEach(function (id) {
                var el = document.getElementById(id);
                if (el) {
                    hydrateEarly(el, targets[id]);
                } else {
                    remaining++;
                }
            });
            if (remaining === 0 && document.readyState !== "loading") {
                obs.disconnect();
            }
        });
        obs.observe(document.documentElement, { childList: true, subtree: true });
        document.addEventListener("DOMContentLoaded", function () { obs.disconnect(); }, { once: true });
    })();
</script>
<style>
    @view-transition {
        navigation: auto;
    }

    @media (min-width: 992px) {
        #pharmacy-sidebar-container,
        #system-sidebar-container {
            min-width: 260px;
            max-width: 260px;
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #dbeafe;
            flex-shrink: 0;
        }
    }

    #topbar-container {
        min-height: 64px;
        background: #ffffff;
        position: relative;
        z-index: 1045;
    }

    .topbar {
        position: relative;
        z-index: 1045;
    }

    .notif-dropdown {
        z-index: 1060 !important;
    }

    @media (min-width: 769px) {
        #sidebar-container,
        #pharmacy-sidebar-container,
        #system-sidebar-container {
            view-transition-name: mf-sidebar;
        }

        #topbar-container {
            view-transition-name: mf-topbar;
        }
    }

    ::view-transition-old(mf-sidebar),
    ::view-transition-new(mf-sidebar),
    ::view-transition-old(mf-topbar),
    ::view-transition-new(mf-topbar) {
        animation: none;
    }

    @keyframes mfFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Keep old root solid while new root cross-dissolves smoothly on top (prevents white/gray brightness dip) */
    ::view-transition-old(root) {
        animation: none;
    }

    ::view-transition-new(root) {
        animation: 0.22s cubic-bezier(0.22, 1, 0.36, 1) both mfFadeIn;
        mix-blend-mode: normal;
    }

    /* Fallback opacity fade ONLY when native View Transitions are unavailable */
    html:not(.mf-vt-supported) body:not(:has(#content)):not(:has(#sidebar-container)):not(:has(#pharmacy-sidebar-container)):not(:has(#system-sidebar-container)) {
        opacity: 0;
        transition: opacity 0.2s cubic-bezier(0.22, 1, 0.36, 1);
    }

    html:not(.mf-vt-supported) body:not(:has(#content)):not(:has(#sidebar-container)):not(:has(#pharmacy-sidebar-container)):not(:has(#system-sidebar-container)).is-visible {
        opacity: 1;
    }

    html:not(.mf-vt-supported) #content {
        opacity: 0;
        transition: opacity 0.2s cubic-bezier(0.22, 1, 0.36, 1);
    }

    html:not(.mf-vt-supported) #content.is-visible {
        opacity: 1;
    }
</style>

<script>
    (function () {
        var supportsViewTransitions = document.documentElement.classList.contains("mf-vt-supported");

        /* Let native View Transitions handle link/form navigations without JS interception */
        if (supportsViewTransitions) {
            return;
        }

        function getTarget() {
            return document.getElementById("content") || document.body;
        }

        function fadeIn() {
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    getTarget().classList.add("is-visible");
                });
            });
        }

        if (document.readyState === "interactive" || document.readyState === "complete") {
            fadeIn();
        } else {
            document.addEventListener("DOMContentLoaded", fadeIn);
        }

        window.addEventListener("pageshow", function () {
            getTarget().classList.add("is-visible");
        });

        function fadeOutThen(callback) {
            getTarget().classList.remove("is-visible");
            setTimeout(callback, 180);
        }

        document.addEventListener("click", function (e) {
            var link = e.target.closest("a");
            if (!link) return;

            var href = link.getAttribute("href");
            if (
                !href ||
                href.startsWith("#") ||
                href.startsWith("mailto:") ||
                href.startsWith("tel:") ||
                href.startsWith("javascript:") ||
                link.target ||
                e.ctrlKey || e.metaKey || e.shiftKey
            ) return;

            e.preventDefault();
            fadeOutThen(function () {
                window.location.href = href;
            });
        });

        document.addEventListener("submit", function (e) {
            var form = e.target;
            if (form.dataset.noTransition) return;

            e.preventDefault();
            fadeOutThen(function () {
                form.submit();
            });
        });
    })();
</script>
