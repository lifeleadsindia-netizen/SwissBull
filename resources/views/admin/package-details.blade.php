@extends('admin.layouts.main')
@section('title', 'Package Details')
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
                            <h5>{{ __('Package Details')}}</h5>
                            <span>{{ __('All Package Details ')}}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Package Details')}}</li>
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
                    <div class="card-header"><h3>{{ __('Package Details')}}</h3></div>
                    <div class="card-body px-5" style="overflow: auto">
                        <table id="data_table" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('S.No') }}</th>
                                    <th>{{ __('Activation Date & Time') }}</th>
                                    <th>{{ __('Member ID') }}</th>
                                    <th>{{ __('Package') }}</th>
                                    <th>{{ __('Deposit') }}</th>
                                    <th>{{ __('Trading Wallet (70%)') }}</th>
                                    <th>{{ __('Return % / Max Limit') }}</th>
                                    <th>{{ __('Lock / Expiry') }}</th>
                                    <th>{{ __('Txn Hash / ID') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($data as $list)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>
                                            <strong>{{ $list['activated_at'] ? date('d-m-Y', strtotime($list['activated_at'])) : date('d-m-Y', strtotime($list['created_at'])) }}</strong><br>
                                            <small class="text-muted">{{ $list['activated_at'] ? date('h:i A', strtotime($list['activated_at'])) : date('h:i A', strtotime($list['created_at'])) }}</small>
                                        </td>
                                        <td><span class="badge badge-primary">{{ $list['memberid'] }}</span></td>
                                        <td>
                                            <strong>{{ $list['package_range'] ? $list['package_range'] . ' USDT' : ($list['package_type'] ?? 'Package') }}</strong>
                                        </td>
                                        <td><strong class="text-success">$ {{ number_format((float) ($list['invest_amount'] ?? $list['package_value']), 2) }}</strong></td>
                                        <td>
                                            <span class="badge badge-warning">$ {{ number_format((float) ($list['trading_wallet_amount'] ?: ($list['package_value'] * 0.70)), 2) }}</span>
                                        </td>
                                        <td>
                                            <small class="d-block text-muted">Return: <strong>{{ number_format((float) ($list['return_percent'] ?: 5.0), 2) }}%</strong></small>
                                            <small class="d-block text-muted">Max Limit: <strong>$ {{ number_format((float) ($list['max_earning'] ?: (($list['invest_amount'] ?: $list['package_value']) * 2.0)), 2) }} ({{ (int) ($list['max_return_percent'] ?: 200) }}%)</strong></small>
                                        </td>
                                        <td>
                                            @if ($list instanceof \App\Models\PackageDetail && $list->isLocked())
                                                <span class="badge badge-danger">Locked ({{ $list->remainingLockDays() }}d)</span>
                                            @elseif (!empty($list['locked_until']) && strtotime($list['locked_until']) > time())
                                                <span class="badge badge-danger">Locked</span>
                                            @else
                                                <span class="badge badge-success">Unlocked</span>
                                            @endif
                                            @if (!empty($list['expires_at']))
                                                <br><small class="text-muted">Exp: {{ date('d-m-Y', strtotime($list['expires_at'])) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-truncate d-inline-block" style="max-width: 130px;" title="{{ $list['txnid'] }}">{{ $list['txnid'] ?? '-' }}</small>
                                        </td>
                                        <td>
                                            @if ($list['status'] == 'Active' || $list['status'] == 'Accepted')
                                                <span class="badge badge-success">{{ $list['status'] }}</span>
                                            @elseif ($list['status'] == 'Expired')
                                                <span class="badge badge-danger">Expired</span>
                                            @else
                                                <span class="badge badge-secondary">{{ $list['status'] }}</span>
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

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
    @endpush
@endsection
