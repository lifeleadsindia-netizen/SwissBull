@extends('member.layouts.main')
@section('title', 'Team Trading Profit')
@section('container')
@include('member.income._income-styles')

<div class="content-body inc-page">
    <div class="container-fluid py-4">

        <div class="inc-hero">
            <div class="inc-eyebrow">Income Section</div>
            @php
                $ttpCfg = \App\Models\TeamTradingProfitConfiction::getActiveSetting();
                $r1 = number_format((float) ($ttpCfg->level_1_rate ?? 0), 0);
                $r3 = number_format((float) ($ttpCfg->level_3_rate ?? 0), 0);
                $r5 = number_format((float) ($ttpCfg->level_5_rate ?? 0), 0);
                $r7 = number_format((float) ($ttpCfg->level_7_rate ?? 0), 0);
                $r9 = number_format((float) ($ttpCfg->level_9_rate ?? 0), 0);
            @endphp
            <h1><i class="fa-solid fa-layer-group me-2"></i>Team Trading Profit</h1>
            <p>Team trading profit share calculated across 10 levels: L1-L2 ({{ $r1 }}%), L3-L4 ({{ $r3 }}%), L5-L6 ({{ $r5 }}%), L7-L8 ({{ $r7 }}%), L9-L10 ({{ $r9 }}%).</p>
        </div>

        <div class="inc-card">
            <div class="inc-card-header">
                <div class="inc-header-icon"><i class="fa-solid fa-sitemap"></i></div>
                <div>
                    <div class="inc-header-title">Team Trading Profit History</div>
                    <div class="inc-header-sub">Daily trading profit commissions earned from your downline team across 10 levels</div>
                </div>
            </div>
            <div class="inc-table-shell">
                <table id="example3" class="inc-table display dataTable no-footer">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Member ID</th>
                            <th>Level</th>
                            <th>Level ID</th>
                            <th>Name</th>
                            <th>Rate</th>
                            <th>Staking Income</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($sData as $list)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                                <td>{{ $list['memberid'] }}</td>
                                <td>Level {{ $list['level'] }}</td>
                                <td>{{ $list['level_id'] }}</td>
                                <td>{{ !empty($list['name']) ? $list['name'] : getName($list['level_id']) }}</td>
                                <td>{{ $list['rate'] ?? levelRate($list['level']) }}%</td>
                                <td>$ {{ number_format($list['staking_income'] ?? $list['package'] ?? 0, 2) }}</td>
                                <td>$ {{ number_format($list['amount'], 2) }}</td>
                                <td>
                                    @if ($list['status'] == 'Unpaid')
                                        <span class="inc-badge unpaid"><i class="fa-solid fa-clock"></i>{{ $list['status'] }}</span>
                                    @elseif ($list['status'] == 'Paid')
                                        <span class="inc-badge paid"><i class="fa-solid fa-circle-check"></i>{{ $list['status'] }}</span>
                                    @else
                                        <span class="inc-badge pending"><i class="fa-solid fa-hourglass-half"></i>{{ $list['status'] }}</span>
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
@endsection
