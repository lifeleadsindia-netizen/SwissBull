@extends('admin.layouts.main')
@section('title', 'Member Details')
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
                            <h5>{{ __('Member Details') }}</h5>
                            <span>{{ __('All Member Details') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Member Details') }}</li>
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
                                    <th>{{ __('Joining') }}</th>
                                    <th>{{ __('Avatar') }}</th>
                                    <th>{{ __('Member Id') }}</th>
                                    <th>{{ __('Member Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Mobile No.') }}</th>
                                    <th>{{ __('Country') }}</th>
                                    <th>{{ __('Member Wallet') }}</th>
                                    <th>{{ __('Rank') }}</th>
                                    <th>{{ __('Sponsor Id') }}</th>
                                    <th>{{ __('Downline') }}</th>
                                    <th>{{ __('Package') }}</th>
                                    <th>{{ __('Team Biz') }}</th>
                                    <th>{{ __('Self Biz') }}</th>
                                    <th>{{ __('Fund Wallet') }}</th>
                                    <th>{{ __('Income Wallet') }}</th>
                                    <th>{{ __('PEPE Token') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @php $symbol = $fiat['country'] ?? ''; @endphp --}}
                                @php $i = 1;  @endphp
                                @foreach ($data as $list)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}<br>{{ date('h:i:s', strtotime($list['created_at'])) }}
                                        </td>
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
                                        <td>{{ $list['name'] }}</td>
                                        <td>{{ $list['email'] }}</td>
                                        <td>
                                          <span>({{ $list['phonecode'] }}) </span><span class="ms-1">{{ $list['mobile'] }}</span></td>
                                        <td>{{ $list['country'] }}</td>
                                        <td>{{ $list['member_wallet'] }}</td>
                                        <td>{{ $list['rank'] }}</td>
                                        <td>{{ $list['sponsorid'] }}</td>
                                        <td>{{ $list['downline'] }}</td>
                                        <td>${{ $list['package'] }}</td>
                                        <td>${{ $list['team_biz'] }}</td>
                                        <td>${{ $list['self_biz'] }}</td>
                                        <td><span class="badge bg-primary rounded-pill px-2 py-1 text-white">
                                            ${{ $list['p2p_wallet'] }}</span>
                                        </td>
                                        <td><span class="badge bg-primary rounded-pill px-2 py-1 text-white">
                                                ${{ $list['wallet'] }}</span>
                                        </td>

                                        <td><span class="btn btn-{{ $list['pepe_wallet'] == 0 ? 'danger' : 'success' }}">{{ $list['pepe_wallet'] }}</span></td>
                                        <td><span class="btn btn-{{ $list['status'] == 'Active' ? 'success' : 'danger' }}">{{ $list['status'] == 'Active' ? 'Active' : ($list['status'] == 'Temp' ? 'Inactive' : $list['status']) }}</span></td>
                                        <td>
                                            <a href="{{ url('hdgteyusjasget/member-login') }}/{{ $list['id'] }}"
                                                target="_blank" class="btn btn-success text-white"><i
                                                    class="ik ik-eye text-white"></i> View</a>
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

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
    @endpush
@endsection
