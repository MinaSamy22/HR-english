{{-- ═══════════════════════════════════════════════════════════════
    Abstract Silk Wave Background — soft corner blob (no hard edges)
    use it in views\backend\layouts\app.blade.php to apply in all pages 
═══════════════════════════════════════════════════════════════ --}}

<style>
    .wave-page-bg {
        position: fixed;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        overflow: hidden;
        background: #ffffff;
    }

    .wave-page-bg svg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }
</style>

<div class="wave-page-bg">
    <svg viewBox="0 0 1920 1080" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="silkFill1" x1="0%" y1="0%" x2="100%" y2="60%">
                <stop offset="0%"   stop-color="#eef0f2" />
                <stop offset="60%"  stop-color="#f7f8f9" />
                <stop offset="100%" stop-color="#ffffff" />
            </linearGradient>
            <linearGradient id="silkFill2" x1="0%" y1="20%" x2="100%" y2="80%">
                <stop offset="0%"   stop-color="#d6d9de" />
                <stop offset="55%"  stop-color="#eef0f2" />
                <stop offset="100%" stop-color="#ffffff" />
            </linearGradient>
            <linearGradient id="silkFill3" x1="10%" y1="0%" x2="90%" y2="100%">
                <stop offset="0%"   stop-color="#e2e4e8" stop-opacity="0.8" />
                <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
            </linearGradient>
            <!-- soft blob gradient, fully round, no rectangular container -->
            <radialGradient id="cornerBlob" cx="50%" cy="50%" r="50%">
                <stop offset="0%"   stop-color="#eef0f2" stop-opacity="0.22" />
                <stop offset="55%"  stop-color="#f7f8f9" stop-opacity="0.12" />
                <stop offset="100%" stop-color="#ffffff" stop-opacity="0" />
            </radialGradient>
            <filter id="softBlur" x="-50%" y="-50%" width="200%" height="200%">
                <feGaussianBlur stdDeviation="40" />
            </filter>
        </defs>

        <!-- soft blurred ellipse anchoring the top-left corner, no hard edges -->
        <ellipse cx="150" cy="80" rx="480" ry="380" fill="url(#cornerBlob)" filter="url(#softBlur)" />

        <!-- large soft silk ribbon, top-left to mid-right -->
        <path d="M -100,-50 
                 C 300,50 500,250 450,420 
                 C 400,600 700,650 1100,500 
                 C 1500,350 1700,200 1920,280 
                 L 1920,-50 Z" 
              fill="url(#silkFill1)" opacity="0.4" />

        <!-- second ribbon layer, offset lower -->
        <path d="M -100,150 
                 C 250,220 480,420 500,600 
                 C 520,780 850,820 1250,680 
                 C 1600,560 1800,500 1920,560 
                 L 1920,-50 L -100,-50 Z" 
              fill="url(#silkFill2)" opacity="0.35" />

        <!-- faint wide wash bottom-right -->
        <path d="M 700,1080 
                 C 1000,900 1400,850 1920,950 
                 L 1920,1080 Z" 
              fill="url(#silkFill3)" opacity="0.6" />

        <!-- fine contour lines tracing the ribbon edges -->
        <g stroke="#c4c7cd" stroke-width="1" opacity="0.4" fill="none">
            <path d="M -100,-30 C 320,70 520,260 470,430 C 420,610 720,660 1120,510 C 1520,360 1720,210 1920,290" />
            <path d="M -100,-10 C 340,90 540,270 490,440 C 440,620 740,670 1140,520 C 1540,370 1740,220 1920,300" />
            <path d="M -100,170 C 270,240 500,440 520,620 C 540,800 870,840 1270,700 C 1620,580 1820,520 1920,580" />
            <path d="M -100,190 C 290,260 520,460 540,640 C 560,820 890,860 1290,720 C 1640,600 1840,540 1920,600" />
        </g>

        <!-- subtle shimmer highlight line -->
        <path d="M -100,80 C 350,150 550,340 480,500 C 420,660 750,700 1150,540 C 1550,390 1750,240 1920,320" 
              stroke="#ffffff" stroke-width="2.5" opacity="0.65" fill="none" />
    </svg>
</div>