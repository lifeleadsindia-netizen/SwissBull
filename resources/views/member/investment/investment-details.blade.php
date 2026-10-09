@extends('member.layouts.main')
@section('title', 'Staking History')
@section('container')
    @include('member.income._income-styles')

    <!--**********************************
                            Content body start
                        ***********************************-->
    <div class="content-body inc-page">
        <div class="container-fluid py-4">
            <!-- row -->
            <div class="row">
                <div class="col-12">
                    <div class="inc-card">
                        <div class="inc-card-header">
                            <div class="inc-header-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                            <div>
                                <div class="inc-header-title">Staking History</div>
                                <div class="inc-header-sub">Current staking investment summary</div>
                            </div>
                        </div>
                        <div class="inc-table-shell">
                            <table id="example3" class="inc-table display dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Package Range</th>
                                        <th>Invested</th>
                                        <th>Txnid</th>
                                        <th>Installments</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1;  @endphp
                                    @foreach ($investment_data as $list)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>
                                                <small
                                                    class="d-block text-white-50">{{ $list->created_at ? $list->created_at->format('d M Y') : '' }}</small>
                                            </td>
                                            <td>
                                                @if ($list->package == 'Package1')
                                                    <span class="text-white font-weight-bold">$50 - $500</span>
                                                @elseif ($list->package == 'Package2')
                                                    <span class="text-white font-weight-bold">$600 - $5000</span>
                                                @else
                                                    <span class="text-white font-weight-bold">$6000</span>
                                                @endif
                                            </td>
                                            <td class="text-warning font-weight-bold">
                                                ${{ number_format((float) $list->invest_amount, 2) }}</td>
                                            <td class="text-warning font-weight-bold">
                                                {{ $list->txnid }}</td>
                                            <td>{{ $list->installments }}</td>
                                            <td>
                                                @if ($list->status === 'Active')
                                                    <span class="badge"
                                                        style="background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 6px; padding: 4px 8px;">Active</span>
                                                @else
                                                    <span class="badge"
                                                        style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; padding: 4px 8px;">Expired</span>
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
    <!--**********************************
                            Content body end
                        ***********************************-->
@endsection
