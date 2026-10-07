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
                            <span>{{ __('Configure package distribution values') }}</span>
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
                    </div>
                @endif
                @if (session()->has('failedMsg'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="ik ik-alert-triangle mr-1"></i></strong> {{ session('failedMsg') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="col-12">
                <form action="{{ route('admin.savePackages') }}" method="POST">
                    @csrf
                    <input type="hidden" id="trading_wallet" name="trading_wallet"
                        value="{{ old('trading_wallet', optional($distribution)->trading_wallet !== null ? number_format((float) $distribution->trading_wallet, 2, '.', '') : (optional($distribution)->p2p_wallet !== null ? number_format((float) $distribution->p2p_wallet, 2, '.', '') : '70.00')) }}">

                    <div class="row">
                        {{-- Left Column: Package Distribution % --}}
                        <div class="col-xl-5 col-lg-5 col-md-12 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                    <h3 class="mb-0 text-white font-weight-bold"><i class="ik ik-pie-chart mr-2"></i>{{ __('Package Distribution (%)') }}</h3>
                                    <span class="badge badge-light text-primary font-weight-bold">100% Pool</span>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">
                                        {{ __('Configure how deposit funds are split across system wallets. By default, 70% goes to the Trading Wallet.') }}
                                    </p>

                                    <div class="form-group">
                                        <label for="p2p_wallet" class="font-weight-bold text-dark">{{ __('Trading Wallet Allocation') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" min="0" max="100"
                                                class="form-control @error('p2p_wallet') is-invalid @enderror"
                                                id="p2p_wallet" name="p2p_wallet"
                                                value="{{ old('p2p_wallet', optional($distribution)->p2p_wallet !== null ? number_format((float) $distribution->p2p_wallet, 2, '.', '') : '70.00') }}"
                                                oninput="document.getElementById('trading_wallet').value = this.value;"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">%</span>
                                            </div>
                                        </div>
                                        <small class="form-text text-muted">{{ __('Allocated directly to member Trading Wallet (P2P Wallet).') }}</small>
                                        @error('p2p_wallet')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="referral_bonus" class="font-weight-bold text-dark">{{ __('Referral Bonus') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" min="0" max="100"
                                                class="form-control @error('referral_bonus') is-invalid @enderror"
                                                id="referral_bonus" name="referral_bonus"
                                                value="{{ old('referral_bonus', optional($distribution)->referral_bonus !== null ? number_format((float) $distribution->referral_bonus, 2, '.', '') : '10.00') }}"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">%</span>
                                            </div>
                                        </div>
                                        @error('referral_bonus')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="team_trading_profit" class="font-weight-bold text-dark">{{ __('Team Trading Profit') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" min="0" max="100"
                                                class="form-control @error('team_trading_profit') is-invalid @enderror"
                                                id="team_trading_profit" name="team_trading_profit"
                                                value="{{ old('team_trading_profit', optional($distribution)->team_trading_profit !== null ? number_format((float) $distribution->team_trading_profit, 2, '.', '') : '8.00') }}"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">%</span>
                                            </div>
                                        </div>
                                        @error('team_trading_profit')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="team_performance_bonus" class="font-weight-bold text-dark">{{ __('Team Performance Bonus') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" min="0" max="100"
                                                class="form-control @error('team_performance_bonus') is-invalid @enderror"
                                                id="team_performance_bonus" name="team_performance_bonus"
                                                value="{{ old('team_performance_bonus', optional($distribution)->team_performance_bonus !== null ? number_format((float) $distribution->team_performance_bonus, 2, '.', '') : '10.00') }}"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">%</span>
                                            </div>
                                        </div>
                                        @error('team_performance_bonus')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="hero_of_the_month" class="font-weight-bold text-dark">{{ __('Hero of the Month') }}</label>
                                        <div class="input-group">
                                            <input type="number" step="any" min="0" max="100"
                                                class="form-control @error('hero_of_the_month') is-invalid @enderror"
                                                id="hero_of_the_month" name="hero_of_the_month"
                                                value="{{ old('hero_of_the_month', optional($distribution)->hero_of_the_month !== null ? number_format((float) $distribution->hero_of_the_month, 2, '.', '') : '2.00') }}"
                                                required>
                                            <div class="input-group-append">
                                                <span class="input-group-text font-weight-bold bg-light">%</span>
                                            </div>
                                        </div>
                                        @error('hero_of_the_month')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Dynamic Package Plans & Ranges --}}
                        <div class="col-xl-7 col-lg-7 col-md-12 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                                    <h3 class="mb-0 text-white font-weight-bold"><i class="ik ik-layers mr-2"></i>{{ __('Package Plans & Investment Tiers') }}</h3>
                                    <span class="badge badge-success font-weight-bold">3 Tiers Configured</span>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">
                                        {{ __('Manage min/max deposit limits, individual return rates, maximum earning capping, and lock periods for each package range.') }}
                                    </p>

                                    @if(isset($packagePlans) && $packagePlans->count() > 0)
                                        @foreach($packagePlans as $index => $plan)
                                            <div class="border rounded p-3 mb-3 bg-light">
                                                <input type="hidden" name="plans[{{ $index }}][id]" value="{{ $plan->id }}">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <h5 class="font-weight-bold text-primary mb-0">
                                                        <span class="badge badge-primary mr-2">Tier {{ $index + 1 }}</span>
                                                        {{ $plan->package_range }} USDT
                                                    </h5>
                                                    <div>
                                                        <select name="plans[{{ $index }}][status]" class="form-control form-control-sm d-inline-block font-weight-bold" style="width: 100px;">
                                                            <option value="Active" {{ $plan->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                            <option value="Inactive" {{ $plan->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 form-group mb-2">
                                                        <label class="small text-muted mb-1">{{ __('Package Title') }}</label>
                                                        <input type="text" class="form-control form-control-sm" name="plans[{{ $index }}][name]" value="{{ $plan->name }}" required>
                                                    </div>
                                                    <div class="col-md-3 form-group mb-2">
                                                        <label class="small text-muted mb-1">{{ __('Min Deposit ($)') }}</label>
                                                        <input type="number" step="any" min="0" class="form-control form-control-sm" name="plans[{{ $index }}][min_amount]" value="{{ $plan->min_amount }}" required>
                                                    </div>
                                                    <div class="col-md-3 form-group mb-2">
                                                        <label class="small text-muted mb-1">{{ __('Max Deposit ($)') }}</label>
                                                        <input type="number" step="any" min="0" class="form-control form-control-sm" name="plans[{{ $index }}][max_amount]" value="{{ $plan->max_amount }}" placeholder="Unlimited">
                                                    </div>
                                                    <div class="col-md-3 form-group mb-2">
                                                        <label class="small text-muted mb-1">{{ __('Trading Wallet %') }}</label>
                                                        <input type="number" step="any" min="0" max="100" class="form-control form-control-sm" name="plans[{{ $index }}][trading_wallet_percent]" value="{{ $plan->trading_wallet_percent }}">
                                                    </div>
                                                    <div class="col-md-3 form-group mb-2">
                                                        <label class="small text-muted mb-1">{{ __('Return Rate %') }}</label>
                                                        <input type="number" step="any" min="0" class="form-control form-control-sm" name="plans[{{ $index }}][return_percent]" value="{{ $plan->return_percent }}">
                                                    </div>
                                                    <div class="col-md-3 form-group mb-2">
                                                        <label class="small text-muted mb-1">{{ __('Max Return Limit %') }}</label>
                                                        <input type="number" step="any" min="0" class="form-control form-control-sm" name="plans[{{ $index }}][max_return_percent]" value="{{ $plan->max_return_percent }}">
                                                    </div>
                                                    <div class="col-md-3 form-group mb-2">
                                                        <label class="small text-muted mb-1">{{ __('Lock Period (Days)') }}</label>
                                                        <input type="number" min="0" class="form-control form-control-sm" name="plans[{{ $index }}][lock_days]" value="{{ $plan->lock_days }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="col-12 text-center my-3">
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                                <i class="ik ik-save mr-2"></i>{{ __('Save / Update All Package Settings') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
