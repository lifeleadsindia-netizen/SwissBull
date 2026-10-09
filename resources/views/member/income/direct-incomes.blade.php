@extends('member.layouts.main')
@section('title', 'Referral Bonus')
@section('container')
@include('member.income._income-styles')

<div class="content-body inc-page">
    <div class="container-fluid py-4">

        <div class="inc-hero">
            <div class="inc-eyebrow">Income Section</div>
            <h1><i class="fa-solid fa-user-plus me-2"></i>Referral Bonus</h1>
            <p>Direct referral commissions earned from your 3-tier sponsor network: Level 1 (5%), Level 2 (3%), Level 3 (2%).</p>
        </div>

        <div class="inc-card">
            <div class="inc-card-header">
                <div class="inc-header-icon"><i class="fa-solid fa-users-line"></i></div>
                <div>
                    <div class="inc-header-title">Referral Bonus History</div>
                    <div class="inc-header-sub">All direct referral commissions credited to your account</div>
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
                            <th>From Member</th>
                            <th>Package Amount</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($dData as $list)
                            @php
                                $pkg = (float) ($list['package'] ?? 0);
                                $amt = (float) ($list['amount'] ?? 0);
                                $storedRate = isset($list['rate']) && $list['rate'] !== null && (float) $list['rate'] > 0 ? (float) $list['rate'] : null;
                                $rate = $storedRate ?? ($pkg > 0 ? round(($amt / $pkg) * 100, 1) : 0);
                                $levelNum = $list['level'] ?? 1;
                            @endphp
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                                <td>{{ $list['memberid'] }}</td>
                                <td><span class="badge bg-info text-white">Level {{ $levelNum }}</span></td>
                                <td>{{ $list['activatingid'] ?? 'N/A' }} ({{ $list['name'] ?? '' }})</td>
                                <td>$ {{ number_format($pkg, 2) }}</td>
                                <td>{{ $rate > 0 ? $rate.'%' : 'Standard' }}</td>
                                <td>$ {{ number_format($amt, 2) }}</td>
                                <td><span class="badge bg-primary">{{ $list['type'] ?? 'Referral Bonus' }}</span></td>
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
