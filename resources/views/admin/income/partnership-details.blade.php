@extends('admin.layouts.main')
@section('title', 'Partnership Details')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-briefcase bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Partnership Details') }}</h5>
                            <span>{{ __('Partnership Investment details') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Partnership Details') }}</li>
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
                        <h3>{{ __('Partnership Investment Details') }}</h3>
                    </div>
                    <div class="card-body px-5 " style="overflow: auto">
                        <div class="responsive">
                            <table id="data_table" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Invest Date') }}</th>
                                        <th>{{ __('Member ID') }}</th>
                                        <th>{{ __('Rank') }}</th>
                                        <th>{{ __('Invest Amount') }}</th>
                                        <th>{{ __('capping') }}</th>
                                        <th>{{ __('Rate') }}</th>
                                        <th>{{ __('Achieved') }}</th>
                                        <th>{{ __('Installments') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1;  @endphp
                                    @foreach ($data as $list)
                                        @php
                                            $item = is_array($list) ? $list : (method_exists($list, 'getAttributes') ? $list->getAttributes() : (array) $list);
                                            $investDate = $item['invest_date'] ?? $list->invest_date ?? $item['created_at'] ?? $list->created_at ?? date('Y-m-d');
                                            $memberid = $item['memberid'] ?? $list->memberid ?? '';
                                            $rank = $item['rank'] ?? $list->rank ?? '';
                                            $investAmount = $item['invest_amount'] ?? $list->invest_amount ?? 0;
                                            $rate = $item['rate'] ?? $list->rate ?? 0;
                                            $capping = $item['capping'] ?? $list->capping ?? 0;
                                            $installments = $item['installments'] ?? $list->installments ?? 0;
                                            $status = $item['status'] ?? $list->status ?? 'Active';
                                            $achieved = $item['achieved'] ?? $list->achieved ?? 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ date('d-m-Y', strtotime($investDate)) }}</td>
                                            <td>{{ $memberid }}</td>
                                            <td>{{ $rank }}</td>
                                            <td>$ {{ number_format($investAmount, 2) }}</td>
                                            <td>$ {{ $capping }}</td>
                                            <td>{{ $rate }}%</td>
                                            <td>{{ $installments }}</td>
                                            <td>$ {{ number_format($achieved, 2) }}</td>
                                            <td>
                                                @if ($status == 'Active')
                                                    <label class="badge badge-success">{{ $status }}</label>
                                                @else
                                                    <label class="badge badge-danger">{{ $status }}</label>
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
