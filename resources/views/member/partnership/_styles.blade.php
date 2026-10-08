<style>
    /* =============================================
       PARTNERSHIP SECTION — SYNC TRADE Theme
       Theme: Luxury Obsidian Black, Radiant Gold, Swiss Red, Candlestick Green
    ============================================= */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --pt-bg: #08090C;
        --pt-surface: #12151E;
        --pt-surface-2: #0C0E14;
        --pt-border: rgba(245, 158, 11, 0.16);
        --pt-border-h: rgba(245, 158, 11, 0.45);
        --pt-gold: #F59E0B;
        --pt-gold-bright: #FFD700;
        --pt-gold-light: #FBBF24;
        --pt-red: #EF4444;
        --pt-green: #10B981;
        --pt-green-neon: #00E676;
        --pt-glow-primary: rgba(245, 158, 11, 0.25);
        --pt-text: #FFFFFF;
        --pt-muted: #CBD5E1;
        --pt-muted2: #94A3B8;
        --pt-danger: #EF4444;
        --pt-blue: #F59E0B;
        --pt-blue-acc: #FBBF24;
        --pt-purple: #EF4444;
        --pt-cyan: #10B981;
    }

    .staking-page {
        font-family: 'Inter', sans-serif;
        background: var(--pt-bg);
        min-height: calc(100vh - 80px);
        color: var(--pt-text);
    }

    .staking-page .container-fluid {
        max-width: 1400px;
    }

    /* ---- HERO BANNER ---- */
    .staking-hero {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        padding: 36px 40px;
        color: #fff;
        background: linear-gradient(135deg, #0C0F17 0%, #1A1408 40%, #362203 80%, #683F06 120%);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(245, 158, 11, 0.35);
    }

    .staking-hero::before {
        content: '';
        position: absolute;
        width: 380px;
        height: 380px;
        right: -60px;
        top: -140px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.28) 0%, transparent 70%);
        animation: heroGlowPulse 4s ease-in-out infinite;
    }

    .staking-hero::after {
        content: '';
        position: absolute;
        width: 260px;
        height: 260px;
        right: -40px;
        top: -110px;
        border: 36px solid rgba(245, 158, 11, 0.08);
        border-radius: 50%;
    }

    @keyframes heroGlowPulse {

        0%,
        100% {
            opacity: 0.6;
            transform: scale(1);
        }

        50% {
            opacity: 1;
            transform: scale(1.08);
        }
    }

    .staking-eyebrow {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #FBBF24;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .staking-eyebrow::before {
        content: '';
        display: inline-block;
        width: 18px;
        height: 2px;
        background: #FBBF24;
        border-radius: 2px;
    }

    .staking-hero h1 {
        position: relative;
        z-index: 1;
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 16px rgba(0, 0, 0, 0.5);
    }

    .staking-hero p {
        position: relative;
        z-index: 1;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
        max-width: 520px;
        font-size: 14.5px;
        line-height: 1.6;
    }

    /* ---- HERO CHIPS ---- */
    .sk-hero-chips {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }

    .sk-chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.35);
        backdrop-filter: blur(6px);
        border-radius: 50px;
        padding: 7px 16px;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
    }

    .sk-chip i {
        color: #FFD700;
        font-size: 13px;
    }

    /* ---- FORM CARD ---- */
    .staking-card {
        background: var(--pt-surface);
        border: 1px solid var(--pt-border);
        border-radius: 18px;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(245, 158, 11, 0.1);
        overflow: hidden;
        transition: box-shadow .3s ease;
    }

    .staking-card:hover {
        box-shadow: 0 28px 70px rgba(0, 0, 0, 0.7), 0 0 30px var(--pt-glow-primary);
    }

    .staking-card .card-header {
        border-bottom: 1px solid var(--pt-border);
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.10), rgba(220, 38, 38, 0.05));
        padding: 20px 28px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 12px;
    }

    .sk-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #FFD700, #F59E0B);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 18px rgba(245, 158, 11, 0.45);
        flex-shrink: 0;
    }

    .sk-card-icon i {
        color: #000;
        font-size: 16px;
    }

    .staking-card .card-title {
        color: var(--pt-text);
        font-size: 17px;
        font-weight: 700;
        margin: 0;
        text-align: left;
    }

    .staking-card .card-title span {
        color: var(--pt-muted);
        font-size: 12px;
        font-weight: 400;
        display: block;
        margin-top: 2px;
        text-align: left;
        text-transform: none;
        letter-spacing: 0;
    }

    .staking-card .card-body {
        padding: 28px;
    }

    /* ---- Labels ---- */
    .staking-label {
        color: var(--pt-muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
    }

    .staking-value {
        color: var(--pt-text);
        font-size: 20px;
        font-weight: 800;
    }

    /* ---- Inputs & Select ---- */
    .staking-input,
    .staking-input.form-control {
        background: var(--pt-surface-2) !important;
        border: 1px solid var(--pt-border) !important;
        border-radius: 10px !important;
        color: var(--pt-text) !important;
        min-height: 50px;
        font-size: 14.5px;
        font-weight: 500;
        padding: 0 16px !important;
        transition: border-color .25s ease, box-shadow .25s ease, background .25s ease !important;
    }

    select.staking-input option {
        background-color: #12151E !important;
        color: #FFFFFF !important;
        padding: 10px;
    }

    .staking-input::placeholder {
        color: var(--pt-muted2) !important;
    }

    .staking-input:focus,
    .staking-input.form-control:focus {
        background: rgba(245, 158, 11, 0.06) !important;
        border-color: var(--pt-gold) !important;
        box-shadow: 0 0 0 3.5px rgba(245, 158, 11, 0.22) !important;
        color: var(--pt-text) !important;
        outline: none !important;
    }

    .staking-input[readonly],
    .staking-input.form-control[readonly] {
        background: rgba(12, 14, 20, 0.85) !important;
        color: var(--pt-muted) !important;
        cursor: default;
    }

    .staking-card .input-group-text {
        background: var(--pt-surface-2) !important;
        border: 1px solid var(--pt-border) !important;
        border-right: none !important;
        border-radius: 10px 0 0 10px !important;
        color: var(--pt-gold-light) !important;
        font-size: 16px;
        font-weight: 700;
        min-height: 50px;
        padding: 0 14px !important;
    }

    .staking-card .input-group .staking-input {
        border-radius: 0 10px 10px 0 !important;
    }

    .staking-card .input-group:focus-within .input-group-text {
        border-color: var(--pt-gold) !important;
    }

    .staking-card .text-danger {
        color: var(--pt-danger) !important;
        font-size: 12px;
    }

    /* ---- Info Note ---- */
    .staking-note {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 12px;
        background: rgba(245, 158, 11, 0.10);
        border: 1px solid rgba(245, 158, 11, 0.25);
        color: #FEF08A;
        font-size: 13px;
        line-height: 1.6;
    }

    .staking-note i {
        margin-top: 2px;
        color: var(--pt-gold-light);
        flex-shrink: 0;
    }

    /* ---- Secure text ---- */
    .sk-secure-txt {
        color: var(--pt-muted);
        font-size: 12.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sk-secure-txt i {
        color: var(--pt-gold);
    }

    /* ---- Button ---- */
    .staking-btn {
        min-height: 50px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #FFD700 0%, #F59E0B 50%, #B45309 100%);
        color: #000;
        padding: 0 28px;
        font-size: 14.5px;
        font-weight: 800;
        letter-spacing: 0.3px;
        box-shadow: 0 8px 28px rgba(245, 158, 11, 0.45);
        transition: all .3s ease;
        position: relative;
        overflow: hidden;
    }

    .staking-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.25), transparent);
        opacity: 0;
        transition: opacity .3s ease;
    }

    .staking-btn:hover {
        background: linear-gradient(135deg, #FFE033 0%, #FBBF24 50%, #D97706 100%);
        box-shadow: 0 12px 36px rgba(245, 158, 11, 0.65);
        transform: translateY(-1px);
        color: #000;
    }

    .staking-btn:hover::before {
        opacity: 1;
    }

    .staking-btn:active {
        transform: translateY(0);
    }

    /* ---- SIDEBAR STAT CARDS ---- */
    .sk-stat-card {
        background: var(--pt-surface);
        border: 1px solid var(--pt-border);
        border-radius: 16px;
        padding: 20px 22px;
        transition: border-color .25s ease, transform .25s ease;
        background-image: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(220, 38, 38, 0.04));
    }

    .sk-stat-card:hover {
        border-color: rgba(245, 158, 11, 0.45);
        transform: translateY(-2px);
    }

    .sk-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .sk-stat-label {
        color: var(--pt-muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
    }

    .sk-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
    }

    .sk-stat-icon.green {
        background: rgba(16, 185, 129, 0.15);
        color: var(--pt-green);
    }

    .sk-stat-icon.cyan {
        background: rgba(245, 158, 11, 0.15);
        color: var(--pt-gold-light);
    }

    .sk-stat-icon.gold {
        background: rgba(245, 158, 11, 0.2);
        color: #FFD700;
    }

    .sk-stat-value {
        color: var(--pt-text);
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .sk-stat-sub {
        color: var(--pt-muted);
        font-size: 12px;
        margin-top: 3px;
    }

    /* ---- HOW-TO CARD ---- */
    .sk-howto-card {
        background: var(--pt-surface);
        border: 1px solid var(--pt-border);
        border-radius: 16px;
        padding: 22px;
        background-image: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(220, 38, 38, 0.04));
    }

    .sk-howto-title {
        color: var(--pt-text);
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sk-howto-title i {
        color: var(--pt-gold-light);
    }

    .sk-step {
        display: flex;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid var(--pt-border);
    }

    .sk-step:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .sk-step-num {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        border-radius: 50%;
        background: linear-gradient(135deg, #FFD700, #F59E0B);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
        color: #000;
        box-shadow: 0 2px 10px rgba(245, 158, 11, 0.4);
    }

    .sk-step-text {
        font-size: 13px;
        color: var(--pt-muted);
        line-height: 1.5;
    }

    .sk-step-text strong {
        color: var(--pt-text);
        display: block;
        margin-bottom: 2px;
    }

    /* ---- Alerts ---- */
    .staking-page .alert-success {
        background: rgba(16, 185, 129, 0.14);
        border: 1px solid rgba(16, 185, 129, 0.35);
        color: #A7F3D0;
        border-radius: 12px;
    }

    .staking-page .alert-danger {
        background: rgba(239, 68, 68, 0.14);
        border: 1px solid rgba(239, 68, 68, 0.35);
        color: #fca5a5;
        border-radius: 12px;
    }

    /* ---- Responsive ---- */
    @media (max-width: 767px) {
        .staking-hero {
            padding: 24px 20px;
            border-radius: 14px;
        }

        .staking-hero h1 {
            font-size: 24px;
        }

        .staking-card .card-body {
            padding: 20px;
        }
    }

    /* ---- INC CARD & TABLE STYLES (MATCHING PARTNERSHIP INCOME) ---- */
    .inc-card {
        background: #12151E;
        border: 1px solid rgba(245, 158, 11, 0.16);
        border-radius: 18px;
        box-shadow: 0 20px 55px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(245, 158, 11, 0.1);
        overflow: hidden;
    }
    .inc-card-header {
        border-bottom: 1px solid rgba(245, 158, 11, 0.16);
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.10), rgba(220, 38, 38, 0.05));
        padding: 18px 28px;
        display: flex; align-items: center; gap: 13px;
    }
    .inc-header-icon {
        width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
        background: linear-gradient(135deg, #FFD700, #F59E0B);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.45);
    }
    .inc-header-icon i { color: #000; font-size: 15px; }
    .inc-header-title { color: #fff; font-size: 16px; font-weight: 700; margin: 0; }
    .inc-header-sub { color: #CBD5E1; font-size: 12px; margin-top: 2px; }

    /* ---- Table scroll wrapper ---- */
    .inc-table-shell {
        width: calc(100% - 4px);
        max-width: calc(100% - 4px);
        margin: 0 2px;
        overflow-x: auto;
        overflow-y: hidden;
        background: #12151E;
        border-radius: 0 0 18px 18px;
        border-top: 1px solid rgba(245, 158, 11, 0.1);
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: rgba(245, 158, 11, 0.45) transparent;
    }

    .inc-table-shell::-webkit-scrollbar { height: 8px; }
    .inc-table-shell::-webkit-scrollbar-thumb {
        background: rgba(245, 158, 11, 0.45);
        border-radius: 10px;
    }
    .inc-table-shell::-webkit-scrollbar-track {
        background: transparent;
    }

    .inc-card .dataTables_wrapper,
    .inc-card .dataTables_scroll,
    .inc-card .dataTables_scrollHead,
    .inc-card .dataTables_scrollBody,
    .inc-card .dataTables_scrollFoot,
    .inc-card .dataTables_scrollHeadInner {
        background: #12151E !important;
        border: none !important;
        box-shadow: none !important;
        outline: none !important;
    }

    .inc-card .dataTables_scrollBody {
        border-top: 0 !important;
        border-bottom: 0 !important;
        border-left: 0 !important;
        border-right: 0 !important;
        box-shadow: none !important;
    }

    .inc-card .dataTables_wrapper .dataTables_scroll {
        overflow: visible !important;
    }

    .inc-card .dataTables_wrapper table {
        margin-bottom: 0 !important;
        border-collapse: collapse !important;
        border-spacing: 0 !important;
    }

    /* ---- Table ---- */
    .inc-table {
        width: 100% !important;
        min-width: 980px;
        border-collapse: collapse !important;
        table-layout: auto;
        background: transparent !important;
    }
    .inc-table thead tr th {
        background: rgba(245, 158, 11, 0.05) !important;
        color: #FBBF24 !important; font-size: 11px !important; font-weight: 700 !important;
        letter-spacing: 0.7px; text-transform: uppercase;
        padding: 14px 16px !important;
        border-bottom: 1px solid rgba(245, 158, 11, 0.16) !important; white-space: nowrap;
    }
    .inc-table tbody tr td {
        color: #E2EAF4 !important; font-size: 13.5px !important;
        padding: 15px 16px !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
        vertical-align: middle !important;
    }
    .inc-table tbody tr:last-child td { border-bottom: none !important; }
    .inc-table tbody tr:hover td { background: rgba(245, 158, 11, 0.06) !important; }
    .inc-table tbody td.dataTables_empty { color: #94A3B8 !important; text-align: center; padding: 40px !important; }

    /* ---- Status Badges ---- */
    .inc-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; border-radius: 50px; font-size: 11.5px; font-weight: 700;
    }
    .inc-badge.paid, .inc-badge.active    { background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.35); }
    .inc-badge.unpaid, .inc-badge.inactive  { background: rgba(239, 68, 68, 0.15); color: #F87171; border: 1px solid rgba(239, 68, 68, 0.35); }
    .inc-badge.pending { background: rgba(245, 158, 11, 0.15); color: #FBBF24; border: 1px solid rgba(245, 158, 11, 0.35); }

    /* ---- DataTable controls ---- */
    .inc-card .dataTables_wrapper .dataTables_length label,
    .inc-card .dataTables_wrapper .dataTables_filter label,
    .inc-card .dataTables_wrapper .dataTables_info { color: #CBD5E1 !important; font-size: 13px; }
    .inc-card .dataTables_wrapper .dataTables_length select,
    .inc-card .dataTables_wrapper .dataTables_filter input {
        background: #0C0E14 !important; border: 1px solid rgba(245, 158, 11, 0.2) !important;
        border-radius: 8px !important; color: #fff !important;
        padding: 5px 10px !important; font-size: 13px !important;
    }
    .inc-card .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #F59E0B !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25) !important; outline: none !important;
    }
    .inc-card .dataTables_wrapper .dataTables_paginate .paginate_button {
        background: #0C0E14 !important; border: 1px solid rgba(245, 158, 11, 0.2) !important;
        color: #CBD5E1 !important; border-radius: 8px !important;
        margin: 0 3px !important; padding: 5px 12px !important; font-size: 13px !important;
    }
    .inc-card .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .inc-card .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: linear-gradient(135deg, #FFD700, #F59E0B) !important;
        border-color: transparent !important; color: #000000 !important; font-weight: 800 !important;
    }
</style>
