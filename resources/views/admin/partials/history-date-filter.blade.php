@php
    $collapseId = 'adminDateFilterCollapse_' . md5($action ?? url()->current());
    $dateOptions = $dateOptions ?? [];
    $defaultDate = $defaultDateField ?? 'created_at';
    $currentDateField = request('date_field', $defaultDate);
    $isAdvanced = request()->has('preset') || request()->has('from_date') || request()->has('to_date') || (request('filter_mode') === 'advanced');
    $filterActive = $filterActive ?? (request()->filled('filter_date') || request()->filled('preset') || (request()->filled('from_date') && request()->filled('to_date')));
    $filterSummary = $filterSummary ?? null;
    $filterErrors = $filterErrors ?? session('filter_errors', []);
    $resetUrl = $action ?? url()->current();
@endphp

<style>
    .admin-history-filter-card {
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        border: 1px solid #e9ecef;
        background: #ffffff;
    }
    .admin-history-filter-card .card-header {
        padding: 12px 20px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #edf2f9;
    }
    .admin-history-filter-card .card-body {
        padding: 18px 20px;
    }
    .admin-history-filter-label {
        font-size: 0.825rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 4px;
        display: block;
    }
    .admin-history-filter-input {
        height: 38px;
        font-size: 0.875rem;
        border-radius: 4px;
    }
    .admin-history-filter-btn {
        height: 38px;
        padding: 6px 16px;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .admin-history-filter-badge {
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .admin-history-filter-advanced-box {
        background-color: #fcfdfe;
        border: 1px dashed #d1d5db;
        border-radius: 6px;
        padding: 16px;
    }
    .admin-history-filter-error-alert {
        padding: 8px 14px;
        font-size: 0.85rem;
        border-radius: 4px;
        margin-bottom: 14px;
    }
    @media (max-width: 767.98px) {
        .admin-history-filter-btn {
            width: 100%;
            margin-top: 6px;
        }
        .admin-history-filter-header-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 8px;
        }
    }
</style>

<div class="card admin-history-filter-card">
    <div class="card-header d-flex justify-content-between align-items-center admin-history-filter-header-flex">
        <div class="d-flex align-items-center">
            <h3 class="mb-0 font-weight-bold" style="font-size: 1rem;">
                <i class="ik ik-calendar text-primary mr-2"></i>{{ __('Date Filter') }}
            </h3>
            @if($filterActive && $filterSummary)
                <span class="badge badge-info admin-history-filter-badge ml-3" title="{{ __('Active Filter') }}">
                    <i class="ik ik-check-circle mr-1"></i>{{ $filterSummary }}
                </span>
            @endif
        </div>
        <div>
            @if($filterActive)
                <a href="{{ $resetUrl }}" class="btn btn-sm btn-outline-danger admin-history-filter-btn" style="height: 30px; padding: 2px 10px;" title="{{ __('Reset all filters') }}">
                    <i class="ik ik-rotate-ccw mr-1"></i>{{ __('Reset') }}
                </a>
            @endif
        </div>
    </div>

    <div class="card-body">
        @if(!empty($filterErrors))
            <div class="alert alert-danger admin-history-filter-error-alert" role="alert">
                <i class="ik ik-alert-circle mr-1"></i>
                @if(is_array($filterErrors))
                    <ul class="mb-0 pl-3">
                        @foreach($filterErrors as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ $filterErrors }}
                @endif
            </div>
        @endif

        <form method="GET" action="{{ $action ?? url()->current() }}" class="admin-history-filter-form" id="adminFilterForm_{{ md5($collapseId) }}">
            {{-- Basic Date Filter Section --}}
            <div class="form-row align-items-end">
                <div class="form-group col-lg-4 col-md-5 col-sm-12 mb-2">
                    <label for="filter_date_{{ md5($collapseId) }}" class="admin-history-filter-label">
                        <i class="ik ik-calendar mr-1 text-muted"></i>{{ __('Specific Date') }}
                    </label>
                    <input type="date" 
                           id="filter_date_{{ md5($collapseId) }}" 
                           name="filter_date" 
                           value="{{ request('filter_date') }}" 
                           class="form-control admin-history-filter-input"
                           placeholder="YYYY-MM-DD">
                </div>

                <div class="form-group col-lg-8 col-md-7 col-sm-12 mb-2">
                    <div class="d-flex flex-wrap" style="gap: 8px;">
                        <input type="hidden" name="filter_mode" id="filter_mode_{{ md5($collapseId) }}" value="{{ request('filter_mode', 'single') }}">
                        
                        <button type="submit" 
                                class="btn btn-primary admin-history-filter-btn" 
                                onclick="document.getElementById('filter_mode_{{ md5($collapseId) }}').value='single';">
                            <i class="ik ik-filter mr-1"></i>{{ __('Filter') }}
                        </button>

                        <button type="button" 
                                class="btn btn-outline-secondary admin-history-filter-btn" 
                                data-toggle="collapse" 
                                data-target="#{{ $collapseId }}" 
                                aria-expanded="{{ $isAdvanced ? 'true' : 'false' }}" 
                                aria-controls="{{ $collapseId }}">
                            <i class="ik ik-sliders mr-1"></i>{{ __('Advanced Filter') }}
                            <i class="ik ik-chevron-down ml-1"></i>
                        </button>

                        @if($filterActive)
                            <a href="{{ $resetUrl }}" class="btn btn-light border admin-history-filter-btn" title="{{ __('Clear and show all records') }}">
                                <i class="ik ik-rotate-ccw mr-1"></i>{{ __('Reset') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Advanced Date Filter Section (Collapsible) --}}
            <div class="collapse {{ $isAdvanced ? 'show' : '' }} mt-2" id="{{ $collapseId }}">
                <div class="admin-history-filter-advanced-box mt-2">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 font-weight-bold text-dark">
                            <i class="ik ik-sliders text-info mr-1"></i>{{ __('Advanced Date Range & Presets') }}
                        </h6>
                        <small class="text-muted">{{ __('Calculated according to server timezone: ') }} <code>{{ config('app.timezone', 'UTC') }}</code></small>
                    </div>

                    <div class="form-row">
                        {{-- Quick Preset Period --}}
                        <div class="form-group col-lg-3 col-md-6 col-sm-12 mb-3">
                            <label for="preset_{{ md5($collapseId) }}" class="admin-history-filter-label">
                                {{ __('Quick Period') }}
                            </label>
                            <select name="preset" 
                                    id="preset_{{ md5($collapseId) }}" 
                                    class="form-control admin-history-filter-input"
                                    onchange="handleAdminPresetChange(this, '{{ md5($collapseId) }}')">
                                <option value="">-- {{ __('Select Preset') }} --</option>
                                <option value="today" {{ request('preset') === 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                                <option value="yesterday" {{ request('preset') === 'yesterday' ? 'selected' : '' }}>{{ __('Yesterday') }}</option>
                                <option value="last_7_days" {{ request('preset') === 'last_7_days' ? 'selected' : '' }}>{{ __('Last 7 Days') }}</option>
                                <option value="last_30_days" {{ request('preset') === 'last_30_days' ? 'selected' : '' }}>{{ __('Last 30 Days') }}</option>
                                <option value="this_month" {{ request('preset') === 'this_month' ? 'selected' : '' }}>{{ __('This Month') }}</option>
                                <option value="last_month" {{ request('preset') === 'last_month' ? 'selected' : '' }}>{{ __('Last Month') }}</option>
                                <option value="this_year" {{ request('preset') === 'this_year' ? 'selected' : '' }}>{{ __('This Year') }}</option>
                                <option value="custom" {{ request('preset') === 'custom' || (!request('preset') && (request('from_date') || request('to_date'))) ? 'selected' : '' }}>{{ __('Custom Range') }}</option>
                            </select>
                        </div>

                        {{-- From Date --}}
                        <div class="form-group col-lg-3 col-md-6 col-sm-12 mb-3">
                            <label for="from_date_{{ md5($collapseId) }}" class="admin-history-filter-label">
                                {{ __('From Date') }} <small class="text-muted">(00:00:00)</small>
                            </label>
                            <input type="date" 
                                   id="from_date_{{ md5($collapseId) }}" 
                                   name="from_date" 
                                   value="{{ request('from_date') }}" 
                                   class="form-control admin-history-filter-input">
                        </div>

                        {{-- To Date --}}
                        <div class="form-group col-lg-3 col-md-6 col-sm-12 mb-3">
                            <label for="to_date_{{ md5($collapseId) }}" class="admin-history-filter-label">
                                {{ __('To Date') }} <small class="text-muted">(23:59:59)</small>
                            </label>
                            <input type="date" 
                                   id="to_date_{{ md5($collapseId) }}" 
                                   name="to_date" 
                                   value="{{ request('to_date') }}" 
                                   class="form-control admin-history-filter-input">
                        </div>

                        {{-- Date Type Selector (only shown if multiple date options exist) --}}
                        @if(!empty($dateOptions) && count($dateOptions) > 1)
                            <div class="form-group col-lg-3 col-md-6 col-sm-12 mb-3">
                                <label for="date_field_{{ md5($collapseId) }}" class="admin-history-filter-label">
                                    {{ __('Date Type') }}
                                </label>
                                <select name="date_field" 
                                        id="date_field_{{ md5($collapseId) }}" 
                                        class="form-control admin-history-filter-input">
                                    @foreach($dateOptions as $optValue => $optLabel)
                                        <option value="{{ $optValue }}" {{ $currentDateField === $optValue ? 'selected' : '' }}>
                                            {{ $optLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            @if(!empty($defaultDate))
                                <input type="hidden" name="date_field" value="{{ $defaultDate }}">
                            @endif
                        @endif
                    </div>

                    {{-- Advanced Actions --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-2" style="gap: 8px;">
                        <div class="text-muted small">
                            <i class="ik ik-info mr-1"></i>{{ __('Custom range is inclusive of start and end dates.') }}
                        </div>
                        <div class="d-flex flex-wrap" style="gap: 8px;">
                            <button type="submit" 
                                    class="btn btn-info admin-history-filter-btn"
                                    onclick="document.getElementById('filter_mode_{{ md5($collapseId) }}').value='advanced'; document.getElementById('filter_date_{{ md5($collapseId) }}').value='';">
                                <i class="ik ik-check mr-1"></i>{{ __('Apply Advanced Filter') }}
                            </button>
                            <a href="{{ $resetUrl }}" class="btn btn-secondary admin-history-filter-btn">
                                <i class="ik ik-x mr-1"></i>{{ __('Clear Filters') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function formatDateYMD(d) {
        var year = d.getFullYear();
        var month = ('0' + (d.getMonth() + 1)).slice(-2);
        var day = ('0' + d.getDate()).slice(-2);
        return year + '-' + month + '-' + day;
    }

    function handleAdminPresetChange(selectElem, hashId) {
        var val = selectElem.value;
        var fromInput = document.getElementById('from_date_' + hashId);
        var toInput = document.getElementById('to_date_' + hashId);
        if (!fromInput || !toInput) return;

        var today = new Date();
        var fromDate = new Date();
        var toDate = new Date();

        switch (val) {
            case 'today':
                fromInput.value = formatDateYMD(today);
                toInput.value = formatDateYMD(today);
                break;
            case 'yesterday':
                fromDate.setDate(today.getDate() - 1);
                fromInput.value = formatDateYMD(fromDate);
                toInput.value = formatDateYMD(fromDate);
                break;
            case 'last_7_days':
                fromDate.setDate(today.getDate() - 6);
                fromInput.value = formatDateYMD(fromDate);
                toInput.value = formatDateYMD(today);
                break;
            case 'last_30_days':
                fromDate.setDate(today.getDate() - 29);
                fromInput.value = formatDateYMD(fromDate);
                toInput.value = formatDateYMD(today);
                break;
            case 'this_month':
                fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
                toDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                fromInput.value = formatDateYMD(fromDate);
                toInput.value = formatDateYMD(toDate);
                break;
            case 'last_month':
                fromDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                toDate = new Date(today.getFullYear(), today.getMonth(), 0);
                fromInput.value = formatDateYMD(fromDate);
                toInput.value = formatDateYMD(toDate);
                break;
            case 'this_year':
                fromDate = new Date(today.getFullYear(), 0, 1);
                toDate = new Date(today.getFullYear(), 11, 31);
                fromInput.value = formatDateYMD(fromDate);
                toInput.value = formatDateYMD(toDate);
                break;
            case 'custom':
                break;
            default:
                break;
        }
    }
</script>
