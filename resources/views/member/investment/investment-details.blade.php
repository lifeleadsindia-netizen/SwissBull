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
                                        <th>Invest Amount</th>
                                        <th>Trading Amount</th>
                                        <th> ROI Rate</th>
                                        <th>Cap %</th>
                                        <th>Max ROI</th>
                                        <th>Earned</th>
                                        <th>Installments</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1;  @endphp
                                    @foreach ($investment_data as $list)
                                        @php
                                            $item = $list instanceof \App\Models\StakingDetail ? $list : \App\Models\StakingDetail::find($list['id']);
                                            $capPercent = $item ? $item->getCappingPercent() : ($list['capping_percent'] ?? 200.0);
                                            $maxAmt = $item ? $item->getMaxRoiAmount() : ((float) $list['invest_amount'] * ($capPercent / 100));
                                            $earned = $item ? $item->getTotalEarned() : (float) ($list['total_earned'] ?? 0);
                                            $rem = max(0.00, round($maxAmt - $earned, 2));
                                        @endphp
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td> {{ date('d-m-Y', strtotime($list['invest_date'])) }}</td>
                                            <td>$ {{ number_format((float) $list['invest_amount'], 2) }}</td>
                                            <td>$ {{ number_format((float) $list['trading_wallet_amount'], 2) }}</td>
                                            <td>{{ $item ? $item->getDailyRate() : $list['rate'] }}%</td>
                                            <td>{{ number_format($capPercent, 1) }}%</td>
                                            <td>$ {{ number_format($maxAmt, 2) }}</td>
                                            <td>$ {{ number_format($earned, 2) }}</td>
                                            <td>{{ $list['installments'] }}</td>
                                            <td>
                                                @if ($list['status'] == 'Active')
                                                    <span class="badge badge-success">{{ $list['status'] }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ $list['status'] }}</span>
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
