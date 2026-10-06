@extends('admin.layouts.main')
@section('title', 'Account Statement')
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
                            <h5>{{ __('Member A/C Statement') }}</h5>
                            <span>{{ __('A/C Statement') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Admin') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('A/C Statement') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('admin.partials.history-date-filter')
                
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Members Details') }}</h3>
                    </div>
                    <div class="card-body px-5" style="overflow: auto">
                        @if (session()->has('successMsg'))
                            <div class="alert alert-danger alert-dismissible fade show col-10 d-block" role="alert">
                                {{ session('successMsg') }}
                            </div>
                        @endif
                        <table id="data_table" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('S.No') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Data1') }}</th>
                                    <th>{{ __('Data2') }}</th>
                                    <th>{{ __('Data3') }}</th>
                                    <th>{{ __('Data4') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            {{-- <tbody>
                                @php $i = 1;  @endphp
                                @foreach ($data as $list)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}<br>{{ date('h:i:s', strtotime($list['created_at'])) }}
                                        </td>
                                        <td>{{ $list['memberid'] }}</td>
                                        <td>{{ $list['type_id'] }}</td>
                                        <td>{{ $list['member_wallet'] }}</td>
                                        <td>{{ $list['rank'] }}</td>
                                        <td><span class="btn btn-primary">{{ $list['status'] }}</span></td>
                                        <td>
                                            <div class="table-actions text-center">
                                                <a href="{{ url('hdgteyusjasget/member-login') }}/{{ $list['id'] }}"
                                                    target="_blank" class="btn btn-success text-white"><i
                                                        class="ik ik-eye text-white"></i> View</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++;  @endphp
                                @endforeach
                            </tbody> --}}
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
