@extends('admin.layouts.main') 
@section('title', 'Account Activation')
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
                        <h5>{{ __('Account Activation')}}</h5>
                        <span>{{ __('All Account Activation ')}}</span>
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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Account Activation')}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            @if (session()->has('actMsg'))
               <div class="alert alert-primary" role="alert">{{session('actMsg')}}</div> 
            @endif
            <div class="card">
                <div class="card-header"><h3>{{ __('Fund Requests')}}</h3></div>
                <div class="card-body px-5">
                    <table id="data_table" class="table">
                        <thead>
                            <tr>
                                <th>{{ __('S.No')}}</th>
                                <th>{{ __('Date')}}</th>
                                <th>{{ __('Member Id')}}</th>
                                <th>{{ __('Amount')}}</th>
                                <th>{{ __('Transfer Id')}}</th>
                                <th>{{ __('Status')}}</th>
                                <th>{{ __('View')}}</th>
                                <th colspan="2" class="text-center">{{ __('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1;  @endphp
                            @foreach ($data as $list )
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{ date('d-m-Y', strtotime($list['date']))}}</td>
                                    <td>{{ $list['memberid']}}</td>
                                    <td>$ {{ $list['amount']}}</td>
                                    <td>{{ $list['txnid']}}</td>
                                    <td>{{ $list['status']}}</td>
                                    <td class="img">
                                        <button class="btn btn-sm btn-info packView" id="{{$list['id']}}" >View </button></td>
                                    <td><a href="{{url('ncaweuifuiscjdiewq/accept-request')}}/{{$list['id']}}" class="btn btn-sm btn-primary">Accept</a></td>
                                </tr>
                                <tr style="display:none"  class="mySec{{$list['id']}}">
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

<!-- push external js -->
@push('script')
<script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
<script>
    $(document).ready(function(){
       $('.packView').click(function(){
          var id = $(this).prop('id');
         $('.mySec'+id).toggle();
       });
    })
</script>
@endpush
@endsection