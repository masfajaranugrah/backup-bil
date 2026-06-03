<style>
    html,
    body {
        touch-action: pan-x pan-y;
    }
</style>
<script>
    let lastTouchEnd = 0;

    document.addEventListener('gesturestart', function (event) {
        event.preventDefault();
    });

    document.addEventListener('gesturechange', function (event) {
        event.preventDefault();
    });

    document.addEventListener('gestureend', function (event) {
        event.preventDefault();
    });

    document.addEventListener('touchend', function (event) {
        const now = Date.now();

        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }

        lastTouchEnd = now;
    }, false);

    document.addEventListener('wheel', function (event) {
        if (event.ctrlKey) {
            event.preventDefault();
        }
    }, { passive: false });
</script>
