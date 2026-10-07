@extends('admin.layouts.main')
@section('title', 'Dashboard')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/chartist/dist/chartist.min.css') }}">
    @endpush

    <div class="adm-dashboard-container">
        <!-- 1. DASHBOARD HEADER AREA -->
        <div class="adm-dash-header">
            <div>
                <h1 class="adm-dash-title">Admin Dashboard</h1>
                <p class="adm-dash-subtitle">Monitor your members, wallets and platform performance in real time.</p>
            </div>
            <div>
                <div class="adm-date-badge">
                    <i class="ik ik-calendar"></i>
                    <div class="adm-date-text">
                        <span class="adm-date-val">
                            {{ date('F d, Y') }} <span class="chevron">▾</span>
                        </span>
                        <span class="adm-date-sub">Today</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. TOP 4 STATISTICS CARDS -->
        <div class="row">
            <!-- Card 1: Total Members -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="adm-stat-card adm-stat-blue">
                    <div class="adm-stat-top">
                        <div class="adm-stat-title-wrap">
                            <span class="adm-stat-indicator"></span>
                            <h4 class="adm-stat-title">Total Members</h4>
                        </div>
                        <div class="adm-stat-icon">
                            <i class="ik ik-users"></i>
                        </div>
                    </div>
                    <div class="adm-stat-bottom">
                        <div>
                            <h2 class="adm-stat-num">{{ totalMembersadmin() }}</h2>
                            <span class="adm-stat-badge">
                                <i class="ik ik-trending-up"></i> + 12%
                            </span>
                        </div>
                        <div class="adm-stat-bars">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- Hidden container to keep legacy chart script intact -->
                    <div class="adm-legacy-chart-holder"><div id="Widget-line-chart1"></div></div>
                </div>
            </div>

            <!-- Card 2: Total Active -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="adm-stat-card adm-stat-cyan">
                    <div class="adm-stat-top">
                        <div class="adm-stat-title-wrap">
                            <span class="adm-stat-indicator"></span>
                            <h4 class="adm-stat-title">Total Active</h4>
                        </div>
                        <div class="adm-stat-icon">
                            <i class="ik ik-user-check"></i>
                        </div>
                    </div>
                    <div class="adm-stat-bottom">
                        <div>
                            <h2 class="adm-stat-num">{{ totalActiveMembers() }}</h2>
                            <span class="adm-stat-badge">Active Members</span>
                        </div>
                        <div class="adm-stat-bars">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- Hidden container to keep legacy chart script intact -->
                    <div class="adm-legacy-chart-holder"><div id="Widget-line-chart2"></div></div>
                </div>
            </div>

            <!-- Card 3: Total Inactive -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="adm-stat-card adm-stat-amber">
                    <div class="adm-stat-top">
                        <div class="adm-stat-title-wrap">
                            <span class="adm-stat-indicator"></span>
                            <h4 class="adm-stat-title">Total Inactive</h4>
                        </div>
                        <div class="adm-stat-icon">
                            <i class="ik ik-user-minus"></i>
                        </div>
                    </div>
                    <div class="adm-stat-bottom">
                        <div>
                            <h2 class="adm-stat-num">{{ totalTempMembers() }}</h2>
                            <span class="adm-stat-badge">
                                <i class="ik ik-trending-down"></i> - 22%
                            </span>
                        </div>
                        <div class="adm-stat-bars">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- Hidden container to keep legacy chart script intact -->
                    <div class="adm-legacy-chart-holder"><div id="Widget-line-chart3"></div></div>
                </div>
            </div>

            <!-- Card 4: Total Blocked -->
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="adm-stat-card adm-stat-red">
                    <div class="adm-stat-top">
                        <div class="adm-stat-title-wrap">
                            <span class="adm-stat-indicator"></span>
                            <h4 class="adm-stat-title">Total Blocked</h4>
                        </div>
                        <div class="adm-stat-icon">
                            <i class="ik ik-user-x"></i>
                        </div>
                    </div>
                    <div class="adm-stat-bottom">
                        <div>
                            <h2 class="adm-stat-num">{{ totalblockedMembers() }}</h2>
                            <span class="adm-stat-badge">0.0%</span>
                        </div>
                        <div class="adm-stat-bars">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- Hidden container to keep legacy chart script intact -->
                    <div class="adm-legacy-chart-holder"><div id="Widget-line-chart4"></div></div>
                </div>
            </div>
        </div>

        <!-- 3. WALLET BALANCE CARDS (3 CARDS) -->
        <div class="row">
            <!-- Total Income Wallet Balance -->
            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                <div class="adm-wallet-card">
                    <div class="adm-wallet-info">
                        <p class="adm-wallet-label">Total Income Wallet Balance</p>
                        <h3 class="adm-wallet-val">$ {{ number_format(totalWalletBalance(), 2) }}</h3>
                        <p class="adm-wallet-sub">Cumulative earnings balance</p>
                    </div>
                    <div class="adm-wallet-graphic">
                        <!-- 3D Isometric Blue Stack Illustration -->
                        <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#shadowBlue)">
                                <path d="M40 14L64 27L40 40L16 27L40 14Z" fill="#60A5FA"/>
                                <path d="M16 27L40 40V50L16 37V27Z" fill="#2563EB"/>
                                <path d="M40 40L64 27V37L40 50V40Z" fill="#3B82F6"/>
                                <path d="M40 24L58 34L40 44L22 34L40 24Z" fill="#93C5FD"/>
                                <path d="M22 34L40 44V54L22 44V34Z" fill="#1D4ED8"/>
                                <path d="M40 44L58 34V44L40 54V44Z" fill="#2563EB"/>
                                <path d="M40 34L52 41L40 48L28 41L40 34Z" fill="#BFDBFE"/>
                                <path d="M28 41L40 48V58L28 51V41Z" fill="#1E40AF"/>
                                <path d="M40 48L52 41V51L40 58V48Z" fill="#1D4ED8"/>
                            </g>
                            <defs>
                                <filter id="shadowBlue" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                    <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#2563EB" flood-opacity="0.25"/>
                                </filter>
                            </defs>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Fund Wallet Balance -->
            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                <div class="adm-wallet-card">
                    <div class="adm-wallet-info">
                        <p class="adm-wallet-label">Total Fund Wallet Balance</p>
                        <h3 class="adm-wallet-val">$ {{ number_format(totalFundWalletBalance(), 2) }}</h3>
                        <p class="adm-wallet-sub">Deposit & working balance</p>
                    </div>
                    <div class="adm-wallet-graphic">
                        <!-- 3D Isometric Purple Cube Illustration -->
                        <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#shadowPurple)">
                                <path d="M40 15L65 28L40 41L15 28L40 15Z" fill="#C084FC"/>
                                <path d="M15 28L40 41V58L15 45V28Z" fill="#7E22CE"/>
                                <path d="M40 41L65 28V45L40 58V41Z" fill="#9333EA"/>
                                <path d="M40 22L55 30L40 38L25 30L40 22Z" fill="#E9D5FF"/>
                                <path d="M25 30L40 38V49L25 41V30Z" fill="#6B21A8"/>
                                <path d="M40 38L55 30V41L40 49V38Z" fill="#7E22CE"/>
                            </g>
                            <defs>
                                <filter id="shadowPurple" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                    <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#7E22CE" flood-opacity="0.25"/>
                                </filter>
                            </defs>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total PEPE Wallet Balance -->
            <div class="col-xl-4 col-lg-4 col-md-12 col-12">
                <div class="adm-wallet-card">
                    <div class="adm-wallet-info">
                        <p class="adm-wallet-label">Total PEPE Wallet Balance</p>
                        <h3 class="adm-wallet-val">{{ totalPEPEWalletBalance() }} PEPE</h3>
                        <p class="adm-wallet-sub">Platform token reserves</p>
                    </div>
                    <div class="adm-wallet-graphic">
                        <!-- 3D Isometric Mint Green Coin Stack Illustration -->
                        <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#shadowGreen)">
                                <path d="M40 15L64 27L40 39L16 27L40 15Z" fill="#6EE7B7"/>
                                <path d="M16 27L40 39V47L16 35V27Z" fill="#059669"/>
                                <path d="M40 39L64 27V35L40 47V39Z" fill="#10B981"/>
                                <path d="M40 25L64 37L40 49L16 37L40 25Z" fill="#A7F3D0"/>
                                <path d="M16 37L40 49V57L16 45V37Z" fill="#047857"/>
                                <path d="M40 49L64 37V45L40 57V49Z" fill="#059669"/>
                                <ellipse cx="40" cy="25" rx="14" ry="7" fill="#D1FAE5"/>
                            </g>
                            <defs>
                                <filter id="shadowGreen" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                    <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#059669" flood-opacity="0.25"/>
                                </filter>
                            </defs>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. INCOME OVERVIEW (SIDE-BY-SIDE: BAR CHART & DONUT SUMMARY) -->
        <div class="row">
            <!-- Left Side: Total Income Overview Bar Chart -->
            <div class="col-xl-8 col-lg-8 col-12 mb-3">
                <div class="adm-income-panel">
                    <div class="adm-panel-head">
                        <h3>Total Income Overview</h3>
                        <div class="adm-filter-btn">
                            <span>This Month</span>
                            <span>▾</span>
                        </div>
                    </div>

                    <div class="adm-chart-wrap">
                        <!-- Floating Tooltip Badge Matching Reference -->
                        <div class="adm-chart-floating-badge">
                            $ 210.50
                            <span>19 Oct</span>
                        </div>

                        <!-- Pixel-Perfect SVG Bar Chart matching Reference Image -->
                        <svg class="adm-svg-chart" viewBox="0 0 650 230" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Horizontal Grid Lines -->
                            <line x1="45" y1="20" x2="640" y2="20" stroke="#F1F5F9" stroke-dasharray="3 3"/>
                            <text x="10" y="24" fill="#94A3B8" font-size="11" font-weight="600">300</text>

                            <line x1="45" y1="80" x2="640" y2="80" stroke="#F1F5F9" stroke-dasharray="3 3"/>
                            <text x="10" y="84" fill="#94A3B8" font-size="11" font-weight="600">200</text>

                            <line x1="45" y1="140" x2="640" y2="140" stroke="#F1F5F9" stroke-dasharray="3 3"/>
                            <text x="10" y="144" fill="#94A3B8" font-size="11" font-weight="600">100</text>

                            <line x1="45" y1="200" x2="640" y2="200" stroke="#E2E8F0" stroke-width="1.2"/>
                            <text x="24" y="204" fill="#94A3B8" font-size="11" font-weight="600">0</text>

                            <!-- Gradient for Bars -->
                            <defs>
                                <linearGradient id="barGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#3B82F6"/>
                                    <stop offset="100%" stop-color="#60A5FA"/>
                                </linearGradient>
                                <linearGradient id="barActiveGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#1D4ED8"/>
                                    <stop offset="100%" stop-color="#3B82F6"/>
                                </linearGradient>
                            </defs>

                            <!-- Bar Group 1 (1 Oct) -->
                            <rect x="58" y="165" width="8" height="35" rx="4" fill="url(#barGrad)"/>
                            <rect x="74" y="172" width="8" height="28" rx="4" fill="#93C5FD"/>
                            <rect x="88" y="152" width="8" height="48" rx="4" fill="url(#barGrad)"/>

                            <!-- Bar Group 2 (5 Oct) -->
                            <rect x="115" y="160" width="8" height="40" rx="4" fill="#93C5FD"/>
                            <rect x="130" y="130" width="8" height="70" rx="4" fill="url(#barGrad)"/>
                            <rect x="145" y="150" width="8" height="50" rx="4" fill="#93C5FD"/>

                            <!-- Bar Group 3 (10 Oct) -->
                            <rect x="175" y="135" width="8" height="65" rx="4" fill="url(#barGrad)"/>
                            <rect x="190" y="168" width="8" height="32" rx="4" fill="#93C5FD"/>
                            <rect x="205" y="142" width="8" height="58" rx="4" fill="url(#barGrad)"/>

                            <!-- Bar Group 4 (15 Oct) -->
                            <rect x="235" y="118" width="8" height="82" rx="4" fill="url(#barGrad)"/>
                            <rect x="250" y="155" width="8" height="45" rx="4" fill="#93C5FD"/>
                            <rect x="265" y="140" width="8" height="60" rx="4" fill="url(#barGrad)"/>

                            <!-- Bar Group 5 (20 Oct - Peak Highlighted Group) -->
                            <rect x="295" y="80" width="8" height="120" rx="4" fill="url(#barGrad)"/>
                            <!-- Peak Bar with Tooltip above it -->
                            <rect x="310" y="60" width="9" height="140" rx="4.5" fill="url(#barActiveGrad)"/>
                            <rect x="326" y="95" width="8" height="105" rx="4" fill="url(#barGrad)"/>

                            <!-- Bar Group 6 (25 Oct) -->
                            <rect x="355" y="105" width="8" height="95" rx="4" fill="url(#barGrad)"/>
                            <rect x="370" y="125" width="8" height="75" rx="4" fill="#93C5FD"/>
                            <rect x="385" y="148" width="8" height="52" rx="4" fill="url(#barGrad)"/>

                            <!-- Bar Group 7 (31 Oct) -->
                            <rect x="415" y="142" width="8" height="58" rx="4" fill="#93C5FD"/>
                            <rect x="430" y="130" width="8" height="70" rx="4" fill="url(#barGrad)"/>
                            <rect x="445" y="155" width="8" height="45" rx="4" fill="#93C5FD"/>

                            <rect x="475" y="138" width="8" height="62" rx="4" fill="url(#barGrad)"/>
                            <rect x="490" y="148" width="8" height="52" rx="4" fill="#93C5FD"/>
                            <rect x="505" y="122" width="8" height="78" rx="4" fill="url(#barGrad)"/>

                            <!-- X Axis Date Labels -->
                            <text x="65" y="218" fill="#94A3B8" font-size="11" font-weight="600">1 Oct</text>
                            <text x="132" y="218" fill="#94A3B8" font-size="11" font-weight="600">5 Oct</text>
                            <text x="195" y="218" fill="#94A3B8" font-size="11" font-weight="600">10 Oct</text>
                            <text x="255" y="218" fill="#94A3B8" font-size="11" font-weight="600">15 Oct</text>
                            <text x="312" y="218" fill="#94A3B8" font-size="11" font-weight="600">20 Oct</text>
                            <text x="375" y="218" fill="#94A3B8" font-size="11" font-weight="600">25 Oct</text>
                            <text x="475" y="218" fill="#94A3B8" font-size="11" font-weight="600">31 Oct</text>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Right Side: Income Summary Donut & Breakdown -->
            <div class="col-xl-4 col-lg-4 col-12 mb-3">
                <div class="adm-income-panel">
                    <div class="adm-panel-head">
                        <h3>Income Summary</h3>
                    </div>

                    <div class="adm-summary-content">
                        <!-- Donut Chart -->
                        <div class="adm-donut-wrap">
                            <svg class="adm-donut-svg" viewBox="0 0 100 100">
                                <!-- Background Circle -->
                                <circle cx="50" cy="50" r="38" fill="transparent" stroke="#F1F5F9" stroke-width="12"/>
                                <!-- Segment 1: Blue (Level Income) -->
                                <circle cx="50" cy="50" r="38" fill="transparent" stroke="#3B82F6" stroke-width="12"
                                    stroke-dasharray="145 238" stroke-dashoffset="0" stroke-linecap="round"/>
                                <!-- Segment 2: Orange (Staking Level) -->
                                <circle cx="50" cy="50" r="38" fill="transparent" stroke="#F97316" stroke-width="12"
                                    stroke-dasharray="55 238" stroke-dashoffset="-148" stroke-linecap="round"/>
                                <!-- Segment 3: Cyan (Single Leg) -->
                                <circle cx="50" cy="50" r="38" fill="transparent" stroke="#06B6D4" stroke-width="12"
                                    stroke-dasharray="25 238" stroke-dashoffset="-206" stroke-linecap="round"/>
                            </svg>
                            <div class="adm-donut-center">
                                <h4>$ {{ number_format((float) totalIn(), 2) }}</h4>
                                <span>Total Income</span>
                            </div>
                        </div>

                        <!-- Income Categories Breakdown matching Reference -->
                        <ul class="adm-income-list">
                            <li>
                                <a href="{{ url('admin/income/roi-incomes') }}" class="adm-income-item-left">
                                    <span class="adm-income-dot" style="background: #0ea5e9;"></span>
                                    <span>ROI Income</span>
                                </a>
                                <a href="{{ url('admin/income/roi-incomes') }}" class="adm-income-amount">
                                    $ {{ number_format((float) totalAdminRoiIncome(), 2) }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/income/staking-level-incomes') }}" class="adm-income-item-left">
                                    <span class="adm-income-dot" style="background: #f97316;"></span>
                                    <span>Staking Level</span>
                                </a>
                                <a href="{{ url('admin/income/staking-level-incomes') }}" class="adm-income-amount">
                                    $ {{ number_format((float) totalAdminStakingLevelIncome(), 2) }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/income/level-incomes') }}" class="adm-income-item-left">
                                    <span class="adm-income-dot" style="background: #eab308;"></span>
                                    <span>Level Income</span>
                                </a>
                                <a href="{{ url('admin/income/level-incomes') }}" class="adm-income-amount">
                                    $ {{ number_format((float) totalAdminLevelIncome(), 2) }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/income/single-leg-incomes') }}" class="adm-income-item-left">
                                    <span class="adm-income-dot" style="background: #10b981;"></span>
                                    <span>Single Leg</span>
                                </a>
                                <a href="{{ url('admin/income/single-leg-incomes') }}" class="adm-income-amount">
                                    $ {{ number_format((float) totalAdminSingleLegIncome(), 2) }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/income/partnership-incomes') }}" class="adm-income-item-left">
                                    <span class="adm-income-dot" style="background: #a855f7;"></span>
                                    <span>Partnership</span>
                                </a>
                                <a href="{{ url('admin/income/partnership-incomes') }}" class="adm-income-amount">
                                    $ {{ number_format((float) totalAdminPartnershipIncome(), 2) }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('admin/income/team-withdrawal-commission-incomes') }}" class="adm-income-item-left">
                                    <span class="adm-income-dot" style="background: #ef4444;"></span>
                                    <span>Team Commission</span>
                                </a>
                                <a href="{{ url('admin/income/team-withdrawal-commission-incomes') }}" class="adm-income-amount">
                                    $ {{ number_format((float) totalAdminTeamWithdrawalCommissionIncome(), 2) }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. SECONDARY TOKEN & WITHDRAWAL ACTIVITY SUMMARY STRIP -->
        <div class="row">
            <div class="col-12">
                <div class="adm-activity-strip">
                    <p class="adm-strip-title">Platform Token & Withdrawal Live Metrics</p>
                    <div class="row">
                        <div class="col-xl-3 col-md-6 col-6 mb-2 mb-xl-0">
                            <div class="adm-strip-item">
                                <p class="adm-strip-item-label">Today PEPE Withdrawal</p>
                                <h5 class="adm-strip-item-val text-success">{{ todayPEPEWithdrawal() }} PEPE</h5>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 col-6 mb-2 mb-xl-0">
                            <div class="adm-strip-item">
                                <p class="adm-strip-item-label">Total PEPE Withdrawal</p>
                                <h5 class="adm-strip-item-val text-danger">{{ totalPEPEWithdrawal() }} PEPE</h5>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 col-6">
                            <div class="adm-strip-item">
                                <p class="adm-strip-item-label">Today's Withdrawal</p>
                                <h5 class="adm-strip-item-val text-primary">$ {{ todaysWithdrawal() }}</h5>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 col-6">
                            <div class="adm-strip-item">
                                <p class="adm-strip-item-label">Total Withdrawal</p>
                                <h5 class="adm-strip-item-val text-danger">$ {{ totalWithdrawal() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. BOTTOM THREE WITHDRAWAL SUMMARY CARDS (GRADIENTS & WAVES) -->
        <div class="row">
            <!-- Card 1: Withdrawal Gross Amount (Purple Gradient) -->
            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                <div class="adm-gradient-card adm-card-purple">
                    <div class="adm-grad-info">
                        <h3 class="adm-grad-val">$ {{ totalgrossAmount() }}</h3>
                        <p class="adm-grad-label">Withdrawal Gross Amount</p>
                    </div>
                    <div class="adm-grad-icon">
                        <i class="ik ik-trending-up"></i>
                    </div>
                    <!-- Decorative Wave Overlay -->
                    <svg class="adm-grad-wave" viewBox="0 0 140 100" fill="none" preserveAspectRatio="none">
                        <path d="M0 60C40 30 80 80 140 40V100H0V60Z" fill="#ffffff"/>
                        <path d="M20 75C60 55 100 85 140 60V100H20V75Z" fill="#ffffff" opacity="0.5"/>
                    </svg>
                </div>
            </div>

            <!-- Card 2: Withdrawal Net Amount (Ocean Blue Gradient) -->
            <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                <div class="adm-gradient-card adm-card-blue">
                    <div class="adm-grad-info">
                        <h3 class="adm-grad-val">$ {{ totalnetAmount() }}</h3>
                        <p class="adm-grad-label">Withdrawal Net Amount</p>
                    </div>
                    <div class="adm-grad-icon">
                        <i class="ik ik-trending-up"></i>
                    </div>
                    <!-- Decorative Wave Overlay -->
                    <svg class="adm-grad-wave" viewBox="0 0 140 100" fill="none" preserveAspectRatio="none">
                        <path d="M0 60C40 30 80 80 140 40V100H0V60Z" fill="#ffffff"/>
                        <path d="M20 75C60 55 100 85 140 60V100H20V75Z" fill="#ffffff" opacity="0.5"/>
                    </svg>
                </div>
            </div>

            <!-- Card 3: Deduction on Withdrawal (Emerald Green Gradient) -->
            <div class="col-xl-4 col-lg-4 col-md-12 col-12">
                <div class="adm-gradient-card adm-card-green">
                    <div class="adm-grad-info">
                        <h3 class="adm-grad-val">$ {{ totaldeductions() }}</h3>
                        <p class="adm-grad-label">Deduction on Withdrawal</p>
                    </div>
                    <div class="adm-grad-icon">
                        <i class="ik ik-trending-up"></i>
                    </div>
                    <!-- Decorative Wave Overlay -->
                    <svg class="adm-grad-wave" viewBox="0 0 140 100" fill="none" preserveAspectRatio="none">
                        <path d="M0 60C40 30 80 80 140 40V100H0V60Z" fill="#ffffff"/>
                        <path d="M20 75C60 55 100 85 140 60V100H20V75Z" fill="#ffffff" opacity="0.5"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- push external js -->
    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/plugins/chartist/dist/chartist.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/plugins/flot-charts/jquery.flot.js') }}"></script>
        <script src="{{ asset('plugins/flot-charts/curvedLines.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/plugins/flot-charts/jquery.flot.tooltip.min.js') }}"></script>

        <script src="{{ asset('adm_assets/assets/plugins/amcharts/amcharts.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/plugins/amcharts/serial.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/plugins/amcharts/themes/light.js') }}"></script>

        <script src="{{ asset('adm_assets/assets/js/widget-statistic.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/widget-data.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/dashboard-charts.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/widgets.js') }}"></script>
    @endpush
@endsection