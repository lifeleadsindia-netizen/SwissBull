@extends('member.layouts.main')
@section('title', 'Partnership Investment Details')
@section('container')
    @include('member.partnership._styles')

    <div class="content-body staking-page">
        <div class="container-fluid py-4 py-md-5">

            {{-- Hero Banner --}}
            <div class="staking-hero mb-4 mb-md-5">
                <div class="staking-eyebrow">Partnership Section</div>
                <h1>Partnership Investment Details</h1>
                <p>View all your active and previous partnership investments and installment details.</p>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="inc-card">
                        <div class="inc-card-header">
                            <div class="inc-header-icon">
                                <i class="fa-solid fa-list-check"></i>
                            </div>
                            <div>
                                <div class="inc-header-title">Investment History</div>
                                <div class="inc-header-sub">All your registered partnership packages</div>
                            </div>
                        </div>
                        <div class="inc-table-shell">
                            <table id="example3" class="inc-table display dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Invest Date</th>
                                        <th>Member ID</th>
                                        <th>Rank</th>
                                        <th>Invest Amount</th>
                                        <th>Installments</th>
                                        <th>Rate</th>
                                        <th>Capping</th>
                                        <th>Achieved</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($investment_data as $list)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ date('d-m-Y', strtotime($list['invest_date'])) }}</td>
                                            <td>{{ $list['memberid'] }}</td>
                                            <td>
                                                @if (!empty($list['rank']))
                                                    <span class="badge badge-info">{{ $list['rank'] }}</span>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>$ {{ number_format((float) ($list['invest_amount'] ?? 0), 2) }}</td>
                                            <td>{{ $list['installments'] }}</td>
                                            <td>{{ $list['rate'] }}%</td>
                                            <td>$ {{ number_format((float) ($list['capping'] ?? 0), 2) }}</td>
                                            <td>$ {{ number_format((float) ($list['achieved'] ?? 0), 2) }}</td>
                                            <td>
                                                @if ($list['status'] == 'Active')
                                                    <span class="inc-badge paid">
                                                        <i class="fa-solid fa-circle-check"></i> {{ $list['status'] }}
                                                    </span>
                                                @else
                                                    <span class="inc-badge unpaid">
                                                        <i class="fa-solid fa-circle-xmark"></i> {{ $list['status'] }}
                                                    </span>
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

        </div>
    </div>
@endsection
