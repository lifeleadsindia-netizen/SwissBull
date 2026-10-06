@extends('member.layouts.main2')
@section('title', 'P2P Wallet')
@section('container')

    <!--**********************************
                Content body start
            ***********************************-->
    <div class="content-body">
        <div class="container-fluid">
            <!-- row -->
            <div class="row pb-3">
                {{-- <div class="col-12"> --}}
                <div class="col-xl-5 col-lg-5 col-sm-5">
                    <div class="col-xl-12">
                        <div class="card overflow-hidden">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="profile-photo">
                                        <img src="{{ asset('uassets/images/wallet-2.png') }}" width="100"
                                            class="img-fluid" alt="">
                                    </div>
                                    <h3 class="mt-4 mb-1">Fund Wallet </h3>
                                    <a class="btn btn-outline-primary btn-rounded mt-3 px-5"
                                        href="javascript:void(0);;">Balance : ${{ $data['p2p_wallet'] }}</a>
                                </div>
                            </div>

                            <div class="card-footer pt-0 pb-0 text-center">
                                <div class="row">
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1">150</h3><span>Total Income</span>
                                    </div>
                                    <div class="col-4 pt-3 pb-3 border-end">
                                        <h3 class="mb-1">140</h3><span>Total Withdrawal</span>
                                    </div>
                                    <div class="col-4 pt-3 pb-3">
                                        <h3 class="mb-1">45</h3><span>Today Withdrawal</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-7 mb-3">
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
                    <div class="card ">
                        <div class="card-header">
                            <h4 class="card-title">P2P Transfer Form</h4>
                        </div>
                        <div class="card-body py-4">
                            <div class="basic-form">
                                <form action="{{ route('fundTrans') }}" method="post">
                                    <input type="hidden" id="csrf" value="{{ csrf_token() }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Amount</label>
                                        <input type="text" id="amount" class="form-control" name="amount"
                                            placeholder="Enter Amount" required>
                                        @error('amount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small id="amountText" class="text-danger"></small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">MemberId</label>
                                        <input type="text" class="form-control" placeholder="Enter Memberid"
                                            name="member" id="fundmemberid" maxlength="9" minlength="9">
                                        @error('member')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small id="memMsg" class="text-danger"></small>
                                    </div>

                                    <div class="mb-3">
                                        <input type="hidden" name="balance" id="balance"
                                            value="{{ $data['p2p_wallet'] }}">
                                        <input type="hidden" name="sender" value="{{ $data['memberid'] }}">
                                        <button class="btn btn-primary btn-rounded btn-block my-3 float-end" id="transBtn"
                                            onclick="return confirm('Are you sure to transfer amount')">Transfer
                                            Amount</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- </div> --}}
            </div>
            <div class="row pt-3">
                <div class="col-12 pt-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">P2P Transfer History</h4>
                            </div>
                            <div class="card-body" style="overflow:auto">
                                <table id="example3" class="display min-w850 dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Date</th>
                                            <th>Particular</th>
                                            <th>Add</th>
                                            <th>Deduct</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1;  @endphp
                                        @foreach ($passdata as $list)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ $list['created_at'] }}</td>
                                                <td>{{ $list['particular'] }}</td>
                                                <td>{{ $list['debit'] }}</td>
                                                <td>{{ $list['credit'] }}</td>
                                                <td> <label class="badge bg-warning">{{ $list['balance'] }}</label></td>
                                            </tr>
                                            @php
                                                $i++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--**********************************
                Content body end
            ***********************************-->


    <!--**********************************
                Footer start
            ***********************************-->
    <div class="footer style-1">
        <div class="copyright">
            <p>Copyright © {{ config('detailsApp.name') }} <span class="current-year">2024</span></p>
        </div>
    </div>
    <!--**********************************
                Footer end
            ***********************************-->

    <!--**********************************
               Support ticket button start
            ***********************************-->

    <!--**********************************
               Support ticket button end
            ***********************************-->


    </div>
    <!--**********************************
            Main wrapper end
        ***********************************-->

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

    <!-- Dashboard 1 -->
    <script src="{{ asset('uassets/js/custom.min.js') }}"></script>
    <script src="{{ asset('uassets/js/dlabnav-init.js') }}"></script>
    <script src="{{ asset('uassets/js/demo.js') }}"></script>
    {{-- <script src="{{asset('uassets/js/styleSwitcher.js')}}"></script> --}}

    <!-- code-highlight -->
    <script src="{{ asset('uassets/js/highlight.min.js') }}"></script>
    <script>
        hljs.highlightAll();
    </script>
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
            $('#fundmemberid').on('input', function() {
                var memberid = $(this).val();
                var csrf = $('#csrf').val();
                if (memberid.length == 0) {
                    $('#memMsg').html('');
                    $('#transBtn').attr('disabled', false);
                }
                $.ajax({
                    url: '/getMember',
                    type: 'POST',
                    data: {
                        'memberid': memberid,
                        _token: csrf,
                    },
                    success: function(response) {
                        $('#memMsg').html(response['data']);
                        if (response['code'] == 0) {
                            $('#transBtn').attr('disabled', true);
                        } else {
                            $('#transBtn').attr('disabled', false);
                        }
                    }
                });
            });
        });
    </script>
    </body>

    </html>
@endsection
