@extends('member.layouts.main')
@section('title', 'Transfer History')
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
                                <div class="inc-header-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                                <div>
                                    <div class="inc-header-title">Transfer History</div>
                                    <div class="inc-header-sub">Your request and transfer records</div>
                                </div>
                            </div>
                            <div class="inc-table-shell">
                                <table id="example3" class="inc-table display dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Date</th>
                                            <th>Request Id</th>
                                            <th>Member Id</th>
                                            <th>Type</th>
                                            <th>Gross</th>
                                            <th>Charges</th>
                                            <th>Net</th>
                                            <th>Status</th>
                                            <th>Payment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @php $i = 1;  @endphp
                                        @foreach ($reqdata as $list)
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ date('d-m-Y', strtotime($list['request_date'])) }}</td>
                                                <td>{{ $list['request_id'] }}</td>
                                                <td>{{ $list['memberid'] }}</td>
                                                <td>{{ $list['type'] }}</td>
                                                <td>
                                                    {{ $list['type'] == 'Airdrop Withdrawal' ? 'PEPE' : '$' }}
                                                    {{ $list['gross_amount'] }}
                                                </td>
                                                <td>
                                                    {{ $list['type'] == 'Airdrop Withdrawal' ? 'PEPE' : '$' }}
                                                    {{ $list['service_charge'] }}
                                                </td>
                                                <td>
                                                    {{ $list['type'] == 'Airdrop Withdrawal' ? 'PEPE' : '$' }}
                                                    {{ $list['net_amount'] }}
                                                </td>
                                                <td>
                                                    @if ($list['status'] == 'Approved')
                                                        <label class="btn btn-success">{{ $list['status'] }}</label>
                                                    @elseif ($list['status'] == 'Cancelled')
                                                        <label class="btn btn-danger">{{ $list['status'] }}</label>
                                                    @else
                                                        <label class="btn btn-info">{{ $list['status'] }}</label>
                                                    @endif
                                                </td>
                                                <td>{{ $list['payment_date'] ?? '--' }}</td>
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
