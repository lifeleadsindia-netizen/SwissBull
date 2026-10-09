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
                <div class="row">
                    {{-- Left Column: Package Distribution % --}}
                    <div class="col-xl-5 col-lg-5 col-md-12 mb-4">
                        <form action="{{ route('admin.savePackages') }}" method="POST" id="formPackageDistribution">
                            @csrf
                            <input type="hidden" id="trading_wallet" name="trading_wallet"
                                value="{{ old('trading_wallet', optional($distribution)->trading_wallet !== null ? number_format((float) $distribution->trading_wallet, 2, '.', '') : (optional($distribution)->p2p_wallet !== null ? number_format((float) $distribution->p2p_wallet, 2, '.', '') : '70.00')) }}">

                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                    <h3 class="mb-0 text-black font-weight-bold">{{ __('Set Packages') }}</h3>
                                </div>
                                <div class="card-body">

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
                                        <small class="form-text text-muted">{{ __('Allocated directly to member Trading Wallet') }}</small>
                                        @error('p2p_wallet')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
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

                                    <div class="text-center pt-2">
                                        <button type="submit" class="btn btn-primary px-4 shadow">
                                            <i class="ik ik-save mr-2"></i>{{ __('Save / Update') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Right Column: Dynamic Package Plans & Ranges --}}
                    <div class="col-xl-7 col-lg-7 col-md-12 mb-4 d-none" style="display: none;">
                        <form action="{{ route('admin.savePackages') }}" method="POST" id="formPackagePlans">
                            @csrf
                            <div class="card shadow-sm border-0 d-none" style="display: none;">
                                <div class="card-header bg-dark text-white d-flex align-items-center justify-content-between">
                                    <h3 class="mb-0 text-white font-weight-bold"><i class="ik ik-layers mr-2"></i>{{ __('Package Plans & Investment Tiers') }}</h3>
                                    <span class="badge badge-success font-weight-bold">3 Tiers Configured</span>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted small mb-3">
                                        {{ __('Manage package titles, min/max deposit limits, and active status for each package range.') }}
                                    </p>

                                    @if(isset($packagePlans) && $packagePlans->count() > 0)
                                        @foreach($packagePlans as $index => $plan)
                                            <div class="border rounded p-3 mb-3 bg-light">
                                                <input type="hidden" name="plans[{{ $index }}][id]" value="{{ $plan->id }}">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <h5 class="font-weight-bold text-primary mb-0">
                                                        <span class="badge badge-primary mr-2">Tier {{ $index + 1 }}</span>
                                                        <span class="plan-range-display" id="planRangeDisplay_{{ $index }}">{{ $plan->display_range }}</span>
                                                    </h5>
                                                    <div>
                                                        <select name="plans[{{ $index }}][status]" class="form-control form-control-sm d-inline-block font-weight-bold" style="width: 100px;">
                                                            <option value="Active" {{ $plan->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                            <option value="Inactive" {{ $plan->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 form-group mb-0">
                                                        <label class="small text-muted mb-1">{{ __('Package Title') }}</label>
                                                        <input type="text" class="form-control form-control-sm" name="plans[{{ $index }}][name]" value="{{ $plan->name }}" required>
                                                    </div>
                                                    <div class="col-md-3 form-group mb-0">
                                                        <label class="small text-muted mb-1">{{ __('Min Deposit ($)') }}</label>
                                                        <input type="number" step="any" min="0" class="form-control form-control-sm min-deposit-input" name="plans[{{ $index }}][min_amount]" id="minAmount_{{ $index }}" data-index="{{ $index }}" value="{{ (float)$plan->min_amount == (int)$plan->min_amount ? (int)$plan->min_amount : $plan->min_amount }}" required>
                                                    </div>
                                                    <div class="col-md-3 form-group mb-0">
                                                        <label class="small text-muted mb-1">{{ __('Max Deposit ($)') }}</label>
                                                        <input type="number" step="any" min="0" class="form-control form-control-sm max-deposit-input" name="plans[{{ $index }}][max_amount]" id="maxAmount_{{ $index }}" data-index="{{ $index }}" value="{{ ($plan->max_amount !== null && $plan->max_amount !== '') ? ((float)$plan->max_amount == (int)$plan->max_amount ? (int)$plan->max_amount : $plan->max_amount) : '' }}" placeholder="Unlimited">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    <div class="text-center pt-2">
                                        <button type="submit" class="btn btn-primary px-4 shadow">
                                            <i class="ik ik-save mr-2"></i>{{ __('Save / Update') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    (function () {
        function updateRangeDisplay(index) {
            const minEl = document.getElementById('minAmount_' + index);
            const maxEl = document.getElementById('maxAmount_' + index);
            const displayEl = document.getElementById('planRangeDisplay_' + index);

            if (!minEl || !displayEl) return;

            const minVal = minEl.value.trim();
            const maxVal = maxEl ? maxEl.value.trim() : '';

            const formattedMin = minVal !== '' ? minVal : '0';

            if (maxVal !== '' && !isNaN(maxVal) && parseFloat(maxVal) > 0) {
                displayEl.textContent = formattedMin + '–' + maxVal + ' USDT';
            } else {
                displayEl.textContent = formattedMin + '+ USDT';
            }
        }

        function initRangeListeners() {
            const minInputs = document.querySelectorAll('.min-deposit-input');
            const maxInputs = document.querySelectorAll('.max-deposit-input');

            minInputs.forEach(function (input) {
                const idx = input.getAttribute('data-index');
                ['input', 'change', 'keyup', 'blur'].forEach(function (evt) {
                    input.addEventListener(evt, function () {
                        updateRangeDisplay(idx);
                    });
                });
            });

            maxInputs.forEach(function (input) {
                const idx = input.getAttribute('data-index');
                ['input', 'change', 'keyup', 'blur'].forEach(function (evt) {
                    input.addEventListener(evt, function () {
                        updateRangeDisplay(idx);
                    });
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initRangeListeners);
        } else {
            initRangeListeners();
        }
    })();
</script>
@endpush
