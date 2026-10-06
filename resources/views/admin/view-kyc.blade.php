@extends('admin.layouts.main') 
@section('title', 'View KYC Deatils')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('KYC Details')}}</h5>
                            <span>{{ __('User KYC and Kyc updates')}}</span>
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
                                <a href="#">{{ __('Dashboard')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('KYC Details')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-lg-12 col-md-12">
                @if (session()->has('update'))
                    <div class="alert alert-success" role="alert">
                        {{session('update')}}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h5>KYC Details</h5>
                        @if ($data['kyc_status']=='Verified')
                            
                        @else
                        <a href="{{url('hdgteyusjasget/kyc/verify')}}/{{$data['id']}}" class="btn btn-sm btn-primary ml-auto mr-3">Verify KYC</a>
                        <a href="{{url('hdgteyusjasget/kyc/reject')}}/{{$data['id']}}" class="btn btn-sm btn-danger  mr-0">Reject KYC</a>
                        @endif
                        
                    </div>
                  <div class="row">
                    <div class="col-lg-12">
                        <div class="card-body"> 
                            <form action="{{route('updateKYC')}}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Bank </label>
                                            <input type="text" class="form-control" name="bank" value="{{$data['bank']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Account No </label>
                                            <input type="text" class="form-control" name="account_no" value="{{$data['account_no']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Accout Holder </label>
                                            <input type="text" class="form-control" name="holder" value="{{$data['holder']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>IFSC Code </label>
                                            <input type="text" class="form-control" name="ifsc" value="{{$data['ifsc']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>UPI ID </label>
                                            <input type="text" class="form-control" name="upi_id" value="{{$data['upi_id']}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>ID No </label>
                                            <input type="text" class="form-control" name="id_no" value="{{$data['id_no']}}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    
                                    <div class="col-md-4">
                                        <input type="hidden" class="form-control" name="id" value="{{$data['id']}}"> 
                                      <button class="btn btn-primary" style="margin-left: 365px">Update Details</button>
                                    </div>
                                </div>
                                <hr>
                            </form>
                            
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="row justify-content-around">
                            <div class="col-md-4 col-sm-12">
                              <div class="card shadow">
                                <div class="card-body shadow">
                                    <div class="text-center"> 
                                        @if ($data['selfie_image'] != '')
                                           <img src="{{asset('uploads')}}/{{$data['selfie_image']}}" class="rounded"   style="width: 100%; height:180px"/>
                                        @else
                                           <img src="{{asset('uploads/image_place.jpg')}}" class="rounded" style="width: 100%; height:180px"  />
                                        @endif
                                    </div>
                                    <h4 class="card-title mt-10">{{ __('Selfie Image')}}</h4>
                                    <form action="{{route('updateAback')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <input type="file" name="selfie_image" class="file-upload-default" required>
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Image">
                                                <span class="input-group-append">
                                                <button class="file-upload-browse btn btn-primary" type="button">{{ __('Upload')}}</button>
                                                </span>
                                            </div>
                                            @error('selfie_image')
                                                   <span class="text-danger"> {{$message}}</span>
                                            @enderror
                                        </div>
                                        <input type="hidden" name="id" value="{{$data['id']}}">
                                        <input type="hidden" name="oldImage" value="{{$data['selfie_image']}}">
                                        <button class="btn btn-sm btn-primary float-right">Update Image</button>
                                    </form>
                                </div>
                              </div>  
                            </div>
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
        <script src="{{ asset('adm_assets/assets/js/form-components.js') }}"></script>
    @endpush
@endsection