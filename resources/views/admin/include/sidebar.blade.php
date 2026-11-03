<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{url('hdgteyusjasget/dashboard')}}">
            <div class="logo-img text-center">
                <img src="{{asset('logo/logo.png')}}" class="header-brand-img" alt="{{ config('detailsApp.name', 'SYNC TRADE') }}" title="Admin">
            </div>
        </a>
        <div class="sidebar-action"></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
    $segment1 = request()->segment(1);
    $segment2 = request()->segment(2);

    $isPlanConfig = request()->is(
    'hdgteyusjasget/set-packages*',
    'hdgteyusjasget/set_packages*',
    'hdgteyusjasget/trading-wallet-control*',
    'hdgteyusjasget/apply-trading-wallet-control*',
    'hdgteyusjasget/monthly-trading-profit*',
    'hdgteyusjasget/monthly_trading_profit*',
    'hdgteyusjasget/save-monthly-trading-profit*',
    'hdgteyusjasget/referral-bonus*',
    'hdgteyusjasget/referral_bonus*',
    'hdgteyusjasget/save-referral-bonus*',
    'hdgteyusjasget/team-trading-profit*',
    'hdgteyusjasget/team_trading_profit*',
    'hdgteyusjasget/save-team-trading-profit*',
    'hdgteyusjasget/daily-team-investment-share*',
    'hdgteyusjasget/daily_team_investment_share*',
    'hdgteyusjasget/save-daily-team-investment-share*'
    ) || request()->routeIs(
    'admin.setPackages',
    'admin.savePackages',
    'admin.tradingWalletControl',
    'admin.applyTradingWalletControl',
    'admin.monthlyTradingProfit',
    'admin.saveMonthlyTradingProfit',
    'admin.referralBonus',
    'admin.saveReferralBonus',
    'admin.teamTradingProfit',
    'admin.saveTeamTradingProfit',
    'admin.dailyTeamInvestmentShare',
    'admin.saveDailyTeamInvestmentShare'
    );

    $isTradingPackageSection = request()->is(
    'hdgteyusjasget/income/monthly-trading-profit*',
    'hdgteyusjasget/income/roi-incomes*',
    'hdgteyusjasget/income/trading-profite-details*'
    ) || request()->routeIs(
    'admin.income.monthlyTradingProfit'
    );

    $isIncomeSection = request()->is(
    'hdgteyusjasget/income/referral-bonus*',
    'hdgteyusjasget/income/direct-incomes*',
    'hdgteyusjasget/income/team-trading-profit*',
    'hdgteyusjasget/income/staking-level-incomes*',
    'hdgteyusjasget/income/daily-team-investment-share*',
    'hdgteyusjasget/income/level-incomes*',
    'hdgteyusjasget/income/hero-of-the-month*',
    'hdgteyusjasget/income/team-withdrawal-commission-incomes*'
    ) || request()->routeIs(
    'admin.income.referralBonus',
    'admin.income.direct',
    'admin.income.teamTradingProfit',
    'admin.income.dailyTeamInvestmentShare',
    'admin.income.heroOfTheMonth'
    );

    $isPartnershipSection = request()->is(
    'hdgteyusjasget/income/partnership-incomes*',
    'hdgteyusjasget/income/partnership-details*'
    );
    @endphp

    <div class="sidebar-content ">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item mt-4 {{ ($segment1 == 'dashboard') ? 'active' : '' }}">
                    <a href="{{url('hdgteyusjasget/dashboard')}}"><i class="ik ik-bar-chart-2"></i><span>Dashboard</span></a>
                </div>
                {{-- <div class="nav-item  {{ ($segment1 == 'dashboard') ? 'active' : '' }}">
                <a href="{{url('hdgteyusjasget/color-dashboard')}}"><i class="ik ik-pie-chart"></i><span>Color Dashboard</span></a>
        </div> --}}
        <div class="nav-item {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
            <a href="{{url('hdgteyusjasget/dashboard-images')}}"><i class="ik ik-image"></i><span>Welcome Images</span> </a>
        </div>
        <div class="nav-item  {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
            <a href="{{url('hdgteyusjasget/dash-msg')}}"><i class="ik ik-mail"></i><span>Dash Messages</span> </a>
        </div>

        <div class="nav-item {{ $segment1 == 'rest-api' ? 'active' : '' }}">
            <a href="{{ url('hdgteyusjasget/notification') }}"><i class="ik ik-bell"></i><span>Notification</span> </a>
        </div>

                <div class="nav-item {{ $isPlanConfig ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-sliders"></i><span>{{ __('Plan & Income') }}<br>{{ __('Configuration') }}</span></a>
                    <div class="submenu-content">
                        <a href="{{ route('admin.setPackages') }}" class="menu-item {{ (request()->is('hdgteyusjasget/set-packages*', 'hdgteyusjasget/set_packages*') || request()->routeIs('admin.setPackages', 'admin.savePackages')) ? 'active' : '' }}">{{ __('Set Packages') }}</a>
                        <a href="{{ route('admin.referralBonus') }}" class="menu-item {{ (request()->is('hdgteyusjasget/referral-bonus*', 'hdgteyusjasget/referral_bonus*', 'hdgteyusjasget/save-referral-bonus*') || request()->routeIs('admin.referralBonus', 'admin.saveReferralBonus')) ? 'active' : '' }}">{{ __('Referral Bonus') }}</a>
                        <a href="{{ route('admin.teamTradingProfit') }}" class="menu-item {{ (request()->is('hdgteyusjasget/team-trading-profit*', 'hdgteyusjasget/team_trading_profit*', 'hdgteyusjasget/save-team-trading-profit*') || request()->routeIs('admin.teamTradingProfit', 'admin.saveTeamTradingProfit')) ? 'active' : '' }}">{{ __('Team Trading Profit') }}</a>
                        <a href="{{ route('admin.dailyTeamInvestmentShare') }}" class="menu-item {{ (request()->is('hdgteyusjasget/daily-team-investment-share*', 'hdgteyusjasget/daily_team_investment_share*', 'hdgteyusjasget/save-daily-team-investment-share*') || request()->routeIs('admin.dailyTeamInvestmentShare', 'admin.saveDailyTeamInvestmentShare')) ? 'active' : '' }}">{{ __('Daily Team Investment Share') }}</a>
                    </div>
                </div>
                
                <!--<div class="nav-item {{ $segment1 == 'rest-api' ? 'active' : '' }}">-->
                <!--    <a href="{{ url('hdgteyusjasget/setRate') }}"><i class="ik ik-sliders"></i><span>Set Rate</span> </a>-->
                <!--</div>-->

                <div class="nav-item {{ ($segment1 == 'whatsapp-messages' || $segment1 == 'whatsapp-reports') ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-share-2"></i><span>{{ __('Marketing') }}</span></a>
                    <div class="submenu-content">
                        {{-- <a href="{{ url('hdgteyusjasget/whatsapp-messages') }}" class="menu-item {{ ($segment1 == 'whatsapp-messages') ? 'active' : '' }}">{{ __('WhatsApp Messages') }}</a> --}}
                        <a href="{{ url('hdgteyusjasget/whatsapp-reports') }}" class="menu-item {{ ($segment1 == 'whatsapp-reports') ? 'active' : '' }}">{{ __('WhatsApp Reports') }}</a>
                    </div>
                </div>

        <div class="nav-item {{ (request()->is('*promotion-banners*') || request()->is('*business-plan-pdfs*') || request()->is('*plan-videos*') || request()->is('*tutorial-videos*')) ? 'active open' : '' }} has-sub">
            <a href="#"><i class="ik ik-film"></i><span>{{ __('Promotional Media') }}</span></a>
            <div class="submenu-content">
                <a href="{{ url('hdgteyusjasget/promotion-banners') }}" class="menu-item {{ request()->is('*promotion-banners*') ? 'active' : '' }}">{{ __('Promotion Banners') }}</a>
                <a href="{{ url('hdgteyusjasget/business-plan-pdfs') }}" class="menu-item {{ request()->is('*business-plan-pdfs*') ? 'active' : '' }}">{{ __('Business Plan PDFs') }}</a>
                <a href="{{ url('hdgteyusjasget/plan-videos') }}" class="menu-item {{ request()->is('*plan-videos*') ? 'active' : '' }}">{{ __('Plan Videos') }}</a>
                <a href="{{ url('hdgteyusjasget/tutorial-videos') }}" class="menu-item {{ request()->is('*tutorial-videos*') ? 'active' : '' }}">{{ __('Tutorial Videos') }}</a>
            </div>
        </div>
        {{-- <div class="nav-item  {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
        <a href="{{url('hdgteyusjasget/set-details')}}"><i class="ik ik-box"></i><span>Set Details</span> </a>
    </div>
    <div class="nav-item  {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
        <a href="{{url('hdgteyusjasget/deposit-details')}}"><i class="ik ik-box"></i><span>Deposit Details</span> </a>
    </div> --}}
    <div class="nav-item {{ ($segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-users"></i><span>{{ __('Member Management')}}</span></a>
        <div class="submenu-content">
            <a href="{{url('hdgteyusjasget/member-details')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('Members Details')}}</a>
            {{-- <a href="{{url('hdgteyusjasget/member-security')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Members Security')}}</a> --}}
            <a href="{{url('hdgteyusjasget/account-control')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('Account Control')}}</a>
            <a href="{{ url('hdgteyusjasget/wallet-address') }}"
                class="menu-item {{ $segment1 == 'badges' ? 'active' : '' }}">{{ __('Member Wallet Address') }}</a>
            <a href="{{url('hdgteyusjasget/package-details')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('Package Details')}}</a>
            {{-- <a href="{{url('hdgteyusjasget/new-kyc-requests')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('New KYC Requests')}}</a>
            <a href="{{url('hdgteyusjasget/verified-kyc-requests')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Verified KYCs')}}</a> --}}
        </div>
    </div>

    <div class="nav-item {{ (request()->is('*funds*') || $segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-database"></i><span>{{ __('Fund Management')}}</span></a>
        <div class="submenu-content">
            <a href="{{url('hdgteyusjasget/funds/add-funds')}}" class="menu-item {{ request()->is('*funds/add-funds') ? 'active' : '' }}">{{ __('Add Funds')}}</a>
            <a href="{{url('hdgteyusjasget/funds/deduct-funds')}}" class="menu-item {{ request()->is('*funds/deduct-funds') ? 'active' : '' }}">{{ __('Deduct Funds')}}</a>
            <a href="{{url('hdgteyusjasget/funds/add-funds-details')}}" class="menu-item {{ request()->is('*funds/add-funds-details') ? 'active' : '' }}">{{ __('Funds Details')}}</a>
            <a href="{{url('hdgteyusjasget/funds/import-fund-details')}}" class="menu-item {{ request()->is('*funds/import-fund-details') ? 'active' : '' }}">{{ __('Import Fund Details')}}</a>
        </div>
    </div>

    <div class="nav-item {{ (request()->is('*trade*') || $segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-dollar-sign"></i><span>{{ __('Add Trading Profit')}}</span></a>
        <div class="submenu-content">
            <a href="{{url('hdgteyusjasget/trade/add-trading-profit')}}" class="menu-item {{ request()->is('*trade/add-trading-profit') ? 'active' : '' }}">{{ __('Add Trading Profit')}}</a>
            <a href="{{url('hdgteyusjasget/trade/add-trading-profit-details')}}" class="menu-item {{ request()->is('*trade/add-trading-profit-details') ? 'active' : '' }}">{{ __('Add Trading Profit Details')}}</a>
        </div>
    </div>


    <div
        class="nav-item {{ $isTradingPackageSection ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-credit-card"></i><span>{{ __('Trading Package Section') }}</span></a>
        <div class="submenu-content">
            <a href="{{ url('hdgteyusjasget/income/monthly-trading-profit') }}"
                class="menu-item {{ (request()->is('hdgteyusjasget/income/monthly-trading-profit*', 'hdgteyusjasget/income/roi-incomes*') || request()->routeIs('admin.income.monthlyTradingProfit')) ? 'active' : '' }}">{{ __('Monthly Trading Profit') }}</a>
            <a href="{{ url('hdgteyusjasget/income/trading-profite-details') }}"
                class="menu-item {{ request()->is('hdgteyusjasget/income/trading-profite-details') ? 'active' : '' }}">{{ __('Trading Profit Details') }}</a>
        </div>
    </div>

    <div
        class="nav-item {{ $isIncomeSection ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-dollar-sign"></i><span>{{ __('Income Section') }}</span></a>
        <div class="submenu-content">
            <a href="{{ url('hdgteyusjasget/income/referral-bonus') }}"
                class="menu-item {{ (request()->is('hdgteyusjasget/income/referral-bonus*', 'hdgteyusjasget/income/direct-incomes*') || request()->routeIs('admin.income.referralBonus', 'admin.income.direct')) ? 'active' : '' }}">{{ __('Referral Bonus') }}</a>
            <a href="{{ url('hdgteyusjasget/income/team-trading-profit') }}"
                class="menu-item {{ (request()->is('hdgteyusjasget/income/team-trading-profit*', 'hdgteyusjasget/income/staking-level-incomes*') || request()->routeIs('admin.income.teamTradingProfit')) ? 'active' : '' }}">{{ __('Team Trading Profit') }}</a>
            <a href="{{ url('hdgteyusjasget/income/daily-team-investment-share') }}"
                class="menu-item {{ (request()->is('hdgteyusjasget/income/daily-team-investment-share*', 'hdgteyusjasget/income/level-incomes*') || request()->routeIs('admin.income.dailyTeamInvestmentShare')) ? 'active' : '' }}">{{ __('Daily Team Investment Share') }}</a>
            <a href="{{ url('hdgteyusjasget/income/hero-of-the-month') }}"
                class="menu-item {{ (request()->is('hdgteyusjasget/income/hero-of-the-month*') || request()->routeIs('admin.income.heroOfTheMonth')) ? 'active' : '' }}">{{ __('Hero of the Month') }}</a>

        </div>
    </div>

    <div
        class="nav-item {{ $isPartnershipSection ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-briefcase"></i><span>{{ __('Partnership Section') }}</span></a>
        <div class="submenu-content">

            <a href="{{ url('hdgteyusjasget/income/partnership-incomes') }}"
                class="menu-item {{ request()->is('hdgteyusjasget/income/partnership-incomes*') ? 'active' : '' }}">{{ __('Partnership Income') }}</a>
            <a href="{{ url('hdgteyusjasget/income/partnership-details') }}"
                class="menu-item {{ request()->is('hdgteyusjasget/income/partnership-details*') ? 'active' : '' }}">{{ __('Partnership Details') }}</a>

        </div>
    </div>





    {{-- <div class="nav-item {{ ($segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
    <a href="#"><i class="ik ik-database"></i><span>{{ __('Resources Section')}}</span></a>
    <div class="submenu-content">
        <a href="{{url('hdgteyusjasget/resources/resources-refund')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('Resources Refund')}}</a>
        <a href="{{url('hdgteyusjasget/resources/refund-details')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Refund details')}}</a>
    </div>
</div> --}}

<div class="nav-item {{ ($segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
    <a href="#"><i class="ik ik-cloud"></i><span>{{ __('Payment Management')}}</span></a>
    <div class="submenu-content">
        {{-- <a href="{{url('hdgteyusjasget/package/fund-requests')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Fund Requests')}}</a>
        <a href="{{url('hdgteyusjasget/set-pay-mode')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Set Payment Mode')}} </a>--}}
        <a href="{{url('hdgteyusjasget/new-withdrawal-request')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('New Withdrawal Requests')}}</a>
        {{-- <a href="{{url('hdgteyusjasget/exchange-withdrawal-request')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('New Exchange Requests')}}</a> --}}
        <a href="{{url('hdgteyusjasget/cancelled-request')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Cancelled Requests')}}</a>
        <a href="{{url('hdgteyusjasget/payment-history')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Payment History')}}</a>
        <!--<a href="{{url('hdgteyusjasget/pepe-settings')}}" class="menu-item {{ request()->is('*pepe-settings*') ? 'active' : '' }}">{{ __('PEPE Token Settings')}}</a>-->
    </div>
</div>
<div class="nav-item  {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
    <a href="{{url('hdgteyusjasget/transaction')}}"><i class="ik ik-database"></i><span>Transaction</span> </a>
</div>
<div class="nav-item {{ ($segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
    <a href="#"><i class="ik ik-mail"></i><span>{{ __('Support')}}</span></a>
    <div class="submenu-content">
        <a href="{{url('hdgteyusjasget/support/new-support-ticket')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('New Support Tickets')}}</a>
        <a href="{{url('hdgteyusjasget/support/open-support-ticket')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('Open Support Tickets')}}</a>
        <a href="{{url('hdgteyusjasget/support/close-support-ticket')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Closed Support Tickets')}}</a>
    </div>
</div>

<div class="nav-item {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
    <a href="{{url('hdgteyusjasget/logout')}}"><i class="ik ik-stop-circle"></i><span>Logout</span> </a>
</div>
<!-- end inventory pages -->


</div>
</div>
</div>