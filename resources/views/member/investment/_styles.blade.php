<style>
    /* =============================================
       MATH WALLET – STAKING PAGE (BULL MARKET THEME)
       Theme: Luxury Obsidian Black, Radiant Gold, Swiss Red, Candlestick Green
    ============================================= */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --sk-bg: #08090C;
        --sk-surface: #12151E;
        --sk-surface-2: #0C0E14;
        --sk-border: rgba(245, 158, 11, 0.16);
        --sk-border-h: rgba(245, 158, 11, 0.45);
        --sk-gold: #F59E0B;
        --sk-gold-bright: #FFD700;
        --sk-gold-light: #FBBF24;
        --sk-red: #EF4444;
        --sk-green: #10B981;
        --sk-green-neon: #00E676;
        --sk-glow-primary: rgba(245, 158, 11, 0.25);
        --sk-glow-purple: rgba(239, 68, 68, 0.25);
        --sk-text: #FFFFFF;
        --sk-muted: #CBD5E1;
        --sk-muted2: #94A3B8;
        --sk-danger: #EF4444;
        --sk-blue: #F59E0B;
        --sk-blue-acc: #FBBF24;
        --sk-purple: #EF4444;
        --sk-cyan: #10B981;
        --sk-cyan-glow: #00E676;
        --sk-grad-primary: linear-gradient(135deg, #FFD700 0%, #F59E0B 50%, #B45309 100%);
        --sk-grad-cyan: linear-gradient(135deg, #00E676 0%, #F59E0B 100%);
    }

    .staking-page {
        background: var(--sk-bg);
        min-height: calc(100vh - 80px);
        font-family: 'Inter', sans-serif;
    }

    .staking-page .container-fluid {
        max-width: 1200px;
    }

    /* ---- HERO ---- */
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

    .sk-hero-chips {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 22px;
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
        background: var(--sk-surface);
        border: 1px solid var(--sk-border);
        border-radius: 18px;
        box-shadow: 0 24px 60px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(245, 158, 11, 0.1);
        overflow: hidden;
        transition: box-shadow .3s ease;
    }

    .staking-card:hover {
        box-shadow: 0 28px 70px rgba(0, 0, 0, 0.7), 0 0 30px var(--sk-glow-primary);
    }

    .staking-card .card-header {
        border-bottom: 1px solid var(--sk-border);
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
        color: var(--sk-text);
        font-size: 17px;
        font-weight: 700;
        margin: 0;
        text-align: left;
    }

    .staking-card .card-title span {
        color: var(--sk-muted);
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
        color: var(--sk-muted);
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
    }

    /* ---- Inputs ---- */
    .staking-input,
    .staking-input.form-control {
        background: var(--sk-surface-2);
        border: 1px solid var(--sk-border);
        border-radius: 10px;
        color: var(--sk-text);
        min-height: 50px;
        font-size: 14.5px;
        font-weight: 500;
        padding: 0 16px;
        transition: border-color .25s ease, box-shadow .25s ease, background .25s ease;
    }

    .staking-input::placeholder {
        color: var(--sk-muted2);
    }

    .staking-input:focus,
    .staking-input.form-control:focus {
        background: rgba(245, 158, 11, 0.06);
        border-color: var(--sk-gold);
        box-shadow: 0 0 0 3.5px rgba(245, 158, 11, 0.22);
        color: var(--sk-text);
        outline: none;
    }

    .staking-input[readonly],
    .staking-input.form-control[readonly] {
        background: rgba(12, 14, 20, 0.85);
        color: var(--sk-muted);
        cursor: default;
    }

    .staking-card .input-group-text {
        background: var(--sk-surface-2);
        border: 1px solid var(--sk-border);
        border-right: none;
        border-radius: 10px 0 0 10px;
        color: var(--sk-gold-light);
        font-size: 16px;
        font-weight: 700;
        min-height: 50px;
        padding: 0 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .staking-card .input-group .staking-input {
        border-radius: 0 10px 10px 0;
    }

    .staking-card .input-group:focus-within .input-group-text {
        border-color: var(--sk-gold);
    }

    .staking-card .text-danger {
        color: var(--sk-danger) !important;
        font-size: 12px;
    }

    /* ---- Select Dropdown ---- */
    .staking-select,
    select.staking-input {
        appearance: none !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        background-color: var(--sk-surface-2) !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%23FBBF24' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 14px center !important;
        background-size: 18px 18px !important;
        padding-right: 44px !important;
        cursor: pointer;
    }

    .staking-select:focus,
    select.staking-input:focus {
        background-color: rgba(245, 158, 11, 0.06) !important;
        border-color: var(--sk-gold) !important;
        box-shadow: 0 0 0 3.5px rgba(245, 158, 11, 0.22) !important;
        color: var(--sk-text) !important;
    }

    .staking-select option,
    select.staking-input option {
        background: #12151E !important;
        color: #FFFFFF !important;
        padding: 12px 16px;
        font-size: 14.5px;
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
        color: var(--sk-gold-light);
        flex-shrink: 0;
    }

    /* ---- Secure label ---- */
    .sk-secure-txt {
        color: var(--sk-muted);
        font-size: 12.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sk-secure-txt i {
        color: var(--sk-green);
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

    /* ---- INFO SIDEBAR ---- */
    .sk-info-panel {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .sk-stat-card {
        background: var(--sk-surface);
        border: 1px solid var(--sk-border);
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
        color: var(--sk-muted);
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
        color: var(--sk-green);
    }

    .sk-stat-icon.cyan {
        background: rgba(245, 158, 11, 0.15);
        color: var(--sk-gold-light);
    }

    .sk-stat-icon.gold {
        background: rgba(245, 158, 11, 0.18);
        color: #FFD700;
    }

    .sk-stat-value {
        color: var(--sk-text);
        font-size: 22px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .sk-stat-sub {
        color: var(--sk-muted);
        font-size: 12px;
        margin-top: 3px;
    }

    /* ---- How it Works ---- */
    .sk-howto-card {
        background: var(--sk-surface);
        border: 1px solid var(--sk-border);
        border-radius: 16px;
        padding: 22px;
        background-image: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(220, 38, 38, 0.04));
    }

    .sk-howto-title {
        color: var(--sk-text);
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sk-howto-title i {
        color: var(--sk-gold-light);
    }

    .sk-step {
        display: flex;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid var(--sk-border);
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
        color: var(--sk-muted);
        line-height: 1.5;
    }

    .sk-step-text strong {
        color: var(--sk-text);
        display: block;
        margin-bottom: 2px;
    }

    /* ---- Tables (details page) ---- */
    .staking-value {
        color: var(--sk-text);
        font-size: 21px;
        font-weight: 700;
    }

    .staking-stat {
        height: 100%;
        padding: 20px;
        border: 1px solid var(--sk-border);
        border-radius: 12px;
        background: var(--sk-surface);
    }

    .staking-stat i {
        color: var(--sk-green);
        font-size: 18px;
        margin-bottom: 15px;
    }

    .staking-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 30px;
        padding: 6px 11px;
        font-size: 11px;
        font-weight: 700;
    }

    .staking-badge.active {
        color: #34D399;
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.35);
    }

    .staking-badge.pending {
        color: var(--sk-gold-light);
        background: rgba(245, 158, 11, 0.15);
        border: 1px solid rgba(245, 158, 11, 0.35);
    }

    .staking-badge.closed {
        color: var(--sk-danger);
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.35);
    }

    .staking-progress {
        height: 8px;
        border-radius: 20px;
        background: var(--sk-surface-2);
        overflow: hidden;
    }

    .staking-progress span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #F59E0B, #10B981);
    }

    .staking-table {
        margin: 0;
        min-width: 720px;
    }

    .staking-table th {
        color: var(--sk-gold-light);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        border-bottom: 1px solid var(--sk-border);
        padding: 14px 16px;
    }

    .staking-table td {
        color: var(--sk-text);
        font-size: 13px;
        vertical-align: middle;
        padding: 17px 16px;
        border-bottom: 1px solid var(--sk-border);
    }

    .staking-table tr:last-child td {
        border-bottom: 0;
    }

    .staking-table-wrap {
        overflow-x: auto;
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
            padding: 26px 22px;
            border-radius: 14px;
        }

        .staking-hero h1 {
            font-size: 24px;
        }

        .staking-card .card-body,
        .staking-card .card-header {
            padding: 20px;
        }

        .sk-hero-chips {
            gap: 8px;
        }

        .sk-chip {
            font-size: 11px;
            padding: 6px 12px;
        }
    }
</style>
