@extends('member.layouts.main')
@section('title', 'Hero of the Month Rewards')
@section('container')
@include('member.income._income-styles')

<div class="content-body inc-page">
    <div class="container-fluid py-4">

        <div class="inc-hero">
            <div class="inc-eyebrow">Income Section</div>
            <h1><i class="fa-solid fa-trophy me-2"></i>Hero of the Month</h1>
            <p>2% monthly pool reward dedicated to the highest direct business performer of the month.</p>
        </div>

        <div class="inc-card">
            <div class="inc-card-header">
                <div class="inc-header-icon"><i class="fa-solid fa-award"></i></div>
                <div>
                    <div class="inc-header-title">Hero of the Month Reward History</div>
                    <div class="inc-header-sub">Monthly 2% pool distributions credited to your account</div>
                </div>
            </div>
            <div class="inc-table-shell">
                <table id="example3" class="inc-table display dataTable no-footer">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Month / Year</th>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Direct Business</th>
                            <th>Total Pool Turnover</th>
                            <th>Pool Share</th>
                            <th>Prize Amount</th>
                            <th>Rank</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($hData as $list)
                            <tr>
                                <td>{{ $i }}</td>
                                <td><strong>{{ $list['month'] ?? $list['month_year'] ?? date('F Y', strtotime($list['created_at'])) }}</strong></td>
                                <td>{{ $list['memberid'] }}</td>
                                <td>{{ $list['member_name'] ?? getName($list['memberid']) }}</td>
                                <td>$ {{ number_format((float) ($list['direct_business'] ?? 0), 2) }}</td>
                                <td>$ {{ number_format((float) ($list['total_pool'] ?? $list['total_pool_business'] ?? 0), 2) }}</td>
                                <td>{{ number_format((float) ($list['pool_percentage'] ?? $list['pool_rate'] ?? 2.0), 1) }}%</td>
                                <td><strong class="text-success">$ {{ number_format((float) ($list['prize_amount'] ?? 0), 2) }}</strong></td>
                                <td><span class="badge bg-warning text-dark font-weight-bold">#{{ $list['rank'] ?? 1 }} Hero</span></td>
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
