@extends('admin.layouts.main')
@section('title', 'Set Packages')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-sliders bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Set Packages') }}</h5>
                            <span>{{ __('Configure package distribution, trading wallet control, and monthly trading profit') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Set Packages') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @if (session()->has('successMsg'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="ik ik-check-circle mr-1"></i></strong> {{ session('successMsg') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                @if (session()->has('failedMsg'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="ik ik-alert-triangle mr-1"></i></strong> {{ session('failedMsg') }}
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
            </div>

            <div class="col-12">
                <form action="{{ route('admin.savePackages') }}" method="POST" id="formSetPackages">
                    @csrf
                    <div class="row">
                        {{-- Card 1: Package Distribution & Allocation --}}
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                    <h3 class="mb-0 text-black font-weight-bold">
                                        <i class="ik ik-sliders mr-2"></i>{{ __('Package Distribution') }}
                                    </h3>
                                    <span class="badge badge-light text-primary font-weight-bold">{{ __('Allocation') }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="trading_wallet" class="font-weight-bold text-dark">{{ __('Trading Wallet Allocation') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" step="any" min="0" max="100"
                                                class="form-control font-weight-bold @error('trading_wallet') is-invalid @enderror"
                                                id="trading_wallet" name="trading_wallet"
                                                value="{{ old('trading_wallet', optional($distribution)->trading_wallet !== null ? number_format((float) $distribution->trading_wallet, 2, '.', '') : '70.00') }}"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">%</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">{{ __('Allocated directly to member Trading Wallet on package purchase.') }}</small>
                                        @error('trading_wallet')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-0">
                                        <label for="hero_of_the_month" class="font-weight-bold text-dark">{{ __('Hero of the Month') }} <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" step="any" min="0" max="100"
                                                class="form-control font-weight-bold @error('hero_of_the_month') is-invalid @enderror"
                                                id="hero_of_the_month" name="hero_of_the_month"
                                                value="{{ old('hero_of_the_month', optional($distribution)->hero_of_the_month !== null ? number_format((float) $distribution->hero_of_the_month, 2, '.', '') : '2.00') }}"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">%</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">{{ __('Percentage allocated to the Hero of the Month reward pool.') }}</small>
                                        @error('hero_of_the_month')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Card 2: Trading Wallet Control --}}
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                    <h3 class="mb-0 text-black font-weight-bold">
                                        <i class="ik ik-pocket mr-2"></i>{{ __('Trading Wallet Control') }}
                                    </h3>
                                    <span class="badge {{ strtolower($currentStatus) === 'on' ? 'badge-success' : 'badge-danger' }} font-weight-bold text-uppercase px-2 py-1">
                                        {{ strtoupper($currentStatus) }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="form-group mb-3">
                                        <label for="lock_days" class="font-weight-bold text-dark">
                                            {{ __('Locking Period') }} <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" min="0" step="1"
                                                class="form-control font-weight-bold @error('lock_days') is-invalid @enderror"
                                                id="lock_days"
                                                name="lock_days"
                                                value="{{ old('lock_days', optional($distribution)->lock_days ?? 90) }}"
                                                placeholder="90"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">{{ __('Days') }}</span>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            {{ __('Number of days member trading wallet remains locked after activation.') }}
                                        </small>
                                        @error('lock_days')
                                            <span class="text-danger small font-weight-bold">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="withdrawal_percent" class="font-weight-bold text-dark">
                                            {{ __('Max Withdrawal Percentage') }}
                                        </label>
                                        <div class="input-group">
                                            <input type="number" step="any" min="0" max="100"
                                                class="form-control font-weight-bold @error('withdrawal_percent') is-invalid @enderror"
                                                id="withdrawal_percent"
                                                name="withdrawal_percent"
                                                value="{{ old('withdrawal_percent', optional($distribution)->withdrawal_percent !== null ? number_format((float) $distribution->withdrawal_percent, 2, '.', '') : '100.00') }}"
                                                placeholder="100.00">
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">%</span>
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            {{ __('Allowable withdrawal percentage from trading wallet after lock expires.') }}
                                        </small>
                                    </div>

                                    {{-- Trading Wallet Status (ON / OFF) --}}
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold text-dark d-block">{{ __('Trading Wallet Status') }}</label>
                                        <div class="p-2 rounded bg-light border d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-muted small text-uppercase font-weight-bold d-block">{{ __('Current Status') }}</span>
                                                <h5 class="mb-0 font-weight-bold {{ strtolower($currentStatus) === 'on' ? 'text-success' : 'text-danger' }}">
                                                    <i class="ik {{ strtolower($currentStatus) === 'on' ? 'ik-check-circle' : 'ik-x-circle' }} mr-1"></i>
                                                    <span>{{ strtoupper($currentStatus) }}</span>
                                                </h5>
                                            </div>
                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-outline-success font-weight-bold {{ strtolower($currentStatus) === 'on' ? 'active' : '' }}">
                                                    <input type="radio" name="status" value="on" autocomplete="off" {{ strtolower($currentStatus) === 'on' ? 'checked' : '' }}>
                                                    <i class="ik ik-check mr-1"></i>{{ __('ON') }}
                                                </label>
                                                <label class="btn btn-outline-danger font-weight-bold {{ strtolower($currentStatus) !== 'on' ? 'active' : '' }}">
                                                    <input type="radio" name="status" value="off" autocomplete="off" {{ strtolower($currentStatus) !== 'on' ? 'checked' : '' }}>
                                                    <i class="ik ik-x mr-1"></i>{{ __('OFF') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Monthly Trading Profit Configuration & Package Rates --}}
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-dark d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 text-white font-weight-bold">
                                        <i class="ik ik-trending-up mr-2 text-primary"></i>{{ __('Monthly Trading Profit & Package Rates') }}
                                    </h5>
                                    <span class="badge badge-success font-weight-bold">{{ __('Dynamic Tiers') }}</span>
                                </div>
                                <div class="card-body">
                                    {{-- Capping Limit Card Header --}}
                                    <div class="p-3 mb-4 rounded bg-light border">
                                        <div class="row align-items-center">
                                            <div class="col-lg-7 col-md-12 mb-2 mb-lg-0">
                                                <h6 class="font-weight-bold text-dark mb-1">{{ __('Monthly Trading Profit Capping') }} <span class="text-danger">*</span></h6>
                                                <p class="text-muted small mb-0">
                                                    {{ __('Maximum total return cap percentage across all active trading stakings (e.g. 200.00%).') }}
                                                </p>
                                            </div>
                                            <div class="col-lg-5 col-md-12">
                                                <div class="input-group">
                                                    <input type="number" step="any" min="0"
                                                        class="form-control font-weight-bold @error('capping') is-invalid @enderror @error('capping_percent') is-invalid @enderror"
                                                        id="capping"
                                                        name="capping"
                                                        value="{{ old('capping', old('capping_percent', (isset($capping) && $capping !== null && $capping !== '') ? number_format((float) $capping, 2, '.', '') : ((isset($cappingPercent) && $cappingPercent !== null && $cappingPercent !== '') ? number_format((float) $cappingPercent, 2, '.', '') : '200.00'))) }}"
                                                        placeholder="e.g. 200.00"
                                                        required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text font-weight-bold bg-white">%</span>
                                                    </div>
                                                </div>
                                                @error('capping')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                                @error('capping_percent')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Dynamic Package Rates Tiers --}}
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="font-weight-bold text-dark mb-0">
                                            <i class="ik ik-layers mr-2 text-primary"></i>{{ __('Dynamic Package Tiers & Rates') }}
                                        </h6>
                                        <span class="badge badge-primary font-weight-bold">{{ $packagePlans->count() }} {{ __('Packages Loaded') }}</span>
                                    </div>

                                    <div class="row">
                                        @foreach($packagePlans as $index => $plan)
                                            @php
                                                $field = 'package_' . ($index + 1) . '_rate';
                                                $planRate = old('rates.' . $plan->id, optional($distribution)->{$field} ?? $plan->return_percent ?? 5.00);
                                            @endphp
                                            <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                                                <div class="card h-100 border shadow-none bg-light">
                                                    <div class="card-header bg-primary py-2 px-3 d-flex align-items-center justify-content-between">
                                                        <h6 class="mb-0 text-black font-weight-bold">
                                                            <span class="badge badge-dark mr-1">Tier {{ $index + 1 }}</span>
                                                            {{ $plan->name }}
                                                        </h6>
                                                        <span class="badge badge-light text-primary font-weight-bold">
                                                            {{ $plan->package_range }} USDT
                                                        </span>
                                                    </div>
                                                    <div class="card-body p-3">
                                                        <input type="hidden" name="plans[{{ $index }}][id]" value="{{ $plan->id }}">
                                                        <input type="hidden" name="plans[{{ $index }}][name]" value="{{ $plan->name }}">
                                                        <input type="hidden" name="plans[{{ $index }}][min_amount]" value="{{ $plan->min_amount }}">
                                                        <input type="hidden" name="plans[{{ $index }}][max_amount]" value="{{ $plan->max_amount }}">

                                                        <div class="p-2 mb-3 bg-white rounded border small">
                                                            <div class="d-flex justify-content-between mb-1">
                                                                <span class="text-muted">{{ __('Min Deposit:') }}</span>
                                                                <strong class="text-dark">${{ number_format((float) $plan->min_amount, 2) }}</strong>
                                                            </div>
                                                            <div class="d-flex justify-content-between">
                                                                <span class="text-muted">{{ __('Max Deposit:') }}</span>
                                                                <strong class="text-dark">
                                                                    {{ $plan->max_amount !== null ? '$' . number_format((float) $plan->max_amount, 2) : __('Unlimited') }}
                                                                </strong>
                                                            </div>
                                                        </div>

                                                        <div class="form-group mb-0">
                                                            <label for="rate_{{ $plan->id }}" class="font-weight-bold text-dark small mb-1">
                                                                {{ __('Rate (%)') }} <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="number" step="any" min="0" max="100"
                                                                    class="form-control font-weight-bold @error('rates.' . $plan->id) is-invalid @enderror"
                                                                    id="rate_{{ $plan->id }}"
                                                                    name="rates[{{ $plan->id }}]"
                                                                    value="{{ number_format((float) $planRate, 2, '.', '') }}"
                                                                    placeholder="5.00"
                                                                    required>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text font-weight-bold bg-white">%</span>
                                                                </div>
                                                            </div>
                                                            <small class="form-text text-muted mt-1">
                                                                {{ __('Monthly Trading Profit Rate for Tier :tier.', ['tier' => $index + 1]) }}
                                                            </small>
                                                            @error('rates.' . $plan->id)
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Unified Submit Button --}}
                                    <div class="text-center pt-3 mt-2 border-top">
                                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow font-weight-bold">
                                            <i class="ik ik-save mr-2"></i>{{ __('Save / Update All Configurations') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
