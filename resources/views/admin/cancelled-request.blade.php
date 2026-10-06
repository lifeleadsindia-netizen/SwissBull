@extends('admin.layouts.main')
@section('title', 'Cancelled Withdrawal Requests')
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
                            <h5>{{ __('Cancelled Withdrawal Requests')}}</h5>
                            <span>{{ __('Cancelled Withdrawal Requests ')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Admin')}}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Cancelled Withdrawal Requests')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                @include('admin.partials.history-date-filter')
                
                @if (session()->has('wMessage'))
                   <div class="alert alert-primary">{{session('wMessage')}}</div>
                @endif
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h3>{{ __('Cancelled Withdrawal Requests') }}</h3>
                        <ul class="nav nav-pills" id="cancelledTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active font-weight-bold" id="usdt-tab" data-toggle="pill" href="#usdt-panel" role="tab" aria-controls="usdt-panel" aria-selected="true" style="border-radius: 6px; padding: 8px 18px; border: 1px solid #007bff;">
                                    <i class="ik ik-dollar-sign mr-1"></i> {{ __('USDT') }}
                                    <span class="badge badge-light ml-1">{{ count($data ?? []) }}</span>
                                </a>
                            </li>
                            <li class="nav-item ml-2">
                                <a class="nav-link font-weight-bold" id="pepe-tab" data-toggle="pill" href="#pepe-panel" role="tab" aria-controls="pepe-panel" aria-selected="false" style="border-radius: 6px; padding: 8px 18px; border: 1px solid #dc3545; color: #dc3545;">
                                    <i class="ik ik-award mr-1"></i> {{ __('PEPE Tokens') }}
                                    <span class="badge badge-danger ml-1">{{ count($pepeData ?? []) }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="cancelledTabsContent">
                            <!-- 1st Tab: USDT Cancelled Requests -->
                            <div class="tab-pane fade show active" id="usdt-panel" role="tabpanel" aria-labelledby="usdt-tab">
                                <div class="table-responsive">
                                    <table id="data_table" class="table px-3" style="zoom: 90%; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('S.No')}}</th>
                                                <th>{{ __('Request Date')}}</th>
                                                <th>{{ __('Request Id') }}</th>
                                                <th>{{ __('Member Id')}}</th>
                                                <th>{{ __('Name')}}</th>
                                                <th>{{ __('Type')}}</th>
                                                <th>{{ __('Gross')}}</th>
                                                <th>{{ __('Charges')}}</th>
                                                <th>{{ __('Net ')}}</th>
                                                <th>{{ __('Cancelled On ')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1;  @endphp
                                            @foreach ($data as $list )
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{ date('d-m-Y', strtotime($list['request_date']))}}<br>{{ date('H:i:s', strtotime($list['request_date']))}}</td>
                                                    <td>{{ $list['request_id']}}</td>
                                                    <td>{{ $list['memberid']}}</td>
                                                    <td>{{ $list['name']}}</td>
                                                    <td><span class="badge badge-{{$list['type'] == 'Exchange'? 'warning':'primary'}}">{{ $list['type'] }}</span></td>
                                                    <td>$ {{ $list['gross_amount']}}</td>
                                                    <td>$ {{ $list['service_charge']}}</td>
                                                    <td>$ {{ $list['net_amount']}}</td>
                                                    <td>{{date('d-m-Y', strtotime( $list['updated_at']))}}</td>
                                                </tr>
                                                @php $i++;  @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- 2nd Tab: PEPE Tokens Cancelled Requests -->
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
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Refund Status') }}</th>
                                                <th>{{ __('Cancelled On') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $j = 1; @endphp
                                            @forelse (($pepeData ?? []) as $list)
                                                <tr>
                                                    <td>{{ $j }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($list['request_date'])) }}<br>{{ date('H:i:s', strtotime($list['request_date'])) }}</td>
                                                    <td><span class="badge badge-secondary">{{ $list['request_id'] }}</span></td>
                                                    <td><strong>{{ $list['memberid'] }}</strong></td>
                                                    <td>
                                                        <code style="font-size: 11px; color: #007bff; word-break: break-all;">{{ $list['wallet_address'] }}</code>
                                                    </td>
                                                    <td>
                                                        <strong class="text-danger" style="font-size: 14px;">
                                                            {{ number_format($list['gross_amount'], 0) }} PEPE
                                                        </strong>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-danger">{{ $list['status'] }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-success"><i class="ik ik-check-circle mr-1"></i> Refunded to Wallet</span>
                                                    </td>
                                                    <td>{{ date('d-m-Y H:i', strtotime($list['updated_at'])) }}</td>
                                                </tr>
                                                @php $j++; @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center py-4 text-muted">
                                                        No cancelled PEPE token requests found.
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
                        order: [[1, 'desc']]
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
