@extends('admin.layouts.main')
@section('title', 'Hero of the Month Rewards')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-award bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Hero of the Month') }}</h5>
                            <span>{{ __('2% Monthly pool distributions for highest direct business achievers') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Hero of the Month') }}</li>
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
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">{{ __('Hero of the Month Rewards History') }}</h3>
                        <span class="badge badge-warning text-dark font-weight-bold p-2" style="font-size: 13px;">
                            <i class="ik ik-star"></i> {{ __('2% Turnover Pool Award') }}
                        </span>
                    </div>
                    <div class="card-body px-5" style="overflow: auto">
                        <div class="responsive">
                            <table id="data_table" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Month / Year') }}</th>
                                        <th>{{ __('Member ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Direct Business') }}</th>
                                        <th>{{ __('Total Pool Turnover') }}</th>
                                        <th>{{ __('Pool Share') }}</th>
                                        <th>{{ __('Prize Amount') }}</th>
                                        <th>{{ __('Rank') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @foreach ($data as $list)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td><strong>{{ $list['month'] ?? $list['month_year'] ?? date('F Y', strtotime($list['created_at'])) }}</strong></td>
                                            <td><strong>{{ $list['memberid'] }}</strong></td>
                                            <td>{{ $list['member_name'] ?? getName($list['memberid']) }}</td>
                                            <td>$ {{ number_format((float) ($list['direct_business'] ?? 0), 2) }}</td>
                                            <td>$ {{ number_format((float) ($list['total_pool'] ?? $list['total_pool_business'] ?? 0), 2) }}</td>
                                            <td>{{ number_format((float) ($list['pool_percentage'] ?? $list['pool_rate'] ?? 2.0), 1) }}%</td>
                                            <td><strong class="text-success" style="font-size: 15px;">$ {{ number_format((float) ($list['prize_amount'] ?? 0), 2) }}</strong></td>
                                            <td><span class="badge badge-warning text-dark font-weight-bold">#{{ $list['rank'] ?? 1 }} Hero</span></td>
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
