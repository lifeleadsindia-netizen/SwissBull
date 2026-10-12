@extends('admin.layouts.main') 
@section('title', 'Achievers Details')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
        <style>
            .achievers-table-card .card-body {
                padding: 1.25rem;
            }
            .achievers-table-card .dataTables_wrapper > .row:first-child {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                margin-left: 0;
                margin-right: 0;
                margin-bottom: 12px;
                gap: 8px;
            }
            .achievers-table-card .dataTables_wrapper > .row:first-child > .col-sm-2 {
                flex: 0 0 auto;
                max-width: none;
                width: auto;
                padding: 0;
            }
            .achievers-table-card .dataTables_wrapper > .row:first-child > .col-sm-7 {
                flex: 1 1 auto;
                max-width: none;
                width: auto;
                padding: 0 6px;
                text-align: center;
            }
            .achievers-table-card .dataTables_wrapper > .row:first-child > .col-sm-3 {
                flex: 0 0 auto;
                max-width: none;
                width: auto;
                padding: 0;
                margin-left: auto;
                text-align: right;
            }
            .achievers-table-card .dataTables_wrapper .dataTables_length label {
                display: inline-flex;
                align-items: center;
                white-space: nowrap;
                margin-bottom: 0;
                font-weight: 500;
                font-size: 13px;
            }
            .achievers-table-card .dataTables_wrapper .dataTables_length select {
                display: inline-block;
                width: auto;
                margin: 0 6px;
                height: 31px;
                padding: 2px 8px;
                border-radius: 4px;
                border: 1px solid #ced4da;
            }
            .achievers-table-card .dataTables_wrapper .dt-buttons {
                display: inline-flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 4px;
            }
            .achievers-table-card .dataTables_wrapper .dt-buttons .btn {
                border-radius: 4px;
                padding: 4px 10px;
                font-size: 12px;
            }
            .achievers-table-card .dataTables_wrapper .dataTables_filter {
                text-align: right;
                display: inline-block;
            }
            .achievers-table-card .dataTables_wrapper .dataTables_filter label {
                display: inline-flex;
                align-items: center;
                white-space: nowrap;
                margin-bottom: 0;
                font-weight: 500;
                font-size: 13px;
            }
            .achievers-table-card .dataTables_wrapper .dataTables_filter input {
                display: inline-block;
                width: 140px;
                max-width: 100%;
                margin-left: 6px !important;
                height: 31px;
                padding: 4px 8px;
                border-radius: 4px;
                border: 1px solid #ced4da;
            }
            .achievers-table-card .table-responsive {
                margin-bottom: 0;
            }
            @media (max-width: 1199px) {
                .achievers-table-card .dataTables_wrapper > .row:first-child > .col-sm-7 {
                    order: 3;
                    flex: 1 1 100%;
                    width: 100%;
                    margin-top: 8px;
                    text-align: center;
                }
                .achievers-table-card .dataTables_wrapper > .row:first-child > .col-sm-2 {
                    order: 1;
                }
                .achievers-table-card .dataTables_wrapper > .row:first-child > .col-sm-3 {
                    order: 2;
                }
            }
            @media (max-width: 575px) {
                .achievers-table-card .dataTables_wrapper > .row:first-child > .col-sm-2,
                .achievers-table-card .dataTables_wrapper > .row:first-child > .col-sm-3 {
                    flex: 1 1 100%;
                    width: 100%;
                    text-align: left;
                }
                .achievers-table-card .dataTables_wrapper .dataTables_filter {
                    text-align: left;
                    margin-top: 6px;
                }
                .achievers-table-card .dataTables_wrapper .dataTables_filter input {
                    width: 100%;
                    margin-left: 0 !important;
                    margin-top: 4px;
                }
            }
        </style>
    @endpush
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-edit bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ __('Achievers Details')}}</h5>
                        <span>{{ __('All Achievers Details')}}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Achievers Details')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-5">
            @if (session()->has('uploadMsg'))
                <div class="alert alert-primary" role="alert">
                    {{session('uploadMsg')}}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('Achievers Image Table')}}</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('acheivImage')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Rank </label>
                            <input type="number" class="form-control" name="rank" placeholder="Enter Image Rank">
                            @error('rank')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Title </label>
                            <input type="text" class="form-control" name="title" placeholder="Enter Title">
                            @error('title')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Image </label>
                            <input type="file" name="image" class="file-upload-default" >
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                </span>
                            </div>
                            @error('image')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div>
                            <button class="btn btn-primary float-right">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            @if (session()->has('delMsg'))
                <div class="alert alert-primary" role="alert">
                    {{session('delMsg')}}
                </div>
            @endif
            <div class="card achievers-table-card">
                <div class="card-header"><h3>{{ __('Image Table')}}</h3></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data_table" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('S.No')}}</th>
                                    <th>{{ __('Title')}}</th>
                                    <th>{{ __('Rank')}}</th>
                                    <th>{{ __('Image')}}</th>
                                    <th class="text-center">{{ __('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1;  @endphp
                                @foreach ($data as $list )
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{ $list['title']}}</td>
                                        <td>{{ $list['rank'] ?? ''}}</td>
                                        <td><img src="{{asset('uploads')}}/{{$list['image']}}" class="" alt="" width="50" height="60">
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <a class="btn btn-danger text-white" href="{{url('admin/achv-image/delete')}}/{{$list['id']}}">Delete</a>
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
</div>

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/form-components.js') }}"></script>
    @endpush
@endsection