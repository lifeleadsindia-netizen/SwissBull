@extends('member.layouts.main')
@section('title', 'Partnership Income')
@section('container')

    <!--**********************************
                        Content body start
                    ***********************************-->
    @include('member.income._income-styles')
    <div class="content-body inc-page">
        <div class="container-fluid py-4">
            <div class="inc-hero">
                <div class="inc-eyebrow">Income Section</div>
                <h1><i class="fa-solid fa-handshake me-2"></i>Partnership Income</h1>
                <p>Commissions earned from your team's partnership performance and milestones.</p>
            </div>
            <div class="inc-card">
                <div class="inc-card-header">
                    <div class="inc-header-icon"><i class="fa-solid fa-people-group"></i></div>
                    <div>
                        <div class="inc-header-title">Partnership Income History</div>
                        <div class="inc-header-sub">Team milestones and partnership credits</div>
                    </div>
                </div>
                <div class="inc-table-shell">
                    @php
                        $partIncomes = \App\Models\PartnershipIncome::where([['memberid', $data['memberid'] ?? session('MEMBER_ID')], ['status', 'Paid']])->orderby('created_at', 'desc')->get();
                        $incomeList = $partIncomes->isNotEmpty() ? $partIncomes : ((isset($pData) && count($pData) > 0) ? $pData : []);
                    @endphp
                    <table id="example3" class="inc-table display dataTable no-footer">
                        <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Date</th>
                                            <th>Member ID</th>
                                            <th>Rank</th>
                                            <th>Total Investment</th>
                                            <th>Rate</th>
                                            <th>Daily Team Biz</th>
                                            <th>Installment</th>
                                            <th>Achieved</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1;  @endphp
                                        @foreach ($incomeList as $list)
                                            @php
                                                $item = (array) (is_array($list) ? $list : $list->getAttributes());
                                            @endphp
                                            <tr>
                                                <td>{{ $i }}</td>
                                                <td>{{ date('d-m-Y', strtotime($item['date'] ?? $item['created_at'])) }}</td>
                                                <td>{{ $item['memberid'] }}</td>
                                                <td>
                                                    @if (!empty($item['rank']))
                                                        <span class="badge badge-info">{{ $item['rank'] }}</span>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>$ {{ number_format($item['total_investment'] ?? $item['package'] ?? 0, 2) }}</td>
                                                <td>{{ $item['rate'] ?? 0 }}%</td>
                                                <td>$ {{ number_format($item['daily_team_biz'] ?? 0, 2) }}</td>
                                                <td>{{ $item['installment'] ?? 1 }}</td>
                                                <td>$ {{ number_format($item['achieved'] ?? 0, 2) }}</td>
                                                <td>$ {{ number_format($item['amount'] ?? 0, 2) }}</td>
                                                <td>
                                                    @if (($item['status'] ?? '') == 'Unpaid')
                                                        <span class="badge badge-danger">{{ $item['status'] }}</span>
                                                    @elseif (($item['status'] ?? '') == 'Paid')
                                                        <span class="badge badge-success">{{ $item['status'] }}</span>
                                                    @else
                                                        <span class="badge badge-warning">{{ $item['status'] ?? 'Pending' }}</span>
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
    </div>
    <!--**********************************
                        Content body end
                    ***********************************-->
@endsection
