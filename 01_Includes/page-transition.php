<style>
    html.is-animating body { opacity: 0; }
    body.transition-fade { transition: opacity 1s ease; }
</style>

<script src="https://unpkg.com/swup@4"></script>
    <script src="https://unpkg.com/@swup/head-plugin@2"></script>
    <script>
        const swup = new Swup({
            plugins: [new SwupHeadPlugin()]
        });
</script>