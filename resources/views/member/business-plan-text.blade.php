@extends('member.layouts.main')
@section('title', 'Official Business Plan')
@section('container')

    <div class="content-body" style="overflow-x: hidden;">
        <div class="container-fluid pt-2 pb-5 plan-page-container" style="padding-bottom: 90px !important;">

            <!-- ============================================================ -->
            <!-- PAGE HEADER WITH BACK BUTTON & BREADCRUMBS -->
            <!-- ============================================================ -->
            <div class="page-titles mb-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="welcome-text">
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <a href="{{ url('/member/dashboard') }}" class="btn btn-sm btn-outline-light d-inline-flex align-items-center px-3 py-1 rounded-pill" style="font-size: 12px; border-color: rgba(255,255,255,0.25);">
                                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                            </a>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="font-size: 11px;">
                                <i class="fas fa-certificate me-1"></i> Official Compensation Plan
                            </span>
                        </div>
                        <h4 class="text-white font-weight-bold mb-0" style="font-size: clamp(18px, 4.5vw, 22px);">Business Plan Text</h4>
                        <p class="mb-0 text-muted" style="font-size: 12.5px; word-break: break-word;">Complete official textual compensation plan, earning structures, distribution rules, and system policies.</p>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <a href="{{ url('/member/dashboard') }}" class="btn btn-outline-secondary text-white btn-sm px-3 d-inline-flex align-items-center">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                        <a href="{{ url('/member/business-plan-pdf') }}" class="btn btn-outline-danger btn-sm px-3 d-inline-flex align-items-center">
                            <i class="fas fa-file-pdf me-1"></i> View PDF Deck
                        </a>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- MULTILINGUAL HERO NOTICE & REFERRAL LINK BANNER -->
            <!-- ============================================================ -->
            <div class="plan-hero-card mb-4 p-3 p-md-4">
                <div class="row align-items-center g-3">
                    <div class="col-lg-8 col-md-12">
                        <div class="d-flex align-items-center mb-2 flex-wrap gap-2">
                            <span class="badge plan-hero-badge">
                                <i class="fas fa-language me-1"></i> Multilingual Translation
                            </span>
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1" style="font-size: 11px;">
                                <i class="fas fa-globe me-1"></i> Auto Translates via Header
                            </span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 11px;">
                                <i class="fas fa-check-circle me-1"></i> Verified Model
                            </span>
                        </div>

                        <h3 class="text-white fw-bold mb-2" style="font-size: clamp(18px, 4.5vw, 24px);">SYNC TRADE Official Business Plan</h3>

                        <!-- Multilingual Notice Callout -->
                        <div class="plan-intro-callout p-3 rounded-3 mb-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fas fa-info-circle text-info mt-1 fa-lg flex-shrink-0"></i>
                                <p class="mb-0 text-white-90" style="font-size: 13.5px; line-height: 1.65; word-break: break-word;">
                                    <strong>Multilingual Notice:</strong> This section has been designed to ensure that, if the Business Plan PDF is not available in your preferred language, you can simply select your desired language from the dropdown menu at the top of the page and access the content in your preferred language.
                                </p>
                            </div>
                        </div>

                        <!-- Quick Referral Link Bar -->
                        <div class="referral-bar-wrapper p-2 rounded-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-12 col-sm">
                                    <div class="position-relative">
                                        <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted" style="font-size: 13px;">
                                            <i class="fas fa-link text-warning"></i>
                                        </span>
                                        <input type="text" id="memberReferralInput" class="form-control referral-input ps-5" value="{{ $referralLink }}" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-auto d-flex gap-2">
                                    <button type="button" class="btn btn-warning btn-sm px-3 fw-bold flex-grow-1 flex-sm-grow-0 d-inline-flex align-items-center justify-content-center" onclick="copyReferralLink()">
                                        <i class="far fa-copy me-1"></i> <span id="copyBtnText">Copy Link</span>
                                    </button>
                                    <a href="https://wa.me/?text={{ rawurlencode("📊 *SYNC TRADE Official Business Plan*\n\nReview the complete earnings and staking model:\n👉 Join our team: " . $referralLink) }}" target="_blank" class="btn btn-success btn-sm px-3 flex-grow-1 flex-sm-grow-0 d-inline-flex align-items-center justify-content-center">
                                        <i class="fab fa-whatsapp me-1"></i> Share
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Summary Highlights Card -->
                    <div class="col-lg-4 col-md-12">
                        <div class="plan-quick-summary p-3 rounded-3">
                            <h6 class="text-white fw-bold mb-3 d-flex align-items-center" style="font-size: 13.5px;">
                                <i class="fas fa-layer-group text-warning me-2"></i> Plan Highlights
                            </h6>
                            <div class="d-flex flex-column gap-2" style="font-size: 12.5px;">
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                    <span class="text-white-50"><i class="fas fa-coins text-warning me-1"></i> Min Deposit</span>
                                    <span class="text-white fw-bold text-nowrap">50 USDT</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                    <span class="text-white-50"><i class="fas fa-wallet text-success me-1"></i> Trading Wallet</span>
                                    <span class="text-success fw-bold text-nowrap">{{ number_format((float) ($distribution?->trading_wallet ?? 70), 0) }}% Allocation</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                    <span class="text-white-50"><i class="fas fa-lock text-info me-1"></i> Lock-in Period</span>
                                    <span class="text-info fw-bold text-nowrap">{{ (int) ($distribution?->lock_days ?? 90) }} Days</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                    <span class="text-white-50"><i class="fas fa-chart-line text-warning me-1"></i> Monthly Profit</span>
                                    <span class="text-warning fw-bold text-nowrap">Up to 10% / Month</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                    <span class="text-white-50"><i class="fas fa-users text-primary me-1"></i> Referral Bonus</span>
                                    <span class="text-white fw-bold text-nowrap">10% (3 Levels)</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                    <span class="text-white-50"><i class="fas fa-trophy text-warning me-1"></i> Hero of Month</span>
                                    <span class="text-warning fw-bold text-nowrap">2% Monthly Pool</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <span class="text-white-50"><i class="fas fa-handshake text-info me-1"></i> Partnership</span>
                                    <span class="text-white fw-bold text-nowrap">2% – 8% Daily Biz</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- QUICK JUMP PILLS NAVIGATION -->
            <!-- ============================================================ -->
            <div class="quick-nav-bar mb-4 p-2 rounded-3">
                <div class="d-flex align-items-center gap-2 overflow-auto py-1 px-1 quick-nav-scroll">
                    <span class="text-white-50 small fw-bold text-nowrap me-1"><i class="fas fa-compass me-1"></i> Jump:</span>
                    <a href="#section-packages" class="quick-nav-pill text-nowrap">Packages</a>
                    <a href="#section-distribution" class="quick-nav-pill text-nowrap">Package Distribution</a>
                    <a href="#section-withdrawal" class="quick-nav-pill text-nowrap">Withdrawal &amp; Lock-in</a>
                    <a href="#section-trading-profit" class="quick-nav-pill text-nowrap">Trading Profit</a>
                    <a href="#section-referral" class="quick-nav-pill text-nowrap">Referral Bonus (3L)</a>
                    <a href="#section-team-trading" class="quick-nav-pill text-nowrap">Team Trading (10L)</a>
                    <a href="#section-daily-share" class="quick-nav-pill text-nowrap">Daily Team Share (10L)</a>
                    <a href="#section-hero" class="quick-nav-pill text-nowrap">Hero of the Month</a>
                    <a href="#section-partnership" class="quick-nav-pill text-nowrap">Partnership</a>
                    <a href="#section-airdrop" class="quick-nav-pill text-nowrap">PEPE Airdrop</a>
                    <a href="#section-ecosystem" class="quick-nav-pill text-nowrap">System Policies</a>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 1: PACKAGES & MINIMUM DEPOSIT -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-packages">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.25) 0%, rgba(217, 119, 6, 0.10) 100%); border-bottom: 1px solid rgba(245, 158, 11, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0">1</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">INVESTMENT PACKAGES &amp; MINIMUM DEPOSIT</h5>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold" style="font-size: 12px;">
                        Minimum Deposit: 50 USDT
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        Investments in <strong>SYNC TRADE</strong> start from as low as <strong>50 USDT</strong>. The system offers 3 distinct tiers tailored for everyday investors up to high-net-worth algorithmic traders. Every individual package maintains its own activation date, lock-in countdown, daily profit rate, and capping limits.
                    </p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-4">
                            <div class="feature-box p-3 rounded-3 text-center h-100">
                                <div class="feature-icon-circle mx-auto mb-2" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa;">
                                    <i class="fas fa-layer-group fa-lg"></i>
                                </div>
                                <span class="badge bg-primary-subtle text-primary mb-1">Tier 1</span>
                                <h5 class="text-white fw-bold mb-1">50 – 500 USDT</h5>
                                <p class="text-white-50 mb-2 small">Entry-level package with up to <strong>5% Monthly Profit</strong> paid on daily basis.</p>
                                <span class="badge bg-secondary text-white-50" style="font-size: 11px;">Min: 50 USDT</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="feature-box p-3 rounded-3 text-center h-100" style="border-color: rgba(245, 158, 11, 0.35);">
                                <div class="feature-icon-circle mx-auto mb-2" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">
                                    <i class="fas fa-chart-line fa-lg"></i>
                                </div>
                                <span class="badge bg-warning-subtle text-warning mb-1">Tier 2</span>
                                <h5 class="text-white fw-bold mb-1">600 – 5,000 USDT</h5>
                                <p class="text-white-50 mb-2 small">Growth tier offering up to <strong>7% Monthly Profit</strong> calculated on daily basis.</p>
                                <span class="badge bg-warning text-dark" style="font-size: 11px;">Most Popular</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="feature-box p-3 rounded-3 text-center h-100">
                                <div class="feature-icon-circle mx-auto mb-2" style="background: rgba(16, 185, 129, 0.2); color: #34d399;">
                                    <i class="fas fa-crown fa-lg"></i>
                                </div>
                                <span class="badge bg-success-subtle text-success mb-1">Tier 3</span>
                                <h5 class="text-white fw-bold mb-1">6,000 USDT &amp; Above</h5>
                                <p class="text-white-50 mb-2 small">Institutional volume with up to <strong>10% Monthly Profit</strong> paid daily.</p>
                                <span class="badge bg-success text-white" style="font-size: 11px;">Max Return</span>
                            </div>
                        </div>
                    </div>

                    <!-- Slideable Table for Packages -->
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="text-white-50 small" style="font-size: 11px;"><i class="fas fa-table me-1"></i> Package Structure &amp; Return Matrix</span>
                        <span class="text-warning small d-md-none" style="font-size: 10.5px;"><i class="fas fa-arrows-alt-h me-1"></i> Scroll horizontally</span>
                    </div>

                    <div class="plan-table-responsive mb-2">
                        <table class="table plan-table plan-table-md align-middle">
                            <thead>
                                <tr>
                                    <th class="text-start">PACKAGE TIER</th>
                                    <th>RANGE (USDT)</th>
                                    <th>TRADING WALLET (70%)</th>
                                    <th>MONTHLY TARGET</th>
                                    <th>PAYOUT FREQUENCY</th>
                                    <th>LOCK-IN DURATION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge bg-primary me-2">Tier 1</span> Starter Bull</td>
                                    <td><strong class="text-warning">$50 – $500</strong></td>
                                    <td class="text-success fw-bold">70% of Investment</td>
                                    <td class="text-info fw-bold">Up to 5% / Month</td>
                                    <td><span class="badge bg-success-subtle text-success">Daily Basis</span></td>
                                    <td>{{ (int) ($distribution?->lock_days ?? 90) }} Days</td>
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge bg-warning text-dark me-2">Tier 2</span> Pro Trader</td>
                                    <td><strong class="text-warning">$600 – $5,000</strong></td>
                                    <td class="text-success fw-bold">70% of Investment</td>
                                    <td class="text-info fw-bold">Up to 7% / Month</td>
                                    <td><span class="badge bg-success-subtle text-success">Daily Basis</span></td>
                                    <td>{{ (int) ($distribution?->lock_days ?? 90) }} Days</td>
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge bg-success me-2">Tier 3</span> Elite Institutional</td>
                                    <td><strong class="text-warning">$6,000 &amp; Above</strong></td>
                                    <td class="text-success fw-bold">70% of Investment</td>
                                    <td class="text-info fw-bold">Up to 10% / Month</td>
                                    <td><span class="badge bg-success-subtle text-success">Daily Basis</span></td>
                                    <td>{{ (int) ($distribution?->lock_days ?? 90) }} Days</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <small class="text-white-50"><i class="fas fa-info-circle me-1 text-info"></i> Note: Every package activates with its own independent returns, countdown clock, and expiry conditions.</small>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 2: PACKAGE DISTRIBUTION (DEPOSIT BREAKDOWN) -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-distribution">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.25) 0%, rgba(5, 150, 105, 0.10) 100%); border-bottom: 1px solid rgba(16, 185, 129, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #10B981;">2</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">PACKAGE DISTRIBUTION (100% TRANSPARENT POOL ALLOCATION)</h5>
                    </div>
                    <span class="badge bg-success text-white px-3 py-2 fw-bold" style="font-size: 12px;">
                        100% Distribution Model
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        Every dollar deposited into the system is allocated transparently across 5 dedicated utility pools with zero hidden fees. This model guarantees institutional liquidity while fueling robust direct, team, and global performance rewards.
                    </p>

                    <div class="row g-3 mb-3">
                        <!-- 70% Trading Wallet -->
                        <div class="col-xl col-md-4 col-sm-6 col-12">
                            <div class="p-3 rounded-3 h-100 text-center" style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.3);">
                                <h3 class="fw-bold text-success mb-1" style="font-size: 28px;">70%</h3>
                                <h6 class="text-white fw-bold mb-1" style="font-size: 13px;">Trading Wallet</h6>
                                <p class="text-white-50 mb-0 small" style="font-size: 11.5px;">Live trading allocation. Profits are calculated exclusively on this amount.</p>
                            </div>
                        </div>

                        <!-- 10% Referral Bonus -->
                        <div class="col-xl col-md-4 col-sm-6 col-12">
                            <div class="p-3 rounded-3 h-100 text-center" style="background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.3);">
                                <h3 class="fw-bold text-primary mb-1" style="font-size: 28px;">10%</h3>
                                <h6 class="text-white fw-bold mb-1" style="font-size: 13px;">Referral Bonus</h6>
                                <p class="text-white-50 mb-0 small" style="font-size: 11.5px;">Distributed instantly across 3 direct upline levels (5% - 3% - 2%).</p>
                            </div>
                        </div>

                        <!-- 10% Team Investment Share -->
                        <div class="col-xl col-md-4 col-sm-6 col-12">
                            <div class="p-3 rounded-3 h-100 text-center" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.3);">
                                <h3 class="fw-bold text-warning mb-1" style="font-size: 28px;">10%</h3>
                                <h6 class="text-white fw-bold mb-1" style="font-size: 13px;">Team Investment Share</h6>
                                <p class="text-white-50 mb-0 small" style="font-size: 11.5px;">Paid across 10 levels (1% each) based on direct referrals.</p>
                            </div>
                        </div>

                        <!-- 8% Team Trading Profit -->
                        <div class="col-xl col-md-6 col-sm-6 col-12">
                            <div class="p-3 rounded-3 h-100 text-center" style="background: rgba(168, 85, 247, 0.08); border: 1px solid rgba(168, 85, 247, 0.3);">
                                <h3 class="fw-bold text-purple mb-1" style="font-size: 28px; color: #c084fc;">08%</h3>
                                <h6 class="text-white fw-bold mb-1" style="font-size: 13px;">Team Trading Profit</h6>
                                <p class="text-white-50 mb-0 small" style="font-size: 11.5px;">Distributed on daily profits earned by team downlines up to 10 levels deep.</p>
                            </div>
                        </div>

                        <!-- 2% Hero of the Month -->
                        <div class="col-xl col-md-6 col-sm-12 col-12">
                            <div class="p-3 rounded-3 h-100 text-center" style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.3);">
                                <h3 class="fw-bold text-danger mb-1" style="font-size: 28px;">02%</h3>
                                <h6 class="text-white fw-bold mb-1" style="font-size: 13px;">Hero of the Month</h6>
                                <p class="text-white-50 mb-0 small" style="font-size: 11.5px;">Collected in Hero Wallet and distributed monthly to top performers.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.03); border: 1px dashed rgba(255, 255, 255, 0.15);">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <span class="text-white font-weight-bold" style="font-size: 13px;">
                                <i class="fas fa-check-double text-success me-2"></i>Total Package Distribution Sum:
                            </span>
                            <span class="badge bg-success px-3 py-2 text-white font-weight-bold" style="font-size: 13px;">
                                70% + 10% + 10% + 8% + 2% = 100%
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 3: WITHDRAWAL POLICY, LOCK-IN & DYNAMIC CAPPING -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-withdrawal">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.25) 0%, rgba(37, 99, 235, 0.10) 100%); border-bottom: 1px solid rgba(59, 130, 246, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #3B82F6;">3</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">WITHDRAWAL POLICY, LOCK-IN PERIOD &amp; DYNAMIC CAPPING</h5>
                    </div>
                    <span class="badge bg-primary text-white px-3 py-2 fw-bold" style="font-size: 12px;">
                        Min Withdrawal: 10 USDT
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        The system maintains two distinct withdrawal channels: <strong>Trading Wallet</strong> and <strong>Income Wallet</strong>, engineered with institutional risk-management, reverse countdown tracking, and dynamic profit return ceilings.
                    </p>

                    <div class="row g-3 mb-4">
                        <!-- Trading Wallet Card -->
                        <div class="col-lg-6 col-12">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-circle p-2" style="background: rgba(16, 185, 129, 0.2); color: #10B981;">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <h6 class="text-white fw-bold mb-0" style="font-size: 15px;">Trading Wallet Withdrawal (100% Principal)</h6>
                                </div>
                                <ul class="text-white-80 small mb-0 ps-3" style="line-height: 1.8;">
                                    <li><strong>100% Withdrawal Allowed:</strong> Available at any time after the <strong>90-Day Lock-in Period</strong>.</li>
                                    <li><strong>Package-by-Package Timing:</strong> The unlocking schedule is tracked individually from the exact activation Date and Time of each staking package.</li>
                                    <li><strong>Dynamic Return Capping:</strong> Admin has the option to govern unlocking via total profit return percentage (e.g. <strong>200%, 300%</strong> or customizable).</li>
                                    <li><strong>Real-Time Countdown:</strong> System displays a reverse clock <span class="badge bg-danger-subtle text-danger">"Fund Unlocks In XX Days"</span> and <span class="badge bg-warning-subtle text-warning">"Fund Locked, You are eligible for total return of XXX% Returns"</span>. When no upper limit is set, shows <span class="badge bg-success-subtle text-success">"No-Limit"</span>.</li>
                                    <li><strong>Profit Calculation Base:</strong> Profit is calculated strictly on the <strong>70% Trading Wallet</strong> allocation.</li>
                                    <li><strong>Minimum Withdrawal:</strong> 10 USDT.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Income Wallet Card -->
                        <div class="col-lg-6 col-12">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-circle p-2" style="background: rgba(245, 158, 11, 0.2); color: #F59E0B;">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </span>
                                    <h6 class="text-white fw-bold mb-0" style="font-size: 15px;">Income Wallet Withdrawal (100% Any Time)</h6>
                                </div>
                                <ul class="text-white-80 small mb-0 ps-3" style="line-height: 1.8;">
                                    <li><strong>100% Withdrawal at Any Time:</strong> All earned referral bonuses, team shares, trading profits, and hero rewards are fully liquid.</li>
                                    <li><strong>No Lock-in:</strong> Income Wallet has zero lock-in constraints.</li>
                                    <li><strong>Minimum Withdrawal:</strong> 10 USDT.</li>
                                    <li><strong>Instant Execution:</strong> Automated payout processing. In case of temporary liquidity queuing, transactions are held safely in sequence until cleared.</li>
                                    <li><strong>P2P Exchange Supported:</strong> Free internal wallet-to-wallet transfers or external on-chain withdrawals.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 4: MONTHLY TRADING PROFIT (PAID DAILY) -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-trading-profit">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(234, 179, 8, 0.25) 0%, rgba(202, 138, 4, 0.10) 100%); border-bottom: 1px solid rgba(234, 179, 8, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #EAB308;">4</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">MONTHLY TRADING PROFIT (CREDITED DAILY)</h5>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold" style="font-size: 12px;">
                        Daily Profit Engine
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        Trading profit is generated through institutional algorithmic trading strategies on Forex and cryptocurrency pairs. Profit is credited directly into your account on a <strong>daily basis</strong> reaching maximum to that of the total Monthly Profit Target.
                    </p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 text-center" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1);">
                                <span class="badge bg-secondary mb-2">50 – 500 USDT</span>
                                <h3 class="text-warning fw-bold mb-1">Up to 5%</h3>
                                <small class="text-white-50">Monthly Profit (Paid Daily)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 text-center" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.35);">
                                <span class="badge bg-warning text-dark mb-2">600 – 5,000 USDT</span>
                                <h3 class="text-warning fw-bold mb-1">Up to 7%</h3>
                                <small class="text-white-50">Monthly Profit (Paid Daily)</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 text-center" style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.35);">
                                <span class="badge bg-success mb-2">6,000 USDT &amp; Above</span>
                                <h3 class="text-success fw-bold mb-1">Up to 10%</h3>
                                <small class="text-white-50">Monthly Profit (Paid Daily)</small>
                            </div>
                        </div>
                    </div>
                    <small class="text-white-50"><i class="fas fa-check text-success me-1"></i> <strong>Key Rule:</strong> Monthly Trading profit is paid on a daily basis reaching maximum to that of the total Monthly Profit Target. Profit is calculated on the 70% Trading Wallet allocation.</small>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 5: REFERRAL BONUS (3 LEVELS) -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-referral">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(147, 51, 234, 0.25) 0%, rgba(126, 34, 206, 0.10) 100%); border-bottom: 1px solid rgba(147, 51, 234, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #9333EA;">5</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">DIRECT REFERRAL BONUS (3 LEVELS &bull; 10% POOL)</h5>
                    </div>
                    <span class="badge text-white px-3 py-2 fw-bold" style="font-size: 12px; background: #9333EA;">
                        Instant Direct Commission
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        Earn instant upfront referral commissions whenever someone joins your downline through your referral link across 3 generations of partners:
                    </p>

                    <div class="plan-table-responsive mb-2">
                        <table class="table plan-table plan-table-sm align-middle">
                            <thead>
                                <tr>
                                    <th class="text-start">REFERRAL LEVEL</th>
                                    <th>COMMISSION RATE</th>
                                    <th>CALCULATION BASE</th>
                                    <th>PAYOUT SPEED</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge bg-warning text-dark me-2">Level 1</span> Direct Referrals</td>
                                    <td><strong class="text-warning font-weight-bold" style="font-size: 16px;">{{ (float) ($referralBonus?->level_1_rate ?? 5) }}%</strong></td>
                                    <td>Total Investment Amount</td>
                                    <td><span class="badge bg-success-subtle text-success">Instant / Real-time</span></td>
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge bg-info text-dark me-2">Level 2</span> Second Generation</td>
                                    <td><strong class="text-info font-weight-bold" style="font-size: 16px;">{{ (float) ($referralBonus?->level_2_rate ?? 3) }}%</strong></td>
                                    <td>Total Investment Amount</td>
                                    <td><span class="badge bg-success-subtle text-success">Instant / Real-time</span></td>
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge bg-secondary me-2">Level 3</span> Third Generation</td>
                                    <td><strong class="text-primary font-weight-bold" style="font-size: 16px;">{{ (float) ($referralBonus?->level_3_rate ?? 2) }}%</strong></td>
                                    <td>Total Investment Amount</td>
                                    <td><span class="badge bg-success-subtle text-success">Instant / Real-time</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <small class="text-white-50"><i class="fas fa-info-circle text-info me-1"></i> Total Referral Pool: <strong>10%</strong> of package amount is distributed across 3 levels immediately.</small>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 6: TEAM TRADING PROFIT (10 LEVELS) -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-team-trading">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.25) 0%, rgba(8, 145, 178, 0.10) 100%); border-bottom: 1px solid rgba(6, 182, 212, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #06B6D4;">6</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">TEAM TRADING PROFIT (10 LEVELS &bull; DAILY TRADING PROFIT SHARE)</h5>
                    </div>
                    <span class="badge bg-info text-dark px-3 py-2 fw-bold" style="font-size: 12px;">
                        Calculated to 4 Decimals (0.0007)
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        Earn a continuous passive royalty from the <strong>Daily Trading Profits</strong> generated by your entire 10-level downline team. This payout is calculated daily with high-precision accounting (at least 4 decimal places, e.g. <code>0.0007</code>):
                    </p>

                    <div class="plan-table-responsive mb-2">
                        <table class="table plan-table plan-table-md align-middle text-center">
                            <thead>
                                <tr>
                                    <th class="text-start">LEVEL</th>
                                    <th>PROFIT SHARE %</th>
                                    <th>BASIS OF CALCULATION</th>
                                    <th>PAYOUT FREQUENCY</th>
                                    <th>PRECISION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $teamTradingRates = [
                                        1 => 5, 2 => 5, 3 => 4, 4 => 4, 5 => 3,
                                        6 => 3, 7 => 2, 8 => 2, 9 => 1, 10 => 1
                                    ];
                                @endphp
                                @foreach ($teamTradingRates as $lvl => $rate)
                                    <tr>
                                        <td class="text-start fw-bold text-white"><span class="badge bg-info-subtle text-info me-2">Level {{ $lvl }}</span></td>
                                        <td><strong class="text-warning font-weight-bold" style="font-size: 15px;">{{ $rate }}%</strong></td>
                                        <td>Downline Daily Trading Profit</td>
                                        <td><span class="badge bg-success-subtle text-success">Daily</span></td>
                                        <td><code>4 Decimals (0.0001+)</code></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <small class="text-white-50"><i class="fas fa-calculator text-warning me-1"></i> <strong>Important Note:</strong> This should be calculated on Trading profit earned on Daily Basis. The amount should be calculated at least 4 decimals as 0.0007.</small>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 7: DAILY TEAM INVESTMENT SHARE (10 LEVELS) -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-daily-share">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(249, 115, 22, 0.25) 0%, rgba(234, 88, 12, 0.10) 100%); border-bottom: 1px solid rgba(249, 115, 22, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #F97316;">7</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">DAILY TEAM INVESTMENT SHARE (10 LEVELS &bull; 1% PER LEVEL)</h5>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold" style="font-size: 12px;">
                        Team Turnover Performance
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        Earn a direct daily percentage of your team's total investment volume across 10 levels. Level unlocking requires achieving specific direct referral targets:
                    </p>

                    <div class="plan-table-responsive mb-2">
                        <table class="table plan-table plan-table-md align-middle text-center">
                            <thead>
                                <tr>
                                    <th class="text-start">LEVEL</th>
                                    <th>DAILY SHARE %</th>
                                    <th>DIRECT REFERRALS REQUIRED</th>
                                    <th>QUALIFICATION RULE</th>
                                    <th>PAYOUT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $teamShareRequirements = [
                                        1 => ['rate' => '1%', 'directs' => 4, 'note' => '4 Direct Referrals'],
                                        2 => ['rate' => '1%', 'directs' => 6, 'note' => '+2 Direct Referrals (6 Total)'],
                                        3 => ['rate' => '1%', 'directs' => 8, 'note' => '+2 Direct Referrals (8 Total)'],
                                        4 => ['rate' => '1%', 'directs' => 10, 'note' => '+2 Direct Referrals (10 Total)'],
                                        5 => ['rate' => '1%', 'directs' => 12, 'note' => '+2 Direct Referrals (12 Total)'],
                                        6 => ['rate' => '1%', 'directs' => 14, 'note' => '+2 Direct Referrals (14 Total)'],
                                        7 => ['rate' => '1%', 'directs' => 16, 'note' => '+2 Direct Referrals (16 Total)'],
                                        8 => ['rate' => '1%', 'directs' => 18, 'note' => '+2 Direct Referrals (18 Total)'],
                                        9 => ['rate' => '1%', 'directs' => 20, 'note' => '+2 Direct Referrals (20 Total)'],
                                        10 => ['rate' => '1%', 'directs' => 22, 'note' => '+2 Direct Referrals (22 Total)'],
                                    ];
                                @endphp
                                @foreach ($teamShareRequirements as $lvl => $info)
                                    <tr>
                                        <td class="text-start fw-bold text-white"><span class="badge bg-warning-subtle text-warning me-2">Level {{ $lvl }}</span></td>
                                        <td><strong class="text-success font-weight-bold" style="font-size: 15px;">{{ $info['rate'] }}</strong></td>
                                        <td><span class="badge bg-primary px-2 py-1 fw-bold">{{ $info['directs'] }} Directs</span></td>
                                        <td class="text-white-80 small">{{ $info['note'] }}</td>
                                        <td><span class="badge bg-success-subtle text-success">Daily</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <small class="text-white-50"><i class="fas fa-users text-info me-1"></i> Starting with 4 Directs on Level 1, each subsequent level unlocks with just +2 additional active direct partners up to 22 directs.</small>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 8: HERO OF THE MONTH (2% GLOBAL POOL) -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-hero">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.25) 0%, rgba(220, 38, 38, 0.10) 100%); border-bottom: 1px solid rgba(239, 68, 68, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #EF4444;">8</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">HERO OF THE MONTH (2% GLOBAL INVESTMENT POOL)</h5>
                    </div>
                    <span class="badge bg-danger text-white px-3 py-2 fw-bold" style="font-size: 12px;">
                        Monthly Leadership Reward
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        A prestigious global recognition program designed to honor the highest performing leader each month:
                    </p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-coins text-danger fa-lg"></i>
                                    <strong class="text-white" style="font-size: 14px;">2% Collection Pool</strong>
                                </div>
                                <p class="text-white-50 small mb-0"><strong>2% of all company investments</strong> are continuously collected in the "Hero of the Month Wallet".</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-calendar-check text-warning fa-lg"></i>
                                    <strong class="text-white" style="font-size: 14px;">1st Day Payout</strong>
                                </div>
                                <p class="text-white-50 small mb-0">Distributed automatically on the <strong>1st Day of every calendar month</strong> directly to the winner's wallet.</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-trophy text-primary fa-lg"></i>
                                    <strong class="text-white" style="font-size: 14px;">Highest Level-1 Direct Biz</strong>
                                </div>
                                <p class="text-white-50 small mb-0">Awarded to the person with the <strong>highest direct investment (Level-1)</strong> during that active month.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 mb-2" style="background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.25);">
                        <h6 class="text-warning fw-bold mb-2"><i class="fas fa-bullhorn me-1"></i> Official Operational Rules:</h6>
                        <ul class="text-white-80 small mb-0 ps-3" style="line-height: 1.8;">
                            <li><strong>Tie Condition:</strong> In case of a tie between multiple leaders with identical top direct business, the 2% fund is <strong>distributed equally</strong> among all tied members.</li>
                            <li><strong>Live Dashboard Ticker:</strong> On the Member Dashboard Hero of the Month tab, the <strong>“ID Number and Country”</strong> are continuously displayed in real time. If there is a tie, names and IDs scroll continuously in a live marquee ticker.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 9: PARTNERSHIP PROGRAM (4 TIERS) -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-partnership">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.25) 0%, rgba(79, 70, 229, 0.10) 100%); border-bottom: 1px solid rgba(99, 102, 241, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #6366F1;">9</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">PARTNERSHIP PROGRAM (EXCLUSIVE TEAM TURNOVER SHARE)</h5>
                    </div>
                    <span class="badge bg-primary text-white px-3 py-2 fw-bold" style="font-size: 12px;">
                        VIP Institutional Ranks
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        For serious leaders and community builders, the Partnership Program provides a daily percentage cut of your <strong>Daily Total Team Business</strong> up to substantial multi-thousand dollar return caps:
                    </p>

                    <div class="plan-table-responsive mb-3">
                        <table class="table plan-table plan-table-md align-middle text-center">
                            <thead>
                                <tr>
                                    <th class="text-start">PARTNER TIER</th>
                                    <th>REQUIRED PACKAGE</th>
                                    <th>DAILY SHARE %</th>
                                    <th>MAX CAPPING %</th>
                                    <th>MAX TOTAL RETURN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge rank-pill me-2" style="background: #94a3b8; color: #000;">SILVER</span> Silver Partner</td>
                                    <td><strong class="text-white">1,000 USDT</strong></td>
                                    <td><strong class="text-warning font-weight-bold">2% Daily</strong> on Total Team Biz</td>
                                    <td><span class="badge bg-warning-subtle text-warning">Up to 200%</span></td>
                                    <td class="text-success fw-bold">2,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge rank-pill me-2" style="background: #f59e0b; color: #000;">GOLD</span> Gold Partner</td>
                                    <td><strong class="text-white">5,000 USDT</strong></td>
                                    <td><strong class="text-warning font-weight-bold">4% Daily</strong> on Total Team Biz</td>
                                    <td><span class="badge bg-warning-subtle text-warning">Up to 400%</span></td>
                                    <td class="text-success fw-bold">20,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge rank-pill me-2" style="background: #38bdf8; color: #000;">PLATINUM</span> Platinum Partner</td>
                                    <td><strong class="text-white">10,000 USDT</strong></td>
                                    <td><strong class="text-warning font-weight-bold">6% Daily</strong> on Total Team Biz</td>
                                    <td><span class="badge bg-warning-subtle text-warning">Up to 600%</span></td>
                                    <td class="text-success fw-bold">60,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge rank-pill me-2" style="background: #a855f7; color: #fff;">DIAMOND</span> Diamond Partner</td>
                                    <td><strong class="text-white">25,000 USDT</strong></td>
                                    <td><strong class="text-warning font-weight-bold">8% Daily</strong> on Total Team Biz</td>
                                    <td><span class="badge bg-warning-subtle text-warning">Up to 800%</span></td>
                                    <td class="text-success fw-bold">200,000 USDT</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <small class="text-white-50"><i class="fas fa-exclamation-triangle text-warning me-1"></i> <strong>Important Condition:</strong> Only higher package will be applicable.</small>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 10: PROMOTION AIRDROP (PEPE BEP20 TOKENS) -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-airdrop">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.25) 0%, rgba(5, 150, 105, 0.10) 100%); border-bottom: 1px solid rgba(16, 185, 129, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #10B981;">10</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">PROMOTION AIRDROP (PEPE BEP20 TOKENS)</h5>
                    </div>
                    <span class="badge bg-success text-white px-3 py-2 fw-bold" style="font-size: 12px;">
                        Daily Viral Rewards
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        Promote your referral link across social media and WhatsApp to claim real <strong>PEPE (BEP20)</strong> token rewards credited directly to your Pepe Wallet:
                    </p>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-circle p-2" style="background: rgba(16, 185, 129, 0.2); color: #10B981;">
                                        <i class="fas fa-paper-plane"></i>
                                    </span>
                                    <strong class="text-white" style="font-size: 14px;">WhatsApp Sharing</strong>
                                </div>
                                <h4 class="text-success fw-bold mb-1">500 PEPE / Msg</h4>
                                <p class="text-white-50 small mb-0">Send 1 message to earn 500 Tokens.<br>Max 10 messages daily = <strong>5,000 PEPE Tokens/Day</strong>.</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(59, 130, 246, 0.05); border: 1px solid rgba(59, 130, 246, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-circle p-2" style="background: rgba(59, 130, 246, 0.2); color: #3B82F6;">
                                        <i class="fas fa-user-plus"></i>
                                    </span>
                                    <strong class="text-white" style="font-size: 14px;">Direct Registration</strong>
                                </div>
                                <h4 class="text-primary fw-bold mb-1">500 PEPE / User</h4>
                                <p class="text-white-50 small mb-0">Earn <strong>500 Pepe BEP20 Tokens</strong> for every direct member registration, even while inactive.</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(245, 158, 11, 0.05); border: 1px solid rgba(245, 158, 11, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-circle p-2" style="background: rgba(245, 158, 11, 0.2); color: #F59E0B;">
                                        <i class="fas fa-bolt"></i>
                                    </span>
                                    <strong class="text-white" style="font-size: 14px;">Direct Activation</strong>
                                </div>
                                <h4 class="text-warning fw-bold mb-1">500 PEPE / Act</h4>
                                <p class="text-white-50 small mb-0">Earn an additional <strong>500 Pepe BEP20 Tokens</strong> instantly when your direct referral activates a package.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 11: ECOSYSTEM ARCHITECTURE & OPERATING POLICIES -->
            <!-- ============================================================ -->
            <div class="plan-card mb-5" id="section-ecosystem">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(100, 116, 139, 0.25) 0%, rgba(71, 85, 105, 0.10) 100%); border-bottom: 1px solid rgba(100, 116, 139, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #64748B;">11</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">ECOSYSTEM SPECIFICATIONS &amp; OPERATIONAL STANDARDS</h5>
                    </div>
                    <span class="badge bg-secondary text-white px-3 py-2 fw-bold" style="font-size: 12px;">
                        Platform Guidelines
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        Official architectural features, compliance terms, and operational standards implemented across the application:
                    </p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h6 class="text-white fw-bold mb-2"><i class="fas fa-clock text-warning me-2"></i> Real-time Dashboard Clock &amp; System Time</h6>
                                <p class="text-white-50 small mb-0">The Date, Time and Time Zone are displayed live on the dashboard alongside <strong>“System Live Since XX Years XX Months XX Days”</strong>. All daily closings execute strictly per this system clock.</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h6 class="text-white fw-bold mb-2"><i class="fas fa-wallet text-success me-2"></i> Dual Registration Mode</h6>
                                <p class="text-white-50 small mb-0">Full registration compatibility for both <strong>General Web Browsers</strong> and <strong>DApp Browsers</strong> (Connect Wallet via Web3 / MetaMask / Trust Wallet).</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h6 class="text-white fw-bold mb-2"><i class="fas fa-language text-info me-2"></i> All Language Translation</h6>
                                <p class="text-white-50 small mb-0">Automatic multilingual engine powered via the header dropdown. Instant translation of textual plans into any world language.</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h6 class="text-white fw-bold mb-2"><i class="fas fa-chart-line text-primary me-2"></i> Live Forex Charts &amp; MT5 Live View</h6>
                                <p class="text-white-50 small mb-0">Live interactive financial charting integrated into the dashboard, with dedicated live viewing scope for master MT5 accounts.</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h6 class="text-white fw-bold mb-2"><i class="fas fa-exchange-alt text-warning me-2"></i> P2P USDT Exchange &amp; News Scroller</h6>
                                <p class="text-white-50 small mb-0">Built-in peer-to-peer exchange with referral linking (O-cycle Studio), real-time notification banner, and dynamic news ticker.</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h6 class="text-white fw-bold mb-2"><i class="fas fa-bolt text-danger me-2"></i> Instant Withdrawals &amp; Live Support</h6>
                                <p class="text-white-50 small mb-0">Instant processing on approved requests. If fund is pending in gateway wallet, transactions queue safely. 24/7 integrated Live Chat support.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- BOTTOM CALL-TO-ACTION -->
            <!-- ============================================================ -->
            <div class="bottom-action-card p-4 rounded-4 text-center">
                <h4 class="text-white fw-bold mb-2" style="font-size: clamp(18px, 4.5vw, 22px);">Ready to Start Your Trading &amp; Earning Journey?</h4>
                <p class="text-white-50 mb-3 mx-auto" style="max-width: 600px; font-size: 13.5px;">
                    Activate a package starting from 50 USDT, enjoy daily automated trading profits, build your global community, and compete for the 2% Hero of the Month prize.
                </p>
                <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('Staking.create') }}" class="btn btn-warning px-4 py-2 fw-bold d-inline-flex align-items-center">
                        <i class="fas fa-plus-circle me-1"></i> New Investment
                    </a>
                    <a href="{{ url('/member/promotion-details') }}" class="btn btn-success px-4 py-2 fw-bold d-inline-flex align-items-center">
                        <i class="fab fa-whatsapp me-1"></i> Promote Referral Link
                    </a>
                    <a href="{{ url('/member/business-plan-pdf') }}" class="btn btn-outline-light px-4 py-2 d-inline-flex align-items-center">
                        <i class="fas fa-file-pdf me-1"></i> Download PDF Deck
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- ============================================================ -->
    <!-- STYLES -->
    <!-- ============================================================ -->
    <style>
        .plan-page-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .plan-hero-card {
            background: linear-gradient(135deg, rgba(16, 20, 30, 0.98), rgba(10, 12, 18, 0.95));
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 20px;
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.6);
        }

        .plan-hero-badge {
            background: rgba(245, 158, 11, 0.15);
            color: #F59E0B;
            border: 1px solid rgba(245, 158, 11, 0.35);
            font-size: 11px;
            padding: 5px 10px;
        }

        .plan-intro-callout {
            background: rgba(59, 130, 246, 0.08);
            border-left: 3px solid #3B82F6;
        }

        .referral-bar-wrapper {
            background: rgba(0, 0, 0, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .referral-input {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            color: #F59E0B !important;
            font-weight: 600;
            font-size: 13px;
        }

        .plan-quick-summary {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .quick-nav-bar {
            background: rgba(12, 14, 20, 0.92);
            border: 1px solid rgba(245, 158, 11, 0.18);
        }

        .quick-nav-scroll {
            scrollbar-width: none;
        }

        .quick-nav-scroll::-webkit-scrollbar {
            display: none;
        }

        .quick-nav-pill {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.85);
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            border: 1px solid rgba(245, 158, 11, 0.15);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .quick-nav-pill:hover {
            background: #F59E0B;
            color: #08090C;
            border-color: #F59E0B;
            transform: translateY(-1px);
        }

        .plan-card {
            background: #0C0E14;
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            overflow: hidden;
        }

        .plan-card:hover {
            border-color: rgba(245, 158, 11, 0.38);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.55);
        }

        .section-number-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #F59E0B, #D97706);
            color: #08090C;
            font-weight: 800;
            font-size: 13px;
        }

        .feature-box {
            background: rgba(245, 158, 11, 0.03);
            border: 1px solid rgba(245, 158, 11, 0.12);
            transition: all 0.2s ease;
        }

        .feature-box:hover {
            background: rgba(245, 158, 11, 0.06);
            border-color: rgba(245, 158, 11, 0.25);
            transform: translateY(-2px);
        }

        .feature-icon-circle {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .plan-table-responsive {
            display: block;
            width: 100% !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            -webkit-overflow-scrolling: touch !important;
            border-radius: 10px;
            background: rgba(8, 9, 12, 0.85);
            scrollbar-width: thin;
            scrollbar-color: rgba(245, 158, 11, 0.4) rgba(12, 14, 20, 0.6);
        }

        .plan-table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        .plan-table-responsive::-webkit-scrollbar-track {
            background: rgba(12, 14, 20, 0.6);
            border-radius: 4px;
        }

        .plan-table-responsive::-webkit-scrollbar-thumb {
            background: rgba(245, 158, 11, 0.4);
            border-radius: 4px;
        }

        .plan-table {
            width: 100% !important;
            margin-bottom: 0 !important;
            background: transparent;
            border-collapse: collapse;
        }

        .plan-table-sm {
            min-width: 480px !important;
        }

        .plan-table-md {
            min-width: 560px !important;
        }

        .plan-table th {
            background: rgba(12, 14, 20, 0.95) !important;
            color: #F59E0B !important;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 12px 14px;
            border-bottom: 2px solid rgba(245, 158, 11, 0.20) !important;
            white-space: nowrap !important;
        }

        .plan-table td {
            background: transparent !important;
            padding: 11px 14px;
            vertical-align: middle;
            font-size: 13px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
            white-space: nowrap !important;
        }

        .plan-table tbody tr:hover td {
            background: rgba(245, 158, 11, 0.05) !important;
        }

        .rank-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 6px;
            letter-spacing: 0.5px;
        }

        .bottom-action-card {
            background: linear-gradient(135deg, #0C0F17 0%, #1A1408 50%, #2A1904 100%);
            border: 1px solid rgba(245, 158, 11, 0.30);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.55);
        }

        @media (max-width: 768px) {
            .plan-card-header {
                padding: 10px 12px !important;
            }
            .plan-table th,
            .plan-table td {
                padding: 10px 12px !important;
                font-size: 12px !important;
            }
            .plan-table th {
                font-size: 10.5px !important;
            }
        }
    </style>

    <!-- ============================================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================================ -->
    <script>
        function getLiveUrl(url) {
            if (!url) return '';
            try {
                const parsed = new URL(url, window.location.origin);
                if (window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                    return window.location.origin + parsed.pathname + parsed.search + parsed.hash;
                }
                return parsed.href;
            } catch (e) {
                return url;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const refInput = document.getElementById('memberReferralInput');
            if (refInput) {
                refInput.value = getLiveUrl(refInput.value);
            }
        });

        function copyReferralLink() {
            const input = document.getElementById('memberReferralInput');
            const liveLink = getLiveUrl(input.value);
            input.value = liveLink;
            input.select();
            input.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(liveLink).then(() => {
                const btnText = document.getElementById('copyBtnText');
                if (btnText) {
                    btnText.innerText = 'Copied!';
                    setTimeout(() => {
                        btnText.innerText = 'Copy Link';
                    }, 2000);
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Referral link copied to clipboard!',
                        showConfirmButton: false,
                        timer: 2000,
                        background: '#161c2d',
                        color: '#fff'
                    });
                } else {
                    alert('Referral link copied!');
                }
            }).catch(err => {
                document.execCommand('copy');
                alert('Referral link copied!');
            });
        }
    </script>

@endsection
