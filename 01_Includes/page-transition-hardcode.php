<!-- filepath: /d:/xampp/htdocs/MediFind_RocketLabs/01_Includes/page-transition-hardcode.php -->
<style>
    /* Page starts hidden */
    html.is-animating body {
        opacity: 0;
    }

    /* Transition applies to body always */
    body {
        transition: opacity 0.2s ease;
    }
</style>

<script>
    (function () {
        /* Mark as animating immediately — before first paint */
        document.documentElement.classList.add("is-animating");

        /* Fade in only after everything is fully loaded and painted */
        window.addEventListener("load", function () {
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    document.documentElement.classList.remove("is-animating");
                });
            });
        });

        /* Intercept all internal link clicks */
        document.addEventListener("click", function (event) {
            var link = event.target.closest("a");
            if (!link) return;

            var href = link.getAttribute("href");
            var isInternal =
                href &&
                !href.startsWith("#") &&
                !href.startsWith("mailto:") &&
                !href.startsWith("tel:") &&
                !href.startsWith("javascript:") &&
                !link.target &&
                !event.ctrlKey &&
                !event.metaKey &&
                !event.shiftKey;

            if (isInternal) {
                event.preventDefault();

                /* Fade out */
                document.documentElement.classList.add("is-animating");

                /* Navigate after fade completes */
                setTimeout(function () {
                    window.location.href = href;
                }, 200); /* Match the transition duration above */
            }
        });
    })();
</script>