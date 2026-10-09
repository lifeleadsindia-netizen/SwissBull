<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{url('admin/dashboard')}}">
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
    'admin/set-packages*',
    'admin/set_packages*',
    'admin/trading-wallet-control*',
    'admin/apply-trading-wallet-control*',
    'admin/monthly-trading-profit*',
    'admin/monthly_trading_profit*',
    'admin/save-monthly-trading-profit*',
    'admin/referral-bonus*',
    'admin/referral_bonus*',
    'admin/save-referral-bonus*',
    'admin/team-trading-profit*',
    'admin/team_trading_profit*',
    'admin/save-team-trading-profit*',
    'admin/daily-team-investment-share*',
    'admin/daily_team_investment_share*',
    'admin/save-daily-team-investment-share*'
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

    $isRoiSection = request()->is(
    'admin/income/monthly-trading-profit*',
    'admin/income/roi-incomes*',
    'admin/income/roi-details*'
    ) || request()->routeIs(
    'admin.income.monthlyTradingProfit'
    );

    $isIncomeSection = request()->is(
    'admin/income/referral-bonus*',
    'admin/income/direct-incomes*',
    'admin/income/team-trading-profit*',
    'admin/income/staking-level-incomes*',
    'admin/income/daily-team-investment-share*',
    'admin/income/level-incomes*',
    'admin/income/hero-of-the-month*',
    'admin/income/team-withdrawal-commission-incomes*'
    ) || request()->routeIs(
    'admin.income.referralBonus',
    'admin.income.direct',
    'admin.income.teamTradingProfit',
    'admin.income.dailyTeamInvestmentShare',
    'admin.income.heroOfTheMonth'
    );

    $isPartnershipSection = request()->is(
    'admin/income/partnership-incomes*',
    'admin/income/partnership-details*'
    );
    @endphp

    <div class="sidebar-content ">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item mt-4 {{ ($segment1 == 'dashboard') ? 'active' : '' }}">
                    <a href="{{url('admin/dashboard')}}"><i class="ik ik-bar-chart-2"></i><span>Dashboard</span></a>
                </div>
                {{-- <div class="nav-item  {{ ($segment1 == 'dashboard') ? 'active' : '' }}">
                <a href="{{url('admin/color-dashboard')}}"><i class="ik ik-pie-chart"></i><span>Color Dashboard</span></a>
        </div> --}}
        <div class="nav-item {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
            <a href="{{url('admin/dashboard-images')}}"><i class="ik ik-image"></i><span>Welcome Images</span> </a>
        </div>
        <div class="nav-item  {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
            <a href="{{url('admin/dash-msg')}}"><i class="ik ik-mail"></i><span>Dash Messages</span> </a>
        </div>

        <div class="nav-item {{ $segment1 == 'rest-api' ? 'active' : '' }}">
            <a href="{{ url('admin/notification') }}"><i class="ik ik-bell"></i><span>Notification</span> </a>
        </div>

        <div class="nav-item {{ $isPlanConfig ? 'active open' : '' }} has-sub">
            <a href="#"><i class="ik ik-sliders"></i><span>{{ __('Plan & Income') }}<br>{{ __('Configuration') }}</span></a>
            <div class="submenu-content">
                <a href="{{ route('admin.setPackages') }}" class="menu-item {{ (request()->is('admin/set-packages*', 'admin/set_packages*') || request()->routeIs('admin.setPackages', 'admin.savePackages')) ? 'active' : '' }}">{{ __('Set Packages') }}</a>
                <a href="{{ route('admin.tradingWalletControl') }}" class="menu-item {{ (request()->is('admin/trading-wallet-control*', 'admin/apply-trading-wallet-control*') || request()->routeIs('admin.tradingWalletControl', 'admin.applyTradingWalletControl')) ? 'active' : '' }}">{{ __('Trading Wallet Control') }}</a>
                <a href="{{ route('admin.monthlyTradingProfit') }}" class="menu-item {{ (request()->is('admin/monthly-trading-profit*', 'admin/monthly_trading_profit*', 'admin/save-monthly-trading-profit*') || request()->routeIs('admin.monthlyTradingProfit', 'admin.saveMonthlyTradingProfit')) ? 'active' : '' }}">{{ __('Monthly Trading Profit') }}</a>
                <a href="{{ route('admin.referralBonus') }}" class="menu-item {{ (request()->is('admin/referral-bonus*', 'admin/referral_bonus*', 'admin/save-referral-bonus*') || request()->routeIs('admin.referralBonus', 'admin.saveReferralBonus')) ? 'active' : '' }}">{{ __('Referral Bonus') }}</a>
                <a href="{{ route('admin.teamTradingProfit') }}" class="menu-item {{ (request()->is('admin/team-trading-profit*', 'admin/team_trading_profit*', 'admin/save-team-trading-profit*') || request()->routeIs('admin.teamTradingProfit', 'admin.saveTeamTradingProfit')) ? 'active' : '' }}">{{ __('Team Trading Profit') }}</a>
                <a href="{{ route('admin.dailyTeamInvestmentShare') }}" class="menu-item {{ (request()->is('admin/daily-team-investment-share*', 'admin/daily_team_investment_share*', 'admin/save-daily-team-investment-share*') || request()->routeIs('admin.dailyTeamInvestmentShare', 'admin.saveDailyTeamInvestmentShare')) ? 'active' : '' }}">{{ __('Daily Team Investment Share') }}</a>
            </div>
        </div>

        <!--<div class="nav-item {{ $segment1 == 'rest-api' ? 'active' : '' }}">-->
        <!--    <a href="{{ url('admin/setRate') }}"><i class="ik ik-sliders"></i><span>Set Rate</span> </a>-->
        <!--</div>-->

        <div class="nav-item {{ ($segment1 == 'whatsapp-messages' || $segment1 == 'whatsapp-reports') ? 'active open' : '' }} has-sub">
            <a href="#"><i class="ik ik-share-2"></i><span>{{ __('Marketing') }}</span></a>
            <div class="submenu-content">
                {{-- <a href="{{ url('admin/whatsapp-messages') }}" class="menu-item {{ ($segment1 == 'whatsapp-messages') ? 'active' : '' }}">{{ __('WhatsApp Messages') }}</a> --}}
                <a href="{{ url('admin/whatsapp-reports') }}" class="menu-item {{ ($segment1 == 'whatsapp-reports') ? 'active' : '' }}">{{ __('WhatsApp Reports') }}</a>
            </div>
        </div>

        <div class="nav-item {{ (request()->is('*promotion-banners*') || request()->is('*business-plan-pdfs*') || request()->is('*plan-videos*') || request()->is('*tutorial-videos*')) ? 'active open' : '' }} has-sub">
            <a href="#"><i class="ik ik-film"></i><span>{{ __('Promotional Media') }}</span></a>
            <div class="submenu-content">
                <a href="{{ url('admin/promotion-banners') }}" class="menu-item {{ request()->is('*promotion-banners*') ? 'active' : '' }}">{{ __('Promotion Banners') }}</a>
                <a href="{{ url('admin/business-plan-pdfs') }}" class="menu-item {{ request()->is('*business-plan-pdfs*') ? 'active' : '' }}">{{ __('Business Plan PDFs') }}</a>
                <a href="{{ url('admin/plan-videos') }}" class="menu-item {{ request()->is('*plan-videos*') ? 'active' : '' }}">{{ __('Plan Videos') }}</a>
                <a href="{{ url('admin/tutorial-videos') }}" class="menu-item {{ request()->is('*tutorial-videos*') ? 'active' : '' }}">{{ __('Tutorial Videos') }}</a>
            </div>
        </div>
        {{-- <div class="nav-item  {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
        <a href="{{url('admin/set-details')}}"><i class="ik ik-box"></i><span>Set Details</span> </a>
    </div>
    <div class="nav-item  {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
        <a href="{{url('admin/deposit-details')}}"><i class="ik ik-box"></i><span>Deposit Details</span> </a>
    </div> --}}
    <div class="nav-item {{ ($segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-users"></i><span>{{ __('Member Management')}}</span></a>
        <div class="submenu-content">
            <a href="{{url('admin/member-details')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('Members Details')}}</a>
            {{-- <a href="{{url('admin/member-security')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Members Security')}}</a> --}}
            <a href="{{url('admin/account-control')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('Account Control')}}</a>
            <a href="{{ url('admin/wallet-address') }}"
                class="menu-item {{ $segment1 == 'badges' ? 'active' : '' }}">{{ __('Member Wallet Address') }}</a>
            <a href="{{url('admin/package-details')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('Package Details')}}</a>
            {{-- <a href="{{url('admin/new-kyc-requests')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('New KYC Requests')}}</a>
            <a href="{{url('admin/verified-kyc-requests')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Verified KYCs')}}</a> --}}
        </div>
    </div>

    <div class="nav-item {{ (request()->is('*funds*') || $segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-database"></i><span>{{ __('Fund Management')}}</span></a>
        <div class="submenu-content">
            <a href="{{url('admin/funds/add-funds')}}" class="menu-item {{ request()->is('*funds/add-funds') ? 'active' : '' }}">{{ __('Add Funds')}}</a>
            <a href="{{url('admin/funds/deduct-funds')}}" class="menu-item {{ request()->is('*funds/deduct-funds') ? 'active' : '' }}">{{ __('Deduct Funds')}}</a>
            <a href="{{url('admin/funds/add-funds-details')}}" class="menu-item {{ request()->is('*funds/add-funds-details') ? 'active' : '' }}">{{ __('Funds Details')}}</a>
            <a href="{{url('admin/funds/import-fund-details')}}" class="menu-item {{ request()->is('*funds/import-fund-details') ? 'active' : '' }}">{{ __('Import Fund Details')}}</a>
        </div>
    </div>


    <div
        class="nav-item {{ $isRoiSection ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-credit-card"></i><span>{{ __('ROI Section') }}</span></a>
        <div class="submenu-content">
            <a href="{{ url('admin/income/monthly-trading-profit') }}"
                class="menu-item {{ (request()->is('admin/income/monthly-trading-profit*', 'admin/income/roi-incomes*') || request()->routeIs('admin.income.monthlyTradingProfit')) ? 'active' : '' }}">{{ __('Monthly Trading Profit') }}</a>
            <a href="{{ url('admin/income/roi-details') }}"
                class="menu-item {{ request()->is('admin/income/roi-details*') ? 'active' : '' }}">{{ __('Trading Profit Details') }}</a>
        </div>
    </div>

    <div
        class="nav-item {{ $isIncomeSection ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-dollar-sign"></i><span>{{ __('Income Section') }}</span></a>
        <div class="submenu-content">
            <a href="{{ url('admin/income/referral-bonus') }}"
                class="menu-item {{ (request()->is('admin/income/referral-bonus*', 'admin/income/direct-incomes*') || request()->routeIs('admin.income.referralBonus', 'admin.income.direct')) ? 'active' : '' }}">{{ __('Referral Bonus') }}</a>
            <a href="{{ url('admin/income/team-trading-profit') }}"
                class="menu-item {{ (request()->is('admin/income/team-trading-profit*', 'admin/income/staking-level-incomes*') || request()->routeIs('admin.income.teamTradingProfit')) ? 'active' : '' }}">{{ __('Team Trading Profit') }}</a>
            <a href="{{ url('admin/income/daily-team-investment-share') }}"
                class="menu-item {{ (request()->is('admin/income/daily-team-investment-share*', 'admin/income/level-incomes*') || request()->routeIs('admin.income.dailyTeamInvestmentShare')) ? 'active' : '' }}">{{ __('Daily Team Investment Share') }}</a>
            <a href="{{ url('admin/income/hero-of-the-month') }}"
                class="menu-item {{ (request()->is('admin/income/hero-of-the-month*') || request()->routeIs('admin.income.heroOfTheMonth')) ? 'active' : '' }}">{{ __('Hero of the Month') }}</a>

        </div>
    </div>

    <div
        class="nav-item {{ $isPartnershipSection ? 'active open' : '' }} has-sub">
        <a href="#"><i class="ik ik-briefcase"></i><span>{{ __('Partnership Section') }}</span></a>
        <div class="submenu-content">

            <a href="{{ url('admin/income/partnership-incomes') }}"
                class="menu-item {{ request()->is('admin/income/partnership-incomes*') ? 'active' : '' }}">{{ __('Partnership Income') }}</a>
            <a href="{{ url('admin/income/partnership-details') }}"
                class="menu-item {{ request()->is('admin/income/partnership-details*') ? 'active' : '' }}">{{ __('Partnership Details') }}</a>

        </div>
    </div>





    {{-- <div class="nav-item {{ ($segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
    <a href="#"><i class="ik ik-database"></i><span>{{ __('Resources Section')}}</span></a>
    <div class="submenu-content">
        <a href="{{url('admin/resources/resources-refund')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('Resources Refund')}}</a>
        <a href="{{url('admin/resources/refund-details')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Refund details')}}</a>
    </div>
</div> --}}

<div class="nav-item {{ ($segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
    <a href="#"><i class="ik ik-cloud"></i><span>{{ __('Payment Management')}}</span></a>
    <div class="submenu-content">
        {{-- <a href="{{url('admin/package/fund-requests')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Fund Requests')}}</a>
        <a href="{{url('admin/set-pay-mode')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Set Payment Mode')}} </a>--}}
        <a href="{{url('admin/new-withdrawal-request')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('New Withdrawal Requests')}}</a>
        {{-- <a href="{{url('admin/exchange-withdrawal-request')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('New Exchange Requests')}}</a> --}}
        <a href="{{url('admin/cancelled-request')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Cancelled Requests')}}</a>
        <a href="{{url('admin/payment-history')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Payment History')}}</a>
        <!--<a href="{{url('admin/pepe-settings')}}" class="menu-item {{ request()->is('*pepe-settings*') ? 'active' : '' }}">{{ __('PEPE Token Settings')}}</a>-->
    </div>
</div>
<div class="nav-item  {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
    <a href="{{url('admin/transaction')}}"><i class="ik ik-database"></i><span>Transaction</span> </a>
</div>
<div class="nav-item {{ ($segment1 == 'alerts' || $segment1 == 'buttons'||$segment1 == 'badges'||$segment1 == 'navigation') ? 'active open' : '' }} has-sub">
    <a href="#"><i class="ik ik-mail"></i><span>{{ __('Support')}}</span></a>
    <div class="submenu-content">
        <a href="{{url('admin/support/new-support-ticket')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('New Support Tickets')}}</a>
        <a href="{{url('admin/support/open-support-ticket')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('Open Support Tickets')}}</a>
        <a href="{{url('admin/support/close-support-ticket')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Closed Support Tickets')}}</a>
    </div>
</div>

<div class="nav-item {{ ($segment1 == 'rest-api') ? 'active' : '' }}">
    <a href="{{url('admin/logout')}}"><i class="ik ik-stop-circle"></i><span>Logout</span> </a>
</div>
<!-- end inventory pages -->


</div>
</div>
</div>