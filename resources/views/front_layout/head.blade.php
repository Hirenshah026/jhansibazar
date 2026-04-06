<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="turbo-visit-control" content="reload">
<title>Jhansi Bazaar — Apna Sheher, Apna Platform</title>

<script src="https://unpkg.com/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
<style>
    /* Turbo loading bar color */
    .turbo-progress-bar {
        background: #3182ce;
        height: 4px;
    }

    /* Jab content load ho raha ho, frame slightly dim ho jaye */
    turbo-frame[aria-busy="true"] {
        opacity: 0.5;
        transition: opacity 0.2s ease-in-out;
        pointer-events: none;
    }
</style>

<script src="https://cdn.tailwindcss.com/3.4.17"></script>
<link
    href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap"
    rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    saffron: {
                        50: '#FFF8F0',
                        100: '#FFE8CC',
                        200: '#FFD199',
                        300: '#FFB566',
                        400: '#FF9933',
                        500: '#FF6B35',
                        600: '#E85A20',
                        700: '#CC4400',
                        800: '#A33300',
                        900: '#7A2200'
                    },
                    gold: {
                        50: '#FFFBEB',
                        100: '#FEF3C7',
                        200: '#FDE68A',
                        300: '#FCD34D',
                        400: '#FBBF24',
                        500: '#F59E0B',
                        600: '#D97706',
                        700: '#B45309'
                    },
                    forest: {
                        50: '#F0FDF4',
                        100: '#DCFCE7',
                        500: '#22C55E',
                        600: '#16A34A',
                        700: '#15803D'
                    },
                    ink: {
                        50: '#F8F7F4',
                        100: '#EFEDE8',
                        200: '#D5D0C8',
                        300: '#B5AEA3',
                        400: '#8A8278',
                        500: '#5C5650',
                        600: '#3D3830',
                        700: '#2A2520',
                        800: '#1A1713',
                        900: '#0D0B09'
                    },
                    rose: {
                        50: '#FFF1F2',
                        500: '#F43F5E',
                        600: '#E11D48'
                    }
                },
                fontFamily: {
                    display: ['"Baloo 2"', 'sans-serif'],
                    body: ['Poppins', 'sans-serif']
                }
            }
        }
    };
</script>
<style>
    * {
        font-family: 'Poppins', sans-serif;
        box-sizing: border-box
    }

    h1,
    h2,
    h3,
    .font-display {
        font-family: 'Baloo 2', sans-serif
    }

    ::-webkit-scrollbar {
        width: 0;
        height: 0
    }

    .screen {
        display: none
    }

    .screen.active {
        display: block
    }

    @keyframes fadeSlideUp {
        from {
            opacity: 0;
            transform: translateY(16px)
        }

        to {
            opacity: 1;
            transform: translateY(0)
        }
    }

    .fade-up {
        animation: fadeSlideUp .35s cubic-bezier(.4, 0, .2, 1) both
    }

    @keyframes popIn {
        0% {
            transform: scale(.7);
            opacity: 0
        }

        70% {
            transform: scale(1.05)
        }

        100% {
            transform: scale(1);
            opacity: 1
        }
    }

    .pop-in {
        animation: popIn .4s cubic-bezier(.34, 1.56, .64, 1) both
    }

    @keyframes shimmer {
        0% {
            background-position: -200% 0
        }

        100% {
            background-position: 200% 0
        }
    }

    .shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite
    }

    @keyframes coinPulse {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(245, 158, 11, .5)
        }

        60% {
            box-shadow: 0 0 0 10px rgba(245, 158, 11, 0)
        }
    }

    .coin-pulse {
        animation: coinPulse 2s infinite
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0
        }

        to {
            transform: translateY(0);
            opacity: 1
        }
    }

    .slide-up {
        animation: slideUp .4s cubic-bezier(.34, 1.2, .64, 1) both
    }

    @keyframes ticker {
        0% {
            transform: translateX(100%)
        }

        100% {
            transform: translateX(-100%)
        }
    }

    .ticker {
        animation: ticker 18s linear infinite
    }

    .card-hover {
        transition: all .2s cubic-bezier(.4, 0, .2, 1)
    }

    .card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 32px rgba(255, 107, 53, .18)
    }

    .btn-press:active {
        transform: scale(.97)
    }

    .nav-pill.active {
        color: #FF6B35;
        background: #FFF8F0
    }

    .cat-active {
        background: #FF6B35 !important;
        color: white !important;
        border-color: #FF6B35 !important
    }

    input:focus,
    textarea:focus,
    select:focus {
        outline: 2px solid #FF6B35;
        outline-offset: 1px
    }

    .wheel-canvas {
        cursor: pointer;
        transition: filter .2s
    }

    .wheel-canvas:hover {
        filter: drop-shadow(0 0 12px rgba(255, 107, 53, .4))
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0)
        }

        50% {
            transform: translateY(-6px)
        }
    }

    .float {
        animation: float 3s ease-in-out infinite
    }

    .badge-new {
        background: linear-gradient(135deg, #FF6B35, #FF9933);
        color: white;
        font-size: 9px;
        padding: 2px 6px;
        border-radius: 20px;
        font-weight: 700;
        letter-spacing: .5px
    }

    .gradient-brand {
        /* background: linear-gradient(135deg, #FF6B35 0%, #FF9933 50%, #FFB566 100%) */
        background-color: #2E7D32
    }

    .gradient-dark {
        background: linear-gradient(135deg, #1A1713 0%, #2A2520 100%)
    }

    .gradient-gold {
        background: linear-gradient(135deg, #F59E0B 0%, #FBBF24 100%)
    }

    .text-gradient {
        background: linear-gradient(135deg, #FF6B35, #FF9933);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text
    }

    .glass {
        background: rgba(255, 255, 255, .15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, .2)
    }

    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent)
    }

    @keyframes spin-ease {
        from {
            transform: rotate(0)
        }

        to {
            transform: rotate(var(--final-rotation))
        }
    }

    .progress-fill {
        transition: width 1.5s cubic-bezier(.4, 0, .2, 1)
    }

    .tab-active {
        color: #FF6B35;
        border-bottom: 2.5px solid #FF6B35
    }

    .notification-dot::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 8px;
        height: 8px;
        background: #FF6B35;
        border-radius: 50%;
        border: 2px solid white
    }
    .hidden
    {
        display: none !important;
    }
</style>
