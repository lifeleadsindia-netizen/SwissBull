@extends('member.layouts.main')
@section('title', 'Transaction Passbook')
@section('container')
@include('member.income._income-styles')

    <!--**********************************
                        Content body start
                    ***********************************-->
    <div class="content-body inc-page">
        <div class="container-fluid py-4">
            <!-- row -->
            <div class="row">
                <div class="col-12">
                    <div class="col-12">
                        <div class="inc-card">
                            <div class="inc-card-header">
                                <div class="inc-header-icon"><i class="fa-solid fa-wallet"></i></div>
                                <div>
                                    <div class="inc-header-title">Transaction Passbook</div>
                                    <div class="inc-header-sub">Wallet movement history</div>
                                </div>
                            </div>
                            <div class="inc-table-shell">
                                <table id="example3" class="inc-table display dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Amount Add</th>
                                            <th>Amount Deduct</th>
                                            <th>Balance</th>
                                            <th>Particular</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1;  @endphp
                                        @foreach ($transdata as $list)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}<br>
                                                    {{ date('H:i:s', strtotime($list['created_at'])) }}</td>
                                                <td>{{ $list['walletType'] }}</td>
                                                <td>
                                                        ${{ $list['debit'] }}
                                                </td>
                                                <td>
                                                        ${{ $list['credit'] }}
                                                </td>
                                                <td>
                                                        ${{ $list['balance'] }}
                                                </td>
                                                <td>{{ $list['particular'] }}</td>
                                            </tr>
                                            @php $i++;  @endphp
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
@endsection
