@extends('admin.layouts.main')
@section('title', 'Partnership Income')
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
                            <h5>{{ __('Partnership Income') }}</h5>
                            <span>{{ __('Partnership Income details') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Partnership Income') }}</li>
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
                    <div class="card-header">
                        <h3>{{ __('Partnership Income') }}</h3>
                    </div>
                    <div class="card-body px-5 " style="overflow: auto">
                        <div class="responsive">
                            @php
                                $partIncomes = \App\Models\PartnershipIncome::orderby('created_at', 'desc')->get();
                                $incomeList = $partIncomes->isNotEmpty() ? $partIncomes : ((isset($data) && count($data) > 0) ? $data : []);
                            @endphp
                            <table id="data_table" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Member ID') }}</th>
                                        <th>{{ __('Rank') }}</th>
                                        <th>{{ __('Total Investment') }}</th>
                                        <th>{{ __('Rate') }}</th>
                                        <th>{{ __('Daily Team Biz') }}</th>
                                        <th>{{ __('Installment') }}</th>
                                        <th>{{ __('Achieved') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Status') }}</th>
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
                                            <td>$ {{ $item['daily_team_biz'] ?? 0 }}</td>
                                            <td>{{ $item['installment'] ?? 1 }}</td>
                                            <td>$ {{ number_format($item['achieved'] ?? 0, 2) }}</td>
                                            <td>$ {{ number_format($item['amount'] ?? 0, 2) }}</td>
                                            <td>
                                                @if (($item['status'] ?? '') == 'Paid')
                                                    <label class="badge badge-success">{{ $item['status'] }}</label>
                                                @else
                                                    <label class="badge badge-danger">{{ $item['status'] ?? 'Unpaid' }}</label>
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

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
    @endpush
@endsection
