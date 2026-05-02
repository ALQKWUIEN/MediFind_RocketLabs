<!-- filepath: /d:/xampp/htdocs/MediFind_RocketLabs/01_Includes/page-transition-hardcode.php -->
<style>
    body {
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    body.is-visible {
        opacity: 1;
    }
</style>

<script>
    (function () {

    
        function fadeIn() {
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    document.body.classList.add("is-visible");
                });
            });
        }

        if (document.readyState === "complete") {
            fadeIn();
        } else {
            window.addEventListener("load", fadeIn);
        }

        /* ── Fade out helper ── */
        function fadeOutThen(callback) {
            document.body.classList.remove("is-visible");
            setTimeout(callback, 250); /* match transition duration */
        }

        /* ── Intercept internal link clicks ── */
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

        /* ── Intercept form submits ── */
        document.addEventListener("submit", function (e) {
            var form = e.target;

            /* Only intercept GET/POST forms navigating away (not fetch/xhr forms) */
            if (form.dataset.noTransition) return;

            e.preventDefault();
            fadeOutThen(function () {
                form.submit();
            });
        });

    })();
</script>