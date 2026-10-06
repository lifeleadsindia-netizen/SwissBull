@extends('member.layouts.main2')
@section('title', 'Main Wallet')
@section('container')

    <!--**********************************
                                                                Content body start
                                                            ***********************************-->
    <div class="content-body">
        <div class="container-fluid">
            <!-- row -->
            <div class="row pb-3">
                {{-- <div class="col-12"> --}}
                <div class="col-xl-8 col-lg-8 col-sm-12 col-md-12 mx-auto">
                    @if (session()->has('successMsg'))
                        <div class="alert alert-success " role="alert">
                            <strong>Congratulations! </strong> {{ session('successMsg') }}
                        </div>
                    @endif
                    @if (session()->has('failedMsg'))
                        <div class="alert alert-danger " role="alert">
                            <strong>Watchout! </strong> {{ session('failedMsg') }}
                        </div>
                    @endif
                    <div class="col-xl-12">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="profile-photo">
                                        <img src="{{ asset('uassets/images/wallet-2.png') }}" width="100"
                                            class="img-fluid" alt="">
                                    </div>
                                    <h3 class="mt-4 mb-1">Withdrawal Amount </h3>
                                    <div class="d-flex flex-column gap-2 mt-3">
                                        <a class="btn btn-outline-primary btn-rounded px-4" href="javascript:void(0);">
                                            Income Wallet Balance : $ {{ $data['wallet'] }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer pt-0 pb-0 text-center">
                                <div class="row">
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1">${{ number_format((float) totalIncome($data['memberid']), 2) }}
                                        </h3><span>Total
                                            Income</span>
                                    </div>
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1">${{ totalWithdrawals($data['memberid']) }}</h3><span>Total
                                            Withdrawal</span>
                                    </div>
                                    <div class="col-4 pt-3 pb-3">
                                        <h3 class="mb-1">${{ todayWithdrawals($data['memberid']) }}</h3><span>Today
                                            Withdrawal</span>
                                    </div>
                                </div>
                            </div>
                            {{-- <form action="{{ route('initWithdrawal') }}" method="post" enctype="multipart/form-data"> --}}
                            @csrf
                            <div class="card-body">
                                <div class="basic-form mb-3">
                                    <label class="form-label" for="wallet_type">Memberid</label>
                                    <input type="text" class="form-control " name="memberid"
                                        placeholder="Enter Member ID" id="memberid" value="{{ $data['memberid'] }}"
                                        readonly>
                                    @error('memberid')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    {{-- <small class="text-muted d-block mt-1">
                                            <span class="text-warning"> </span> <span class="text-white">.</span>
                                        </small> --}}
                                </div>
                                <div class="basic-form" class="mb-3">
                                    <label class="form-label" for="withAmount">Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-white">$</span>
                                        <input type="text" class="form-control " name="amount"
                                            placeholder="Enter Amount" required id="withAmount">
                                    </div>
                                    @error('amount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="alert alert-warning mt-3 mb-0" role="alert">
                                    <strong>Note:</strong>
                                    Income Wallet withdrawal has 15% service charge deduction.
                                </div>
                                <div class="mb-3">
                                    <input type="hidden" name="route" id="route"
                                        value="{{ route('initiate-withdrawal') }}">
                                    <input type="hidden" name="balValidate" id="balValidate"
                                        value="{{ route('balValidate') }}">
                                    <input type="hidden" name="getPvtcd" id="getPvtcd" value="{{ route('getPvtcd') }}">
                                    <input type="hidden" name="netamount" id="netamount" value="{{ $data['wallet'] }}">
                                    <input type="hidden" class="form-control" value="{{ csrf_token() }}" id="csrf">
                                    <input type="hidden" name="memberid" id="memberid" value="{{ $data['memberid'] }}">
                                    <input type="hidden" name="memberWallet" value="{{ $data['member_wallet'] }}"
                                        id="memberWallet">
                                    <input type="hidden" name="backUrl" value="{{ config('detailsApp.url') }}"
                                        id="backUrl">
                                    <button
                                        class="btn btn-primary btn-rounded  btn-block mt-3 mb-4 float-end withdraw_btn subBtn">Withdraw Now</button>
                                    <div class="text-center">
                                        <span id="wMesg" class="text-danger text-center"></span>
                                    </div>
                                </div>
                            </div>
                            {{-- </form> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!--********************************** Content body end ***********************************-->
    <script>
        (function() {
            const walletType = document.getElementById('wallet_type');
            const amountSymbol = document.getElementById('amountSymbol');
            if (!walletType || !amountSymbol) return;

            const updateAmountSymbol = () => {
                const selected = walletType.options[walletType.selectedIndex];
                const symbol = selected ? selected.getAttribute('data-symbol') : null;
                amountSymbol.textContent = symbol || '{{ $country['symbol'] }}';
            };

            walletType.addEventListener('change', updateAmountSymbol);
            updateAmountSymbol();
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.0/dist/web3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/3.0.0-rc.5/web3.min.js"></script>
    <script src="https://unpkg.com/@walletconnect/web3-provider@1.7.1/dist/umd/index.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.2/ethers.umd.js"></script>
    <script src="{{ asset('uassets/js/main_wallets2.js') }}"></script>

    <!--**********************************
                        Scripts
                    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('uassets/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('uassets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>

    <!-- Datatable -->
    <script src="{{ asset('uassets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('uassets/vendor/datatables/responsive/responsive.js') }}"></script>
    <script src="{{ asset('uassets/js/plugins-init/datatables.init.js') }}"></script>


    <!-- Apex Chart -->
    <script src="{{ asset('uassets/vendor/apexchart/apexchart.js') }}"></script>
    <script src="{{ asset('uassets/vendor/chart-js/chart.bundle.min.js') }}"></script>

    <!-- counter -->
    <script src="{{ asset('uassets/vendor/counter/counter.min.js') }}"></script>
    <script src="{{ asset('uassets/vendor/counter/waypoint.min.js') }}"></script>

    <!-- Chart piety plugin files -->
    {{-- <script src="{{asset('uassets/vendor/peity/jquery.peity.min.js')}}"></script>
            <script src="{{asset('uassets/vendor/swiper/js/swiper-bundle.min.js')}}"></script> --}}
    <script src="{{ asset('uassets/vendor/peity/jquery.peity.min.js') }}"></script>
    <script src="{{ asset('uassets/js/dashboard/trading-market.js') }}"></script>

    <!-- Dashboard 1 -->
    <script src="{{ asset('uassets/js/dashboard/dashboard-1.js') }}"></script>
    <script src="{{ asset('uassets/js/custom.min.js') }}"></script>
    <script src="{{ asset('uassets/js/dlabnav-init.js') }}"></script>
    <script src="{{ asset('uassets/js/demo.js') }}"></script>
    <script src="{{ asset('uassets/js/main.js') }}"></script>
    {{-- <script src="{{asset('uassets/js/styleSwitcher.js')}}"></script> --}}
    <script type="text/javascript">
        function googleTranslateFunction() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateFunction"></script>
    <script>
        jQuery(document).ready(function() {
            setTimeout(function() {
                dlabSettingsOptions.version = 'light';
                new dlabSettings(dlabSettingsOptions);
                setCookie('version', 'light');
            }, 1500)
        });
    </script>
    <script>
        $(function() {
            function isMobileView() {
                return window.innerWidth <= 768;
            }

            //withdrawal code
            $('#withAmount').on('input', function() {

                var netBalance = parseInt($('#netamount').val());
                var inputAmount = parseInt($(this).val());
                //  alert("Echo");
                if (inputAmount > netBalance) {

                    $('#withdrawBtn').attr('disabled', true);
                    $('#wMesg').html('Enter amount can not be greater then Net Balance');
                } else if (inputAmount < 10) {
                    $('#withdrawBtn').attr('disabled', true);
                    $('#wMesg').html('Minimum withdrawal amount is $10');
                } else {
                    $('#withdrawBtn').attr('disabled', false);
                    $('#wMesg').html('');
                }
            });

        })
    </script>
@endsection
