@extends('admin.layouts.main')
@section('title', 'Daily Team Investment Share')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-pie-chart bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Daily Team Investment Share') }}</h5>
                            <span>{{ __('Configure multi-level daily team investment share percentage rates and direct referral requirements across 10 levels') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Daily Team Investment Share') }}</li>
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
                <form action="{{ route('admin.saveDailyTeamInvestmentShare') }}" method="POST" id="dailyTeamInvestmentShareForm">
                    @csrf

                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="font-weight-bold text-dark mb-0">
                                    <i class="ik ik-sliders mr-2 text-primary"></i>{{ __('Daily Team Investment Share Configuration') }}
                                </h5>
                                <span class="badge badge-primary font-weight-bold">{{ __('10 Levels') }}</span>
                            </div>
                            <p class="text-muted small mt-1 mb-3">
                                {{ __('Configure dynamic rates and direct referral requirements for levels 1 through 10 below.') }}
                            </p>
                        </div>

                        @for ($i = 1; $i <= 10; $i++)
                            @php
                                $rateField = "level_{$i}_rate";
                                $directsField = "level_{$i}_directs";
                                $defaultRate = '1.00';
                                $defaultDirects = ($i === 1) ? 4 : 2;

                                $rateVal = optional($setting)->{$rateField} !== null
                                    ? number_format((float) $setting->{$rateField}, 2, '.', '')
                                    : $defaultRate;

                                $directsVal = optional($setting)->{$directsField} !== null
                                    ? (int) $setting->{$directsField}
                                    : $defaultDirects;
                            @endphp
                            <div class="col-xl-6 col-lg-6 col-md-12 mb-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0 text-white font-weight-bold">
                                            <i class="ik ik-layers mr-2"></i>{{ __('Level :num Configuration', ['num' => $i]) }}
                                        </h6>
                                        <span class="badge badge-light text-primary font-weight-bold">
                                            {{ __('Level :num', ['num' => $i]) }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- Rate Input --}}
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <div class="form-group mb-0">
                                                    <label for="level_{{ $i }}_rate" class="font-weight-bold text-dark">
                                                        {{ __('Level-:num Rate (%)', ['num' => $i]) }} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="number" step="any" min="0" max="100"
                                                            class="form-control @error('level_' . $i . '_rate') is-invalid @enderror"
                                                            id="level_{{ $i }}_rate" name="level_{{ $i }}_rate" value="{{ old('level_' . $i . '_rate', $rateVal) }}"
                                                            placeholder="1.00"
                                                            required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text font-weight-bold bg-light">%</span>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        {{ __('Daily share rate percentage.') }}
                                                    </small>
                                                    @error('level_' . $i . '_rate')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Directs Requirement Input --}}
                                            <div class="col-sm-6">
                                                <div class="form-group mb-0">
                                                    <label for="level_{{ $i }}_directs" class="font-weight-bold text-dark">
                                                        {{ __('Direct Referrals', ['num' => $i]) }} <span class="text-danger">*</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="number" step="1" min="0"
                                                            class="form-control @error('level_' . $i . '_directs') is-invalid @enderror"
                                                            id="level_{{ $i }}_directs" name="level_{{ $i }}_directs" value="{{ old('level_' . $i . '_directs', $directsVal) }}"
                                                            placeholder="{{ $defaultDirects }}"
                                                            required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text font-weight-bold bg-light">{{ __('Directs') }}</span>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        @if ($i === 1)
                                                            {{ __('4 Direct Referrals required.') }}
                                                        @else
                                                            {{ __('+2 Direct Referrals required.') }}
                                                        @endif
                                                    </small>
                                                    @error('level_' . $i . '_directs')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor

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
