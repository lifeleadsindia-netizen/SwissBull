@extends('admin.layouts.main') 
@section('title', 'Package Activation')
@section('content')
    <!-- push external head elements to head -->
    @push('head')
       <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
    @endpush
   

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-inbox bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Package Activation')}}</h5>
                            <span>{{ __('list of all active users')}}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">Users</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Package Activation</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                @include('admin.partials.history-date-filter')
                
                @if (session()->has('activate'))
                    <div class="alert alert-success">{{session('activate')}}</div>
                @endif
                <div class="card px-2">
                    <div class="card-header"><h3>{{ __('Activate User Packages')}}</h3></div>
                    <div class="card-body" style="overflow: auto">
                        <table id="data_table" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Id')}}</th>
                                    <th class="nosort">{{ __('Avatar')}}</th>
                                    <th>{{ __('Member Id')}}</th>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Email')}}</th>
                                    <th>{{ __('Mobile')}}</th>
                                    <th class="nosort">{{ __('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($data as $list )
                                    <tr>
                                        <td>{{ __($i++)}}</td>
                                        <td><img src="{{asset('adm_assets/assets/img/users/1.jpg')}}" class="table-user-thumb" alt=""></td>
                                        <td>{{ __($list->memberid)}}</td>
                                        <td>{{ __($list->name)}}</td>
                                        <td>{{ __($list->email)}}</td>
                                        <td>{{ __($list->mobile)}}</td>
                                        <td><a href="{{url('hdgteyusjasget/activate-package')}}/{{($list->id)}}" class="btn btn-sm btn-success">Activate Package</a>
                                        </td>
                                    </tr>
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