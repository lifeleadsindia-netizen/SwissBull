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

@php
// 1. Members Data
$totalMembers = (int) totalMembersadmin();
$activeMembers = (int) totalActiveMembers();
$inactiveMembers = (int) totalTempMembers();
$blockedMembers = (int) totalblockedMembers();

$activePercent = $totalMembers > 0 ? round(($activeMembers / $totalMembers) * 100, 1) : 0;
$inactivePercent = $totalMembers > 0 ? round(($inactiveMembers / $totalMembers) * 100, 1) : 0;
$blockedPercent = $totalMembers > 0 ? round(($blockedMembers / $totalMembers) * 100, 1) : 0;

// 2. Wallets Data
$walletBalance = (float) totalWalletBalance();
$fundWalletBalance = (float) totalFundWalletBalance();
$pepeWalletBalance = (float) totalPEPEWalletBalance();
$trandingWalletBalance = (float) totalTradingWalletBalance();

// 3. Incomes Data
$totVal = (float) totalIn();
$monthlyTradingProfit = (float) totalAdminMonthlyTradingProfit();
$referralBonus = (float) totalAdminReferralBonus();
$teamTradingProfit = (float) totalAdminTeamTradingProfit();
$dailyTeamInvestmentShare = (float) totalAdminDailyTeamInvestmentShare();
$heroOfTheMonth = (float) totalAdminHeroOfTheMonthIncome();
$partnershipIncome = (float) totalAdminPartnershipIncome();

// 4. Dynamic Income Overview Chart Data
$chartData = adminIncomeOverviewChartData();
$groupCenters = [73, 130, 190, 250, 310, 370, 480];

// 5. Donut Segments Calculation
$circ = 301.6;
$incomeCategories = [
['name' => 'Monthly Trading Profit', 'val' => $monthlyTradingProfit, 'color' => '#3B82F6', 'url' => url('admin/income/monthly-trading-profit')],
['name' => 'Referral Bonus', 'val' => $referralBonus, 'color' => '#F97316', 'url' => url('admin/income/referral-bonus')],
['name' => 'Team Trading Profit', 'val' => $teamTradingProfit, 'color' => '#10B981', 'url' => url('admin/income/team-trading-profit')],
['name' => 'Daily Team Investment Share', 'val' => $dailyTeamInvestmentShare, 'color' => '#F59E0B', 'url' => url('admin/income/daily-team-investment-share')],
['name' => 'Hero of the Month', 'val' => $heroOfTheMonth, 'color' => '#8B5CF6', 'url' => url('admin/income/hero-of-the-month')],
['name' => 'Partnership Income', 'val' => $partnershipIncome, 'color' => '#EC4899', 'url' => url('admin/income/partnership-incomes')],
];

$donutOffset = 0;
foreach ($incomeCategories as &$cat) {
if ($totVal > 0 && $cat['val'] > 0) {
$cat['len'] = max(4, round(($cat['val'] / $totVal) * $circ, 1));
$cat['offset'] = $donutOffset;
$donutOffset += $cat['len'] + 3;
} else {
$cat['len'] = 0;
$cat['offset'] = 0;
}
}
unset($cat);
@endphp

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
                        <h2 class="adm-stat-num">{{ $totalMembers }}</h2>
                        <span class="adm-stat-badge">
                            <i class="ik ik-trending-up"></i> {{ $activePercent }}% Active
                        </span>
                    </div>
                    <div class="adm-stat-bars">
                        <span style="height: {{ max(4, min(18, round(($activeMembers / max(1, $totalMembers)) * 18))) }}px;"></span>
                        <span style="height: {{ max(4, min(18, round(($inactiveMembers / max(1, $totalMembers)) * 18))) }}px;"></span>
                        <span style="height: {{ max(4, min(18, round(($blockedMembers / max(1, $totalMembers)) * 18))) }}px;"></span>
                        <span style="height: 18px;"></span>
                    </div>
                </div>
                <!-- Hidden container to keep legacy chart script intact -->
                <div class="adm-legacy-chart-holder">
                    <div id="Widget-line-chart1"></div>
                </div>
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
                        <h2 class="adm-stat-num">{{ $activeMembers }}</h2>
                        <span class="adm-stat-badge">
                            <i class="ik ik-user-check"></i> {{ $activePercent }}%
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
                <div class="adm-legacy-chart-holder">
                    <div id="Widget-line-chart2"></div>
                </div>
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
                        <h2 class="adm-stat-num">{{ $inactiveMembers }}</h2>
                        <span class="adm-stat-badge">
                            <i class="ik ik-trending-down"></i> {{ $inactivePercent }}%
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
                <div class="adm-legacy-chart-holder">
                    <div id="Widget-line-chart3"></div>
                </div>
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
                        <h2 class="adm-stat-num">{{ $blockedMembers }}</h2>
                        <span class="adm-stat-badge">
                            <i class="ik ik-user-x"></i> {{ $blockedPercent }}%
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
                <div class="adm-legacy-chart-holder">
                    <div id="Widget-line-chart4"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. WALLET BALANCE CARDS (3 CARDS) -->
    <div class="row">
        <!-- Total Income Wallet Balance -->
        <div class="col-xl-6 col-lg-4 col-md-6 col-12">
            <div class="adm-wallet-card">
                <div class="adm-wallet-info">
                    <p class="adm-wallet-label">Total Income Wallet Balance</p>
                    <h3 class="adm-wallet-val">$ {{ number_format($walletBalance, 2) }}</h3>
                    <p class="adm-wallet-sub">Cumulative earnings balance</p>
                </div>
                <div class="adm-wallet-graphic">

                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#shadowBlue)">
                            <path d="M40 14L64 27L40 40L16 27L40 14Z" fill="#60A5FA" />
                            <path d="M16 27L40 40V50L16 37V27Z" fill="#2563EB" />
                            <path d="M40 40L64 27V37L40 50V40Z" fill="#3B82F6" />
                            <path d="M40 24L58 34L40 44L22 34L40 24Z" fill="#93C5FD" />
                            <path d="M22 34L40 44V54L22 44V34Z" fill="#1D4ED8" />
                            <path d="M40 44L58 34V44L40 54V44Z" fill="#2563EB" />
                            <path d="M40 34L52 41L40 48L28 41L40 34Z" fill="#BFDBFE" />
                            <path d="M28 41L40 48V58L28 51V41Z" fill="#1E40AF" />
                            <path d="M40 48L52 41V51L40 58V48Z" fill="#1D4ED8" />
                        </g>
                        <defs>
                            <filter id="shadowBlue" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#2563EB" flood-opacity="0.25" />
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Fund Wallet Balance -->
        <div class="col-xl-6 col-lg-4 col-md-6 col-12">
            <div class="adm-wallet-card">
                <div class="adm-wallet-info">
                    <p class="adm-wallet-label">Total Fund Wallet Balance</p>
                    <h3 class="adm-wallet-val">$ {{ number_format($fundWalletBalance, 2) }}</h3>
                    <p class="adm-wallet-sub">Deposit & working balance</p>
                </div>
                <div class="adm-wallet-graphic">

                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#shadowPurple)">
                            <path d="M40 15L65 28L40 41L15 28L40 15Z" fill="#C084FC" />
                            <path d="M15 28L40 41V58L15 45V28Z" fill="#7E22CE" />
                            <path d="M40 41L65 28V45L40 58V41Z" fill="#9333EA" />
                            <path d="M40 22L55 30L40 38L25 30L40 22Z" fill="#E9D5FF" />
                            <path d="M25 30L40 38V49L25 41V30Z" fill="#6B21A8" />
                            <path d="M40 38L55 30V41L40 49V38Z" fill="#7E22CE" />
                        </g>
                        <defs>
                            <filter id="shadowPurple" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#7E22CE" flood-opacity="0.25" />
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total PEPE Wallet Balance -->
        <div class="col-xl-6 col-lg-4 col-md-12 col-12">
            <div class="adm-wallet-card">
                <div class="adm-wallet-info">
                    <p class="adm-wallet-label">Total PEPE Wallet Balance</p>
                    <h3 class="adm-wallet-val">{{ number_format($pepeWalletBalance, 2) }} PEPE</h3>
                    <p class="adm-wallet-sub">Platform token reserves</p>
                </div>
                <div class="adm-wallet-graphic">
                    <!-- 3D Isometric Mint Green Coin Stack Illustration -->
                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#shadowGreen)">
                            <path d="M40 15L64 27L40 39L16 27L40 15Z" fill="#6EE7B7" />
                            <path d="M16 27L40 39V47L16 35V27Z" fill="#059669" />
                            <path d="M40 39L64 27V35L40 47V39Z" fill="#10B981" />
                            <path d="M40 25L64 37L40 49L16 37L40 25Z" fill="#A7F3D0" />
                            <path d="M16 37L40 49V57L16 45V37Z" fill="#047857" />
                            <path d="M40 49L64 37V45L40 57V49Z" fill="#059669" />
                            <ellipse cx="40" cy="25" rx="14" ry="7" fill="#D1FAE5" />
                        </g>
                        <defs>
                            <filter id="shadowGreen" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#059669" flood-opacity="0.25" />
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Trading Wallet Balance -->
        <div class="col-xl-6 col-lg-4 col-md-12 col-12">
            <div class="adm-wallet-card">
                <div class="adm-wallet-info">
                    <p class="adm-wallet-label">Total Trading Wallet Balance</p>
                    <h3 class="adm-wallet-val">{{ number_format($trandingWalletBalance, 2) }} USDT</h3>
                    <p class="adm-wallet-sub">Platform token reserves</p>
                </div>
                <div class="adm-wallet-graphic">
                    <!-- 3D Isometric Mint Green Coin Stack Illustration -->
                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#shadowGreen)">
                            <path d="M40 15L64 27L40 39L16 27L40 15Z" fill="#6EE7B7" />
                            <path d="M16 27L40 39V47L16 35V27Z" fill="#059669" />
                            <path d="M40 39L64 27V35L40 47V39Z" fill="#10B981" />
                            <path d="M40 25L64 37L40 49L16 37L40 25Z" fill="#A7F3D0" />
                            <path d="M16 37L40 49V57L16 45V37Z" fill="#047857" />
                            <path d="M40 49L64 37V45L40 57V49Z" fill="#059669" />
                            <ellipse cx="40" cy="25" rx="14" ry="7" fill="#D1FAE5" />
                        </g>
                        <defs>
                            <filter id="shadowGreen" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#059669" flood-opacity="0.25" />
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>
        </div>
    </div>


    <!-- 3. Income CARDS (3 CARDS) -->
    <!-- <div class="row"> -->
    <!-- Total Monthly Trading Profit -->
    <!-- <div class="col-xl-6 col-lg-4 col-md-6 col-12">
            <div class="adm-wallet-card">
                <div class="adm-wallet-info">
                    <p class="adm-wallet-label">Total Monthly Trading Profit</p>
                    <h3 class="adm-wallet-val">$ {{ number_format($monthlyTradingProfit, 2) }}</h3>
                    <p class="adm-wallet-sub">Monthly Trading Profit</p>
                </div>
                <div class="adm-wallet-graphic">
                   
                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#shadowBlue)">
                            <path d="M40 14L64 27L40 40L16 27L40 14Z" fill="#60A5FA" />
                            <path d="M16 27L40 40V50L16 37V27Z" fill="#2563EB" />
                            <path d="M40 40L64 27V37L40 50V40Z" fill="#3B82F6" />
                            <path d="M40 24L58 34L40 44L22 34L40 24Z" fill="#93C5FD" />
                            <path d="M22 34L40 44V54L22 44V34Z" fill="#1D4ED8" />
                            <path d="M40 44L58 34V44L40 54V44Z" fill="#2563EB" />
                            <path d="M40 34L52 41L40 48L28 41L40 34Z" fill="#BFDBFE" />
                            <path d="M28 41L40 48V58L28 51V41Z" fill="#1E40AF" />
                            <path d="M40 48L52 41V51L40 58V48Z" fill="#1D4ED8" />
                        </g>
                        <defs>
                            <filter id="shadowBlue" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#2563EB" flood-opacity="0.25" />
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>
        </div> -->

    <!-- Referral Bonus Income -->
    <!-- <div class="col-xl-6 col-lg-4 col-md-6 col-12">
            <div class="adm-wallet-card">
                <div class="adm-wallet-info">
                    <p class="adm-wallet-label">Referral Bonus Income</p>
                    <h3 class="adm-wallet-val">$ {{ number_format($referralBonus, 2) }}</h3>
                    <p class="adm-wallet-sub">Referral Bonus</p>
                </div>
                <div class="adm-wallet-graphic">
                   
                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#shadowPurple)">
                            <path d="M40 15L65 28L40 41L15 28L40 15Z" fill="#C084FC" />
                            <path d="M15 28L40 41V58L15 45V28Z" fill="#7E22CE" />
                            <path d="M40 41L65 28V45L40 58V41Z" fill="#9333EA" />
                            <path d="M40 22L55 30L40 38L25 30L40 22Z" fill="#E9D5FF" />
                            <path d="M25 30L40 38V49L25 41V30Z" fill="#6B21A8" />
                            <path d="M40 38L55 30V41L40 49V38Z" fill="#7E22CE" />
                        </g>
                        <defs>
                            <filter id="shadowPurple" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#7E22CE" flood-opacity="0.25" />
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>
        </div> -->

    <!-- Team Trading Profit -->
    <!-- <div class="col-xl-6 col-lg-4 col-md-12 col-12">
            <div class="adm-wallet-card">
                <div class="adm-wallet-info">
                    <p class="adm-wallet-label">Team Trading Profit</p>
                    <h3 class="adm-wallet-val">$ {{ number_format($teamTradingProfit, 2) }}</h3>
                    <p class="adm-wallet-sub">Team Trading Profit</p>
                </div>
                <div class="adm-wallet-graphic">
                   
                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#shadowGreen)">
                            <path d="M40 15L64 27L40 39L16 27L40 15Z" fill="#6EE7B7" />
                            <path d="M16 27L40 39V47L16 35V27Z" fill="#059669" />
                            <path d="M40 39L64 27V35L40 47V39Z" fill="#10B981" />
                            <path d="M40 25L64 37L40 49L16 37L40 25Z" fill="#A7F3D0" />
                            <path d="M16 37L40 49V57L16 45V37Z" fill="#047857" />
                            <path d="M40 49L64 37V45L40 57V49Z" fill="#059669" />
                            <ellipse cx="40" cy="25" rx="14" ry="7" fill="#D1FAE5" />
                        </g>
                        <defs>
                            <filter id="shadowGreen" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#059669" flood-opacity="0.25" />
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>
        </div> -->

    <!-- Daily Team Investment Income -->
    <!-- <div class="col-xl-6 col-lg-4 col-md-12 col-12">
            <div class="adm-wallet-card">
                <div class="adm-wallet-info">
                    <p class="adm-wallet-label">Daily Team Investment Income</p>
                    <h3 class="adm-wallet-val">$ {{ number_format($dailyTeamInvestmentShare, 2) }}</h3>
                    <p class="adm-wallet-sub">Team Investment Income</p>
                </div>
                <div class="adm-wallet-graphic">
                    
                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#shadowGreen)">
                            <path d="M40 15L64 27L40 39L16 27L40 15Z" fill="#6EE7B7" />
                            <path d="M16 27L40 39V47L16 35V27Z" fill="#059669" />
                            <path d="M40 39L64 27V35L40 47V39Z" fill="#10B981" />
                            <path d="M40 25L64 37L40 49L16 37L40 25Z" fill="#A7F3D0" />
                            <path d="M16 37L40 49V57L16 45V37Z" fill="#047857" />
                            <path d="M40 49L64 37V45L40 57V49Z" fill="#059669" />
                            <ellipse cx="40" cy="25" rx="14" ry="7" fill="#D1FAE5" />
                        </g>
                        <defs>
                            <filter id="shadowGreen" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#059669" flood-opacity="0.25" />
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>
        </div> -->

    <!-- Hero of the Month -->
    <!-- <div class="col-xl-6 col-lg-4 col-md-12 col-12">
            <div class="adm-wallet-card">
                <div class="adm-wallet-info">
                    <p class="adm-wallet-label">Hero of the Month</p>
                    <h3 class="adm-wallet-val">$ {{ number_format($heroOfTheMonth, 2) }}</h3>
                    <p class="adm-wallet-sub">Hero of the Month</p>
                </div>
                <div class="adm-wallet-graphic">
                    
                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#shadowGreen)">
                            <path d="M40 15L64 27L40 39L16 27L40 15Z" fill="#6EE7B7" />
                            <path d="M16 27L40 39V47L16 35V27Z" fill="#059669" />
                            <path d="M40 39L64 27V35L40 47V39Z" fill="#10B981" />
                            <path d="M40 25L64 37L40 49L16 37L40 25Z" fill="#A7F3D0" />
                            <path d="M16 37L40 49V57L16 45V37Z" fill="#047857" />
                            <path d="M40 49L64 37V45L40 57V49Z" fill="#059669" />
                            <ellipse cx="40" cy="25" rx="14" ry="7" fill="#D1FAE5" />
                        </g>
                        <defs>
                            <filter id="shadowGreen" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#059669" flood-opacity="0.25" />
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>
        </div> -->
    <!-- Partnership Income -->
    <!-- <div class="col-xl-6 col-lg-4 col-md-12 col-12">
            <div class="adm-wallet-card">
                <div class="adm-wallet-info">
                    <p class="adm-wallet-label">Partnership Income</p>
                    <h3 class="adm-wallet-val">$ {{ number_format($partnershipIncome, 2) }}</h3>
                    <p class="adm-wallet-sub">Partnership Income</p>
                </div>
                <div class="adm-wallet-graphic">
                   
                    <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#shadowGreen)">
                            <path d="M40 15L64 27L40 39L16 27L40 15Z" fill="#6EE7B7" />
                            <path d="M16 27L40 39V47L16 35V27Z" fill="#059669" />
                            <path d="M40 39L64 27V35L40 47V39Z" fill="#10B981" />
                            <path d="M40 25L64 37L40 49L16 37L40 25Z" fill="#A7F3D0" />
                            <path d="M16 37L40 49V57L16 45V37Z" fill="#047857" />
                            <path d="M40 49L64 37V45L40 57V49Z" fill="#059669" />
                            <ellipse cx="40" cy="25" rx="14" ry="7" fill="#D1FAE5" />
                        </g>
                        <defs>
                            <filter id="shadowGreen" x="10" y="10" width="60" height="55" filterUnits="userSpaceOnUse">
                                <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#059669" flood-opacity="0.25" />
                            </filter>
                        </defs>
                    </svg>
                </div>
            </div>
        </div> -->
    <!-- </div> -->

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

                <div class="adm-chart-wrap" style="position: relative;">
                    <!-- Floating Tooltip Badge Dynamically Positioned Above Peak Interval -->
                    @php
                    $peakPos = $groupCenters[$chartData['peakIndex']] ?? 310;
                    $peakPct = round(($peakPos / 650) * 100, 1);
                    $peakH = $chartData['hasData'] ? max(20, round(($chartData['peakAmount'] / $chartData['yMax']) * 140)) : 10;
                    $badgeTop = max(10, 185 - $peakH);
                    @endphp
                    <div class="adm-chart-floating-badge" style="left: calc({{ $peakPct }}% - 38px); top: {{ $badgeTop }}px;">
                        $ {{ number_format($chartData['peakAmount'], 2) }}
                        <span>{{ $chartData['peakLabel'] }}</span>
                    </div>

                    <!-- Pixel-Perfect SVG Bar Chart with 100% Dynamic Data -->
                    <svg class="adm-svg-chart" viewBox="0 0 650 230" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Horizontal Grid Lines & Dynamic Y-Axis Labels -->
                        <line x1="45" y1="20" x2="640" y2="20" stroke="#F1F5F9" stroke-dasharray="3 3" />
                        <text x="10" y="24" fill="#94A3B8" font-size="11" font-weight="600">{{ $chartData['yMax'] }}</text>

                        <line x1="45" y1="80" x2="640" y2="80" stroke="#F1F5F9" stroke-dasharray="3 3" />
                        <text x="10" y="84" fill="#94A3B8" font-size="11" font-weight="600">{{ $chartData['yMid'] }}</text>

                        <line x1="45" y1="140" x2="640" y2="140" stroke="#F1F5F9" stroke-dasharray="3 3" />
                        <text x="10" y="144" fill="#94A3B8" font-size="11" font-weight="600">{{ $chartData['yLow'] }}</text>

                        <line x1="45" y1="200" x2="640" y2="200" stroke="#E2E8F0" stroke-width="1.2" />
                        <text x="24" y="204" fill="#94A3B8" font-size="11" font-weight="600">0</text>

                        <!-- Gradient for Bars -->
                        <defs>
                            <linearGradient id="barGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#3B82F6" />
                                <stop offset="100%" stop-color="#60A5FA" />
                            </linearGradient>
                            <linearGradient id="barActiveGrad" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#1D4ED8" />
                                <stop offset="100%" stop-color="#3B82F6" />
                            </linearGradient>
                        </defs>

                        <!-- 7 Dynamic Bar Groups -->
                        @foreach ($chartData['groups'] as $idx => $grp)
                        @php
                        $cX = $groupCenters[$idx] ?? (70 + $idx * 60);
                        $b1 = (float)$grp['bar1'];
                        $b2 = (float)$grp['bar2'];
                        $b3 = (float)$grp['bar3'];
                        $isPeak = ($idx === $chartData['peakIndex'] && $chartData['hasData']);

                        // Dynamic bar heights
                        $h1 = $b1 > 0 ? max(6, min(140, round(($b1 / $chartData['yMax']) * 140))) : ($chartData['hasData'] ? 0 : 2);
                        $h2 = $b2 > 0 ? max(6, min(140, round(($b2 / $chartData['yMax']) * 140))) : ($chartData['hasData'] ? 0 : 2);
                        $h3 = $b3 > 0 ? max(6, min(140, round(($b3 / $chartData['yMax']) * 140))) : ($chartData['hasData'] ? 0 : 2);

                        if ($isPeak && $h2 < 18) {
                            $h2=max(18, min(140, round(($grp['total'] / $chartData['yMax']) * 140)));
                            }

                            $y1=200 - $h1;
                            $y2=200 - $h2;
                            $y3=200 - $h3;
                            @endphp

                            @if ($h1> 0)
                            <rect x="{{ $cX - 15 }}" y="{{ $y1 }}" width="8" height="{{ $h1 }}" rx="4" fill="url(#barGrad)" />
                            @endif

                            @if ($h2 > 0)
                            <rect x="{{ $cX - 4 }}" y="{{ $y2 }}" width="9" height="{{ $h2 }}" rx="4.5" fill="{{ $isPeak ? 'url(#barActiveGrad)' : '#93C5FD' }}" />
                            @endif

                            @if ($h3 > 0)
                            <rect x="{{ $cX + 8 }}" y="{{ $y3 }}" width="8" height="{{ $h3 }}" rx="4" fill="url(#barGrad)" />
                            @endif

                            <!-- X Axis Date Label -->
                            <text x="{{ $cX }}" y="218" fill="#94A3B8" font-size="11" font-weight="600" text-anchor="middle">{{ $grp['label'] }}</text>
                            @endforeach
                    </svg>
                </div>
            </div>
        </div>

        <!-- Right Side: Income Summary Donut & Breakdown -->
        <div class="col-xl-4 col-lg-4 col-12 mb-3">
            <div class="adm-income-panel">
                <div class="adm-panel-head">
                    <h3>Income Summary</h3>
                    <span class="badge bg-light text-muted px-2 py-1" style="font-size: 11px; font-weight: 600; border-radius: 6px;">Total: ${{ number_format($totVal, 2) }}</span>
                </div>

                <div class="adm-summary-content">
                    <!-- Dynamic Donut Chart -->
                    <div class="adm-donut-wrap">
                        <svg class="adm-donut-svg" viewBox="0 0 120 120">
                            <!-- Background Circle -->
                            <circle cx="60" cy="60" r="48" fill="transparent" stroke="#F1F5F9" stroke-width="8" />

                            @foreach ($incomeCategories as $cat)
                            @if ($cat['len'] > 0)
                            <circle cx="60" cy="60" r="48" fill="transparent" stroke="{{ $cat['color'] }}" stroke-width="8"
                                stroke-dasharray="{{ $cat['len'] }} 301.6" stroke-dashoffset="-{{ $cat['offset'] }}" stroke-linecap="round" />
                            @endif
                            @endforeach
                        </svg>
                        <div class="adm-donut-center">
                            <h4>$ {{ number_format($totVal, 2) }}</h4>
                            <span>Total Income</span>
                        </div>
                    </div>

                    <!-- 6 Incomes Dynamic Breakdown List -->
                    <ul class="adm-income-list">
                        @foreach ($incomeCategories as $cat)
                        <li>
                            <a href="{{ $cat['url'] }}" class="adm-income-item-link">
                                <div class="adm-income-item-left">
                                    <span class="adm-income-dot" style="background: {{ $cat['color'] }}; box-shadow: 0 0 6px {{ $cat['color'] }}66;"></span>
                                    <span class="adm-income-label">{{ $cat['name'] }}</span>
                                </div>
                                <div class="adm-income-item-right">
                                    <span class="adm-income-amount">$ {{ number_format((float) $cat['val'], 2) }}</span>
                                    <i class="ik ik-chevron-right adm-income-chevron"></i>
                                </div>
                            </a>
                        </li>
                        @endforeach
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
                            <h5 class="adm-strip-item-val text-success">{{ number_format((float) todayPEPEWithdrawal(), 2) }} PEPE</h5>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-6 mb-2 mb-xl-0">
                        <div class="adm-strip-item">
                            <p class="adm-strip-item-label">Total PEPE Withdrawal</p>
                            <h5 class="adm-strip-item-val text-danger">{{ number_format((float) totalPEPEWithdrawal(), 2) }} PEPE</h5>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-6">
                        <div class="adm-strip-item">
                            <p class="adm-strip-item-label">Today's Withdrawal</p>
                            <h5 class="adm-strip-item-val text-primary">$ {{ number_format((float) todaysWithdrawal(), 2) }}</h5>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-6">
                        <div class="adm-strip-item">
                            <p class="adm-strip-item-label">Total Withdrawal</p>
                            <h5 class="adm-strip-item-val text-danger">$ {{ number_format((float) totalWithdrawal(), 2) }}</h5>
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
                    <h3 class="adm-grad-val">$ {{ number_format((float) totalgrossAmount(), 2) }}</h3>
                    <p class="adm-grad-label">Withdrawal Gross Amount</p>
                </div>
                <div class="adm-grad-icon">
                    <i class="ik ik-trending-up"></i>
                </div>
                <!-- Decorative Wave Overlay -->
                <svg class="adm-grad-wave" viewBox="0 0 140 100" fill="none" preserveAspectRatio="none">
                    <path d="M0 60C40 30 80 80 140 40V100H0V60Z" fill="#ffffff" />
                    <path d="M20 75C60 55 100 85 140 60V100H20V75Z" fill="#ffffff" opacity="0.5" />
                </svg>
            </div>
        </div>

        <!-- Card 2: Withdrawal Net Amount (Ocean Blue Gradient) -->
        <div class="col-xl-4 col-lg-4 col-md-6 col-12">
            <div class="adm-gradient-card adm-card-blue">
                <div class="adm-grad-info">
                    <h3 class="adm-grad-val">$ {{ number_format((float) totalnetAmount(), 2) }}</h3>
                    <p class="adm-grad-label">Withdrawal Net Amount</p>
                </div>
                <div class="adm-grad-icon">
                    <i class="ik ik-trending-up"></i>
                </div>
                <!-- Decorative Wave Overlay -->
                <svg class="adm-grad-wave" viewBox="0 0 140 100" fill="none" preserveAspectRatio="none">
                    <path d="M0 60C40 30 80 80 140 40V100H0V60Z" fill="#ffffff" />
                    <path d="M20 75C60 55 100 85 140 60V100H20V75Z" fill="#ffffff" opacity="0.5" />
                </svg>
            </div>
        </div>

        <!-- Card 3: Deduction on Withdrawal (Emerald Green Gradient) -->
        <div class="col-xl-4 col-lg-4 col-md-12 col-12">
            <div class="adm-gradient-card adm-card-green">
                <div class="adm-grad-info">
                    <h3 class="adm-grad-val">$ {{ number_format((float) totaldeductions(), 2) }}</h3>
                    <p class="adm-grad-label">Deduction on Withdrawal</p>
                </div>
                <div class="adm-grad-icon">
                    <i class="ik ik-trending-up"></i>
                </div>
                <!-- Decorative Wave Overlay -->
                <svg class="adm-grad-wave" viewBox="0 0 140 100" fill="none" preserveAspectRatio="none">
                    <path d="M0 60C40 30 80 80 140 40V100H0V60Z" fill="#ffffff" />
                    <path d="M20 75C60 55 100 85 140 60V100H20V75Z" fill="#ffffff" opacity="0.5" />
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