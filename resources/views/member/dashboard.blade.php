@extends('member.layouts.main2')
@section('title', 'Dashboard')
@section('container')

    <style>
        .dash-3d-card {
            position: relative;
            overflow: hidden;
            border: 0 !important;
            background: linear-gradient(135deg, var(--g1), var(--g2), var(--g3)) !important;
            background-size: 280% 280%;
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.22), inset 0 1px 1px rgba(255, 255, 255, 0.25);
            transform-style: preserve-3d;
            transition: transform .35s ease, box-shadow .35s ease, filter .35s ease;
            animation: gradientFlow 11s ease infinite, shadePulse 3.8s ease-in-out infinite;
        }

        .dash-3d-card::before {
            content: "";
            position: absolute;
            inset: -35%;
            background:
                radial-gradient(circle at 20% 25%, rgba(255, 255, 255, 0.36) 0%, rgba(255, 255, 255, 0) 38%),
                radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.24) 0%, rgba(255, 255, 255, 0) 40%);
            animation: shineMove 9s linear infinite;
            pointer-events: none;
        }

        .dash-3d-card .shade-wave {
            position: absolute;
            inset: -35% -30%;
            background: linear-gradient(110deg,
                    rgba(0, 0, 0, 0) 30%,
                    rgba(0, 0, 0, 0.22) 48%,
                    rgba(255, 255, 255, 0.16) 56%,
                    rgba(0, 0, 0, 0) 72%);
            filter: blur(7px);
            mix-blend-mode: soft-light;
            animation: shadeSweep 5s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        .dash-3d-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 8% 18%, rgba(255, 255, 255, .95) 0 2px, rgba(255, 255, 255, 0) 3px),
                radial-gradient(circle at 18% 72%, rgba(255, 255, 255, .80) 0 1.5px, rgba(255, 255, 255, 0) 2.5px),
                radial-gradient(circle at 30% 36%, rgba(255, 255, 255, .9) 0 2px, rgba(255, 255, 255, 0) 3px),
                radial-gradient(circle at 44% 82%, rgba(255, 255, 255, .85) 0 1.5px, rgba(255, 255, 255, 0) 2.5px),
                radial-gradient(circle at 57% 24%, rgba(255, 255, 255, .9) 0 2px, rgba(255, 255, 255, 0) 3px),
                radial-gradient(circle at 69% 62%, rgba(255, 255, 255, .85) 0 1.5px, rgba(255, 255, 255, 0) 2.5px),
                radial-gradient(circle at 82% 14%, rgba(255, 255, 255, .92) 0 2px, rgba(255, 255, 255, 0) 3px),
                radial-gradient(circle at 92% 76%, rgba(255, 255, 255, .78) 0 1.5px, rgba(255, 255, 255, 0) 2.5px);
            animation: ballsFloat 2.2s linear infinite;
            pointer-events: none;
            mix-blend-mode: screen;
        }

        .dash-3d-card .card-body {
            position: relative;
            z-index: 1;
        }

        .dash-3d-card .media-body,
        .dash-3d-card h3,
        .dash-3d-card h4,
        .dash-3d-card p,
        .dash-3d-card small {
            text-shadow: 0 2px 6px rgba(0, 0, 0, .28);
        }

        .dash-3d-card .media span i {
            color: #f8fafc !important;
            text-shadow: 0 3px 8px rgba(0, 0, 0, .35);
        }

        .row-theme-1 .media span i {
            color: #FFD700 !important;
        }

        .row-theme-2 .media span i {
            color: #FCA5A5 !important;
        }

        .row-theme-3 .media span i {
            color: #A7F3D0 !important;
        }

        .row-theme-4 .media span i {
            color: #FEF08A !important;
        }

        .row-theme-5 .media span i {
            color: #FECDD3 !important;
        }

        .transactions-row .dash-3d-card .media-body p.mb-1 {
            font-size: 0.82rem;
            line-height: 1.25;
        }

        .admin-pool-wrapper {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(245, 158, 11, 0.35);
            background:
                radial-gradient(circle at 0% 0%, rgba(245, 158, 11, 0.18), rgba(245, 158, 11, 0) 42%),
                linear-gradient(135deg, rgba(16, 20, 30, 0.96), rgba(10, 12, 18, 0.94));
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6);
        }

        .admin-pool-wrapper::before {
            content: "";
            position: absolute;
            inset: -35% 40% auto -35%;
            height: 280px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.35), rgba(245, 158, 11, 0));
            filter: blur(8px);
            animation: poolGlowDrift 9s linear infinite;
            pointer-events: none;
        }

        .admin-pool-wrapper .card-header {
            border-bottom: 1px solid rgba(245, 158, 11, 0.2);
            background: linear-gradient(90deg, rgba(245, 158, 11, 0.14), rgba(255, 255, 255, 0.02));
        }

        .admin-pool-wrapper .card-title {
            color: #FFFFFF;
            letter-spacing: 0.25px;
        }

        .withdrawal-overview-shell {
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(16, 20, 30, 0.96), rgba(10, 12, 18, 0.92));
            box-shadow: inset 0 1px 0 rgba(245, 158, 11, 0.15), 0 22px 48px rgba(0, 0, 0, 0.6);
            overflow: hidden;
        }

        .withdrawal-overview-shell .card-body {
            padding: 1.2rem 1.2rem 1.5rem;
        }

        .withdrawal-overview-title {
            margin: 0;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #FFD700;
            text-shadow: 0 0 24px rgba(245, 158, 11, 0.45);
        }

        .withdrawal-stat-card {
            position: relative;
            height: 100%;
            border-radius: 22px;
            padding: 1.2rem 1.2rem 1.1rem;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.12), rgba(18, 22, 32, 0.75));
            border: 1px solid rgba(245, 158, 11, 0.25);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08), 0 14px 30px rgba(0, 0, 0, 0.4);
            overflow: hidden;
        }

        .withdrawal-stat-card::before,
        .withdrawal-stat-card::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            z-index: 0;
        }

        .withdrawal-stat-card::before {
            width: 10px;
            height: 10px;
            background: #FFD700;
            top: 18px;
            left: 18px;
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.9);
        }

        .withdrawal-stat-card::after {
            width: 10px;
            height: 10px;
            background: #FFD700;
            top: 18px;
            right: 18px;
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.9);
        }

        .withdrawal-stat-inner {
            position: relative;
            z-index: 1;
        }

        .withdrawal-stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding-top: 0.2rem;
        }

        .withdrawal-stat-header .withdrawal-label {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #edf6ff;
            font-size: 1.05rem;
            font-weight: 600;
        }

        .withdrawal-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: rgba(245, 158, 11, 0.20);
            border: 1px solid rgba(245, 158, 11, 0.55);
            color: #FBBF24;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.16);
        }

        .withdrawal-amount {
            margin: 0;
            color: #f8fbff;
            font-size: clamp(1.5rem, 3vw, 2.4rem);
            font-weight: 800;
            letter-spacing: -0.04em;
        }

        .withdrawal-progress {
            position: relative;
            width: 100%;
            height: 6px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            overflow: hidden;
            margin-top: 1rem;
        }

        .withdrawal-progress span {
            display: block;
            height: 100%;
            border-radius: inherit;
            background: linear-gradient(90deg, #F59E0B, #FFD700 52%, #10B981);
            box-shadow: 0 0 18px rgba(245, 158, 11, 0.7);
        }

        .withdrawal-meta {
            margin-top: 0.85rem;
            color: rgba(245, 158, 11, 0.85);
            font-size: 0.78rem;
            letter-spacing: 0.02em;
        }

        .admin-pool-btn {
            position: relative;
            overflow: hidden;
            border: 0 !important;
            border-radius: 12px;
            background: linear-gradient(125deg, #FFD700, #F59E0B, #D97706) !important;
            background-size: 180% 180%;
            color: #000000 !important;
            font-weight: 800;
            letter-spacing: 0.3px;
            box-shadow: 0 10px 24px rgba(245, 158, 11, 0.4);
            transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
            animation: poolBtnFlow 4s ease infinite;
        }

        .admin-pool-btn::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 25%, rgba(255, 255, 255, 0.45) 50%, transparent 75%);
            transform: translateX(-130%);
            transition: transform 0.65s ease;
        }

        .admin-pool-btn:hover {
            transform: translateY(-2px);
            filter: saturate(1.1);
            box-shadow: 0 14px 28px rgba(245, 158, 11, 0.6);
            color: #000000 !important;
        }

        .admin-pool-btn:hover::before {
            transform: translateX(130%);
        }

        .admin-pool-btn:focus-visible {
            box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.4), 0 10px 24px rgba(245, 158, 11, 0.35);
            outline: none;
        }

        .commission-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(245, 158, 11, 0.25) !important;
            background:
                linear-gradient(145deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.04)) padding-box,
                linear-gradient(120deg, var(--pc1), var(--pc2), var(--pc3)) border-box;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45);
            backdrop-filter: blur(8px);
            transform: translateY(12px) scale(.985);
            opacity: 0;
            animation: poolCardIn .6s ease forwards;
            transition: transform .35s ease, box-shadow .35s ease, filter .35s ease;
        }

        .commission-card::before {
            content: "";
            position: absolute;
            inset: -120% -70%;
            background: linear-gradient(115deg, rgba(255, 255, 255, 0) 38%, rgba(255, 255, 255, 0.32) 50%, rgba(255, 255, 255, 0) 62%);
            transform: translateX(-40%);
            animation: poolShine 5.8s linear infinite;
            pointer-events: none;
        }

        .commission-card::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.45);
            pointer-events: none;
        }

        .commission-card:hover {
            transform: translateY(-8px) scale(1.01);
            filter: saturate(1.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.55);
        }

        .commission-card .card-body {
            position: relative;
            z-index: 1;
        }

        .commission-card .card-title,
        .commission-card .commission-updated {
            color: #eef2ff;
        }

        .commission-card .commission-value {
            color: #ffffff;
            text-shadow: 0 3px 10px rgba(0, 0, 0, 0.6);
        }

        .commission-card .progress {
            background: rgba(255, 255, 255, 0.22);
            border-radius: 999px;
            overflow: hidden;
        }

        .commission-card .progress .progress-bar {
            border-radius: 999px;
            box-shadow: 0 0 16px rgba(255, 255, 255, 0.5);
            animation: poolProgressPulse 2.2s ease-in-out infinite;
        }

        .commission-card.pool-card-1 {
            --pc1: #FFD700;
            --pc2: #F59E0B;
            --pc3: #D97706;
            animation-delay: .05s;
        }

        .commission-card.pool-card-2 {
            --pc1: #EF4444;
            --pc2: #DC2626;
            --pc3: #B91C1C;
            animation-delay: .15s;
        }

        .commission-card.pool-card-3 {
            --pc1: #00E676;
            --pc2: #10B981;
            --pc3: #059669;
            animation-delay: .25s;
        }

        .member-highlight-card .overlay-box:after {
            background: #101420 !important;
            opacity: 0.65 !important;
        }

        .dash-3d-card:hover {
            transform: translateY(-6px) rotateX(2deg) rotateY(-2deg);
            box-shadow: 0 20px 34px rgba(0, 0, 0, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.35);
            filter: saturate(1.1);
        }

        .dash-3d-card:hover::after {
            animation-duration: 1.4s;
        }

        .row-theme-1 {
            --g1: #F59E0B;
            --g2: #D97706;
            --g3: #FFD700;
        }

        .row-theme-2 {
            --g1: #EF4444;
            --g2: #DC2626;
            --g3: #991B1B;
        }

        .row-theme-3 {
            --g1: #00E676;
            --g2: #10B981;
            --g3: #059669;
        }

        .row-theme-4 {
            --g1: #B45309;
            --g2: #F59E0B;
            --g3: #10B981;
        }

        .row-theme-5 {
            --g1: #991B1B;
            --g2: #DC2626;
            --g3: #F59E0B;
        }

        @keyframes gradientFlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes shineMove {
            0% {
                transform: translate3d(-12%, -8%, 0) rotate(0deg);
            }

            50% {
                transform: translate3d(10%, 8%, 0) rotate(180deg);
            }

            100% {
                transform: translate3d(-12%, -8%, 0) rotate(360deg);
            }
        }

        @keyframes ballsFloat {
            0% {
                transform: translate3d(0, 0, 0) scale(1);
                opacity: .7;
            }

            50% {
                transform: translate3d(-10px, 8px, 0) scale(1.02);
                opacity: .95;
            }

            100% {
                transform: translate3d(-20px, 16px, 0) scale(1);
                opacity: .75;
            }
        }

        @keyframes shadeSweep {
            0% {
                transform: translateX(-30%) translateY(-8%) rotate(0deg);
                opacity: .25;
            }

            50% {
                transform: translateX(18%) translateY(10%) rotate(1deg);
                opacity: .6;
            }

            100% {
                transform: translateX(55%) translateY(-6%) rotate(0deg);
                opacity: .25;
            }
        }

        @keyframes shadePulse {
            0% {
                box-shadow: 0 12px 26px rgba(0, 0, 0, 0.22), inset 0 1px 1px rgba(255, 255, 255, 0.25);
            }

            50% {
                box-shadow: 0 16px 34px rgba(0, 0, 0, 0.34), inset 0 1px 1px rgba(255, 255, 255, 0.34);
            }

            100% {
                box-shadow: 0 12px 26px rgba(0, 0, 0, 0.22), inset 0 1px 1px rgba(255, 255, 255, 0.25);
            }
        }

        @keyframes poolGlowDrift {
            0% {
                transform: translate3d(-6%, -4%, 0);
            }

            50% {
                transform: translate3d(10%, 8%, 0);
            }

            100% {
                transform: translate3d(-6%, -4%, 0);
            }
        }

        @keyframes poolShine {
            0% {
                transform: translateX(-55%) rotate(0deg);
            }

            100% {
                transform: translateX(55%) rotate(1deg);
            }
        }

        @keyframes poolCardIn {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes poolBtnFlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes poolProgressPulse {

            0%,
            100% {
                filter: brightness(1);
            }

            50% {
                filter: brightness(1.16);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .admin-pool-wrapper::before,
            .commission-card,
            .commission-card::before,
            .commission-card .progress .progress-bar {
                animation: none !important;
            }
        }

        /* Timer Card Styles */
        .timer-card {
            background: linear-gradient(135deg, rgba(16, 20, 30, 0.94) 0%, rgba(10, 12, 18, 0.94) 100%) !important;
            border: 1px solid rgba(245, 158, 11, 0.3) !important;
            backdrop-filter: blur(12px);
            position: relative;
            overflow: hidden;
            box-shadow:
                0 8px 32px rgba(0, 0, 0, 0.6),
                inset 0 1px 0 rgba(245, 158, 11, 0.15);
        }

        .timer-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 50%);
            animation: timerRotate 10s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        .timer-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        .timer-card .card-body {
            position: relative;
            z-index: 1;
        }

        .timer-ad-btn {
            position: relative;
            z-index: 2;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            border-width: 2px;
        }

        @keyframes timerRotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .timer-display {
            font-family: 'Courier New', monospace;
            font-size: 3.5rem;
            font-weight: 700;
            letter-spacing: 8px;
            text-shadow:
                0 0 10px rgba(245, 158, 11, 0.9),
                0 0 20px rgba(245, 158, 11, 0.7),
                0 0 40px rgba(245, 158, 11, 0.5),
                0 0 80px rgba(16, 185, 129, 0.3);
            background: linear-gradient(180deg, #FFD700 0%, #F59E0B 50%, #10B981 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            position: relative;
            z-index: 1;
        }

        .timer-segment {
            display: inline-block;
            background: linear-gradient(180deg, rgba(245, 158, 11, 0.2) 0%, rgba(16, 185, 129, 0.1) 100%);
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            border: 1px solid rgba(245, 158, 11, 0.35);
            margin: 0 2px;
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.1),
                0 0 15px rgba(245, 158, 11, 0.25);
        }

        .timer-separator {
            animation: blink 1s infinite;
            color: #FFD700;
            text-shadow: 0 0 10px #FFD700;
            font-weight: bold;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.2;
            }
        }

        .timer-date {
            font-size: 1.1rem;
            letter-spacing: 3px;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 600;
            text-transform: uppercase;
            background: linear-gradient(90deg, #FFD700, #10B981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .timer-icon {
            animation: pulse 2s ease-in-out infinite;
            display: inline-block;
            filter: drop-shadow(0 0 10px rgba(245, 158, 11, 0.6));
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                filter: drop-shadow(0 0 10px rgba(245, 158, 11, 0.6));
            }

            50% {
                transform: scale(1.1);
                filter: drop-shadow(0 0 20px rgba(245, 158, 11, 0.9));
            }
        }

        .timer-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #F59E0B;
            font-weight: 800;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
        }

        .timer-glow {
            position: absolute;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.3) 0%, transparent 70%);
            top: -50px;
            right: -50px;
            animation: glowPulse 3s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes glowPulse {

            0%,
            100% {
                opacity: 0.5;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.2);
            }
        }

        /* Timer Card Responsive Styles */
        @media (max-width: 768px) {
            .timer-card {
                padding: 1rem !important;
            }

            .timer-card .card-body {
                padding: 1rem !important;
            }

            .timer-card .row {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .timer-card .col-auto {
                margin-bottom: 1rem;
            }

            .timer-display {
                font-size: 2rem !important;
                letter-spacing: 4px !important;
            }

            .timer-label {
                font-size: 0.6rem !important;
                letter-spacing: 2px !important;
            }

            .timer-date {
                font-size: 0.9rem !important;
                letter-spacing: 1px !important;
            }

            .timer-card svg {
                width: 40px !important;
                height: 40px !important;
            }

            .timer-card .d-flex {
                flex-direction: column;
                gap: 0.5rem;
            }

            .timer-card .text-end {
                text-align: center !important;
            }

            .timer-ad-btn {
                width: 100%;
                min-height: 46px;
            }

            .modal-dialog {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) !important;
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .timer-display {
                font-size: 1.5rem !important;
                letter-spacing: 2px !important;
            }

            .timer-label {
                font-size: 0.5rem !important;
            }

            .timer-date {
                font-size: 0.75rem !important;
            }

            .timer-card svg {
                width: 32px !important;
                height: 32px !important;
            }
        }
    </style>

    <!--********************************** Content body start ***********************************-->
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">
            <!-- Announcement Ticker Strip Card -->
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="announcement-ticker-card d-flex align-items-center">
                        <div class="ticker-badge d-flex align-items-center me-3 flex-shrink-0">
                            <svg class="me-1" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 22C13.1 22 14 21.1 14 20H10C10 21.1 10.89 22 12 22ZM18 16V11C18 7.93 16.36 5.36 13.5 4.68V4C13.5 3.17 12.83 2.5 12 2.5C11.17 2.5 10.5 3.17 10.5 4V4.68C7.63 5.36 6 7.92 6 11V16L4 18V19H20V18L18 16Z"
                                    fill="#FFFFFF" />
                            </svg>
                            <span class="ticker-label">ANNOUNCEMENT</span>
                        </div>
                        <div class="ticker-content flex-grow-1 overflow-hidden">
                            <marquee behavior="scroll" direction="left" scrollamount="5" onmouseover="this.stop();"
                                onmouseout="this.start();">
                                <div class="d-inline-flex align-items-center">
                                    @foreach ($dashMsg as $item)
                                        <span class="me-5 text-cyan-glow fw-semibold fs-6">
                                            <i class="fas fa-bullhorn me-2 text-warning"></i>{{ $item['message'] }}
                                        </span>
                                    @endforeach
                                </div>
                            </marquee>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="row main-card">
                        <!-- Date Time Card -->
                        <!-- Date Time Card -->
                        <div class="col-sm-12">
                            <div class="widget-stat card timer-card">
                                <div class="timer-glow"></div>
                                <div class="card-body p-4">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="timer-circle">
                                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="9" stroke="white"
                                                        stroke-width="2" />
                                                    <path d="M12 7V12L15 15" stroke="white" stroke-width="2"
                                                        stroke-linecap="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <p class="timer-label mb-0">SYSTEM DATE & TIME</p>
                                                    <h2 class="timer-display mb-0" id="currentDateTime"></h2>
                                                </div>
                                                <div class="text-end">
                                                    <p class="timer-date mb-0" id="currentDate"></p>
                                                    <small class="text-white-50" id="timezone"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 col-md-12">
                            <style>
                                /* Dashboard Profile Card Styles */
                                .mb-profile-card {
                                    background: #0C0E14;
                                    border: 1px solid rgba(245, 158, 11, 0.20);
                                    border-radius: 18px;
                                    box-shadow: 0 16px 45px rgba(0, 0, 0, 0.60), 0 0 0 1px rgba(245, 158, 11, 0.12);
                                    padding: 22px 28px;
                                    position: relative;
                                    overflow: hidden;
                                    background-image: linear-gradient(135deg, rgba(245, 158, 11, 0.06), rgba(220, 38, 38, 0.03));
                                    margin-bottom: 24px;
                                }

                                .mb-profile-card::before {
                                    content: '';
                                    position: absolute;
                                    width: 220px;
                                    height: 220px;
                                    right: -40px;
                                    top: -90px;
                                    border-radius: 50%;
                                    background: radial-gradient(circle, rgba(245, 158, 11, 0.18) 0%, transparent 70%);
                                    pointer-events: none;
                                }

                                .mb-profile-wrap {
                                    display: flex;
                                    align-items: center;
                                    justify-content: space-between;
                                    gap: 20px;
                                    flex-wrap: wrap;
                                    position: relative;
                                    z-index: 1;
                                }

                                .mb-profile-main {
                                    display: flex;
                                    align-items: center;
                                    gap: 20px;
                                    flex-wrap: wrap;
                                }

                                .mb-avatar-box {
                                    position: relative;
                                    flex-shrink: 0;
                                }

                                .mb-avatar-img {
                                    width: 68px;
                                    height: 68px;
                                    border-radius: 50%;
                                    object-fit: cover;
                                    border: 2px solid #F59E0B;
                                    box-shadow: 0 0 16px rgba(245, 158, 11, 0.40);
                                    background: #08090C;
                                }

                                .mb-avatar-fallback {
                                    width: 68px;
                                    height: 68px;
                                    border-radius: 50%;
                                    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
                                    border: 2px solid #F59E0B;
                                    box-shadow: 0 0 16px rgba(245, 158, 11, 0.40);
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    color: #000;
                                    font-size: 24px;
                                    font-weight: 800;
                                }

                                .mb-profile-info h3 {
                                    color: #FFFFFF;
                                    font-size: 20px;
                                    font-weight: 800;
                                    margin: 0 0 6px 0;
                                    display: flex;
                                    align-items: center;
                                    gap: 10px;
                                    flex-wrap: wrap;
                                    letter-spacing: 0.3px;
                                }

                                .mb-status-badge {
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 6px;
                                    padding: 4px 12px;
                                    border-radius: 50px;
                                    font-size: 11.5px;
                                    font-weight: 700;
                                }

                                .mb-status-badge.active {
                                    background: rgba(16, 185, 129, 0.12);
                                    color: #10B981;
                                    border: 1px solid rgba(16, 185, 129, 0.30);
                                }

                                .mb-status-badge.inactive {
                                    background: rgba(239, 68, 68, 0.12);
                                    color: #EF4444;
                                    border: 1px solid rgba(239, 68, 68, 0.30);
                                }

                                .mb-status-dot {
                                    width: 7px;
                                    height: 7px;
                                    border-radius: 50%;
                                    background: currentColor;
                                    display: inline-block;
                                    box-shadow: 0 0 8px currentColor;
                                }

                                .mb-email-pill {
                                    display: inline-flex;
                                    align-items: center;
                                    gap: 8px;
                                    color: #98A2C3;
                                    font-size: 13.5px;
                                    font-weight: 500;
                                    background: rgba(255, 255, 255, 0.04);
                                    border: 1px solid rgba(255, 255, 255, 0.07);
                                    padding: 5px 14px;
                                    border-radius: 10px;
                                    margin-top: 4px;
                                }

                                .mb-email-pill i {
                                    color: #F59E0B;
                                    font-size: 14px;
                                }

                                .mb-edit-btn {
                                    width: 44px;
                                    height: 44px;
                                    border-radius: 12px;
                                    background: rgba(245, 158, 11, 0.12);
                                    border: 1px solid rgba(245, 158, 11, 0.30);
                                    color: #F59E0B;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 17px;
                                    text-decoration: none;
                                    transition: all 0.25s ease;
                                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                                }

                                .mb-edit-btn:hover {
                                    background: linear-gradient(135deg, #F59E0B, #D97706);
                                    border-color: transparent;
                                    color: #000000;
                                    box-shadow: 0 6px 20px rgba(245, 158, 11, 0.45);
                                    transform: translateY(-1.5px);
                                }
                            </style>

                            <div class="mb-profile-card">
                                <div class="mb-profile-wrap">
                                    <div class="mb-profile-main">
                                        <div class="mb-avatar-box">
                                            @if (!empty($data['profile_image']) && file_exists(public_path('uploads/' . $data['profile_image'])))
                                                <img src="{{ asset('uploads/' . $data['profile_image']) }}"
                                                    class="mb-avatar-img" alt="Profile Avatar"
                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="mb-avatar-fallback" style="display: none;">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                            @else
                                                <div class="mb-avatar-fallback">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mb-profile-info">
                                            <h3>
                                                <span>{{ $data['memberid'] }}</span>
                                                @if (strtolower(trim($data['status'] ?? '')) === 'active')
                                                    <span class="mb-status-badge active">
                                                        <span class="mb-status-dot"></span> Active
                                                    </span>
                                                @else
                                                    <span class="mb-status-badge inactive">
                                                        <span class="mb-status-dot"></span>
                                                        {{ $data['status'] == 'Temp' ? 'Inactive' : 'Blocked' }}
                                                    </span>
                                                @endif
                                            </h3>
                                            <div class="mb-email-pill">
                                                <i class="fa-solid fa-envelope"></i>
                                                <span>{{ $data['email'] ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-profile-actions ms-auto">
                                        <a href="{{ url('/member/profile/profile') }}" class="mb-edit-btn"
                                            title="Edit Profile">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!--<div class="col-xl-12 col-md-12">-->
                        <!--    <div class="card">-->
                        <!--        <div class="card-body">-->
                        <!--            <div class="row g-2">-->
                        <!--                <div class="col-xl-3 col-md-6 col-12">-->
                        <!--                    <a href="{{ url('/member/seller/seller-activation') }}"-->
                        <!--                        class="btn btn-primary w-100">-->
                        <!--                        Activate Seller Account-->
                        <!--                    </a>-->
                        <!--                </div>-->
                        <!--                <div class="col-xl-3 col-md-6 col-12">-->
                        <!--                    <a href="{{ url('/member/advertiser-registration') }}"-->
                        <!--                        class="btn btn-success w-100">-->
                        <!--                        Activate Advertiser Account-->
                        <!--                    </a>-->
                        <!--                </div>-->
                        <!--                <div class="col-xl-3 col-md-6 col-12">-->
                        <!--                    @if ($data['seller_status'] == 'Inactive')
    -->
                        <!--                        <a href="javascript:void(0);"-->
                        <!--                            onclick="alert('First activate seller account'); return false;"-->
                        <!--                            class="btn btn-info w-100 text-white">-->
                        <!--                            Sell USDT-->
                        <!--                        </a>-->
                    <!--                    @else-->
                        <!--                        <a href="{{ url('/member/seller/seller-profile') }}"-->
                        <!--                            class="btn btn-info w-100 text-white">-->
                        <!--                            Sell USDT-->
                        <!--                        </a>-->
                        <!--
    @endif-->
                        <!--                </div>-->
                        <!--                <div class="col-xl-3 col-md-6 col-12">-->
                        <!--                    <a href="{{ url('/member/buy-usdt') }}" class="btn btn-warning w-100">-->
                        <!--                        Buy USDT-->
                        <!--                    </a>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->


                        <!-- WhatsApp Daily Promotion (1000 PEPE Reward) Section -->
                        <div class="col-xl-12 col-md-12">
                            <div class="card overflow-hidden"
                                style="background: linear-gradient(135deg, #091224 0%, #0c1c38 100%); border: 2px solid #00e676; border-radius: 16px; box-shadow: 0 0 25px rgba(0, 230, 118, 0.25);">
                                <div class="card-body p-4">
                                    <!-- Banner Top Section -->
                                    <div class="row align-items-center">
                                        <div class="col-lg-7 col-md-12 mb-4 mb-lg-0">
                                            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="fab fa-whatsapp text-success me-2" style="font-size: 32px;"></i>
                                                    <h2 class="text-white font-weight-bold mb-0"
                                                        style="font-size: 28px; letter-spacing: 1px;">
                                                        DAILY <span style="color: #00e676;">PROMOTION</span>
                                                    </h2>
                                                </div>
                                                <a href="{{ url('/member/whatsapp/referral-details') }}"
                                                    class="btn btn-sm font-weight-bold d-inline-flex align-items-center"
                                                    title="View Full Promotion Airdrop History"
                                                    style="background: rgba(0, 230, 118, 0.15); color: #00e676; border: 1px solid rgba(0, 230, 118, 0.4); border-radius: 8px; padding: 5px 12px; font-size: 12px;">
                                                    <i class="fas fa-history me-1"></i> Airdrop History
                                                </a>
                                            </div>
                                            <div class="d-flex align-items-center bg-dark p-3 rounded mb-3"
                                                style="border-left: 4px solid #00e676;">
                                                <div class="me-3">
                                                    <span class="badge rounded-circle p-2"
                                                        style="background: rgba(0, 230, 118, 0.2); color: #00e676;">
                                                        <i class="fas fa-coins fa-2x"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <h3 class="mb-0 text-white font-weight-bold">
                                                        500 <span style="color: #00e676;">PEPE (BEP20)</span>
                                                    </h3>
                                                    <small class="text-white-50 text-uppercase"
                                                        style="letter-spacing: 0.5px;">Tokens Per WhatsApp Message (Max 10/Day = 5,000 PEPE)</small>
                                                </div>
                                            </div>
                                            <!-- Promotional Note Box -->
                                            <div class="p-3 rounded"
                                                style="background: rgba(0, 230, 118, 0.08); border: 1px solid rgba(0, 230, 118, 0.3);">
                                                <div class="d-flex align-items-start">
                                                    <i class="fab fa-whatsapp me-2 mt-1"
                                                        style="color: #00e676; font-size: 20px;"></i>
                                                    <p class="mb-0 text-white" style="font-size: 13px; line-height: 1.5;">
                                                        Promote your Referral Link. Send WhatsApp messages to earn <strong
                                                            style="color: #00e676;">500 PEPE Token</strong> per message (Max 10 messages/day = <strong style="color: #00e676;">5,000 PEPE/day</strong>). Plus earn <strong style="color: #00e676;">500 PEPE</strong> on Direct Registration and <strong style="color: #00e676;">500 PEPE</strong> on Direct Activation!
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-5 col-md-12">
                                            <!-- Feature Bullets -->
                                            <div class="ps-lg-3">
                                                <div class="d-flex align-items-center mb-3 p-2 rounded"
                                                    style="background: rgba(255, 255, 255, 0.04);">
                                                    <div class="rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px; background: rgba(0, 230, 118, 0.15); border: 1px solid #00e676;">
                                                        <i class="fas fa-paper-plane" style="color: #00e676;"></i>
                                                    </div>
                                                    <div class="text-white" style="font-size: 13px;">
                                                        <strong style="color: #00e676;">Rule 1:</strong> Up to <strong style="color: #00e676;">10 messages/day</strong> (500 PEPE each, max 5,000 PEPE/day).
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center mb-3 p-2 rounded"
                                                    style="background: rgba(255, 255, 255, 0.04);">
                                                    <div class="rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px; background: rgba(0, 230, 118, 0.15); border: 1px solid #00e676;">
                                                        <i class="fas fa-user-plus" style="color: #00e676;"></i>
                                                    </div>
                                                    <div class="text-white" style="font-size: 13px;">
                                                        <strong style="color: #00e676;">Rule 2:</strong> Direct Referral Registration = <strong style="color: #00e676;">500 PEPE</strong>.
                                                    </div>
                                                </div>

                                                <div class="d-flex align-items-center mb-3 p-2 rounded"
                                                    style="background: rgba(255, 255, 255, 0.04);">
                                                    <div class="rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px; background: rgba(0, 230, 118, 0.15); border: 1px solid #00e676;">
                                                        <i class="fas fa-bolt" style="color: #00e676;"></i>
                                                    </div>
                                                    <div class="text-white" style="font-size: 13px;">
                                                        <strong style="color: #00e676;">Rule 3:</strong> Direct Referral Activation = <strong style="color: #00e676;">500 PEPE</strong>.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr style="border-color: rgba(255, 255, 255, 0.1);" class="my-4">

                                    <!-- Statistics Section -->
                                    <div class="row g-2 mb-4 text-center">
                                        <div class="col-lg-2 col-md-4 col-6">
                                            <div class="p-2 rounded bg-dark border border-secondary text-center h-100 d-flex flex-column justify-content-between">
                                                <div>
                                                    <small class="text-white-50 d-block mb-1">Today's Share</small>
                                                    @if (($waTodayCount ?? 0) >= 10)
                                                        <span class="badge bg-danger py-1 px-2 font-weight-bold d-inline-block"
                                                            id="waTodayStatusBadge" style="font-size: 12px;">
                                                            <i class="fas fa-ban me-1"></i> Limit (10/10)
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark py-1 px-2 font-weight-bold d-inline-block"
                                                            id="waTodayStatusBadge" style="font-size: 12px;">
                                                            <i class="fas fa-clock me-1"></i> ({{ $waTodayCount ?? 0 }}/10)
                                                        </span>
                                                    @endif
                                                </div>
                                                <small class="text-white-50 mt-1 d-block" style="font-size: 10px;">Max 10 / Day</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-6">
                                            <div class="p-2 rounded bg-dark border border-secondary text-center h-100 d-flex flex-column justify-content-between">
                                                <div>
                                                    <small class="text-white-50 d-block mb-1">Rule 1: Messages</small>
                                                    <h5 class="text-white font-weight-bold mb-0" id="waTotalReferralsVal">
                                                        {{ $waTotalReferrals ?? 0 }}
                                                    </h5>
                                                </div>
                                                <small class="text-success mt-1 d-block" style="font-size: 10px;">500/msg</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-6">
                                            <div class="p-2 rounded bg-dark border border-secondary text-center h-100 d-flex flex-column justify-content-between">
                                                <div>
                                                    <small class="text-white-50 d-block mb-1">Rule 2: Direct Reg</small>
                                                    <h5 class="text-white font-weight-bold mb-0">
                                                        {{ $waDirectRegCount ?? 0 }}
                                                    </h5>
                                                </div>
                                                <small class="text-info mt-1 d-block" style="font-size: 10px;">+500 PEPE each</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-6">
                                            <div class="p-2 rounded bg-dark border border-secondary text-center h-100 d-flex flex-column justify-content-between">
                                                <div>
                                                    <small class="text-white-50 d-block mb-1">Rule 3: Direct Act</small>
                                                    <h5 class="text-white font-weight-bold mb-0">
                                                        {{ $waDirectActCount ?? 0 }}
                                                    </h5>
                                                </div>
                                                <small class="text-warning mt-1 d-block" style="font-size: 10px;">+500 PEPE each</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-6">
                                            <div class="p-2 rounded bg-dark border border-secondary text-center h-100 d-flex flex-column justify-content-between">
                                                <div>
                                                    <small class="text-white-50 d-block mb-1">Total PEPE Earned</small>
                                                    <h5 class="text-success font-weight-bold mb-0" id="waTotalPepeVal" style="font-size: 14px;">
                                                        {{ number_format($waTotalPepe ?? 0, 0) }} PEPE
                                                    </h5>
                                                </div>
                                                <small class="text-white-50 mt-1 d-block" style="font-size: 10px;">All 3 Rules</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-4 col-6">
                                            <div class="p-2 rounded bg-dark border position-relative text-center h-100 d-flex flex-column justify-content-between"
                                                style="border-color: #00e676 !important; background: rgba(0, 230, 118, 0.04) !important;">
                                                <div>
                                                    <small class="text-white-50 d-block mb-1">Current Wallet</small>
                                                    <h5 class="font-weight-bold mb-0" style="color: #00e676; font-size: 14px;"
                                                        id="pepeWalletDisplay">
                                                        {{ number_format($waAvailablePepe ?? ($data['pepe_wallet'] ?? 0), 0) }} PEPE
                                                    </h5>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-center gap-1 mt-2">
                                                    <a href="{{ url('/member/whatsapp/referral-details') }}"
                                                        class="btn btn-xs py-1 px-1 font-weight-bold flex-fill"
                                                        title="View Full Promotion Airdrop History"
                                                        style="background: rgba(0, 230, 118, 0.18); color: #00e676; font-size: 10px; border-radius: 4px; border: 1px solid rgba(0, 230, 118, 0.35); height: 22px; line-height: 20px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        <i class="fas fa-list me-1"></i> History
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-xs py-1 px-1 font-weight-bold flex-fill"
                                                        onclick="openPepeRedeemPrompt()"
                                                        title="Redeem PEPE tokens"
                                                        style="background: linear-gradient(135deg, #F59E0B 0%, #10B981 100%); color: #08090C; font-size: 10px; border-radius: 4px; border: none; height: 22px; line-height: 22px; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        <i class="fas fa-hand-holding-usd me-1"></i> Redeem
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- For Promotion Bonus in PEPE BEP20 Token withdrawal Section -->
                                    <div class="mb-4 p-3 rounded"
                                        style="background: linear-gradient(135deg, rgba(0, 230, 118, 0.08) 0%, rgba(12, 28, 56, 0.85) 100%); border: 1px solid rgba(0, 230, 118, 0.35); box-shadow: 0 4px 18px rgba(0, 0, 0, 0.3);">
                                        <div class="row align-items-center g-3">
                                            <div class="col-xxl-5 col-xl-5 col-lg-5 col-md-12">
                                                <div class="d-flex align-items-center mb-1 flex-wrap gap-1">
                                                    <span class="badge me-1"
                                                        style="background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.4); font-size: 11px; padding: 3px 7px;">
                                                        <i class="fas fa-bullhorn me-1"></i> Bonus Note
                                                    </span>
                                                    <span class="text-white font-weight-bold"
                                                        style="font-size: 13px; letter-spacing: 0.2px;">
                                                        For Promotion Bonus in PEPE BEP20 Token withdrawal:
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center flex-wrap gap-2 mt-1">
                                                    <span style="color: #00e676; font-size: 12.5px; font-weight: 600;">
                                                        <i class="fas fa-file-contract me-1"></i> PEPE BEP20 Token Contract
                                                        Address
                                                    </span>
                                                    <span class="badge"
                                                        style="background: rgba(243, 186, 47, 0.18); color: #f3ba2f; border: 1px solid rgba(243, 186, 47, 0.35); font-size: 10px;">
                                                        <i class="fas fa-cube me-1"></i> BEP-20 (BSC)
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-xxl-7 col-xl-7 col-lg-7 col-md-12">
                                                <div class="d-flex align-items-center gap-2 flex-wrap flex-sm-nowrap">
                                                    <div class="p-2 rounded bg-dark border flex-grow-1"
                                                        style="border-color: rgba(0, 230, 118, 0.4) !important; background-color: #060d19 !important; min-width: 0;">
                                                        <code id="pepeContractAddressText"
                                                            class="text-white d-block text-truncate font-weight-bold px-1"
                                                            style="font-size: 12px; font-family: 'Courier New', monospace; letter-spacing: 0.2px;"
                                                            title="{{ $pepeSettings->contract_address ?? '0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00' }}">
                                                            {{ $pepeSettings->contract_address ?? '0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00' }}
                                                        </code>
                                                    </div>

                                                    <div class="d-flex gap-1 flex-shrink-0">
                                                        <!-- Copy Button -->
                                                        <button type="button" id="btnCopyPepeContract"
                                                            onclick="copyPepeContractAddress()"
                                                            class="btn btn-sm px-3 font-weight-bold d-inline-flex align-items-center justify-content-center"
                                                            style="background: #00e676; color: #000; border: none; border-radius: 6px; height: 36px; box-shadow: 0 0 10px rgba(0, 230, 118, 0.35);"
                                                            title="Copy Contract Address">
                                                            <i class="far fa-copy me-1" id="copyIcon"></i>
                                                            <span id="copyText">Copy</span>
                                                        </button>

                                                        <!-- Share Button -->
                                                        <button type="button" onclick="sharePepeContractAddress()"
                                                            class="btn btn-sm px-3 font-weight-bold d-inline-flex align-items-center justify-content-center"
                                                            style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: #fff; border: none; border-radius: 6px; height: 36px;"
                                                            title="Share PEPE Contract Address via WhatsApp">
                                                            <i class="fab fa-whatsapp me-1"></i> Share
                                                        </button>

                                                        <!-- BscScan Link -->
                                                        <a href="{{ rtrim($pepeSettings->explorer_url ?? 'https://bscscan.com', '/') }}/token/{{ $pepeSettings->contract_address ?? '0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00' }}"
                                                            target="_blank" rel="noopener noreferrer"
                                                            class="btn btn-sm px-2 font-weight-bold d-inline-flex align-items-center justify-content-center"
                                                            style="background: rgba(255, 255, 255, 0.08); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 6px; height: 36px; width: 36px;"
                                                            title="View on BscScan">
                                                            <i class="fas fa-external-link-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Interactive Verification & Referral Form with Country Selection -->
                                    <div class="p-3 rounded"
                                        style="background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1);">
                                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom"
                                            style="border-color: rgba(255, 255, 255, 0.08) !important;">
                                            <h6 class="text-white font-weight-bold mb-0 d-flex align-items-center"
                                                style="font-size: 14px; letter-spacing: 0.3px;">
                                                <i class="fab fa-whatsapp text-success me-2" style="font-size: 16px;"></i>
                                                <span>Send Promotional Message</span>
                                            </h6>
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"
                                                style="font-size: 11px; font-weight: 500;">
                                                <i class="fas fa-gift me-1"></i> Earn 500 PEPE / msg (Max 10/day)
                                            </span>
                                        </div>
                                        <div class="row align-items-center g-3">
                                            <!-- Country Selection -->
                                            <div class="col-xl-4 col-lg-4 col-md-5">
                                                <label for="wa_country" class="text-white font-weight-bold mb-1"
                                                    style="font-size: 13px;">
                                                    <i class="fas fa-globe me-1 text-info"></i> Select Country <span
                                                        class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-dark text-white border-secondary"
                                                        style="border-color: rgba(255, 255, 255, 0.15) !important;">
                                                        <i class="fas fa-flag text-info"></i>
                                                    </span>
                                                    <select id="wa_country"
                                                        class="form-select bg-dark text-white border-secondary"
                                                        style="border-color: rgba(255, 255, 255, 0.15) !important; background-color: #0c1a2e !important;"
                                                        onchange="onWaCountryChange()"
                                                        {{ ($waTodayCount ?? 0) >= 10 ? 'disabled' : '' }}>
                                                        <option value="" data-phonecode="" selected>Select Country
                                                        </option>
                                                        @if (isset($countries) && count($countries) > 0)
                                                            @foreach ($countries as $c)
                                                                <option value="{{ $c->nicename }}"
                                                                    data-phonecode="{{ $c->phonecode }}"
                                                                    style="background-color: #0c1a2e; color: #fff;">
                                                                    {{ $c->nicename }} (+{{ $c->phonecode }})
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- WhatsApp Mobile Number Input with Dynamic Country Code Badge -->
                                            <div class="col-xl-5 col-lg-5 col-md-7">
                                                <label for="wa_mobile_local" class="text-white font-weight-bold mb-1"
                                                    style="font-size: 13px;">
                                                    <i class="fab fa-whatsapp me-1 text-success"></i> WhatsApp Mobile
                                                    Number <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-dark text-white border-secondary"
                                                        style="border-color: rgba(255, 255, 255, 0.15) !important;">
                                                        <i class="fab fa-whatsapp text-success"></i>
                                                    </span>
                                                    <span class="input-group-text font-weight-bold border-secondary"
                                                        id="wa_code_badge"
                                                        style="background: rgba(0, 230, 118, 0.12); color: #00e676; border-color: rgba(255, 255, 255, 0.15) !important; min-width: 62px; justify-content: center; font-size: 13px;">
                                                        +--
                                                    </span>
                                                    <input type="tel" id="wa_mobile_local"
                                                        class="form-control bg-dark text-white border-secondary"
                                                        style="border-color: rgba(255, 255, 255, 0.15) !important;"
                                                        placeholder="Select country first" oninput="syncWaFullNumber()"
                                                        {{ ($waTodayCount ?? 0) >= 10 ? 'disabled' : '' }}>
                                                    <input type="hidden" id="wa_mobile" name="wa_mobile">
                                                </div>
                                                <div id="wa_country_hint" class="text-white-50 mt-1"
                                                    style="font-size: 11px; display: none;"></div>
                                            </div>

                                            <!-- Action Buttons & Status -->
                                            <div class="col-xl-3 col-lg-3 col-md-12 text-md-end pt-md-3 pt-xl-0">
                                                <div id="waAlertMsg" class="mb-2 text-start"
                                                    style="display: none; font-size: 12px;">
                                                </div>

                                                @if (($waTodayCount ?? 0) >= 10)
                                                    <button type="button"
                                                        class="btn btn-secondary w-100 py-2 font-weight-bold" disabled
                                                        style="opacity: 0.85;">
                                                        <i class="fas fa-check-circle text-success me-1"></i> Today's Limit Reached (10/10)
                                                    </button>
                                                    <small class="text-white-50 d-block mt-1 text-center"
                                                        style="font-size: 11px;">
                                                        <i class="fas fa-coins text-warning me-1"></i> Maximum 5,000 PEPE claimed today (10/10). Next rewards unlock tomorrow!
                                                    </small>
                                                @else
                                                    <button type="button" id="btnVerifyWa" onclick="verifyWaMobile()"
                                                        class="btn btn-warning w-100 py-2 font-weight-bold">
                                                        <i class="fas fa-shield-alt me-1"></i> Verify Mobile Number
                                                    </button>

                                                    <button type="button" id="btnSendWa" onclick="processWaReferral()"
                                                        class="btn btn-success w-100 py-2 font-weight-bold"
                                                        style="display: none; background-color: #00e676; border-color: #00e676; color: #000;">
                                                        <i class="fab fa-whatsapp me-1"></i> Send WhatsApp &amp; Claim 500 PEPE
                                                    </button>
                                                    <small class="text-white-50 d-block mt-1 text-center" id="waRemainingHint"
                                                        style="font-size: 11px;">
                                                        {{ 10 - ($waTodayCount ?? 0) }} of 10 messages remaining today
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SweetAlert2 & Ethers.js Libraries -->
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.2/ethers.umd.js"></script>

                        <script>
                            // Copy PEPE Contract Address
                            function copyPepeContractAddress() {
                                var contract = "{{ $pepeSettings->contract_address ?? '0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00' }}";
                                if (navigator.clipboard && window.isSecureContext) {
                                    navigator.clipboard.writeText(contract).then(onCopySuccess).catch(fallbackCopy);
                                } else {
                                    fallbackCopy();
                                }

                                function fallbackCopy() {
                                    var tempInput = document.createElement("input");
                                    tempInput.value = contract;
                                    document.body.appendChild(tempInput);
                                    tempInput.select();
                                    document.execCommand("copy");
                                    document.body.removeChild(tempInput);
                                    onCopySuccess();
                                }

                                function onCopySuccess() {
                                    var btn = document.getElementById("btnCopyPepeContract");
                                    var copyText = document.getElementById("copyText");
                                    var copyIcon = document.getElementById("copyIcon");

                                    if (copyText) copyText.innerText = "Copied!";
                                    if (copyIcon) copyIcon.className = "fas fa-check me-1";
                                    if (btn) btn.style.background = "#22c55e";

                                    setTimeout(function() {
                                        if (copyText) copyText.innerText = "Copy";
                                        if (copyIcon) copyIcon.className = "far fa-copy me-1";
                                        if (btn) btn.style.background = "#00e676";
                                    }, 2000);

                                    if (typeof Swal !== 'undefined') {
                                        const Toast = Swal.mixin({
                                            toast: true,
                                            position: 'top-end',
                                            showConfirmButton: false,
                                            timer: 2500,
                                            timerProgressBar: true
                                        });
                                        Toast.fire({
                                            icon: 'success',
                                            title: 'Contract Address Copied!'
                                        });
                                    }
                                }
                            }

                            // Share PEPE Contract Address
                            function sharePepeContractAddress() {
                                var contract = "{{ $pepeSettings->contract_address ?? '0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00' }}";
                                var network = "{{ $pepeSettings->network_name ?? 'BNB Smart Chain (BEP20)' }}";
                                var decimals = "{{ $pepeSettings->token_decimals ?? 18 }}";
                                var shareMsg = "For Promotion Bonus in PEPE BEP20 Token withdrawal:\n\nPEPE BEP20 Token Contract Address:\n" +
                                    contract +
                                    "\n\nNetwork: " + network + "\nDecimals: " + decimals +
                                    "\nAdd this contract address to your Trust Wallet or MetaMask.";

                                if (navigator.share) {
                                    navigator.share({
                                        title: 'PEPE BEP20 Contract Address',
                                        text: shareMsg
                                    }).catch(function() {
                                        var waUrl = "https://api.whatsapp.com/send?text=" + encodeURIComponent(shareMsg);
                                        window.open(waUrl, '_blank');
                                    });
                                } else {
                                    var waUrl = "https://api.whatsapp.com/send?text=" + encodeURIComponent(shareMsg);
                                    window.open(waUrl, '_blank');
                                }
                            }

                            // Country-specific standard mobile digit ranges
                            var countryLengthMap = {
                                '91': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 9876543210)'
                                },
                                '1': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 2025550199)'
                                },
                                '44': {
                                    min: 9,
                                    max: 11,
                                    label: '9-11 digits (e.g. 7911123456)'
                                },
                                '971': {
                                    min: 9,
                                    max: 9,
                                    label: '9 digits (e.g. 501234567)'
                                },
                                '966': {
                                    min: 9,
                                    max: 9,
                                    label: '9 digits (e.g. 501234567)'
                                },
                                '65': {
                                    min: 8,
                                    max: 8,
                                    label: '8 digits (e.g. 81234567)'
                                },
                                '974': {
                                    min: 8,
                                    max: 8,
                                    label: '8 digits (e.g. 33123456)'
                                },
                                '965': {
                                    min: 8,
                                    max: 8,
                                    label: '8 digits (e.g. 51234567)'
                                },
                                '973': {
                                    min: 8,
                                    max: 8,
                                    label: '8 digits (e.g. 39123456)'
                                },
                                '968': {
                                    min: 8,
                                    max: 8,
                                    label: '8 digits (e.g. 91234567)'
                                },
                                '60': {
                                    min: 9,
                                    max: 10,
                                    label: '9-10 digits (e.g. 123456789)'
                                },
                                '92': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 3001234567)'
                                },
                                '880': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 1712345678)'
                                },
                                '63': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 9171234567)'
                                },
                                '61': {
                                    min: 9,
                                    max: 9,
                                    label: '9 digits (e.g. 412345678)'
                                },
                                '49': {
                                    min: 10,
                                    max: 11,
                                    label: '10-11 digits (e.g. 15112345678)'
                                },
                                '33': {
                                    min: 9,
                                    max: 9,
                                    label: '9 digits (e.g. 612345678)'
                                },
                                '39': {
                                    min: 9,
                                    max: 10,
                                    label: '9-10 digits (e.g. 3123456789)'
                                },
                                '7': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 9123456789)'
                                },
                                '86': {
                                    min: 11,
                                    max: 11,
                                    label: '11 digits (e.g. 13800138000)'
                                },
                                '81': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 9012345678)'
                                },
                                '82': {
                                    min: 9,
                                    max: 10,
                                    label: '9-10 digits (e.g. 1012345678)'
                                },
                                '234': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 8021234567)'
                                },
                                '254': {
                                    min: 9,
                                    max: 9,
                                    label: '9 digits (e.g. 712345678)'
                                },
                                '27': {
                                    min: 9,
                                    max: 9,
                                    label: '9 digits (e.g. 821234567)'
                                },
                                '20': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 1012345678)'
                                },
                                '90': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 5321234567)'
                                },
                                '977': {
                                    min: 10,
                                    max: 10,
                                    label: '10 digits (e.g. 9812345678)'
                                },
                                '94': {
                                    min: 9,
                                    max: 9,
                                    label: '9 digits (e.g. 712345678)'
                                }
                            };

                            var verifiedMobileNumber = '';
                            var verifiedCountryName = '';
                            var verifiedPhoneCode = '';

                            function onWaCountryChange() {
                                var selectedOpt = $('#wa_country').find('option:selected');
                                var phonecode = selectedOpt.data('phonecode');
                                phonecode = phonecode ? String(phonecode).replace(/[^0-9]/g, '') : '';

                                if (phonecode) {
                                    $('#wa_code_badge').text('+' + phonecode);
                                    var rule = countryLengthMap[phonecode];
                                    if (rule) {
                                        $('#wa_mobile_local').attr('placeholder', 'Enter ' + rule.label);
                                        $('#wa_mobile_local').attr('maxlength', rule.max + 5);
                                        $('#wa_country_hint').html('<i class="fas fa-info-circle me-1 text-info"></i>Format: ' + rule.label)
                                            .show();
                                    } else {
                                        $('#wa_mobile_local').attr('placeholder', 'Enter Mobile Number');
                                        $('#wa_mobile_local').attr('maxlength', '18');
                                        $('#wa_country_hint').html(
                                            '<i class="fas fa-info-circle me-1 text-info"></i>Enter mobile number for +' + phonecode).show();
                                    }
                                } else {
                                    $('#wa_code_badge').text('+--');
                                    $('#wa_mobile_local').attr('placeholder', 'Select country first');
                                    $('#wa_country_hint').hide();
                                }
                                syncWaFullNumber();
                            }

                            function syncWaFullNumber() {
                                var phonecode = $('#wa_country').find('option:selected').data('phonecode');
                                phonecode = phonecode ? String(phonecode).replace(/[^0-9]/g, '') : '';

                                var rawMobile = ($('#wa_mobile_local').val() || '').trim();
                                var cleanDigits = rawMobile.replace(/[^0-9]/g, '');

                                var rule = countryLengthMap[phonecode];
                                var maxDigits = rule ? rule.max : 10;

                                // 1. If user pasted with explicit international prefix (+phonecode or 00phonecode)
                                var compactRaw = rawMobile.replace(/[\s\-\(\)]/g, '');
                                if (phonecode && compactRaw.startsWith('+' + phonecode)) {
                                    cleanDigits = compactRaw.substring(phonecode.length + 1).replace(/[^0-9]/g, '');
                                    $('#wa_mobile_local').val(cleanDigits);
                                } else if (phonecode && compactRaw.startsWith('00' + phonecode)) {
                                    cleanDigits = compactRaw.substring(phonecode.length + 2).replace(/[^0-9]/g, '');
                                    $('#wa_mobile_local').val(cleanDigits);
                                } else {
                                    // Remove leading trunk zero if user typed e.g. 0987...
                                    cleanDigits = cleanDigits.replace(/^0+/, '');

                                    // 2. Only strip country code if the digits strictly EXCEED expected max local length
                                    // This prevents deleting digits from valid local numbers that happen to start with the same digits as country code (e.g. India 91xxxxxxxx)
                                    if (phonecode && cleanDigits.length > maxDigits && cleanDigits.startsWith(phonecode)) {
                                        cleanDigits = cleanDigits.substring(phonecode.length);
                                        $('#wa_mobile_local').val(cleanDigits);
                                    }
                                }

                                // Clamp input to maximum digits for the country
                                if (cleanDigits.length > maxDigits) {
                                    cleanDigits = cleanDigits.substring(0, maxDigits);
                                    $('#wa_mobile_local').val(cleanDigits);
                                }

                                var fullMobile = '';
                                if (cleanDigits) {
                                    fullMobile = (phonecode ? ('+' + phonecode) : '') + cleanDigits;
                                }
                                $('#wa_mobile').val(fullMobile);
                                return fullMobile;
                            }

                            function verifyWaMobile() {
                                var countryVal = $('#wa_country').val();
                                var selectedOpt = $('#wa_country').find('option:selected');
                                var phonecode = selectedOpt.data('phonecode');
                                phonecode = phonecode ? String(phonecode).replace(/[^0-9]/g, '') : '';

                                var localMobile = ($('#wa_mobile_local').val() || '').trim();
                                var cleanDigits = localMobile.replace(/[^0-9]/g, '');

                                if (!countryVal || !phonecode) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Country Required',
                                        text: 'Please select a country first.',
                                        confirmButtonColor: '#00e676'
                                    });
                                    return;
                                }

                                if (!cleanDigits) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Mobile Required',
                                        text: 'Please enter a valid WhatsApp mobile number.',
                                        confirmButtonColor: '#00e676'
                                    });
                                    return;
                                }

                                // Country-specific length validation
                                var rule = countryLengthMap[phonecode];
                                if (rule) {
                                    if (cleanDigits.length < rule.min || cleanDigits.length > rule.max) {
                                        var msg = rule.min === rule.max ?
                                            ('For ' + countryVal + ', mobile number must be ' + rule.min + ' digits (you entered ' + cleanDigits
                                                .length + ' digits).') :
                                            ('For ' + countryVal + ', mobile number must be between ' + rule.min + ' and ' + rule.max +
                                                ' digits.');
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Invalid Mobile Number Length',
                                            text: msg,
                                            confirmButtonColor: '#00e676'
                                        });
                                        return;
                                    }
                                } else {
                                    if (cleanDigits.length < 5 || cleanDigits.length > 15) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Invalid Mobile Number',
                                            text: 'Please enter a valid mobile number (5 to 15 digits).',
                                            confirmButtonColor: '#00e676'
                                        });
                                        return;
                                    }
                                }

                                var mobile = syncWaFullNumber();

                                $('#btnVerifyWa').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Verifying...');

                                $.ajax({
                                    url: '{{ route('member.whatsapp.verify') }}',
                                    type: 'POST',
                                    data: {
                                        mobile_number: mobile,
                                        country: countryVal,
                                        phonecode: phonecode
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    success: function(res) {
                                        $('#btnVerifyWa').prop('disabled', false).html(
                                            '<i class="fas fa-shield-alt me-1"></i> Verify Mobile Number');

                                        if (res.success) {
                                            verifiedMobileNumber = res.clean_mobile || mobile;
                                            verifiedCountryName = countryVal;
                                            verifiedPhoneCode = phonecode;

                                            $('#waAlertMsg').removeClass('text-danger').addClass('text-success').text(res.message)
                                                .show();
                                            $('#btnVerifyWa').hide();
                                            $('#btnSendWa').show();
                                            $('#wa_country').css('pointer-events', 'none');
                                            $('#wa_mobile_local').prop('readonly', true);

                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Number Verified!',
                                                html: '<b>' + (res.message || 'Number verified successfully.') + '</b><br><br>Ready to send your WhatsApp referral and claim 500 PEPE Tokens?',
                                                showCancelButton: true,
                                                confirmButtonText: '<i class="fab fa-whatsapp me-1"></i> Send WhatsApp & Claim 500 PEPE',
                                                cancelButtonText: 'Cancel',
                                                confirmButtonColor: '#00e676'
                                            }).then(function(result) {
                                                if (result.isConfirmed) {
                                                    processWaReferral();
                                                }
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Verification Failed',
                                                text: res.message || 'Verification error.',
                                                confirmButtonColor: '#00e676'
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        $('#btnVerifyWa').prop('disabled', false).html(
                                            '<i class="fas fa-shield-alt me-1"></i> Verify Mobile Number');
                                        var errMsg = 'An error occurred during verification.';
                                        if (xhr.responseJSON && xhr.responseJSON.message) {
                                            errMsg = xhr.responseJSON.message;
                                        }
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Verification Error',
                                            text: errMsg,
                                            confirmButtonColor: '#00e676'
                                        });
                                    }
                                 });
                            }

                            function processWaReferral() {
                                var mobile = verifiedMobileNumber || $('#wa_mobile').val().trim() || syncWaFullNumber();
                                var countryVal = verifiedCountryName || $('#wa_country').val();
                                var phonecode = verifiedPhoneCode || $('#wa_country').find('option:selected').data('phonecode');
                                phonecode = phonecode ? String(phonecode).replace(/[^0-9]/g, '') : '';

                                if (!mobile) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Mobile Required',
                                        text: 'Please enter a valid mobile number.',
                                        confirmButtonColor: '#00e676'
                                    });
                                    return;
                                }

                                $('#btnSendWa').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');

                                $.ajax({
                                    url: '{{ route('member.whatsapp.process') }}',
                                    type: 'POST',
                                    data: {
                                        mobile_number: mobile,
                                        country: countryVal,
                                        phonecode: phonecode
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    },
                                    success: function(res) {
                                        if (res.success) {
                                            if (res.wa_total_pepe !== undefined) {
                                                $('#waTotalPepeVal').text(Number(res.wa_total_pepe).toLocaleString() + ' PEPE');
                                            }
                                            if (res.wa_total_referrals !== undefined) {
                                                $('#waTotalReferralsVal').text(res.wa_total_referrals);
                                            }
                                            window.waAvailablePepe = (window.waAvailablePepe || 0) + (res.reward_amount || 500);
                                            window.pepeWalletBalance = res.pepe_wallet !== undefined ? res.pepe_wallet : window
                                                .waAvailablePepe;
                                            $('#pepeWalletDisplay').text(Number(window.pepeWalletBalance).toLocaleString() +
                                                ' PEPE');

                                            var currentTodayCount = res.today_count !== undefined ? res.today_count : (({{ $waTodayCount ?? 0 }}) + 1);
                                            if (currentTodayCount >= 10) {
                                                $('#waTodayStatusBadge').removeClass('bg-warning text-dark').addClass(
                                                    'bg-success text-white').html(
                                                    '<i class="fas fa-check-circle me-1"></i> Completed (10/10)');
                                            } else {
                                                $('#waTodayStatusBadge').removeClass('bg-success text-white').addClass(
                                                    'bg-warning text-dark').html(
                                                    '<i class="fas fa-clock me-1"></i> Available (' + currentTodayCount + '/10)');
                                            }

                                            if (res.whatsapp_url) {
                                                window.open(res.whatsapp_url, '_blank');
                                            }

                                            Swal.fire({
                                                icon: 'success',
                                                title: '🎉 500 PEPE Tokens Earned!',
                                                html: '<b>' + (res.message || 'Referral saved successfully.') +
                                                    '</b><br><br>Your promotional referral has been saved and 500 PEPE tokens credited to your wallet.<br>(' + (res.today_count || currentTodayCount) + '/10 messages sent today)<br><br>Click below to open WhatsApp and send your referral link.',
                                                showCancelButton: true,
                                                confirmButtonText: '<i class="fab fa-whatsapp me-1"></i> Open WhatsApp Now',
                                                cancelButtonText: 'Done',
                                                confirmButtonColor: '#25D366'
                                            }).then(function(result) {
                                                if (result.isConfirmed && res.whatsapp_url) {
                                                    window.open(res.whatsapp_url, '_blank');
                                                }
                                                location.reload();
                                            });
                                        } else {
                                            $('#btnSendWa').prop('disabled', false).html(
                                                '<i class="fab fa-whatsapp me-1"></i> Send WhatsApp Referral & Claim 500 PEPE');
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Process Failed',
                                                text: res.message || 'Error processing referral.',
                                                confirmButtonColor: '#00e676'
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        $('#btnSendWa').prop('disabled', false).html(
                                            '<i class="fab fa-whatsapp me-1"></i> Send WhatsApp Referral & Claim 500 PEPE');
                                        var errMsg = 'An error occurred while processing referral.';
                                        if (xhr.responseJSON && xhr.responseJSON.message) {
                                            errMsg = xhr.responseJSON.message;
                                        }
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Submission Error',
                                            text: errMsg,
                                            confirmButtonColor: '#00e676'
                                        });
                                    }
                                });
                            }

                            window.pepeWalletBalance = {{ (float) ($waAvailablePepe ?? ($data['pepe_wallet'] ?? 0)) }};
                            window.waAvailablePepe = {{ (float) ($waAvailablePepe ?? ($data['pepe_wallet'] ?? 0)) }};
                            window.pepeDappSettings = {
                                contract_address: "{{ $pepeSettings->contract_address ?? '0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00' }}",
                                claim_contract_address: "{{ $pepeSettings->claim_contract_address ?? '' }}",
                                token_symbol: "{{ $pepeSettings->token_symbol ?? 'PEPE' }}",
                                token_name: "{{ $pepeSettings->token_name ?? 'PEPE BEP-20' }}",
                                token_decimals: {{ (int) ($pepeSettings->token_decimals ?? 18) }},
                                chain_id: {{ (int) ($pepeSettings->chain_id ?? 56) }},
                                network_name: "{{ $pepeSettings->network_name ?? 'BNB Smart Chain (BEP20)' }}",
                                rpc_url: "{{ $pepeSettings->rpc_url ?? 'https://bsc-dataseed.binance.org/' }}",
                                explorer_url: "{{ rtrim($pepeSettings->explorer_url ?? 'https://bscscan.com', '/') }}",
                                gas_limit: {{ (int) ($pepeSettings->gas_limit ?? 180000) }},
                                min_redeem: {{ (float) ($pepeSettings->min_redeem ?? 1) }},
                                is_active: {{ $pepeSettings->is_active ?? true ? 'true' : 'false' }},
                                token_abi: {!! json_encode(json_decode($pepeSettings->getEffectiveAbi())) !!},
                                claim_contract_abi: {!! json_encode(json_decode($pepeSettings->getEffectiveClaimAbi())) !!}
                            };

                            function getActiveWeb3Provider() {
                                if (typeof window.ethereum !== 'undefined') {
                                    if (window.ethereum.providers && window.ethereum.providers.length > 0) {
                                        return window.ethereum.selectedProvider || window.ethereum.providers[0];
                                    }
                                    return window.ethereum;
                                }
                                if (typeof window.trustwallet !== 'undefined') return window.trustwallet;
                                if (typeof window.okxwallet !== 'undefined') return window.okxwallet;
                                if (typeof window.BinanceChain !== 'undefined') return window.BinanceChain;
                                if (typeof window.bitkeep !== 'undefined' && window.bitkeep.ethereum) return window.bitkeep.ethereum;
                                if (typeof window.tokenpocket !== 'undefined' && window.tokenpocket.ethereum) return window.tokenpocket
                                    .ethereum;
                                return null;
                            }

                            function getWalletBrandName(provider) {
                                if (!provider) return 'Web3 Wallet';
                                if (provider.isTrust || provider.isTrustWallet) return 'Trust Wallet';
                                if (provider.isMetaMask && !provider.isBraveWallet && !provider.isTokenPocket) return 'MetaMask';
                                if (provider.isOkxWallet || provider.isOKExWallet) return 'OKX Wallet';
                                if (provider.isBinance || provider.isBinanceChain) return 'Binance Wallet';
                                if (provider.isCoinbaseWallet) return 'Coinbase Wallet';
                                if (provider.isTokenPocket) return 'TokenPocket';
                                if (provider.isBitKeep || provider.isBitget) return 'Bitget Wallet';
                                if (provider.isMathWallet) return 'Math Wallet';
                                return 'Web3 Wallet';
                            }

                            function openPepeRedeemPrompt() {
                                var settings = window.pepeDappSettings || {};
                                if (settings.is_active === false) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Maintenance in Progress',
                                        text: 'PEPE Token redemption is currently undergoing scheduled maintenance. Please check back shortly.',
                                        confirmButtonColor: '#00e676'
                                    });
                                    return;
                                }

                                var availableBalance = typeof window.pepeWalletBalance !== 'undefined' ?
                                    window.pepeWalletBalance :
                                    (typeof window.waAvailablePepe !== 'undefined' ? window.waAvailablePepe :
                                        {{ (float) ($data['pepe_wallet'] ?? 0) }});
                                var walletAddress = "{{ $data['member_wallet'] ?? ($data['wallet_address'] ?? session('address')) }}";
                                var minRedeem = settings.min_redeem || 1;

                                if (availableBalance <= 0) {
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'No PEPE Tokens to Redeem',
                                        text: 'You have 0 PEPE tokens available to redeem. Promote your referral link via WhatsApp daily to earn 1,000 PEPE tokens!',
                                        confirmButtonColor: '#00e676'
                                    });
                                    return;
                                }

                                Swal.fire({
                                    title: '<span style="color: #00e676; font-weight: 700;"><i class="fas fa-coins me-1"></i> PEPE Token Redemption</span>',
                                    html: `
                                        <form id="pepeManualForm" method="POST" action="{{ route('initPepeWithdrawalForm') }}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="memberid" value="{{ $data['memberid'] }}">
                                            <div class="text-start p-2" style="font-size: 13px;">
                                            <div class="mb-3 p-3 rounded bg-dark text-white border border-secondary">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="text-white-50" style="font-size: 12px;">Available to Redeem:</span>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 10px;">BEP-20 (BSC)</span>
                                                </div>
                                                <strong class="text-success" style="font-size: 20px;">` + Number(
                                            availableBalance).toLocaleString() + ` ` + (settings.token_symbol || 'PEPE') +
                                        `</strong>
                                            </div>
                                            <div class="mb-3">
                                                <label class="font-weight-bold text-white mb-1">Target BEP-20 Wallet Address:</label>
                                                <input type="text" id="swal_pepe_wallet" class="form-control" value="` +
                                        (walletAddress || '') +
                                        `" placeholder="0x..." readonly style="font-family: monospace; font-size: 12px; background: rgba(255, 255, 255, 0.08); color: #00e676; cursor: not-allowed; border: 1px solid rgba(0, 230, 118, 0.4);">
                                                <small class="text-white-50"><i class="fas fa-lock me-1"></i> Tokens will be redeemed directly to this address via your connected Web3 wallet on BNB Smart Chain.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="font-weight-bold text-white mb-1">Tokens to Redeem:</label>
                                                <input type="number" name="amount" id="withAmount" class="form-control font-weight-bold text-success" value="` +
                                        availableBalance + `" max="` + availableBalance + `" min="` + minRedeem + `" required>
                                                <small class="text-muted">Enter quantity to redeem (min: ` +
                                        minRedeem + ` ` + (settings.token_symbol || 'PEPE') +
                                        `)</small>
                                            </div>

                                            <input type="hidden" name="route" id="route"
                                                value="{{ Route::has('initiatePepeWithdrawal') ? route('initiatePepeWithdrawal') : url('member/initiatePepeWithdrawal') }}">
                                            <input type="hidden" name="balValidate" id="balValidate"
                                                value="{{ Route::has('pepeValidate') ? route('pepeValidate') : url('member/pepeValidate') }}">
                                            <input type="hidden" name="getPvtcd" id="getPvtcd" value="{{ Route::has('getPrivateKeyPepe') ? route('getPrivateKeyPepe') : url('member/getPrivateKeyPepe') }}">
                                            <input type="hidden" name="netamount" id="netamount" value="{{ $data['pepe_wallet'] }}">
                                            <input type="hidden" class="form-control" value="{{ csrf_token() }}" id="csrf">
                                            <input type="hidden" name="memberid" id="memberid" value="{{ $data['memberid'] }}">
                                            <input type="hidden" name="memberWallet" value="{{ $data['member_wallet'] }}"
                                                id="memberWallet">
                                            <input type="hidden" name="backUrl" value="{{ url('/') }}"
                                                id="backUrl">

                                            <div class="p-2 rounded bg-dark border border-secondary text-start" style="font-size: 12px;">
                                                <span class="text-info"><i class="fas fa-network-wired me-1"></i> <strong>Network:</strong> ` +
                                        (settings.network_name ||
                                            'BNB Smart Chain (BEP20)') +
                                        `</span><br>
                                                <span class="text-white-50"><i class="fas fa-file-contract me-1"></i> Token Contract: <code class="text-success small">` +
                                        (settings.contract_address ? (settings.contract_address.substring(0, 8) + '...' + settings
                                            .contract_address.substring(settings.contract_address.length - 6)) : '') + `</code></span>
                                            </div>
                                            </div>
                                        </form>
                                    `,
                                    showCancelButton: true,
                                    confirmButtonText: '<i class="fas fa-bolt me-1"></i> Withdraw Now',
                                    cancelButtonText: 'Cancel',
                                    confirmButtonColor: '#00e676',
                                    cancelButtonColor: '#d33',
                                    customClass: {
                                        confirmButton: 'withdraw_btn'
                                    },
                                    showLoaderOnConfirm: true,
                                    preConfirm: () => {
                                        const form = document.getElementById('pepeManualForm');
                                        const formData = new FormData(form);
                                        return fetch(form.action, {
                                            method: 'POST',
                                            headers: {
                                                'X-Requested-With': 'XMLHttpRequest',
                                                'Accept': 'application/json'
                                            },
                                            body: formData
                                        })
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error(response.statusText)
                                            }
                                            return response.json()
                                        })
                                        .then(data => {
                                            if (!data.success) {
                                                throw new Error(data.message)
                                            }
                                            return data;
                                        })
                                        .catch(error => {
                                            Swal.showValidationMessage(error.message)
                                        })
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed && result.value) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Withdrawal Requested',
                                            text: result.value.message,
                                            confirmButtonColor: '#00e676'
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    }
                                });
                            }

                            // async function submitPepeRedeem(amount, walletAddress) {
                            //     var config = window.pepeDappSettings || {};

                            //     // 1. Check Web3 provider (supports MetaMask, Trust Wallet, OKX, Binance, mobile wallets)
                            //     const activeProvider = getActiveWeb3Provider();
                            //     if (!activeProvider) {
                            //         Swal.fire({
                            //             icon: 'warning',
                            //             title: 'Web3 Wallet Required',
                            //             html: '<p class="text-white">To claim real PEPE tokens to your BEP-20 address, please open this DApp in your <b>Web3 Wallet</b> (Trust Wallet, MetaMask, Binance Web3, OKX, etc.) or install a wallet browser extension.</p>',
                            //             confirmButtonColor: '#00e676'
                            //         });
                            //         return;
                            //     }

                            //     const walletName = getWalletBrandName(activeProvider);

                            //     try {
                            //         // 2. Request Wallet connection
                            //         Swal.fire({
                            //             title: 'Connecting to ' + walletName + '...',
                            //             html: '<div class="p-2 text-center"><div class="spinner-border text-success mb-2" role="status"></div><p class="text-white mb-0">Requesting Web3 wallet connection in ' +
                            //                 walletName + '...</p></div>',
                            //             allowOutsideClick: false,
                            //             showConfirmButton: false,
                            //             didOpen: () => {
                            //                 Swal.showLoading();
                            //             }
                            //         });

                            //         const accounts = await activeProvider.request({
                            //             method: 'eth_requestAccounts'
                            //         });
                            //         if (!accounts || accounts.length === 0) {
                            //             throw new Error('Please connect your ' + walletName + ' account to continue.');
                            //         }
                            //         const activeWallet = accounts[0];

                            //         // 3. Ensure BNB Smart Chain (Chain ID 56)
                            //         const targetChainId = config.chain_id || 56;
                            //         const targetChainIdHex = '0x' + targetChainId.toString(16);
                            //         const currentChainId = await activeProvider.request({
                            //             method: 'eth_chainId'
                            //         });

                            //         if (currentChainId !== targetChainIdHex) {
                            //             Swal.fire({
                            //                 title: 'Switching Network...',
                            //                 html: '<div class="p-2 text-center"><div class="spinner-border text-success mb-2" role="status"></div><p class="text-white mb-0">Switching network to <b>' +
                            //                     (config.network_name || 'BNB Smart Chain') + '</b> in ' + walletName +
                            //                     '...</p></div>',
                            //                 allowOutsideClick: false,
                            //                 showConfirmButton: false,
                            //                 didOpen: () => {
                            //                     Swal.showLoading();
                            //                 }
                            //             });

                            //             try {
                            //                 await activeProvider.request({
                            //                     method: 'wallet_switchEthereumChain',
                            //                     params: [{
                            //                         chainId: targetChainIdHex
                            //                     }]
                            //                 });
                            //             } catch (switchError) {
                            //                 if (switchError.code === 4902) {
                            //                     await activeProvider.request({
                            //                         method: 'wallet_addEthereumChain',
                            //                         params: [{
                            //                             chainId: targetChainIdHex,
                            //                             chainName: config.network_name || 'BNB Smart Chain',
                            //                             nativeCurrency: {
                            //                                 name: 'BNB',
                            //                                 symbol: 'BNB',
                            //                                 decimals: 18
                            //                             },
                            //                             rpcUrls: [config.rpc_url || 'https://bsc-dataseed.binance.org/'],
                            //                             blockExplorerUrls: [config.explorer_url || 'https://bscscan.com']
                            //                         }]
                            //                     });
                            //                 } else {
                            //                     throw switchError;
                            //                 }
                            //             }
                            //         }

                            //         // 4. Request cryptographically signed claim voucher from backend
                            //         Swal.fire({
                            //             title: 'Preparing Claim Authorization...',
                            //             html: '<div class="p-2 text-center"><div class="spinner-border text-success mb-2" role="status"></div><p class="text-white mb-0">Authorizing claim voucher with cryptographic signature...</p></div>',
                            //             allowOutsideClick: false,
                            //             showConfirmButton: false,
                            //             didOpen: () => {
                            //                 Swal.showLoading();
                            //             }
                            //         });

                            //         const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector(
                            //             'meta[name="csrf-token"]').content : '{{ csrf_token() }}';
                            //         const voucherRes = await fetch('{{ route('member.pepe.prepareClaim') }}', {
                            //             method: 'POST',
                            //             headers: {
                            //                 'Content-Type': 'application/json',
                            //                 'Accept': 'application/json',
                            //                 'X-CSRF-TOKEN': csrfToken
                            //             },
                            //             body: JSON.stringify({
                            //                 amount: amount,
                            //                 wallet_address: activeWallet
                            //             })
                            //         });

                            //         const voucherData = await voucherRes.json().catch(() => ({
                            //             success: false,
                            //             message: 'Server returned error ' + voucherRes.status
                            //         }));
                            //         if (!voucherRes.ok || !voucherData.success) {
                            //             throw new Error(voucherData.message || 'Failed to prepare claim voucher.');
                            //         }

                            //         const v = voucherData.voucher;
                            //         const isContractClaim = voucherData.mode === 'contract_claim' && v.contract_address;
                            //         const provider = new ethers.providers.Web3Provider(activeProvider);
                            //         const signer = provider.getSigner();

                            //         let onChainTxHash = '';
                            //         let explorerTxUrl = '';

                            //         if (isContractClaim) {
                            //             // 5A. Smart Contract Claim execution
                            //             Swal.fire({
                            //                 title: '<span style="color: #00e676;"><i class="fas fa-wallet me-2"></i>Confirm in Wallet</span>',
                            //                 html: `
                            //                     <div class="text-start p-2" style="font-size: 13px;">
                            //                         <p class="text-white mb-2">Please approve the claim transaction in your <b>` +
                            //                     walletName +
                            //                     `</b> to receive your PEPE tokens directly from the Smart Contract.</p>
                            //                         <div class="p-2 rounded bg-dark border border-secondary mb-2" style="font-size: 12px;">
                            //                             <span class="text-white-50">Claiming:</span> <strong class="text-success">` +
                            //                     Number(v.amount).toLocaleString() + ` ` + (v.token_symbol || 'PEPE') +
                            //                     `</strong><br>
                            //                             <span class="text-white-50">Recipient:</span> <code class="text-white small">` +
                            //                     activeWallet.substring(0, 8) + '...' + activeWallet
                            //                     .substring(activeWallet.length - 6) +
                            //                     `</code><br>
                            //                             <span class="text-white-50">Distributor:</span> <code class="text-success small">` +
                            //                     v
                            //                     .contract_address.substring(0, 8) + '...' + v.contract_address.substring(v
                            //                         .contract_address.length - 6) + `</code>
                            //                         </div>
                            //                         <small class="text-muted"><i class="fas fa-info-circle me-1"></i>A small BNB Smart Chain network gas fee will be paid via your wallet.</small>
                            //                     </div>
                            //                 `,
                            //                 allowOutsideClick: false,
                            //                 showConfirmButton: false,
                            //                 didOpen: () => {
                            //                     Swal.showLoading();
                            //                 }
                            //             });

                            //             const claimContractAbi = voucherData.claim_contract_abi || config.claim_contract_abi;
                            //             const contract = new ethers.Contract(v.contract_address, claimContractAbi, signer);

                            //             const tx = await contract.claim(
                            //                 v.amount_raw,
                            //                 v.nonce,
                            //                 v.deadline,
                            //                 v.signature, {
                            //                     gasLimit: v.gas_limit || 180000
                            //                 }
                            //             );

                            //             explorerTxUrl = (config.explorer_url || 'https://bscscan.com').replace(/\/+$/, '') + '/tx/' + tx
                            //                 .hash;
                            //             Swal.fire({
                            //                 title: 'Claiming on BNB Smart Chain...',
                            //                 html: `
                            //                     <div class="p-2 text-center">
                            //                         <div class="spinner-border text-success mb-3" role="status"></div>
                            //                         <p class="text-white mb-2"><b>Transaction Submitted to Blockchain!</b></p>
                            //                         <p class="text-white-50 small mb-2">Waiting for on-chain block confirmation...</p>
                            //                         <code class="text-success small d-block text-truncate mb-3" style="font-size: 11px;">` +
                            //                     tx.hash + `</code>
                            //                         <a href="` + explorerTxUrl + `" target="_blank" class="btn btn-sm btn-outline-info font-weight-bold">
                            //                             <i class="fas fa-external-link-alt me-1"></i> Track on BscScan
                            //                         </a>
                            //                     </div>
                            //                 `,
                            //                 allowOutsideClick: false,
                            //                 showConfirmButton: false,
                            //                 didOpen: () => {
                            //                     Swal.showLoading();
                            //                 }
                            //             });

                            //             await tx.wait(1);
                            //             onChainTxHash = tx.hash;
                            //         } else {
                            //             // 5B. Web3 Wallet Cryptographic Confirmation (Universal across Trust Wallet, MetaMask, OKX, etc. with 0 gas fee)
                            //             Swal.fire({
                            //                 title: '<span style="color: #00e676;"><i class="fas fa-wallet me-2"></i>Confirm in Wallet</span>',
                            //                 html: `
                            //                     <div class="text-start p-2" style="font-size: 13px;">
                            //                         <p class="text-white mb-2">Please approve the redemption authorization in your <b>` +
                            //                     walletName +
                            //                     `</b>.</p>
                            //                         <div class="p-2 rounded bg-dark border border-secondary mb-2" style="font-size: 12px;">
                            //                             <span class="text-white-50">Redeeming:</span> <strong class="text-success">` +
                            //                     Number(v.amount).toLocaleString() + ` ` + (v.token_symbol || 'PEPE') +
                            //                     `</strong><br>
                            //                             <span class="text-white-50">Recipient Wallet:</span> <code class="text-white small">` +
                            //                     activeWallet.substring(0, 8) + '...' +
                            //                     activeWallet.substring(activeWallet.length - 6) +
                            //                     `</code>
                            //                         </div>
                            //                         <small class="text-success"><i class="fas fa-shield-alt me-1"></i>Cryptographic wallet verification via ` +
                            //                     walletName + `.</small>
                            //                     </div>
                            //                 `,
                            //                 allowOutsideClick: false,
                            //                 showConfirmButton: false,
                            //                 didOpen: () => {
                            //                     Swal.showLoading();
                            //                 }
                            //             });

                            //             const signMsg = v.sign_message || ("Authorize PEPE Token Redemption\nAmount: " + v.amount +
                            //                 " PEPE\nRecipient: " + activeWallet + "\nNonce: " + v.nonce);
                            //             const userSignature = await signer.signMessage(signMsg);

                            //             onChainTxHash = ethers.utils.keccak256(ethers.utils.toUtf8Bytes(userSignature + ':' + v.nonce));
                            //             explorerTxUrl = (config.explorer_url || 'https://bscscan.com').replace(/\/+$/, '') + '/address/' +
                            //                 activeWallet;
                            //         }

                            //         // 6. Record confirmed transaction in backend database
                            //         const recordRes = await fetch('{{ route('member.pepe.redeem') }}', {
                            //             method: 'POST',
                            //             headers: {
                            //                 'Content-Type': 'application/json',
                            //                 'Accept': 'application/json',
                            //                 'X-CSRF-TOKEN': csrfToken
                            //             },
                            //             body: JSON.stringify({
                            //                 amount: amount,
                            //                 wallet_address: activeWallet,
                            //                 txnid: onChainTxHash
                            //             })
                            //         });

                            //         const recordData = await recordRes.json().catch(() => ({
                            //             success: false,
                            //             message: 'Server returned error ' + recordRes.status
                            //         }));
                            //         if (!recordRes.ok || !recordData.success) {
                            //             throw new Error(recordData.message || 'Failed to record transaction.');
                            //         }

                            //         if (recordData.success) {
                            //             if (recordData.new_balance !== undefined) {
                            //                 $('#pepeWalletDisplay').text(Number(recordData.new_balance).toLocaleString() + ' ' + (config
                            //                     .token_symbol || 'PEPE'));
                            //                 window.pepeWalletBalance = recordData.new_balance;
                            //             }
                            //             if (recordData.available_pepe !== undefined) {
                            //                 window.waAvailablePepe = recordData.available_pepe;
                            //             }

                            //             var explorerLink = recordData.explorer_link || explorerTxUrl;
                            //             var txHtml =
                            //                 `
                            //                 <div class="mt-3 p-3 rounded bg-dark border border-secondary text-start">
                            //                     <span class="text-white-50 d-block small mb-1">BNB Smart Chain Reference Hash:</span>
                            //                     <code class="text-success small d-block text-truncate mb-2" style="font-size: 11px;">` +
                            //                 onChainTxHash + `</code>
                            //                     <a href="` + explorerLink + `" target="_blank" class="btn btn-sm btn-success font-weight-bold" style="color: #000;">
                            //                         <i class="fas fa-check-circle me-1"></i> View on BscScan
                            //                     </a>
                            //                 </div>
                            //             `;

                            //             Swal.fire({
                            //                 icon: 'success',
                            //                 title: '🎉 PEPE Tokens Redeemed!',
                            //                 html: '<p class="text-white mb-2"><b>' + recordData.message + '</b></p>' + txHtml,
                            //                 confirmButtonColor: '#00e676',
                            //                 confirmButtonText: 'Awesome!'
                            //             }).then(() => {
                            //                 window.location.reload();
                            //             });
                            //         } else {
                            //             Swal.fire({
                            //                 icon: 'info',
                            //                 title: 'Tokens Redeemed',
                            //                 html: '<p class="text-white">Redemption processed for your wallet on BSC! Hash: <code>' +
                            //                     onChainTxHash + '</code></p>',
                            //                 confirmButtonColor: '#00e676'
                            //             }).then(() => {
                            //                 window.location.reload();
                            //             });
                            //         }
                            //     } catch (err) {
                            //         console.error('PEPE Claim Error:', err);
                            //         var errorInfo = parseFriendlyWeb3Error(err);

                            //         Swal.fire({
                            //             icon: errorInfo.icon || 'warning',
                            //             title: '<span style="font-weight: 700;">' + errorInfo.title + '</span>',
                            //             html: `
                            //                 <div class="p-2 text-center" style="font-size: 13px;">
                            //                     <p class="text-white mb-2 font-weight-bold">` + errorInfo.message + `</p>
                            //                     <small class="text-white-50 d-block">` + errorInfo.detail + `</small>
                            //                 </div>
                            //             `,
                            //             confirmButtonColor: '#00e676',
                            //             confirmButtonText: 'OK'
                            //         });
                            //     }
                            // }

                            // function parseFriendlyWeb3Error(err) {
                            //     if (!err) {
                            //         return {
                            //             icon: 'error',
                            //             title: 'Redeem Failed',
                            //             message: 'An unexpected error occurred.',
                            //             detail: 'Please check your connection and try again.'
                            //         };
                            //     }

                            //     var msg = (err.message || '').toLowerCase();
                            //     var code = err.code;

                            //     // 1. User rejection / cancellation in Wallet
                            //     if (code === 4001 || code === 'ACTION_REJECTED' || msg.includes('user rejected') || msg.includes(
                            //             'user denied') || msg.includes('rejected transaction') || msg.includes('cancelled')) {
                            //         return {
                            //             icon: 'info',
                            //             title: 'Transaction Cancelled',
                            //             message: 'You cancelled the request in your wallet.',
                            //             detail: 'No PEPE tokens were deducted and no fee was charged.'
                            //         };
                            //     }

                            //     // 2. Insufficient BNB for gas fee
                            //     if (msg.includes('insufficient funds') || (msg.includes('gas') && msg.includes('exceeds'))) {
                            //         return {
                            //             icon: 'warning',
                            //             title: 'Insufficient Gas Fee',
                            //             message: 'Your wallet does not have enough BNB for gas.',
                            //             detail: 'Please deposit a small amount of BNB (for gas) into your wallet and try again.'
                            //         };
                            //     }

                            //     // 3. Smart contract claim address not configured
                            //     if (msg.includes('smart contract claim address is not configured')) {
                            //         return {
                            //             icon: 'warning',
                            //             title: 'Claim Setup Required',
                            //             message: 'Claim distributor contract is not configured yet.',
                            //             detail: 'Please configure the claim contract address in settings.'
                            //         };
                            //     }

                            //     // 4. Contract execution reverted
                            //     if (msg.includes('execution reverted') || msg.includes('revert') || msg.includes('call revert exception')) {
                            //         if (msg.includes('insufficient pepe tokens') || msg.includes('insufficient contract token balance')) {
                            //             return {
                            //                 icon: 'warning',
                            //                 title: 'Distributor Balance Low',
                            //                 message: 'Contract has insufficient PEPE tokens.',
                            //                 detail: 'Tokens will be replenished shortly. Please contact support.'
                            //             };
                            //         }
                            //         if (msg.includes('voucher has already been claimed') || msg.includes('already been claimed')) {
                            //             return {
                            //                 icon: 'info',
                            //                 title: 'Already Claimed',
                            //                 message: 'This voucher has already been claimed on blockchain.',
                            //                 detail: 'Please refresh your page to see your updated balance.'
                            //             };
                            //         }
                            //         if (msg.includes('voucher signature has expired') || msg.includes('expired')) {
                            //             return {
                            //                 icon: 'info',
                            //                 title: 'Voucher Expired',
                            //                 message: 'Authorization voucher has expired.',
                            //                 detail: 'Please click Redeem again to get a fresh voucher.'
                            //             };
                            //         }
                            //         return {
                            //             icon: 'error',
                            //             title: 'Claim Transaction Reverted',
                            //             message: 'Blockchain contract could not process this claim.',
                            //             detail: 'Please ensure contract address is correct and funded.'
                            //         };
                            //     }

                            //     // 5. Network / Connection errors
                            //     if (msg.includes('network') || msg.includes('timeout') || msg.includes('failed to fetch')) {
                            //         return {
                            //             icon: 'error',
                            //             title: 'Network Error',
                            //             message: 'Could not connect to BNB Smart Chain RPC.',
                            //             detail: 'Please check your internet connection or try again in a few moments.'
                            //         };
                            //     }

                            //     // Default fallback with cleaned text (no raw json/hex)
                            //     var reason = err.reason || (err.message && err.message.length < 120 && !err.message.includes('{') && !err
                            //         .message.includes('0x') ? err.message : '') || 'Transaction could not be completed.';
                            //     if (reason.length > 100 || reason.includes('{') || reason.includes('0x')) {
                            //         reason = 'Transaction could not be completed.';
                            //     }

                            //     return {
                            //         icon: 'error',
                            //         title: 'Redeem Failed',
                            //         message: reason,
                            //         detail: 'Please try again or contact support if the issue persists.'
                            //     };
                            // }

                            // $(document).ready(function() {
                            //     onWaCountryChange();
                            // });
                        </script>
                    </div>

                    @if (session()->has('successMsg'))
                        <div class="alert alert-success" role="alert">
                            {{ session('successMsg') }}
                        </div>
                    @endif
                    @if (session()->has('failedMsg'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('failedMsg') }}
                        </div>
                    @endif
                    <div class="row">
                        <!--<div class="col-12 col-xl-12 col-sm-12">-->
                        <!--    <div class="alert alert-danger mt-3" role="alert"-->
                        <!--        style="overflow: auto; font-size:15px">-->
                        <!--        <a-->
                        <!--            id="code">https://fltcoin.com/member/register/{{ $data['member_wallet'] }}</a>-->
                        <!--        <span class="float-end" id="copyButtonId"><svg xmlns="http://www.w3.org/2000/svg"-->
                        <!--                height="0.875em" viewBox="0 0 512 512">-->
                        <!--                <path-->
                        <!--                    d="M448 384H256c-35.3 0-64-28.7-64-64V64c0-35.3 28.7-64 64-64H396.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V320c0 35.3-28.7 64-64 64zM64 128h96v48H64c-8.8 0-16 7.2-16 16V448c0 8.8 7.2 16 16 16H256c8.8 0 16-7.2 16-16V416h48v32c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V192c0-35.3 28.7-64 64-64z" />-->
                        <!--            </svg>-->
                        <!--            <span id="msgText"> Copy</span></span>-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="col-xl-6 col-lg-12 col-sm-12">
                            <div class="card overflow-hidden member-highlight-card"
                                style="background-color: #0c0e14 !important; border: 1px solid rgba(245, 158, 11, 0.25);">
                                <div class="text-center p-5 overlay-box"
                                    style="background-color: #0c0e14; background-image: linear-gradient(rgba(12, 14, 20, 0.85), rgba(12, 14, 20, 0.85)), url('{{ asset('logo/bg.jpeg') }}'); background-size: cover; background-position: center;">
                                    <img src="{{ asset('logo/name-logo.png') }}" width="400" class="img-fluid"
                                        alt="">
                                    <h3 class="mt-2 mb-2 text-white">Congratulations
                                    </h3>
                                    <h5 class="mb-0 text-white">{{ getName($data['memberid']) }}</h5>
                                </div>
                                <div class="card-body" style="background-color: #0c0e14;">
                                    <div class="row g-3 text-center">
                                        <div class="col-6">
                                            <div class="rounded p-3 text-white h-100 d-flex flex-column justify-content-center align-items-center shadow-sm"
                                                style="background: linear-gradient(135deg, rgba(245,158,11,0.20), rgba(12,14,20,0.95)); border: 1px solid rgba(245,158,11,0.25); min-height: 150px;">
                                                <div class="d-flex align-items-center justify-content-center rounded-circle mb-3"
                                                    style="width: 54px; height: 54px; background: rgba(245,158,11,0.15); color: #fbbf24; font-size: 1.3rem;">
                                                    <i class="fa-solid fa-wallet"></i>
                                                </div>
                                                <small class="text-uppercase text-white-50 mb-2"
                                                    style="letter-spacing:0.08em;">Main Wallet</small>
                                                <h3 class="mb-0 text-white">$
                                                    {{ number_format((float) $data['wallet'], 2) }}
                                                </h3>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="rounded p-3 text-white h-100 d-flex flex-column justify-content-center align-items-center shadow-sm"
                                                style="background: linear-gradient(135deg, rgba(16,185,129,0.20), rgba(12,14,20,0.95)); border: 1px solid rgba(16,185,129,0.25); min-height: 150px;">
                                                <div class="d-flex align-items-center justify-content-center rounded-circle mb-3"
                                                    style="width: 54px; height: 54px; background: rgba(16,185,129,0.15); color: #10b981; font-size: 1.3rem;">
                                                    <i class="fa-solid fa-landmark"></i>
                                                </div>
                                                <small class="text-uppercase text-white-50 mb-2"
                                                    style="letter-spacing:0.08em;">Fund Wallet</small>
                                                <h3 class="mb-0 text-white">$
                                                    {{ number_format((float) $data['p2p_wallet'], 2) }}
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6">
                            <div class="card border-0 pb-0">
                                <div class="card-header border-0 pb-0">
                                    <h4 class="card-title">User Details</h4>
                                </div>
                                <div class="card-body p-0 ">
                                    <div id="DZ_W_Todo4" class="widget-media dlab-scroll  px-4 my-4 height370">
                                        <ul class="timeline">

                                            <li>
                                                <div class="timeline-panel">
                                                    <div class="media-body">
                                                        <h5 class="mb-0">Member ID</h5>
                                                        <small class="text-muted"></small>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-success light sharp"
                                                            data-bs-toggle="dropdown">
                                                            {{ $data['memberid'] }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="timeline-panel">
                                                    <div class="media-body">
                                                        <h5 class="mb-0">Downline</h5>
                                                        <small class="text-muted"></small>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-primary light sharp"
                                                            data-bs-toggle="dropdown">
                                                            {{ $data['downline'] }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="timeline-panel">
                                                    <div class="media-body">
                                                        <h5 class="mb-0">SponsorId</h5>
                                                        <small class="text-muted"></small>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-warning light sharp"
                                                            data-bs-toggle="dropdown">
                                                            {{ $data['sponsorid'] }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="timeline-panel">
                                                    <div class="media-body">
                                                        <h5 class="mb-0">Status</h5>
                                                        <small class="text-muted"></small>
                                                    </div>
                                                    <div class="dropdown">

                                                        @if (strtolower(trim($data['status'] ?? '')) === 'active')
                                                            <button type="button" class="btn btn-primary light sharp"
                                                                data-bs-toggle="dropdown">
                                                                Active
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-danger light sharp"
                                                                data-bs-toggle="dropdown">
                                                                {{ $data['status'] == 'Temp' ? 'Inactive' : 'Blocked' }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="timeline-panel">
                                                    <div class="media-body">
                                                        <h5 class="mb-0">Joining Date</h5>
                                                        <small class="text-muted"></small>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-info light sharp"
                                                            data-bs-toggle="dropdown">
                                                            {{ date('d-m-Y', strtotime($data['created_at'])) }}
                                                        </button>

                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="timeline-panel">
                                                    <div class="media-body">
                                                        <h5 class="mb-0">Daily Team Biz</h5>
                                                    </div>
                                                    <div class="dropdown">
                                                        <small>
                                                            $
                                                            {{ $data['daily_team_biz'] + $data['part_daily_team_biz'] }}</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <!--<li>-->
                                            <!--    <div class="timeline-panel">-->
                                            <!--        <div class="media-body">-->
                                            <!--            <h5 class="mb-0">Daily Self Biz</h5>-->
                                            <!--        </div>-->
                                            <!--        <div class="dropdown">-->
                                            <!--            <small>-->
                                            <!--                $ {{ $data['daily_team_biz'] - $data['part_daily_team_biz'] }}</small>-->
                                            <!--        </div>-->
                                            <!--    </div>-->
                                            <!--</li>-->
                                            <li>
                                                <div class="timeline-panel">
                                                    <div class="media-body">
                                                        <h5 class="mb-0">Member Wallet</h5>
                                                    </div>
                                                    <div class="dropdown">
                                                        <small>
                                                            {{ $data['member_wallet'] }}</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="timeline-panel">
                                                    <div class="media-body">
                                                        <h5 class="mb-0">Country</h5>
                                                        <small class="text-muted"></small>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn btn-success light sharp"
                                                            data-bs-toggle="dropdown">
                                                            {{ $data['country'] }}
                                                        </button>

                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- PACKAGE & INVESTMENT OVERVIEW (PHASE 2) -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card admin-pool-wrapper" style="border: 1px solid rgba(245, 158, 11, 0.4); border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, rgba(16, 20, 30, 0.98), rgba(10, 12, 18, 0.95)); box-shadow: 0 18px 40px rgba(0, 0, 0, 0.65);">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="background: linear-gradient(90deg, rgba(245, 158, 11, 0.18), rgba(255, 215, 0, 0.05)); border-bottom: 1px solid rgba(245, 158, 11, 0.25); padding: 1.2rem 1.5rem;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: rgba(245, 158, 11, 0.2); color: #FFD700; border: 1px solid rgba(245, 158, 11, 0.4); font-size: 1.25rem;">
                                            <i class="fa-solid fa-gem"></i>
                                        </div>
                                        <div>
                                            <h4 class="card-title mb-0 text-white font-weight-bold" style="font-size: 1.25rem; letter-spacing: 0.3px;">
                                                Active Package &amp; Investment Details
                                            </h4>
                                            <small class="text-white-50" style="font-size: 0.8rem;">
                                                Tiered package allocation &bull; 70% Trading Wallet credit
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                                        @if ($activePackage && $activePackage->isPackageActive())
                                            <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.4); padding: 7px 14px; font-size: 12px; font-weight: 700; border-radius: 8px;">
                                                <i class="fa-solid fa-circle-check me-1"></i> Package Active
                                            </span>
                                        @elseif ($activePackage && $activePackage->isExpired())
                                            <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.4); padding: 7px 14px; font-size: 12px; font-weight: 700; border-radius: 8px;">
                                                <i class="fa-solid fa-clock-rotate-left me-1"></i> Package Expired
                                            </span>
                                        @else
                                            <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.4); padding: 7px 14px; font-size: 12px; font-weight: 700; border-radius: 8px;">
                                                <i class="fa-solid fa-circle-info me-1"></i> No Active Package
                                            </span>
                                        @endif
                                        <a href="{{ url('/member/fund/import-flt') }}" class="btn btn-sm font-weight-bold" style="background: linear-gradient(135deg, #FFD700, #F59E0B); color: #000; border: none; border-radius: 8px; padding: 7px 14px; font-size: 12px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);">
                                            <i class="fa-solid fa-plus-circle me-1"></i> New Investment
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row g-3">
                                        <!-- Current Package Range & Investment -->
                                        <div class="col-xl-3 col-md-6 col-sm-12">
                                            <div class="p-3 rounded h-100" style="background: linear-gradient(135deg, rgba(255, 215, 0, 0.12), rgba(12, 14, 20, 0.95)); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 14px;">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="text-white-50 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 600;">Current Package</span>
                                                    <span class="badge bg-warning text-dark font-weight-bold" style="font-size: 10px;">
                                                        {{ $activePackage->package ?? ($data['package'] ?? 'Standard') }}
                                                    </span>
                                                </div>
                                                <h3 class="text-white font-weight-bold mb-1" style="font-size: 1.5rem;">
                                                    $ {{ number_format((float) ($activePackage->invest_amount ?? ($activePackage->package_value ?? $totalInvestment)), 2) }}
                                                </h3>
                                                <div class="d-flex align-items-center gap-1 text-warning mt-2" style="font-size: 0.78rem;">
                                                    <i class="fa-solid fa-layer-group"></i>
                                                    <span>Range: {{ $activePackage->package_range ?? ($data['package'] ?? '50 - 500 USDT') }}</span>
                                                </div>
                                                <small class="text-white-50 d-block mt-1" style="font-size: 0.72rem;">Total Invested: $ {{ number_format((float) $totalInvestment, 2) }}</small>
                                            </div>
                                        </div>

                                        <!-- Trading Wallet (70% Allocation) -->
                                        <div class="col-xl-3 col-md-6 col-sm-12">
                                            <div class="p-3 rounded h-100" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.12), rgba(12, 14, 20, 0.95)); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 14px;">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="text-white-50 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 600;">Trading Wallet</span>
                                                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10B981; font-size: 10px; font-weight: 700;">
                                                        70% Allocated
                                                    </span>
                                                </div>
                                                <h3 class="text-white font-weight-bold mb-1" style="font-size: 1.5rem; color: #10B981 !important;">
                                                    $ {{ number_format((float) ($data['p2p_wallet'] ?? 0), 2) }}
                                                </h3>
                                                <div class="d-flex align-items-center gap-1 text-success mt-2" style="font-size: 0.78rem;">
                                                    <i class="fa-solid fa-coins"></i>
                                                    <span>Package Credit: $ {{ number_format((float) ($activePackage->trading_wallet_amount ?? (($activePackage->invest_amount ?? 0) * 0.70)), 2) }}</span>
                                                </div>
                                                <small class="text-white-50 d-block mt-1" style="font-size: 0.72rem;">Available for trading &amp; withdrawal</small>
                                            </div>
                                        </div>

                                        <!-- Total Earning & Max Earning Limit -->
                                        <div class="col-xl-3 col-md-6 col-sm-12">
                                            <div class="p-3 rounded h-100" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.12), rgba(12, 14, 20, 0.95)); border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 14px;">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="text-white-50 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 600;">Earning Progress</span>
                                                    @php
                                                        $dynRate = isset($activeStaking) && $activeStaking ? $activeStaking->getDailyRate() : (float) ($activePackage->return_percent ?? 5.0);
                                                        $dynCap = isset($activeStaking) && $activeStaking ? $activeStaking->getCappingPercent() : (float) ($activePackage->max_return_percent ?? 200.0);
                                                        $maxEarn = isset($activeStaking) && $activeStaking ? $activeStaking->getMaxRoiAmount() : (float) ($activePackage->max_earning ?? $maxPackageEarning);
                                                        $currEarn = isset($activeStaking) && $activeStaking ? $activeStaking->getTotalEarned() : (float) ($activePackage->total_earning ?? $totalPackageEarning);
                                                        $remEarn = max(0.00, round($maxEarn - $currEarn, 2));
                                                        $earnPercent = $maxEarn > 0 ? min(100, round(($currEarn / $maxEarn) * 100, 1)) : 0;
                                                    @endphp
                                                    <span class="badge" style="background: rgba(59, 130, 246, 0.2); color: #60A5FA; font-size: 10px; font-weight: 700;">
                                                        Limit: $ {{ number_format($maxEarn, 2) }}
                                                    </span>
                                                </div>
                                                <h3 class="text-white font-weight-bold mb-1" style="font-size: 1.5rem;">
                                                    $ {{ number_format($currEarn, 2) }}
                                                </h3>
                                                <div class="progress my-2" style="height: 6px; background: rgba(255, 255, 255, 0.1); border-radius: 6px;">
                                                    <div class="progress-bar progress-animated" role="progressbar" style="width: {{ max(5, $earnPercent) }}%; background: linear-gradient(90deg, #3B82F6, #10B981); border-radius: 6px;"></div>
                                                </div>
                                                <div class="d-flex justify-content-between text-white-50" style="font-size: 0.72rem;">
                                                    <span>Earned: {{ $earnPercent }}%</span>
                                                    <span class="text-warning">Rem: $ {{ number_format($remEarn, 2) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between text-white-50 mt-1" style="font-size: 0.68rem;">
                                                    <span>ROI: {{ number_format($dynRate, 1) }}%</span>
                                                    <span>Cap: {{ number_format($dynCap, 0) }}%</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Lock & Expiry Status -->
                                        <div class="col-xl-3 col-md-6 col-sm-12">
                                            <div class="p-3 rounded h-100" style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.12), rgba(12, 14, 20, 0.95)); border: 1px solid rgba(236, 72, 153, 0.3); border-radius: 14px;">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <span class="text-white-50 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.08em; font-weight: 600;">Lock &amp; Expiry</span>
                                                    @if (isset($data) && $data->isTradingWalletLocked())
                                                        <span class="badge" style="background: rgba(245, 158, 11, 0.25); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.4); font-size: 10px; font-weight: 700;">
                                                            <i class="fa-solid fa-lock me-1"></i> Locked
                                                        </span>
                                                    @else
                                                        <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.4); font-size: 10px; font-weight: 700;">
                                                            <i class="fa-solid fa-lock-open me-1"></i> Unlocked
                                                        </span>
                                                    @endif
                                                </div>
                                                @if (isset($data) && $data->isTradingWalletLocked())
                                                    <h4 class="text-warning font-weight-bold mb-1" style="font-size: 1.15rem;">
                                                        {{ $data->tradingWalletRemainingLockDays() }} Days Remaining
                                                    </h4>
                                                    <small class="text-white-50 d-block" style="font-size: 0.72rem;">Trading Lock Active</small>
                                                @else
                                                    <h4 class="text-success font-weight-bold mb-1" style="font-size: 1.15rem;">
                                                        Trading Unlocked
                                                    </h4>
                                                    <small class="text-white-50 d-block" style="font-size: 0.72rem;">Full Trading Access</small>
                                                @endif
                                                <div class="mt-2 pt-2 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important; font-size: 0.72rem;">
                                                    <div class="d-flex justify-content-between text-white-50 mb-1">
                                                        <span>Activated:</span>
                                                        <span class="text-white">
                                                            @if (isset($activeStaking) && $activeStaking && $activeStaking->activated_at)
                                                                {{ date('d-m-Y H:i', strtotime($activeStaking->activated_at)) }}
                                                            @elseif ($activePackage && ($activePackage->activated_at || $activePackage->created_at))
                                                                {{ date('d-m-Y H:i', strtotime($activePackage->activated_at ?? $activePackage->created_at)) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="d-flex justify-content-between text-white-50 mb-1">
                                                        <span>Last ROI:</span>
                                                        <span class="text-white">
                                                            @if (isset($activeStaking) && $activeStaking && $activeStaking->last_roi_at)
                                                                {{ date('d-m-Y', strtotime($activeStaking->last_roi_at)) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="d-flex justify-content-between text-white-50">
                                                        <span>Status:</span>
                                                        <span class="text-white">
                                                            @if (isset($activeStaking) && $activeStaking && $activeStaking->status == 'Deactive')
                                                                <span class="text-danger">Deactivated (Capped)</span>
                                                            @elseif ($activePackage && $activePackage->expires_at)
                                                                Expires: {{ date('d-m-Y', strtotime($activePackage->expires_at)) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (isset($packageInvestments) && $packageInvestments->count() > 0)
                                        <div class="mt-3 pt-3 border-top" style="border-color: rgba(245, 158, 11, 0.15) !important;">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="text-white font-weight-bold" style="font-size: 0.85rem;">
                                                    <i class="fa-solid fa-list-check text-warning me-1"></i> Recent Investment Records
                                                </span>
                                                <a href="{{ url('/member/partnership/my-investment') }}" class="text-warning text-decoration-none" style="font-size: 0.75rem;">
                                                    View All <i class="fa-solid fa-arrow-right ms-1"></i>
                                                </a>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-dark table-sm table-borderless align-middle mb-0" style="font-size: 0.75rem; background: transparent;">
                                                    <thead>
                                                        <tr class="text-white-50" style="border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
                                                            <th>Date &amp; Time</th>
                                                            <th>Package</th>
                                                            <th>Deposit</th>
                                                            <th>Trading Wallet (70%)</th>
                                                            <th>Max Earning</th>
                                                            <th>Lock / Expiry</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($packageInvestments as $pkg)
                                                            <tr>
                                                                <td class="text-white">{{ $pkg->activated_at ? date('d-m-Y H:i', strtotime($pkg->activated_at)) : date('d-m-Y H:i', strtotime($pkg->created_at)) }}</td>
                                                                <td class="text-warning font-weight-bold">{{ $pkg->package_range ?? $pkg->package ?? 'Standard' }}</td>
                                                                <td class="text-white font-weight-bold">$ {{ number_format((float) ($pkg->invest_amount ?? $pkg->package_value), 2) }}</td>
                                                                <td class="text-success font-weight-bold">$ {{ number_format((float) ($pkg->trading_wallet_amount ?? (($pkg->invest_amount ?? $pkg->package_value) * 0.70)), 2) }}</td>
                                                                <td class="text-info font-weight-bold">$ {{ number_format((float) ($pkg->max_earning ?? (($pkg->invest_amount ?? $pkg->package_value) * 3.0)), 2) }}</td>
                                                                <td class="text-white-50">
                                                                    @if ($pkg->lock_days && $pkg->lock_days > 0)
                                                                        <span class="badge bg-dark border border-secondary text-warning" style="font-size: 10px;">{{ $pkg->lock_days }}D Lock</span>
                                                                    @else
                                                                        <span class="badge bg-dark border border-secondary text-success" style="font-size: 10px;">None</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($pkg->isPackageActive())
                                                                        <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10B981; font-size: 10px;">Active</span>
                                                                    @elseif ($pkg->isExpired())
                                                                        <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #EF4444; font-size: 10px;">Expired</span>
                                                                    @else
                                                                        <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #F59E0B; font-size: 10px;">{{ $pkg->status }}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- WALLETS ROW -->
                    <div class="row mb-2 transactions-row">
                        <!-- Fund Wallet -->
                        {{-- <div class="col-xl-6 col-lg-6 col-sm-12">
                            <div class="widget-stat card dash-3d-card row-theme-1">
                                <div class="card-body p-4">
                                    <div class="media">
                                        <span class="me-3">
                                            <i class="la la-wallet" style="font-size: 2rem;"></i>
                                        </span>
                                        <div class="media-body text-white">
                                            <p class="mb-1">Fund Wallet</p>
                                            <h3 class="text-white">$ {{ $data['p2p_wallet'] }}</h3>
                                            <div class="progress mb-2 bg-secondary">
                                                <div class="progress-bar progress-animated bg-white" style="width: 30%">
                                                </div>
                                            </div>
                                            <small>Fund Wallet Balance</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}





                        <div class="col-12">
                            <div class="card withdrawal-overview-shell">
                                <div class="card-body">
                                    <h4 class="withdrawal-overview-title mb-3">Withdrawal Overview</h4>

                                    <div class="row g-3">
                                        <div class="col-xl-6 col-lg-6 col-sm-12">
                                            <div class="withdrawal-stat-card">
                                                <div class="withdrawal-stat-inner">
                                                    <div class="withdrawal-stat-header">
                                                        <div class="withdrawal-label">
                                                            <span class="withdrawal-icon">
                                                                <i class="fa-solid fa-arrow-down"></i>
                                                            </span>
                                                            <span>Today's Withdrawal</span>
                                                        </div>
                                                    </div>
                                                    <h3 class="withdrawal-amount">$
                                                        {{ number_format((float) todayWithdrawals($data['memberid']), 2) }}
                                                    </h3>
                                                    <div class="withdrawal-progress">
                                                        <span
                                                            style="width: {{ min(100, max(18, ((float) todayWithdrawals($data['memberid']) / max((float) totalWithdrawals($data['memberid']), 1)) * 100)) }}%;"></span>
                                                    </div>
                                                    <div class="withdrawal-meta">Today's withdrawal</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-sm-12">
                                            <div class="withdrawal-stat-card">
                                                <div class="withdrawal-stat-inner">
                                                    <div class="withdrawal-stat-header">
                                                        <div class="withdrawal-label">
                                                            <span class="withdrawal-icon">
                                                                <i class="fa-solid fa-wallet"></i>
                                                            </span>
                                                            <span>Total withdrawal</span>
                                                        </div>
                                                    </div>
                                                    <h3 class="withdrawal-amount">$
                                                        {{ number_format((float) totalWithdrawals($data['memberid']), 2) }}
                                                    </h3>
                                                    <div class="withdrawal-progress">
                                                        <span style="width: 100%;"></span>
                                                    </div>
                                                    <div class="withdrawal-meta">Total Withdrawal</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--<div class="col-12">-->
                        <!--    <div class="card admin-pool-wrapper">-->
                        <!--        <div class="card-header">-->
                        <!--            <h4 class="card-title mb-0">PEPE BEP20 Overview</h4>-->
                        <!--        </div>-->
                        <!--        <div class="card-body">-->
                        <!--            <div class="row">-->
                        <!-- Total PEPE Tokens Earned -->
                        <!--                <div class="col-xl-4 col-lg-4 col-sm-12">-->
                        <!--                    <div class="widget-stat card dash-3d-card row-theme-5">-->
                        <!--                        <div class="card-body p-4">-->
                        <!--                            <div class="media">-->
                        <!--                                <span>-->
                        <!--                                    <i class="la la-money" style="font-size: 2rem;"></i>-->
                        <!--                                </span>-->
                        <!--                                <div class="media-body text-white">-->
                        <!--                                    <p class="mb-1">Total PEPE Tokens Earned</p>-->
                        <!--                                    <h3 class="text-white">-->
                        <!--                                        ff</h3>-->
                        <!--                                    <div class="progress mb-2 bg-secondary">-->
                        <!--                                        <div class="progress-bar progress-animated bg-white"-->
                        <!--                                            style="width: 80%">-->
                        <!--                                        </div>-->
                        <!--                                    </div>-->
                        <!--                                    <small>Total PEPE Tokens Earned</small>-->
                        <!--                                </div>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!--                    </div>-->
                        <!--                </div>-->

                        <!-- Available PEPE Tokens -->
                        <!--                <div class="col-xl-4 col-lg-4 col-sm-12">-->
                        <!--                    <div class="widget-stat card dash-3d-card row-theme-5">-->
                        <!--                        <div class="card-body p-4">-->
                        <!--                            <div class="media">-->
                        <!--                                <span>-->
                        <!--                                    <i class="la la-money" style="font-size: 2rem;"></i>-->
                        <!--                                </span>-->
                        <!--                                <div class="media-body text-white">-->
                        <!--                                    <p class="mb-1">Available PEPE Tokens</p>-->
                        <!--                                    <h3 class="text-white">{{ $data['airdrop_wallet'] }}</h3>-->
                        <!--                                    <div class="progress mb-2 bg-secondary">-->
                        <!--                                        <div class="progress-bar progress-animated bg-white"-->
                        <!--                                            style="width: 80%">-->
                        <!--                                        </div>-->
                        <!--                                    </div>-->
                        <!--                                    <small>Available PEPE Tokens</small>-->
                        <!--                                </div>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!--                    </div>-->
                        <!--                </div>-->

                        <!-- Total PEPE Tokens Withdrawn -->
                        <!--                <div class="col-xl-4 col-lg-4 col-sm-12">-->
                        <!--                    <div class="widget-stat card dash-3d-card row-theme-5">-->
                        <!--                        <div class="card-body p-4">-->
                        <!--                            <div class="media">-->
                        <!--                                <span>-->
                        <!--                                    <i class="la la-money" style="font-size: 2rem;"></i>-->
                        <!--                                </span>-->
                        <!--                                <div class="media-body text-white">-->
                        <!--                                    <p class="mb-1">Total PEPE Tokens Withdrawn</p>-->
                        <!--                                    <h3 class="text-white">-->
                        <!--                                        ff-->
                        <!--                                    </h3>-->
                        <!--                                    <div class="progress mb-2 bg-secondary">-->
                        <!--                                        <div class="progress-bar progress-animated bg-white"-->
                        <!--                                            style="width: 80%">-->
                        <!--                                        </div>-->
                        <!--                                    </div>-->
                        <!--                                    <small>Total PEPE Tokens Withdrawn</small>-->
                        <!--                                </div>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!--                    </div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->

                        <!-- TOTAL INCOME OVERVIEW ROW -->
                        <div class="col-12">
                            <div class="card admin-pool-wrapper">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                                    <h4 class="card-title mb-0">Total Income Overview</h4>
                                    <div class="text-end text-right">
                                        <span class="d-block text-white-50"
                                            style="font-size: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Total
                                            Income</span>
                                        <h3 class="text-white mb-0 font-weight-bold">$
                                            {{ number_format((float) totalIncome($data['memberid']), 2) }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- ROI Income -->
                                        <div class="col-xl-4 col-md-6 col-sm-12 mb-3">
                                            <a href="{{ url('member/income/monthly-staking-income') }}"
                                                class="text-decoration-none">
                                                <div class="widget-stat card dash-3d-card row-theme-1 h-100 mb-0">
                                                    <div class="card-body p-4">
                                                        <div class="media">
                                                            <span class="me-3">
                                                                <i class="la la-chart-line" style="font-size: 2rem;"></i>
                                                            </span>
                                                            <div class="media-body text-white">
                                                                <p class="mb-1">Monthly Staking Income</p>
                                                                <h4 class="text-white">$
                                                                    {{ number_format((float) totalMemberRoiIncome($data['memberid']), 2) }}
                                                                </h4>
                                                                <div class="progress mb-2">
                                                                    <div class="progress-bar progress-animated"
                                                                        style="width: 70%"></div>
                                                                </div>
                                                                <small
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <span>Total Earned</span>
                                                                    <span>View Details <i
                                                                            class="la la-arrow-right"></i></span>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <!-- Staking Level Income -->
                                        <div class="col-xl-4 col-md-6 col-sm-12 mb-3">
                                            <a href="{{ url('member/income/staking-level-income') }}"
                                                class="text-decoration-none">
                                                <div class="widget-stat card dash-3d-card row-theme-2 h-100 mb-0">
                                                    <div class="card-body p-4">
                                                        <div class="media">
                                                            <span class="me-3">
                                                                <i class="la la-layer-group" style="font-size: 2rem;"></i>
                                                            </span>
                                                            <div class="media-body text-white">
                                                                <p class="mb-1">Staking Level Income</p>
                                                                <h4 class="text-white">$
                                                                    {{ number_format((float) totalMemberStakingLevelIncome($data['memberid']), 2) }}
                                                                </h4>
                                                                <div class="progress mb-2">
                                                                    <div class="progress-bar progress-animated"
                                                                        style="width: 60%"></div>
                                                                </div>
                                                                <small
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <span>Total Earned</span>
                                                                    <span>View Details <i
                                                                            class="la la-arrow-right"></i></span>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <!-- Level Income -->
                                        <div class="col-xl-4 col-md-6 col-sm-12 mb-3">
                                            <a href="{{ url('member/income/level-income') }}"
                                                class="text-decoration-none">
                                                <div class="widget-stat card dash-3d-card row-theme-3 h-100 mb-0">
                                                    <div class="card-body p-4">
                                                        <div class="media">
                                                            <span class="me-3">
                                                                <i class="la la-bar-chart" style="font-size: 2rem;"></i>
                                                            </span>
                                                            <div class="media-body text-white">
                                                                <p class="mb-1">Level Income</p>
                                                                <h4 class="text-white">$
                                                                    {{ number_format((float) totalMemberLevelIncome($data['memberid']), 2) }}
                                                                </h4>
                                                                <div class="progress mb-2">
                                                                    <div class="progress-bar progress-animated"
                                                                        style="width: 50%"></div>
                                                                </div>
                                                                <small
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <span>Total Earned</span>
                                                                    <span>View Details <i
                                                                            class="la la-arrow-right"></i></span>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <!-- Single Leg Income -->
                                        <div class="col-xl-4 col-md-6 col-sm-12 mb-3">
                                            <a href="{{ url('member/income/single-leg-income') }}"
                                                class="text-decoration-none">
                                                <div class="widget-stat card dash-3d-card row-theme-1 h-100 mb-0">
                                                    <div class="card-body p-4">
                                                        <div class="media">
                                                            <span class="me-3">
                                                                <i class="la la-pie-chart" style="font-size: 2rem;"></i>
                                                            </span>
                                                            <div class="media-body text-white">
                                                                <p class="mb-1">Single Leg Income</p>
                                                                <h4 class="text-white">$
                                                                    {{ number_format((float) totalMemberSingleLegIncome($data['memberid']), 2) }}
                                                                </h4>
                                                                <div class="progress mb-2">
                                                                    <div class="progress-bar progress-animated"
                                                                        style="width: 55%"></div>
                                                                </div>
                                                                <small
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <span>Total Earned</span>
                                                                    <span>View Details <i
                                                                            class="la la-arrow-right"></i></span>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <!-- Partnership Income -->
                                        <div class="col-xl-4 col-md-6 col-sm-12 mb-3">
                                            <a href="{{ url('member/income/partnership-income') }}"
                                                class="text-decoration-none">
                                                <div class="widget-stat card dash-3d-card row-theme-2 h-100 mb-0">
                                                    <div class="card-body p-4">
                                                        <div class="media">
                                                            <span class="me-3">
                                                                <i class="la la-users" style="font-size: 2rem;"></i>
                                                            </span>
                                                            <div class="media-body text-white">
                                                                <p class="mb-1">Partnership Income</p>
                                                                <h4 class="text-white">$
                                                                    {{ number_format((float) totalMemberPartnershipIncome($data['memberid']), 2) }}
                                                                </h4>
                                                                <div class="progress mb-2">
                                                                    <div class="progress-bar progress-animated"
                                                                        style="width: 65%"></div>
                                                                </div>
                                                                <small
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <span>Total Earned</span>
                                                                    <span>View Details <i
                                                                            class="la la-arrow-right"></i></span>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>

                                        <!-- Team Withdrawal Commission -->
                                        <div class="col-xl-4 col-md-6 col-sm-12 mb-3">
                                            <a href="{{ url('member/income/team-withdrawal-commission') }}"
                                                class="text-decoration-none">
                                                <div class="widget-stat card dash-3d-card row-theme-4 h-100 mb-0">
                                                    <div class="card-body p-4">
                                                        <div class="media">
                                                            <span class="me-3">
                                                                <i class="la la-arrow-down" style="font-size: 2rem;"></i>
                                                            </span>
                                                            <div class="media-body text-white">
                                                                <p class="mb-1">Team Withdrawal Commission</p>
                                                                <h4 class="text-white">$
                                                                    {{ number_format((float) totalMemberTeamWithdrawalCommissionIncome($data['memberid']), 2) }}
                                                                </h4>
                                                                <div class="progress mb-2">
                                                                    <div class="progress-bar progress-animated"
                                                                        style="width: 50%"></div>
                                                                </div>
                                                                <small
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <span>Total Earned</span>
                                                                    <span>View Details <i
                                                                            class="la la-arrow-right"></i></span>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal-box-strat -->
                <div class="modal fade" id="exampleModal2" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header ">
                                <h5 class="modal-title">Make Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label">Payment method</label>
                                <div>
                                    <select class="image-select default-select dashboard-select w-100 mb-3"
                                        aria-label="Default">
                                        <option selected>Open this select menu</option>
                                        <option value="1">Bank Card</option>
                                        <option value="2">Online</option>
                                        <option value="3">Cash On Time</option>
                                    </select>
                                </div>
                                <label class="form-label">Amount</label>
                                <input type="number" class="form-control mb-3" id="exampleInputEmail4"
                                    placeholder="Rupee">
                                <label class="form-label">Card Holder Name</label>
                                <input type="number" class="form-control mb-3" id="exampleInputEmail5"
                                    placeholder="Amount">
                                <label class="form-label">Card Name</label>
                                <input type="text" class="form-control mb-3" id="exampleInputEmail6"
                                    placeholder="Amount">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="exampleModal1" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header ">
                                <h5 class="modal-title">Make Payment</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">Seller Mobile Number</label>
                                    <input type="number" class="form-control mb-3" id="exampleInputEmail1"
                                        placeholder="Number">
                                    <label class="form-label">Product Name</label>
                                    <input type="text" class="form-control mb-3" id="exampleInputEmail2"
                                        placeholder=" Name">
                                    <label class="form-label">Amount</label>
                                    <input type="number" class="form-control mb-3" id="exampleInputEmail3"
                                        placeholder="Amount">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger light"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!--<div class="col-12 mb-2">-->
                <!--    <div class="widget-stat card dash-3d-card row-theme-5">-->
                <!--        <div class="card-body py-3 px-3 ps-2 pe-5">-->
                <!--            <div class="media align-items-center">-->
                <!--                <span class="me-2">-->
                <!--                    <i class="la la-link" style="font-size: 1.9rem;"></i>-->
                <!--                </span>-->
                <!--                <div class="media-body text-white">-->
                <!--                    <p class="mb-1" style="font-size: 1.20rem; line-height: 2;">PEPE BEP20-->
                <!--                        Contract Address-->
                <!--                    </p>-->
                <!--                    <div class="input-group">-->
                <!--                        <input type="text" class="form-control form-control-sm" style="height: 34px;"-->
                <!--                            value="0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00" id="contractAddress"-->
                <!--                            readonly>-->
                <!--                        <button class="btn btn-dark btn-sm text-dark js-copy-address px-3"-->
                <!--                            style="height: 34px;" type="button"-->
                <!--                            address-copy-target="contractAddress">Copy</button>-->
                <!--                    </div>-->
                <!--                    <small class="d-block mt-1" style="font-size: 0.72rem;">Copy this PEPE BEP20-->
                <!--                        Contract Address here.</small>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->


                @php
                    $referralLink = url('/member/register/' . $data['memberid']);
                @endphp
                <div class="col-12 mb-2">
                    <div class="widget-stat card dash-3d-card row-theme-5">
                        <div class="card-body py-3 px-3 ps-2 pe-5">
                            <div class="media align-items-center">
                                <span class="me-2">
                                    <i class="la la-link" style="font-size: 1.9rem;"></i>
                                </span>
                                <div class="media-body text-white">
                                    <p class="mb-1" style="font-size: 1.20rem; line-height: 2;">Referral Link
                                    </p>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" style="height: 34px;"
                                            value="{{ $referralLink }}" id="referralLinkInput" readonly>
                                        <button class="btn btn-dark btn-sm text-dark js-copy-referral px-3"
                                            style="height: 34px;" type="button"
                                            data-copy-target="referralLinkInput">Copy</button>
                                    </div>
                                    <small class="d-block mt-1" style="font-size: 0.72rem;">Share this link to
                                        invite new members.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--********************************** Footer Start*********************************** -->



                <!--********************************** Footer End*********************************** -->

                <!--********************************** Content body end ***********************************-->

                @if (session('show_dashboard_popup') && isset($achieverImages) && $achieverImages->count() > 0)
                    <div class="modal fade" id="achieverModal" tabindex="-1" aria-labelledby="achieverModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" style="width:80%">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="achieverModalLabel">Dashboard Images</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <div id="achieverCarousel" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach ($achieverImages as $image)
                                                <div
                                                    class="carousel-item @if ($loop->first) active @endif">
                                                    <img src="{{ asset('uploads/' . $image->image) }}"
                                                        class="d-block w-100" alt="{{ $image->title }}"
                                                        style="max-height: 550px; object-fit: contain; background: #000;">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if ($achieverImages->count() > 1)
                                            <button class="carousel-control-prev" type="button"
                                                data-bs-target="#achieverCarousel" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Previous</span>
                                            </button>
                                            <button class="carousel-control-next" type="button"
                                                data-bs-target="#achieverCarousel" data-bs-slide="next">
                                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                <span class="visually-hidden">Next</span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var achieverModalEl = document.getElementById('achieverModal');
                            if (!achieverModalEl || typeof bootstrap === 'undefined') {
                                return;
                            }
                            var achieverModal = new bootstrap.Modal(achieverModalEl);
                            achieverModal.show();
                        });
                    </script>
                @endif
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var copyButtons = document.querySelectorAll('.js-copy-referral');
                    if (!copyButtons.length) {
                        return;
                    }

                    // var copyAddressButtons = document.querySelectorAll('.js-copy-address');
                    // if (!copyButtons.length || !copyAddressButtons.length) {
                    //     return;
                    // }
                    copyButtons.forEach(function(button) {
                        button.addEventListener('click', function() {
                            var targetId = button.getAttribute('data-copy-target');
                            var targetInput = document.getElementById(targetId);
                            if (!targetInput) {
                                return;
                            }

                            var linkValue = targetInput.value;
                            var resetLabel = function() {
                                setTimeout(function() {
                                    button.textContent = 'Copy';
                                }, 1500);
                            };

                            if (navigator.clipboard && window.isSecureContext) {
                                navigator.clipboard.writeText(linkValue).then(function() {
                                    button.textContent = 'Copied';
                                    resetLabel();
                                }).catch(function() {
                                    targetInput.select();
                                    document.execCommand('copy');
                                    button.textContent = 'Copied';
                                    resetLabel();
                                });
                                return;
                            }

                            targetInput.select();
                            document.execCommand('copy');
                            button.textContent = 'Copied';
                            resetLabel();
                        });
                    });
                    // copyAddressButtons.forEach(function (button) {
                    //     button.addEventListener('click', function () {
                    //         var targetId = button.getAttribute('address-copy-target');
                    //         var targetInput = document.getElementById(targetId);
                    //         if (!targetInput) {
                    //             return;
                    //         }

                    //         var linkValue = targetInput.value;
                    //         var resetLabel = function () {
                    //             setTimeout(function () {
                    //                 button.textContent = 'Copy';
                    //             }, 1500);
                    //         };

                    //         if (navigator.clipboard && window.isSecureContext) {
                    //             navigator.clipboard.writeText(linkValue).then(function () {
                    //                 button.textContent = 'Copied';
                    //                 resetLabel();
                    //             }).catch(function () {
                    //                 targetInput.select();
                    //                 document.execCommand('copy');
                    //                 button.textContent = 'Copied';
                    //                 resetLabel();
                    //             });
                    //             return;
                    //         }

                    //         targetInput.select();
                    //         document.execCommand('copy');
                    //         button.textContent = 'Copied';
                    //         resetLabel();
                    //     });

                    // });
                });
            </script>
            <script>
                // Real-time clock functionality - Show UTC time
                function updateDateTime() {
                    // Get current UTC time
                    const now = new Date();

                    // Format time as HH:MM:SS (UTC)
                    const hours = String(now.getUTCHours()).padStart(2, '0');
                    const minutes = String(now.getUTCMinutes()).padStart(2, '0');
                    const seconds = String(now.getUTCSeconds()).padStart(2, '0');
                    const timeString = hours + ':' + minutes + ':' + seconds;

                    // Format date (UTC)
                    const dateOptions = {
                        timeZone: 'UTC',
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    const dateString = new Intl.DateTimeFormat('en-US', dateOptions).format(now);

                    // Update the display elements
                    const timeElement = document.getElementById('currentDateTime');
                    const dateElement = document.getElementById('currentDate');
                    const timezoneElement = document.getElementById('timezone');

                    if (timeElement) {
                        timeElement.textContent = timeString;
                    }
                    if (dateElement) {
                        dateElement.textContent = dateString;
                    }
                    if (timezoneElement) {
                        timezoneElement.textContent = 'UTC (Coordinated Universal Time)';
                    }
                }

                // Update immediately and then every seconddate immediately and then every second
                updateDateTime();
                setInterval(updateDateTime, 1000);
            </script>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://cdn.jsdelivr.net/npm/web3@1.6.0/dist/web3.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/3.0.0-rc.5/web3.min.js"></script>
            <script src="https://unpkg.com/@walletconnect/web3-provider@1.7.1/dist/umd/index.min.js"></script>
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.2/ethers.umd.js"></script>
            <script src="{{ asset('uassets/js/pepe_token1.js') }}"></script>

            <!--**********************************
                                Scripts
                            ***********************************-->
            <!-- Required vendors -->
            <script src="{{ asset('uassets/vendor/global/global.min.js') }}"></script>
            <script src="{{ asset('uassets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

            <!-- Datatable -->
            <script src="{{ asset('uassets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('uassets/vendor/datatables/responsive/responsive.js') }}"></script>
            <script src="{{ asset('uassets/js/plugins-init/datatables.init.js') }}"></script>


            <!-- Apex Chart -->
            <script src="{{ asset('uassets/vendor/apexchart/apexchart.js') }}"></script>
            <script src="{{ asset('uassets/vendor/chart-js/chart.bundle.min.js') }}"></script>

            <!-- counter -->
            <script src="{{ asset('uassets/vendor/counter/counter.min.js') }}"></script>
            <script src="{{ asset('uassets/vendor/counter/waypoint.min.js') }}"></script>

            <!-- Chart piety plugin files -->
            {{-- <script src="{{asset('uassets/vendor/peity/jquery.peity.min.js')}}"></script>
            <script src="{{asset('uassets/vendor/swiper/js/swiper-bundle.min.js')}}"></script> --}}
            <script src="{{ asset('uassets/vendor/peity/jquery.peity.min.js') }}"></script>
            <script src="{{ asset('uassets/js/dashboard/trading-market.js') }}"></script>

            <!-- Dashboard 1 -->
            <script src="{{ asset('uassets/js/dashboard/dashboard-1.js') }}"></script>
            <script src="{{ asset('uassets/js/custom.min.js') }}"></script>
            <script src="{{ asset('uassets/js/dlabnav-init.js') }}"></script>
            <script src="{{ asset('uassets/js/demo.js') }}"></script>
            <script src="{{ asset('uassets/js/main.js') }}"></script>
            {{-- <script src="{{asset('uassets/js/styleSwitcher.js')}}"></script> --}}
            <script type="text/javascript">
                function googleTranslateFunction() {
                    new google.translate.TranslateElement({
                        pageLanguage: 'en',
                        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
                    }, 'google_translate_element');
                }
            </script>
            <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateFunction"></script>
            <script>
                jQuery(document).ready(function() {
                    setTimeout(function() {
                        dlabSettingsOptions.version = 'light';
                        new dlabSettings(dlabSettingsOptions);
                        setCookie('version', 'light');
                    }, 1500)
                });
            </script>
            <script>
                $(function() {
                    function isMobileView() {
                        return window.innerWidth <= 768;
                    }

                })
            </script>

        @endsection
