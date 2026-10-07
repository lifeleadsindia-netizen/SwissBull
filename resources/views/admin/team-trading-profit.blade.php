@extends('admin.layouts.main')
@section('title', 'Team Trading Profit')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-bar-chart bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Team Trading Profit') }}</h5>
                            <span>{{ __('Configure multi-level team trading profit percentage rates across 10 levels') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Team Trading Profit') }}</li>
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
                <form action="{{ route('admin.saveTeamTradingProfit') }}" method="POST" id="teamTradingProfitForm">
                    @csrf

                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="font-weight-bold text-dark mb-0">
                                    <i class="ik ik-sliders mr-2 text-primary"></i>{{ __('Team Trading Profit Rates Configuration') }}
                                </h5>
                                <span class="badge badge-primary font-weight-bold">{{ __('10 Levels') }}</span>
                            </div>
                            <p class="text-muted small mt-1 mb-3">
                                {{ __('Configure dynamic team trading profit percentage rates for levels 1 through 10 below.') }}
                            </p>
                        </div>

                        @php
                            $defaultRates = [
                                1 => '5.00',
                                2 => '5.00',
                                3 => '4.00',
                                4 => '4.00',
                                5 => '3.00',
                                6 => '3.00',
                                7 => '2.00',
                                8 => '2.00',
                                9 => '1.00',
                                10 => '1.00',
                            ];
                        @endphp

                        @for ($i = 1; $i <= 10; $i++)
                            @php
                                $field = "level_{$i}_rate";
                                $val = optional($setting)->{$field} !== null
                                    ? number_format((float) $setting->{$field}, 2, '.', '')
                                    : $defaultRates[$i];
                            @endphp
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0 text-white font-weight-bold">
                                            <i class="ik ik-trending-up mr-2"></i>{{ __('Level-:num Rate', ['num' => $i]) }}
                                        </h6>
                                        <span class="badge badge-light text-primary font-weight-bold">
                                            {{ __('Level :num', ['num' => $i]) }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group mb-0">
                                            <label for="level_{{ $i }}_rate" class="font-weight-bold text-dark">
                                                {{ __('Level-:num Rate (%)', ['num' => $i]) }} <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" step="any" min="0" max="100"
                                                    class="form-control @error('level_' . $i . '_rate') is-invalid @enderror"
                                                    id="level_{{ $i }}_rate"
                                                    name="level_{{ $i }}_rate"
                                                    value="{{ old('level_' . $i . '_rate', $val) }}"
                                                    placeholder="{{ $defaultRates[$i] }}"
                                                    required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text font-weight-bold bg-light">%</span>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                {{ __('Team Trading Profit rate for Level :num.', ['num' => $i]) }}
                                            </small>
                                            @error('level_' . $i . '_rate')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
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
