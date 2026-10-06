@extends('admin.layouts.main')
@section('title', 'Trading Wallet Control')

@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-pocket bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Trading Wallet Control') }}</h5>
                            <span>{{ __('Manage member trading wallet lock periods and post-lock withdrawal limits by registration date') }}</span>
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
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Trading Wallet Control') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session()->has('successMsg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ik ik-check-circle mr-2"></i> {{ session('successMsg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if (session()->has('failedMsg'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ik ik-alert-triangle mr-2"></i> {{ session('failedMsg') }}
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

        {{-- Step 1: Date Range Filter Card --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 font-weight-bold text-dark">
                    <i class="ik ik-calendar mr-2 text-primary"></i>{{ __('Step 1: Select Package Activation Date Range') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center mb-3">
                    <span class="small font-weight-bold text-muted mr-2">{{ __('Quick Date Presets:') }}</span>
                    <button type="button" class="btn btn-xs btn-outline-primary mr-1 btnQuickDate" data-preset="today">
                        <i class="ik ik-calendar mr-1"></i>{{ __('Today') }}
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-secondary mr-1 btnQuickDate" data-preset="yesterday">
                        {{ __('Yesterday') }}
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-secondary mr-1 btnQuickDate" data-preset="week">
                        {{ __('Last 7 Days') }}
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-secondary btnQuickDate" data-preset="month">
                        {{ __('This Month') }}
                    </button>
                </div>
                <form id="dateFilterForm" method="GET" action="{{ route('admin.tradingWalletControl') }}">
                    <div class="row align-items-end">
                        <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                            <label for="date_from" class="font-weight-bold">
                                {{ __('Package Date From') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ik ik-calendar"></i></span>
                                </div>
                                <input type="date"
                                    class="form-control"
                                    id="date_from"
                                    name="date_from"
                                    value="{{ old('date_from', $dateFrom ?? '') }}"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                            <label for="date_to" class="font-weight-bold">
                                {{ __('Package Date To') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ik ik-calendar"></i></span>
                                </div>
                                <input type="date"
                                    class="form-control"
                                    id="date_to"
                                    name="date_to"
                                    value="{{ old('date_to', $dateTo ?? '') }}"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12">
                            <button type="button" class="btn btn-primary btn-block font-weight-bold" id="btnFilterMembers">
                                <i class="ik ik-search mr-1"></i> {{ __('Fetch Matching Package Members') }}
                            </button>
                        </div>
                    </div>
                </form>
                <div id="filterStatusMsg" class="mt-2"></div>
            </div>
        </div>

        {{-- Bulk Application Form --}}
        <form action="{{ route('admin.applyTradingWalletControl') }}" method="POST" id="applyControlForm">
            @csrf
            <input type="hidden" name="date_from" id="hidden_date_from" value="{{ $dateFrom ?? '' }}">
            <input type="hidden" name="date_to" id="hidden_date_to" value="{{ $dateTo ?? '' }}">

            <div class="row">
                {{-- Left Side: Rule Settings (Lock Days & Max Withdrawal %) --}}
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header bg-primary text-white">
                            <h5 class="text-white mb-0 font-weight-bold">
                                <i class="ik ik-sliders mr-2"></i>{{ __('Step 2: Set Trading Wallet Rules') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            {{-- Active Current Setting Badge --}}
                            <div class="alert alert-info py-2 px-3 mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-uppercase font-weight-bold d-block text-muted" style="font-size: 10px; letter-spacing: 0.5px;">{{ __('Active Default Setting') }}</small>
                                    <strong class="text-primary">{{ $setting->lock_days ?? 30 }} {{ __('Days Lock') }}</strong> &bull; <strong>{{ number_format((float) ($setting->withdrawal_percent ?? 100), 1) }}% {{ __('Max Withdr.') }}</strong>
                                </div>
                                <span class="badge badge-success font-weight-bold px-2 py-1"><i class="ik ik-check-circle mr-1"></i>{{ __('Live') }}</span>
                            </div>

                            {{-- Condition A: Lock Period --}}
                            <div class="p-3 mb-3 rounded" style="background-color: #fff8e1; border: 1px solid #ffe082;">
                                <h6 class="font-weight-bold text-dark mb-1">
                                    <i class="ik ik-lock mr-1 text-warning"></i> {{ __('Condition A: Lock Period') }}
                                </h6>
                                <p class="small text-muted mb-2">
                                    {{ __('During this period, withdrawal is completely blocked (0%) and next package purchase is locked. Applies to Package Details and verified Member Details.') }}
                                </p>
                                <div class="form-group mb-0">
                                    <label for="lock_days" class="font-weight-bold">
                                        {{ __('Lock Period (Days)') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" min="0" step="1"
                                            class="form-control font-weight-bold @error('lock_days') is-invalid @enderror"
                                            id="lock_days"
                                            name="lock_days"
                                            value="{{ old('lock_days', $setting->lock_days ?? 30) }}"
                                            placeholder="e.g. 30"
                                            required>
                                        <div class="input-group-append">
                                            <span class="input-group-text font-weight-bold">{{ __('Days') }}</span>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ __('Example: 30 Days = Locked for 30 days. Day 31 unlocks.') }}
                                    </small>
                                </div>
                            </div>

                            {{-- Condition B: Maximum Withdrawal Percentage --}}
                            <div class="p-3 mb-3 rounded" style="background-color: #e8f5e9; border: 1px solid #c8e6c9;">
                                <h6 class="font-weight-bold text-dark mb-1">
                                    <i class="ik ik-percent mr-1 text-success"></i> {{ __('Condition B: Post-Lock Withdrawal %') }}
                                </h6>
                                <p class="small text-muted mb-2">
                                    {{ __('Becomes active ONLY after the lock period has expired. Limits max withdrawable % of Trading Wallet.') }}
                                </p>
                                <div class="form-group mb-0">
                                    <label for="withdrawal_percent" class="font-weight-bold">
                                        {{ __('Maximum Withdrawal Allowed (%)') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100"
                                            class="form-control font-weight-bold @error('withdrawal_percent') is-invalid @enderror"
                                            id="withdrawal_percent"
                                            name="withdrawal_percent"
                                            value="{{ old('withdrawal_percent', $setting->withdrawal_percent ?? 100.00) }}"
                                            placeholder="e.g. 50"
                                            required>
                                        <div class="input-group-append">
                                            <span class="input-group-text font-weight-bold">%</span>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">
                                        {{ __('Example: 50% = Member can withdraw at most 50% of Trading Wallet after lock expires.') }}
                                    </small>
                                </div>
                            </div>

                            {{-- Summary Preview Card --}}
                            <div class="card bg-light border-0 p-3 mb-3">
                                <h6 class="font-weight-bold text-dark mb-2">
                                    <i class="ik ik-info mr-1"></i>{{ __('Rule Logic Preview') }}
                                </h6>
                                <div class="small" id="rulePreviewText">
                                    <div class="mb-1">
                                        <span class="badge badge-warning text-dark font-weight-bold">Days 1 to <span class="dynLockDays">30</span></span>:
                                        <strong class="text-danger">0% Withdrawal + Package Locked</strong>
                                    </div>
                                    <div>
                                        <span class="badge badge-success font-weight-bold">From Day <span class="dynUnlockDay">31</span>+</span>:
                                        <strong class="text-success">Max <span class="dynPercent">100</span>%</strong> Withdrawal & Next Package Allowed
                                    </div>
                                </div>
                            </div>

                            {{-- Action 1: Apply to Selected Members (Date Range) --}}
                            <button type="submit" name="action" value="apply_selected" class="btn btn-success btn-lg btn-block font-weight-bold shadow-sm" id="btnApplyControl">
                                <i class="ik ik-check-circle mr-1"></i> {{ __('Apply to Selected Members') }}
                            </button>
                            <small class="text-muted d-block text-center mt-1 mb-3">
                                <span id="selectedCountBadge" class="font-weight-bold text-primary">0</span> {{ __('package entry/entries selected') }}
                            </small>

                            {{-- Action 2: Save as Current Default Setting (Applies to Today & New Entries) --}}
                            <button type="submit" name="action" value="save_current_setting" class="btn btn-outline-primary btn-block font-weight-bold" id="btnSaveCurrentSetting">
                                <i class="ik ik-save mr-1"></i> {{ __('Save Current Setting (Today & New Entries)') }}
                            </button>
                            <small class="text-muted d-block text-center mt-1" style="font-size: 11px;">
                                {{ __('Updates current setting for today\'s entries. Older dates remain unchanged.') }}
                            </small>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Matching Members Table with Check All --}}
                <div class="col-lg-8 col-md-12 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap border-bottom">
                            <div>
                                <h5 class="mb-0 font-weight-bold text-dark">
                                    <i class="ik ik-users mr-2 text-primary"></i>{{ __('Matching Package Members (Package Details + Member Details)') }}
                                </h5>
                                <small class="text-muted" id="tableSubheader">
                                    @if (!empty($dateFrom) && !empty($dateTo))
                                        {{ __('Showing package activations from') }} <strong>{{ $dateFrom }}</strong> {{ __('to') }} <strong>{{ $dateTo }}</strong> ({{ (isset($packages) ? $packages->count() : (isset($members) ? $members->count() : 0)) }} found)
                                    @else
                                        {{ __('Please select a date range above and click "Fetch Matching Package Members".') }}
                                    @endif
                                </small>
                            </div>
                            <div class="mt-2 mt-sm-0">
                                <button type="button" class="btn btn-sm btn-outline-primary mr-1" id="btnSelectAll">
                                    <i class="ik ik-check-square"></i> Check All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnUnselectAll">
                                    <i class="ik ik-square"></i> Uncheck All
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0" id="membersTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 45px;" class="text-center">
                                                <input type="checkbox" id="checkAll" style="cursor: pointer; transform: scale(1.2);">
                                            </th>
                                            <th>{{ __('Member ID') }}</th>
                                            <th>{{ __('Member Name') }}</th>
                                            <th>{{ __('Package Details') }}</th>
                                            <th>{{ __('Package Date') }}</th>
                                            <th>{{ __('Trading Wallet') }}</th>
                                            <th>{{ __('Current Status') }}</th>
                                            <th>{{ __('Max Withdr. %') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="membersTableBody">
                                        @php
                                            $displayItems = isset($packages) && $packages->count() > 0 ? $packages : ($members ?? collect());
                                        @endphp
                                        @forelse ($displayItems as $item)
                                            @php
                                                $pkg = $item instanceof \App\Models\PackageDetail ? $item : ($item->latestPackageDetail ?? null);
                                                $mem = $item instanceof \App\Models\MemberDetail ? $item : ($item->member ?? null);
                                                $isLocked = ($pkg && $pkg->isLocked()) || ($mem && $mem->isTradingWalletLocked());
                                                $remDays = max($pkg ? $pkg->remainingLockDays() : 0, $mem ? $mem->tradingWalletRemainingLockDays() : 0);
                                                $lockedUntil = ($pkg && $pkg->locked_until) ? $pkg->locked_until : ($mem ? $mem->trading_wallet_locked_until : null);
                                                $maxWithdrawable = $mem ? $mem->tradingWalletMaxWithdrawable() : 0.00;
                                                $pkgId = $pkg ? $pkg->id : ($item->id ?? 0);
                                                $memberId = $mem ? $mem->memberid : ($pkg ? $pkg->memberid : $item->memberid);
                                                $memberName = $mem ? $mem->name : 'N/A';
                                                $mobile = $mem ? $mem->mobile : '';
                                                $pkgType = $pkg ? $pkg->package_type : 'Account Activation';
                                                $pkgVal = $pkg ? (float) ($pkg->package_value ?? 0) : 0.00;
                                                $pkgDate = $pkg && $pkg->created_at ? $pkg->created_at->format('d M Y') : ($item->created_at ? $item->created_at->format('d M Y') : 'N/A');
                                                $tradingBal = $mem ? (float) ($mem->p2p_wallet ?? $mem->trading_wallet ?? 0.00) : 0.00;
                                                $withPercent = $mem ? (float) ($mem->trading_wallet_withdrawal_percent ?? 100.00) : 100.00;
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox"
                                                        name="selected_packages[]"
                                                        value="{{ $pkgId }}"
                                                        class="member-checkbox package-checkbox"
                                                        checked
                                                        style="cursor: pointer; transform: scale(1.15);">
                                                    <input type="hidden" name="selected_members[]" value="{{ $memberId }}">
                                                </td>
                                                <td>
                                                    <strong class="text-primary">{{ $memberId }}</strong>
                                                    @if($mobile)
                                                        <div class="small text-muted" style="font-size: 11px;">{{ $mobile }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold text-dark">{{ $memberName }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info font-weight-bold">{{ $pkgType }}</span>
                                                    <div class="small font-weight-bold text-dark mt-1">${{ number_format($pkgVal, 2) }}</div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        <i class="ik ik-calendar mr-1"></i>
                                                        {{ $pkgDate }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold text-dark">
                                                        ${{ number_format($tradingBal, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($isLocked)
                                                        <span class="badge badge-warning text-dark font-weight-bold"
                                                            title="Locked until {{ $lockedUntil ? $lockedUntil->format('d M Y') : '' }}">
                                                            <i class="ik ik-lock mr-1"></i>Locked ({{ $remDays }}d left)
                                                        </span>
                                                    @else
                                                        <span class="badge badge-success font-weight-bold">
                                                            <i class="ik ik-unlock mr-1"></i>Unlocked
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-primary font-weight-bold">
                                                        {{ number_format($withPercent, 1) }}%
                                                    </span>
                                                    <div class="small text-muted" style="font-size: 11px;">
                                                        Max: ${{ number_format($maxWithdrawable, 2) }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr id="emptyTableRow">
                                                <td colspan="8" class="text-center py-5 text-muted">
                                                    <i class="ik ik-package ik-2x mb-2 d-block text-secondary"></i>
                                                    @if (!empty($dateFrom) && !empty($dateTo))
                                                        <span class="font-weight-bold text-dark">{{ __('No package entries found between these dates.') }}</span>
                                                        <div class="small text-muted mt-1">{{ __('Try expanding the package date range.') }}</div>
                                                    @else
                                                        <span class="font-weight-bold text-dark">{{ __('No date range selected yet.') }}</span>
                                                        <div class="small text-muted mt-1">{{ __('Please pick a Package Date From and To above, then click "Fetch Matching Package Members".') }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                function updateSelectedCount() {
                    var total = $('.member-checkbox').length;
                    var checked = $('.member-checkbox:checked').length;
                    $('#selectedCountBadge').text(checked + ' of ' + total);

                    if (total > 0 && checked === total) {
                        $('#checkAll').prop('checked', true);
                        $('#checkAll').prop('indeterminate', false);
                    } else if (checked > 0) {
                        $('#checkAll').prop('checked', false);
                        $('#checkAll').prop('indeterminate', true);
                    } else {
                        $('#checkAll').prop('checked', false);
                        $('#checkAll').prop('indeterminate', false);
                    }

                    if (checked === 0) {
                        $('#btnApplyControl').prop('disabled', true).addClass('disabled');
                    } else {
                        $('#btnApplyControl').prop('disabled', false).removeClass('disabled');
                    }
                }

                function updateRulePreview() {
                    var lockDays = parseInt($('#lock_days').val()) || 0;
                    var percent = parseFloat($('#withdrawal_percent').val()) || 0;
                    if (percent > 100) percent = 100;
                    if (percent < 0) percent = 0;

                    $('.dynLockDays').text(lockDays);
                    $('.dynUnlockDay').text(lockDays + 1);
                    $('.dynPercent').text(percent);
                }

                // Check All toggle
                $('#checkAll').on('change', function() {
                    var isChecked = $(this).is(':checked');
                    $('.member-checkbox').prop('checked', isChecked);
                    updateSelectedCount();
                });

                $('#btnSelectAll').on('click', function() {
                    $('.member-checkbox').prop('checked', true);
                    $('#checkAll').prop('checked', true);
                    updateSelectedCount();
                });

                $('#btnUnselectAll').on('click', function() {
                    $('.member-checkbox').prop('checked', false);
                    $('#checkAll').prop('checked', false);
                    updateSelectedCount();
                });

                $(document).on('change', '.member-checkbox', function() {
                    updateSelectedCount();
                });

                $('#lock_days, #withdrawal_percent').on('input change', function() {
                    updateRulePreview();
                });

                // Quick Date Presets
                $('.btnQuickDate').on('click', function() {
                    var preset = $(this).data('preset');
                    var now = new Date();
                    var pad = function(n) { return (n < 10 ? '0' : '') + n; };
                    var format = function(d) {
                        return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
                    };

                    var fromStr = '';
                    var toStr = format(now);

                    if (preset === 'today') {
                        fromStr = toStr;
                    } else if (preset === 'yesterday') {
                        var y = new Date(now);
                        y.setDate(y.getDate() - 1);
                        fromStr = format(y);
                        toStr = fromStr;
                    } else if (preset === 'week') {
                        var w = new Date(now);
                        w.setDate(w.getDate() - 6);
                        fromStr = format(w);
                    } else if (preset === 'month') {
                        var m = new Date(now.getFullYear(), now.getMonth(), 1);
                        fromStr = format(m);
                    }

                    $('#date_from').val(fromStr);
                    $('#date_to').val(toStr);
                    $('#btnFilterMembers').trigger('click');
                });

                // AJAX date filter button
                $('#btnFilterMembers').on('click', function() {
                    var dateFrom = $('#date_from').val();
                    var dateTo = $('#date_to').val();

                    if (!dateFrom || !dateTo) {
                        $('#filterStatusMsg').html(
                            '<div class="alert alert-warning py-2 px-3 mb-0">' +
                            '<i class="ik ik-alert-circle mr-1"></i> Please select both Package Date From and To.' +
                            '</div>'
                        );
                        return;
                    }

                    if (dateTo < dateFrom) {
                        $('#filterStatusMsg').html(
                            '<div class="alert alert-danger py-2 px-3 mb-0">' +
                            '<i class="ik ik-alert-triangle mr-1"></i> Package Date To must be on or after Package Date From.' +
                            '</div>'
                        );
                        return;
                    }

                    $('#hidden_date_from').val(dateFrom);
                    $('#hidden_date_to').val(dateTo);

                    $('#filterStatusMsg').html(
                        '<div class="text-muted small py-1">' +
                        '<i class="ik ik-refresh-cw ik-spin mr-1"></i> Querying package activations from ' + dateFrom + ' to ' + dateTo + '...' +
                        '</div>'
                    );

                    $('#btnFilterMembers').prop('disabled', true);

                    $.ajax({
                        url: '{{ route("admin.filterTradingMembers") }}',
                        type: 'POST',
                        data: {
                            date_from: dateFrom,
                            date_to: dateTo,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#btnFilterMembers').prop('disabled', false);
                            if (response.success) {
                                var count = response.count;
                                $('#filterStatusMsg').html(
                                    '<div class="alert alert-success py-2 px-3 mb-0">' +
                                    '<i class="ik ik-check-circle mr-1"></i> ' + response.message +
                                    '</div>'
                                );

                                $('#tableSubheader').html(
                                    'Showing package activations from <strong>' + dateFrom + '</strong> to <strong>' + dateTo + '</strong> (' + count + ' found)'
                                );

                                if (count === 0) {
                                    $('#membersTableBody').html(
                                        '<tr id="emptyTableRow">' +
                                        '<td colspan="8" class="text-center py-5 text-muted">' +
                                        '<i class="ik ik-package ik-2x mb-2 d-block text-secondary"></i>' +
                                        '<span class="font-weight-bold text-dark">No package entries found between ' + dateFrom + ' and ' + dateTo + '.</span>' +
                                        '<div class="small text-muted mt-1">Try expanding the date range.</div>' +
                                        '</td>' +
                                        '</tr>'
                                    );
                                } else {
                                    var rowsHtml = '';
                                    var items = response.packages || response.members || [];
                                    items.forEach(function(m) {
                                        var statusBadge = m.is_locked
                                            ? '<span class="badge badge-warning text-dark font-weight-bold" title="Locked until ' + (m.locked_until || '') + '">' +
                                              '<i class="ik ik-lock mr-1"></i>Locked (' + m.remaining_lock_days + 'd left)</span>'
                                            : '<span class="badge badge-success font-weight-bold">' +
                                              '<i class="ik ik-unlock mr-1"></i>Unlocked</span>';

                                        var pkgType = m.package_type || 'Account Activation';
                                        var pkgVal = typeof m.package_value !== 'undefined' ? Number(m.package_value).toFixed(2) : '0.00';
                                        var pkgDate = m.package_date || m.registration_date || 'N/A';

                                        rowsHtml += '<tr>' +
                                            '<td class="text-center">' +
                                            '<input type="checkbox" name="selected_packages[]" value="' + m.id + '" class="member-checkbox package-checkbox" checked style="cursor: pointer; transform: scale(1.15);">' +
                                            '<input type="hidden" name="selected_members[]" value="' + m.memberid + '">' +
                                            '</td>' +
                                            '<td>' +
                                            '<strong class="text-primary">' + m.memberid + '</strong>' +
                                            (m.mobile ? '<div class="small text-muted" style="font-size: 11px;">' + m.mobile + '</div>' : '') +
                                            '</td>' +
                                            '<td><span class="font-weight-bold text-dark">' + m.name + '</span></td>' +
                                            '<td>' +
                                            '<span class="badge badge-info font-weight-bold">' + pkgType + '</span>' +
                                            '<div class="small font-weight-bold text-dark mt-1">$' + pkgVal + '</div>' +
                                            '</td>' +
                                            '<td><span class="text-muted"><i class="ik ik-calendar mr-1"></i>' + pkgDate + '</span></td>' +
                                            '<td><span class="font-weight-bold text-dark">$' + Number(m.trading_wallet).toFixed(2) + '</span></td>' +
                                            '<td>' + statusBadge + '</td>' +
                                            '<td>' +
                                            '<span class="badge badge-primary font-weight-bold">' + Number(m.withdrawal_percent).toFixed(1) + '%</span>' +
                                            '<div class="small text-muted" style="font-size: 11px;">Max: $' + Number(m.max_withdrawable).toFixed(2) + '</div>' +
                                            '</td>' +
                                            '</tr>';
                                    });
                                    $('#membersTableBody').html(rowsHtml);
                                }

                                updateSelectedCount();
                            }
                        },
                        error: function(xhr) {
                            $('#btnFilterMembers').prop('disabled', false);
                            var err = 'Failed to fetch package members.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                err = xhr.responseJSON.message;
                            }
                            $('#filterStatusMsg').html(
                                '<div class="alert alert-danger py-2 px-3 mb-0">' +
                                '<i class="ik ik-alert-triangle mr-1"></i> ' + err +
                                '</div>'
                            );
                    });
                });

                // Validation on form submission
                var activeSubmitAction = 'apply_selected';
                $('#btnSaveCurrentSetting').on('click', function() {
                    activeSubmitAction = 'save_current_setting';
                });
                $('#btnApplyControl').on('click', function() {
                    activeSubmitAction = 'apply_selected';
                });

                $('#applyControlForm').on('submit', function(e) {
                    var lockDays = parseInt($('#lock_days').val());
                    if (isNaN(lockDays) || lockDays < 0) {
                        e.preventDefault();
                        alert('Please enter a valid lock period in days (0 or greater).');
                        return false;
                    }

                    var percent = parseFloat($('#withdrawal_percent').val());
                    if (isNaN(percent) || percent < 0 || percent > 100) {
                        e.preventDefault();
                        alert('Please enter a valid maximum withdrawal percentage (0 to 100%).');
                        return false;
                    }

                    if (activeSubmitAction === 'save_current_setting') {
                        return confirm('Update the active default Lock Period to ' + lockDays + ' days (' + percent + '% max withdrawal)?\n\nThis will apply to all active entries activated TODAY, and all future package activations.\nHistorical/older entries will NOT be modified.');
                    }

                    var checkedCount = $('.member-checkbox:checked').length;
                    if (checkedCount === 0) {
                        e.preventDefault();
                        alert('Please select at least one member using the checkboxes.');
                        return false;
                    }

                    return confirm('Apply Trading Wallet Control to ' + checkedCount + ' selected member(s)?\n- Lock Period: ' + lockDays + ' days\n- Post-Lock Max Withdrawal: ' + percent + '%');
                });

                // Initialize counts & preview
                updateSelectedCount();
                updateRulePreview();
            });
        </script>
    @endpush
@endsection
