<!DOCTYPE html>
<html lang="en">

<head>
    <!--Title-->
    <title>Error 404 | {{ config('detailsApp.name') }}</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Dexignlabs">
    <meta name="robots" content="index, follow">
    <meta name="keywords" content="Admin Dashboard, Bootstrap Template, FrontEnd, Web Application, Responsive Design, User Experience, Customizable, Modern UI, Dashboard Template, Admin Panel, Bootstrap 5, HTML5, CSS3, JavaScript, Admin Template, UI Kit, SASS, SCSS, Analytics, Responsive Dashboard, responsive admin dashboard, ui kit, web app, Admin Dashboard, Template, Admin, Authentication, FrontEnd Integration, Web Application UI, Bootstrap Framework, User Interface Kit, SASS Integration, Customizable Template, HTML5/CSS3, Analytics Dashboard, Admin Dashboard UI, Mobile-Friendly Design, UI Components, Dashboard Widgets, Dashboard Framework, Data Visualization, User Experience (UX), Dashboard Widgets, Real-time Analytics, Cross-Browser Compatibility, Interactive Charts, Performance Optimization, Multi-Purpose Template, Efficient Admin Tools, Modern Web Technologies, Responsive Tables, Dashboard Widgets, Invoice Management, Access Control, Modular Design, Trend Analysis, User-Friendly Interface, Crypto Trading UI, Cryptocurrency Dashboard, Trading Platform Interface, Responsive Crypto Admin, Financial Dashboard, UI Components for Crypto, Cryptocurrency Exchange, Blockchain , Crypto Portfolio Template, Crypto Market Analytics">
    <meta name="description" content="Empower your cryptocurrency trading platform with Jiade, the ultimate Crypto Trading UI Admin Bootstrap 5 Template. Seamlessly combining sleek design with the power of Bootstrap 5, Jiade offers a sophisticated and user-friendly interface for managing your crypto assets. Packed with customizable components, responsive charts, and a modern dashboard, Jiade accelerates your development process. Crafted for efficiency and aesthetics, this template is your key to creating a cutting-edge crypto trading experience. Explore Jiade today and elevate your crypto trading platform to new heights with a UI that blends functionality and style effortlessly.">
    <meta property="og:title" content="Jiade : Crypto Trading UI Admin  Bootstrap 5 Template | Dexignlabs">
    <meta property="og:description" content="Empower your cryptocurrency trading platform with Jiade, the ultimate Crypto Trading UI Admin Bootstrap 5 Template. Seamlessly combining sleek design with the power of Bootstrap 5, Jiade offers a sophisticated and user-friendly interface for managing your crypto assets. Packed with customizable components, responsive charts, and a modern dashboard, Jiade accelerates your development process. Crafted for efficiency and aesthetics, this template is your key to creating a cutting-edge crypto trading experience. Explore Jiade today and elevate your crypto trading platform to new heights with a UI that blends functionality and style effortlessly.">
    <meta property="og:image" content="https://jiade.dexignlab.com/xhtml/social-image.png">
    <meta name="format-detection" content="telephone=no">
    <meta name="twitter:title" content="Jiade : Crypto Trading UI Admin  Bootstrap 5 Template | Dexignlabs">
    <meta name="twitter:description" content="Empower your cryptocurrency trading platform with Jiade, the ultimate Crypto Trading UI Admin Bootstrap 5 Template. Seamlessly combining sleek design with the power of Bootstrap 5, Jiade offers a sophisticated and user-friendly interface for managing your crypto assets. Packed with customizable components, responsive charts, and a modern dashboard, Jiade accelerates your development process. Crafted for efficiency and aesthetics, this template is your key to creating a cutting-edge crypto trading experience. Explore Jiade today and elevate your crypto trading platform to new heights with a UI that blends functionality and style effortlessly.">
    <meta name="twitter:image" content="https://jiade.dexignlab.com/xhtml/social-image.png">
    <meta name="twitter:card" content="summary_large_image">
    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FAVICONS ICON -->
    <link rel="icon" type="image/png" href="{{ asset('logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('logo/favicon/site.webmanifest') }}" />
    <link href="{{ asset('uassets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link class="main-css" href="{{ asset('uassets/css/style.css')}}" rel="stylesheet">

    <style>
        body.signin-bg {
            min-height: 100vh;
            background-color: #000;
            overflow: hidden;
        }

        body.signin-bg .authincation {
            min-height: 100vh;
            background-image:
                linear-gradient(rgba(10, 12, 25, 0.62), rgba(10, 12, 25, 0.62)),
                url("{{ asset('logo/bg.jpeg') }}");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
            isolation: isolate;
        }

        .error-wrap {
            position: relative;
            width: 100%;
            max-width: 700px;
            text-align: center;
            padding: 2.2rem 1.2rem;
        }

        .error-code {
            font-size: clamp(86px, 16vw, 190px);
            font-weight: 800;
            line-height: .9;
            letter-spacing: .08em;
            color: #fef08a;
            text-shadow: 0 0 14px rgba(254, 240, 138, .75), 0 0 44px rgba(56, 189, 248, .35);
            animation: codeFloat 4s ease-in-out infinite, codePulse 2.6s ease-in-out infinite;
            position: relative;
            z-index: 3;
        }

        .error-code::before,
        .error-code::after {
            content: "404";
            position: absolute;
            inset: 0;
            opacity: .25;
            pointer-events: none;
        }

        .error-code::before {
            transform: translate(-4px, 1px);
            color: #38bdf8;
            animation: glitchShift 2.4s steps(2, end) infinite;
        }

        .error-code::after {
            transform: translate(4px, -1px);
            color: #f472b6;
            animation: glitchShift 3s steps(2, end) reverse infinite;
        }

        .error-title {
            color: #fef08a;
            font-weight: 700;
            margin-top: 14px;
            animation: fadeUp .9s ease;
        }

        .error-subtitle {
            color: #e2e8f0;
            max-width: 540px;
            margin: 0 auto 1.8rem;
            animation: fadeUp 1.15s ease;
        }

        .error-btn {
            position: relative;
            z-index: 3;
            box-shadow: 0 12px 30px rgba(56, 189, 248, .3);
            animation: fadeUp 1.3s ease;
        }

        .spark {
            position: absolute;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #fff;
            opacity: .7;
            animation: sparkRise linear infinite;
        }

        .spark.s1 {
            left: 12%;
            bottom: -10%;
            animation-duration: 7s;
            animation-delay: 0s;
        }

        .spark.s2 {
            left: 30%;
            bottom: -12%;
            animation-duration: 6s;
            animation-delay: 1.2s;
        }

        .spark.s3 {
            left: 48%;
            bottom: -8%;
            animation-duration: 8s;
            animation-delay: 2s;
        }

        .spark.s4 {
            left: 65%;
            bottom: -10%;
            animation-duration: 7.5s;
            animation-delay: .8s;
        }

        .spark.s5 {
            left: 82%;
            bottom: -12%;
            animation-duration: 6.8s;
            animation-delay: 1.8s;
        }

        @keyframes codeFloat {
            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-9px);
            }
        }

        @keyframes codePulse {
            0%,
            100% {
                text-shadow: 0 0 14px rgba(254, 240, 138, .75), 0 0 44px rgba(56, 189, 248, .35);
            }

            50% {
                text-shadow: 0 0 24px rgba(254, 240, 138, .95), 0 0 62px rgba(56, 189, 248, .55);
            }
        }

        @keyframes glitchShift {
            0%,
            48%,
            100% {
                clip-path: inset(0 0 0 0);
            }

            50% {
                clip-path: inset(22% 0 46% 0);
            }

            52% {
                clip-path: inset(66% 0 14% 0);
            }
        }

        @keyframes sparkRise {
            0% {
                transform: translateY(0) scale(.8);
                opacity: 0;
            }

            15% {
                opacity: .75;
            }

            100% {
                transform: translateY(-115vh) scale(1.2);
                opacity: 0;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 991px) {
            body.signin-bg .authincation {
                background-attachment: scroll;
            }

            .error-wrap {
                padding: 1.6rem .75rem;
            }
        }
    </style>

</head>

<body class="signin-bg">
    <div class="authincation d-flex flex-column flex-lg-row flex-column-fluid">
        <span class="spark s1"></span>
        <span class="spark s2"></span>
        <span class="spark s3"></span>
        <span class="spark s4"></span>
        <span class="spark s5"></span>

        <div class="container flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <div class="d-flex justify-content-center h-100 align-items-center">
                <div class="authincation-content style-2">
                    <div class="row no-gutters">
                        <div class="col-xl-12 tab-content">
                            <div class="error-wrap">
                                <div class="error-code">404</div>
                                <h2 class="error-title"><i class="fa fa-exclamation-triangle text-warning me-2"></i>The page you were looking for is not found!</h2>
                                <p class="error-subtitle">You may have mistyped the address or the page may have moved.</p>
                                <a href="{{ url('/') }}" class="btn btn-primary btn-lg error-btn">BACK TO HOMEPAGE</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
	Scripts
***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('uassets/vendor/global/global.min.js')}}"></script>
    <script src="{{ asset('uassets/vendor/bootstrap-select/dist/js/bootstrap-select.minjs')}}"></script>
    <script src="{{ asset('uassets/js/custom.minjs')}}"></script>
    <script src="{{ asset('uassets/js/dlabnav-initjs')}}"></script>
    <script src="{{ asset('uassets/js/demojs')}}"></script>
    <script src="{{ asset('uassets/js/styleSwitcherjs')}}"></script>

</body>

</html>
