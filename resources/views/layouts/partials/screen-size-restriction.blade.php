<div id="screenSizeRestriction" class="screen-size-restriction" aria-hidden="true">
    <div class="screen-size-restriction-content">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="screen-size-restriction-icon">
            <path stroke-width="1.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
        </svg>
        <h2 class="h5 fw-bold mb-2">Sorry!</h2>
        <p class="text-muted mb-0">Your screen needs to be wider than 1046px and taller than 812px to view this page.</p>
        <p class="small text-muted mt-2">Please resize your browser window or use a larger device.</p>
    </div>
</div>
<style>
    .screen-size-restriction {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: #f1f5f9;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    .screen-size-restriction[aria-hidden="false"] { display: flex; }
    .screen-size-restriction-content {
        text-align: center;
        max-width: 400px;
    }
    .screen-size-restriction-icon {
        color: #94a3b8;
        margin-bottom: 1rem;
    }
</style>
<script>
(function() {
    var el = document.getElementById('screenSizeRestriction');
    if (!el) return;
    var minW = 1000, minH = 600;
    function check() {
        var w = window.innerWidth, h = window.innerHeight;
        el.setAttribute('aria-hidden', (w >= minW && h >= minH) ? 'true' : 'false');
    }
    check();
    window.addEventListener('resize', check);
})();
</script>
