@extends('admin.layouts.main')
@section('title', 'Set Details')
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
                            <h5>{{ __('Set Details') }}</h5>
                            <span>{{ __('All Set Details') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Set Details') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-9">
                @if (session()->has('bonus_successMsg'))
                    <div class="alert alert-primary" role="alert">
                        {{ session('bonus_successMsg') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Bonus Amount') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setBonus') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>Minimum Deposit</label>
                                <input type="number" class="form-control" name="min_deposit"
                                    placeholder="Enter Minimum Deposit" value="{{ $data['min_deposit'] }}">
                                @error('min_deposit')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Bonus Rate </label>
                                <input type="number" class="form-control" name="bonus_rate" placeholder="Enter Bonus Rate "
                                    value="{{ $data['bonus_rate'] }}">
                                @error('bonus_rate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <button class="btn btn-primary float-right">Change Bonus</button>
                            </div>
                        </form>
                    </div>
                </div>
                @if (session()->has('res_successMsg'))
                    <div class="alert alert-primary" role="alert">
                        {{ session('res_successMsg') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Resources Data') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('setResources') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label>Minimum Loss Amount </label>
                                <input type="number" class="form-control" name="min_loss"
                                    placeholder="Enter Minimum Loss Amount" value="{{ $data['min_loss'] }}">
                                @error('min_loss')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Refund Rate </label>
                                <input type="text" class="form-control" name="refund_rate"
                                    placeholder="Enter Refund Rate" value="{{ $data['refund_rate'] }}">
                                @error('refund_rate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Daily Transfer Rate </label>
                                <input type="text" class="form-control" name="daily_rate"
                                    placeholder="Enter Daily Refund Rate" value="{{ $data['daily_rate'] }}">
                                @error('daily_rate')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <button class="btn btn-primary float-right">Change Details</button>
                            </div>
                        </form>
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
