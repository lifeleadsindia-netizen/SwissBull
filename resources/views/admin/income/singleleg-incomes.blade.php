@extends('admin.layouts.main')
@section('title', 'Single Leg Income')
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
                            <h5>{{ __('Single Leg Income') }}</h5>
                            <span>{{ __('Single Leg Income details') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Single Leg Income') }}</li>
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
                        <h3>{{ __('Single Leg Income') }}</h3>
                    </div>
                    <div class="card-body px-5 " style="overflow: auto">
                        <div class="responsive">
                            @php
                                $incomeList = isset($data) ? $data : \App\Models\SingleLegIncome::orderby('created_at', 'desc')->get();
                            @endphp
                            <table id="data_table" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Member ID') }}</th>
                                        <th>{{ __('Stage') }}</th>
                                        <th>{{ __('Required Team') }}</th>
                                        <th>{{ __('Self Staking') }}</th>
                                        <th>{{ __('Income Amount') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1;  @endphp
                                    @foreach ($incomeList as $list)
                                        @php
                                            $item = is_array($list) ? $list : (method_exists($list, 'getAttributes') ? $list->getAttributes() : (array) $list);
                                            $dateVal = $item['created_at'] ?? $list->created_at ?? $item['date'] ?? $list->date ?? date('Y-m-d');
                                            $memberidVal = $item['memberid'] ?? $list->memberid ?? '';
                                            $levelVal = $item['level'] ?? $list->level ?? '';
                                            $teamVal = $item['team'] ?? $list->team ?? '';
                                            $stakingVal = $item['staking'] ?? $list->staking ?? 0;
                                            $amountVal = $item['amount'] ?? $list->amount ?? 0;
                                            $statusVal = $item['status'] ?? $list->status ?? 'Paid';
                                        @endphp
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ date('d-m-Y', strtotime($dateVal)) }}</td>
                                            <td>{{ $memberidVal }}</td>
                                            <td>Stage {{ $levelVal }}</td>
                                            <td>{{ $teamVal }}</td>
                                            <td>$ {{ number_format($stakingVal, 2) }}</td>
                                            <td>$ {{ number_format($amountVal, 2) }}</td>
                                            <td>
                                                @if ($statusVal == 'Paid')
                                                    <label class="badge badge-success">{{ $statusVal }}</label>
                                                @else
                                                    <label class="badge badge-danger">{{ $statusVal }}</label>
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
