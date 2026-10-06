@extends('member.layouts.main')
@section('title', 'Team Withdrawal Commission')
@section('container')
@include('member.income._income-styles')

<div class="content-body inc-page">
    <div class="container-fluid py-4">

        <div class="inc-hero">
            <div class="inc-eyebrow">Income Section</div>
            <h1><i class="fa-solid fa-hand-holding-dollar me-2"></i>Team Withdrawal Commission</h1>
            <p>Commissions earned from your team members' withdrawal transactions across levels.</p>
        </div>

        <div class="inc-card">
            <div class="inc-card-header">
                <div class="inc-header-icon"><i class="fa-solid fa-money-bill-transfer"></i></div>
                <div>
                    <div class="inc-header-title">Withdrawal Commission History</div>
                    <div class="inc-header-sub">Team withdrawal commissions credited to your account</div>
                </div>
            </div>
            <div class="inc-table-shell">
                @php
                    $withIncomes = \App\Models\WithdrawalIncome::where([['memberid', $data['memberid'] ?? session('MEMBER_ID')], ['status', 'Paid']])->orderby('created_at', 'desc')->get();
                    $incomeList = $withIncomes->isNotEmpty() ? $withIncomes : ((isset($wData) && count($wData) > 0) ? $wData : []);
                @endphp
                <table id="example3" class="inc-table display dataTable no-footer">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Member ID</th>
                            <th>Level</th>
                            <th>Level ID</th>
                            <th>Name</th>
                            <th>Withdrawal Amount</th>
                            <th>Rate</th>
                            <th>Commission Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($incomeList as $list)
                            @php $item = (array) (is_array($list) ? $list : $list->getAttributes()); @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('d-m-Y', strtotime($item['created_at'])) }}</td>
                                <td>{{ $item['memberid'] }}</td>
                                <td>Level {{ $item['level'] ?? '' }}</td>
                                <td>{{ $item['level_id'] ?? '' }}</td>
                                <td>{{ !empty($item['name']) ? $item['name'] : getName($item['level_id'] ?? '') }}</td>
                                <td>$ {{ number_format($item['withdrawal_amount'] ?? $item['package'] ?? 0, 2) }}</td>
                                <td>{{ $item['rate'] ?? 2 }}%</td>
                                <td>$ {{ number_format($item['amount'] ?? 0, 2) }}</td>
                                <td>
                                    @if (($item['status'] ?? '') == 'Unpaid')
                                        <span class="inc-badge unpaid"><i class="fa-solid fa-clock"></i>{{ $item['status'] }}</span>
                                    @elseif (($item['status'] ?? '') == 'Paid')
                                        <span class="inc-badge paid"><i class="fa-solid fa-circle-check"></i>{{ $item['status'] }}</span>
                                    @else
                                        <span class="inc-badge pending"><i class="fa-solid fa-hourglass-half"></i>{{ $item['status'] ?? 'Pending' }}</span>
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
