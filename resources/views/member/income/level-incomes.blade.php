@extends('member.layouts.main')
@section('title', 'Level Income')
@section('container')
@include('member.income._income-styles')

<div class="content-body inc-page">
    <div class="container-fluid py-4">

        <div class="inc-hero">
            <div class="inc-eyebrow">Income Section</div>
            <h1><i class="fa-solid fa-diagram-successor me-2"></i>Level Income</h1>
            <p>Commissions earned from your downline team's package activations across levels.</p>
        </div>

        <div class="inc-card">
            <div class="inc-card-header">
                <div class="inc-header-icon"><i class="fa-solid fa-network-wired"></i></div>
                <div>
                    <div class="inc-header-title">Level Income History</div>
                    <div class="inc-header-sub">Level-wise commission records from team activity</div>
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
                            <th>Package Amount</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($lData as $list)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                                <td>{{ $list['memberid'] }}</td>
                                <td>Level {{ $list['level'] }}</td>
                                <td>{{ $list['level_id'] }}</td>
                                <td>{{ !empty($list['name']) ? $list['name'] : getName($list['level_id']) }}</td>
                                <td>$ {{ number_format($list['package'] ?? $list['staking_income'] ?? 0, 2) }}</td>
                                <td>{{ $list['rate'] ?? levelRate($list['level']) }}%</td>
                                <td>$ {{ number_format($list['amount'], 2) }}</td>
                                <td>
                                    @if ($list['status'] == 'Unpaid')
                                        <span class="inc-badge unpaid"><i class="fa-solid fa-clock"></i>{{ $list['status'] }}</span>
                                    @elseif ($list['status'] == 'Paid')
                                        <span class="inc-badge paid"><i class="fa-solid fa-circle-check"></i>{{ $list['status'] }}</span>
                                    @else
                                        <span class="inc-badge unpaid"><i class="fa-solid fa-clock"></i>{{ $list['status'] }}</span>
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
