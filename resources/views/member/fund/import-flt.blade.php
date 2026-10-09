@extends('member.layouts.main2')
@section('title', 'Deposit Fund')
@section('container')
@include('member.income._income-styles')

<style>
    /* =============================================
       DEPOSIT FUND PAGE — Bull Trading Theme Match
       Colors: #08090C obsidian · #F59E0B gold · #EF4444 red · #10B981 green
    ============================================= */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .dep-page { font-family: 'Inter', sans-serif; }

    /* ---- Hero Banner ---- */
    .dep-hero {
        position: relative; overflow: hidden;
        border-radius: 18px; padding: 28px 36px; color: #fff; margin-bottom: 28px;
        background: linear-gradient(135deg, #0C0F17 0%, #1A1408 40%, #362203 80%, #683F06 120%);
        border: 1px solid rgba(245, 158, 11, 0.35);
        box-shadow: 0 16px 50px rgba(0,0,0,0.65), 0 0 25px rgba(245, 158, 11, 0.15);
    }
    .dep-hero::before {
        content: ''; position: absolute;
        width: 300px; height: 300px; right: -60px; top: -110px; border-radius: 50%;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.25) 0%, transparent 70%);
        animation: depGlow 4s ease-in-out infinite;
    }
    .dep-hero::after {
        content: ''; position: absolute;
        width: 200px; height: 200px; right: -25px; top: -70px;
        border: 28px solid rgba(245, 158, 11, 0.08); border-radius: 50%;
    }
    @keyframes depGlow {
        0%, 100% { opacity: 0.5; transform: scale(1); }
        50%       { opacity: 1;   transform: scale(1.1); }
    }
    .dep-eyebrow {
        position: relative; z-index: 1;
        display: inline-flex; align-items: center; gap: 7px;
        color: #F59E0B; font-size: 11px; font-weight: 700;
        letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;
    }
    .dep-eyebrow::before {
        content: ''; display: inline-block;
        width: 16px; height: 2px; background: #F59E0B; border-radius: 2px;
    }
    .dep-hero h1 {
        position: relative; z-index: 1;
        font-size: 26px; font-weight: 800; margin: 0 0 6px 0;
        text-shadow: 0 2px 12px rgba(0,0,0,0.5);
        background: linear-gradient(135deg, #FFFFFF 30%, #FDE68A 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .dep-hero p {
        position: relative; z-index: 1;
        color: rgba(255,255,255,0.75); margin: 0; font-size: 13.5px;
    }

    /* ---- Form Card ---- */
    .dep-card {
        background: #0C0E14;
        border: 1px solid rgba(245, 158, 11, 0.15);
        border-radius: 18px;
        box-shadow: 0 20px 55px rgba(0,0,0,0.55), 0 0 15px rgba(245, 158, 11, 0.05);
        overflow: hidden; margin-bottom: 28px;
    }
    .dep-card-header {
        border-bottom: 1px solid rgba(245, 158, 11, 0.15);
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(239, 68, 68, 0.04));
        padding: 18px 28px;
        display: flex; align-items: center; gap: 13px;
    }
    .dep-header-icon {
        width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
        background: linear-gradient(135deg, #F59E0B, #D97706);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.35);
    }
    .dep-header-icon i { color: #08090C; font-size: 15px; }
    .dep-header-title { color: #F8FAFC; font-size: 16px; font-weight: 700; margin: 0; }
    .dep-header-sub { color: #94A3B8; font-size: 12px; margin-top: 2px; }
    .dep-card-body { padding: 28px; }

    /* Labels */
    .dep-label {
        color: #F59E0B; font-size: 11px; font-weight: 700;
        letter-spacing: 0.6px; text-transform: uppercase;
        margin-bottom: 8px; display: block;
    }
    /* Inputs */
    .dep-input {
        background: #08090C !important;
        border: 1px solid rgba(245, 158, 11, 0.20) !important;
        border-radius: 10px !important; color: #F8FAFC !important;
        min-height: 50px; font-size: 14.5px; font-weight: 500;
        padding: 0 16px !important;
        transition: border-color .25s ease, box-shadow .25s ease !important;
    }
    .dep-input::placeholder { color: #64748B !important; }
    .dep-input:focus {
        border-color: #F59E0B !important;
        box-shadow: 0 0 0 3.5px rgba(245, 158, 11, 0.20) !important;
        background: rgba(245, 158, 11, 0.04) !important;
        outline: none !important;
    }
    .dep-input[readonly] { color: #94A3B8 !important; cursor: default; }

    /* Package select dropdown */
    select.dep-input {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23F59E0B' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 16px center !important;
        padding-right: 42px !important;
        cursor: pointer;
    }
    select.dep-input option {
        background-color: #08090C !important;
        color: #F8FAFC !important;
    }

    /* Amount input group */
    .dep-prefix {
        background: #08090C !important;
        border: 1px solid rgba(245, 158, 11, 0.20) !important;
        border-right: none !important;
        border-radius: 10px 0 0 10px !important;
        color: #F59E0B !important; font-size: 16px; font-weight: 700;
        min-height: 50px; padding: 0 14px !important;
        display: flex; align-items: center; justify-content: center;
    }
    .dep-input.amount-field {
        border-radius: 0 10px 10px 0 !important;
        border-left: none !important;
    }

    /* Submit Button */
    .dep-btn {
        min-height: 50px; border: none; border-radius: 12px; width: 100%;
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: #08090C; font-size: 14.5px; font-weight: 800;
        box-shadow: 0 8px 28px rgba(245, 158, 11, 0.35);
        transition: all .3s ease;
    }
    .dep-btn:hover {
        background: linear-gradient(135deg, #FBBF24 0%, #B45309 100%);
        box-shadow: 0 12px 36px rgba(245, 158, 11, 0.50);
        transform: translateY(-1px); color: #08090C;
    }
    .dep-btn:active { transform: translateY(0); }

    /* Footer secure row */
    .dep-footer {
        border-top: 1px solid rgba(245, 158, 11, 0.15);
        padding-top: 20px; margin-top: 8px;
    }
    .dep-secure { color: #94A3B8; font-size: 12.5px; display: flex; align-items: center; gap: 6px; margin-bottom: 14px; }
    .dep-secure i { color: #10B981; }

    /* Alerts */
    .dep-page .alert-success {
        background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.35);
        color: #6EE7B7; border-radius: 12px;
    }
    .dep-page .alert-danger {
        background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.35);
        color: #FCA5A5; border-radius: 12px;
    }
    .dep-page .text-danger { color: #EF4444 !important; font-size: 12px; }

    /* ---- History Table ---- */
    .inc-table-shell,
    .table-responsive {
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
    .dep-table,
    .inc-table {
        width: 100% !important;
        min-width: 680px !important;
        border-collapse: collapse !important;
    }
    .dep-table thead tr th,
    .inc-table thead tr th {
        background: rgba(245, 158, 11, 0.08) !important;
        color: #F59E0B !important;
        font-size: 11px !important; font-weight: 700 !important;
        letter-spacing: 0.7px; text-transform: uppercase;
        padding: 14px 16px !important;
        border-bottom: 1px solid rgba(245, 158, 11, 0.18) !important;
        white-space: nowrap !important;
    }
    .dep-table tbody tr td,
    .inc-table tbody tr td {
        color: #E2E8F0 !important; font-size: 13.5px !important;
        padding: 15px 16px !important;
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }
    .dep-table tbody tr:last-child td { border-bottom: none !important; }
    .dep-table tbody tr:hover td { background: rgba(245, 158, 11, 0.06) !important; }
    .dep-table tbody td.dataTables_empty { color: #64748B !important; text-align: center; padding: 40px !important; }

    /* Status badges */
    .dep-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 13px; border-radius: 50px;
        font-size: 11.5px; font-weight: 700;
        background: rgba(16, 185, 129, 0.15);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.35);
    }

    /* DataTable controls */
    .dep-card .dataTables_wrapper .dataTables_length label,
    .dep-card .dataTables_wrapper .dataTables_filter label,
    .dep-card .dataTables_wrapper .dataTables_info { color: #94A3B8 !important; font-size: 13px; }
    .dep-card .dataTables_wrapper .dataTables_length select,
    .dep-card .dataTables_wrapper .dataTables_filter input {
        background: #08090C !important; border: 1px solid rgba(245, 158, 11, 0.20) !important;
        border-radius: 8px !important; color: #F8FAFC !important;
        padding: 5px 10px !important; font-size: 13px !important;
    }
    .dep-card .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #F59E0B !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.20) !important; outline: none !important;
    }
    .dep-card .dataTables_wrapper .dataTables_paginate .paginate_button {
        background: #08090C !important; border: 1px solid rgba(245, 158, 11, 0.20) !important;
        color: #94A3B8 !important; border-radius: 8px !important;
        margin: 0 3px !important; padding: 5px 12px !important; font-size: 13px !important;
    }
    .dep-card .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dep-card .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: linear-gradient(135deg, #F59E0B, #D97706) !important;
        border-color: transparent !important; color: #08090C !important;
        font-weight: 700 !important;
    }
</style>

<!--********************************** Content body start ***********************************-->
<div class="content-body dep-page">
    <div class="container-fluid py-4">

        {{-- Hero --}}
        <div class="dep-hero">
            <div class="dep-eyebrow">Deposit Section</div>
            <h1>Deposit Fund</h1>
            <p>Add USDT (BEP-20) funds directly to your fund wallet via MetaMask.</p>
        </div>

        <div class="row justify-content-center">
            {{-- ===== FORM CARD ===== --}}
            <div class="col-xl-7 col-lg-8 col-md-10 col-12 mb-4 mx-auto">

                @if (session()->has('successMsg'))
                    <div class="alert alert-success mb-3" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('successMsg') }}
                    </div>
                @endif
                @if (session()->has('FailedMsg'))
                    <div class="alert alert-danger mb-3" role="alert">
                        <i class="fa-solid fa-circle-xmark me-2"></i>{{ session('FailedMsg') }}
                    </div>
                @endif

                <div class="dep-card">
                    <div class="dep-card-header">
                        <div class="dep-header-icon">
                            <i class="fa-solid fa-arrow-down-to-line"></i>
                        </div>
                        <div>
                            <div class="dep-header-title">Deposit Fund</div>
                            <div class="dep-header-sub">Connect wallet & deposit USDT BEP-20</div>
                        </div>
                    </div>

                    <div class="dep-card-body">
                        <input type="hidden" id="csrf" value="{{ csrf_token() }}">
                        @csrf

                        <div class="mb-4">
                            <label class="dep-label" for="memberid">
                                <i class="fa-solid fa-id-badge me-1"></i> Member ID
                            </label>
                            <input type="text" class="form-control dep-input" name="memberid" id="memberid"
                                value="{{ $data['memberid'] }}" readonly>
                        </div>

                        <div class="mb-4">
                            <label class="dep-label" for="amount">
                                <i class="fa-solid fa-dollar-sign me-1"></i> Amount (USDT BEP-20)
                            </label>
                            <div class="input-group">
                                <span class="dep-prefix">$</span>
                                <input type="text" class="form-control dep-input amount-field"
                                    name="amount" value="" id="amount"
                                    onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 46'
                                    placeholder="Enter deposit amount (min 1 USDT)">
                            </div>
                            @error('amount')
                                <span class="text-danger d-block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="dep-label">
                                <i class="fa-solid fa-wallet me-1"></i> Fund Wallet Balance (USDT BEP-20)
                            </label>
                            <input type="text" class="form-control dep-input" name="wallet"
                                value="{{ $data['p2p_wallet'] }}" readonly>
                            @error('p2p_wallet')
                                <span class="text-danger d-block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="dep-footer">
                            <div class="dep-secure">
                                <i class="fa-solid fa-shield-halved"></i> Secure blockchain transaction via MetaMask
                            </div>
                            <input type="hidden" id="csrf" value="{{ csrf_token() }}">
                            <input type="hidden" id="route" value="{{ route('addFund') }}">
                            <button class="btn dep-btn transferBtn">
                                <i class="fa-solid fa-bolt me-2"></i>Deposit Fund
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== HISTORY TABLE ===== --}}
            <div class="col-12">
                <div class="inc-card">
                    <div class="inc-card-header">
                        <div class="inc-header-icon">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <div class="inc-header-title">Deposit Fund History</div>
                            <div class="inc-header-sub">All your deposit transactions</div>
                        </div>
                    </div>
                    <div class="inc-table-shell">
                        <table id="example3" class="inc-table display dataTable no-footer">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Date</th>
                                    <th>Order Id</th>
                                    <th>Transaction Id</th>
                                    <th>Amount</th>
                                    <th>Mode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($addfundData as $list)
                                    @php $mdata = getAllData($list['memberid']); @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                                        <td>{{ $list['orderid'] }}</td>
                                        <td>{{ $list['txnid'] }}</td>
                                        <td>$ {{ $list['amount'] }}</td>
                                        <td>{{ $list['mode'] }}</td>
                                        <td>
                                            @if ($list['status'] == 'Unpaid' || $list['status'] == 'Pending')
                                                <span class="inc-badge pending"><i class="fa-solid fa-hourglass-half me-1"></i>{{ $list['status'] }}</span>
                                            @elseif ($list['status'] == 'Approved' || $list['status'] == 'Paid' || $list['status'] == 'Success')
                                                <span class="inc-badge paid"><i class="fa-solid fa-circle-check me-1"></i>{{ $list['status'] }}</span>
                                            @else
                                                <span class="inc-badge paid"><i class="fa-solid fa-circle-check me-1"></i>{{ $list['status'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!--********************************** Content body end ***********************************-->


    <!--********************************** Footer start ***********************************-->

    <div class="footer style-1">
        <div class="copyright">
            <p>Copyright ©
                <script>
                    document.write(new Date().getFullYear())
                </script>
                {{ config('detailsApp.name') }}, All Right Reserved
            </p>
        </div>
    </div>
    </div>

    <!--********************************** Main wrapper end ***********************************-->

    <!--********************************** Scripts ***********************************-->

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    <!-- Dashboard 1 -->
    <script src="{{ asset('uassets/js/dashboard/dashboard-1.js') }}"></script>
    <script src="{{ asset('uassets/js/custom.min.js') }}"></script>
    <script src="{{ asset('uassets/js/dlabnav-init.js') }}"></script>
    <script src="{{ asset('uassets/js/demo.js') }}"></script>
    <script src="{{ asset('uassets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.0/dist/web3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/3.0.0-rc.5/web3.min.js"></script>
    <script src="https://unpkg.com/@walletconnect/web3-provider@1.7.1/dist/umd/index.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.2/ethers.umd.js"></script>
    <script src="{{ asset('uassets/js/block12.js') }}?v={{ time() }}"></script>

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
        $(document).ready(function() {
            $('#act_btn').on('click', function() {
                $(this).html('Processing .......')
            })
        })
    </script>

    </body>

    </html>

@endsection
