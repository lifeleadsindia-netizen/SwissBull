@extends('admin.layouts.main')
@section('title', 'Payment History')
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
                            <h5>{{ __('Paid Withdrawal Requests') }}</h5>
                            <span>{{ __('All Paid Withdrawal Requests ') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Paid Withdrawal Requests') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                @include('admin.partials.history-date-filter')

                @if (session()->has('wMessage'))
                    <div class="alert alert-primary">{{ session('wMessage') }}</div>
                @endif
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h3>{{ __('Paid Withdrawal Requests') }}</h3>
                        <ul class="nav nav-pills" id="historyTabs" role="tablist">
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
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="historyTabsContent">
                            <!-- 1st Tab: USDT Paid Requests -->
                            <div class="tab-pane fade show active" id="usdt-panel" role="tabpanel"
                                aria-labelledby="usdt-tab">
                                <div class="table-responsive">
                                    <table id="data_table" class="table px-3" style="zoom: 90%; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('S.No') }}</th>
                                                <th>{{ __('Request Date') }}</th>
                                                <th>{{ __('Request Id') }}</th>
                                                <th>{{ __('Member Id') }}</th>
                                                <th>{{ __('Wallet Address') }}</th>
                                                <th>{{ __('Type') }}</th>
                                                <th>{{ __('Gross') }}</th>
                                                <th>{{ __('Charges') }}</th>
                                                <th>{{ __('Net ') }}</th>
                                                <th>{{ __('Payment Date') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1;  @endphp
                                            @foreach ($data as $list)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{ $list['request_date'] }}</td>
                                                    <td>{{ $list['request_id'] }}</td>
                                                    <td>{{ $list['memberid'] }}</td>
                                                    <td>{{ $list['wallet_address'] }}</td>
                                                    <td><span
                                                            class="badge badge-{{ $list['type'] == 'Exchange' ? 'warning' : 'primary' }}">{{ $list['type'] }}</span>
                                                    </td>
                                                    <td>$ {{ $list['gross_amount'] }}</td>
                                                    <td>$ {{ $list['service_charge'] }}</td>
                                                    <td>$ {{ $list['net_amount'] }}</td>
                                                    <td>{{ $list['payment_date'] }}</td>
                                                    <td><span class="badge badge-success">{{ $list['status'] }}</span></td>
                                                </tr>
                                                @php $i++;  @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 2nd Tab: PEPE Tokens Paid Requests -->
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
                                                <th>{{ __('Tx Hash') }}</th>
                                                <th>{{ __('Payment Date') }}</th>
                                                <th>{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $j = 1; @endphp
                                            @forelse (($pepeData ?? []) as $list)
                                                <tr>
                                                    <td>{{ $j }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($list['request_date'])) }}<br>{{ date('H:i:s', strtotime($list['request_date'])) }}
                                                    </td>
                                                    <td><span
                                                            class="badge badge-secondary">{{ $list['request_id'] }}</span>
                                                    </td>
                                                    <td><strong>{{ $list['memberid'] }}</strong></td>
                                                    <td>
                                                        <code
                                                            style="font-size: 11px; color: #007bff; word-break: break-all;">{{ $list['wallet_address'] }}</code>
                                                    </td>
                                                    <td>
                                                        <strong class="text-success" style="font-size: 14px;">
                                                            {{ number_format($list['gross_amount'], 0) }} PEPE
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        @if (!empty($list['txnid']))
                                                            <a href="{{ rtrim($pepeSettings->explorer_url ?? 'https://bscscan.com', '/') }}/tx/{{ $list['txnid'] }}"
                                                                target="_blank" class="badge badge-primary text-white"
                                                                title="{{ $list['txnid'] }}"
                                                                style="text-decoration: none;">
                                                                <i class="ik ik-external-link mr-1"></i>
                                                                {{ substr($list['txnid'], 0, 6) }}...{{ substr($list['txnid'], -4) }}
                                                            </a>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $list['payment_date'] ? date('d-m-Y H:i', strtotime($list['payment_date'])) : '-' }}
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success">{{ $list['status'] }}</span>
                                                    </td>
                                                </tr>
                                                @php $j++; @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-4 text-muted">
                                                        No paid PEPE token requests found.
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

                // Adjust columns when switching tabs
                $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                    $.fn.dataTable.tables({
                        visible: true,
                        api: true
                    }).columns.adjust();
                });
            });
        </script>
    @endpush
@endsection
