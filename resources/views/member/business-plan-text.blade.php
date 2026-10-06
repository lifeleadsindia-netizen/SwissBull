@extends('member.layouts.main')
@section('title', 'Business Plan Text')
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
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 11px;">
                                <i class="fas fa-file-alt me-1"></i> Official Plan
                            </span>
                        </div>
                        <h4 class="text-white font-weight-bold mb-0" style="font-size: clamp(18px, 4.5vw, 22px);">Business Plan Text</h4>
                        <p class="mb-0 text-muted" style="font-size: 12.5px; word-break: break-word;">Complete official textual compensation plan and earning structure with multilingual support.</p>
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

                        <h3 class="text-white fw-bold mb-2" style="font-size: clamp(18px, 4.5vw, 24px);">Math Wallet Official Business Plan</h3>

                        <!-- Exact Text from Document Intro -->
                        <div class="plan-intro-callout p-3 rounded-3 mb-3">
                            <div class="d-flex align-items-start gap-2">
                                <i class="fas fa-info-circle text-info mt-1 fa-lg flex-shrink-0"></i>
                                <p class="mb-0 text-white-90" style="font-size: 13.5px; line-height: 1.65; word-break: break-word;">
                                    <strong>Multilingual Notice:</strong> This section has been designed to ensure that, if the Business Plan PDF is not available in your preferred language, you can simply select your desired language from the dropdown menu at the top of the page and access the content in your preferred language.
                                </p>
                            </div>
                        </div>

                        <!-- Quick Referral Link Bar (Mobile Responsive Wrap) -->
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
                                    <a href="https://wa.me/?text={{ rawurlencode("📊 *Math Wallet Official Business Plan*\n\nReview the complete earnings and staking model:\n👉 Join our team: " . $referralLink) }}" target="_blank" class="btn btn-success btn-sm px-3 flex-grow-1 flex-sm-grow-0 d-inline-flex align-items-center justify-content-center">
                                        <i class="fab fa-whatsapp me-1"></i> Share
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-12">
                        <div class="plan-quick-summary p-3 rounded-3">
                            <h6 class="text-white fw-bold mb-3 d-flex align-items-center" style="font-size: 13.5px;">
                                <i class="fas fa-layer-group text-primary me-2"></i> Plan Highlights
                            </h6>
                            <div class="d-flex flex-column gap-2" style="font-size: 12.5px;">
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                    <span class="text-white-50"><i class="fas fa-bolt text-warning me-1"></i> Activation</span>
                                    <span class="text-white fw-bold text-nowrap">30 USDT (BEP20)</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                    <span class="text-white-50"><i class="fas fa-gift text-success me-1"></i> Daily Promo</span>
                                    <span class="text-white fw-bold text-nowrap">1,000 PEPE / Day</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                    <span class="text-white-50"><i class="fas fa-sitemap text-info me-1"></i> Level Income</span>
                                    <span class="text-white fw-bold text-nowrap">10 Levels Deep</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-secondary-subtle">
                                    <span class="text-white-50"><i class="fas fa-globe text-primary me-1"></i> Single Leg Stages</span>
                                    <span class="text-white fw-bold text-nowrap">15 Stages (Up to 100k)</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center py-1">
                                    <span class="text-white-50"><i class="fas fa-chart-line text-warning me-1"></i> Monthly Staking</span>
                                    <span class="text-white fw-bold text-nowrap">5% / mo (Up to 200%)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- QUICK JUMP PILLS NAVIGATION (SMOOTH HORIZONTAL SCROLL) -->
            <!-- ============================================================ -->
            <div class="quick-nav-bar mb-4 p-2 rounded-3">
                <div class="d-flex align-items-center gap-2 overflow-auto py-1 px-1 quick-nav-scroll">
                    <span class="text-white-50 small fw-bold text-nowrap me-1"><i class="fas fa-compass me-1"></i> Jump:</span>
                    <a href="#section-activation" class="quick-nav-pill text-nowrap">Activation</a>
                    <a href="#section-daily-promotion" class="quick-nav-pill text-nowrap">Daily Promotion</a>
                    <a href="#section-level-activation" class="quick-nav-pill text-nowrap">Level (Activation)</a>
                    <a href="#section-level-staking" class="quick-nav-pill text-nowrap">Level (Staking)</a>
                    <a href="#section-single-leg" class="quick-nav-pill text-nowrap">Single Leg (15 Stages)</a>
                    <a href="#section-monthly-staking" class="quick-nav-pill text-nowrap">Monthly Staking</a>
                    <a href="#section-team-withdrawal" class="quick-nav-pill text-nowrap">Team Withdrawal</a>
                    <a href="#section-partnership" class="quick-nav-pill text-nowrap">Partnership</a>
                    <a href="#section-withdrawal-system" class="quick-nav-pill text-nowrap">Withdrawal System</a>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 1: ACTIVATION PACKAGE -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-activation">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.25) 0%, rgba(217, 119, 6, 0.1) 100%); border-bottom: 1px solid rgba(245, 158, 11, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0">1</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">ACTIVATION PACKAGE</h5>
                    </div>
                    <span class="badge bg-primary px-3 py-2 text-white fw-bold" style="font-size: 12.5px;">
                        30 USDT BEP20
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-4" style="font-size: 14.5px; line-height: 1.7; word-break: break-word;">
                        <strong>Activation Package: 30 USDT BEP20.</strong> This is a one-time activation fee that provides you with lifetime activation. There are no recurring activation charges.
                    </p>

                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="feature-box p-3 rounded-3 text-center h-100">
                                <div class="feature-icon-circle mx-auto mb-2" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">
                                    <i class="fas fa-coins fa-lg"></i>
                                </div>
                                <h6 class="text-white fw-bold mb-1">30 USDT BEP20</h6>
                                <p class="text-white-50 mb-0 small">Affordable one-time entry on the Binance Smart Chain.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="feature-box p-3 rounded-3 text-center h-100">
                                <div class="feature-icon-circle mx-auto mb-2" style="background: rgba(16, 185, 129, 0.2); color: #34d399;">
                                    <i class="fas fa-infinity fa-lg"></i>
                                </div>
                                <h6 class="text-white fw-bold mb-1">Lifetime Validity</h6>
                                <p class="text-white-50 mb-0 small">No recurring subscriptions, renewals, or hidden maintenance costs.</p>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4">
                            <div class="feature-box p-3 rounded-3 text-center h-100">
                                <div class="feature-icon-circle mx-auto mb-2" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">
                                    <i class="fas fa-key fa-lg"></i>
                                </div>
                                <h6 class="text-white fw-bold mb-1">Full System Access</h6>
                                <p class="text-white-50 mb-0 small">Instantly unlocks Global Single Leg placement and Daily Promotion bonuses.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 2: DAILY PROMOTION & AIRDROP BONUS -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-daily-promotion">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.25) 0%, rgba(5, 150, 105, 0.1) 100%); border-bottom: 1px solid rgba(16, 185, 129, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #10b981;">2</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">PROMOTION AIRDROP (PEPE BEP20 TOKENS)</h5>
                    </div>
                    <span class="badge bg-success px-3 py-2 text-white fw-bold" style="font-size: 12.5px;">
                        Up to 5,000 PEPE Daily + Referral Bonuses
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <h6 class="text-success fw-bold mb-2" style="font-size: clamp(14px, 4vw, 16px); word-break: break-word;">
                        Promote Your Referral Link &amp; Earn PEPE (BEP20) Token Rewards
                    </h6>
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        This promotional airdrop rewards members for sharing their referral link and expanding the community. All rewards are distributed directly in <strong>PEPE BEP20 tokens</strong> across 3 clear rules:
                    </p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(16, 185, 129, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-circle p-2" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">
                                        <i class="fas fa-paper-plane"></i>
                                    </span>
                                    <strong class="text-white" style="font-size: 13.5px;">Rule 1: Message Sending</strong>
                                </div>
                                <p class="text-white-50 small mb-0">
                                    1 WhatsApp message sent = <strong class="text-success">500 PEPE tokens</strong>.<br>
                                    Maximum 10 messages/day = <strong class="text-success">5,000 PEPE tokens/day</strong>.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(16, 185, 129, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-circle p-2" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">
                                        <i class="fas fa-user-plus"></i>
                                    </span>
                                    <strong class="text-white" style="font-size: 13.5px;">Rule 2: Direct Registration</strong>
                                </div>
                                <p class="text-white-50 small mb-0">
                                    When your direct referral registers (inactive):<br>
                                    Receive <strong class="text-success">500 PEPE BEP20 tokens</strong> instantly.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="p-3 rounded-3 h-100" style="background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(16, 185, 129, 0.3);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge rounded-circle p-2" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">
                                        <i class="fas fa-bolt"></i>
                                    </span>
                                    <strong class="text-white" style="font-size: 13.5px;">Rule 3: Direct Activation</strong>
                                </div>
                                <p class="text-white-50 small mb-0">
                                    When your direct referral activates their account:<br>
                                    Receive <strong class="text-success">500 PEPE BEP20 tokens</strong> instantly.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-3" style="background: rgba(16, 185, 129, 0.08); border: 1px dashed rgba(16, 185, 129, 0.35);">
                        <div class="row align-items-center g-3">
                            <div class="col-12 col-md-8">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: rgba(16, 185, 129, 0.25);">
                                        <i class="fab fa-whatsapp text-success fa-lg"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-white fw-bold mb-1" style="font-size: 13.5px;">Simple WhatsApp Promotion</h6>
                                        <span class="text-white-50 small" style="word-break: break-word;">Send pre-crafted message with your referral link up to 10 times daily to claim 500 PEPE per message.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 text-md-end">
                                <a href="{{ url('/member/promotion-details') }}" class="btn btn-success btn-sm px-4 fw-bold w-100 w-md-auto">
                                    <i class="fab fa-whatsapp me-1"></i> Send Today's Promo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 3: LEVEL INCOME FROM ACTIVATION PACKAGE -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-level-activation">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.25) 0%, rgba(109, 40, 217, 0.1) 100%); border-bottom: 1px solid rgba(139, 92, 246, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #8b5cf6;">3</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">LEVEL INCOME (ACTIVATION PACKAGE)</h5>
                    </div>
                    <span class="badge px-3 py-2 text-white fw-bold" style="font-size: 12.5px; background: #8b5cf6;">
                        10 Levels &bull; 0.5% – 10%
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        This income system offers an opportunity to earn between 0.5% and 10%, depending on the team level. You can earn from up to 10 levels deep whenever a new activation takes place. As your team grows, your potential earnings can increase. You can also build an unlimited referral network by referring people directly and helping your team grow, creating an opportunity for ongoing income based on eligible team activations.
                    </p>

                    <!-- Mobile Swipe Guide -->
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="text-white-50 small" style="font-size: 11px;">10-Level Activation Payout Structure</span>
                        <span class="d-md-none badge bg-dark text-info border border-info-subtle px-2 py-1" style="font-size: 10.5px;">
                            <i class="fas fa-arrows-left-right me-1"></i> Swipe table &rarr;
                        </span>
                    </div>

                    <!-- Slideable Table Container (No overflow-hidden) -->
                    <div class="plan-table-responsive border border-secondary-subtle">
                        <table class="table table-dark table-hover mb-0 plan-table plan-table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 25%;">LEVEL</th>
                                    <th class="text-center" style="width: 25%;">PERCENTAGE</th>
                                    <th class="text-start" style="width: 50%;">ELIGIBILITY</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-1</span></td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">10%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">NO REQUIREMENT</span></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-2</span></td>
                                    <td class="text-center fw-bold text-info" style="font-size: 15px;">3%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">1 DIRECT REFERRAL</span></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-3</span></td>
                                    <td class="text-center fw-bold text-info" style="font-size: 15px;">2%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(2 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-4</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">1%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(3 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-5</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">1%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(4 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-6</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">1%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(5 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-7</span></td>
                                    <td class="text-center fw-bold text-secondary" style="font-size: 15px;">0.50%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(6 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-8</span></td>
                                    <td class="text-center fw-bold text-secondary" style="font-size: 15px;">0.50%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(7 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-9</span></td>
                                    <td class="text-center fw-bold text-secondary" style="font-size: 15px;">0.50%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(8 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-10</span></td>
                                    <td class="text-center fw-bold text-secondary" style="font-size: 15px;">0.50%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(9 Directs Total)</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 4: LEVEL INCOME FROM STAKING PACKAGE -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-level-staking">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(6, 182, 212, 0.25) 0%, rgba(8, 145, 178, 0.1) 100%); border-bottom: 1px solid rgba(6, 182, 212, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #06b6d4;">4</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">LEVEL INCOME (STAKING PACKAGE)</h5>
                    </div>
                    <span class="badge bg-info px-3 py-2 text-white fw-bold" style="font-size: 12.5px;">
                        Staking 20 to 25,000 USDT
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        This income system offers an opportunity to earn between 0.5% and 10%, depending on the team level. As your team members progress through the 10-level structure, they may purchase eligible staking packages ranging from 20 to 25,000, depending on the single-leg stage they achieve. This creates an opportunity to earn from qualifying activity across your team. The larger and more active your team, the greater your potential earnings.
                    </p>

                    <!-- Mobile Swipe Guide -->
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="text-white-50 small" style="font-size: 11px;">10-Level Staking Payout Structure</span>
                        <span class="d-md-none badge bg-dark text-info border border-info-subtle px-2 py-1" style="font-size: 10.5px;">
                            <i class="fas fa-arrows-left-right me-1"></i> Swipe table &rarr;
                        </span>
                    </div>

                    <!-- Slideable Table Container (No overflow-hidden) -->
                    <div class="plan-table-responsive border border-secondary-subtle">
                        <table class="table table-dark table-hover mb-0 plan-table plan-table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 25%;">LEVEL</th>
                                    <th class="text-center" style="width: 25%;">PERCENTAGE</th>
                                    <th class="text-start" style="width: 50%;">ELIGIBILITY</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-1</span></td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">10%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">NO REQUIREMENT</span></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-2</span></td>
                                    <td class="text-center fw-bold text-info" style="font-size: 15px;">3%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">1 DIRECT REFERRAL</span></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-3</span></td>
                                    <td class="text-center fw-bold text-info" style="font-size: 15px;">2%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(2 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-4</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">1%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(3 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-5</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">1%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(4 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-6</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">1%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(5 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-7</span></td>
                                    <td class="text-center fw-bold text-secondary" style="font-size: 15px;">0.50%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(6 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-8</span></td>
                                    <td class="text-center fw-bold text-secondary" style="font-size: 15px;">0.50%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(7 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-9</span></td>
                                    <td class="text-center fw-bold text-secondary" style="font-size: 15px;">0.50%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(8 Directs Total)</small></td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-10</span></td>
                                    <td class="text-center fw-bold text-secondary" style="font-size: 15px;">0.50%</td>
                                    <td class="text-start text-white-90"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">+1 DIRECT REFERRAL</span> <small class="text-muted">(9 Directs Total)</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 5: GLOBAL SINGLE LEG INCOME -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-single-leg">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.25) 0%, rgba(217, 119, 6, 0.1) 100%); border-bottom: 1px solid rgba(245, 158, 11, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #f59e0b;">5</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">GLOBAL SINGLE LEG INCOME</h5>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold" style="font-size: 12.5px;">
                        15 Stages &bull; Up to 100,000 USDT Passive
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        Every active ID is positioned sequentially, one below another, creating a single-leg global structure (chain). Members who activate after you, anywhere in the global network, are placed below your position according to the system's placement rules. As your single-leg team reaches the required target for each stage, you become eligible for the corresponding passive income opportunity. The complete single-leg structure is divided into 15 stages. Once a stage target is achieved, the team calculation starts afresh for the next stage, allowing you to progress through the structure step by step. This global single-leg earning system is designed to provide participants with an opportunity to generate income as the network grows and qualifying stage targets are achieved. You can earn up to <strong>100,000 USDT</strong> as passive income from this Single Leg Income depending upon the global single leg structure.
                    </p>

                    <!-- Mobile Swipe Guide -->
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="text-white-50 small" style="font-size: 11px;">15-Stage Global Single Leg Structure</span>
                        <span class="d-md-none badge bg-dark text-info border border-info-subtle px-2 py-1" style="font-size: 10.5px;">
                            <i class="fas fa-arrows-left-right me-1"></i> Swipe table &rarr;
                        </span>
                    </div>

                    <!-- Slideable Table Container (No overflow-hidden) -->
                    <div class="plan-table-responsive border border-secondary-subtle">
                        <table class="table table-dark table-hover mb-0 plan-table plan-table-md">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 15%;">STAGE</th>
                                    <th class="text-center" style="width: 28%;">TOTAL SINGLE LEG TEAM</th>
                                    <th class="text-center" style="width: 30%;">MANDATORY STAKING</th>
                                    <th class="text-center" style="width: 27%;">EARNING</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 1</span></td>
                                    <td class="text-center fw-semibold text-white">25</td>
                                    <td class="text-center"><span class="badge bg-secondary-subtle text-secondary px-2 py-1">NOT REQUIRED</span></td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">2 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 2</span></td>
                                    <td class="text-center fw-semibold text-white">75</td>
                                    <td class="text-center"><span class="badge bg-secondary-subtle text-secondary px-2 py-1">NOT REQUIRED</span></td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">5 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 3</span></td>
                                    <td class="text-center fw-semibold text-white">200</td>
                                    <td class="text-center"><span class="badge bg-secondary-subtle text-secondary px-2 py-1">NOT REQUIRED</span></td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">9 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 4</span></td>
                                    <td class="text-center fw-semibold text-white">450</td>
                                    <td class="text-center text-warning fw-semibold">20 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">18 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 5</span></td>
                                    <td class="text-center fw-semibold text-white">1,000</td>
                                    <td class="text-center text-warning fw-semibold">30 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">40 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 6</span></td>
                                    <td class="text-center fw-semibold text-white">3,000</td>
                                    <td class="text-center text-warning fw-semibold">70 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">100 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 7</span></td>
                                    <td class="text-center fw-semibold text-white">6,500</td>
                                    <td class="text-center text-warning fw-semibold">150 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">250 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 8</span></td>
                                    <td class="text-center fw-semibold text-white">15,000</td>
                                    <td class="text-center text-warning fw-semibold">300 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">500 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 9</span></td>
                                    <td class="text-center fw-semibold text-white">40,000</td>
                                    <td class="text-center text-warning fw-semibold">600 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">1,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 10</span></td>
                                    <td class="text-center fw-semibold text-white">100,000</td>
                                    <td class="text-center text-warning fw-semibold">1,200 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">2,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 11</span></td>
                                    <td class="text-center fw-semibold text-white">200,000</td>
                                    <td class="text-center text-warning fw-semibold">2,500 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">5,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 12</span></td>
                                    <td class="text-center fw-semibold text-white">400,000</td>
                                    <td class="text-center text-warning fw-semibold">5,000 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">10,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 13</span></td>
                                    <td class="text-center fw-semibold text-white">600,000</td>
                                    <td class="text-center text-warning fw-semibold">8,000 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">15,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill">STAGE 14</span></td>
                                    <td class="text-center fw-semibold text-white">800,000</td>
                                    <td class="text-center text-warning fw-semibold">10,000 USDT</td>
                                    <td class="text-center fw-bold text-success" style="font-size: 15px;">20,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold"><span class="badge stage-pill" style="background: linear-gradient(135deg, #f59e0b, #ef4444);">STAGE 15</span></td>
                                    <td class="text-center fw-semibold text-white">1,000,000</td>
                                    <td class="text-center text-warning fw-semibold">25,000 USDT</td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 16px;">50,000 USDT</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 6: MONTHLY STAKING INCOME -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-monthly-staking">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(20, 184, 166, 0.25) 0%, rgba(13, 148, 136, 0.1) 100%); border-bottom: 1px solid rgba(20, 184, 166, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #14b8a6;">6</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">MONTHLY STAKING INCOME</h5>
                    </div>
                    <span class="badge px-3 py-2 text-white fw-bold" style="font-size: 12.5px; background: #14b8a6;">
                        5% Monthly Return &bull; Up to 200% Total (40 Mos)
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        You are eligible to earn Single-Leg income through the first three stages without any additional conditions. To unlock Stage 4 onwards, activation of a qualifying staking package is mandatory. The staking package is designed to provide an additional earning opportunity, with the system offering <strong>5% returns per cycle</strong> for each staking package, with a cycle of up to <strong>40 Months</strong>. A month is calculated as 30 days from the date of package activation. Based on the stated program structure, this can provide up to <strong>200% total returns</strong> over the applicable staking period. You have the flexibility to choose when to activate your staking package. You may activate gradually as you progress through the Single-Leg stages, or activate in advance to participate in the available staking-based income opportunity.
                    </p>

                    <!-- Mobile Swipe Guide -->
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="text-white-50 small" style="font-size: 11px;">Staking Package Return Schedules</span>
                        <span class="d-md-none badge bg-dark text-info border border-info-subtle px-2 py-1" style="font-size: 10.5px;">
                            <i class="fas fa-arrows-left-right me-1"></i> Swipe table &rarr;
                        </span>
                    </div>

                    <!-- Slideable Table Container (No overflow-hidden) -->
                    <div class="plan-table-responsive border border-secondary-subtle">
                        <table class="table table-dark table-hover mb-0 plan-table plan-table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 33%;">STAKING PACKAGE</th>
                                    <th class="text-center" style="width: 33%;">MONTHLY RETURN (5%)</th>
                                    <th class="text-center" style="width: 34%;">TOTAL RETURN (200% / 40 Mos)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold text-white">20 USDT</td>
                                    <td class="text-center text-info fw-semibold">1 USDT</td>
                                    <td class="text-center text-success fw-bold">40 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">30 USDT</td>
                                    <td class="text-center text-info fw-semibold">1.5 USDT</td>
                                    <td class="text-center text-success fw-bold">60 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">70 USDT</td>
                                    <td class="text-center text-info fw-semibold">3.5 USDT</td>
                                    <td class="text-center text-success fw-bold">140 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">150 USDT</td>
                                    <td class="text-center text-info fw-semibold">7.5 USDT</td>
                                    <td class="text-center text-success fw-bold">300 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">300 USDT</td>
                                    <td class="text-center text-info fw-semibold">15 USDT</td>
                                    <td class="text-center text-success fw-bold">600 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">600 USDT</td>
                                    <td class="text-center text-info fw-semibold">30 USDT</td>
                                    <td class="text-center text-success fw-bold">1,200 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">1,200 USDT</td>
                                    <td class="text-center text-info fw-semibold">60 USDT</td>
                                    <td class="text-center text-success fw-bold">2,400 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">2,500 USDT</td>
                                    <td class="text-center text-info fw-semibold">125 USDT</td>
                                    <td class="text-center text-success fw-bold">5,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">5,000 USDT</td>
                                    <td class="text-center text-info fw-semibold">250 USDT</td>
                                    <td class="text-center text-success fw-bold">10,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">8,000 USDT</td>
                                    <td class="text-center text-info fw-semibold">400 USDT</td>
                                    <td class="text-center text-success fw-bold">16,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">10,000 USDT</td>
                                    <td class="text-center text-info fw-semibold">500 USDT</td>
                                    <td class="text-center text-success fw-bold">20,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white">25,000 USDT</td>
                                    <td class="text-center text-info fw-semibold">1,250 USDT</td>
                                    <td class="text-center text-success fw-bold">50,000 USDT</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 7: TEAM WITHDRAWAL COMMISSION -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-team-withdrawal">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(236, 72, 153, 0.25) 0%, rgba(219, 39, 119, 0.1) 100%); border-bottom: 1px solid rgba(236, 72, 153, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #ec4899;">7</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">TEAM WITHDRAWAL COMMISSION</h5>
                    </div>
                    <span class="badge px-3 py-2 text-white fw-bold" style="font-size: 12.5px; background: #ec4899;">
                        5 Levels &bull; 2% per Level (Lifetime)
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        Complete your Direct Referral target and unlock the opportunity to earn commissions on qualifying withdrawals made by your team members across up to five levels. You can unlock Level 1 by referring 5 Direct Referrals. Add 5 more Direct Referrals to unlock Level 2, and continue the same way. Every additional 5 Direct Referrals unlocks the next level. With 25 Direct Referrals, all 5 levels are unlocked, subject to the applicable program terms. Once all five levels are unlocked, you have the opportunity to earn ongoing team-based commissions on qualifying withdrawals for the lifetime.
                    </p>

                    <!-- Mobile Swipe Guide -->
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="text-white-50 small" style="font-size: 11px;">Team Withdrawal Commission Tiers</span>
                        <span class="d-md-none badge bg-dark text-info border border-info-subtle px-2 py-1" style="font-size: 10.5px;">
                            <i class="fas fa-arrows-left-right me-1"></i> Swipe table &rarr;
                        </span>
                    </div>

                    <!-- Slideable Table Container (No overflow-hidden) -->
                    <div class="plan-table-responsive border border-secondary-subtle">
                        <table class="table table-dark table-hover mb-0 plan-table plan-table-sm">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 25%;">UNLOCK</th>
                                    <th class="text-center" style="width: 25%;">DIRECT TEAM</th>
                                    <th class="text-start" style="width: 50%;">TEAM WITHDRAWAL COMMISSION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-1</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">5</td>
                                    <td class="text-start text-white-90"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">2%</span> FROM LEVEL-1</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-2</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">10</td>
                                    <td class="text-start text-white-90"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">2%</span> FROM LEVEL-1 AND 2</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-3</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">15</td>
                                    <td class="text-start text-white-90"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">2%</span> FROM LEVEL-1, 2 AND 3</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-4</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">20</td>
                                    <td class="text-start text-white-90"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">2%</span> FROM LEVEL-1, 2, 3 AND 4</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold text-white"><span class="badge level-pill">LEVEL-5</span></td>
                                    <td class="text-center fw-bold text-warning" style="font-size: 15px;">25</td>
                                    <td class="text-start text-white-90"><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">2%</span> FROM LEVEL-1, 2, 3, 4 AND 5</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 8: PARTNERSHIP INCOME (14. PARTNERSHIP PROGRAM) -->
            <!-- ============================================================ -->
            <div class="plan-card mb-4" id="section-partnership">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(234, 88, 12, 0.25) 0%, rgba(194, 65, 12, 0.1) 100%); border-bottom: 1px solid rgba(234, 88, 12, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #ea580c;">8</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">PARTNERSHIP PROGRAM</h5>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-2 fw-bold" style="font-size: 12.5px;">
                        4 Levels &bull; Max Return up to 200,000 USDT
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-3" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        The <strong>Partnership Program</strong> provides an additional earning opportunity based on your Partnership level. There are 4 partnership levels offering daily team business returns (from 2% to 8%) up to their respective maximum return caps. The applicable income is calculated daily on the total qualifying business generated by your entire team. Only higher package will be applicable. When a higher package is activated, the previously activated lower package is deactivated and replaced by the higher package.
                    </p>

                    <!-- Mobile Swipe Guide -->
                    <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                        <span class="text-white-50 small" style="font-size: 11px;">4 Partnership Levels &amp; Returns</span>
                        <span class="d-md-none badge bg-dark text-info border border-info-subtle px-2 py-1" style="font-size: 10.5px;">
                            <i class="fas fa-arrows-left-right me-1"></i> Swipe table &rarr;
                        </span>
                    </div>

                    <!-- Slideable Table Container (No overflow-hidden) -->
                    <div class="plan-table-responsive border border-secondary-subtle">
                        <table class="table table-dark table-hover mb-0 plan-table plan-table-md">
                            <thead>
                                <tr>
                                    <th class="text-start" style="width: 40%;">PACKAGE</th>
                                    <th class="text-center" style="width: 30%;">DAILY TEAM BUSINESS %</th>
                                    <th class="text-end" style="width: 30%;">MAXIMUM RETURN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge rank-pill me-2" style="background: #94a3b8; color: #000;">SILVER</span> Silver – 1,000 USDT</td>
                                    <td class="text-center"><span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">2%</span></td>
                                    <td class="text-end fw-bold text-success">2,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge rank-pill me-2" style="background: #eab308; color: #000;">GOLD</span> Gold – 5,000 USDT</td>
                                    <td class="text-center"><span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">4%</span></td>
                                    <td class="text-end fw-bold text-success">20,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge rank-pill me-2" style="background: #38bdf8; color: #000;">PLATINUM</span> Platinum – 10,000 USDT</td>
                                    <td class="text-center"><span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">6%</span></td>
                                    <td class="text-end fw-bold text-success">60,000 USDT</td>
                                </tr>
                                <tr>
                                    <td class="text-start fw-bold text-white"><span class="badge rank-pill me-2" style="background: #a855f7; color: #fff;">DIAMOND</span> Diamond – 25,000 USDT</td>
                                    <td class="text-center"><span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">8%</span></td>
                                    <td class="text-end fw-bold text-success">200,000 USDT</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 rounded mt-3" style="background: rgba(245, 158, 11, 0.1); border: 1px dashed rgba(245, 158, 11, 0.4);">
                        <strong class="text-warning d-block" style="font-size: 13px;">
                            <i class="fas fa-info-circle me-1"></i> Only higher package will be applicable.
                        </strong>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- SECTION 9: WITHDRAWAL SYSTEM -->
            <!-- ============================================================ -->
            <div class="plan-card mb-5" id="section-withdrawal-system">
                <div class="plan-card-header p-3 px-md-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.25) 0%, rgba(220, 38, 38, 0.1) 100%); border-bottom: 1px solid rgba(239, 68, 68, 0.3);">
                    <div class="d-flex align-items-center gap-2">
                        <span class="section-number-badge flex-shrink-0" style="background: #ef4444;">9</span>
                        <h5 class="text-white fw-bold mb-0" style="font-size: clamp(14px, 4vw, 17px);">WITHDRAWAL SYSTEM</h5>
                    </div>
                    <span class="badge bg-danger px-3 py-2 text-white fw-bold" style="font-size: 12.5px;">
                        15% Total Deduction Breakdown
                    </span>
                </div>
                <div class="p-3 p-md-4">
                    <p class="text-white-90 lead-text mb-4" style="font-size: 14px; line-height: 1.75; word-break: break-word;">
                        A total of <strong>15%</strong> is deducted from each withdrawal at the time the withdrawal is processed. Of this amount, <strong>10%</strong> is allocated as Team Withdrawal Commission, distributed across the eligible 5 levels above you. The remaining <strong>5%</strong> is utilised towards the platform fee and to support the operation and maintenance of the Single-Leg system.
                    </p>

                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-3 text-center h-100" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3);">
                                <h3 class="text-success fw-bold mb-1">85%</h3>
                                <h6 class="text-white fw-bold mb-1">Net User Payout</h6>
                                <p class="text-white-50 mb-0 small" style="word-break: break-word;">Directly credited in USDT (BEP20) to your designated receiving wallet address.</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-3 text-center h-100" style="background: rgba(236, 72, 153, 0.12); border: 1px solid rgba(236, 72, 153, 0.3);">
                                <h3 class="text-pink fw-bold mb-1" style="color: #f472b6;">10%</h3>
                                <h6 class="text-white fw-bold mb-1">Team Withdrawal Commission</h6>
                                <p class="text-white-50 mb-0 small" style="word-break: break-word;">Distributed equally at 2% each to the 5 eligible active upline sponsors.</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="p-3 rounded-3 text-center h-100" style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.3);">
                                <h3 class="fw-bold mb-1" style="color: #fbbf24;">5%</h3>
                                <h6 class="text-white fw-bold mb-1">Platform Maintenance</h6>
                                <p class="text-white-50 mb-0 small" style="word-break: break-word;">Dedicated to server operations, blockchain gas, and single-leg pool stability.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- BOTTOM CALL-TO-ACTION & BACK NAVIGATION -->
            <!-- ============================================================ -->
            <div class="bottom-action-card p-3 p-md-4 rounded-4 mb-5 text-center">
                <h4 class="text-white fw-bold mb-2" style="font-size: clamp(16px, 4.5vw, 20px);">Ready to Build and Earn with Math Wallet?</h4>
                <p class="text-white-50 mb-4 mx-auto" style="max-width: 650px; font-size: 13.5px; word-break: break-word;">
                    Take advantage of the verified 15-stage single-leg system, daily promotional airdrops, and sustainable staking returns. Share your link with your team today!
                </p>
                <div class="d-flex align-items-stretch justify-content-center flex-column flex-sm-row gap-2 gap-sm-3">
                    <a href="{{ url('/member/dashboard') }}" class="btn btn-outline-light btn-md px-3 py-2 fw-bold d-inline-flex align-items-center justify-content-center" style="font-size: 13.5px;">
                        <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                    </a>
                    <a href="{{ url('/member/business-plan-pdf') }}" class="btn btn-danger btn-md px-3 py-2 fw-bold d-inline-flex align-items-center justify-content-center" style="font-size: 13.5px;">
                        <i class="fas fa-file-pdf me-2"></i> Download Official PDF
                    </a>
                    <a href="{{ url('/member/plan-video') }}" class="btn btn-primary btn-md px-3 py-2 fw-bold d-inline-flex align-items-center justify-content-center" style="font-size: 13.5px;">
                        <i class="fas fa-play-circle me-2"></i> Watch Plan Video
                    </a>
                </div>
            </div>

            <!-- Bottom Spacer -->
            <div class="pb-5 mb-4" style="height: 40px;"></div>

        </div>
    </div>

    <!-- ============================================================ -->
    <!-- STYLES -->
    <!-- ============================================================ -->
    <style>
        /* Prevent entire page body from horizontal overflow shaking */
        .content-body {
            overflow-x: hidden !important;
            max-width: 100vw;
        }

        .plan-page-container {
            max-width: 100%;
            overflow-x: hidden;
        }

        .plan-hero-card {
            background: linear-gradient(135deg, #0C0F17 0%, #1A1408 40%, #362203 80%, #683F06 120%);
            border: 1px solid rgba(245, 158, 11, 0.35);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.65), 0 0 20px rgba(245, 158, 11, 0.12);
            position: relative;
            overflow: hidden;
        }

        .plan-hero-badge {
            background: rgba(245, 158, 11, 0.18);
            color: #FBBF24;
            border: 1px solid rgba(245, 158, 11, 0.35);
            font-size: 11.5px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .plan-intro-callout {
            background: rgba(245, 158, 11, 0.08);
            border-left: 4px solid #F59E0B;
            border-radius: 8px;
        }

        .referral-bar-wrapper {
            background: rgba(0, 0, 0, 0.45);
            border: 1px solid rgba(245, 158, 11, 0.18);
        }

        .referral-input {
            background: rgba(8, 9, 12, 0.8) !important;
            border: 1px solid rgba(245, 158, 11, 0.25) !important;
            color: #10B981 !important;
            font-size: 13px !important;
            font-weight: 600;
            border-radius: 8px;
        }

        .plan-quick-summary {
            background: rgba(245, 158, 11, 0.03);
            border: 1px dashed rgba(245, 158, 11, 0.25);
        }

        .quick-nav-bar {
            background: rgba(12, 14, 20, 0.95);
            border: 1px solid rgba(245, 158, 11, 0.20);
        }

        .quick-nav-scroll {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch !important;
            scrollbar-width: none;
            -ms-overflow-style: none;
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
            border: 1px solid rgba(245, 158, 11, 0.18);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
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

        /* Slideable Table Responsive System */
        .plan-table-responsive {
            display: block;
            width: 100% !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            -webkit-overflow-scrolling: touch !important;
            touch-action: pan-x pan-y !important;
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

        .plan-table-responsive::-webkit-scrollbar-thumb:hover {
            background: rgba(245, 158, 11, 0.7);
        }

        .plan-table {
            width: 100% !important;
            margin-bottom: 0 !important;
            background: transparent;
            border-collapse: collapse;
        }

        /* Minimum widths prevent mobile squishing and enable smooth sliding */
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

        .level-pill {
            background: rgba(239, 68, 68, 0.15);
            color: #FCA5A5;
            border: 1px solid rgba(239, 68, 68, 0.35);
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .stage-pill {
            background: rgba(245, 158, 11, 0.18);
            color: #fde68a;
            border: 1px solid rgba(245, 158, 11, 0.35);
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 6px;
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

        /* Mobile Viewport Tweaks */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
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
