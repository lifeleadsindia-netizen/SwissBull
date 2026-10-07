@extends('member.layouts.main')
@section('title', 'Monthly Trading Profit')
@section('container')
@include('member.income._income-styles')

<div class="content-body inc-page">
    <div class="container-fluid py-4">

        <div class="inc-hero">
            <div class="inc-eyebrow">Income Section</div>
            <h1><i class="fa-solid fa-chart-line me-2"></i>Monthly Trading Profit</h1>
            <p>Your package-based daily trading return on investment earnings — credited automatically up to your monthly tier limit.</p>
        </div>

        <div class="inc-card">
            <div class="inc-card-header">
                <div class="inc-header-icon"><i class="fa-solid fa-percent"></i></div>
                <div>
                    <div class="inc-header-title">Monthly Trading Profit History</div>
                    <div class="inc-header-sub">All daily trading profit installments credited to your account</div>
                </div>
            </div>
            <div class="inc-table-shell">
                <table id="example3" class="inc-table display dataTable no-footer">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Member ID</th>
                            <th>Name</th>
                            <th>Total Investment</th>
                            <th>Rate</th>
                            <th>Installment</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($rData as $list)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('d-m-Y', strtotime($list['date'] ?? $list['created_at'])) }}</td>
                                <td>{{ $list['memberid'] }}</td>
                                <td>{{ getName($list['memberid']) }}</td>
                                <td>$ {{ number_format($list['total_investment'], 2) }}</td>
                                <td>{{ $list['rate'] }}%</td>
                                <td>{{ $list['installment'] }}</td>
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
