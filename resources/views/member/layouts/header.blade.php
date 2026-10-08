<!DOCTYPE html>
<html lang="en">

<head>
    <!--Title-->
    <title>@yield('title') || {{ config('detailsApp.name') }}</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Dexignlabs">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- whatsapp icon link --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Datatable -->
    <link href="{{ asset('uassets/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <!-- FAVICONS ICON -->
    <link rel="icon" type="image/png" href="{{ asset('logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('logo/favicon/site.webmanifest') }}" />

    <link href="{{ asset('uassets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('uassets/vendor/swiper/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">

    <!-- Style css -->
    <link href="{{ asset('uassets/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('uassets/css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('uassets/icons/line-awesome/css/line-awesome.min.css') }}" rel="stylesheet">



    <script>
        window.onload = function() {
            showPopup();
        };

        function showPopup() {
            document.getElementById('popup').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>

    <script>
        function closeProfileDropdown() {
            const profile = document.querySelector('.header-profile2');
            const menu = profile ? profile.querySelector('.dropdown-menu') : null;
            if (profile) profile.classList.remove('show');
            if (menu) {
                menu.classList.remove('show');
                menu.style.display = 'none';
            }
        }

        function toggleProfileDropdown(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            // Close notification popup if open
            const notifPanel = document.getElementById('headerNotifPanel');
            if (notifPanel) {
                notifPanel.style.display = 'none';
                notifPanel.classList.remove('active');
            }
            const profile = document.querySelector('.header-profile2');
            const menu = profile ? profile.querySelector('.dropdown-menu') : null;
            if (!profile || !menu) return;

            const isOpen = profile.classList.contains('show') || menu.classList.contains('show') || menu.style.display ===
                'block';
            if (isOpen) {
                closeProfileDropdown();
            } else {
                profile.classList.add('show');
                menu.classList.add('show');
                menu.style.display = 'block';
            }
        }

        function openNotifPopup(e) {
            e.preventDefault();
            e.stopPropagation();
            closeProfileDropdown();
            const panel = document.getElementById('headerNotifPanel');
            if (!panel) return;
            if (panel.style.display === 'block') {
                panel.style.display = 'none';
                panel.classList.remove('active');
            } else {
                panel.style.display = 'block';
                panel.classList.add('active');
            }
        }
        document.addEventListener('click', function(ev) {
            const panel = document.getElementById('headerNotifPanel');
            if (panel) {
                const bell = ev.target.closest('.notification_dropdown .nav-link.bell');
                if (!bell && !panel.contains(ev.target)) {
                    panel.style.display = 'none';
                    panel.classList.remove('active');
                }
            }

            const profile = document.querySelector('.header-profile2');
            if (profile) {
                const trigger = ev.target.closest('.header-profile2 .nav-link, .header-profile2 .header-info2');
                const menu = profile.querySelector('.dropdown-menu');
                if (!trigger && menu && !menu.contains(ev.target)) {
                    closeProfileDropdown();
                }
            }
        });

        function markAllRead() {
            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                "{{ csrf_token() }}";
            fetch("{{ route('member.notifications.readAll') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                }
            }).then(r => r.json()).then(res => {
                if (res.success) {
                    var badge = document.querySelector('.notification_dropdown .nav-link span.notif-badge');
                    if (badge) badge.remove();

                    // Change all badges in header list to 'Read'
                    document.querySelectorAll('#headerNotifList .badge').forEach(function(b) {
                        if (b.innerText.trim() === 'NEW') {
                            b.innerText = 'Read';
                            b.style.background = 'rgba(255,255,255,0.1)';
                            b.style.color = '#94a3b8';
                        }
                    });

                    // Reset background on items
                    document.querySelectorAll('#headerNotifList .header-notif-item').forEach(function(item) {
                        item.style.background = 'rgba(255,255,255,0.03)';
                        item.style.borderColor = 'rgba(255,255,255,0.07)';
                    });

                    if (typeof window.onHeaderMarkAllRead === 'function') {
                        window.onHeaderMarkAllRead();
                    }
                }
            }).catch(console.error);
        }
    </script>
    <script>
        (function() {
            function lockMaterialIcons() {
                document.querySelectorAll('.material-symbols-outlined').forEach(function(el) {
                    var original = (el.getAttribute('data-icon') || el.textContent || '').trim();
                    if (!original) return;
                    el.setAttribute('data-icon', original);
                    el.setAttribute('translate', 'no');
                    el.classList.add('notranslate');
                    if ((el.textContent || '').trim() !== original) {
                        el.textContent = original;
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                lockMaterialIcons();
                var target = document.getElementById('menu') || document.body;
                var observer = new MutationObserver(lockMaterialIcons);
                observer.observe(target, {
                    subtree: true,
                    childList: true,
                    characterData: true
                });
            });
        })();
    </script>
    <style>
        .VIpgJd-ZVi9od-ORHb-OEVmcd {
            display: none !important;
        }

        .header-left .search-area {
            display: none !important;
        }

        .language-selector-wrapper {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .language-icon {
            font-size: 16px;
            line-height: 1;
        }

        #google_translate_element .goog-te-gadget {
            font-size: 0 !important;
            line-height: 1;
            color: transparent !important;
        }

        #google_translate_element .goog-te-combo {
            margin: 0 !important;
            min-height: 32px;
            max-width: 138px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.96);
            color: #111827;
            font-size: 13px;
            padding: 4px 8px;
        }

        @media (max-width: 767.98px) {
            body {
                padding-right: 0px !important;
            }

            .notification_dropdown.sm-search {
                display: none !important;
            }

            .header .header-content {
                padding-left: 10px;
                padding-right: 10px;
            }

            .header .navbar-collapse {
                display: flex !important;
                align-items: center;
                justify-content: flex-end !important;
            }

            .header .header-right {
                margin-left: auto !important;
                display: flex;
                align-items: center;
                flex-wrap: nowrap;
                gap: 2px;
            }

            .header .header-right>li {
                margin: 0 !important;
            }

            .header .header-right .nav-link {
                padding: 0.4rem 0.45rem;
            }

            .header .header-right .notification_dropdown.me-4 {
                margin-right: 0 !important;
            }

            .language-selector-wrapper {
                gap: 2px;
                padding: 0 2px;
            }

            .language-icon {
                display: none;
            }

            #google_translate_element .goog-te-combo {
                max-width: 85px;
                min-height: 28px;
                font-size: 11px;
                padding: 2px 4px;
                border-radius: 4px;
            }

            /* Compact dropdown for mobile */
            .goog-te-menu-frame {
                max-height: 250px !important;
                max-width: 90vw !important;
                width: 160px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
                border-radius: 6px !important;
            }

            .goog-te-menu2 {
                max-height: 250px !important;
                overflow-y: auto !important;
                width: 160px !important;
            }

            .goog-te-menu2-entry {
                padding: 6px 8px !important;
                font-size: 11px !important;
                line-height: 1.3 !important;
                white-space: nowrap !important;
                overflow: hidden !important;
                text-overflow: ellipsis !important;
            }

            .goog-te-menu2-entry:hover {
                background-color: rgba(0, 0, 0, 0.08) !important;
            }

            .header .header-profile2 .header-info2 .sidebar-info {
                display: none !important;
            }

            .header .header-profile2 .header-info2 img {
                width: 34px;
                height: 34px;
            }
        }

        /* Extra compact for small phones */
        @media (max-width: 576px) {
            body {
                padding-right: 0px !important;
            }

            .language-selector-wrapper {
                gap: 1px;
                padding: 0 1px;
            }

            #google_translate_element .goog-te-combo {
                max-width: 75px;
                min-height: 26px;
                font-size: 10px;
                padding: 1px 3px;
            }

            .goog-te-menu-frame {
                max-height: 200px !important;
                width: 140px !important;
                max-width: 85vw !important;
            }

            .goog-te-menu2 {
                max-height: 200px !important;
                width: 140px !important;
            }

            .goog-te-menu2-entry {
                padding: 4px 6px !important;
                font-size: 10px !important;
            }
        }

        /* Ultra compact for very small screens */
        @media (max-width: 360px) {
            .language-selector-wrapper {
                gap: 0px;
                padding: 0;
            }

            #google_translate_element .goog-te-combo {
                max-width: 65px;
                min-height: 24px;
                font-size: 9px;
                padding: 1px 2px;
            }

            .goog-te-menu-frame {
                max-height: 180px !important;
                width: 120px !important;
                max-width: 80vw !important;
            }

            .goog-te-menu2 {
                max-height: 180px !important;
                width: 120px !important;
            }

            .goog-te-menu2-entry {
                padding: 3px 4px !important;
                font-size: 9px !important;
                line-height: 1.2 !important;
            }
        }
    </style>
    <style>
        .VIpgJd-ZVi9od-ORHb-OEVmcd {
            display: none !important;
        }

        /* Google Translate dropdown scrollbar */
        .goog-te-menu-frame {
            max-height: 300px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }

        .skiptranslate iframe {
            max-height: 300px !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }

        /* Google Translate widget iframe content scrollable */
        .VIpgJd-ZVi9od-xl07Ob-OEVmcd {
            overflow: auto !important;
            max-height: 300px !important;
        }
    </style>
    <script>
        // Inject CSS into Google Translate iframe for scrollbar
        function injectTranslateStyles() {
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.tagName === 'IFRAME') {
                            var iframe = node;
                            iframe.addEventListener('load', function() {
                                try {
                                    var doc = iframe.contentDocument || iframe.contentWindow
                                        .document;
                                    var style = doc.createElement('style');
                                    style.textContent = `
                                        body {
                                            overflow-y: auto !important;
                                            max-height: 300px !important;
                                        }
                                        table {
                                            max-height: 300px !important;
                                            overflow-y: auto !important;
                                            display: block !important;
                                        }
                                        td {
                                            display: block !important;
                                        }
                                    `;
                                    doc.head.appendChild(style);
                                    doc.body.style.overflowY = 'auto';
                                    doc.body.style.maxHeight = '300px';
                                } catch (e) {}
                            });
                        }
                    });
                });
            });

            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }

        document.addEventListener('DOMContentLoaded', injectTranslateStyles);
    </script>
</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">
        {{-- <div class="header-banner" style="background-image:url('{{asset('uassets/images/bg-1.png')}}');">

        </div> --}}
        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="#" class="brand-logo">
                <div class="py-2 text-center">
                    <img src="{{ asset('logo/name-logo.png') }}" height="36px" alt=""
                        class="text-center mt-2 d-none d-md-inline-block">
                    <img src="{{ asset('logo/logo.png') }}" height="25px" alt=""
                        class="text-center mt-1 d-inline-block d-md-none me-2">
                </div>
            </a>
            <div class="nav-control me-1">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Chat box start
        ***********************************-->
        <div class="chatbox">
            <div class="chatbox-close"></div>
            <div class="custom-tab-1">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#notes">Notes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#alerts">Alerts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#chat">Chat</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="chat" role="tabpanel">
                        <div class="card mb-sm-3 mb-md-0 contacts_card dlab-chat-user-box">
                            <div class="card-header chat-list-header text-center">
                                <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect fill="#000000" x="4" y="11" width="16" height="2"
                                                rx="1" />
                                            <rect fill="#000000" opacity="0.3"
                                                transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) "
                                                x="4" y="11" width="16" height="2" rx="1" />
                                        </g>
                                    </svg></a>
                                <div>
                                    <h6 class="mb-1">Chat List</h6>
                                    <p class="mb-0">Show All</p>
                                </div>
                                <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <circle fill="#000000" cx="5" cy="12" r="2" />
                                            <circle fill="#000000" cx="12" cy="12" r="2" />
                                            <circle fill="#000000" cx="19" cy="12" r="2" />
                                        </g>
                                    </svg></a>
                            </div>
                            <div class="card-body contacts_body p-0 dlab-scroll  " id="DLAB_W_Contacts_Body">
                                <ul class="contacts">
                                    <li class="name-first-letter">A</li>
                                    <li class="active dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/1.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Archie Parker</span>
                                                <p>Kalid is online</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/2.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon offline"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Alfie Mason</span>
                                                <p>Taherah left 7 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/3.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>AharlieKane</span>
                                                <p>Sami is online</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/4.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon offline"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Athan Jacoby</span>
                                                <p>Nargis left 30 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="name-first-letter">B</li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/5.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon offline"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Bashid Samim</span>
                                                <p>Rashid left 50 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/1.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Breddie Ronan</span>
                                                <p>Kalid is online</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/2.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon offline"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Ceorge Carson</span>
                                                <p>Taherah left 7 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="name-first-letter">D</li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/3.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Darry Parker</span>
                                                <p>Sami is online</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/4.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon offline"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Denry Hunter</span>
                                                <p>Nargis left 30 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="name-first-letter">J</li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/5.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon offline"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Jack Ronan</span>
                                                <p>Rashid left 50 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/1.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Jacob Tucker</span>
                                                <p>Kalid is online</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/2.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon offline"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>James Logan</span>
                                                <p>Taherah left 7 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/3.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Joshua Weston</span>
                                                <p>Sami is online</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="name-first-letter">O</li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/4.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon offline"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Oliver Acker</span>
                                                <p>Nargis left 30 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="dlab-chat-user">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img src="images/avatar/5.jpg" class="rounded-circle user_img"
                                                    alt="">
                                                <span class="online_icon offline"></span>
                                            </div>
                                            <div class="user_info">
                                                <span>Oscar Weston</span>
                                                <p>Rashid left 50 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card chat dlab-chat-history-box d-none">
                            <div class="card-header chat-list-header text-center">
                                <a href="javascript:void(0);" class="dlab-chat-history-back">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24" />
                                            <rect fill="#000000" opacity="0.3"
                                                transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000) "
                                                x="14" y="7" width="2" height="10" rx="1" />
                                            <path
                                                d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z"
                                                fill="#000000" fill-rule="nonzero"
                                                transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) " />
                                        </g>
                                    </svg>
                                </a>
                                <div>
                                    <h6 class="mb-1">Chat with Khelesh</h6>
                                    <p class="mb-0 text-success">Online</p>
                                </div>
                                <div class="dropdown">
                                    <a href="javascript:void(0);" data-bs-toggle="dropdown"
                                        aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <circle fill="#000000" cx="5" cy="12" r="2" />
                                                <circle fill="#000000" cx="12" cy="12" r="2" />
                                                <circle fill="#000000" cx="19" cy="12" r="2" />
                                            </g>
                                        </svg></a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li class="dropdown-item"><i class="fa fa-user-circle text-primary me-2"></i>
                                            View profile</li>
                                        <li class="dropdown-item"><i class="fa fa-users text-primary me-2"></i> Add to
                                            btn-close friends</li>
                                        <li class="dropdown-item"><i class="fa fa-plus text-primary me-2"></i> Add to
                                            group</li>
                                        <li class="dropdown-item"><i class="fa fa-ban text-primary me-2"></i> Block
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body msg_card_body dlab-scroll" id="DLAB_W_Contacts_Body3">
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                    <div class="msg_cotainer">
                                        Hi, how are you samim?
                                        <span class="msg_time">8:40 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        Hi Khalid i am good tnx how about you?
                                        <span class="msg_time_send">8:55 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                    <div class="msg_cotainer">
                                        I am good too, thank you for your chat template
                                        <span class="msg_time">9:00 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        You are welcome
                                        <span class="msg_time_send">9:05 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                    <div class="msg_cotainer">
                                        I am looking for your next templates
                                        <span class="msg_time">9:07 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        Ok, thank you have a good day
                                        <span class="msg_time_send">9:10 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                    <div class="msg_cotainer">
                                        Bye, see you
                                        <span class="msg_time">9:12 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                    <div class="msg_cotainer">
                                        Hi, how are you samim?
                                        <span class="msg_time">8:40 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        Hi Khalid i am good tnx how about you?
                                        <span class="msg_time_send">8:55 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                    <div class="msg_cotainer">
                                        I am good too, thank you for your chat template
                                        <span class="msg_time">9:00 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        You are welcome
                                        <span class="msg_time_send">9:05 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                    <div class="msg_cotainer">
                                        I am looking for your next templates
                                        <span class="msg_time">9:07 AM, Today</span>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        Ok, thank you have a good day
                                        <span class="msg_time_send">9:10 AM, Today</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/2.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="images/avatar/1.jpg" class="rounded-circle user_img_msg"
                                            alt="">
                                    </div>
                                    <div class="msg_cotainer">
                                        Bye, see you
                                        <span class="msg_time">9:12 AM, Today</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer type_msg">
                                <div class="input-group">
                                    <textarea class="form-control" placeholder="Type your message..."></textarea>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary"><i
                                                class="fa fa-location-arrow"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="alerts" role="tabpanel">
                        <div class="card mb-sm-3 mb-md-0 contacts_card">
                            <div class="card-header chat-list-header text-center">
                                <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <circle fill="#000000" cx="5" cy="12" r="2" />
                                            <circle fill="#000000" cx="12" cy="12" r="2" />
                                            <circle fill="#000000" cx="19" cy="12" r="2" />
                                        </g>
                                    </svg></a>
                                <div>
                                    <h6 class="mb-1">Notications</h6>
                                    <p class="mb-0">Show All</p>
                                </div>
                                <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                            <path
                                                d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                                                fill="#000000" fill-rule="nonzero" />
                                        </g>
                                    </svg></a>
                            </div>
                            <div class="card-body contacts_body p-0 dlab-scroll" id="DLAB_W_Contacts_Body1">
                                <ul class="contacts">
                                    <li class="name-first-letter">SEVER STATUS</li>
                                    <li class="active">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont primary">KK</div>
                                            <div class="user_info">
                                                <span>David Nester Birthday</span>
                                                <p class="text-primary">Today</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="name-first-letter">SOCIAL</li>
                                    <li>
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont success">RU</div>
                                            <div class="user_info">
                                                <span>Perfection Simplified</span>
                                                <p>Jame Smith commented on your status</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="name-first-letter">SEVER STATUS</li>
                                    <li>
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont primary">AU</div>
                                            <div class="user_info">
                                                <span>AharlieKane</span>
                                                <p>Sami is online</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont info">MO</div>
                                            <div class="user_info">
                                                <span>Athan Jacoby</span>
                                                <p>Nargis left 30 mins ago</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="notes">
                        <div class="card mb-sm-3 mb-md-0 note_card">
                            <div class="card-header chat-list-header text-center">
                                <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect fill="#000000" x="4" y="11" width="16" height="2"
                                                rx="1" />
                                            <rect fill="#000000" opacity="0.3"
                                                transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) "
                                                x="4" y="11" width="16" height="2" rx="1" />
                                        </g>
                                    </svg></a>
                                <div>
                                    <h6 class="mb-1">Notes</h6>
                                    <p class="mb-0">Add New Nots</p>
                                </div>
                                <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                            <path
                                                d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                                                fill="#000000" fill-rule="nonzero" />
                                        </g>
                                    </svg></a>
                            </div>
                            <div class="card-body contacts_body p-0 dlab-scroll" id="DLAB_W_Contacts_Body2">
                                <ul class="contacts">
                                    <li class="active">
                                        <div class="d-flex bd-highlight">
                                            <div class="user_info">
                                                <span>New order placed..</span>
                                                <p>10 Aug 2020</p>
                                            </div>
                                            <div class="ms-auto">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary btn-xs sharp me-1"><i
                                                        class="fas fa-pencil-alt"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex bd-highlight">
                                            <div class="user_info">
                                                <span>Youtube, a video-sharing website..</span>
                                                <p>10 Aug 2020</p>
                                            </div>
                                            <div class="ms-auto">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary btn-xs sharp me-1"><i
                                                        class="fas fa-pencil-alt"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex bd-highlight">
                                            <div class="user_info">
                                                <span>john just buy your product..</span>
                                                <p>10 Aug 2020</p>
                                            </div>
                                            <div class="ms-auto">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary btn-xs sharp me-1"><i
                                                        class="fas fa-pencil-alt"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="d-flex bd-highlight">
                                            <div class="user_info">
                                                <span>Athan Jacoby</span>
                                                <p>10 Aug 2020</p>
                                            </div>
                                            <div class="ms-auto">
                                                <a href="javascript:void(0);"
                                                    class="btn btn-primary btn-xs sharp me-1"><i
                                                        class="fas fa-pencil-alt"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i
                                                        class="fa fa-trash"></i></a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Chat box End
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="input-group search-area">
                                <input type="text" class="form-control" placeholder="Search here...">
                                <span class="input-group-text"><a href="javascript:void(0)">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path
                                                    d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                <path
                                                    d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                                                    fill="#000000" fill-rule="nonzero"></path>
                                            </g>
                                        </svg>
                                    </a></span>
                            </div>
                        </div>
                        <ul class="navbar-nav header-right">
                            <li class="nav-item notification_dropdown">
                                <div class="nav-link">
                                    <div class="language-selector-wrapper">
                                        <div id="google_translate_element"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell" href="javascript:void(0);" onclick="openNotifPopup(event)"
                                    role="button" aria-expanded="false" style="position: relative;">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="32px" height="32px" viewBox="0 0 24 24" version="1.1"
                                        class="svg-main-icon">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <path
                                                d="M17,12 L18.5,12 C19.3284271,12 20,12.6715729 20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 C4,12.6715729 4.67157288,12 5.5,12 L7,12 L7.5582739,6.97553494 C7.80974924,4.71225688 9.72279394,3 12,3 C14.2772061,3 16.1902508,4.71225688 16.4417261,6.97553494 L17,12 Z"
                                                fill="#fff"></path>
                                            <rect fill="#fff" opacity="0.3" x="10" y="16" width="4"
                                                height="4" rx="2">
                                            </rect>
                                        </g>
                                    </svg>
                                    @php
                                        $navUnread =
                                            $unreadNotifCount ??
                                            (isset($headerNotifications)
                                                ? $headerNotifications
                                                    ->reject(fn($n) => in_array((int) $n->id, $readIds ?? [], true))
                                                    ->count()
                                                : 0);
                                        $navItems = $headerNotifications ?? ($notifications ?? collect());
                                    @endphp
                                    @if ($navUnread > 0)
                                        <span class="notif-badge"
                                            style="position: absolute; top: -1px; right: 2px; background: #ef4444; color: #fff; border-radius: 50%; padding: 1px 5px; font-size: 10.5px; font-weight: 800; min-width: 18px; text-align: center; box-shadow: 0 0 10px rgba(239, 68, 68, 0.9); line-height: 16px;">{{ $navUnread > 99 ? '99+' : $navUnread }}</span>
                                    @endif
                                </a>

                                <style>
                                    .header-notif-panel {
                                        position: absolute;
                                        right: 0;
                                        top: 48px;
                                        width: 320px;
                                        max-height: 400px;
                                        overflow-y: auto;
                                        background: #0c0e14 !important;
                                        border: 1px solid rgba(245, 158, 11, 0.35) !important;
                                        border-radius: 14px !important;
                                        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.7) !important;
                                        padding: 14px;
                                        display: none;
                                        z-index: 99999;
                                    }

                                    .header-notif-panel.active {
                                        display: block !important;
                                    }

                                    .header-notif-item {
                                        padding: 10px 12px;
                                        margin-bottom: 8px;
                                        border-radius: 10px;
                                        transition: all 0.2s ease;
                                    }

                                    .header-notif-item:hover {
                                        transform: translateY(-1px);
                                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
                                    }
                                </style>

                                <div class="header-notif-panel" id="headerNotifPanel">
                                    <div
                                        class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom border-secondary">
                                        <span class="text-white font-weight-bold" style="font-size:13px;"><i
                                                class="fas fa-bell me-1 text-warning"></i> Notifications</span>
                                        <a href="javascript:void(0);" onclick="markAllRead();"
                                            style="font-size:11px; color:#f59e0b; text-decoration:none; font-weight: 600;">Mark
                                            all read</a>
                                    </div>
                                    <div id="headerNotifList">
                                        @if ($navItems->count() > 0)
                                            @foreach ($navItems->take(6) as $n)
                                                @php
                                                    $isItemRead =
                                                        in_array((int) $n->id, $readIds ?? [], true) ||
                                                        in_array((string) $n->id, $readIds ?? [], true);
                                                    $isSpecific =
                                                        strtolower(trim($n->type ?? '')) === 'specific member' ||
                                                        !empty($n->memberid);
                                                @endphp
                                                <a href="{{ url('/member/notification/' . $n->id) }}"
                                                    class="header-notif-item"
                                                    style="cursor:pointer; display:block; text-decoration:none; background: {{ $isItemRead ? 'rgba(255,255,255,0.03)' : 'rgba(245, 158, 11, 0.10)' }}; border: 1px solid {{ $isItemRead ? 'rgba(255,255,255,0.06)' : 'rgba(245, 158, 11, 0.25)' }};">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-1">
                                                        <div class="header-notif-title"
                                                            style="color: #ffffff; font-weight: 700; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                                                            {{ $n->title ?? 'Notification' }}
                                                        </div>
                                                        <div>
                                                            @if (!$isItemRead)
                                                                <span class="badge"
                                                                    style="background: #ef4444; color: #fff; font-size: 9px; padding: 2px 6px; border-radius: 4px; font-weight: 700;">NEW</span>
                                                            @else
                                                                <span class="badge"
                                                                    style="background: rgba(255,255,255,0.1); color: #94a3b8; font-size: 9px; padding: 2px 6px; border-radius: 4px;">Read</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="header-notif-msg"
                                                        style="color: #cbd5e1; font-size: 12px; line-height: 1.4;">
                                                        {{ Str::limit($n->message ?? '-', 65) }}
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center mt-1"
                                                        style="font-size: 10.5px; color: #94a3b8;">
                                                        <span><i class="far fa-clock me-1 text-warning"></i>
                                                            {{ date('d-m-Y h:i A', strtotime($n->created_at ?? now())) }}</span>
                                                        <span class="badge"
                                                            style="{{ $isSpecific ? 'background: rgba(239, 68, 68, 0.2); color: #f87171;' : 'background: rgba(245, 158, 11, 0.2); color: #fbbf24;' }} font-size: 9px; padding: 2px 6px; border-radius: 4px;">
                                                            {{ $isSpecific ? 'Personal' : 'All Users' }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        @else
                                            <div class="header-notif-item text-center py-4"
                                                style="background: rgba(255,255,255,0.02); border-radius: 10px; border: 1px dashed rgba(255,255,255,0.1);">
                                                <div class="header-notif-title"
                                                    style="color: #94a3b8; font-size: 13px; font-weight: 600;">No
                                                    notifications yet</div>
                                                <div class="header-notif-msg"
                                                    style="color: #64748b; font-size: 11px; margin-top: 4px;">All
                                                    platform announcements will appear here.</div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-2 text-center border-top border-secondary pt-2">
                                        <a href="{{ url('/member/notifications') }}"
                                            style="font-size: 11px; color: #F59E0B; text-decoration: none; font-weight: 600;">View
                                            All Notifications &rarr;</a>
                                    </div>
                                </div>
                            </li>

                            <li>
                                <div class="dropdown header-profile2" id="headerProfileDropdown">
                                    <a class="nav-link" href="javascript:void(0);" role="button"
                                        onclick="toggleProfileDropdown(event)" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <div class="header-info2 d-flex align-items-center">
                                            <div class="d-flex align-items-center sidebar-info">
                                                {{-- <div>
                                                    <h5 class="mb-0 text-white">{{ getName($data['memberid']) }}</h5>
                                                    <span class="d-block text-end">{{ $data['memberid'] }}</span>
                                                </div> --}}
                                            </div>
                                            @if ($data['profile_image'] != '')
                                                <img src="{{ asset('uploads') }}/{{ $data['profile_image'] }}"
                                                    alt="">
                                            @else
                                                <img src="{{ asset('uploads/avatar.jpg') }}" alt="">
                                            @endif
                                        </div>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a href="{{ url('/member/profile/profile') }}"
                                            class="dropdown-item ai-icon ">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                height="24px" viewBox="0 0 24 24" version="1.1"
                                                class="svg-main-icon">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                    <path
                                                        d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                                                        fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                    <path
                                                        d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                                        fill="var(--primary)" fill-rule="nonzero" />
                                                </g>
                                            </svg>
                                            <span class="ms-2">Profile </span>
                                        </a>
                                        <a href="{{ url('/member/wallet/withdrawal') }}"
                                            class="dropdown-item ai-icon ">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                height="24px" viewBox="0 0 24 24" version="1.1"
                                                class="svg-main-icon">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path
                                                        d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z"
                                                        fill="#000000" />
                                                    <circle fill="var(--primary)" opacity="0.3" cx="19.5"
                                                        cy="17.5" r="2.5" />
                                                </g>
                                            </svg>
                                            <span class="ms-2">Wallet </span>
                                        </a>
                                        <a href="{{ url('/member/logout') }}" class="dropdown-item ai-icon">
                                            <svg class="logout" xmlns="http://www.w3.org/2000/svg" width="18"
                                                height="18" viewBox="0 0 24 24" fill="none" stroke="#fd5353"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                                <polyline points="16 17 21 12 16 7"></polyline>
                                                <line x1="21" y1="12" x2="9" y2="12">
                                                </line>
                                            </svg>
                                            <span class="ms-2 text-danger">Logout </span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="feature-box style-3">
                <a href="{{ url('/member/wallet/withdrawal') }}" class="wallet-box wallet-cta text-decoration-none">
                    <span class="wallet-cta__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="50px" height="50px" viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#fff" opacity="0.3" cx="20.5" cy="12.5" r="1.5" />
                                <rect fill="#fff" opacity="0.3"
                                    transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) "
                                    x="3" y="3" width="18" height="7" rx="1" />
                                <path
                                    d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z"
                                    fill="#fff" />
                            </g>
                        </svg>
                    </span>
                    <span class="wallet-cta__content" style="overflow: auto">
                        <span class="wallet-cta__label">Income Wallet</span>
                        <h4 class="wallet-cta__amount">${{ $data['wallet'] }}</h4>
                        <small class="wallet-cta__hint">Withdraw Money</small>
                    </span>
                    <span class="wallet-cta__arrow">&#8250;</span>
                </a>
            </div>
            <span class="main-menu">Main Menu</span>
            <div class="menu-scroll">
                <div class="dlabnav-scroll">
                    <ul class="metismenu" id="menu">
                        <li>
                            <a href="{{ url('/member/dashboard') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="dashboard">dashboard</i>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/member/trading-dashboard') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="candlestick_chart">candlestick_chart</i>
                                <span class="nav-text">Trading Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/member/notifications') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="notifications">notifications</i>
                                <span class="nav-text">All Notifications</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/member/whatsapp/referral-details') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="share">share</i>
                                <span class="nav-text">Promotion Details</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/member/pepe/redeem-history') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="redeem">redeem</i>
                                <span class="nav-text">Airdrop Withdrawal</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/member/promotion-banners') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="campaign">campaign</i>
                                <span class="nav-text">Promotion Banners</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/member/business-plan-pdf') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="picture_as_pdf">picture_as_pdf</i>
                                <span class="nav-text">Business Plan PDF</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/member/business-plan-text') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="text_snippet">text_snippet</i>
                                <span class="nav-text">Business Plan Text</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/member/plan-video') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="smart_display">smart_display</i>
                                <span class="nav-text">Plan Video</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/member/tutorial-video') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="ondemand_video">ondemand_video</i>
                                <span class="nav-text">Tutorial Video</span>
                            </a>
                        </li>
                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined">account_circle</i>
                                <span class="nav-text">Profile</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('/member/profile/profile') }}">Profile</a></li>
                                <!--<li><a href="{{ url('/member/profile/security') }}">Security</a></li>-->
                                <!--<li><a href="{{ url('/member/kyc-details') }}">Kyc Details</a></li>-->
                                <!--<li><a href="{{ url('/member/qr-code') }}">QR Code</a></li>-->
                            </ul>
                        </li>
                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="account_balance">account_balance</i>
                                <span class="nav-text">Package Activation</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('member/fund/deposit-fund') }}">Deposit Fund</a>
                                </li>
                            </ul>
                        </li>
                        {{-- <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="ads_click">ads_click</i>
                                <span class="nav-text">Activation</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('member/activation') }}">Account Activation</a>
                                </li>
                                <li><a href="{{ url('member/activation-detail') }}">Account Details</a>
                                </li>
                            </ul>
                        </li> --}}
                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="">stack</i>
                                <span class="nav-text">Staking</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('member/Staking/create') }}">Create Staking</a>
                                </li>
                                <li><a href="{{ url('member/Staking/details') }}">Staking History</a>
                                </li>
                            </ul>
                        </li>

                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="monetization_on">monetization_on</i>
                                <span class="nav-text">Income Section</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('member/income/monthly-trading-profit') }}">Monthly Trading Profit</a></li>
                                <li><a href="{{ url('member/income/referral-bonus') }}">Referral Bonus</a></li>
                                <li><a href="{{ url('member/income/team-trading-profit') }}">Team Trading Profit</a></li>
                                <li><a href="{{ url('member/income/daily-team-investment-share') }}">Daily Team Investment Share</a></li>
                                <li><a href="{{ url('member/income/hero-of-the-month') }}">Hero of the Month</a></li>
                                <li><a href="{{ url('member/income/partnership-income') }}">Partnership Income</a></li>
                            </ul>
                        </li>

                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="account_tree">account_tree</i>
                                <span class="nav-text">Single Leg Section</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('member/income/single-leg-details') }}">Single Leg Details</a>
                                </li>
                                <li><a href="{{ url('member/income/single-leg-income') }}">Single Leg Income</a>
                                </li>
                            </ul>
                        </li>

                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined" data-icon="money">money</i>
                                <span class="nav-text">Partnership</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('/member/partnership/create-investment') }}">Create
                                        Investment</a></li>
                                <li><a href="{{ url('/member/partnership/investment-details') }}">Investment
                                        Details</a></li>
                                <li><a href="{{ url('/member/income/partnership/investment-incomes') }}">Partnership
                                        Income</a></li>
                            </ul>
                        </li>

                        {{-- <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined">credit_card</i>
                                <span class="nav-text">P2P Wallet</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('/member/p2p-wallet') }}">P2P Wallet</a></li>
                                <li><a href="{{ url('/member/p2p-history') }}">P2P History</a></li>
                            </ul>
                        </li> --}}
                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="account_balance_wallet">account_balance_wallet</i>
                                <span class="nav-text">Main Wallet</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('member/wallet/withdrawal') }}">Withdrawal</a></li>
                                <li><a href="{{ url('member/wallet/withdrawal-history') }}">Withdrawal History</a>
                                </li>
                                <li><a href="{{ url('member/pepe/redeem-history') }}">PEPE Redeem History</a></li>
                                <li><a href="{{ url('member/wallet/all-transaction') }}">All Transactions</a></li>
                            </ul>
                        </li>


                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="groups">groups</i>
                                <span class="nav-text">Team Details</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('member/team/geneology') }}">Level Team</a></li>
                                <li><a href="{{ url('/member/direct-team') }}">Direct Team</a></li>
                                <li><a href="{{ url('/member/level-details') }}">Level Details</a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="chat">chat</i>
                                <span class="nav-text">Support</span>
                            </a>
                            <ul aria-expanded="false">
                                <li><a href="{{ url('/member/support/create-ticket') }}">Create New Ticket</a></li>
                                <li><a href="{{ url('member/support/support-tickets') }}">Support Tickets</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ url('/member/logout') }}" aria-expanded="false">
                                <i class="material-symbols-outlined notranslate" translate="no"
                                    data-icon="logout">logout</i>
                                <span class="nav-text">Logout</span>
                            </a>
                        </li>
                    </ul>
                    <div class="support-box">

                    </div>

                </div>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->
