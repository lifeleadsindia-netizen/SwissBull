@extends('admin.layouts.main')
@section('title', 'Referral Bonus')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user-check bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Referral Bonus') }}</h5>
                            <span>{{ __('Referral Bonus (Direct Income) distribution records') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Referral Bonus') }}</li>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">{{ __('Referral Bonus History') }}</h3>
                        <a href="{{ route('admin.referralBonus') }}" class="btn btn-sm btn-primary">
                            <i class="ik ik-settings"></i> {{ __('Configure Rates (L1-L3)') }}
                        </a>
                    </div>
                    <div class="card-body px-5" style="overflow: auto">
                        <div class="responsive">
                            <table id="data_table" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Member ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Level') }}</th>
                                        <th>{{ __('From Member') }}</th>
                                        <th>{{ __('Package Amount') }}</th>
                                        <th>{{ __('Rate') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($data as $list)
                                        @php
                                            $pkg = (float) ($list['package'] ?? 0);
                                            $amt = (float) ($list['amount'] ?? 0);
                                            $storedRate = isset($list['rate']) && $list['rate'] !== null && (float) $list['rate'] > 0 ? (float) $list['rate'] : null;
                                            $rate = $storedRate ?? ($pkg > 0 ? round(($amt / $pkg) * 100, 1) : 0);
                                            $levelNum = $list['level'] ?? 1;
                                        @endphp
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                                            <td><strong>{{ $list['memberid'] }}</strong></td>
                                            <td>{{ getName($list['memberid']) }}</td>
                                            <td><span class="badge badge-primary">Level {{ $levelNum }}</span></td>
                                            <td>{{ $list['activatingid'] ?? 'N/A' }} ({{ $list['name'] ?? '' }})</td>
                                            <td>$ {{ number_format($pkg, 2) }}</td>
                                            <td>{{ $rate > 0 ? $rate.'%' : 'Standard' }}</td>
                                            <td><strong class="text-success">$ {{ number_format($amt, 2) }}</strong></td>
                                            <td><span class="badge badge-info">{{ $list['type'] ?? 'Referral Bonus' }}</span></td>
                                            <td>
                                                @if ($list['status'] == 'Paid')
                                                    <label class="badge badge-success">{{ $list['status'] }}</label>
                                                @else
                                                    <label class="badge badge-danger">{{ $list['status'] }}</label>
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

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
    @endpush
@endsection
