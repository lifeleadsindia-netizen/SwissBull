<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SYNC TRADE — Your Gateway to the Multi-Chain World</title>
    
    <!-- FAVICONS ICON -->
    <link rel="icon" type="image/png" href="{{ asset('logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('logo/favicon/site.webmanifest') }}" />
    
    <meta name="description"
        content="SYNC TRADE is a multi-platform universal crypto wallet supporting 100+ blockchains and 3000+ tokens. Self-custodial, multi-chain, DApp-ready.">
    <meta name="theme-color" content="#080A14">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #080A14;
            --surface: #0E1122;
            --surface2: #141833;
            --surface3: #1C2245;
            --line: #222850;
            --line2: #333c6e;
            --txt: #EEF1FB;
            --txt2: #B4BDDB;
            --mut: #7B85AC;
            --blue: #3D7BFF;
            --indigo: #6B5CFF;
            --cyan: #33D6F0;
            --violet: #9B6BFF;
            --brand: linear-gradient(120deg, #3D7BFF 0%, #6B5CFF 52%, #9B6BFF 100%);
            --brand2: linear-gradient(120deg, #33D6F0, #3D7BFF 60%, #6B5CFF);
            --brand-soft: linear-gradient(120deg, rgba(61, 123, 255, .16), rgba(155, 107, 255, .14));
            --up: #2FCF8E;
            --down: #F0566E;
        }

        * {
            box-sizing: border-box
        }

        html {
            scroll-behavior: smooth;
            overflow-x: clip
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--txt);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            max-width: 100vw;
            -webkit-font-smoothing: antialiased
        }

        a {
            text-decoration: none;
            color: inherit
        }

        img,
        svg,
        canvas {
            display: block;
            max-width: 100%
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
            font-variant-numeric: tabular-nums
        }

        .disp {
            font-family: 'Space Grotesk', sans-serif
        }

        ::selection {
            background: var(--indigo);
            color: #fff
        }

        .gtext {
            background: var(--brand);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent
        }

        .gtext2 {
            background: var(--brand2);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent
        }

        #bg3d {
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none
        }

        .amb {
            position: fixed;
            inset: 0;
            z-index: -2;
            pointer-events: none;
            overflow: hidden
        }

        .amb span {
            position: absolute;
            border-radius: 50%;
            filter: blur(150px)
        }

        .amb .g1 {
            width: 620px;
            height: 620px;
            background: #3D7BFF;
            top: -240px;
            left: -160px;
            opacity: .22
        }

        .amb .g2 {
            width: 560px;
            height: 560px;
            background: #9B6BFF;
            top: -120px;
            right: -180px;
            opacity: .18
        }

        .amb .g3 {
            width: 520px;
            height: 520px;
            background: #33D6F0;
            bottom: -260px;
            left: 42%;
            opacity: .12
        }

        header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: color-mix(in srgb, var(--bg) 82%, transparent);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--line)
        }

        .hdr {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
            height: 72px;
            display: flex;
            align-items: center;
            gap: 22px
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            flex-shrink: 0
        }

        .brand img {
            height: 30px;
            width: auto
        }

        .desk-nav {
            display: none;
            align-items: center;
            gap: 28px;
            margin-left: 12px
        }

        @media(min-width:1024px) {
            .desk-nav {
                display: flex
            }
        }

        .navlink {
            color: var(--txt2);
            font-weight: 500;
            font-size: 14.5px;
            position: relative;
            transition: color .15s;
            cursor: pointer
        }

        .navlink:hover {
            color: var(--txt)
        }

        .navlink::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -7px;
            height: 2px;
            width: 0;
            background: var(--brand);
            border-radius: 2px;
            transition: width .25s
        }

        .navlink:hover::after {
            width: 100%
        }

        .hdr .right {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-left: auto
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 11px;
            display: grid;
            place-items: center;
            color: var(--txt2);
            border: 1px solid var(--line);
            background: var(--surface2);
            cursor: pointer
        }

        @media(min-width:1024px) {
            #burger {
                display: none
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: .18s;
            border: 1px solid transparent;
            font-size: 14px;
            line-height: 1;
            white-space: nowrap;
            font-family: inherit
        }

        .btn-primary {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 8px 30px -10px rgba(107, 92, 255, .7)
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 40px -10px rgba(107, 92, 255, .9)
        }

        .btn-outline {
            background: transparent;
            border-color: var(--line2);
            color: var(--txt)
        }

        .btn-outline:hover {
            border-color: var(--indigo);
            color: #fff;
            background: var(--brand-soft)
        }

        .btn-ghost {
            background: var(--surface2);
            border-color: var(--line);
            color: var(--txt)
        }

        .btn-ghost:hover {
            border-color: var(--indigo)
        }

        .btn-lg {
            padding: 15px 30px;
            font-size: 15px;
            border-radius: 14px
        }

        .btn-sm {
            padding: 9px 17px;
            font-size: 13px
        }

        a.wc-top {
            display: none
        }

        @media(min-width:1024px) {
            a.wc-top {
                display: inline-flex
            }
        }

        @media(max-width:420px) {
            .hdr .btn-primary .wl {
                display: none
            }

            .hdr .btn-primary {
                padding: 9px 12px
            }
        }

        .mobile-menu {
            display: none;
            border-top: 1px solid var(--line);
            background: var(--surface)
        }

        .mobile-menu.open {
            display: block;
            animation: mmIn .2s ease
        }

        @keyframes mmIn {
            from {
                opacity: 0;
                transform: translateY(-6px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        @media(min-width:1024px) {

            .mobile-menu,
            .mobile-menu.open {
                display: none
            }
        }

        .mm-inner {
            padding: 14px 22px;
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .mm-inner .navlink {
            padding: 10px 2px
        }

        .mm-inner .btn {
            width: 100%;
            margin-top: 6px
        }

        .wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px
        }

        .sec {
            padding: 80px 0
        }

        .kick {
            font-family: 'JetBrains Mono';
            font-weight: 600;
            font-size: 11.5px;
            letter-spacing: .22em;
            text-transform: uppercase
        }

        .h-title {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            letter-spacing: -.02em;
            line-height: 1.08
        }

        .sub {
            color: var(--mut)
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            font-size: 12.5px;
            font-weight: 600;
            padding: 8px 15px;
            border-radius: 100px;
            background: var(--brand-soft);
            border: 1px solid color-mix(in srgb, var(--indigo) 34%, transparent);
            color: var(--cyan);
            font-family: 'JetBrains Mono'
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--up);
            box-shadow: 0 0 0 0 var(--up);
            animation: pulse 2.2s infinite
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(47, 207, 142, .6)
            }

            70% {
                box-shadow: 0 0 0 9px rgba(47, 207, 142, 0)
            }

            100% {
                box-shadow: 0 0 0 0 rgba(47, 207, 142, 0)
            }
        }

        .card {
            position: relative;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            transition: transform .22s, border-color .22s
        }

        .glass {
            background: linear-gradient(160deg, rgba(255, 255, 255, .04), transparent 60%), var(--surface)
        }

        .gborder::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: var(--brand);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            opacity: 0;
            transition: opacity .25s;
            pointer-events: none
        }

        .gborder:hover::before {
            opacity: 1
        }

        .gborder:hover {
            transform: translateY(-5px)
        }

        .glow-border::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            padding: 1px;
            background: var(--brand);
            opacity: .5;
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none
        }

        .fic {
            width: 54px;
            height: 54px;
            border-radius: 15px;
            display: grid;
            place-items: center;
            font-size: 22px;
            color: #fff;
            background: var(--brand);
            box-shadow: 0 12px 30px -12px rgba(107, 92, 255, .7)
        }

        .fic-soft {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            font-size: 19px;
            background: var(--brand-soft);
            color: var(--cyan);
            border: 1px solid color-mix(in srgb, var(--indigo) 30%, transparent)
        }

        .tilt {
            transform-style: preserve-3d;
            transition: transform .14s ease-out;
            will-change: transform
        }

        .hero-3d {
            position: relative;
            width: min(520px, 92vw);
            aspect-ratio: 1;
            margin: auto;
            display: grid;
            place-items: center
        }

        #coin {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 2
        }

        .hero-3d .halo {
            position: absolute;
            inset: 14%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(107, 92, 255, .28), transparent 62%);
            filter: blur(12px)
        }

        .chainpill {
            position: absolute;
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 12px;
            font-weight: 600;
            padding: 7px 12px;
            border-radius: 100px;
            background: var(--surface2);
            border: 1px solid var(--line2);
            z-index: 5;
            box-shadow: 0 8px 22px -10px rgba(0, 0, 0, .7);
            animation: floaty 5s ease-in-out infinite
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-12px)
            }
        }

        .plat {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--txt2);
            padding: 9px 15px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--surface);
            transition: .18s
        }

        .plat:hover {
            border-color: var(--indigo);
            color: #fff;
            transform: translateY(-2px)
        }

        .chaincard {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 22px 14px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--surface);
            transition: .2s
        }

        .chaincard:hover {
            transform: translateY(-4px);
            border-color: transparent;
            background: linear-gradient(160deg, var(--surface2), var(--surface))
        }

        .chaincard .ic {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 20px;
            color: #fff
        }

        .eco {
            position: relative;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 24px;
            transition: .2s;
            overflow: hidden
        }

        .eco:hover {
            transform: translateY(-5px);
            border-color: var(--indigo)
        }

        .stat .num {
            font-family: 'Space Grotesk';
            font-weight: 700;
            font-size: 2.2rem;
            line-height: 1
        }

        .trust {
            display: flex;
            align-items: center;
            gap: 9px;
            color: var(--mut);
            font-size: 13px;
            font-weight: 500
        }

        .trust i {
            color: var(--cyan)
        }

        footer {
            border-top: 1px solid var(--line);
            background: var(--surface)
        }

        .flink {
            color: var(--mut);
            font-size: 14px;
            line-height: 2.2;
            transition: .15s;
            cursor: pointer
        }

        .flink:hover {
            color: var(--cyan)
        }

        .soc {
            width: 41px;
            height: 41px;
            border-radius: 12px;
            border: 1px solid var(--line);
            display: grid;
            place-items: center;
            color: var(--mut);
            transition: .15s;
            background: var(--surface2)
        }

        .soc:hover {
            color: #fff;
            background: var(--brand);
            border-color: transparent;
            transform: translateY(-3px)
        }

        [data-aos] {
            will-change: transform, opacity
        }

        @media(min-width:900px) {
            .hero-grid {
                grid-template-columns: 1.05fr .95fr
            }
        }

        @media(prefers-reduced-motion:reduce) {
            * {
                animation: none !important;
                transition: none !important
            }
        }

        /* Direct Web3 Connect Toast & Modal */
        .cw-toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
            max-width: 420px;
            width: calc(100vw - 32px);
        }
        .cw-toast {
            pointer-events: auto;
            background: rgba(14, 17, 34, 0.94);
            border: 1px solid var(--line2);
            border-radius: 14px;
            padding: 14px 18px;
            color: var(--txt);
            font-size: 13.5px;
            line-height: 1.5;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.55), 0 0 20px rgba(61, 123, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            animation: cwToastSlideIn 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            transition: all 0.3s ease;
        }
        .cw-toast.info {
            border-color: rgba(51, 214, 240, 0.5);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.55), 0 0 24px rgba(51, 214, 240, 0.2);
        }
        .cw-toast.success {
            border-color: rgba(47, 207, 142, 0.5);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.55), 0 0 24px rgba(47, 207, 142, 0.25);
        }
        .cw-toast.warn {
            border-color: rgba(255, 170, 0, 0.5);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.55), 0 0 24px rgba(255, 170, 0, 0.25);
        }
        .cw-toast.error {
            border-color: rgba(240, 86, 110, 0.5);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.55), 0 0 24px rgba(240, 86, 110, 0.25);
        }
        @keyframes cwToastSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .cw-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(4, 6, 15, 0.82);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 99990;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .cw-modal-backdrop.active {
            opacity: 1;
            pointer-events: auto;
        }
        .cw-modal {
            background: #0E1122;
            border: 1px solid var(--line2);
            border-radius: 20px;
            width: 100%;
            max-width: 460px;
            padding: 28px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7), 0 0 30px rgba(107, 92, 255, 0.2);
            position: relative;
            transform: scale(0.94);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .cw-modal-backdrop.active .cw-modal {
            transform: scale(1);
        }
        .cw-modal-close {
            position: absolute;
            top: 18px;
            right: 18px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--line);
            color: var(--txt2);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .cw-modal-close:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
            transform: scale(1.05);
        }
        .cw-wallet-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin: 20px 0;
        }
        .cw-wallet-item {
            background: var(--surface2);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s;
            text-decoration: none;
            color: var(--txt);
        }
        .cw-wallet-item:hover {
            border-color: var(--cyan);
            background: var(--surface3);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(51, 214, 240, 0.15);
        }
        .cw-wallet-icon {
            width: 44px;
            height: 44px;
            object-fit: contain;
            margin-bottom: 8px;
            border-radius: 10px;
        }
        .cw-spinner {
            display: inline-block;
            animation: cwSpin 1s linear infinite;
        }
        @keyframes cwSpin {
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <div class="amb"><span class="g1"></span><span class="g2"></span><span class="g3"></span></div>
    <canvas id="bg3d"></canvas>

    <header>
        <div class="hdr">
            <a href="#" class="brand"><img
                    src="{{ asset('logo/logo.png') }}"
                    alt="{{ config('detailsApp.name', 'SYNC TRADE') }}"></a>
            <nav class="desk-nav">
                <a class="navlink" href="#">Wallet</a>
                <a class="navlink" href="#">Products</a>
                <a class="navlink" href="#">Chains</a>
                <a class="navlink" href="#">Developers</a>
                <a class="navlink" href="#">Community</a>
            </nav>
            <div class="right">
                <a class="btn btn-outline btn-sm wc-top" href="#"><i class="fa-solid fa-download"></i> Download</a>
                @if (session()->has('MEMBER_ID'))
                    <a class="btn btn-primary btn-sm" href="{{ url('member/dashboard') }}"><i
                            class="fa-solid fa-gauge"></i>
                        <span>Dashboard</span></a>
                @else
                    <a class="btn btn-primary btn-sm" href="javascript:void(0);" data-connect><i
                            class="fa-solid fa-wallet"></i>
                        <span class="wl">Connect Wallet</span></a>
                @endif
                <button class="icon-btn" id="burger" aria-label="Menu"><i class="fa-solid fa-bars"></i></button>
            </div>
        </div>
        <div id="mnav" class="mobile-menu">
            <div class="mm-inner">
                <a class="navlink" href="#">Wallet</a>
                <a class="navlink" href="#">Products</a>
                <a class="navlink" href="#">Chains</a>
                <a class="navlink" href="#">Developers</a>
                <a class="navlink" href="#">Community</a>
                <a class="btn btn-outline" href="#"><i class="fa-solid fa-download"></i> Download</a>
                @if (session()->has('MEMBER_ID'))
                    <a class="btn btn-primary" href="{{ url('member/dashboard') }}"><i
                            class="fa-solid fa-gauge"></i>
                        <span>Dashboard</span></a>
                @else
                    <a class="btn btn-primary" href="javascript:void(0);" data-connect><i
                            class="fa-solid fa-wallet"></i>
                        <span class="wl">Connect Wallet</span></a>
                @endif
            </div>
        </div>
    </header>

    <!-- HERO -->
    <section class="wrap" style="padding-top:60px;padding-bottom:50px">
        <div style="display:grid;gap:44px;align-items:center" class="hero-grid">
            <div data-aos="fade-up">
                <div class="chip"><span class="live-dot"></span> 100+ BLOCKCHAINS · 3000+ TOKENS</div>
                <!--<h1 class="h-title" style="font-size:clamp(2.3rem,6vw,3.7rem);margin:22px 0 0">Your gateway to the <span-->
                <!--        class="gtext">multi-chain</span> world</h1>-->
                <!--<p class="sub" style="font-size:1.08rem;line-height:1.65;max-width:520px;margin-top:20px">Math Wallet is-->
                <!--    a multi-platform universal crypto wallet — store, send and receive assets across 100+ blockchains,-->
                <!--    explore multi-chain DApps, and stay in full self-custody of your keys.</p>-->
                <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:30px">
                    @if (session()->has('MEMBER_ID'))
                        <a class="btn btn-primary btn-lg" href="{{ url('member/dashboard') }}"><i
                                class="fa-solid fa-gauge"></i> <span class="wl">Go to Dashboard</span></a>
                    @else
                        <a class="btn btn-primary btn-lg" href="javascript:void(0);" data-connect><i
                                class="fa-solid fa-wallet"></i> <span class="wl">Connect Wallet</span></a>
                    @endif
                    <a class="btn btn-outline btn-lg" href="#"><i class="fa-solid fa-download"></i> Download App</a>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:26px">
                    <span class="plat"><i class="fa-brands fa-apple"></i> iOS</span>
                    <span class="plat"><i class="fa-brands fa-android"></i> Android</span>
                    <span class="plat"><i class="fa-brands fa-chrome"></i> Extension</span>
                    <span class="plat"><i class="fa-solid fa-globe"></i> Web</span>
                    <span class="plat"><i class="fa-solid fa-microchip"></i> Hardware</span>
                </div>
            </div>
            <div data-aos="fade-left" data-aos-delay="120">
                <div class="hero-3d">
                    <div class="halo"></div>
                    <canvas id="coin"></canvas>
                    <div class="chainpill" style="top:6%;left:44%"><i class="fa-brands fa-bitcoin"
                            style="color:#F7931A"></i> Bitcoin</div>
                    <div class="chainpill" style="bottom:12%;left:2%;animation-delay:1.2s"><i
                            class="fa-brands fa-ethereum" style="color:#8AA9FF"></i> Ethereum</div>
                    <div class="chainpill" style="top:46%;right:-2%;animation-delay:2.1s"><i class="fa-solid fa-b"
                            style="color:#F0B90B"></i> BNB Chain</div>
                    <div class="chainpill" style="top:12%;right:6%;animation-delay:.6s"><i class="fa-solid fa-bolt"
                            style="color:#9B6BFF"></i> Solana</div>
                </div>
            </div>
        </div>
        <div
            style="margin-top:40px;padding-top:28px;border-top:1px solid var(--line);display:flex;flex-wrap:wrap;gap:20px 34px">
            <span class="trust"><i class="fa-solid fa-key"></i> Self-custodial keys</span>
            <span class="trust"><i class="fa-solid fa-link"></i> 100+ chains</span>
            <span class="trust"><i class="fa-solid fa-cubes"></i> Multi-chain DApps</span>
            <span class="trust"><i class="fa-solid fa-shield-halved"></i> Secure by design</span>
        </div>
    </section>

    <!-- STATS -->
    <section class="sec" style="padding-top:0">
        <div class="wrap">
            <div style="display:grid;gap:18px;grid-template-columns:repeat(auto-fit,minmax(180px,1fr))">
                <div class="statcard" data-aos="fade-up">
                    <div class="num">100+</div>
                    <div class="sub">Supported Blockchains</div>
                </div>
                <div class="statcard" data-aos="fade-up" data-aos-delay="60">
                    <div class="num">3000+</div>
                    <div class="sub">Supported Tokens</div>
                </div>
                <div class="statcard" data-aos="fade-up" data-aos-delay="120">
                    <div class="num">1M+</div>
                    <div class="sub">Active Users</div>
                </div>
                <div class="statcard" data-aos="fade-up">
                    <div class="num">50K+</div>
                    <div class="sub">DApps Supported</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="sec">
        <div class="wrap">
            <div style="text-align:center;margin-bottom:46px" data-aos="fade-up">
                <div class="kick gtext2">Why SYNC TRADE</div>
                <h2 class="h-title" style="font-size:clamp(1.8rem,4.4vw,2.5rem);margin-top:10px">One wallet. Every
                    chain.</h2>
            </div>
            <div style="display:grid;gap:18px;grid-template-columns:repeat(auto-fit,minmax(260px,1fr))">
                <div class="card glass gborder" style="padding:28px" data-aos="fade-up">
                    <div class="fic"><i class="fa-solid fa-key"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:18px;margin:18px 0 8px">Self-Custodial</h4>
                    <p class="sub" style="font-size:14px;line-height:1.6;margin:0">Wallets are generated completely
                        client-side. Your private keys and mnemonics stay with you — always.</p>
                </div>
                <div class="card glass gborder" style="padding:28px" data-aos="fade-up" data-aos-delay="60">
                    <div class="fic"><i class="fa-solid fa-link"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:18px;margin:18px 0 8px">Truly Multi-Chain</h4>
                    <p class="sub" style="font-size:14px;line-height:1.6;margin:0">Store 100+ blockchains and 3000+
                        tokens — EVM networks, BNB Chain, Bitcoin, Solana and many more.</p>
                </div>
                <div class="card glass gborder" style="padding:28px" data-aos="fade-up" data-aos-delay="120">
                    <div class="fic"><i class="fa-solid fa-cubes"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:18px;margin:18px 0 8px">Multi-Chain DApps</h4>
                    <p class="sub" style="font-size:14px;line-height:1.6;margin:0">The only extension wallet that
                        supports multi-chain DApps, with a built-in DApp browser to explore Web3.</p>
                </div>
                <div class="card glass gborder" style="padding:28px" data-aos="fade-up">
                    <div class="fic"><i class="fa-solid fa-paper-plane"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:18px;margin:18px 0 8px">Send &amp; Receive</h4>
                    <p class="sub" style="font-size:14px;line-height:1.6;margin:0">Easily send and receive tokens across
                        chains with a fast, clean and intuitive interface.</p>
                </div>
                <div class="card glass gborder" style="padding:28px" data-aos="fade-up" data-aos-delay="60">
                    <div class="fic"><i class="fa-solid fa-mobile-screen"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:18px;margin:18px 0 8px">Multi-Platform</h4>
                    <p class="sub" style="font-size:14px;line-height:1.6;margin:0">Available on mobile, desktop browser
                        extension and hardware — your wallet, everywhere you are.</p>
                </div>
                <div class="card glass gborder" style="padding:28px" data-aos="fade-up" data-aos-delay="120">
                    <div class="fic"><i class="fa-solid fa-shield-halved"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:18px;margin:18px 0 8px">Secure by Design</h4>
                    <p class="sub" style="font-size:14px;line-height:1.6;margin:0">Multiple key types, client-side
                        security and a transparent architecture built for peace of mind.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CHAINS -->
    <section class="sec" style="padding-top:0">
        <div class="wrap">
            <div style="text-align:center;margin-bottom:46px" data-aos="fade-up">
                <div class="kick gtext2">Supported Networks</div>
                <h2 class="h-title" style="font-size:clamp(1.8rem,4.4vw,2.5rem);margin-top:10px">Access 100+ blockchains
                </h2>
            </div>
            <div style="display:grid;gap:14px;grid-template-columns:repeat(auto-fit,minmax(120px,1fr))">
                <div class="chaincard" data-aos="fade-up">
                    <div class="ic" style="background:#F7931A"><i class="fa-brands fa-bitcoin"></i></div><span
                        style="font-weight:600;font-size:14px">Bitcoin</span>
                </div>
                <div class="chaincard" data-aos="fade-up" data-aos-delay="40">
                    <div class="ic" style="background:#627EEA"><i class="fa-brands fa-ethereum"></i></div><span
                        style="font-weight:600;font-size:14px">Ethereum</span>
                </div>
                <div class="chaincard" data-aos="fade-up" data-aos-delay="80">
                    <div class="ic" style="background:#F0B90B"><i class="fa-solid fa-b"></i></div><span
                        style="font-weight:600;font-size:14px">BNB Chain</span>
                </div>
                <div class="chaincard" data-aos="fade-up" data-aos-delay="120">
                    <div class="ic" style="background:#9945FF"><i class="fa-solid fa-bolt"></i></div><span
                        style="font-weight:600;font-size:14px">Solana</span>
                </div>
                <div class="chaincard" data-aos="fade-up" data-aos-delay="160">
                    <div class="ic" style="background:#E6007A"><i class="fa-solid fa-circle-dot"></i></div><span
                        style="font-weight:600;font-size:14px">Polkadot</span>
                </div>
                <div class="chaincard" data-aos="fade-up">
                    <div class="ic" style="background:#8247E5"><i class="fa-solid fa-diamond"></i></div><span
                        style="font-weight:600;font-size:14px">Polygon</span>
                </div>
                <div class="chaincard" data-aos="fade-up" data-aos-delay="40">
                    <div class="ic" style="background:#0098EA"><i class="fa-solid fa-paper-plane"></i></div><span
                        style="font-weight:600;font-size:14px">TON</span>
                </div>
                <div class="chaincard" data-aos="fade-up" data-aos-delay="80">
                    <div class="ic" style="background:#23292F"><i class="fa-solid fa-xmark"></i></div><span
                        style="font-weight:600;font-size:14px">Ripple</span>
                </div>
                <div class="chaincard" data-aos="fade-up" data-aos-delay="120">
                    <div class="ic" style="background:#2775CA"><i class="fa-solid fa-dollar-sign"></i></div><span
                        style="font-weight:600;font-size:14px">Tron</span>
                </div>
                <div class="chaincard" data-aos="fade-up" data-aos-delay="160">
                    <div class="ic" style="background:#0F6E8C"><i class="fa-solid fa-database"></i></div><span
                        style="font-weight:600;font-size:14px">Filecoin</span>
                </div>
                <div class="chaincard" data-aos="fade-up">
                    <div class="ic" style="background:#28A0F0"><i class="fa-solid fa-a"></i></div><span
                        style="font-weight:600;font-size:14px">Arbitrum</span>
                </div>
                <div class="chaincard" data-aos="fade-up" data-aos-delay="40">
                    <div class="ic" style="background:var(--indigo)"><i class="fa-solid fa-ellipsis"></i></div><span
                        style="font-weight:600;font-size:14px">& 90+ more</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ECOSYSTEM -->
    <section class="sec" style="padding-top:0">
        <div class="wrap">
            <div style="text-align:center;margin-bottom:46px" data-aos="fade-up">
                <div class="kick gtext2">The Ecosystem</div>
                <h2 class="h-title" style="font-size:clamp(1.8rem,4.4vw,2.5rem);margin-top:10px">More than a wallet</h2>
            </div>
            <div style="display:grid;gap:16px;grid-template-columns:repeat(auto-fit,minmax(230px,1fr))">
                <div class="eco tilt" data-aos="fade-up">
                    <div class="fic-soft"><i class="fa-solid fa-wallet"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:16px;margin:16px 0 6px">SYNC TRADE</h4>
                    <p class="sub" style="font-size:13.5px;line-height:1.55;margin:0">The multi-chain universal wallet
                        at the heart of it all.</p>
                </div>
                <div class="eco tilt" data-aos="fade-up" data-aos-delay="50">
                    <div class="fic-soft"><i class="fa-solid fa-arrows-rotate"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:16px;margin:16px 0 6px">Math Swap</h4>
                    <p class="sub" style="font-size:13.5px;line-height:1.55;margin:0">Multi-exchange liquidity
                        aggregation for smooth swaps.</p>
                </div>
                <div class="eco tilt" data-aos="fade-up" data-aos-delay="100">
                    <div class="fic-soft"><i class="fa-solid fa-store"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:16px;margin:16px 0 6px">Math DApp Store</h4>
                    <p class="sub" style="font-size:13.5px;line-height:1.55;margin:0">A decentralized app store — your
                        entry to every DApp.</p>
                </div>
                <div class="eco tilt" data-aos="fade-up" data-aos-delay="150">
                    <div class="fic-soft"><i class="fa-solid fa-cube"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:16px;margin:16px 0 6px">Math Chain</h4>
                    <p class="sub" style="font-size:13.5px;line-height:1.55;margin:0">A Layer-2 blockchain built on
                        Substrate.</p>
                </div>
                <div class="eco tilt" data-aos="fade-up">
                    <div class="fic-soft"><i class="fa-solid fa-newspaper"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:16px;margin:16px 0 6px">Math News</h4>
                    <p class="sub" style="font-size:13.5px;line-height:1.55;margin:0">Latest news and price info for
                        supported chains.</p>
                </div>
                <div class="eco tilt" data-aos="fade-up" data-aos-delay="50">
                    <div class="fic-soft"><i class="fa-solid fa-id-card"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:16px;margin:16px 0 6px">Math ID</h4>
                    <p class="sub" style="font-size:13.5px;line-height:1.55;margin:0">A unified identity across the Math
                        ecosystem.</p>
                </div>
                <div class="eco tilt" data-aos="fade-up" data-aos-delay="100">
                    <div class="fic-soft"><i class="fa-solid fa-shapes"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:16px;margin:16px 0 6px">Math Verse</h4>
                    <p class="sub" style="font-size:13.5px;line-height:1.55;margin:0">Where NFT, AI and the metaverse
                        meet.</p>
                </div>
                <div class="eco tilt" data-aos="fade-up" data-aos-delay="150">
                    <div class="fic-soft"><i class="fa-solid fa-credit-card"></i></div>
                    <h4 class="disp" style="font-weight:600;font-size:16px;margin:16px 0 6px">Math Pay</h4>
                    <p class="sub" style="font-size:13.5px;line-height:1.55;margin:0">A one-stop solution to accept
                        crypto payments.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- DOWNLOAD CTA -->
    <section class="sec" style="padding-top:0">
        <div class="wrap">
            <div class="card glass glow-border"
                style="text-align:center;padding:64px 26px;position:relative;overflow:hidden" data-aos="fade-up">
                <div
                    style="position:absolute;inset:0;background:radial-gradient(62% 84% at 50% 0%,rgba(107,92,255,.20),transparent 62%)">
                </div>
                <div style="position:relative">
                    <img src="{{ asset('logo/logo.png') }}"
                        alt=""
                        style="height:66px;margin:0 auto 22px;filter:drop-shadow(0 10px 30px rgba(107,92,255,.55))">
                    <h2 class="h-title" style="font-size:clamp(1.8rem,4.4vw,2.7rem)">Start your <span
                            class="gtext">multi-chain</span> journey</h2>
                    <p class="sub" style="margin-top:14px;font-size:15px">Connect your wallet or download the app — and
                        explore Web3 across every chain.</p>
                    <div style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-top:30px">
                        @if (session()->has('MEMBER_ID'))
                            <a class="btn btn-primary btn-lg" href="{{ url('member/dashboard') }}"><i
                                    class="fa-solid fa-gauge"></i> <span class="wl">Go to Dashboard</span></a>
                        @else
                            <a class="btn btn-primary btn-lg" href="javascript:void(0);" data-connect><i
                                    class="fa-solid fa-wallet"></i> <span class="wl">Connect Wallet</span></a>
                        @endif
                        <a class="btn btn-outline btn-lg" href="#"><i class="fa-solid fa-download"></i> Download App</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer style="padding:56px 0 32px">
        <div class="wrap">
            <div style="display:grid;gap:34px;grid-template-columns:repeat(auto-fit,minmax(150px,1fr))">
                <div style="grid-column:1/-1;max-width:340px">
                    <a href="#" class="brand" style="margin-bottom:16px"><img
                            src="{{ asset('logo/logo.png') }}"
                            alt="{{ config('detailsApp.name', 'SYNC TRADE') }}" style="height:28px"></a>
                    <p class="sub" style="font-size:14px;max-width:320px;line-height:1.6">The multi-platform universal
                        crypto wallet — 100+ blockchains, 3000+ tokens, fully self-custodial.</p>
                    <div style="display:flex;gap:9px;margin-top:18px">
                        <a class="soc" href="#"><i class="fa-brands fa-x-twitter"></i></a><a class="soc" href="#"><i
                                class="fa-brands fa-telegram"></i></a>
                        <a class="soc" href="#"><i class="fa-brands fa-discord"></i></a><a class="soc" href="#"><i
                                class="fa-brands fa-github"></i></a>
                    </div>
                </div>
                <div>
                    <h5 class="disp" style="font-size:14px;font-weight:600;margin:0 0 12px">Wallet</h5><a class="flink"
                        href="#">Download</a><br><a class="flink" href="#">Extension</a><br><a class="flink"
                        href="#">Hardware</a><br><a class="flink" href="#">Security</a>
                </div>
                <div>
                    <h5 class="disp" style="font-size:14px;font-weight:600;margin:0 0 12px">Products</h5><a
                        class="flink" href="#">Math Swap</a><br><a class="flink" href="#">DApp Store</a><br><a
                        class="flink" href="#">Math Chain</a><br><a class="flink" href="#">Math Pay</a>
                </div>
                <div>
                    <h5 class="disp" style="font-size:14px;font-weight:600;margin:0 0 12px">Resources</h5><a
                        class="flink" href="#">Docs</a><br><a class="flink" href="#">Developers</a><br><a class="flink"
                        href="#">News</a><br><a class="flink" href="#">Support</a>
                </div>
                <div>
                    <h5 class="disp" style="font-size:14px;font-weight:600;margin:0 0 12px">Company</h5><a class="flink"
                        href="#">About</a><br><a class="flink" href="#">Community</a><br><a class="flink"
                        href="#">Terms</a><br><a class="flink" href="#">Privacy</a>
                </div>
            </div>
            <div
                style="display:flex;flex-wrap:wrap;justify-content:space-between;gap:12px;margin-top:44px;padding-top:26px;border-top:1px solid var(--line)">
                <span class="sub" style="font-size:13px">© {{ date('Y') }} SYNC TRADE. All rights reserved.</span>
                <span class="sub" style="font-size:13px">Powering the future of digital finance.</span>
            </div>
        </div>
    </footer>

    <!-- Toast Container -->
    <div id="cwToastContainer" class="cw-toast-container" aria-live="polite"></div>

    <!-- Web3 Wallet Connection Modal -->
    <div id="cwModalBackdrop" class="cw-modal-backdrop" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="cw-modal">
            <button class="cw-modal-close" id="cwModalClose" aria-label="Close modal">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px">
                <span class="live-dot"></span>
                <span style="font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--cyan)">Web3 Multi-Chain Connect</span>
            </div>
            <h3 style="font-size:20px;font-weight:800;color:#fff;margin:0 0 6px">Connect Your Wallet</h3>
            <p style="font-size:13px;color:var(--txt2);margin:0;line-height:1.5">
                Connect your Web3 crypto wallet to sign in or register instantly.
            </p>

            <div class="cw-wallet-grid">
                <!-- Math Wallet -->
                <div class="cw-wallet-item" data-wallet="MathWallet">
                    <img src="{{ asset('logo/logo.png') }}" alt="Math Wallet" class="cw-wallet-icon" onerror="this.onerror=null; this.src='https://raw.githubusercontent.com/trustwallet/assets/master/blockchains/binance/info/logo.png';">
                    <span style="font-size:13px;font-weight:700;color:#fff">Math Wallet</span>
                    <span style="font-size:11px;color:var(--mut);margin-top:2px">Extension &amp; App</span>
                </div>
                <!-- MetaMask -->
                <div class="cw-wallet-item" data-wallet="MetaMask">
                    <img src="https://raw.githubusercontent.com/MetaMask/brand-resources/master/SVG/metamask-fox.svg" alt="MetaMask" class="cw-wallet-icon">
                    <span style="font-size:13px;font-weight:700;color:#fff">MetaMask</span>
                    <span style="font-size:11px;color:var(--mut);margin-top:2px">Browser &amp; Mobile</span>
                </div>
                <!-- Trust Wallet -->
                <div class="cw-wallet-item" data-wallet="TrustWallet">
                    <img src="https://trustwallet.com/assets/images/media/assets/trust_platform.svg" alt="Trust Wallet" class="cw-wallet-icon" onerror="this.onerror=null; this.src='https://assets.coingecko.com/coins/images/11055/large/trust-wallet-token.png';">
                    <span style="font-size:13px;font-weight:700;color:#fff">Trust Wallet</span>
                    <span style="font-size:11px;color:var(--mut);margin-top:2px">Multi-Chain App</span>
                </div>
                <!-- Browser / Injected Wallet -->
                <div class="cw-wallet-item" data-wallet="Injected">
                    <div class="cw-wallet-icon" style="display:flex;align-items:center;justify-content:center;background:rgba(61,123,255,.2);border:1px solid rgba(61,123,255,.3);border-radius:10px">
                        <i class="fa-solid fa-plug text-lg" style="color:var(--cyan)"></i>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:#fff">Browser Wallet</span>
                    <span style="font-size:11px;color:var(--mut);margin-top:2px">Injected Web3</span>
                </div>
            </div>

            <div id="cwModalNotice" style="font-size:12px;color:var(--mut);text-align:center;line-height:1.5;padding-top:10px;border-top:1px solid var(--line)">
                Don't have a Web3 wallet? <a href="https://mathwallet.org" target="_blank" rel="noopener" style="color:var(--cyan);text-decoration:none;font-weight:600">Download Math Wallet &rarr;</a>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 680, easing: 'ease-out-cubic', once: true, offset: 70 });
        const burger = document.getElementById('burger'), mnav = document.getElementById('mnav');
        burger.onclick = () => { const o = mnav.classList.toggle('open'); burger.innerHTML = '<i class="fa-solid fa-' + (o ? 'xmark' : 'bars') + '"></i>'; };
        document.querySelectorAll('#mnav a').forEach(a => a.addEventListener('click', () => { mnav.classList.remove('open'); burger.innerHTML = '<i class="fa-solid fa-bars"></i>'; }));

        /* ===== Direct Frontend Web3 Wallet Connect & Login Flow ===== */
        (function () {
            let eip6963Providers = [];
            window.addEventListener('eip6963:announceProvider', function (e) {
                if (e && e.detail && !eip6963Providers.some(p => p.info.uuid === e.detail.info.uuid)) {
                    eip6963Providers.push(e.detail);
                }
            });
            window.dispatchEvent(new Event('eip6963:requestProvider'));

            const modalBackdrop = document.getElementById('cwModalBackdrop');
            const modalClose = document.getElementById('cwModalClose');

            function openModal() {
                if (modalBackdrop) {
                    modalBackdrop.classList.add('active');
                    modalBackdrop.setAttribute('aria-hidden', 'false');
                }
            }

            function closeModal() {
                if (modalBackdrop) {
                    modalBackdrop.classList.remove('active');
                    modalBackdrop.setAttribute('aria-hidden', 'true');
                }
            }

            if (modalClose) modalClose.addEventListener('click', closeModal);
            if (modalBackdrop) {
                modalBackdrop.addEventListener('click', function (e) {
                    if (e.target === modalBackdrop) closeModal();
                });
            }

            function showToast(message, type = 'info', duration = 5000) {
                const container = document.getElementById('cwToastContainer');
                if (!container) return;
                const toast = document.createElement('div');
                toast.className = `cw-toast ${type}`;
                let icon = 'fa-circle-info text-[#33D6F0]';
                if (type === 'success') icon = 'fa-circle-check text-[#2FCF8E]';
                else if (type === 'warn') icon = 'fa-triangle-exclamation text-yellow-400';
                else if (type === 'error') icon = 'fa-circle-exclamation text-red-400';

                toast.innerHTML = `<i class="fa-solid ${icon} text-lg shrink-0"></i><div style="flex:1">${message}</div>`;
                container.appendChild(toast);

                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';
                    setTimeout(() => toast.remove(), 350);
                }, duration);
            }

            function setButtonsLoading(loading, text = 'Connect Wallet') {
                document.querySelectorAll('[data-connect]').forEach(btn => {
                    if (loading) {
                        btn.style.pointerEvents = 'none';
                        btn.style.opacity = '0.85';
                        btn.innerHTML = `<i class="fa-solid fa-spinner cw-spinner"></i> <span class="wl">${text}</span>`;
                    } else {
                        btn.style.pointerEvents = 'auto';
                        btn.style.opacity = '1';
                        btn.innerHTML = `<i class="fa-solid fa-wallet"></i> <span class="wl">${text}</span>`;
                    }
                });
            }

            function getWalletProvider(name) {
                if (name === 'MathWallet') {
                    if (window.mathwallet) return window.mathwallet;
                    if (window.ethereum?.isMathWallet) return window.ethereum;
                    const eip = eip6963Providers.find(p => /math/i.test(p.info.name));
                    if (eip) return eip.provider;
                }
                if (name === 'MetaMask') {
                    if (window.ethereum?.providers?.length) {
                        const p = window.ethereum.providers.find(x => x.isMetaMask && !x.isTrust);
                        if (p) return p;
                    }
                    if (window.ethereum?.isMetaMask && !window.ethereum?.isTrust) return window.ethereum;
                    const eip = eip6963Providers.find(p => /metamask/i.test(p.info.name));
                    if (eip) return eip.provider;
                }
                if (name === 'TrustWallet') {
                    if (window.trustwallet) return window.trustwallet;
                    if (window.ethereum?.providers?.length) {
                        const p = window.ethereum.providers.find(x => x.isTrust || x.isTrustWallet);
                        if (p) return p;
                    }
                    if (window.ethereum?.isTrust || window.ethereum?.isTrustWallet) return window.ethereum;
                    const eip = eip6963Providers.find(p => /trust/i.test(p.info.name));
                    if (eip) return eip.provider;
                }

                // Default / Injected / Any provider
                if (window.ethereum) {
                    if (window.ethereum.providers && window.ethereum.providers.length) {
                        return window.ethereum.providers[0];
                    }
                    return window.ethereum;
                }
                if (window.mathwallet) return window.mathwallet;
                if (window.trustwallet) return window.trustwallet;
                if (eip6963Providers.length > 0) return eip6963Providers[0].provider;

                return null;
            }

            async function proceedWithAddress(address) {
                showToast(`Connected: <b>${address.slice(0, 6)}...${address.slice(-4)}</b>. Validating member...`, 'info', 4000);
                setButtonsLoading(true, 'Validating...');

                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

                try {
                    const response = await fetch("{{ route('addressValidate') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": csrfToken
                        },
                        body: new URLSearchParams({
                            "address": address,
                            "_token": csrfToken
                        })
                    });

                    const data = await response.json();
                    console.log("addressValidate response:", data);

                    if (data.code === 1) {
                        showToast('Member verified! Redirecting to Dashboard...', 'success', 3000);
                        setButtonsLoading(true, 'Redirecting...');
                        setTimeout(() => {
                            window.location.href = "{{ url('member/dashboard') }}";
                        }, 400);
                    } else {
                        showToast('New wallet detected! Redirecting to Registration...', 'info', 3500);
                        setButtonsLoading(true, 'Registering...');
                        setTimeout(() => {
                            window.location.href = "{{ url('/member/register/1') }}";
                        }, 400);
                    }
                } catch (err) {
                    console.error("Address validation error:", err);
                    showToast('Validation failed. Please try again.', 'error');
                    setButtonsLoading(false, 'Connect Wallet');
                }
            }

            async function connectAndAuthenticate(walletName = null) {
                const provider = getWalletProvider(walletName);

                if (!provider) {
                    openModal();
                    showToast('No Web3 wallet extension detected. Please select or install a wallet.', 'warn', 6000);
                    return;
                }

                closeModal();
                setButtonsLoading(true, 'Connecting...');
                showToast('Connecting wallet... Please check your wallet popup.', 'info', 5000);

                try {
                    let accounts = [];

                    // Fast check if already permitted / in-app wallet selectedAddress
                    if (provider.selectedAddress) {
                        accounts = [provider.selectedAddress];
                    }

                    if ((!accounts || !accounts.length) && provider.request) {
                        try {
                            const silent = await provider.request({ method: 'eth_accounts' });
                            if (silent && silent.length && silent[0]) accounts = silent;
                        } catch (e) {}
                    }

                    // Request user authorization if needed
                    if (!accounts || !accounts.length || !accounts[0]) {
                        if (provider.request) {
                            accounts = await provider.request({ method: 'eth_requestAccounts' });
                        } else if (provider.enable) {
                            accounts = await provider.enable();
                        }
                    }

                    if (accounts && accounts[0]) {
                        await proceedWithAddress(accounts[0]);
                    } else {
                        showToast('No account selected. Please unlock your wallet and try again.', 'warn');
                        setButtonsLoading(false, 'Connect Wallet');
                    }

                } catch (err) {
                    console.warn("Wallet connect caught:", err);
                    setButtonsLoading(false, 'Connect Wallet');

                    if (err.code === 4001 || (err.message && err.message.includes('rejected'))) {
                        showToast('Connection request was cancelled by user.', 'warn');
                    } else if (err.code === -32002 || (err.message && err.message.includes('already pending'))) {
                        showToast('A connection request is already pending in your wallet extension. Please open the wallet popup to approve.', 'warn', 7000);
                    } else {
                        showToast(err.message || 'Failed to connect. Please unlock your wallet and try again.', 'error');
                    }
                }
            }

            // Click listener on all [data-connect] buttons
            document.querySelectorAll('[data-connect]').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    connectAndAuthenticate();
                });
            });

            // Click listeners on modal wallet items
            document.querySelectorAll('.cw-wallet-item').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const wName = item.getAttribute('data-wallet');
                    connectAndAuthenticate(wName);
                });
            });
        })();

        /* tilt */
        document.querySelectorAll('.tilt').forEach(c => {
            c.addEventListener('mousemove', e => { const r = c.getBoundingClientRect(); const x = (e.clientX - r.left) / r.width - .5, y = (e.clientY - r.top) / r.height - .5; c.style.transform = 'perspective(900px) rotateY(' + (x * 9) + 'deg) rotateX(' + (-y * 9) + 'deg)'; });
            c.addEventListener('mouseleave', () => c.style.transform = 'perspective(900px) rotateY(0) rotateX(0)');
        });

        /* ===== 3D hero coin (Three.js) ===== */
        (function () {
            if (!window.THREE) return; const cv = document.getElementById('coin'); if (!cv) return;
            const scene = new THREE.Scene();
            const cam = new THREE.PerspectiveCamera(50, 1, 0.1, 100); cam.position.z = 4.4;
            const rnd = new THREE.WebGLRenderer({ canvas: cv, alpha: true, antialias: true });
            rnd.setClearColor(0x000000, 0);
            function size() { const r = cv.getBoundingClientRect(); rnd.setSize(r.width, r.height, false); cam.aspect = r.width / r.height; cam.updateProjectionMatrix(); rnd.setPixelRatio(Math.min(devicePixelRatio, 2)); }
            size(); addEventListener('resize', size);
            const grp = new THREE.Group(); scene.add(grp);
            // coin body
            const coin = new THREE.Mesh(new THREE.CylinderGeometry(1.5, 1.5, 0.28, 64),
                new THREE.MeshStandardMaterial({ color: 0x2a2f55, metalness: .9, roughness: .28, emissive: 0x0a0f2a }));
            coin.rotation.x = Math.PI / 2; grp.add(coin);
            // rim
            const rim = new THREE.Mesh(new THREE.TorusGeometry(1.5, 0.075, 20, 80),
                new THREE.MeshStandardMaterial({ color: 0x6B5CFF, metalness: .8, roughness: .25, emissive: 0x2a1f7a, emissiveIntensity: .6 }));
            grp.add(rim);
            // "M" triangle-dots motif via small spheres forming an M-ish cluster
            const dotMat = new THREE.MeshStandardMaterial({ color: 0x8fd0ff, emissive: 0x3D7BFF, emissiveIntensity: .8, metalness: .5, roughness: .3 });
            const pts = [[-0.55, 0.5], [-0.55, 0], [-0.55, -0.5], [-0.2, 0.25], [0.15, 0.5], [0.15, 0], [0.15, -0.5], [0.5, 0.25], [0.85, 0.5], [0.85, 0], [0.85, -0.5]];
            pts.forEach(p => { const s = new THREE.Mesh(new THREE.SphereGeometry(0.12, 20, 20), dotMat); s.position.set(p[0] * 0.9, p[1] * 0.9, 0.16); grp.add(s); });
            // wireframe blockchain shell
            const shell = new THREE.Mesh(new THREE.IcosahedronGeometry(2.35, 1),
                new THREE.MeshBasicMaterial({ color: 0x33D6F0, wireframe: true, transparent: true, opacity: .16 }));
            scene.add(shell);
            // orbiting block nodes (blockchain)
            const nodes = new THREE.Group(); scene.add(nodes);
            const nodeMat = new THREE.MeshStandardMaterial({ color: 0x9B6BFF, emissive: 0x6B5CFF, emissiveIntensity: .7, metalness: .6, roughness: .3 });
            const orb = [];
            for (let i = 0; i < 7; i++) { const b = new THREE.Mesh(new THREE.BoxGeometry(0.24, 0.24, 0.24), nodeMat); const a = i / 7 * Math.PI * 2; b.userData = { a, r: 2.9, s: 0.3 + Math.random() * 0.3 }; nodes.add(b); orb.push(b); }
            // lights
            scene.add(new THREE.AmbientLight(0x8899ff, 0.7));
            const l1 = new THREE.PointLight(0x6B5CFF, 1.4); l1.position.set(4, 4, 5); scene.add(l1);
            const l2 = new THREE.PointLight(0x33D6F0, 1.1); l2.position.set(-4, -2, 3); scene.add(l2);
            let mx = 0, my = 0; addEventListener('mousemove', e => { mx = (e.clientX / innerWidth - .5); my = (e.clientY / innerHeight - .5); });
            (function loop() {
                requestAnimationFrame(loop);
                grp.rotation.y += 0.011; grp.rotation.x = Math.sin(Date.now() * 0.0006) * 0.12;
                shell.rotation.y -= 0.004; shell.rotation.x += 0.002;
                orb.forEach(b => { b.userData.a += 0.006 * b.userData.s * 4; b.position.set(Math.cos(b.userData.a) * b.userData.r, Math.sin(b.userData.a * 0.8) * 0.7, Math.sin(b.userData.a) * b.userData.r); b.rotation.x += 0.02; b.rotation.y += 0.02; });
                scene.rotation.y += ((mx * 0.5) - scene.rotation.y) * 0.05; scene.rotation.x += ((my * 0.35) - scene.rotation.x) * 0.05;
                rnd.render(scene, cam);
            })();
        })();

        /* ===== background particle chain ===== */
        (function () {
            const cv = document.getElementById('bg3d'), cx = cv.getContext('2d'); let W, H, pts = [];
            function size() { W = cv.width = innerWidth; H = cv.height = innerHeight; }
            size(); addEventListener('resize', () => { size(); seed(); });
            function seed() { pts = []; const n = Math.min(70, Math.floor(W / 24)); for (let i = 0; i < n; i++)pts.push({ x: Math.random() * W, y: Math.random() * H, vx: (Math.random() - .5) * .25, vy: (Math.random() - .5) * .25 }); }
            seed();
            (function loop() {
                requestAnimationFrame(loop); cx.clearRect(0, 0, W, H);
                pts.forEach(p => { p.x += p.vx; p.y += p.vy; if (p.x < 0 || p.x > W) p.vx *= -1; if (p.y < 0 || p.y > H) p.vy *= -1; });
                for (let i = 0; i < pts.length; i++) {
                    for (let j = i + 1; j < pts.length; j++) {
                        const dx = pts[i].x - pts[j].x, dy = pts[i].y - pts[j].y, d = Math.hypot(dx, dy);
                        if (d < 140) { cx.strokeStyle = 'rgba(107,92,255,' + (1 - d / 140) * .16 + ')'; cx.lineWidth = 1; cx.beginPath(); cx.moveTo(pts[i].x, pts[i].y); cx.lineTo(pts[j].x, pts[j].y); cx.stroke(); }
                    }
                    cx.fillStyle = 'rgba(51,214,240,.5)'; cx.beginPath(); cx.arc(pts[i].x, pts[i].y, 1.4, 0, 7); cx.fill();
                }
            })();
        })();
    </script>
</body>

</html>