@extends('member.layouts.main')
@section('title', 'Single Leg Income')
@section('container')
@include('member.income._income-styles')

<div class="content-body inc-page">
    <div class="container-fluid py-4">

        <div class="inc-hero">
            <div class="inc-eyebrow">Income Section</div>
            <h1><i class="fa-solid fa-arrow-trend-up me-2"></i>Single Leg Income</h1>
            <p>Milestone-based rewards earned as your single-leg team grows through each level.</p>
        </div>

        <div class="inc-card">
            <div class="inc-card-header">
                <div class="inc-header-icon"><i class="fa-solid fa-person-walking-arrow-right"></i></div>
                <div>
                    <div class="inc-header-title">Single Leg Income History</div>
                    <div class="inc-header-sub">Rewards credited based on team size milestones</div>
                </div>
            </div>
            <div class="inc-table-shell">
                @php
                    $incomeList = (isset($sData) && count($sData) > 0) ? $sData : \Illuminate\Support\Facades\DB::table('single_leg_incomes')->where([['memberid', $data['memberid'] ?? session('MEMBER_ID')], ['status', 'Paid']])->orderby('created_at', 'desc')->get();
                @endphp
                <table id="example3" class="inc-table display dataTable no-footer">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Member ID</th>
                            <th>Stage</th>
                            <th>Required Team</th>
                            <th>Self Staking</th>
                            <th>Income Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($incomeList as $list)
                            @php
                                $item = is_array($list) ? $list : (method_exists($list, 'getAttributes') ? $list->getAttributes() : (array) $list);
                                $dateVal = $item['created_at'] ?? $list->created_at ?? $item['date'] ?? $list->date ?? date('Y-m-d');
                                $memberidVal = $item['memberid'] ?? $list->memberid ?? '';
                                $levelVal = $item['level'] ?? $list->level ?? '';
                                $teamVal = $item['team'] ?? $list->team ?? '';
                                $stakingVal = $item['staking'] ?? $list->staking ?? 0;
                                $amountVal = $item['amount'] ?? $list->amount ?? 0;
                                $statusVal = $item['status'] ?? $list->status ?? 'Paid';
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('d-m-Y', strtotime($dateVal)) }}</td>
                                <td>{{ $memberidVal }}</td>
                                <td>Stage {{ $levelVal }}</td>
                                <td>{{ $teamVal }}</td>
                                <td>$ {{ number_format($stakingVal, 2) }}</td>
                                <td>$ {{ number_format($amountVal, 2) }}</td>
                                <td>
                                    @if ($statusVal == 'Unpaid')
                                        <span class="inc-badge unpaid"><i class="fa-solid fa-clock"></i>{{ $statusVal }}</span>
                                    @elseif ($statusVal == 'Paid')
                                        <span class="inc-badge paid"><i class="fa-solid fa-circle-check"></i>{{ $statusVal }}</span>
                                    @else
                                        <span class="inc-badge pending"><i class="fa-solid fa-hourglass-half"></i>{{ $statusVal }}</span>
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
