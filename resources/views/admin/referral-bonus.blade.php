@extends('admin.layouts.main')
@section('title', 'Referral Bonus')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-users bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Referral Bonus') }}</h5>
                            <span>{{ __('Configure multi-level referral bonus rates for levels 1, 2, and 3') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Referral Bonus') }}</li>
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
                <form action="{{ route('admin.saveReferralBonus') }}" method="POST" id="referralBonusForm">
                    @csrf

                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="font-weight-bold text-dark mb-0">
                                    <i class="ik ik-sliders mr-2 text-primary"></i>{{ __('Referral Bonus Rates Configuration') }}
                                </h5>
                                <span class="badge badge-primary font-weight-bold">{{ __('3 Levels') }}</span>
                            </div>
                            <p class="text-muted small mt-1 mb-3">
                                {{ __('Configure dynamic referral bonus percentage rates for Level 1, Level 2, and Level 3 sponsors.') }}
                            </p>
                        </div>

                        {{-- Level 1 Card --}}
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 text-white font-weight-bold">
                                        <i class="ik ik-user mr-2"></i>{{ __('Level-1 Rate') }}
                                    </h5>
                                    <span class="badge badge-light text-primary font-weight-bold">{{ __('Level 1') }}</span>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="p-2 mb-3 bg-light rounded text-muted small">
                                            <div class="d-flex justify-content-between">
                                                <span>{{ __('Target Tier:') }}</span>
                                                <strong class="text-dark">{{ __('Direct Sponsor (Level 1)') }}</strong>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label for="level_1_rate" class="font-weight-bold text-dark">
                                                {{ __('Level-1 Rate (%)') }} <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" step="any" min="0" max="100"
                                                    class="form-control @error('level_1_rate') is-invalid @enderror"
                                                    id="level_1_rate"
                                                    name="level_1_rate"
                                                    value="{{ old('level_1_rate', optional($setting)->level_1_rate !== null ? number_format((float) $setting->level_1_rate, 2, '.', '') : '5.00') }}"
                                                    placeholder="5.00"
                                                    required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text font-weight-bold bg-light">%</span>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                {{ __('Direct referral bonus rate paid to direct Level-1 sponsor.') }}
                                            </small>
                                            @error('level_1_rate')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Level 2 Card --}}
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 text-white font-weight-bold">
                                        <i class="ik ik-users mr-2"></i>{{ __('Level-2 Rate') }}
                                    </h5>
                                    <span class="badge badge-light text-primary font-weight-bold">{{ __('Level 2') }}</span>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="p-2 mb-3 bg-light rounded text-muted small">
                                            <div class="d-flex justify-content-between">
                                                <span>{{ __('Target Tier:') }}</span>
                                                <strong class="text-dark">{{ __('2nd Generation Sponsor') }}</strong>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label for="level_2_rate" class="font-weight-bold text-dark">
                                                {{ __('Level-2 Rate (%)') }} <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" step="any" min="0" max="100"
                                                    class="form-control @error('level_2_rate') is-invalid @enderror"
                                                    id="level_2_rate"
                                                    name="level_2_rate"
                                                    value="{{ old('level_2_rate', optional($setting)->level_2_rate !== null ? number_format((float) $setting->level_2_rate, 2, '.', '') : '3.00') }}"
                                                    placeholder="3.00"
                                                    required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text font-weight-bold bg-light">%</span>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                {{ __('Indirect referral bonus rate paid to Level-2 sponsor.') }}
                                            </small>
                                            @error('level_2_rate')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Level 3 Card --}}
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 text-white font-weight-bold">
                                        <i class="ik ik-award mr-2"></i>{{ __('Level-3 Rate') }}
                                    </h5>
                                    <span class="badge badge-light text-primary font-weight-bold">{{ __('Level 3') }}</span>
                                </div>
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="p-2 mb-3 bg-light rounded text-muted small">
                                            <div class="d-flex justify-content-between">
                                                <span>{{ __('Target Tier:') }}</span>
                                                <strong class="text-dark">{{ __('3rd Generation Sponsor') }}</strong>
                                            </div>
                                        </div>

                                        <div class="form-group mb-0">
                                            <label for="level_3_rate" class="font-weight-bold text-dark">
                                                {{ __('Level-3 Rate (%)') }} <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" step="any" min="0" max="100"
                                                    class="form-control @error('level_3_rate') is-invalid @enderror"
                                                    id="level_3_rate"
                                                    name="level_3_rate"
                                                    value="{{ old('level_3_rate', optional($setting)->level_3_rate !== null ? number_format((float) $setting->level_3_rate, 2, '.', '') : '2.00') }}"
                                                    placeholder="2.00"
                                                    required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text font-weight-bold bg-light">%</span>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                {{ __('Indirect referral bonus rate paid to Level-3 sponsor.') }}
                                            </small>
                                            @error('level_3_rate')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
