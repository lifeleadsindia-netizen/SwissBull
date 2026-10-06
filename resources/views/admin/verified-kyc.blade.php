@extends('admin.layouts.main') 
@section('title', 'Verified KYCs')
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
                        <h5>{{ __('Verified KYCs')}}</h5>
                        <span>{{ __('Verified KYCs details')}}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Verified KYC ')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            @if (session()->has('kycMesg'))
               <div class="alert alert-primary">{{session('kycMesg')}}</div> 
            @endif
            <div class="card">
                <div class="card-header"><h3>{{ __('KYC Requests')}}</h3></div>
                <div class="card-body px-5">
                    <table id="data_table" class="table">
                        <thead>
                            <tr>
                                <th>{{ __('S.No')}}</th>
                                <th>{{ __('Avatar')}}</th>
                                <th>{{ __('Member Id')}}</th>
                                <th>{{ __('Name')}}</th>
                                <th>{{ __('Email')}}</th>
                                <th>{{ __('Mobile')}}</th>
                                <th>{{ __('Sponsor Id')}}</th>
                                <th>{{ __('Status')}}</th>
                                <th>{{ __('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1;  @endphp
                            @foreach ($data as $list )
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                    @if ($list['profile_image']=='')
                                       <img src="{{asset('uploads/avatar.jpg')}}" class="table-user-thumb" alt=""> 
                                    @else
                                       <img src="{{asset('uploads')}}/{{$list['profile_image']}}" class="table-user-thumb" alt="">
                                    @endif
                                    </td>
                                    <td>{{ $list['memberid']}}</td>
                                    <td>{{ $list['name']}}</td>
                                    <td>{{ $list['email']}}</td>
                                    <td>{{ $list['mobile']}}</td>
                                    <td>{{ $list['sponsorid']}}</td>
                                    <td>{{ $list['status']}}</td>
                                    <td>
                                        <div class="table-actions">
                                            
                                            <a href="{{url('hdgteyusjasget/KYC/view')}}/{{$list['id']}}"><i class="ik ik-eye"></i> View KYC</a>
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
    @endpush
@endsection