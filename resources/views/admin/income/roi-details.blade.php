@extends('admin.layouts.main')
@section('title', 'Roi Details')
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
                        <h5>{{ __('Roi Details') }}</h5>
                        <span>{{ __('Roi Details details') }}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Roi Details') }}</li>
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
                    <div class="card-header">
                        <h3>{{ __('Roi Income') }}</h3>
                    </div>
                    <div class="card-body px-5 " style="overflow: auto">
                        <div class="responsive">
                            <table id="data_table" class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th>{{ __('Invest Date') }}</th>
                                        <th>{{ __('Member ID') }}</th>
                                        <th>{{ __('Package Range') }}</th>
                                        <th>{{ __('Invest Amount') }}</th>
                                        <th>{{ __('Txnid') }}</th>
                                        <th>{{ __('Installments') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1;  @endphp
                                    @foreach ($data as $list)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                                            <td>{{ $list['memberid'] }}</td>
                                            <td>
                                                @if ($list->package == 'Package1')
                                                    <span>$50 - $500</span>
                                                @elseif ($list->package == 'Package2')
                                                    <span>$600 - $5000</span>
                                                @else
                                                    <span>$6000</span>
                                                @endif
                                            </td>
                                            <td>$ {{ number_format($list['invest_amount'], 2) }}</td>
                                            <td>{{ $list['txnid'] }}</td>
                                            <td>{{ $list['installments'] }}</td>
                                            <td>
                                                @if ($list['status'] == 'Active')
                                                    <label class="badge badge-success">{{ $list['status'] }}</label>
                                                @else
                                                    <label class="badge badge-danger">{{ $list['status'] }}</label>
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
</div>

@push('script')
<script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
@endpush
@endsection