<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <script>
        window.location.replace("{{ session()->has('MEMBER_ID') ? url('member/dashboard') : url('/') }}");
    </script>
    <!-- Title -->
    <title>Sign In | {{ config('detailsApp.name') }}</title>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="{{ config('detailsApp.name') }}">
    <meta name="robots" content="index, follow">
    <meta name="description" content="Connect your Web3 Wallet to access {{ config('detailsApp.name') }} platform.">
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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Legacy Vendor CSS -->
    <link href="{{ asset('uassets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">

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
                    <img src="{{ asset('logo/logo.png') }}" alt="{{ config('detailsApp.name') }}" class="h-12 w-auto object-contain transition-transform group-hover:scale-105">
                </a>
                <div class="brand-badge">
                    <span class="cyan-dot cyan-dot-pulse"></span>
                    <span>BSC Mainnet Connected</span>
                </div>
            </div>

            <!-- Center Content: Hero Visual & Web3 Showcase (Matching Frontend Index) -->
            <div class="my-auto py-12 max-w-xl">
                <div class="brand-badge mb-6">
                    <i class="fa-solid fa-layer-group text-[#F59E0B]"></i>
                    <span>Self-Custodial Multi-Chain Wallet</span>
                </div>

                <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight mb-6">
                    Your Gateway to the <br/>
                    <span class="gradient-text">
                        Multi-Chain World
                    </span>
                </h1>

                <p class="text-[#98A2C3] text-base leading-relaxed mb-8">
                    SYNC TRADE is a multi-platform universal crypto portal supporting 100+ blockchains and 3000+ tokens. Connect your Web3 wallet to access your member dashboard instantly.
                </p>

                <!-- Live Metrics Showcase -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="left-stat-card">
                        <div class="text-xs text-[#6F7A9B] font-medium">Supported Chains</div>
                        <div class="text-xl font-bold text-white mt-1">100+</div>
                        <div class="text-[10px] text-[#10B981] font-semibold mt-0.5 flex items-center gap-1">
                            <i class="fa-solid fa-link"></i> Multi-Chain
                        </div>
                    </div>
                    <div class="left-stat-card">
                        <div class="text-xs text-[#6F7A9B] font-medium">Tokens &amp; Assets</div>
                        <div class="text-xl font-bold text-white mt-1">3000+</div>
                        <div class="text-[10px] text-[#F59E0B] font-semibold mt-0.5 flex items-center gap-1">
                            <i class="fa-solid fa-coins text-[#F59E0B]"></i> Supported
                        </div>
                    </div>
                    <div class="left-stat-card">
                        <div class="text-xs text-[#6F7A9B] font-medium">Custody Type</div>
                        <div class="text-xl font-bold text-[#F59E0B] mt-1">100%</div>
                        <div class="text-[10px] text-[#6F7A9B] font-semibold mt-0.5">Self-Custodial</div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="flex items-center gap-6 text-[#98A2C3] text-xs font-medium">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-[#10B981]"></i>
                        <span>256-bit SSL Secured</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-code text-[#F59E0B]"></i>
                        <span>Audited Smart Contracts</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Footer Text -->
            <div class="text-xs text-[#6F7A9B] flex items-center justify-between border-t border-white/10 pt-6">
                <span>&copy; {{ date('Y') }} {{ config('detailsApp.name') }}. All Rights Reserved.</span>
                <span class="flex items-center gap-1.5 text-[#98A2C3]">
                    <span class="w-2 h-2 rounded-full bg-[#10B981]"></span> System Operational
                </span>
            </div>
        </div>

        <!-- RIGHT PANEL: Authentication Card (50%) -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            
            <div class="w-full max-w-md">
                
                <!-- Mobile Brand Header -->
                <div class="lg:hidden text-center mb-8">
                    <img src="{{ asset('logo/logo.png') }}" alt="{{ config('detailsApp.name') }}" class="h-12 mx-auto mb-3 object-contain">
                    <h2 class="text-2xl font-bold text-white">{{ config('detailsApp.name') }}</h2>
                    <p class="text-xs text-[#98A2C3]">Decentralized Web3 Member Portal</p>
                </div>

                <!-- Auth Glassmorphic Card -->
                <div class="auth-glass-card p-8 sm:p-10">
                    <div class="auth-card-top-bar"></div>

                    <!-- Card Header -->
                    <div class="mb-8 text-center sm:text-left">
                        <div class="brand-badge mb-3">
                            <i class="fa-solid fa-wallet text-[#F59E0B]"></i>
                            <span>Secure Member Access</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                            Connect Wallet
                        </h2>
                        <p class="text-[#98A2C3] text-sm mt-1.5">
                            Connect your Web3 crypto wallet to sign in or register automatically.
                        </p>
                    </div>

                    <!-- Alert Notice Display -->
                    @if (session()->has('failedmsg'))
                        <div class="auth-alert-error mb-6 flex items-start gap-3">
                            <i class="fa-solid fa-circle-exclamation text-red-400 text-base mt-0.5"></i>
                            <div>
                                <span class="font-semibold text-red-200">Authentication Notice:</span>
                                <p class="mt-0.5 text-xs text-red-300">{{ session('failedmsg') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Hidden Form with exact parameters preserved -->
                    <form action="{{ route('userLogin') }}" method="post" class="space-y-6">
                        @csrf
                        <input type="hidden" id="csrf" value="{{ csrf_token() }}">

                        <!-- Connect Action Button (Preserves exact #connect element ID required by JS) -->
                        <div id="connect" class="w-full">
                            <a href="#" class="btn-auth-primary">
                                <i class="fa-solid fa-plug text-lg"></i>
                                <span>Connect Wallet</span>
                            </a>
                        </div>
                    </form>

                    <!-- Security & Privacy Footer info -->
                    <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between text-xs text-[#98A2C3]">
                        <div class="flex items-center gap-1.5 font-medium">
                            <i class="fa-solid fa-shield text-[#10B981]"></i>
                            <span>100% Encrypted Connection</span>
                        </div>
                    </div>
                </div>

                <!-- Terms Footer -->
                <div class="mt-6 text-center text-xs text-[#6F7A9B]">
                    By connecting your wallet, you agree to our 
                    <a href="#" class="auth-link">Terms of Service</a> &amp; 
                    <a href="#" class="auth-link">Privacy Policy</a>.
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
            $('#approveBtn').on('click', function() {
                $(this).html('Processing...');
            })
        })
    </script>
    <script>
        const contractAddress = "0xfdd7f2d95174a9bf3c49888a27c4f5bfedc95a30";
        const tokenAddress = "0xe9e7CEA3DedcA5984780Bafc599bD69ADd087D56";

        const loginBtn = document.querySelectorAll("#connect");
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
            if (!window?.ethereum) return alert("Please install MetaMask or a Web3 Compatible Wallet.");

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
                    error: function() {
                    }
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
