@extends('admin.layouts.main') 
@section('title', 'Rejected Fund Requests ')
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
                        <h5>{{ __('Rejected Fund Requests ')}}</h5>
                        <span>{{ __('All Rejected Fund Requests  ')}}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Rejected Fund Requests ')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            @if (session()->has('fundMsg'))
               <div class="alert alert-primary" role="alert">{{session('fundMsg')}}</div> 
            @endif
            <div class="card">
                <div class="card-header"><h3>{{ __('Rejected Fund Requests ')}}</h3></div>
                <div class="card-body px-5">
                   <div class="table-responsive"> 
                    <table id="data_table" class="table">
                        <thead>
                            <tr>
                                <th>{{ __('S.No')}}</th>
                                <th>{{ __('Date')}}</th>
                                <th>{{ __('Member Id')}}</th>
                                <th>{{ __('Amount')}}</th>
                                <th>{{ __('Transfer Id')}}</th>
                                <th>{{ __('Image')}}</th>
                                <th>{{ __('Status')}}</th>
                                <th>{{ __('View')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1;  @endphp
                            @foreach ($data as $list )
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{ date('d-m-Y', strtotime($list['updated_at']))}}<br>{{ date('H:i:s', strtotime($list['updated_at']))}}<br></td>
                                    <td>{{ $list['memberid']}}</td>
                                    <td>$ {{ $list['amount']}}</td>
                                    <td style="width:200px; display:block">{{ $list['tran_id']}}</td>
                                    <td>{{ $list['image']}}</td>
                                    <td>{{ $list['status']}}</td>
                                    <td >
                                        <button class="btn btn-sm btn-info packView" id="{{$list['id']}}" >View </button>
                                    </td>
                                </tr>
                                <tr style="display:none"  class="mySecs{{$list['id']}}">
                                    <td colspan="10">
                                        <img src="{{asset('uploads')}}/{{$list['image']}}" width="400">
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

<!-- push external js -->
@push('script')
<script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
<script>
    $(document).ready(function(){
       $('.packView').click(function(){
          var id = $(this).prop('id');
         $('.mySecs'+id).toggle();
       });
    })
</script>
@endpush
@endsection