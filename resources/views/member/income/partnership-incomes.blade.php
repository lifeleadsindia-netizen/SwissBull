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
                                            <th>Total Capping</th>
                                            <th>Available Capping</th>
                                            <th>Total Earn</th>
                                            <th>Last Earn</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1;  @endphp
                                        @foreach ($incomeList as $list)
                                            @php
                                                $item = (array) (is_array($list) ? $list : $list->getAttributes());
                                                $invAmt = (float) ($item['total_investment'] ?? ($item['package'] ?? 0));
                                                $investId = $item['invest_id'] ?? null;
                                                $detail = $investId ? \App\Models\PartnershipDetail::find($investId) : null;
                                                if ($detail && (float) $detail->capping > 0) {
                                                    $totalCapping = (float) $detail->capping;
                                                } else {
                                                    $rankLower = strtolower((string) ($item['rank'] ?? ($detail?->rank ?? '')));
                                                    if ($rankLower === 'silver' || $invAmt == 1000) {
                                                        $cappingMult = 2;
                                                    } elseif ($rankLower === 'gold' || $invAmt == 5000) {
                                                        $cappingMult = 4;
                                                    } elseif ($rankLower === 'platinum' || $invAmt == 10000) {
                                                        $cappingMult = 6;
                                                    } elseif ($rankLower === 'diamond' || $invAmt == 25000) {
                                                        $cappingMult = 8;
                                                    } else {
                                                        $cappingMult = 2;
                                                    }
                                                    $totalCapping = $invAmt * $cappingMult;
                                                }
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
                                                <td>$ {{ number_format($totalCapping, 2) }}</td>
                                                <td>$ {{ number_format($totalCapping - $item['achieved'], 2) }}</td>
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
