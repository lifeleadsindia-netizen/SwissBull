@extends('member.layouts.main')
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
                            <form action="{{ route('initWithdrawal') }}" method="post" enctype="multipart/form-data">
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

                                {{-- <div class="alert alert-warning mt-3 mb-0" role="alert">
                                    <strong>Note:</strong>
                                    Income Wallet withdrawal has 15% service charge deduction.
                                </div> --}}
                                <div class="mb-3">
                                   
                                    <button type="submit"
                                        class="btn btn-primary btn-rounded  btn-block mt-3 mb-4 float-end withdraw_btn subBtn">Withdraw Now</button>
                                    <div class="text-center">
                                        <span id="wMesg" class="text-danger text-center"></span>
                                    </div>
                                </div>
                            </div>
                            </form>
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

  
@endsection
