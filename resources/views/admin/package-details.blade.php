@extends('admin.layouts.main')
@section('title', 'Package Details')
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
                            <h5>{{ __('Package Details')}}</h5>
                            <span>{{ __('All Package Details ')}}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Package Details')}}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                @include('admin.partials.history-date-filter')
                
                @if (session()->has('wMessage'))
                   <div class="alert alert-primary">{{session('wMessage')}}</div>
                @endif
                <div class="card">
                    <div class="card-header"><h3>{{ __('Package Details')}}</h3></div>
                    <div class="card-body px-5" style="overflow: auto">
                        <table id="data_table" class="table">
                            <thead>
                                  <tr>
                                    <th>{{ __('S.No')}}</th>
                                    <th>{{ __('Date')}}</th>
                                    <th>{{ __('Member Id')}}</th>
                                    <th>{{ __('Package Type')}}</th>
                                    <th>{{ __('Txn Id')}}</th>
                                    <th>{{ __('Value')}}</th>
                                    <th>{{ __('Status ')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1;  @endphp
                                @foreach ($data as $list )
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{date('d-m-Y', strtotime($list['created_at']))}}</td>
                                        <td>{{ $list['memberid']}}</td>
                                        <td>{{ $list['package_type']}}</td>
                                        <td>{{ $list['txnid']}}</td>
                                        <td>$ {{ $list['package_value']}}</td>
                                        <td><span class="btn btn-success">{{ $list['status']}}</b></td>
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
