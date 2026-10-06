<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand py-3" href="{{url('hdgteyusjasget/dashboard')}}">
            <div class="logo-img text-center ">
              <img  height="51px" src="{{asset('logo/name-logo.png')}}" class="header-brand-img py-2 pe-3 pl-0" title="Admin">
            </div>
        </a>
        <div class="sidebar-action"></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
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
                
                
                 <div
                    class="nav-item {{ $segment1 == 'alerts' || $segment1 == 'buttons' || $segment1 == 'badges' || $segment1 == 'navigation' ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-credit-card"></i><span>{{ __('ROI Section') }}</span></a>
                    <div class="submenu-content">

                        <a href="{{ url('hdgteyusjasget/income/roi-incomes') }}"
                            class="menu-item {{ $segment1 == 'badges' ? 'active' : '' }}">{{ __(' Roi Income') }}</a>
                        <a href="{{ url('hdgteyusjasget/income/roi-details') }}"
                            class="menu-item {{ $segment1 == 'badges' ? 'active' : '' }}">{{ __(' Roi Details') }}</a>
                    </div>
                </div>


                <div
                    class="nav-item {{ $segment1 == 'alerts' || $segment1 == 'buttons' || $segment1 == 'badges' || $segment1 == 'navigation' ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-dollar-sign"></i><span>{{ __('Income Section') }}</span></a>
                    <div class="submenu-content">

                        <a href="{{ url('hdgteyusjasget/income/staking-level-incomes') }}"
                            class="menu-item {{ $segment1 == 'badges' ? 'active' : '' }}">{{ __('Staking Level Income') }}</a>
                        <a href="{{ url('hdgteyusjasget/income/level-incomes') }}"
                            class="menu-item {{ $segment1 == 'badges' ? 'active' : '' }}">{{ __('Level Income') }}</a>
                        <a href="{{ url('hdgteyusjasget/income/single-leg-incomes') }}"
                            class="menu-item {{ $segment1 == 'badges' ? 'active' : '' }}">{{ __('Single Leg Income') }}</a>
                        <a href="{{ url('hdgteyusjasget/income/team-withdrawal-commission-incomes') }}"
                            class="menu-item {{ $segment1 == 'badges' ? 'active' : '' }}">{{ __('Team Withdrawal Commission') }}</a>

                    </div>
                </div>

                <div
                    class="nav-item {{ $segment1 == 'alerts' || $segment1 == 'buttons' || $segment1 == 'badges' || $segment1 == 'navigation' ? 'active open' : '' }} has-sub">
                    <a href="#"><i class="ik ik-briefcase"></i><span>{{ __('Partnership Section') }}</span></a>
                    <div class="submenu-content">

                        <a href="{{ url('hdgteyusjasget/income/partnership-incomes') }}"
                            class="menu-item {{ $segment1 == 'badges' ? 'active' : '' }}">{{ __('Partnership Income') }}</a>
                        <a href="{{ url('hdgteyusjasget/income/partnership-details') }}"
                            class="menu-item {{ $segment1 == 'badges' ? 'active' : '' }}">{{ __('Partnership Details') }}</a>

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
                        <!--<a href="{{url('hdgteyusjasget/new-withdrawal-request')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('New Withdrawal Requests')}}</a>-->
                        {{-- <a href="{{url('hdgteyusjasget/exchange-withdrawal-request')}}" class="menu-item {{ ($segment1 == 'alerts') ? 'active' : '' }}">{{ __('New Exchange Requests')}}</a> --}}
                        <!--<a href="{{url('hdgteyusjasget/cancelled-request')}}" class="menu-item {{ ($segment1 == 'badges') ? 'active' : '' }}">{{ __('Cancelled Requests')}}</a>-->
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
