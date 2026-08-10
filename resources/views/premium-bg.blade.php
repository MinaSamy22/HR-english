{{-- ═══════════════════════════════════════════════════════════════
    use it in views\backend\layouts\app.blade.php to apply in all pages 

═══════════════════════════════════════════════════════════════ --}}

<style>
    /* ── Premium Background System ── */
    .premium-page-bg {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        overflow: hidden;
        background: #f1f5f9;
    }

    .premium-page-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 70% 50% at 0% 0%, rgba(37, 99, 235, 0.08), transparent 55%),
            radial-gradient(ellipse 60% 45% at 100% 100%, rgba(124, 58, 237, 0.07), transparent 50%);
    }

    .premium-page-bg .premium-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(15, 23, 42, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(15, 23, 42, 0.05) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: linear-gradient(to bottom, black 10%, black 85%, transparent 100%);
        -webkit-mask-image: linear-gradient(to bottom, black 10%, black 85%, transparent 100%);
    }

    .premium-page-bg .premium-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        animation: premiumFloat 8s ease-in-out infinite;
        pointer-events: none;
    }

    .premium-page-bg .premium-orb-1 {
        width: 280px;
        height: 280px;
        top: -80px;
        right: -60px;
        background: rgba(37, 99, 235, 0.08);
    }

    .premium-page-bg .premium-orb-2 {
        width: 220px;
        height: 220px;
        bottom: 10%;
        left: -70px;
        background: rgba(124, 58, 237, 0.07);
        animation-delay: -3s;
    }

    .premium-page-bg .premium-orb-3 {
        width: 140px;
        height: 140px;
        bottom: 35%;
        right: 15%;
        background: rgba(59, 130, 246, 0.06);
        animation-delay: -5s;
    }

    @keyframes premiumFloat {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-18px) scale(1.04); }
    }
</style>

<div class="premium-page-bg">
    <div class="premium-grid"></div>
    <div class="premium-orb premium-orb-1"></div>
    <div class="premium-orb premium-orb-2"></div>
    <div class="premium-orb premium-orb-3"></div>
</div>
