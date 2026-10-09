@extends('admin.layouts.main')
@section('title', 'Monthly Trading Profit')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-trending-up bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Monthly Trading Profit') }}</h5>
                            <span>{{ __('Set monthly trading profit rates and capping') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Monthly Trading Profit') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
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
                @if ($errors->any())
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
                <form action="{{ route('admin.saveMonthlyTradingProfit') }}" method="POST" id="monthlyTradingProfitForm">
                    @csrf

                    {{-- Top Section: Capping Configuration Card --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 text-white font-weight-bold">
                                        <i class="ik ik-shield mr-2"></i>{{ __('Monthly Trading Profit Capping (%)') }}
                                    </h5>
                                    <span class="badge badge-success font-weight-bold">{{ __('Capping Limit') }}</span>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-lg-7 col-md-12 mb-3 mb-lg-0">
                                            <p class="text-muted mb-0">
                                                {{ __('Enter Monthly Trading Profit Capping percentage limit') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-5 col-md-12">
                                            <div class="form-group mb-0">
                                                <label for="capping_percent" class="font-weight-bold text-dark">
                                                    {{ __('Monthly Trading Profit Capping (%)') }} <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" step="any" min="0"
                                                        class="form-control @error('capping_percent') is-invalid @enderror"
                                                        id="capping_percent"
                                                        name="capping_percent"
                                                        value="{{ old('capping_percent', $cappingPercent !== null && $cappingPercent !== '' ? number_format((float) $cappingPercent, 2, '.', '') : '') }}"
                                                        placeholder="e.g. 200.00"
                                                        required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text font-weight-bold bg-light">%</span>
                                                    </div>
                                                </div>
                                                @error('capping_percent')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Packages Section: Dynamically Displayed Packages --}}
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="font-weight-bold text-dark mb-0">
                                    <i class="ik ik-layers mr-2 text-primary"></i>{{ __('Package Rates Configuration') }}
                                </h5>
                                <span class="badge badge-primary font-weight-bold">{{ $packages->count() }} {{ __('Packages Loaded') }}</span>
                            </div>
                            <p class="text-muted small mt-1 mb-3">
                                {{ __('Each package is fetched dynamically from current package records. Enter the specific Rate (%) for each package below.') }}
                            </p>
                        </div>

                        @forelse($packages as $index => $package)
                            @php
                                $pkgConfig = $configurations->get($package->id);
                                $pkgRate = null;
                                if ($pkgConfig) {
                                    $pkgRate = $pkgConfig->rate ?? $pkgConfig->rate_percent;
                                } elseif ($configurations->first() && isset($configurations->first()->{'package_' . ($index + 1) . '_rate'})) {
                                    $pkgRate = $configurations->first()->{'package_' . ($index + 1) . '_rate'};
                                }
                            @endphp
                            <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header bg-primary d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0 text-black font-weight-bold">
                                            <i class="ik ik-package mr-2"></i>{{ $package->name }}
                                        </h5>
                                        <span class="badge badge-light text-primary font-weight-bold">
                                            {{ $package->package_range }} USDT
                                        </span>
                                    </div>
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <div class="p-2 mb-3 bg-light rounded text-muted small">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span>{{ __('Min Deposit:') }}</span>
                                                    <strong class="text-dark">${{ number_format((float) $package->min_amount, 2) }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ __('Max Deposit:') }}</span>
                                                    <strong class="text-dark">
                                                        {{ $package->max_amount !== null ? '$' . number_format((float) $package->max_amount, 2) : __('Unlimited') }}
                                                    </strong>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label for="rate_{{ $package->id }}" class="font-weight-bold text-dark">
                                                    {{ __('Rate (%)') }} <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                    <input type="number" step="any" min="0" max="100"
                                                        class="form-control @error('rates.' . $package->id) is-invalid @enderror"
                                                        id="rate_{{ $package->id }}"
                                                        name="rates[{{ $package->id }}]"
                                                        value="{{ old('rates.' . $package->id, $pkgRate !== null && $pkgRate !== '' ? number_format((float) $pkgRate, 2, '.', '') : '') }}"
                                                        placeholder="e.g. 5.00"
                                                        required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text font-weight-bold bg-light">%</span>
                                                    </div>
                                                </div>
                                                <small class="form-text text-muted">
                                                    {{ __('Monthly Trading Profit Rate for :package.', ['package' => $package->name]) }}
                                                </small>
                                                @error('rates.' . $package->id)
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning" role="alert">
                                    <i class="ik ik-alert-circle mr-2"></i>{{ __('No packages found in the database. Please ensure packages exist.') }}
                                </div>
                            </div>
                        @endforelse

                        {{-- Submit Button --}}
                        <div class="col-12 text-center my-3">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow" id="btnSaveConfig">
                                <i class="ik ik-save mr-2"></i>{{ __('Save Configuration') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
