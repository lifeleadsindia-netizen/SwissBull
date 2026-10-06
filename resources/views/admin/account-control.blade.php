@extends('admin.layouts.main') 
@section('title', 'Account Control')
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
                        <h5>{{ __('Member Details')}}</h5>
                        <span>{{ __('All Member Details')}}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Member Details')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
                @include('admin.partials.history-date-filter')
                
            @if (session()->has('statusDeact'))
                <div class="alert alert-danger" role="alert">
                    {{session('statusDeact')}}
                </div>
            @endif
            @if (session()->has('statusAct'))
                <div class="alert alert-primary" role="alert">
                    {{session('statusAct')}}
                </div>
            @endif
            <div class="card">
                <div class="card-header"><h3>{{ __('Members Details')}}</h3></div>
                <div class="card-body px-5" style="overflow: auto">
                    <table id="data_table" class="table">
                        <thead>
                            <tr>
                                <th>{{ __('S.No')}}</th>
                                <th>{{ __('Joining')}}</th>
                                <th>{{ __('Avatar')}}</th>
                                <th>{{ __('Member Id')}}</th>
                                <th>{{ __('Name')}}</th>
                                <th>{{ __('Email')}}</th>
                                <th>{{ __('Mobile')}}</th>
                                <th>{{ __('Sponsor Id')}}</th>
                                <th>{{ __('Change Status')}}</th>
                                <th>{{ __('Action')}}</th>
                                <!--<th>{{ __('A/C Statement')}}</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1;  @endphp
                            @foreach ($data as $list )
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{date('d-m-Y', strtotime($list['created_at']))}}<br>{{date('h:i:s', strtotime($list['created_at']))}}</td>
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
                                    <td>({{ $list['phonecode'] }}) {{ $list['mobile']}}</td>
                                    <td>{{ $list['sponsorid']}}</td>
                                    <td>
                                        @if ($list['status'] == 'Active')
                                            <a href="{{url('hdgteyusjasget/account-control')}}/{{$list['id']}}"><button class="btn btn-primary">{{$list['status']}}</button></a>
                                        @endif
                                        @if ($list['status'] == 'Deactive')
                                            <a href="{{url('hdgteyusjasget/account-control')}}/{{$list['id']}}"><button class="btn btn-danger">{{$list['status']}}</button></a>
                                        @endif
                                    </td>
                                    <td><a href="{{url('hdgteyusjasget/members/member-update')}}/{{$list['id']}}" class="btn btn-primary"><i class="ik ik-edit"></i> Edit</a></td>
                                    <!--<td><a href="{{url('hdgteyusjasget/account-statement')}}" class="btn btn-primary"><i class="ik ik-info"></i> Info</a></td>-->
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