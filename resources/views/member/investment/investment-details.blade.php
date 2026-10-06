@extends('member.layouts.main')
@section('title', 'Staking History')
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
                    <div class="inc-card">
                        <div class="inc-card-header">
                            <div class="inc-header-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                            <div>
                                <div class="inc-header-title">Staking History</div>
                                <div class="inc-header-sub">Current staking investment summary</div>
                            </div>
                        </div>
                        <div class="inc-table-shell">
                            <table id="example3" class="inc-table display dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Invest Date</th>
                                        <th>Memberid</th>
                                        <th>Invest Amount</th>
                                        <th>Rate</th>
                                        <th>Installments</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1;  @endphp
                                    @foreach ($investment_data as $list)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td> {{ date('d-m-Y', strtotime($list['invest_date'])) }}</td>
                                            <td>{{ $list['memberid'] }}</td>
                                            <td>$ {{ $list['invest_amount'] }}</td>
                                            <td>{{ $list['rate'] }}%</td>
                                            <td>{{ $list['installments'] }}</td>
                                            <td>
                                                @if ($list['status'] == 'Active')
                                                    <span class="badge  badge-success">{{ $list['status'] }}</span>
                                                @else
                                                    <span class="badge  badge-danger ">{{ $list['status'] }}</span>
                                                @endif
                                            </td>
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
    <!--**********************************
                    Content body end
                ***********************************-->
@endsection
