<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Forget Password | {{ config('detailsApp.name') }}</title>

    <!-- FAVICONS ICON -->
    <link rel="icon" type="image/png" href="{{ asset('logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('logo/favicon/site.webmanifest') }}" />

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/icon-kit/dist/css/iconkit.min.css') }}">

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --text-sub: #475569;
            --border-color: #e2e8f0;
            --card-bg: rgba(255, 255, 255, 0.90);
            --card-border: rgba(255, 255, 255, 0.95);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            width: 100%;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f0f6fd;
            background-image: url("{{ asset('admin-bg.png') }}");
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
            margin: auto;
        }

        /* Premium Glass Card */
        .auth-card {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-radius: 24px;
            padding: 42px 38px 36px;
            border: 1px solid var(--card-border);
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.10),
                        0 8px 20px -6px rgba(37, 99, 235, 0.08),
                        0 0 0 1px rgba(255, 255, 255, 0.8) inset;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            width: 100%;
        }

        /* Brand / Logo Area */
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo-img {
            max-height: 54px;
            max-width: 200px;
            object-fit: contain;
            margin-bottom: 6px;
            filter: drop-shadow(0 2px 8px rgba(0, 0, 0, 0.04));
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 18px;
            border-radius: 14px;
            background: rgba(37, 99, 235, 0.06);
            border: 1px solid rgba(37, 99, 235, 0.15);
            color: var(--primary);
            font-weight: 700;
            font-size: 18px;
            letter-spacing: -0.3px;
        }

        .brand-badge i {
            font-size: 20px;
            color: var(--primary);
        }

        .auth-title {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            font-size: 13.5px;
            color: var(--text-muted);
            font-weight: 400;
            margin: 0;
            line-height: 1.5;
        }

        /* Action Button */
        .btn-submit {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.2px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.28);
            transition: all 0.25s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.36);
            color: #ffffff;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Notice Box */
        .notice-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(241, 245, 249, 0.85);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            margin-top: 18px;
            margin-bottom: 20px;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            text-align: center;
        }

        .notice-box i {
            color: var(--primary);
            font-size: 16px;
            flex-shrink: 0;
        }

        /* Back to Login Link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .back-link i {
            font-size: 14px;
            transition: transform 0.2s ease;
        }

        .back-link:hover i {
            transform: translateX(-3px);
        }

        /* Custom Alert Messages */
        .alert-custom-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #047857;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 13.5px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .alert-custom-success i {
            font-size: 18px;
            color: #10b981;
            flex-shrink: 0;
        }

        /* Footer Note */
        .auth-footer {
            margin-top: 28px;
            text-align: center;
            font-size: 12.5px;
            color: var(--text-muted);
        }

        @media (max-width: 576px) {
            .auth-card {
                padding: 30px 20px 24px;
                border-radius: 20px;
            }

            .auth-title {
                font-size: 21px;
            }

            body {
                padding: 16px 12px;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="auth-card">
            <div class="brand-header">
                <a href="{{ url('/') }}" style="text-decoration: none;">
                    <img src="{{ asset('logo/logo.png') }}" alt="{{ config('detailsApp.name') }}" class="brand-logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                    <span class="brand-badge" style="display: none;">
                        <i class="ik ik-shield"></i>
                        <span>{{ config('detailsApp.name', 'SYNC TRADE') }}</span>
                    </span>
                </a>
                <h3 class="auth-title mt-3">Forget Password</h3>
                <p class="auth-subtitle">Retrieve your password on your registered email</p>
            </div>

            @if (session()->has('succMsg'))
                <div class="alert-custom-success">
                    <i class="ik ik-check-circle"></i>
                    <span>{{ session('succMsg') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('retrivePassword') }}">
                @csrf
                <button type="submit" class="btn-submit">
                    <span>Retrieve Password</span>
                    <i class="ik ik-send"></i>
                </button>

                <div class="notice-box">
                    <i class="ik ik-info"></i>
                    <span>Password will be sent to your registered email ID.</span>
                </div>

                <div class="text-center mt-2">
                    <a href="{{ url('/admin') }}" class="back-link">
                        <i class="ik ik-arrow-left"></i>
                        <span>Back to Login</span>
                    </a>
                </div>
            </form>

            <div class="auth-footer">
                &copy; {{ date('Y') }} {{ config('detailsApp.name', 'SYNC TRADE') }}. All rights reserved.
            </div>
        </div>
    </div>

    <script src="{{ asset('adm_assets/assets/src/js/vendor/jquery-3.3.1.min.js') }}"></script>
</body>

</html>
