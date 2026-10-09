@extends('admin.layouts.main')
@section('title', 'Trading Wallet Control')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-pocket bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Trading Wallet Control') }}</h5>
                            <span>{{ __('Configure trading wallet delivery locking days and system ON/OFF status') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ url('admin/dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">{{ __('Admin') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Trading Wallet Control') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session()->has('successMsg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ik ik-check-circle mr-2"></i> {{ session('successMsg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session()->has('failedMsg'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ik ik-alert-triangle mr-2"></i> {{ session('failedMsg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            {{-- Card 1 — 90-Day Delivery / Locking Code Configuration --}}
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 font-weight-bold text-dark">
                            <i class="ik ik-clock mr-2 text-primary"></i>{{ __('Trading Wallet Locking Period') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.applyTradingWalletControl') }}" method="POST" id="lockDaysForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="lock_days" class="font-weight-bold text-dark">
                                    {{ __('Locking Period ') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" min="0" step="1"
                                        class="form-control font-weight-bold @error('lock_days') is-invalid @enderror"
                                        id="lock_days"
                                        name="lock_days"
                                        value="{{ old('lock_days', $setting->lock_days ?? 90) }}"
                                        placeholder="90"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text font-weight-bold">{{ __('Days') }}</span>
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    {{ __('Enter the number of days during which the member trading wallet is locked (e.g. 90 Days).') }}
                                </small>
                                @error('lock_days')
                                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mt-4 pt-2 border-top text-right">
                                <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm" id="btnSaveLockDays">
                                    <i class="ik ik-save mr-2"></i>{{ __('Save / Update') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Card 2 — ON / OFF --}}
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 font-weight-bold text-dark">
                            <i class="ik ik-toggle-right mr-2 text-primary"></i>{{ __('ON / OFF') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.applyTradingWalletControl') }}" method="POST" id="statusControlForm">
                            @csrf
                            {{-- Current Status Display --}}
                            <div class="p-3 mb-4 rounded bg-light border d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Current Status') }}</span>
                                    <h4 class="mb-0 font-weight-bold {{ strtolower($currentStatus) === 'on' ? 'text-success' : 'text-danger' }}" id="statusDisplay">
                                        <i class="ik {{ strtolower($currentStatus) === 'on' ? 'ik-check-circle' : 'ik-x-circle' }} mr-1"></i>
                                        <span>{{ strtoupper($currentStatus) }}</span>
                                    </h4>
                                </div>
                                <span class="badge {{ strtolower($currentStatus) === 'on' ? 'badge-success' : 'badge-danger' }} px-3 py-2 font-weight-bold text-uppercase" style="font-size: 13px;" id="statusBadge">
                                    {{ strtoupper($currentStatus) }}
                                </span>
                            </div>

                            {{-- Actions: ON and OFF buttons --}}
                            <div class="d-flex flex-wrap align-items-center mb-3">
                                <button type="submit" name="status" value="on" class="btn btn-success font-weight-bold px-4 mr-2 mb-2 shadow-sm" id="btnStatusOn">
                                    <i class="ik ik-check-circle mr-2"></i>{{ __('ON') }}
                                </button>
                                <button type="submit" name="status" value="off" class="btn btn-danger font-weight-bold px-4 mb-2 shadow-sm" id="btnStatusOff">
                                    <i class="ik ik-x-circle mr-2"></i>{{ __('OFF') }}
                                </button>
                            </div>
                            <small class="text-muted d-block">
                                {{ __('Click ON to set status to on, or OFF to set status to off.') }}
                            </small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
