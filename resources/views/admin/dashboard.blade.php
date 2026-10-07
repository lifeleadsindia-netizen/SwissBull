@extends('admin.layouts.main')
@section('title', 'Dashboard')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/weather-icons/css/weather-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/owl.carousel/dist/assets/owl.carousel.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('adm_assets/assets/plugins/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/chartist/dist/chartist.min.css') }}">
        <style>
            .order-usdt-section {
                border: 0;
                overflow: hidden;
                border-radius: 14px;
                box-shadow: 0 14px 36px rgba(18, 38, 63, 0.12);
            }

            .order-usdt-header {
                background:
                    radial-gradient(circle at right top, rgba(255, 255, 255, 0.22), transparent 38%),
                    linear-gradient(125deg, #0b365d 0%, #126a83 54%, #1f8a70 100%);
                color: #fff;
                border-bottom: 0;
                padding: 20px 22px;
            }

            .order-usdt-header h3 {
                color: #fff;
                font-size: 22px;
                font-weight: 700;
                letter-spacing: 0.2px;
            }

            .order-usdt-subtitle {
                color: rgba(255, 255, 255, 0.9);
                font-size: 13px;
                margin: 4px 0 0;
            }

            .order-usdt-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                white-space: nowrap;
                line-height: 1;
                border-radius: 999px;
                padding: 9px 16px;
                font-weight: 700;
                border: 1px solid rgba(255, 255, 255, 0.35);
                color: #fff;
                background: linear-gradient(135deg, #ff9f43, #ff6b6b);
                box-shadow: 0 8px 18px rgba(255, 107, 107, 0.32);
                transition: all 0.2s ease;
            }

            .order-usdt-btn i {
                font-size: 14px;
                margin-left: 0;
                transition: transform 0.2s ease;
            }

            .order-usdt-btn:hover {
                color: #fff;
                transform: translateY(-1px);
                box-shadow: 0 12px 22px rgba(255, 107, 107, 0.38);
            }

            .order-usdt-btn:focus,
            .order-usdt-btn:active {
                color: #fff;
                box-shadow: 0 0 0 0.2rem rgba(255, 159, 67, 0.28);
            }

            .order-usdt-btn:hover i {
                transform: translateX(2px);
            }

            .order-usdt-grid {
                background: linear-gradient(180deg, #f7fbff, #ffffff);
                border-top: 1px solid #edf3f9;
            }

            .order-usdt-stat {
                border-radius: 12px;
                padding: 16px;
                background: #fff;
                border: 1px solid #eaf0f7;
                height: 100%;
                position: relative;
                overflow: hidden;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .order-usdt-stat::after {
                content: "";
                position: absolute;
                left: 0;
                bottom: 0;
                width: 100%;
                height: 3px;
            }

            .order-usdt-stat:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 22px rgba(18, 38, 63, 0.11);
            }

            .order-usdt-label {
                color: #738092;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.7px;
                margin-bottom: 8px;
            }

            .order-usdt-value {
                font-size: 28px;
                line-height: 1;
                font-weight: 700;
                margin: 0;
                color: #1f2d3d;
            }

            .order-usdt-icon {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                margin-bottom: 12px;
            }

            .order-usdt-total .order-usdt-icon {
                background: rgba(15, 76, 129, 0.12);
                color: #0f4c81;
            }

            .order-usdt-total::after {
                background: #0f4c81;
            }

            .order-usdt-allowed .order-usdt-icon {
                background: rgba(23, 162, 95, 0.12);
                color: #17a25f;
            }

            .order-usdt-allowed::after {
                background: #17a25f;
            }

            .order-usdt-other .order-usdt-icon {
                background: rgba(240, 173, 78, 0.16);
                color: #d4942f;
            }

            .order-usdt-other::after {
                background: #f0ad4e;
            }

            .order-usdt-amount .order-usdt-icon {
                background: rgba(111, 66, 193, 0.13);
                color: #6f42c1;
            }

            .order-usdt-amount::after {
                background: #6f42c1;
            }

            .order-usdt-meta {
                border: 1px solid #e6edf5;
                border-radius: 12px;
                padding: 14px 16px;
                background: #fff;
            }

            .order-usdt-meta-top {
                font-size: 12px;
                font-weight: 600;
                color: #607086;
                margin-bottom: 10px;
            }

            .order-usdt-progress {
                height: 8px;
                background: #edf2f8;
                border-radius: 999px;
                overflow: hidden;
            }

            .order-usdt-progress-allowed {
                background: linear-gradient(90deg, #1abc76, #109b61);
                height: 100%;
                float: left;
            }

            .order-usdt-progress-other {
                background: linear-gradient(90deg, #f3b450, #df9931);
                height: 100%;
                float: left;
            }

            .order-usdt-legend {
                font-size: 12px;
                color: #6d7787;
                margin-top: 10px;
            }

            /* Pool Cards Styling */
            .pool-card {
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
                border: 2px solid rgba(0, 255, 156, 0.2);
                border-radius: 12px;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .pool-card::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #00ff9c, #007bff);
            }

            .pool-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
            }

            .pool-card-primary {
                border-left: 5px solid #007bff;
            }

            .pool-card-success {
                border-left: 5px solid #00ff9c;
            }

            .pool-card-danger {
                border-left: 5px solid #ff6b6b;
            }

            .pool-card-value {
                font-weight: 700;
                margin-bottom: 0.5rem;
                font-size: 1.8rem;
            }

            .pool-value-total {
                color: #007bff;
            }

            .pool-value-active {
                color: #00ff9c;
            }

            .pool-value-inactive {
                color: #ff6b6b;
            }

            .pool-card-label {
                color: #666;
                font-size: 0.95rem;
                font-weight: 600;
                margin: 0;
            }

            .pool-card-icon {
                font-size: 2.5rem;
                opacity: 0.8;
                transition: all 0.3s ease;
            }

            .pool-icon-primary {
                color: #007bff;
            }

            .pool-icon-success {
                color: #00ff9c;
            }

            .pool-icon-danger {
                color: #ff6b6b;
            }

            .pool-card:hover .pool-card-icon {
                opacity: 1;
                transform: scale(1.1);
            }

            .commission-overview-card {
                border: 0;
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 14px 36px rgba(18, 38, 63, 0.12);
                background: #fff;
            }

            .commission-overview-header {
                background:
                    radial-gradient(circle at right top, rgba(255, 255, 255, 0.2), transparent 40%),
                    linear-gradient(130deg, #213a75 0%, #265d9b 55%, #2c86b8 100%);
                padding: 18px 22px;
                color: #fff;
            }

            .commission-overview-header h3 {
                margin: 0;
                color: #fff;
                font-size: 22px;
                font-weight: 700;
            }

            .commission-overview-subtitle {
                margin: 4px 0 0;
                font-size: 13px;
                color: rgba(255, 255, 255, 0.88);
            }

            .commission-overview-body {
                background: linear-gradient(180deg, #f7fbff, #ffffff);
                border-top: 1px solid #e9f1fa;
                padding: 16px;
            }

            .commission-metric {
                display: block;
                border: 1px solid #e7eff8;
                border-radius: 12px;
                background: #fff;
                padding: 16px;
                text-decoration: none !important;
                position: relative;
                overflow: hidden;
                height: 100%;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .commission-metric:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 22px rgba(18, 38, 63, 0.11);
            }

            .commission-metric::after {
                content: "";
                position: absolute;
                left: 0;
                bottom: 0;
                width: 100%;
                height: 3px;
            }

            .commission-metric-icon {
                width: 44px;
                height: 44px;
                border-radius: 12px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 18px;
                margin-bottom: 10px;
            }

            .commission-metric-label {
                font-size: 12px;
                color: #6f7f92;
                text-transform: uppercase;
                font-weight: 700;
                letter-spacing: 0.7px;
                margin-bottom: 8px;
            }

            .commission-metric-value {
                font-size: 28px;
                line-height: 1;
                font-weight: 700;
                margin: 0;
                color: #1f2d3d;
            }

            .commission-metric-link {
                margin-top: 8px;
                display: inline-block;
                font-size: 12px;
                font-weight: 600;
                color: #75859b;
            }

            .commission-metric-deposit .commission-metric-icon {
                background: rgba(42, 119, 242, 0.14);
                color: #2a77f2;
            }

            .commission-metric-deposit::after {
                background: #2a77f2;
            }

            .commission-metric-withdrawal .commission-metric-icon {
                background: rgba(230, 85, 98, 0.14);
                color: #e65562;
            }

            .commission-metric-withdrawal::after {
                background: #e65562;
            }

            .commission-metric-advertisement .commission-metric-icon {
                background: rgba(36, 176, 111, 0.15);
                color: #24b06f;
            }

            .commission-metric-advertisement::after {
                background: #24b06f;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="row">
            <!-- page statustic chart start -->

            <div class="col-xl-3 col-md-6">
                <div class="card card-red text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ totalMembersadmin() }}</h4>
                                <p class="mb-0">Total Members</p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-shopping-cart f-30"></i>
                            </div>
                        </div>
                        <div id="Widget-line-chart1" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ totalActiveMembers() }}</h4>
                                <p class="mb-0">Total Active </p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="ik ik-shopping-cart f-30"></i>
                            </div>
                        </div>
                        <div id="Widget-line-chart2" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-green text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h4 class="mb-0">{{ totalTempMembers() }}</h4>
                                <p class="mb-0">Total Inactive </p>
                            </div>
                            <div class="col-4 text-right">
                                <i class="fas fa-cube f-30"></i>
                            </div>
                        </div>
                        <div id="Widget-line-chart3" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-yellow text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h4 class="mb-0">{{ totalblockedMembers() }}</h4>
                                <p class="mb-0">Total Blocked</p>
                            </div>
                            <div class="col-3 text-right">
                                <i class="ik f-30">৳</i>
                            </div>
                        </div>
                        <div id="Widget-line-chart4" class="chart-line chart-shadow"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total Income Wallet Balance</h6>
                                <h5 class="pt-2">$ {{number_format( totalWalletBalance(), 2) }}</h5>
                            </div>
                            <div class="icon">
                                <i class="ik ik-mail"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total Fund Wallet Balance</h6>
                                <h5 class="pt-2">$ {{ number_format(totalFundWalletBalance(), 2) }}</h5>
                            </div>
                            <div class="icon">
                                <i class="ik ik-server"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total PEPE Wallet Balance</h6>
                                <h5 class="pt-2"> {{ totalPEPEWalletBalance() }} PEPE</h5>
                            </div>
                            <div class="icon">
                                <i class="ik ik-server"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-------White Section-------->



            <div class="col-12 mt-2">
                <div class="card commission-overview-card">
                    <div
                        class="card-header commission-overview-header d-flex justify-content-between align-items-center flex-wrap" style="padding: 10px 20px; background-color: #1e1e2d;">
                        <h3 class="mb-0 text-white">Total Income Overview</h3>
                        <div class="text-right text-end">
                            <!--<span class="d-block"-->
                            <!--    style="font-size: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;">Total-->
                            <!--    Income</span>-->
                            <h3 class="mb-0 text-white">$ {{ number_format((float) totalIn(), 2) }}</h3>
                        </div>
                    </div>
                    <div class="card-body commission-overview-body" style="padding: 0px 20px;">
                        <div class="row">
                            <!-- 1. Monthly Trading Profit -->
                            <div class="col-xl-4 col-md-6 mb-3">
                                <a href="{{ url('admin/income/monthly-trading-profit') }}"
                                    class="commission-metric commission-metric-deposit">
                                    <span class="commission-metric-icon"><i class="ik ik-trending-up"></i></span>
                                    <p class="commission-metric-label">Monthly Trading Profit</p>
                                    <h4 class="commission-metric-value">
                                        $ {{ number_format((float) totalAdminRoiIncome(), 2) }}</h4>
                                    <span class="commission-metric-link">View Details <i
                                            class="ik ik-arrow-right"></i></span>
                                </a>
                            </div>

                            <!-- 2. Referral Bonus -->
                            <div class="col-xl-4 col-md-6 mb-3">
                                <a href="{{ url('admin/income/referral-bonus') }}"
                                    class="commission-metric commission-metric-advertisement">
                                    <span class="commission-metric-icon"><i class="ik ik-user-check"></i></span>
                                    <p class="commission-metric-label">Referral Bonus</p>
                                    <h4 class="commission-metric-value">
                                        $ {{ number_format((float) totalAdminDirectIncome(), 2) }}</h4>
                                    <span class="commission-metric-link">View Details <i
                                            class="ik ik-arrow-right"></i></span>
                                </a>
                            </div>

                            <!-- 3. Team Trading Profit -->
                            <div class="col-xl-4 col-md-6 mb-3">
                                <a href="{{ url('admin/income/team-trading-profit') }}"
                                    class="commission-metric commission-metric-withdrawal">
                                    <span class="commission-metric-icon"><i class="ik ik-layers"></i></span>
                                    <p class="commission-metric-label">Team Trading Profit</p>
                                    <h4 class="commission-metric-value">
                                        $ {{ number_format((float) totalAdminStakingLevelIncome(), 2) }}</h4>
                                    <span class="commission-metric-link">View Details <i
                                            class="ik ik-arrow-right"></i></span>
                                </a>
                            </div>

                            <!-- 4. Daily Team Investment Share -->
                            <div class="col-xl-4 col-md-6 mb-3">
                                <a href="{{ url('admin/income/daily-team-investment-share') }}"
                                    class="commission-metric commission-metric-deposit">
                                    <span class="commission-metric-icon"><i class="ik ik-bar-chart-2"></i></span>
                                    <p class="commission-metric-label">Daily Team Investment Share</p>
                                    <h4 class="commission-metric-value">
                                        $ {{ number_format((float) totalAdminLevelIncome(), 2) }}</h4>
                                    <span class="commission-metric-link">View Details <i
                                            class="ik ik-arrow-right"></i></span>
                                </a>
                            </div>

                            <!-- 5. Hero of the Month -->
                            <div class="col-xl-4 col-md-6 mb-3">
                                <a href="{{ url('admin/income/hero-of-the-month') }}"
                                    class="commission-metric commission-metric-advertisement">
                                    <span class="commission-metric-icon"><i class="ik ik-award"></i></span>
                                    <p class="commission-metric-label">Hero of the Month</p>
                                    <h4 class="commission-metric-value">
                                        $ {{ number_format((float) totalAdminHeroOfTheMonthIncome(), 2) }}</h4>
                                    <span class="commission-metric-link">View Details <i
                                            class="ik ik-arrow-right"></i></span>
                                </a>
                            </div>

                            <!-- 6. Partnership Income -->
                            <div class="col-xl-4 col-md-6 mb-3">
                                <a href="{{ url('admin/income/partnership-incomes') }}"
                                    class="commission-metric commission-metric-withdrawal">
                                    <span class="commission-metric-icon"><i class="ik ik-briefcase"></i></span>
                                    <p class="commission-metric-label">Partnership Income</p>
                                    <h4 class="commission-metric-value">
                                        $ {{ number_format((float) totalAdminPartnershipIncome(), 2) }}</h4>
                                    <span class="commission-metric-link">View Details <i
                                            class="ik ik-arrow-right"></i></span>
                                </a>
                            </div>

                            @if (totalAdminSingleLegIncome() > 0)
                                <div class="col-xl-6 col-md-6 mb-3">
                                    <a href="{{ url('admin/income/single-leg-incomes') }}"
                                        class="commission-metric commission-metric-deposit">
                                        <span class="commission-metric-icon"><i class="ik ik-pie-chart"></i></span>
                                        <p class="commission-metric-label">Single Leg Income</p>
                                        <h4 class="commission-metric-value">$ {{ number_format((float) totalAdminSingleLegIncome(), 2) }}</h4>
                                        <span class="commission-metric-link">View Details <i class="ik ik-arrow-right"></i></span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!--<div class="col-lg-3 col-md-6 col-sm-12">-->
            <!--    <div class="widget">-->
            <!--        <div class="widget-body">-->
            <!--            <div class="d-flex justify-content-between align-items-center">-->
            <!--                <div class="state">-->
            <!--                    <h6>Activation</h6>-->
            <!--                    <h4>$ {{ totalAvtivation() }}</h4>-->
            <!--                </div>-->
            <!--                <div class="icon">-->
            <!--                    <i class="ik ik-award"></i>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <small class="text-small mt-10 d-block">Total Amount By Account Activation</small>-->
            <!--        </div>-->
            <!--        <div class="progress progress-sm">-->
            <!--            <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="62" aria-valuemin="0"-->
            <!--                aria-valuemax="100" style="width: 62%;"></div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-lg-3 col-md-6 col-sm-12">-->
            <!--    <div class="widget">-->
            <!--        <div class="widget-body">-->
            <!--            <div class="d-flex justify-content-between align-items-center">-->
            <!--                <div class="state">-->
            <!--                    <h6>Upgrade</h6>-->
            <!--                    <h4>$ {{ totalUpgrade() }}</h4>-->
            <!--                </div>-->
            <!--                <div class="icon">-->
            <!--                    <i class="ik ik-thumbs-up"></i>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <small class="text-small mt-10 d-block">Total Amount By Upgrade</small>-->
            <!--        </div>-->
            <!--        <div class="progress progress-sm">-->
            <!--            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="78" aria-valuemin="0"-->
            <!--                aria-valuemax="100" style="width: 78%;"></div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-lg-3 col-md-6 col-sm-12">-->
            <!--    <div class="widget">-->
            <!--        <div class="widget-body">-->
            <!--            <div class="d-flex justify-content-between align-items-center">-->
            <!--                <div class="state">-->
            <!--                    <h6>Total Withdrawal</h6>-->
            <!--                    <h4>$ {{ totalWithdrawal() }}</h4>-->
            <!--                </div>-->
            <!--                <div class="icon">-->
            <!--                    <i class="ik ik-thumbs-up"></i>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <small class="text-small mt-10 d-block">Total Withdrawal</small>-->
            <!--        </div>-->
            <!--        <div class="progress progress-sm">-->
            <!--            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="78" aria-valuemin="0"-->
            <!--                aria-valuemax="100" style="width: 78%;"></div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-lg-3 col-md-6 col-sm-12">-->
            <!--    <div class="widget">-->
            <!--        <div class="widget-body">-->
            <!--            <div class="d-flex justify-content-between align-items-center">-->
            <!--                <div class="state">-->
            <!--                    <h6>Total P2P Balance</h6>-->
            <!--                    <h4>$ </h4>-->
            <!--                </div>-->
            <!--                <div class="icon">-->
            <!--                    <i class="ik ik-thumbs-up"></i>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <small class="text-small mt-10 d-block">Total Invetment</small>-->
            <!--        </div>-->
            <!--        <div class="progress progress-sm">-->
            <!--            <div class="progress-bar bg-blue" role="progressbar" aria-valuenow="78" aria-valuemin="0"-->
            <!--                aria-valuemax="100" style="width: 78%;"></div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            
            <div class="col-12 mt-2">
                <div class="card commission-overview-card" style="background-color: #1e1e2d;">
                    <div
                        class="card-header commission-overview-header d-flex justify-content-between align-items-center flex-wrap" style="padding: 10px 20px;background-color: #1e1e2d;">
                        <h3 class="mb-0 text-white">PEPE Tokens Overview</h3>
                        <!--<div class="text-right text-end">-->
                        <!--    <span class="d-block"-->
                        <!--        style="font-size: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;">Total-->
                        <!--        Income</span>-->
                        <!--    <h3 class="mb-0">$ {{ number_format((float) totalIn(), 2) }}</h3>-->
                        <!--</div>-->
                    </div>
                    <!--<div class="col-xl-12">-->
                        <div class="card product-progress-card">
                    <div class="card-block">
                        <div class="row pp-main">
                            <div class="col-xl-6 col-md-6 mb-3">
                                <div class="pp-cont">
                                    <div class="row align-items-center mb-20">
                                        <div class="col-auto">
                                            <h4 class="">Today PEPE</h4>
                                        </div>
                                        <div class="col text-right">
                                            <h4 class="mb-0 text-green">{{ todayPEPEWithdrawal() }} PEPE</h4>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-15">
                                        <div class="col-auto">
                                            <p class="mb-0">Today's PEPE Withdrawal</p>
                                        </div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-green" style="width:45%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-3">
                                <div class="pp-cont">
                                    <div class="row align-items-center mb-20">
                                        <div class="col-auto">
                                            <h4 class="">Total PEPE</h4>
                                        </div>
                                        <div class="col text-right">
                                            <h4 class="mb-0 text-red">{{ totalPEPEWithdrawal() }} PEPE</h4>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-15">
                                        <div class="col-auto">
                                            <p class="mb-0">Total PEPE Withdrawal</p>
                                        </div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-red" style="width:75%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                   <!--</div>-->
                </div>
            </div>
            
            
            <div class="col-xl-12">
                <div class="card product-progress-card">
                    <div class="card-block">
                        <div class="row pp-main">
                            <div class="col-xl-6 col-md-6 mb-3">
                                <div class="pp-cont">
                                    <div class="row align-items-center mb-20">
                                        <div class="col-auto">
                                            <i class="fas fa-cube f-24 text-mute"></i>
                                        </div>
                                        <div class="col text-right">
                                            <h4 class="mb-0 text-blue">$ {{ todaysWithdrawal() }}</h4>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-15">
                                        <div class="col-auto">
                                            <p class="mb-0">Today's Withdrawal</p>
                                        </div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-blue" style="width:45%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-3">
                                <div class="pp-cont">
                                    <div class="row align-items-center mb-20">
                                        <div class="col-auto">
                                            <i class="fas fa-tag f-24 text-mute"></i>
                                        </div>
                                        <div class="col text-right">
                                            <h4 class="mb-0 text-red">$ {{ totalWithdrawal() }}</h4>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-15">
                                        <div class="col-auto">
                                            <p class="mb-0">Total Withdrawal</p>
                                        </div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-red" style="width:75%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card card-red text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h4 class="mb-0">$ {{ totalgrossAmount() }}</h4>
                                <p class="mb-0">Withdrawal Gross Amount</p>
                            </div>
                            <div class="col-3 text-right">
                                <i class="ik ik-user f-30"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-blue text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h4 class="mb-0">$ {{ totalnetAmount() }}</h4>
                                <p class="mb-0">Withdrawal Net Amount</p>
                            </div>
                            <div class="col-3 text-right">
                                <i class="ik ik-user f-30"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card card-green text-white">
                    <div class="card-block">
                        <div class="row align-items-center">
                            <div class="col-9">
                                <h4 class="mb-0">$ {{ totaldeductions() }}</h4>
                                <p class="mb-0">Deduction on Withdrawal</p>
                            </div>
                            <div class="col-3 text-right">
                                <i class="ik ik-user f-30"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total Loss Members</h6>
                                <h4 class="pt-2">₹ {{totalLoss()}}</h4>
                            </div>
                            <div class="icon">
                                <i class="ik ik-trending-down text-danger"></i>
                            </div>
                        </div>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="31" aria-valuemin="0"
                            aria-valuemax="100" style="width: 31%;"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <div class="widget">
                    <div class="widget-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="state">
                                <h6>Total Win Members</h6>
                                <h4 class="pt-2">₹ {{totalWin()}}</h4>
                            </div>
                            <div class="icon">
                                <i class="ik ik-trending-up text-success"></i>
                            </div>
                        </div>
                    </div>
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-info" role="progressbar" aria-valuenow="20" aria-valuemin="0"
                            aria-valuemax="100" style="width: 20%;"></div>
                    </div>
                </div>
            </div> --}}
            <!--<div class="col-xl-4 col-md-6">-->
            <!--    <div class="card card-red text-white">-->
            <!--        <div class="card-block">-->
            <!--            <div class="row align-items-center">-->
            <!--                <div class="col-9">-->
            <!--                    <h4 class="mb-0">₹ {{totalgrossAmount()}}</h4>-->
            <!--                    <p class="mb-0">Withdrawal Gross Amount</p>-->
            <!--                </div>-->
            <!--                <div class="col-3 text-right">-->
            <!--                    <i class="ik ik-user f-30"></i>-->
            <!--                </div>-->
            <!--            </div>-->

            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-xl-4 col-md-6">-->
            <!--    <div class="card card-blue text-white">-->
            <!--        <div class="card-block">-->
            <!--            <div class="row align-items-center">-->
            <!--                <div class="col-9">-->
            <!--                    <h4 class="mb-0">₹ {{totalnetAmount()}}</h4>-->
            <!--                    <p class="mb-0">Withdrawal Net Amount</p>-->
            <!--                </div>-->
            <!--                <div class="col-3 text-right">-->
            <!--                    <i class="ik ik-user f-30"></i>-->
            <!--                </div>-->
            <!--            </div>-->

            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-xl-4 col-md-6">-->
            <!--    <div class="card card-green text-white">-->
            <!--        <div class="card-block">-->
            <!--            <div class="row align-items-center">-->
            <!--                <div class="col-9">-->
            <!--                    <h4 class="mb-0">₹ {{totaldeductions()}}</h4>-->
            <!--                    <p class="mb-0">Deduction on Withdrawal</p>-->
            <!--                </div>-->
            <!--                <div class="col-3 text-right">-->
            <!--                    <i class="ik ik-user f-30"></i>-->
            <!--                </div>-->
            <!--            </div>-->

            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-xl-4 col-md-6">-->
            <!--    <div class="card card-yellow text-white">-->
            <!--        <div class="card-block">-->
            <!--            <div class="row align-items-center">-->
            <!--                <div class="col-9">-->
            <!--                    <h4 class="mb-0">{{pendingwithrawalreq()}}</h4>-->
            <!--                    <p class="mb-0">Pending Withdrwal Req.</p>-->
            <!--                </div>-->
            <!--                <div class="col-3 text-right">-->
            <!--                    <i class="ik ik-user f-30"></i>-->
            <!--                </div>-->
            <!--            </div>-->

            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-xl-4 col-md-6">-->
            <!--    <div class="card card-green text-white">-->
            <!--        <div class="card-block">-->
            <!--            <div class="row align-items-center">-->
            <!--                <div class="col-9">-->
            <!--                    <h4 class="mb-0">{{Cancelwithrawalreq()}}</h4>-->
            <!--                    <p class="mb-0">Cancel Withdrwal Req.</p>-->
            <!--                </div>-->
            <!--                <div class="col-3 text-right">-->
            <!--                    <i class="ik ik-user f-30"></i>-->
            <!--                </div>-->
            <!--            </div>-->

            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!--<div class="col-xl-4 col-md-6">-->
            <!--    <div class="card card-yellow text-white">-->
            <!--        <div class="card-block">-->
            <!--            <div class="row align-items-center">-->
            <!--                <div class="col-9">-->
            <!--                    <h4 class="mb-0">{{Approvwithrawalreq()}}</h4>-->
            <!--                    <p class="mb-0">Approved Withdrwal Req.</p>-->
            <!--                </div>-->
            <!--                <div class="col-3 text-right">-->
            <!--                    <i class="ik ik-user f-30"></i>-->
            <!--                </div>-->
            <!--            </div>-->

            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
            <!-- page statustic chart end -->

        </div>
    </div>
    <!-- push external js -->
    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/owl.carousel/dist/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/plugins/chartist/dist/chartist.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/plugins/flot-charts/jquery.flot.js') }}"></script>
        <!-- <script src="{{ asset('adm_assets/assets/plugins/flot-charts/jquery.flot.categories.js') }}"></script> -->
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