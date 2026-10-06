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
            <div class="col-md-6 col-lg-5">
                @if (session()->has('successMsg'))
                    <div class="alert alert-success" role="alert">
                        {{ session('successMsg') }}
                    </div>
                @endif
                @if (session()->has('failedMsg'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('failedMsg') }}
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

                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('SET PACKAGES') }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.savePackages') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="trading_wallet">{{ __('Trading Wallet') }}</label>
                                <div class="input-group">
                                    <input type="number" step="any" min="0"
                                        class="form-control @error('trading_wallet') is-invalid @enderror"
                                        id="trading_wallet" name="trading_wallet"
                                        value="{{ old('trading_wallet', optional($distribution)->trading_wallet !== null ? number_format((float) $distribution->trading_wallet, 2, '.', '') : '') }}"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                @error('trading_wallet')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="referral_bonus">{{ __('Referral Bonus') }}</label>
                                <div class="input-group">
                                    <input type="number" step="any" min="0"
                                        class="form-control @error('referral_bonus') is-invalid @enderror"
                                        id="referral_bonus" name="referral_bonus"
                                        value="{{ old('referral_bonus', optional($distribution)->referral_bonus !== null ? number_format((float) $distribution->referral_bonus, 2, '.', '') : '') }}"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                @error('referral_bonus')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="team_trading_profit">{{ __('Team Trading Profit') }}</label>
                                <div class="input-group">
                                    <input type="number" step="any" min="0"
                                        class="form-control @error('team_trading_profit') is-invalid @enderror"
                                        id="team_trading_profit" name="team_trading_profit"
                                        value="{{ old('team_trading_profit', optional($distribution)->team_trading_profit !== null ? number_format((float) $distribution->team_trading_profit, 2, '.', '') : '') }}"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                @error('team_trading_profit')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="team_performance_bonus">{{ __('Team Performance Bonus') }}</label>
                                <div class="input-group">
                                    <input type="number" step="any" min="0"
                                        class="form-control @error('team_performance_bonus') is-invalid @enderror"
                                        id="team_performance_bonus" name="team_performance_bonus"
                                        value="{{ old('team_performance_bonus', optional($distribution)->team_performance_bonus !== null ? number_format((float) $distribution->team_performance_bonus, 2, '.', '') : '') }}"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                @error('team_performance_bonus')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="hero_of_the_month">{{ __('Hero of the Month') }}</label>
                                <div class="input-group">
                                    <input type="number" step="any" min="0"
                                        class="form-control @error('hero_of_the_month') is-invalid @enderror"
                                        id="hero_of_the_month" name="hero_of_the_month"
                                        value="{{ old('hero_of_the_month', optional($distribution)->hero_of_the_month !== null ? number_format((float) $distribution->hero_of_the_month, 2, '.', '') : '') }}"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                @error('hero_of_the_month')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    {{ __('Save / Update') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
