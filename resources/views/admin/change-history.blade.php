@extends('admin.layouts.main') 
@section('title', 'History Change')
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
                        <h5>{{ __('History Change')}}</h5>
                        <span>{{ __('History Change')}}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Chnage in Coin')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
                @include('admin.partials.history-date-filter')
                
            @if (session()->has('delMsg'))
            <div class="alert alert-primary" role="alert">
                {{session('delMsg')}}
            </div>
        @endif
        <div class="card">
            <div class="card-header"><h3>{{ __('History Change')}}</h3></div>
            <div class="card-body px-5">
                <table id="data_table" class="table">
                    <thead>
                        <tr>
                            <th>{{ __('S.No')}}</th>
                            <th>{{ __('Coin')}}</th>
                            <th>{{ __('Starttime')}}</th>
                            <th>{{ __('Minutes')}}</th>
                            <th>{{ __('Stoptime')}}</th>
                            <th>{{ __('Type')}}</th>
                            <th>{{ __('Price Change')}}</th>
                            <th class="text-center">{{ __('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1;  @endphp
                        @foreach ($data as $list )
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{ $list['coin']}}</td>
                                <td>{{ $list['starttime']}}</td>
                                <td>{{ $list['minutes']}}</td>
                                <td>{{ $list['stoptime']}}</td>
                                <td>{{ $list['type']}}</td>
                                <td>{{ $list['price_change']}}</td>
                                <td>
                                    <div class="text-center">
                                        <a class="btn btn-danger text-white" href="{{url('hdgteyusjasget/change-history/delete')}}/{{$list['id']}}">Delete</a>
                                    </div>
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

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/form-components.js') }}"></script>
    @endpush
@endsection