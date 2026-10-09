@extends('admin.layouts.main')
@section('title', 'New Withdrawal Requests')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-edit bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('New Withdrawal Requests') }}</h5>
                            <span>{{ __('New Withdrawal Requests to verify') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Admin') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Withdrawal Requests') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                @include('admin.partials.history-date-filter', ['showMemberIdFilter' => true])
                
                @if (session()->has('wMessage'))
                    <div class="alert alert-primary">{{ session('wMessage') }}</div>
                @endif
                <div class="card" style="overflow:auto">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h3>{{ __('New Withdrawal Requests') }}</h3>
                        <ul class="nav nav-pills" id="withdrawalTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active font-weight-bold" id="usdt-tab" data-toggle="pill"
                                    href="#usdt-panel" role="tab" aria-controls="usdt-panel" aria-selected="true"
                                    style="border-radius: 6px; padding: 8px 18px; border: 1px solid #007bff;">
                                    <i class="ik ik-dollar-sign mr-1"></i> {{ __('USDT') }}
                                    <span class="badge badge-light ml-1">{{ count($data ?? []) }}</span>
                                </a>
                            </li>
                            <li class="nav-item ml-2">
                                <a class="nav-link font-weight-bold" id="pepe-tab" data-toggle="pill" href="#pepe-panel"
                                    role="tab" aria-controls="pepe-panel" aria-selected="false"
                                    style="border-radius: 6px; padding: 8px 18px; border: 1px solid #28a745; color: #28a745;">
                                    <i class="ik ik-award mr-1"></i> {{ __('PEPE Tokens') }}
                                    <span class="badge badge-success ml-1">{{ count($pepeData ?? []) }}</span>
                                </a>
                            </li>
                            <li class="nav-item ml-2">
                                <a class="nav-link font-weight-bold" id="trading-tab" data-toggle="pill" href="#trading-panel"
                                    role="tab" aria-controls="trading-panel" aria-selected="false"
                                    style="border-radius: 6px; padding: 8px 18px; border: 1px solid #ffc107; color: #ffc107;">
                                    <i class="ik ik-bar-chart-2 mr-1"></i> {{ __('Trading') }}
                                    <span class="badge badge-warning ml-1">{{ count($tradingData ?? []) }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="withdrawalTabsContent">
                            <!-- 1st Tab: USDT Requests -->
                            <div class="tab-pane fade show active" id="usdt-panel" role="tabpanel" aria-labelledby="usdt-tab">
                                <div class="table-responsive">
                                    <table id="data_table" class="table px-3" style="zoom: 90%; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('S.No') }}</th>
                                                <th>{{ __('Request Date') }}</th>
                                                <th>{{ __('Request Id') }}</th>
                                                <th>{{ __('Member Id') }}</th>
                                                <th>{{ __('Wallet Type') }}</th>
                                                <th>{{ __('Wallet Address') }}</th>
                                                {{-- <th>{{ __('Gross') }}</th> --}}
                                                {{-- <th>{{ __('Charges') }}</th> --}}
                                                <th>{{ __('Net Amount ') }}</th>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $symbol = $fiat['country'] ?? ''; @endphp
                                            @php $i = 1;  @endphp
                                            @foreach ($data as $list)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($list['created_at']))}}<br>{{ date('H:i:s', strtotime($list['created_at']))}}</td>
                                                    
                                                    <td>{{ $list['request_id'] }}</td>
                                                    <td>{{ $list['memberid'] }}</td>
                                                    <td>{{ $list['type'] }}</td>
                                                    <td>{{ $list['wallet_address'] }}</td>
                                                    {{-- <td> $ {{ $list['gross_amount'] }}</td> --}}
                                                    {{-- <td>$ {{ $list['service_charge'] }}</td> --}}
                                                    <td>$ {{ $list['net_amount'] }}</td>
                                                    <td class="px-0">
                                                        <a href="{{ url('admin/withdrawal/accept-online') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-success mx-0">Online Pay</a>
                                                    </td>
                                                    <td class="px-1">
                                                        <a href="{{ url('admin/withdrawal/accept') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-primary mx-0">Accept</a>
                                                    </td>
                                                    <td class="px-0">
                                                        <a href="{{ url('admin/withdrawal/cancel') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-danger mx-0">Cancel</a>
                                                    </td>
                                                </tr>
                                                @php $i++;  @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 2nd Tab: PEPE Tokens Requests -->
                            <div class="tab-pane fade" id="pepe-panel" role="tabpanel" aria-labelledby="pepe-tab">
                                <div class="table-responsive">
                                    <table id="pepe_data_table" class="table px-3" style="zoom: 90%; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('S.No') }}</th>
                                                <th>{{ __('Request Date') }}</th>
                                                <th>{{ __('Request Id') }}</th>
                                                <th>{{ __('Member Id') }}</th>
                                                <th>{{ __('BEP-20 Wallet Address') }}</th>
                                                <th>{{ __('PEPE Tokens') }}</th>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $j = 1; @endphp
                                            @forelse (($pepeData ?? []) as $list)
                                                <tr>
                                                    <td>{{ $j }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($list['created_at']))}}<br>{{ date('H:i:s', strtotime($list['created_at']))}}</td>
                                                    <td><span class="badge badge-secondary">{{ $list['request_id'] }}</span></td>
                                                    <td><strong>{{ $list['memberid'] }}</strong></td>
                                                    <td>
                                                        <code style="font-size: 11px; color: #007bff; word-break: break-all;">{{ $list['wallet_address'] }}</code>
                                                    </td>
                                                    <td>
                                                        <strong class="text-success" style="font-size: 14px;">
                                                            {{ number_format($list['gross_amount'], 0) }} PEPE
                                                        </strong>
                                                    </td>
                                                    <td class="px-0">
                                                        <a href="{{ url('admin/withdrawal-accept-pepe') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-success mx-0">Online Pay</a>
                                                    </td>
                                                    <td class="px-1">
                                                        <a href="{{ url('admin/withdrawal/accept') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-primary mx-0">Accept</a>
                                                    </td>
                                                    <td class="px-0">
                                                        <a href="{{ url('admin/withdrawal/cancel') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-danger mx-0">Cancel</a>
                                                    </td>
                                                </tr>
                                                @php $j++; @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-4 text-muted">
                                                        No new PEPE token requests found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 3rd Tab: Trading Requests -->
                            <div class="tab-pane fade" id="trading-panel" role="tabpanel" aria-labelledby="trading-tab">
                                <div class="table-responsive">
                                    <table id="trading_data_table" class="table px-3" style="zoom: 90%; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('S.No') }}</th>
                                                <th>{{ __('Request Date') }}</th>
                                                <th>{{ __('Request Id') }}</th>
                                                <th>{{ __('Member Id') }}</th>
                                                <th>{{ __('Wallet Type') }}</th>
                                                <th>{{ __('Wallet Address') }}</th>
                                                <th>{{ __('Net Amount ') }}</th>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $k = 1;  @endphp
                                            @forelse (($tradingData ?? []) as $list)
                                                <tr>
                                                    <td>{{ $k }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($list['created_at']))}}<br>{{ date('H:i:s', strtotime($list['created_at']))}}</td>
                                                    
                                                    <td>{{ $list['request_id'] }}</td>
                                                    <td>{{ $list['memberid'] }}</td>
                                                    <td>{{ $list['type'] }}</td>
                                                    <td>{{ $list['wallet_address'] }}</td>
                                                    <td>$ {{ $list['net_amount'] }}</td>
                                                    <td class="px-0">
                                                        <a href="{{ url('admin/withdrawal/accept-online') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-success mx-0">Online Pay</a>
                                                    </td>
                                                    <td class="px-1">
                                                        <a href="{{ url('admin/withdrawal/accept') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-primary mx-0">Accept</a>
                                                    </td>
                                                    <td class="px-0">
                                                        <a href="{{ url('admin/withdrawal/cancel') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-danger mx-0">Cancel</a>
                                                    </td>
                                                </tr>
                                                @php $k++;  @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center py-4 text-muted">
                                                        No new Trading withdrawal requests found.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
        <script>
            $(document).ready(function() {
                if ($('#pepe_data_table').length && !$.fn.DataTable.isDataTable('#pepe_data_table')) {
                    $('#pepe_data_table').DataTable({
                        responsive: true,
                        order: [
                            [1, 'desc']
                        ]
                    });
                }

                if ($('#trading_data_table').length && !$.fn.DataTable.isDataTable('#trading_data_table')) {
                    $('#trading_data_table').DataTable({
                        responsive: true,
                        order: [
                            [1, 'desc']
                        ]
                    });
                }

                // Adjust columns when switching tabs
                $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                });
            });
        </script>
    @endpush
@endsection
