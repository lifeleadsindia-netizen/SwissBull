<header class="header-top" header-theme="light">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <div class="top-menu d-flex align-items-center">
                <button type="button" class="btn-icon mobile-nav-toggle d-lg-none"><span></span></button>

                <button type="button" id="navbar-fullscreen" class="nav-link"><i class="ik ik-maximize"></i></button>
            </div>
            <div class="top-menu d-flex align-items-center">

                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">

                        <img class="avatar" src="{{ asset('uploads/avatar.jpg')}}" alt="">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{url('hdgteyusjasget/profile')}}"><i
                                class="ik ik-user dropdown-icon"></i> {{ __('Profile')}}</a>

                        <a class="dropdown-item" href="{{ url('hdgteyusjasget/logout') }}">
                            <i class="ik ik-power dropdown-icon"></i>
                            {{ __('Logout')}}
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>