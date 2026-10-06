@extends('admin.layouts.main') 
@section('title', 'Double Member Details')
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
                        <h5>{{ __('Double Member Details')}}</h5>
                        <span>{{ __('All Double Member Details')}}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Double Member Details')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card" style="zoom:90%">
                <div class="card-header"><h3>{{ __('Data Table')}}</h3></div>
                <div class="card-body px-5">
                    <div class="responsive">
                    <table id="data_table" class="table">
                        <thead>
                            <tr>
                                <th>{{ __('S.No')}}</th>
                                <th>{{ __('Date')}}</th>
                                <th>{{ __('MemberId')}}</th>
                                <th>{{ __('Double Id')}}</th>
                                <th>{{ __('Double SponsorId')}}</th>
                                <th>{{ __('Name')}}</th>
                                <th>{{ __('Team')}}</th>
                                <th>{{ __('Downline')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1;  @endphp
                            @foreach ($doubleData as $list )
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{date('d-m-Y', strtotime($list['created_at']))}}<br>{{date('h:i:s', strtotime($list['created_at']))}}</td>
                                    <td>{{ $list['memberid']}}</td>
                                    <td>{{ $list['doubleid']}}</td>
                                    <td>{{ $list['dbsponsorid']}}</td>
                                    <td>{{ $list['name']}}</td>
                                    <td>{{ $list['team']}}</td>
                                    <td>{{ $list['downline']}}</td>
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