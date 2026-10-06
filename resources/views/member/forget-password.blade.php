<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <!-- Title -->
    <title>Forget Password | {{ config('detailsApp.name') }}</title>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="{{ config('detailsApp.name') }}">
    <meta name="robots" content="index, follow">
    <meta name="description" content="Retrieve your password for {{ config('detailsApp.name') }}.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('logo/favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('logo/favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo/favicon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('logo/favicon/site.webmanifest') }}" />

    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Authentication Theme CSS -->
    <link rel="stylesheet" href="{{ asset('uassets/css/member-auth.css') }}">
</head>

<body class="auth-page-bg antialiased selection:bg-[#F59E0B] selection:text-black">

    <!-- Ambient Lighting & Network Overlay -->
    <div class="auth-bg-ambient">
        <div class="auth-grid-overlay"></div>
    </div>

    <div class="min-h-screen flex flex-col lg:flex-row relative z-10 overflow-x-hidden">

        <!-- LEFT PANEL: Web3 Platform Showcase (Desktop 50%) -->
        <div class="hidden lg:flex lg:w-1/2 left-brand-panel p-12 lg:p-16 flex-col justify-between">

            <!-- Top Header / Brand Logo -->
            <div class="flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('logo/logo.png') }}" alt="{{ config('detailsApp.name') }}"
                        class="h-12 w-auto object-contain transition-transform group-hover:scale-105">
                </a>
                <div class="brand-badge">
                    <span class="cyan-dot cyan-dot-pulse"></span>
                    <span>Account Security</span>
                </div>
            </div>

            <!-- Center Content: Security & Password Recovery -->
            <div class="my-auto py-12 max-w-xl">
                <div class="brand-badge mb-6">
                    <i class="fa-solid fa-key text-[#F59E0B]"></i>
                    <span>Self-Service Recovery</span>
                </div>

                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-6">
                    Secure Account <br />
                    <span class="gradient-text">
                        Password Retrieval
                    </span>
                </h1>

                <p class="text-[#98A2C3] text-base leading-relaxed mb-8">
                    Lost access to your password? Enter your registered details to receive instant reset credentials
                    directly on your verified email ID.
                </p>

                <!-- Security Highlights -->
                <div class="left-stat-card space-y-4 mb-8">
                    <div class="flex items-center gap-3 text-sm text-[#F5F7FF]">
                        <i class="fa-solid fa-lock text-[#F59E0B] text-base"></i>
                        <span>Automated On-Chain Credential Dispatch</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-[#F5F7FF]">
                        <i class="fa-solid fa-envelope-circle-check text-[#F59E0B] text-base"></i>
                        <span>Instant Verification Link to Registered Email</span>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="flex items-center gap-6 text-[#98A2C3] text-xs font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-shield text-[#10B981]"></i>
                        <span>256-bit Encrypted Protocol</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer Text -->
            <div class="text-xs text-[#6F7A9B] flex items-center justify-between border-t border-white/10 pt-6">
                <span>&copy; {{ date('Y') }} {{ config('detailsApp.name') }}. All Rights Reserved.</span>
                <span class="flex items-center gap-1.5 text-[#98A2C3]">
                    <span class="w-2 h-2 rounded-full bg-[#10B981]"></span> Support Active
                </span>
            </div>
        </div>

        <!-- RIGHT PANEL: Forgot Password Card (50%) -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">

            <div class="w-full max-w-md">

                <!-- Mobile Brand Header -->
                <div class="lg:hidden text-center mb-8">
                    <img src="{{ asset('logo/logo.png') }}" alt="{{ config('detailsApp.name') }}"
                        class="h-12 mx-auto mb-3 object-contain">
                    <h2 class="text-2xl font-bold text-white">{{ config('detailsApp.name') }}</h2>
                    <p class="text-xs text-[#98A2C3]">Forget Password</p>
                </div>

                <!-- Glassmorphic Card -->
                <div class="auth-glass-card p-8 sm:p-10">
                    <div class="auth-card-top-bar"></div>

                    <!-- Card Header -->
                    <div class="mb-8 text-center sm:text-left">
                        <div class="brand-badge mb-3">
                            <i class="fa-solid fa-unlock-keyhole text-[#F59E0B]"></i>
                            <span>Recovery Portal</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Forget Password
                        </h2>
                        <p class="text-[#98A2C3] text-sm mt-1.5">
                            Retrieve your password on your registered email address.
                        </p>
                    </div>

                    <!-- Alert Messages -->
                    @if (session()->has('succMsg'))
                        <div class="auth-alert-success mb-6 flex items-start gap-3">
                            <i class="fa-solid fa-circle-check text-[#10B981] text-base mt-0.5"></i>
                            <div>
                                <span class="font-bold text-emerald-200">Success:</span>
                                <p class="mt-0.5 text-xs text-emerald-300">{{ session('succMsg') }}</p>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('failedMsg'))
                        <div class="auth-alert-error mb-6 flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation text-red-400 text-base mt-0.5"></i>
                            <div>
                                <span class="font-semibold text-red-200">Notice:</span>
                                <p class="mt-0.5 text-xs text-red-300">{{ session('failedMsg') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Form (Preserves action and POST parameters) -->
                    <form method="POST" action="{{ route('retrivePassword') }}" class="space-y-6">
                        @csrf

                        <!-- Member ID / Email Field -->
                        <div>
                            <label class="block text-xs font-bold text-[#98A2C3] uppercase tracking-wider mb-1.5">
                                Member ID or Email <span class="text-red-400">*</span>
                            </label>
                            <div class="auth-field-wrapper">
                                <i class="fa-solid fa-user-shield auth-input-icon"></i>
                                <input type="text" name="member_id" class="auth-input auth-input-has-icon"
                                    placeholder="Enter Member ID or Email Address" required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button type="submit" class="btn-auth-primary">
                                <i class="fa-solid fa-paper-plane text-sm"></i>
                                <span>Retrieve Password</span>
                            </button>
                        </div>
                    </form>

                    <p class="mt-4 text-center text-xs text-[#98A2C3] font-medium">
                        Password credentials will be sent to your registered email ID.
                    </p>

                    <!-- Back to Login Footer -->
                    <div class="mt-8 pt-6 border-t border-white/10 text-center">
                        <a href="{{ url('/member') }}" class="auth-link inline-flex items-center gap-2 text-xs">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Back to Sign In</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Vendor Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
</body>

</html>
