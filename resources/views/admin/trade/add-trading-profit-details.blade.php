@extends('admin.layouts.main')
@section('title', 'Add Trading Profit Details')
@section('content')
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
                0% {
                    opacity: 0.6;
                }

                50% {
                    opacity: 1;
                }

                100% {
                    opacity: 0.6;
                }
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-edit bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Add Trading Profit Details') }}</h5>
                            <span>{{ __('All Add Trading Profit Details') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Admin') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ __('Add Add Trading Profit Details') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                @include('admin.partials.history-date-filter', ['showMemberIdFilter' => true])

                @if (session()->has('failedMsg'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('failedMsg') }}
                    </div>
                @endif
                @if (session()->has('successMsg'))
                    <div class="alert alert-success" role="alert">
                        {{ session('successMsg') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h3>{{ __('Add Add Trading Profit Details') }}</h3>
                        <span class="ml-auto mr-2"><a href="{{ url('hdgteyusjasget/trade/add-trading-profit') }}"
                                class="btn btn-sm btn-success text-white"> + Add Trading Profit</a></span>
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
                                        @foreach ($data as $profit)
                                            <tr>
                                                <td class="px-4 py-3 text-dark small font-weight-bold">
                                                    {{ $profit->created_at ? $profit->created_at->format('d M Y, h:i A') : ($profit->date ? $profit->date->format('d M Y') : 'N/A') }}
                                                </td>
                                                <td class="py-3">
                                                    <a href="{{ url('hdgteyusjasget/trade/add-trading-profit-details?member_id=' . $profit->memberid) }}" class="font-weight-bold text-primary">
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
                    {{-- <div class="card-body px-5 mx-2" style="overflow-x: auto;">
                        <table id="data_table" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('S.No') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Memberid') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Added By') }}</th>
                                    <th>{{ __('Status') }}</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1;  @endphp
                                @foreach ($data as $list)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ date('Y-m-d', strtotime($list['created_at'])) }}</td>
                                        <td>{{ $list['memberid'] }}</td>
                                        <td>$ {{ $list['amount'] }}</td>
                                        <td>
                                            {{ $list['added_by'] }}
                                        </td>
                                        <td>
                                            <div class="badge badge-success">
                                                {{ $list['status'] }}
                                            </div>
                                        </td>
                                    </tr>
                                    @php $i++;  @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="{{ asset('adm_assets/assets/plugins/DataTables/datatables.min.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/datatables.js') }}"></script>
        <script src="{{ asset('adm_assets/assets/js/form-components.js') }}"></script>
    @endpush
@endsection
