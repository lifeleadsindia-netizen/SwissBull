<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <!-- Title -->
    <title>Create Account | {{ config('detailsApp.name') }}</title>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="{{ config('detailsApp.name') }}">
    <meta name="robots" content="index, follow">
    <meta name="description" content="Register your Web3 account on {{ config('detailsApp.name') }}.">
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

    <!-- Legacy Vendor CSS -->
    <link href="{{ asset('uassets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">

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
            <div class="flex items-center justify-between mb-5">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('logo/logo.png') }}" alt="{{ config('detailsApp.name') }}"
                        class="h-12 w-auto object-contain transition-transform group-hover:scale-105">
                </a>
                <div class="brand-badge">
                    <span class="cyan-dot cyan-dot-pulse"></span>
                    <span>BSC Network Connected</span>
                </div>
            </div>

            <!-- Center Content: Platform Visual & Steps (Matching Frontend Index) -->
            <div class="mt-12 mb-auto py-4 max-w-xl">
                <div class="brand-badge mb-4">
                    <i class="fa-solid fa-user-plus text-[#F59E0B]"></i>
                    <span>Multi-Chain Onboarding Protocol</span>
                </div>

                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-6">
                    Join The Multi-Chain <br />
                    <span class="gradient-text">
                        Crypto Portal
                    </span>
                </h1>

                <p class="text-[#98A2C3] text-base leading-relaxed mb-8">
                    Connect your Web3 crypto wallet to create your member profile, access 100+ blockchains, and start
                    earning multi-tier staking &amp; referral rewards.
                </p>

                <!-- Onboarding Step Highlights -->
                <div class="space-y-4 mb-8">
                    <div class="left-stat-card flex items-start gap-4">
                        <div
                            class="w-8 h-8 rounded-xl bg-[#F59E0B]/20 text-[#F59E0B] flex items-center justify-center font-bold text-sm shrink-0 border border-[#F59E0B]/30">
                            1</div>
                        <div>
                            <div class="text-sm font-bold text-white">Web3 Wallet Bound</div>
                            <div class="text-xs text-[#98A2C3] mt-0.5">Your connected wallet address is bound to your
                                account profile securely on-chain.</div>
                        </div>
                    </div>
                    <div class="left-stat-card flex items-start gap-4">
                        <div
                            class="w-8 h-8 rounded-xl bg-[#EF4444]/20 text-[#EF4444] flex items-center justify-center font-bold text-sm shrink-0 border border-[#EF4444]/30">
                            2</div>
                        <div>
                            <div class="text-sm font-bold text-white">Instant Email Authentication</div>
                            <div class="text-xs text-[#98A2C3] mt-0.5">Verify your email via 6-digit OTP to enable 2FA
                                protection.</div>
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="flex items-center gap-6 text-[#98A2C3] text-xs font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-[#10B981]"></i>
                        <span>Protected Registration</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-key text-[#F59E0B]"></i>
                        <span>Zero Password Risk</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer Text -->
            <div class="text-xs text-[#6F7A9B] flex items-center justify-between border-t border-white/10 pt-6">
                <span>&copy; {{ date('Y') }} {{ config('detailsApp.name') }}. All Rights Reserved.</span>
                <span class="flex items-center gap-1.5 text-[#98A2C3]">
                    <span class="w-2 h-2 rounded-full bg-[#10B981]"></span> Live Node Active
                </span>
            </div>
        </div>

        <!-- RIGHT PANEL: Registration Form Card (50%) -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-4 sm:p-8 lg:p-12">

            <div class="w-full max-w-lg">

                <!-- Mobile Brand Header -->
                <div class="lg:hidden text-center mb-6 sm:mb-8">
                    <img src="{{ asset('logo/logo.png') }}" alt="{{ config('detailsApp.name') }}"
                        class="h-10 sm:h-12 mx-auto mb-2 sm:mb-3 object-contain">
                    <h2 class="text-xl sm:text-2xl font-bold text-white">{{ config('detailsApp.name') }}</h2>
                    <p class="text-xs text-[#98A2C3]">Create Account</p>
                </div>

                <!-- Registration Glassmorphic Card -->
                <div class="auth-glass-card p-5 sm:p-8 lg:p-10">
                    <div class="auth-card-top-bar"></div>

                    <!-- Card Header -->
                    <div class="mb-6 text-center sm:text-left">
                        <div class="brand-badge mb-2">
                            <i class="fa-solid fa-user-plus text-[#F59E0B]"></i>
                            <span>New Member Onboarding</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Create Account
                        </h2>
                        <p class="text-[#98A2C3] text-sm mt-1">
                            Complete your member profile details below.
                        </p>
                    </div>

                    <!-- Connected Wallet Display Pill -->
                    <div class="mb-6">
                        <div
                            class="p-3.5 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#F59E0B]/20 text-[#F59E0B] flex items-center justify-center text-xs shrink-0 border border-[#F59E0B]/30">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-[10px] uppercase font-bold tracking-wider text-[#98A2C3]">Connected
                                        Wallet Address</div>
                                    <div class="text-xs font-mono font-bold text-white truncate"
                                        id="connectedWalletDisplay">
                                        {{ session('address') }}
                                    </div>
                                </div>
                            </div>
                            <button type="button"
                                class="px-3 py-1 rounded-xl bg-[#10B981]/20 border border-[#10B981]/40 text-[#10B981] text-xs font-bold shrink-0"
                                id="nav-forget-tab">
                                Connected <i class="fa-solid fa-circle-check text-[10px] ml-1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    @if (session()->has('failedMsg'))
                    <div class="auth-alert-error mb-6 flex items-start gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-400 text-base mt-0.5"></i>
                        <div>
                            <span class="font-semibold text-red-200">Registration Warning:</span>
                            <p class="mt-0.5 text-xs text-red-300">{{ session('failedMsg') }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Main Form (Preserves exact route, method, inputs, and JavaScript element IDs) -->
                    <form action="{{ route('userRegister') }}" method="post" class="space-y-4">
                        @csrf

                        <!-- Sponsor ID Field -->
                        <div>
                            <label for="sponsor"
                                class="block text-xs font-bold text-[#98A2C3] uppercase tracking-wider mb-1.5">
                                Sponsor ID <span class="text-red-400">*</span>
                            </label>
                            <div class="auth-field-wrapper">
                                <i class="fa-solid fa-users auth-input-icon"></i>
                                <input type="text" class="auth-input auth-input-has-icon font-semibold"
                                    id="sponsor" name="sponsorid" placeholder="Enter Referral Id"
                                    value="{{ old('sponsorid', session('sponsorid')) }}">
                            </div>
                            <div class="mt-1" id="sponhtml"></div>
                            @error('sponsorid')
                            <span class="text-xs text-red-400 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- No Sponsor Checkbox -->
                        <!--<div class="flex items-center gap-2 pt-1">-->
                        <!--    <input-->
                        <!--        class="w-4 h-4 rounded text-[#F59E0B] focus:ring-[#F59E0B] bg-white/5 border-white/20 cursor-pointer"-->
                        <!--        type="checkbox" id="noSponsorId">-->
                        <!--    <label class="text-xs text-[#98A2C3] font-medium cursor-pointer" for="noSponsorId">-->
                        <!--        Check box if you do not have a Sponsor ID.-->
                        <!--    </label>-->
                        <!--</div>-->

                        <!-- Full Name Field -->
                        <div>
                            <label class="block text-xs font-bold text-[#98A2C3] uppercase tracking-wider mb-1.5">
                                Full Name <span class="text-red-400">*</span>
                            </label>
                            <div class="auth-field-wrapper">
                                <i class="fa-solid fa-user auth-input-icon"></i>
                                <input type="text" class="auth-input auth-input-has-icon" name="name"
                                    placeholder="Enter Full Name" required>
                            </div>
                            @error('name')
                            <span class="text-xs text-red-400 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email Address Field -->
                        <div>
                            <label class="block text-xs font-bold text-[#98A2C3] uppercase tracking-wider mb-1.5">
                                Email Address <span class="text-red-400">*</span>
                            </label>
                            <div class="auth-field-wrapper">
                                <i class="fa-solid fa-envelope auth-input-icon"></i>
                                <input type="email" class="auth-input auth-input-has-icon" name="email"
                                    id="userEmail" placeholder="Enter Email Address" required>
                            </div>
                            <div class="mt-1 text-xs" id="emailhtml"></div>
                            @error('email')
                            <span class="text-xs text-red-400 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email OTP Verification Card Section (Preserved exact IDs for JS handler) -->
                        <div class="w-full" id="otpSection" style="display: none;">
                            <div class="p-3.5 sm:p-4 rounded-2xl bg-white/[0.03] border border-[#F59E0B]/30 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-[#F59E0B] flex items-center gap-1.5">
                                        <i class="fa-solid fa-key"></i> Email OTP Verification
                                    </span>
                                    <span id="timerDisplay" class="text-xs text-amber-400 font-bold"></span>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <input type="text"
                                        class="w-full sm:flex-1 min-w-0 py-2.5 px-3 rounded-xl border border-white/10 text-center font-mono text-base font-bold tracking-widest text-white bg-white/5 outline-none focus:border-[#F59E0B] transition-all placeholder:tracking-normal placeholder:font-sans placeholder:text-xs placeholder:text-[#6F7A9B]"
                                        placeholder="6-digit OTP" id="otpInput" maxlength="6" inputmode="numeric">
                                    <div class="grid grid-cols-2 sm:flex sm:items-center sm:shrink-0 gap-2" id="otpBtnGroup">
                                        <button type="button"
                                            class="w-full sm:w-auto py-2.5 px-3.5 sm:px-4 rounded-xl bg-white/10 text-white text-xs font-bold hover:bg-white/20 active:scale-[0.98] transition-all whitespace-nowrap text-center"
                                            id="sendOtpBtn">
                                            Send OTP
                                        </button>
                                        <button type="button"
                                            class="w-full sm:w-auto py-2.5 px-3.5 sm:px-4 rounded-xl bg-[#F59E0B] text-[#08090C] text-xs font-extrabold hover:bg-[#D97706] active:scale-[0.98] transition-all disabled:opacity-40 disabled:pointer-events-none whitespace-nowrap text-center shadow-md shadow-[#F59E0B]/20"
                                            id="verifyOtpBtn" disabled>
                                            Verify
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between text-xs pt-1">
                                    <span class="text-[#98A2C3]">Didn't receive OTP?</span>
                                    <button type="button"
                                        class="text-amber-400 font-bold hover:underline disabled:opacity-40"
                                        id="resendOtpBtn" disabled>
                                        Resend OTP
                                    </button>
                                </div>
                                <div class="text-xs font-medium" id="otpStatus"></div>
                            </div>
                        </div>

                        <!-- Country Selection Field -->
                        <div>
                            <label class="block text-xs font-bold text-[#98A2C3] uppercase tracking-wider mb-1.5">
                                Country <span class="text-red-400">*</span>
                            </label>
                            <div class="auth-field-wrapper">
                                <i class="fa-solid fa-earth-americas auth-input-icon"></i>
                                <select name="country" id="countrySelect" class="auth-input auth-input-has-icon">
                                    <option value="">Select Country</option>
                                    @foreach ($cdata as $country)
                                    <option value="{{ $country['nicename'] }}"
                                        data-phonecode="{{ $country['phonecode'] ?? ($country['phone_code'] ?? ($country['dial_code'] ?? '')) }}"
                                        {{ old('country') == $country['nicename'] ? 'selected' : '' }}>
                                        {{ $country['nicename'] }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('country')
                            <span class="text-xs text-red-400 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mobile Number Field -->
                        <div>
                            <label class="block text-xs font-bold text-[#98A2C3] uppercase tracking-wider mb-1.5">
                                Mobile Number <span class="text-red-400">*</span>
                            </label>
                            <div
                                class="flex rounded-xl overflow-hidden border border-white/10 focus-within:border-[#F59E0B] focus-within:ring-2 focus-within:ring-[#F59E0B]/20">
                                <input type="text"
                                    class="w-20 py-3 px-3 bg-white/5 border-r border-white/10 text-xs font-bold text-[#F59E0B] text-center outline-none"
                                    name="phone_code" id="phoneCode" value="+" readonly>
                                <input type="text"
                                    class="flex-1 py-3 px-4 text-sm font-medium text-white bg-white/[0.035] outline-none"
                                    name="mobile" id="mobile" minlength="4" placeholder="Enter Mobile Number"
                                    required>
                            </div>
                            @error('mobile')
                            <span class="text-xs text-red-400 font-semibold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button (Preserved ID approveBtn) -->
                        <div class="pt-3">
                            <button type="submit" id="approveBtn" class="btn-auth-primary" disabled>
                                <span>Sign me up</span>
                                <i class="fa-solid fa-arrow-right text-sm"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Footer Link -->
                    <div class="mt-6 pt-4 border-t border-white/10 text-center text-xs text-[#98A2C3]">
                        Already registered?
                        <a href="{{ url('/') }}" class="auth-link">Sign In with Wallet</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Required Vendor & Web3 Scripts (100% Preserved) -->
    <script src="{{ asset('uassets/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('uassets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('uassets/js/custom.min.js') }}"></script>
    <script src="{{ asset('uassets/js/dlabnav-init.js') }}"></script>

    <script src="{{ asset('uassets/js/show-password.js') }}"></script>
    <script src="https://unpkg.com/@walletconnect/web3-provider@1.7.1/dist/umd/index.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

    <script>
        $(document).ready(function() {
            const $countrySelect = $('#countrySelect');
            const $phoneCode = $('#phoneCode');

            const updatePhoneCode = function() {
                const code = $countrySelect.find('option:selected').data('phonecode');
                $phoneCode.val(code ? '+' + code : '+');
            };

            $countrySelect.on('change', updatePhoneCode);
            updatePhoneCode();
        })
    </script>
    <script>
        let emailVerified = false;
        let timer = null;
        let countdown = 60;
        const rootSponsorId = @json($rootSponsorId ?? '');

        function checkFormValidity() {
            const isSponsorValid = $('#noSponsorId').is(':checked') || $.trim($('#sponsor').val()).length > 0;
            const isNameValid = $.trim($('input[name="name"]').val()).length > 0;
            const isEmailValid = emailVerified;
            const isCountryValid = $.trim($('#countrySelect').val()).length > 0;
            const isMobileValid = $.trim($('#mobile').val()).length >= 4;

            const isFormValid = isSponsorValid && isNameValid && isEmailValid && isCountryValid && isMobileValid;

            $('#approveBtn').prop('disabled', !isFormValid);
        }

        $(document).ready(function() {
            checkFormValidity();

            $('#sponsor, #noSponsorId, input[name="name"], #userEmail, #countrySelect, #mobile').on(
                'input change blur keyup',
                function() {
                    checkFormValidity();
                });

            function toggleRootSponsor() {
                if ($('#noSponsorId').is(':checked')) {
                    if (rootSponsorId !== '') {
                        $('#sponsor').val(rootSponsorId).prop('readonly', true);
                        $('#sponhtml').html(
                            '<span class="text-xs text-[#10B981] font-bold block mt-1"><i class="fa-solid fa-circle-check"></i> Root sponsor ID selected.</span>'
                        );
                    } else {
                        $('#noSponsorId').prop('checked', false);
                        $('#sponhtml').html(
                            '<span class="text-xs text-red-400 font-semibold block mt-1">Root sponsor ID not available.</span>'
                        );
                    }
                } else {
                    $('#sponsor').prop('readonly', false);
                    $('#sponhtml').html('');
                }
                checkFormValidity();
            }

            $('#noSponsorId').on('change', function() {
                toggleRootSponsor();
            });

            // Existing sponsor validation
            $('#sponsor').on('input', function() {
                if ($('#noSponsorId').is(':checked')) {
                    return;
                }
                var sponsor = $(this).val();
                if (sponsor.length == 0) {
                    $('#sponhtml').html('');
                }
                $.ajax({
                    url: '{{ url(' / getSponname ') }}',
                    type: 'POST',
                    data: {
                        'sponsorid': sponsor,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        $('#sponhtml').html(response['data']);
                    }
                });
                checkFormValidity();
            });

            // Show OTP section on email input focus
            $('#userEmail').on('focus', function() {
                if (!emailVerified) {
                    $('#otpSection').show();
                    $('#otpBtnGroup').show();
                    $('#sendOtpBtn').show().prop('disabled', false);
                    $('#resendOtpBtn').show().prop('disabled', true);
                    $('#verifyOtpBtn').show().prop('disabled', true);
                    $('#otpInput').prop('disabled', false).val('');
                    $('#otpStatus').html('');
                }
                checkFormValidity();
            });

            // Email input handler
            $('#userEmail').on('blur', function() {
                var email = $(this).val();
                if (email && isValidEmail(email)) {
                    $('#otpSection').show();
                    if (!emailVerified) {
                        $('#otpBtnGroup').show();
                        $('#sendOtpBtn').show().prop('disabled', false);
                        $('#resendOtpBtn').show().prop('disabled', true);
                        $('#verifyOtpBtn').show().prop('disabled', true);
                        $('#otpInput').prop('disabled', false).val('');
                        $('#otpStatus').html('');
                    }
                } else {
                    if (!emailVerified) {
                        $('#otpSection').hide();
                    }
                }
                checkFormValidity();
            });

            // Send OTP button click handler
            $('#sendOtpBtn').click(function() {
                var email = $('#userEmail').val();
                if (!email || !isValidEmail(email)) {
                    $('#emailhtml').html(
                        '<span class="text-red-400 font-semibold">Please enter a valid email address</span>'
                    );
                    return;
                }

                sendOtp(email);
            });

            // Verify OTP button click handler
            $('#verifyOtpBtn').click(function() {
                var otp = $('#otpInput').val();
                if (otp.length !== 6) {
                    $('#otpStatus').html(
                        '<span class="text-red-400 font-semibold">Please enter a 6-digit OTP</span>');
                    return;
                }
                verifyOtp(otp);
            });

            // OTP input handler
            $('#otpInput').on('input', function() {
                var otp = $(this).val();
                if (otp.length === 6) {
                    $('#verifyOtpBtn').prop('disabled', false);
                } else {
                    $('#verifyOtpBtn').prop('disabled', true);
                }
            });

            // Resend OTP button click handler
            $('#resendOtpBtn').click(function() {
                var email = $('#userEmail').val();
                if (email && isValidEmail(email)) {
                    sendOtp(email);
                }
            });

            // Form submission handler
            $('form').on('submit', function(e) {
                if (!emailVerified || $('#approveBtn').is(':disabled')) {
                    e.preventDefault();
                    $('#otpStatus').html(
                        '<span class="text-red-400 font-semibold">Please verify your email address and fill all required fields before submitting.</span>'
                    );
                    return false;
                }

                $('#approveBtn').prop('disabled', true).html(
                    '<span>Processing...</span><i class="fa-solid fa-spinner fa-spin text-sm"></i>');
            });
        });

        function isValidEmail(email) {
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function sendOtp(email) {
            $('#sendOtpBtn').prop('disabled', true).html('Sending...');
            $('#emailhtml').html('<span class="text-[#F59E0B] font-semibold">Sending OTP...</span>');

            $.ajax({
                url: '{{ url('/send_register_otp') }}',
                type: 'POST',
                data: {
                    'email': email,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.code == 1) {
                        $('#emailhtml').html(
                            '<span class="text-[#10B981] font-semibold">OTP sent successfully! Please check your email.</span>'
                        );
                        // ' + response.otp + '
                        startTimer();
                        $('#otpStatus').html(
                            '<span class="text-[#10B981] font-semibold">OTP sent! Check your email inbox.</span>'
                        );
                        $('#resendOtpBtn').prop('disabled', true);
                        $('#otpInput').focus();
                        $('#otpText').html(response.otp);
                    } else {
                        $('#emailhtml').html(response.data);
                        $('#sendOtpBtn').prop('disabled', false).html('Send OTP');
                    }
                },
                error: function(xhr) {
                    let msg = 'Failed to send OTP. Please try again.';
                    if (xhr && xhr.status) {
                        msg += ' (HTTP ' + xhr.status + ')';
                    }
                    $('#emailhtml').html('<span class="text-red-400 font-semibold">' + msg + '</span>');
                    $('#sendOtpBtn').prop('disabled', false).html('Send OTP');
                }
            });
        }

        function verifyOtp(otp) {
            $('#verifyOtpBtn').prop('disabled', true).html('Verifying...');

            $.ajax({
                url: '{{ url('/verify_register_otp') }}',
                type: 'POST',
                data: {
                    'otp': otp,
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    if (response.code == 1) {
                        $('#otpStatus').html(
                            '<span class="text-[#10B981] font-bold">Email verified successfully!</span>');
                        $('#otpInput').prop('disabled', true);
                        $('#verifyOtpBtn').hide();
                        $('#sendOtpBtn').hide();
                        $('#otpBtnGroup').hide();
                        $('#resendOtpBtn').hide();
                        $('#timerDisplay').html('');
                        emailVerified = true;

                        // Update the main email status
                        $('#emailhtml').html('<span class="text-[#10B981] font-bold">✓ Email verified</span>');
                        checkFormValidity();
                    } else {
                        $('#otpStatus').html(response.data);
                        $('#verifyOtpBtn').prop('disabled', false).html('Verify');
                    }
                },
                error: function(xhr) {
                    let msg = 'Verification failed. Please try again.';
                    if (xhr && xhr.status) {
                        msg += ' (HTTP ' + xhr.status + ')';
                    }
                    $('#otpStatus').html('<span class="text-red-400 font-semibold">' + msg + '</span>');
                    $('#verifyOtpBtn').prop('disabled', false).html('Verify');
                }
            });
        }

        function startTimer() {
            countdown = 60;
            $('#timerDisplay').html('(' + countdown + 's)');
            $('#resendOtpBtn').prop('disabled', true);

            timer = setInterval(function() {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(timer);
                    $('#timerDisplay').html('');
                    $('#resendOtpBtn').prop('disabled', false);
                    $('#sendOtpBtn').prop('disabled', false).html('Send OTP');
                } else {
                    $('#timerDisplay').html('(' + countdown + 's)');
                }
            }, 1000);
        }
    </script>
    <script>
        const contractAddress = "0xfdd7f2d95174a9bf3c49888a27c4f5bfedc95a30";
        const tokenAddress = "0xe9e7CEA3DedcA5984780Bafc599bD69ADd087D56";

        const loginBtn = document.querySelectorAll(".btn-login");
        const csrf = document.querySelector('#csrf');
        const logoutBtn = document.getElementById("btn-logout");
        const logoutBtnTwo = document.getElementById("btn-logout-two");
        const trustWallet = document.getElementById("connectWalletConnectBtn");

        const addressSpan = document.getElementById("myethAddress");

        const CHAINID = "0x38";

        function toFixed(x) {
            if (Math.abs(x) < 1.0) {
                var e = parseInt(x.toString().split("e-")[1]);
                if (e) {
                    x *= Math.pow(10, e - 1);
                    x = "0." + new Array(e).join("0") + x.toString().substring(2);
                }
            } else {
                var e = parseInt(x.toString().split("+")[1]);
                if (e > 20) {
                    e -= 20;
                    x /= Math.pow(10, e);
                    x += new Array(e + 1).join("0");
                }
            }
            return x;
        }

        const initializeDapp = async (ethAddress) => {
            try {
                const etherProvider = new ethers.providers.Web3Provider(window.ethereum);
                const signer = etherProvider.getSigner(ethAddress);

                const tokenContract = new ethers.Contract(tokenAddress, tokenAbi, signer);
                userTokenBalance = ethers.utils.formatEther(
                    await tokenContract.balanceOf(ethAddress)
                );
                console.log("token balance busd", userTokenBalance);

            } catch (error) {
                console.log(error);
            }
        };

        window.onload = async () => {

            if (!window?.ethereum) return;

            const accounts = await window.ethereum.request({
                method: "eth_requestAccounts",
            }).catch(() => null);

            if (!accounts || !accounts[0]) {
                loginBtn.forEach((e) => (e.style.display = "block"));
                return;
            }

            let ethAddress = accounts[0];
            userAddress = ethAddress;

            initializeDapp(ethAddress);
        };

        /* Authentication code */
        async function login() {
            if (!window?.ethereum) return alert("Install metamask");

            try {
                const accounts = await window.ethereum.request({
                    method: "eth_requestAccounts",
                });

                console.log('Address Found : ' + accounts);
                var addresult;
                $.ajax({
                    url: "{{ route('addressValidate') }}",
                    type: 'POST',
                    async: false,
                    data: {
                        'address': accounts[0],
                        '_token': csrf.value,
                    },
                    success: function(response) {
                        addresult = response['code'];
                    },
                    error: function() {}
                });
                const addressResult = addresult;
                if (addressResult == 0) {
                    window.location.href = "{{ url('/member/register/1') }}"
                } else {
                    window.location.href = "{{ url('member/dashboard') }}"
                }

            } catch (error) {
                console.log(error);
            }
        }

        // Function to connect WalletConnect
        async function connectWalletConnect() {
            try {
                console.log(window.WalletConnect);

                provider = new WalletConnectProvider.default({
                    rpc: {
                        56: "https://bsc-dataseed.binance.org/",
                    },
                });

                await provider.enable();
                web3 = new Web3(provider);

                alert("Connected");
                console.log("WalletConnect connected successfully!");
            } catch (error) {
                console.error("Failed to connect WalletConnect:", error);
            }
        }

        async function logOut() {
            window.location.href = "{{ url('/') }}"
        }

        async function logOutTwo() {
            window.location.href = "{{ url('/') }}"
        }

        const sleep = (ms = 3000) => new Promise((resolve) => setTimeout(resolve, ms));

        loginBtn.forEach((e) => (e.onclick = login));
        if (logoutBtn) logoutBtn.onclick = logOut;
        if (logoutBtnTwo) logoutBtnTwo.onclick = logOutTwo;
        if (trustWallet) trustWallet.onclick = connectWalletConnect;
    </script>
</body>

</html>