@extends('admin.layouts.main')
@section('title', 'Member Account Security')
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
                            <h5>{{ __('Member Account Security') }}</h5>
                            <span>{{ __('All Member Account Security') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Member Account Security') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                @include('admin.partials.history-date-filter')
                
                @if (session()->has('succMsg'))
                    <div class="alert alert-success" role="alert">{{ session('succMsg') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Data Table') }}</h3>
                    </div>
                    <div class="card-body px-5" style="overflow: auto">
                        <div class="responsive">
                            <table id="data_table" class="table text-center">
                                <thead>
                                    <tr>
                                        <th>{{ __('S.No') }}</th>
                                        <th class="col-1">{{ __('Avatar') }}</th>
                                        <th class="col-1">{{ __('MemberId') }}</th>
                                        <th class="text-center col-2">{{ __('Password') }}</th>
                                        <th class="text-center col-2">{{ __('Txn Password') }}</th>
                                        <th class="text-center">{{ __('Member Wallet') }}</th>
                                        <th class="text-center col-1">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1;  @endphp
                                    @foreach ($data as $list)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>
                                                @if ($list['profile_image'] == '')
                                                    <img src="{{ asset('uploads/avatar.jpg') }}" class="table-user-thumb"
                                                        alt="">
                                                @else
                                                    <img src="{{ asset('uploads') }}/{{ $list['profile_image'] }}"
                                                        class="table-user-thumb" alt="">
                                                @endif
                                            </td>
                                            <td>{{ $list['memberid'] }}</td>
                                            <form action="{{ route('securityUpdate') }}" method="post">
                                                @csrf
                                                <td><input class="form-control" type="text" name="password"
                                                        placeholder="Enter Password"></td>
                                                <td><input class="form-control" type="text" name="txnpassword"
                                                        placeholder="Enter Transaction Password">
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" name="member_wallet"
                                                        placeholder="Enter Member Wallet"
                                                        value="{{ $list['member_wallet'] }}">
                                                </td>
                                                <td>
                                                    <input type="hidden" name="id" value="{{ $list['id'] }}">
                                                    <button class="btn btn-primary">Update</button>
                                                </td>
                                            </form>
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
