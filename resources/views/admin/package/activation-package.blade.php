@extends('admin.layouts.main') 
@section('title', 'Activation Packages Details')
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
                        <h5>{{ __('Activation Packages')}}</h5>
                        <span>{{ __('All Activation Packages ')}}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Activation Packages')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            @if (session()->has('wMessage'))
               <div class="alert alert-primary">{{session('wMessage')}}</div> 
            @endif
            <div class="card">
                <div class="card-header"><h3>{{ __('Activation Packages')}}</h3></div>
                <div class="card-body px-5">
                    <table id="data_table" class="table">
                        <thead>
                            <tr>
                                <th>{{ __('S.No')}}</th>
                                <th>{{ __('Date')}}</th>
                                <th>{{ __('Order Id')}}</th>
                                <th>{{ __('Member Id')}}</th>
                                <th>{{ __('Type')}}</th>
                                <th>{{ __('Value')}}</th>
                                <th>{{ __('TxnId')}}</th>
                                <th>{{ __('Mode')}}</th>
                                <th>{{ __('Status')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1;  @endphp
                            @foreach ($packdata as $list )
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{ $list['txn_date']}}</td>
                                    <td>{{ $list['order_id']}}</td>
                                    <td>{{ $list['memberid']}}</td>
                                    <td>{{ $list['package_type']}}</td>
                                    <td>$ {{ $list['package_value']}}</td>
                                    <td>{{ $list['txnid']}}</td>
                                    <td>{{ $list['payment_mode']}}</td>
                                    <td><a href="javascript:;" class="btn btn-sm btn-primary">{{ $list['status']}}</a></td>
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

<!-- push external js -->
@push('script')
<script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
@endpush
@endsection