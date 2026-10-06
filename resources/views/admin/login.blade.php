<!doctype html>
<html lang="en">

<head>
    
    <!-- FAVICONS ICON -->
    <link rel="icon" type="image/png" href="{{ asset('logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('logo/favicon/site.webmanifest') }}" />

    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | {{config('detailsApp.name')}}</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/icon-kit/dist/css/iconkit.min.css') }}">

    <style>
        :root {
            --primary-blue: #3B6CFF;
            --electric-blue: #2563FF;
            --purple-accent: #7C4DFF;
            --cyan-accent: #22D3EE;
            --dark-bg: #070B18;
            --card-bg: rgba(15, 22, 45, 0.85);
            --text-gray: #98A2C3;
            --gradient-primary: linear-gradient(135deg, #3B6CFF 0%, #7C4DFF 100%);
            --gradient-primary-hover: linear-gradient(135deg, #4B7BFF 0%, #8B5CF6 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--dark-bg);
            color: #fff;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Ambient Grid Overlay */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
            opacity: 0.6;
        }

        /* Responsive Layout Fix */
        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            z-index: 2;
        }

        /* Glassmorphism Card */
        .auth-card {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 24px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3B6CFF 0%, #7C4DFF 50%, #22D3EE 100%);
        }

        .logo-box {
            text-align: center;
            height: 90px;
        }

        .logo-box img {
            height: 60px;
            object-fit: contain;
            filter: drop-shadow(0 0 12px rgba(59, 108, 255, 0.35));
        }

        h3 {
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 8px;
            text-align: center;
            color: #FFFFFF;
        }

        .subtitle {
            color: var(--text-gray);
            text-align: center;
            font-size: 14px;
            margin-bottom: 30px;
        }

        /* Floating Input Style */
        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--cyan-accent);
            font-size: 18px;
            z-index: 5;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.035) !important;
            border: 1px solid rgba(255, 255, 255, 0.10) !important;
            border-radius: 14px !important;
            padding: 12px 12px 12px 48px !important;
            color: #fff !important;
            height: 55px;
            transition: 0.3s ease;
        }

        .form-control::placeholder {
            color: #6F7A9B !important;
        }

        .form-control:focus {
            border-color: #4A6FFF !important;
            box-shadow: 0 0 0 3px rgba(74, 111, 255, 0.18) !important;
            background: rgba(255, 255, 255, 0.06) !important;
        }

        /* OTP Button */
        .btn-otp {
            background: rgba(59, 108, 255, 0.12);
            color: var(--cyan-accent);
            border: 1px solid rgba(59, 108, 255, 0.35);
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 16px;
            float: right;
            margin-bottom: 12px;
            transition: 0.3s ease;
            text-decoration: none;
        }

        .btn-otp:hover {
            background: var(--primary-blue);
            color: #ffffff;
            border-color: var(--primary-blue);
            box-shadow: 0 4px 15px rgba(59, 108, 255, 0.35);
        }

        /* Main Action Button */
        .btn-login {
            background: var(--gradient-primary);
            border: none;
            border-radius: 14px;
            color: #FFFFFF;
            font-weight: 700;
            height: 54px;
            width: 100%;
            margin-top: 10px;
            font-size: 16px;
            box-shadow: 0 10px 30px rgba(59, 108, 255, 0.35);
            transition: all 0.25s ease;
        }

        .btn-login:hover {
            background: var(--gradient-primary-hover);
            transform: translateY(-1.5px);
            box-shadow: 0 14px 35px rgba(124, 77, 255, 0.45);
            color: #ffffff;
        }

        /* Background Decoration */
        .bg-circle {
            position: absolute;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            z-index: 1;
            filter: blur(80px);
            pointer-events: none;
        }

        .circle-1 {
            top: -150px;
            left: -150px;
            background: radial-gradient(circle, rgba(59, 108, 255, 0.20) 0%, transparent 70%);
        }

        .circle-2 {
            bottom: -150px;
            right: -150px;
            background: radial-gradient(circle, rgba(124, 77, 255, 0.20) 0%, transparent 70%);
        }

        /* Link Styling */
        .forgot-link {
            color: var(--cyan-accent);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .auth-card {
                padding: 30px 20px;
                background: var(--card-bg);
            }
        }
    </style>
    
</head>

<body>

    <div class="bg-circle circle-1"></div>
    <div class="bg-circle circle-2"></div>

    <div class="login-container">
        <div class="auth-card">
            <div class="logo-box" style="height: 70px;">
                <img src="{{ asset('logo/logo.png') }}" alt="{{ config('detailsApp.name') }}">
            </div>

            <h3>Welcome Back</h3>

            {{-- @if (session('OtpMsg'))
                <div class="alert alert-success py-2 mt-4"
                    style="font-size: 13px;border-radius: 10px;background: rgb(68 239 130 / 10%);border: 1px solid rgb(68 239 103 / 30%);color: #a5fcbd;">
                    {{ session('OtpMsg') }}
                </div>
            @endif --}}
            
            @if (session('loginmsg'))
                <div class="alert alert-danger py-2 mt-3"
                    style="font-size: 13px; border-radius: 10px; background: rgba(220,53,69,0.1); border: 1px solid #dc3545; color: #ff8e98;">
                    {{ session('loginmsg') }}
                </div>
            @endif

            <form method="POST" action="{{ route('adminLogin') }}">
                @csrf

                {{-- <div class="clearfix mt-4" style="justify-self: center;">
                    <a href="{{ url('/send-admin-otp') }}" class="btn-otp">Get OTP</a>
                </div>
                @if (session('admin_otp'))
                     <span>{{session('admin_otp')}}</span>
                @endif

                <div class="input-group-custom">
                    <i class="ik ik-shield"></i>
                    <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                </div> --}}

                <div class="input-group-custom" style="margin-top: 20px;">
                    <i class="ik ik-user"></i>
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>

                <div class="input-group-custom">
                    <i class="ik ik-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <a href="{{ url('/forget-password') }}" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-login">Sign In Now</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('adm_assets/assets/src/js/vendor/jquery-3.3.1.min.js') }}"></script>
</body>

</html>
