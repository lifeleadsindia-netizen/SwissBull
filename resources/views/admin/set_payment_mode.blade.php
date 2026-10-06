@extends('admin.layouts.main') 
@section('title', 'Set Payment Mode')
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
                            <h5>{{ __('Set Payment Mode')}}</h5>
                            <span>{{ __('Set Payment Mode ')}}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Set Payment Mode')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    
    
        <div class="row">
            <div class="col-md-5">
                @if (session()->has('payMsg'))
                   <div class="alert alert-primary">{{session('payMsg')}}</div> 
                @endif
                <div class="card">
                    <div class="card-header"><h3>{{ __('Set Payment Mode')}}</h3></div>
                     <div class="card-body">
                        <form action="{{route('paySubmit')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Payment Mode Name</label>
                                <input type="text" name="name" placeholder="Enter Name" class="form-control">
                                @error('name')
                                       <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" placeholder="Enter Payment Address" class="form-control">
                                @error('address')
                                       <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Bar Code Image</label>
                                <input type="file" name="barcode" class="file-upload-default">
                                <div class="input-group col-xs-12">
                                    <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                    <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                    </span>
                                </div>
                                @error('barcode')
                                       <span class="text-danger"> {{$message}}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <button class="btn btn-sm btn-primary float-right" >Submit Details</button>
                            </div>
                        </form>
                    </div> 
                </div>
            </div>
            <div class="col-md-7">
                @if (session()->has('wMessage'))
                   <div class="alert alert-primary">{{session('wMessage')}}</div> 
                @endif
                <div class="card">
                    <div class="card-header"><h3>{{ __('Paid Withdrawal Requests')}}</h3></div>
                    <div class="card-body px-3">
                        <table  class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('S.No')}}</th>
                                    <th>{{ __('Name')}}</th>
                                    <th>{{ __('Image')}}</th>
                                    <th >{{ __('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1;  @endphp
                                @foreach ($payData as $list )
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{ $list['name']}}</td>
                                        <td><img src="{{asset('uploads')}}/{{ $list['image']}}" width="100"> </td>
                                        <td>
                                        <a href="{{url('hdgteyusjasget/mode/delete')}}/{{$list['id']}}" class="btn btn-sm btn-primary" onclick="return confirm('Are you sure to delete data')"> Delete</a> </td>
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