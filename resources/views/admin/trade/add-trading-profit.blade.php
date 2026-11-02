@extends('admin.layouts.main')
@section('title', 'Add Trading Profit')

@push('head')
    <link rel="stylesheet" href="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.css') }}">
    <style>
        .tp-hero-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04);
            transition: all 0.25s ease;
            overflow: hidden;
            background: #ffffff;
        }
        .tp-hero-card:hover {
            box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.12), 0 8px 16px -4px rgba(15, 23, 42, 0.06);
        }
        .tp-card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
            padding: 1.15rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .tp-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .tp-icon-badge {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }
        .tp-badge-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        .tp-badge-success {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
        }
        .tp-badge-purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
        }
        .tp-badge-amber {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .tp-form-control {
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            color: #0f172a;
            font-weight: 500;
        }
        .tp-form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3.5px rgba(59, 130, 246, 0.15);
            background-color: #ffffff;
        }
        .tp-input-group-text {
            border-radius: 10px 0 0 10px;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            background: #f8fafc;
            color: #64748b;
            font-weight: 600;
        }
        .tp-input-group .tp-form-control {
            border-radius: 0 10px 10px 0;
        }
        .tp-quick-btn {
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .tp-quick-btn:hover {
            background: #3b82f6;
            color: #ffffff;
            border-color: #3b82f6;
            transform: translateY(-1px);
        }
        .tp-quick-btn.tp-max-btn {
            background: #eff6ff;
            color: #2563eb;
            border-color: #bfdbfe;
        }
        .tp-quick-btn.tp-max-btn:hover {
            background: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
        }
        .tp-metric-card {
            border-radius: 14px;
            padding: 1rem 1.15rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }
        .tp-metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(15, 23, 42, 0.08);
        }
        .tp-metric-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
        }
        .tp-metric-card.card-tw::before {
            background: linear-gradient(180deg, #3b82f6 0%, #06b6d4 100%);
        }
        .tp-metric-card.card-wallet::before {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
        }
        .tp-metric-card.card-cap::before {
            background: linear-gradient(180deg, #8b5cf6 0%, #6366f1 100%);
        }
        .tp-metric-card.card-remaining::before {
            background: linear-gradient(180deg, #f59e0b 0%, #ea580c 100%);
        }
        .tp-metric-label {
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .tp-metric-value {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
        }
        .tp-metric-sub {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 4px;
            font-weight: 500;
        }
        .tp-progress-bar-wrap {
            background: #f1f5f9;
            border-radius: 999px;
            height: 10px;
            overflow: hidden;
            position: relative;
        }
        .tp-progress-bar-inner {
            height: 100%;
            border-radius: 999px;
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(90deg, #10b981 0%, #3b82f6 100%);
        }
        .tp-progress-bar-inner.warning {
            background: linear-gradient(90deg, #f59e0b 0%, #ea580c 100%);
        }
        .tp-progress-bar-inner.danger {
            background: linear-gradient(90deg, #ef4444 0%, #b91c1c 100%);
        }
        .tp-empty-state {
            padding: 2.75rem 1.5rem;
            text-align: center;
        }
        .tp-empty-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            color: #3b82f6;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.15);
        }
        .tp-user-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 1.25rem;
        }
        .tp-user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.25);
            flex-shrink: 0;
        }
        .tp-btn-submit {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.75rem;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 6px 18px rgba(37, 99, 235, 0.3);
            transition: all 0.2s ease;
        }
        .tp-btn-submit:hover:not(:disabled) {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transform: translateY(-1.5px);
            box-shadow: 0 8px 22px rgba(37, 99, 235, 0.4);
            color: #ffffff;
        }
        .tp-btn-submit:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            box-shadow: none;
        }
        .tp-impact-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.85rem;
            margin-top: 1rem;
        }
        .tp-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        .tp-status-dot.active {
            background: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.25);
        }
        .tp-status-dot.inactive {
            background: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25);
        }
        .tp-shimmer {
            animation: tpPulse 1.5s infinite;
        }
        @keyframes tpPulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
        .tp-staking-row {
            transition: all 0.2s ease;
        }
        .tp-staking-row:hover {
            background-color: #f8fafc !important;
        }
        .tp-staking-selected {
            background-color: #f0fdf4 !important;
            border-left: 3px solid #10b981 !important;
        }
        .tp-staking-deactive {
            background-color: #fafafa !important;
            opacity: 0.85;
        }
        .btn-autofill-staking {
            border-radius: 8px;
            font-size: 0.78rem;
            padding: 5px 12px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .btn-autofill-staking.is-active {
            background: #10b981 !important;
            border-color: #10b981 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-lg-7 col-md-6">
                    <div class="page-header-title d-flex align-items-center">
                        <div class="tp-icon-badge tp-badge-primary mr-3" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="ik ik-trending-up"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ __('Add Trading Profit') }}</h4>
                            <span class="text-muted small">{{ __('Real-time member capping inspection & trading profit disbursement') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 text-md-right mt-3 mt-md-0">
                    <a href="{{ url('admin/trade/add-trading-profit-details') }}" class="btn btn-sm btn-outline-primary font-weight-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="ik ik-list mr-1"></i> {{ __('Profit Distribution History') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Session Flash Messages -->
        @if (session()->has('failedMsg'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px; background: #fef2f2; border-left: 4px solid #ef4444 !important; color: #991b1b;">
                <div class="d-flex align-items-center">
                    <i class="ik ik-alert-octagon mr-2" style="font-size: 1.2rem;"></i>
                    <strong class="mr-1">{{ __('Error:') }}</strong> {{ session('failedMsg') }}
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #991b1b;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('successMsg'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px; background: #ecfdf5; border-left: 4px solid #10b981 !important; color: #065f46;">
                <div class="d-flex align-items-center">
                    <i class="ik ik-check-circle mr-2" style="font-size: 1.2rem;"></i>
                    <strong class="mr-1">{{ __('Success!') }}</strong> {{ session('successMsg') }}
                </div>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="color: #065f46;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Main Workspace: Form on Left, Member Capping on Right -->
        <div class="row">
            <!-- Left Column: Add Profit Form -->
            <div class="col-xl-5 col-lg-6 mb-4">
                <div class="card tp-hero-card h-100">
                    <div class="tp-card-header">
                        <div class="tp-card-title">
                            <div class="tp-icon-badge tp-badge-primary">
                                <i class="ik ik-dollar-sign"></i>
                            </div>
                            <span>{{ __('Disburse Profit') }}</span>
                        </div>
                        <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 0.75rem; border-radius: 6px;">
                            <i class="ik ik-shield mr-1 text-primary"></i> Cap Rate: <strong>{{ number_format((float) ($globalCapping ?? 300), 0) }}%</strong>
                        </span>
                    </div>

                    <div class="card-body p-4">
                        <form id="tradingProfitForm" action="{{ route('addTradingProfit') }}" method="post">
                            @csrf
                            <input type="hidden" id="csrf" value="{{ csrf_token() }}">
                            <input type="hidden" name="staking_id" id="staking_id" value="0">

                            <!-- Member ID Input -->
                            <div class="form-group mb-3">
                                <label for="memberid" class="font-weight-bold text-dark small mb-1">
                                    {{ __('Member ID') }} <span class="text-danger">*</span>
                                </label>
                                <div class="input-group tp-input-group position-relative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text tp-input-group-text">
                                            <i class="ik ik-user"></i>
                                        </span>
                                    </div>
                                    <input type="text"
                                           class="form-control tp-form-control @error('memberid') is-invalid @enderror"
                                           name="memberid"
                                           id="memberid"
                                           value="{{ old('memberid') }}"
                                           placeholder="{{ __('Enter Member ID (e.g. ST1234567)') }}"
                                           autocomplete="off"
                                           required>
                                    <div id="memberSearchSpinner" class="position-absolute" style="right: 14px; top: 12px; z-index: 10; display: none;">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="sr-only">Checking...</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="memMsg" class="mt-1 small font-weight-bold"></div>
                                @error('memberid')
                                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Selected Specific Staking Alert / Callout -->
                            <div id="selectedStakingBox" class="p-3 mb-3 border rounded-lg" style="display: none; background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border-color: #86efac !important; border-radius: 12px; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.1);">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="badge badge-success px-2 py-1 font-weight-bold mr-2" style="font-size: 0.75rem; border-radius: 6px;">
                                            <i class="ik ik-layers mr-1"></i> {{ __('Staking Selected') }}
                                        </span>
                                        <strong class="text-dark small" id="selectedStakingTitle">Staking #0</strong>
                                    </div>
                                    <button type="button" id="btnClearStakingSelection" class="btn btn-sm btn-link text-danger font-weight-bold p-0 text-decoration-none" title="Deselect staking and return to general capping mode">
                                        <i class="ik ik-x-circle mr-1"></i> {{ __('Clear') }}
                                    </button>
                                </div>
                                <div class="d-flex align-items-center justify-content-between small text-muted flex-wrap">
                                    <span>Package: <strong id="selectedStakingPackage" class="text-dark">--</strong></span>
                                    <span>Staking Remaining Cap: <strong id="selectedStakingRemaining" class="text-success font-weight-bold">$0.00</strong></span>
                                </div>
                                <div class="mt-2 text-muted" style="font-size: 0.75rem;">
                                    <i class="ik ik-info mr-1 text-success"></i> {{ __('Disbursement will be recorded specifically against this staking.') }}
                                </div>
                            </div>

                            <!-- Amount Input -->
                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="amount" class="font-weight-bold text-dark small mb-0">
                                        {{ __('Profit Amount ($)') }} <span class="text-danger">*</span>
                                    </label>
                                    <span id="maxAllowedBadge" class="small text-muted font-weight-bold" style="display: none;">
                                        {{ __('Max Allowed:') }} <span id="maxAllowedText" class="text-success">$0.00</span>
                                    </span>
                                </div>
                                <div class="input-group tp-input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text tp-input-group-text">$ USD</span>
                                    </div>
                                    <input type="number"
                                           step="0.01"
                                           min="0.01"
                                           class="form-control tp-form-control @error('amount') is-invalid @enderror"
                                           name="amount"
                                           id="amount"
                                           value="{{ old('amount') }}"
                                           placeholder="{{ __('0.00') }}"
                                           required>
                                </div>
                                @error('amount')
                                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                                @enderror

                                <!-- Quick Presets -->
                                <div class="d-flex flex-wrap align-items-center gap-1 mt-2" style="gap: 6px;">
                                    <span class="small text-muted mr-1 font-weight-bold">{{ __('Quick:') }}</span>
                                    <button type="button" class="tp-quick-btn quick-amt" data-amt="10">+ $10</button>
                                    <button type="button" class="tp-quick-btn quick-amt" data-amt="50">+ $50</button>
                                    <button type="button" class="tp-quick-btn quick-amt" data-amt="100">+ $100</button>
                                    <button type="button" class="tp-quick-btn quick-amt" data-amt="500">+ $500</button>
                                    <button type="button" id="btnSetMax" class="tp-quick-btn tp-max-btn" style="display: none;">
                                        <i class="ik ik-zap"></i> {{ __('Max Cap') }}
                                    </button>
                                </div>

                                <!-- Dynamic Over-limit Warning -->
                                <div id="amountOverLimitAlert" class="mt-2 text-danger small font-weight-bold" style="display: none;">
                                    <i class="ik ik-alert-triangle mr-1"></i>
                                    <span>{{ __('Entered amount exceeds remaining capping balance!') }}</span>
                                </div>
                            </div>

                            <!-- Live Impact Calculation Preview -->
                            <div id="impactPreviewBox" class="tp-impact-box" style="display: none;">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>{{ __('Main Wallet After:') }}</span>
                                    <strong id="previewNewWallet" class="text-dark">$0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>{{ __('Remaining Capping After:') }}</span>
                                    <strong id="previewNewRemaining" class="text-primary">$0.00</strong>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="mt-4 pt-2 d-flex align-items-center justify-content-between">
                                <button type="reset" id="btnResetForm" class="btn btn-link text-muted font-weight-bold text-decoration-none px-2">
                                    <i class="ik ik-rotate-ccw mr-1"></i> {{ __('Reset') }}
                                </button>
                                <button type="submit" id="btnSubmitProfit" class="tp-btn-submit">
                                    <i class="ik ik-check-circle mr-1"></i> {{ __('Submit Profit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Member Capping & Balances Analytics Card -->
            <div class="col-xl-7 col-lg-6 mb-4">
                <div class="card tp-hero-card h-100">
                    <div class="tp-card-header">
                        <div class="tp-card-title">
                            <div class="tp-icon-badge tp-badge-purple">
                                <i class="ik ik-shield"></i>
                            </div>
                            <span>{{ __('Member Capping & Balances') }}</span>
                        </div>
                        <div id="liveCheckBadge">
                            <span class="badge badge-pill badge-light border text-muted small px-3 py-1">
                                <i class="ik ik-clock mr-1"></i> {{ __('Live Auto-Lookup') }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4 position-relative">
                        <!-- STATE 1: Empty Placeholder (No Member Selected) -->
                        <div id="stateEmpty" class="tp-empty-state">
                            <div class="tp-empty-icon">
                                <i class="ik ik-user-check"></i>
                            </div>
                            <h5 class="font-weight-bold text-dark mb-2">{{ __('No Member Selected') }}</h5>
                            <p class="text-muted small mx-auto mb-4" style="max-width: 420px; line-height: 1.6;">
                                {{ __('Type a Member ID in the form on the left. The system will instantly retrieve the member\'s Trading Wallet, Main Wallet balance, Total Capping limit, and Remaining Profit room.') }}
                            </p>

                            <div class="row justify-content-center text-left" style="max-width: 480px; margin: 0 auto;">
                                <div class="col-sm-6 mb-2">
                                    <div class="p-2 border rounded-lg bg-light small d-flex align-items-center">
                                        <i class="ik ik-check text-success font-weight-bold mr-2"></i>
                                        <span>{{ __('Real-Time Capping Check') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div class="p-2 border rounded-lg bg-light small d-flex align-items-center">
                                        <i class="ik ik-check text-success font-weight-bold mr-2"></i>
                                        <span>{{ __('Main & Trading Wallet') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div class="p-2 border rounded-lg bg-light small d-flex align-items-center">
                                        <i class="ik ik-check text-success font-weight-bold mr-2"></i>
                                        <span>{{ __('Over-Distribution Guard') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-2">
                                    <div class="p-2 border rounded-lg bg-light small d-flex align-items-center">
                                        <i class="ik ik-check text-success font-weight-bold mr-2"></i>
                                        <span>{{ __('One-Click Max Autofill') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STATE 2: Loading State -->
                        <div id="stateLoading" class="tp-empty-state" style="display: none;">
                            <div class="spinner-grow text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <h6 class="font-weight-bold text-dark">{{ __('Fetching Member Data...') }}</h6>
                            <p class="text-muted small">{{ __('Calculating dynamic capping limits and balances') }}</p>
                        </div>

                        <!-- STATE 3: Success State (Member Verified) -->
                        <div id="stateDetails" style="display: none;">
                            <!-- Member Profile Bar -->
                            <div class="tp-user-pill">
                                <div id="userAvatar" class="tp-user-avatar">
                                    --
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                                        <h6 id="userName" class="font-weight-bold text-dark mb-0 mr-2 text-truncate">Member Name</h6>
                                        <div class="d-flex align-items-center gap-1 mt-1 mt-sm-0">
                                            <span id="userStatusBadge" class="badge badge-success px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 0.75rem;">
                                                <span class="tp-status-dot active mr-1"></span> Active
                                            </span>
                                            <span id="userPackageBadge" class="badge badge-light border text-muted px-2 py-1 font-weight-bold ml-1" style="border-radius: 6px; font-size: 0.75rem;">
                                                Standard
                                            </span>
                                        </div>
                                    </div>
                                    <div class="small text-muted mt-1 d-flex flex-wrap align-items-center gap-2">
                                        <span class="mr-2"><i class="ik ik-hash mr-1"></i><strong id="userMemberId" class="text-dark">ST000000</strong></span>
                                        <span id="userEmailSpan" class="mr-2 text-truncate" style="max-width: 200px;"><i class="ik ik-mail mr-1"></i><span id="userEmail">--</span></span>
                                    </div>
                                </div>
                            </div>

                            <!-- 4 Key Metric Tiles (2x2 Grid) -->
                            <div class="row mb-3">
                                <!-- Metric 1: Trading Wallet -->
                                <div class="col-sm-6 mb-3">
                                    <div class="tp-metric-card card-tw h-100">
                                        <div class="tp-metric-label">
                                            <span>{{ __('Trading Wallet') }}</span>
                                            <i class="ik ik-pie-chart text-primary"></i>
                                        </div>
                                        <div id="valTradingWallet" class="tp-metric-value text-primary">$0.00</div>
                                        <div class="tp-metric-sub">{{ __('Base Trading Capital for Capping') }}</div>
                                    </div>
                                </div>

                                <!-- Metric 2: Main Wallet -->
                                <div class="col-sm-6 mb-3">
                                    <div class="tp-metric-card card-wallet h-100">
                                        <div class="tp-metric-label">
                                            <span>{{ __('Main Wallet') }}</span>
                                            <i class="ik ik-credit-card text-success"></i>
                                        </div>
                                        <div id="valMainWallet" class="tp-metric-value text-success">$0.00</div>
                                        <div class="tp-metric-sub">{{ __('Profit will be credited here') }}</div>
                                    </div>
                                </div>

                                <!-- Metric 3: Total Capping Limit -->
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <div class="tp-metric-card card-cap h-100">
                                        <div class="tp-metric-label">
                                            <span>{{ __('Total Capping Cap') }}</span>
                                            <i class="ik ik-award text-purple" style="color: #8b5cf6;"></i>
                                        </div>
                                        <div id="valTotalCapping" class="tp-metric-value" style="color: #6d28d9;">$0.00</div>
                                        <div id="valCappingRatioSub" class="tp-metric-sub">300% of Trading Wallet</div>
                                    </div>
                                </div>

                                <!-- Metric 4: Remaining Capping Room -->
                                <div class="col-sm-6">
                                    <div class="tp-metric-card card-remaining h-100">
                                        <div class="tp-metric-label">
                                            <span>{{ __('Remaining Cap Room') }}</span>
                                            <i id="remainingIcon" class="ik ik-target text-warning"></i>
                                        </div>
                                        <div id="valRemainingCapping" class="tp-metric-value text-warning">$0.00</div>
                                        <div class="tp-metric-sub">{{ __('Max profit allowed to add') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Visual Capping Progress Bar -->
                            <div class="border rounded-lg p-3 bg-light mb-3" style="border-radius: 12px;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small font-weight-bold text-dark">
                                        <i class="ik ik-activity mr-1 text-primary"></i> {{ __('Capping Quota Consumed') }}
                                    </span>
                                    <span id="cappingPercentText" class="small font-weight-bold text-primary">0%</span>
                                </div>
                                <div class="tp-progress-bar-wrap mb-2">
                                    <div id="cappingProgressBar" class="tp-progress-bar-inner" style="width: 0%;"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center small text-muted">
                                    <span>{{ __('Profit Paid:') }} <strong id="valAchievedProfit" class="text-dark">$0.00</strong></span>
                                    <span>{{ __('Max Ceiling:') }} <strong id="valCeilingProfit" class="text-dark">$0.00</strong></span>
                                </div>
                            </div>

                            <!-- Live Eligibility Status Banner -->
                            <div id="eligibilityBanner" class="p-3 rounded-lg d-flex align-items-center" style="border-radius: 10px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
                                <i id="eligibilityIcon" class="ik ik-check-circle mr-2" style="font-size: 1.25rem;"></i>
                                <div class="small" id="eligibilityText">
                                    <strong>{{ __('Eligible for Profit:') }}</strong> {{ __('You can disburse up to the available capping balance.') }}
                                </div>
                            </div>

                            <!-- Stakings Summary Callout & Quick Link -->
                            <div id="stakingsSummaryCallout" class="mt-3 p-3 rounded-lg border bg-white d-flex align-items-center justify-content-between flex-wrap" style="border-radius: 10px; border-color: #e2e8f0; display: none !important;">
                                <div class="d-flex align-items-center mr-2 mb-2 mb-sm-0">
                                    <div class="tp-icon-badge tp-badge-purple mr-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                        <i class="ik ik-layers"></i>
                                    </div>
                                    <div class="small">
                                        <strong class="text-dark d-block">{{ __('Member Active Stakings') }}</strong>
                                        <span class="text-muted"><span id="valTotalStakingsCount" class="font-weight-bold text-dark">0</span> total (<span id="valRemainingStakingsCount" class="font-weight-bold text-success">0</span> with remaining capping)</span>
                                    </div>
                                </div>
                                <a href="#memberStakingsSection" class="btn btn-sm btn-outline-primary font-weight-bold px-3 py-1" style="border-radius: 8px;">
                                    <i class="ik ik-arrow-down mr-1"></i> {{ __('View Stakings Table') }}
                                </a>
                            </div>
                        </div>

                        <!-- STATE 4: Not Found / Error State -->
                        <div id="stateNotFound" class="tp-empty-state" style="display: none;">
                            <div class="tp-empty-icon" style="background: #fef2f2; color: #ef4444; box-shadow: 0 6px 16px rgba(239, 68, 68, 0.15);">
                                <i class="ik ik-alert-octagon"></i>
                            </div>
                            <h6 class="font-weight-bold text-danger mb-1">{{ __('Member ID Not Found') }}</h6>
                            <p id="notFoundMsg" class="text-muted small mx-auto mb-0" style="max-width: 360px;">
                                {{ __('Please check the Member ID and try again.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Stakings Breakdown Table Section -->
        <div class="row mt-4" id="memberStakingsSection" style="display: none;">
            <div class="col-12">
                <div class="card tp-hero-card">
                    <div class="tp-card-header">
                        <div class="tp-card-title">
                            <div class="tp-icon-badge tp-badge-purple">
                                <i class="ik ik-layers"></i>
                            </div>
                            <div>
                                <span>{{ __('Member Stakings & Remaining Capping Breakdown') }}</span>
                                <div class="text-muted small font-weight-normal mt-1">
                                    {{ __('Click "Autofill Capping" on any staking below to automatically disburse its specific remaining capping.') }}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span id="stakingsCountBadge" class="badge badge-pill badge-primary px-3 py-2 font-weight-bold" style="font-size: 0.8rem;">
                                0 Stakings Found
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="tableMemberStakings">
                                <thead class="bg-light">
                                    <tr class="small text-muted font-weight-bold text-uppercase">
                                        <th class="border-0 px-4 py-3">{{ __('Staking ID / Order') }}</th>
                                        <th class="border-0 py-3">{{ __('Package') }}</th>
                                        <th class="border-0 py-3 text-center">{{ __('Status') }}</th>
                                        <th class="border-0 py-3">{{ __('Invest Date') }}</th>
                                        <th class="border-0 py-3">{{ __('Invested') }}</th>
                                        <th class="border-0 py-3">{{ __('Trading Capital (70%)') }}</th>
                                        <th class="border-0 py-3">{{ __('Max Cap Limit') }}</th>
                                        <th class="border-0 py-3">{{ __('Profit Paid') }}</th>
                                        <th class="border-0 py-3">{{ __('Remaining Capping') }}</th>
                                        <th class="border-0 py-3" style="width: 140px;">{{ __('Quota Used') }}</th>
                                        <th class="border-0 py-3 text-right pr-4">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyMemberStakings">
                                    <!-- Populated dynamically via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light px-4 py-3 d-flex align-items-center justify-content-between flex-wrap text-muted small">
                        <div>
                            <i class="ik ik-info mr-1 text-primary font-weight-bold"></i>
                            {{ __('Clicking "Autofill Capping" sets that staking\'s remaining capping in the disbursement form and targets that staking on submission.') }}
                        </div>
                        <div class="text-dark font-weight-bold mt-1 mt-md-0">
                            {{ __('General withdrawal across all remaining capping without picking a staking is also supported.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Recent Admin Profit Additions -->
        @if (isset($recentProfits) && $recentProfits->count() > 0)
            <div class="row mt-2">
                <div class="col-12">
                    <div class="card tp-hero-card">
                        <div class="tp-card-header">
                            <div class="tp-card-title">
                                <div class="tp-icon-badge tp-badge-success">
                                    <i class="ik ik-clock"></i>
                                </div>
                                <span>{{ __('Recent Profit Disbursements (Admin)') }}</span>
                            </div>
                            <a href="{{ url('admin/trade/add-trading-profit-details') }}" class="btn btn-sm btn-link font-weight-bold text-primary p-0">
                                {{ __('View Complete History') }} <i class="ik ik-arrow-right ml-1"></i>
                            </a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr class="small text-muted font-weight-bold">
                                            <th class="border-0 px-4 py-3">{{ __('DATE & TIME') }}</th>
                                            <th class="border-0 py-3">{{ __('MEMBER ID') }}</th>
                                            <th class="border-0 py-3">{{ __('MEMBER NAME') }}</th>
                                            <th class="border-0 py-3">{{ __('AMOUNT') }}</th>
                                            <th class="border-0 py-3">{{ __('ADDED BY') }}</th>
                                            <th class="border-0 py-3 text-right pr-4">{{ __('STATUS') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentProfits as $profit)
                                            <tr>
                                                <td class="px-4 py-3 text-dark small font-weight-bold">
                                                    {{ $profit->created_at ? $profit->created_at->format('d M Y, h:i A') : ($profit->date ? $profit->date->format('d M Y') : 'N/A') }}
                                                </td>
                                                <td class="py-3">
                                                    <a href="{{ url('admin/trade/add-trading-profit-details?member_id=' . $profit->memberid) }}" class="font-weight-bold text-primary">
                                                        {{ $profit->memberid }}
                                                    </a>
                                                </td>
                                                <td class="py-3 small text-dark font-weight-bold">
                                                    {{ $profit->member?->name ?? 'N/A' }}
                                                </td>
                                                <td class="py-3">
                                                    <span class="badge badge-success px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 0.85rem;">
                                                        + ${{ number_format((float) $profit->amount, 2) }}
                                                    </span>
                                                </td>
                                                <td class="py-3 small text-muted">
                                                    <i class="ik ik-user-check text-muted mr-1"></i> {{ $profit->added_by }}
                                                </td>
                                                <td class="py-3 text-right pr-4">
                                                    <span class="badge badge-pill badge-success font-weight-bold px-3 py-1">
                                                        {{ $profit->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                var memberSearchTimer = null;
                var activeMemberData = null;
                var activeCappingData = null;
                var activeStakingsList = [];
                var selectedStakingId = 0;
                var selectedStakingObj = null;

                function formatCurrency(val) {
                    var num = parseFloat(val);
                    if (isNaN(num)) num = 0;
                    return '$' + num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }

                function escapeHtml(text) {
                    if (text === null || text === undefined) return '';
                    return $('<div>').text(String(text)).html();
                }

                function getInitials(name) {
                    if (!name) return '--';
                    var parts = name.trim().split(' ');
                    if (parts.length >= 2) {
                        return (parts[0][0] + parts[1][0]).toUpperCase();
                    }
                    return name.substring(0, 2).toUpperCase();
                }

                function showState(state) {
                    $('#stateEmpty').hide();
                    $('#stateLoading').hide();
                    $('#stateDetails').hide();
                    $('#stateNotFound').hide();

                    if (state === 'empty') {
                        $('#stateEmpty').show();
                    } else if (state === 'loading') {
                        $('#stateLoading').show();
                    } else if (state === 'details') {
                        $('#stateDetails').show();
                    } else if (state === 'not_found') {
                        $('#stateNotFound').show();
                    }
                }

                function clearStakingSelection() {
                    selectedStakingId = 0;
                    selectedStakingObj = null;
                    $('#staking_id').val('0');
                    $('#selectedStakingBox').slideUp(200);
                    $('.tp-staking-row').removeClass('tp-staking-selected');
                    $('.btn-autofill-staking').each(function() {
                        $(this).removeClass('is-active').html('<i class="ik ik-arrow-up-right mr-1"></i> Autofill Capping');
                    });
                    if (activeCappingData) {
                        var remaining = parseFloat(activeCappingData.remaining_capping || 0);
                        $('#maxAllowedText').text(formatCurrency(remaining));
                    }
                    validateAmount();
                }

                function renderStakingsTable(stakings, totalCount, remainingCount) {
                    var tbodyHtml = '';
                    if (!stakings || stakings.length === 0) {
                        tbodyHtml = '<tr><td colspan="11" class="text-center py-4 text-muted"><i class="ik ik-inbox mr-1"></i> No staking investments found for this member.</td></tr>';
                        $('#stakingsSummaryCallout').attr('style', 'display: none !important;');
                    } else {
                        $.each(stakings, function(index, stk) {
                            var remCap = parseFloat(stk.remaining_capping || 0);
                            var capPct = parseFloat(stk.percent_used || 0);
                            var pBarColor = capPct >= 90 ? 'bg-danger' : (capPct >= 70 ? 'bg-warning' : 'bg-success');
                            var remBadgeClass = remCap > 0 ? 'text-success font-weight-bold' : 'text-danger font-weight-bold';

                            var statusText = stk.status || 'Active';
                            var isDeactive = (statusText.toLowerCase() === 'deactive' || statusText.toLowerCase() === 'expired' || remCap <= 0);
                            var statusBadgeHtml = '';

                            if (isDeactive) {
                                statusBadgeHtml = '<span class="badge badge-danger px-2 py-1 font-weight-bold" style="border-radius: 6px; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;"><span class="tp-status-dot inactive mr-1" style="background: #ef4444;"></span> Deactive</span>';
                            } else {
                                statusBadgeHtml = '<span class="badge badge-success px-2 py-1 font-weight-bold" style="border-radius: 6px;"><span class="tp-status-dot active mr-1"></span> Active</span>';
                            }

                            var actionButtonHtml = '';
                            if (stk.has_remaining && !isDeactive) {
                                var isThisSelected = (selectedStakingId === parseInt(stk.id, 10));
                                var btnClass = isThisSelected ? 'btn btn-sm btn-autofill-staking is-active font-weight-bold' : 'btn btn-sm btn-outline-primary btn-autofill-staking font-weight-bold';
                                var btnText = isThisSelected ? '<i class="ik ik-check mr-1"></i> Selected' : '<i class="ik ik-arrow-up-right mr-1"></i> Autofill Capping';

                                actionButtonHtml = '<button type="button" class="' + btnClass + '" ' +
                                    'data-staking-id="' + stk.id + '" ' +
                                    'data-remaining="' + remCap.toFixed(2) + '" ' +
                                    'data-package="' + escapeHtml(stk.package) + '" ' +
                                    'data-order="' + escapeHtml(stk.order_id) + '" ' +
                                    'data-invest="' + stk.invest_amount + '">' +
                                    btnText + '</button>';
                            } else {
                                actionButtonHtml = '<span class="badge badge-light border text-danger px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 0.78rem;"><i class="ik ik-x-circle mr-1 text-danger"></i> Deactive (Capped)</span>';
                            }

                            var rowClass = 'tp-staking-row' + (isDeactive ? ' tp-staking-deactive' : '') + (selectedStakingId === parseInt(stk.id, 10) ? ' tp-staking-selected' : '');

                            tbodyHtml += '<tr class="' + rowClass + '" id="stakingRow_' + stk.id + '">' +
                                '<td class="px-4 py-3"><span class="font-weight-bold text-dark">#' + stk.id + '</span><div class="small text-muted font-weight-bold">' + escapeHtml(stk.order_id) + '</div></td>' +
                                '<td class="py-3"><span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="border-radius: 6px;">' + escapeHtml(stk.package) + '</span></td>' +
                                '<td class="py-3 text-center">' + statusBadgeHtml + '</td>' +
                                '<td class="py-3 small text-muted font-weight-bold">' + stk.date + '</td>' +
                                '<td class="py-3 font-weight-bold text-dark">' + formatCurrency(stk.invest_amount) + '</td>' +
                                '<td class="py-3 font-weight-bold text-primary">' + formatCurrency(stk.trading_wallet_amount) + '</td>' +
                                '<td class="py-3 font-weight-bold" style="color: #6d28d9;">' + formatCurrency(stk.total_capping) + '</td>' +
                                '<td class="py-3 small font-weight-bold text-muted">' + formatCurrency(stk.paid_profit) + '</td>' +
                                '<td class="py-3"><span class="' + remBadgeClass + '" style="font-size: 0.95rem;">' + formatCurrency(remCap) + '</span></td>' +
                                '<td class="py-3">' +
                                    '<div class="d-flex align-items-center gap-2">' +
                                        '<div class="progress flex-grow-1" style="height: 6px; border-radius: 999px;">' +
                                            '<div class="progress-bar ' + pBarColor + '" role="progressbar" style="width: ' + capPct + '%;" aria-valuenow="' + capPct + '" aria-valuemin="0" aria-valuemax="100"></div>' +
                                        '</div>' +
                                        '<span class="small text-muted font-weight-bold ml-2">' + capPct.toFixed(0) + '%</span>' +
                                    '</div>' +
                                '</td>' +
                                '<td class="py-3 text-right pr-4">' + actionButtonHtml + '</td>' +
                            '</tr>';
                        });

                        $('#stakingsCountBadge').text(totalCount + ' Staking(s) Found (' + remainingCount + ' with Cap Room)');
                        $('#valTotalStakingsCount').text(totalCount);
                        $('#valRemainingStakingsCount').text(remainingCount);
                        $('#stakingsSummaryCallout').removeAttr('style').attr('style', 'border-radius: 10px; border-color: #e2e8f0;');
                    }

                    $('#tbodyMemberStakings').html(tbodyHtml);
                    $('#memberStakingsSection').show();
                }

                function fetchMemberCapping(memberid) {
                    memberid = $.trim(memberid);
                    if (!memberid) {
                        activeMemberData = null;
                        activeCappingData = null;
                        activeStakingsList = [];
                        clearStakingSelection();
                        $('#memMsg').html('');
                        $('#maxAllowedBadge').hide();
                        $('#btnSetMax').hide();
                        $('#impactPreviewBox').hide();
                        $('#amountOverLimitAlert').hide();
                        $('#btnSubmitProfit').prop('disabled', false);
                        $('#memberStakingsSection').hide();
                        $('#tbodyMemberStakings').empty();
                        $('#stakingsSummaryCallout').attr('style', 'display: none !important;');
                        showState('empty');
                        return;
                    }

                    showState('loading');
                    $('#memberSearchSpinner').show();
                    $('#memberStakingsSection').hide();

                    var csrf = $('#csrf').val();

                    $.ajax({
                        url: '{{ route("admin.getMemberCapping") }}',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            memberid: memberid,
                            _token: csrf
                        },
                        success: function(res) {
                            $('#memberSearchSpinner').hide();

                            if (res && res.status && res.member && res.capping) {
                                activeMemberData = res.member;
                                activeCappingData = res.capping;
                                activeStakingsList = res.stakings || [];

                                clearStakingSelection();

                                // Update memMsg
                                $('#memMsg').html('<span class="text-success"><i class="ik ik-check-circle mr-1"></i> ' + escapeHtml(res.member.name) + ' (' + escapeHtml(res.member.memberid) + ')</span>');

                                // Populate details card
                                $('#userAvatar').text(getInitials(res.member.name));
                                $('#userName').text(res.member.name);
                                $('#userMemberId').text(res.member.memberid);

                                if (res.member.email && res.member.email !== 'N/A') {
                                    $('#userEmail').text(res.member.email);
                                    $('#userEmailSpan').show();
                                } else {
                                    $('#userEmailSpan').hide();
                                }

                                var statusText = res.member.status || 'Active';
                                $('#userStatusBadge').html('<span class="tp-status-dot ' + (statusText.toLowerCase() === 'active' ? 'active' : 'inactive') + ' mr-1"></span> ' + statusText);
                                if (statusText.toLowerCase() === 'active') {
                                    $('#userStatusBadge').removeClass('badge-warning').addClass('badge-success');
                                } else {
                                    $('#userStatusBadge').removeClass('badge-success').addClass('badge-warning');
                                }

                                $('#userPackageBadge').text(res.member.package_name || 'Standard');

                                // Balances
                                $('#valTradingWallet').text(formatCurrency(res.capping.trading_wallet));
                                $('#valMainWallet').text(formatCurrency(res.capping.wallet));
                                $('#valTotalCapping').text(formatCurrency(res.capping.total_capping));
                                $('#valCappingRatioSub').text((res.capping.capping_percent || 300) + '% of Trading Wallet');

                                var remaining = parseFloat(res.capping.remaining_capping || 0);
                                $('#valRemainingCapping').text(formatCurrency(remaining));

                                // Max Allowed Badge in Form
                                $('#maxAllowedText').text(formatCurrency(remaining));
                                $('#maxAllowedBadge').show();
                                $('#btnSetMax').show();

                                // Progress bar
                                var percentUsed = parseFloat(res.capping.percent_used || 0);
                                if (isNaN(percentUsed)) percentUsed = 0;
                                if (percentUsed > 100) percentUsed = 100;

                                $('#cappingPercentText').text(percentUsed.toFixed(1) + '%');
                                var $pBar = $('#cappingProgressBar');
                                $pBar.css('width', percentUsed + '%');
                                $pBar.removeClass('warning danger');

                                if (percentUsed >= 90) {
                                    $pBar.addClass('danger');
                                } else if (percentUsed >= 70) {
                                    $pBar.addClass('warning');
                                }

                                $('#valAchievedProfit').text(formatCurrency(res.capping.achieved_profit));
                                $('#valCeilingProfit').text(formatCurrency(res.capping.total_capping));

                                // Eligibility banner
                                var $banner = $('#eligibilityBanner');
                                var $bannerIcon = $('#eligibilityIcon');
                                var $bannerText = $('#eligibilityText');

                                if (remaining < 1.0) {
                                    $banner.css({
                                        'background': '#fef2f2',
                                        'border-color': '#fecaca',
                                        'color': '#991b1b'
                                    });
                                    $bannerIcon.attr('class', 'ik ik-alert-octagon mr-2 text-danger');
                                    $bannerText.html('<strong>Capping Limit Reached!</strong> This member has already received their total allowable profit (' + formatCurrency(res.capping.total_capping) + '). No more profit can be added.');
                                    $('#valRemainingCapping').removeClass('text-warning text-success').addClass('text-danger');
                                    $('#btnSubmitProfit').prop('disabled', true);
                                } else {
                                    $banner.css({
                                        'background': '#ecfdf5',
                                        'border-color': '#a7f3d0',
                                        'color': '#065f46'
                                    });
                                    $bannerIcon.attr('class', 'ik ik-check-circle mr-2 text-success');
                                    $bannerText.html('<strong>Eligible for Profit:</strong> You can add up to <strong>' + formatCurrency(remaining) + '</strong> to this member\'s account.');
                                    $('#valRemainingCapping').removeClass('text-danger text-warning').addClass('text-success');
                                    $('#btnSubmitProfit').prop('disabled', false);
                                }

                                // Render Stakings Breakdown Table
                                renderStakingsTable(res.stakings, res.stakings_count, res.remaining_stakings_count);

                                showState('details');
                                validateAmount();
                            } else {
                                activeMemberData = null;
                                activeCappingData = null;
                                activeStakingsList = [];
                                clearStakingSelection();
                                $('#memMsg').html('<span class="text-danger"><i class="ik ik-x-circle mr-1"></i> Member ID not found</span>');
                                $('#notFoundMsg').text('No member found with ID: ' + memberid);
                                $('#maxAllowedBadge').hide();
                                $('#btnSetMax').hide();
                                $('#impactPreviewBox').hide();
                                $('#amountOverLimitAlert').hide();
                                $('#memberStakingsSection').hide();
                                $('#tbodyMemberStakings').empty();
                                $('#stakingsSummaryCallout').attr('style', 'display: none !important;');
                                showState('not_found');
                            }
                        },
                        error: function(xhr) {
                            $('#memberSearchSpinner').hide();
                            activeMemberData = null;
                            activeCappingData = null;
                            activeStakingsList = [];
                            clearStakingSelection();
                            var errMsg = 'No member found with ID: ' + memberid;
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errMsg = xhr.responseJSON.message;
                            }
                            $('#memMsg').html('<span class="text-danger"><i class="ik ik-x-circle mr-1"></i> ' + escapeHtml(errMsg) + '</span>');
                            $('#notFoundMsg').text(errMsg);
                            $('#maxAllowedBadge').hide();
                            $('#btnSetMax').hide();
                            $('#impactPreviewBox').hide();
                            $('#amountOverLimitAlert').hide();
                            $('#memberStakingsSection').hide();
                            $('#tbodyMemberStakings').empty();
                            $('#stakingsSummaryCallout').attr('style', 'display: none !important;');
                            showState('not_found');
                        }
                    });
                }

                function validateAmount() {
                    var enteredAmt = parseFloat($('#amount').val());
                    if (isNaN(enteredAmt) || enteredAmt <= 0) {
                        $('#impactPreviewBox').hide();
                        $('#amountOverLimitAlert').hide();
                        $('#amount').removeClass('is-invalid');
                        return true;
                    }

                    if (activeCappingData) {
                        var memberRemaining = parseFloat(activeCappingData.remaining_capping || 0);
                        var currentWallet = parseFloat(activeCappingData.wallet || 0);

                        // If a specific staking is selected, ensure entered amount does not exceed that staking's remaining capping
                        if (selectedStakingId > 0 && selectedStakingObj) {
                            var stkRemaining = selectedStakingObj.remaining;
                            if (enteredAmt > stkRemaining) {
                                $('#amountOverLimitAlert').show();
                                $('#amountOverLimitAlert span').html('Amount ($' + enteredAmt.toFixed(2) + ') exceeds remaining capping ($' + stkRemaining.toFixed(2) + ') of Staking #' + selectedStakingId + '. <a href="javascript:void(0)" id="btnFixMax" class="font-weight-bold text-danger text-decoration-underline ml-1">Set to max</a>');
                                $('#amount').addClass('is-invalid');
                                $('#btnSubmitProfit').prop('disabled', true);
                                return false;
                            }
                        }

                        if (memberRemaining > 0 && enteredAmt > memberRemaining) {
                            $('#amountOverLimitAlert').show();
                            $('#amountOverLimitAlert span').html('Amount ($' + enteredAmt.toFixed(2) + ') exceeds member total capping balance ($' + memberRemaining.toFixed(2) + '). <a href="javascript:void(0)" id="btnFixMax" class="font-weight-bold text-danger text-decoration-underline ml-1">Set to max</a>');
                            $('#amount').addClass('is-invalid');
                            $('#btnSubmitProfit').prop('disabled', true);
                            return false;
                        }

                        $('#amountOverLimitAlert').hide();
                        $('#amount').removeClass('is-invalid');
                        if (memberRemaining >= 0.01) {
                            $('#btnSubmitProfit').prop('disabled', false);
                        }

                        // Preview box
                        var newWallet = currentWallet + enteredAmt;
                        var newRemaining = Math.max(0, memberRemaining - enteredAmt);
                        $('#previewNewWallet').text(formatCurrency(newWallet));
                        $('#previewNewRemaining').text(formatCurrency(newRemaining));
                        $('#impactPreviewBox').show();
                    }
                    return true;
                }

                // Debounced member lookup
                $('#memberid').on('input', function() {
                    var val = $(this).val();
                    clearTimeout(memberSearchTimer);
                    memberSearchTimer = setTimeout(function() {
                        fetchMemberCapping(val);
                    }, 300);
                });

                // Amount input listener
                $('#amount').on('input change', function() {
                    validateAmount();
                });

                // Autofill Action Button click on Staking Row
                $(document).on('click', '.btn-autofill-staking', function(e) {
                    e.preventDefault();
                    var stkId = parseInt($(this).data('staking-id'), 10);
                    var rem = parseFloat($(this).data('remaining') || 0);
                    var pkg = $(this).data('package') || 'Package';
                    var order = $(this).data('order') || ('STK#' + stkId);

                    selectedStakingId = stkId;
                    selectedStakingObj = {
                        id: stkId,
                        remaining: rem,
                        package: pkg,
                        order: order
                    };

                    // Set form inputs
                    $('#staking_id').val(stkId);
                    $('#amount').val(rem.toFixed(2)).trigger('input');

                    // Update selected staking callout in form
                    $('#selectedStakingTitle').text('Staking #' + stkId + ' (' + order + ')');
                    $('#selectedStakingPackage').text(pkg);
                    $('#selectedStakingRemaining').text(formatCurrency(rem));
                    $('#maxAllowedText').text(formatCurrency(rem) + ' (Staking #' + stkId + ')');
                    $('#selectedStakingBox').slideDown(200);

                    // Update row visual highlights
                    $('.tp-staking-row').removeClass('tp-staking-selected');
                    $('#stakingRow_' + stkId).addClass('tp-staking-selected');

                    $('.btn-autofill-staking').each(function() {
                        var thisId = parseInt($(this).data('staking-id'), 10);
                        if (thisId === stkId) {
                            $(this).addClass('is-active').html('<i class="ik ik-check mr-1"></i> Selected');
                        } else {
                            $(this).removeClass('is-active').html('<i class="ik ik-arrow-up-right mr-1"></i> Autofill Capping');
                        }
                    });

                    // Smooth scroll to form on mobile or large views
                    if ($(window).scrollTop() > $('#tradingProfitForm').offset().top) {
                        $('html, body').animate({
                            scrollTop: $('#tradingProfitForm').offset().top - 90
                        }, 400);
                    }

                    validateAmount();
                });

                // Clear Staking Selection handler
                $('#btnClearStakingSelection').on('click', function(e) {
                    e.preventDefault();
                    clearStakingSelection();
                });

                // Quick Amount buttons
                $('.quick-amt').on('click', function(e) {
                    e.preventDefault();
                    var addVal = parseFloat($(this).data('amt'));
                    var current = parseFloat($('#amount').val()) || 0;
                    var finalVal = (current + addVal).toFixed(2);

                    var ceiling = 0;
                    if (selectedStakingId > 0 && selectedStakingObj) {
                        ceiling = selectedStakingObj.remaining;
                    } else if (activeCappingData) {
                        ceiling = parseFloat(activeCappingData.remaining_capping || 0);
                    }

                    if (ceiling > 0 && parseFloat(finalVal) > ceiling) {
                        finalVal = ceiling.toFixed(2);
                    }
                    $('#amount').val(finalVal).trigger('input');
                });

                // Max Cap button
                $('#btnSetMax, $(document)').on('click', '#btnSetMax, #btnFixMax', function(e) {
                    e.preventDefault();
                    if (selectedStakingId > 0 && selectedStakingObj) {
                        $('#amount').val(selectedStakingObj.remaining.toFixed(2)).trigger('input');
                    } else if (activeCappingData) {
                        var remaining = parseFloat(activeCappingData.remaining_capping || 0);
                        if (remaining > 0) {
                            $('#amount').val(remaining.toFixed(2)).trigger('input');
                        }
                    }
                });

                // Reset Form handler
                $('#btnResetForm').on('click', function() {
                    activeMemberData = null;
                    activeCappingData = null;
                    activeStakingsList = [];
                    clearStakingSelection();
                    $('#memMsg').html('');
                    $('#maxAllowedBadge').hide();
                    $('#btnSetMax').hide();
                    $('#impactPreviewBox').hide();
                    $('#amountOverLimitAlert').hide();
                    $('#btnSubmitProfit').prop('disabled', false);
                    $('#memberStakingsSection').hide();
                    $('#tbodyMemberStakings').empty();
                    $('#stakingsSummaryCallout').attr('style', 'display: none !important;');
                    showState('empty');
                });

                // Auto-trigger on page load if memberid has value (e.g. from validation redirect)
                var initialMember = $.trim($('#memberid').val());
                if (initialMember) {
                    fetchMemberCapping(initialMember);
                }
            });
        </script>
    @endpush
@endsection
