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
                @include('admin.partials.history-date-filter')
                
                @if (session()->has('wMessage'))
                    <div class="alert alert-primary">{{ session('wMessage') }}</div>
                @endif
                <div class="card" style="overflow:auto">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap" style="padding: 12px 20px;">
                        <h4 class="mb-0">{{ __('New Withdrawal Requests') }} (USDT)</h4>
                        <span class="badge badge-primary">{{ count($data) }}</span>
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
                                                <th>{{ __('Gross') }}</th>
                                                <th>{{ __('Charges') }}</th>
                                                <th>{{ __('Net ') }}</th>
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
                                                    <td>{{ date('d-m-Y', strtotime($list['request_date'])) }}<br>{{ date('H:i:s', strtotime($list['request_date'])) }}
                                                    </td>
                                                    <td>{{ $list['request_id'] }}</td>
                                                    <td>{{ $list['memberid'] }}</td>
                                                    <td>{{ $list['type'] }}</td>
                                                    <td>{{ $list['wallet_address'] }}</td>
                                                    <td> $ {{ $list['gross_amount'] }}</td>
                                                    <td>$ {{ $list['service_charge'] }}</td>
                                                    <td>$ {{ $list['net_amount'] }}</td>
                                                    <td class="px-0">
                                                        <a href="{{ url('hdgteyusjasget/withdrawal/accept-online') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-success mx-0">Online Pay</a>
                                                    </td>
                                                    <td class="px-1">
                                                        <a href="{{ url('hdgteyusjasget/withdrawal/accept') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-primary mx-0">Accept</a>
                                                    </td>
                                                    <td class="px-0">
                                                        <a href="{{ url('hdgteyusjasget/withdrawal/cancel') }}/{{ $list['id'] }}"
                                                            class="btn btn-sm btn-danger mx-0">Cancel</a>
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
    </div>

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Adjust columns when switching tabs
                $('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
                    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                });
            });
        </script>
    @endpush
@endsection
