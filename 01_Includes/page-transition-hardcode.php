<!-- filepath: /d:/xampp/htdocs/MediFind_RocketLabs/01_Includes/page-transition-hardcode.php -->
<script>
    if ("onpagereveal" in window) {
        document.documentElement.classList.add("mf-vt-supported");
    }
</script>
<style>
    @view-transition {
        navigation: auto;
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
