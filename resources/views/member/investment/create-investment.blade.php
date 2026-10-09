@extends('member.layouts.main')
@section('title', 'Start Staking')
@section('container')
    @include('member.investment._styles')
    <div class="content-body staking-page">
        <div class="container-fluid py-4 py-md-5">

            {{-- ===================== HERO ===================== --}}
            <div class="staking-hero mb-4 mb-md-5">
                <div class="staking-eyebrow">Staking &amp; Trading Packages</div>
                <h1>Activate Staking Package</h1>
                <p>Stake directly from your Fund Wallet. <strong><span
                            class="dynamic-trading-percent">{{ $packageDistribution->trading_wallet }}%</span> of the package
                        amount</strong> is credited directly to your Trading Wallet to earn daily trading profits.</p>
                <div class="sk-hero-chips">
                    <div class="sk-chip"><i class="fa-solid fa-wallet"></i> Funded from Fund Wallet</div>
                    <div class="sk-chip"><i class="fa-solid fa-chart-line"></i> <span
                            class="dynamic-trading-percent">{{ $packageDistribution->trading_wallet }}%</span> to Trading
                        Wallet
                    </div>
                    <div class="sk-chip"><i class="fa-solid fa-bolt"></i> Daily Trading ROI</div>
                </div>
            </div>

            {{-- ===================== MAIN GRID ===================== --}}
            <div class="row g-4">

                {{-- ---- Left: Form ---- --}}
                <div class="col-xl-7 col-lg-7">

                    @if (session()->has('successMsg'))
                        <div class="alert alert-success mb-3" role="alert"
                            style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #10B981; border-radius: 12px;">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('successMsg') }}
                        </div>
                    @endif
                    @if (session()->has('failedMsg'))
                        <div class="alert alert-danger mb-3" role="alert"
                            style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #EF4444; border-radius: 12px;">
                            <i class="fa-solid fa-circle-xmark me-2"></i>{{ session('failedMsg') }}
                        </div>
                    @endif

                    <div class="staking-card">
                        {{-- Card Header --}}
                        <div class="card-header">
                            <div class="sk-card-icon">
                                <i class="fa-solid fa-cubes-stacked"></i>
                            </div>
                            <div>
                                <div class="card-title">
                                    Staking Package
                                    <span>Select your package tier and amount from your Fund Wallet</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body">

                            <form action="{{ route('createInvestment') }}" method="post" id="stakingForm">
                                @csrf
                                <div class="row g-4">

                                    {{-- Member ID --}}
                                    <div class="col-md-12">
                                        <label class="staking-label" for="memberid">
                                            <i class="fa-solid fa-id-badge me-1"></i> Member ID
                                        </label>
                                        <div class="input-group">
                                            <input class="form-control staking-input" id="memberid"
                                                value="{{ $data['memberid'] ?? '' }}" name="memberid" type="text"
                                                placeholder="Enter memberID" readonly>
                                        </div>
                                        @error('memberid')
                                            <span class="text-danger d-block mt-1"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Fund Wallet Balance Notice --}}
                                    <div class="col-md-12">
                                        <div class="p-3 rounded d-flex align-items-center justify-content-between"
                                            style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.25); border-radius: 12px;">
                                            <div>
                                                <small class="text-white-50 text-uppercase d-block"
                                                    style="font-size: 11px; letter-spacing: 0.5px;">Fund Wallet Balance</small>
                                                <span class="text-warning font-weight-bold"
                                                    style="font-size: 18px;">${{ number_format((float) ($data['p2p_wallet'] ?? 0), 2) }}
                                                    USDT</span>
                                            </div>
                                            <a href="{{ url('member/fund/deposit-fund') }}" class="btn btn-sm"
                                                style="background: rgba(245, 158, 11, 0.2); color: #F59E0B; border: 1px solid rgba(245, 158, 11, 0.4); border-radius: 8px;">
                                                <i class="fa-solid fa-plus me-1"></i>Deposit Fund
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Package Selection --}}
                                    <div class="col-md-12">
                                        <label class="staking-label" for="package">
                                            <i class="fa-solid fa-layer-group me-1"></i> Select Staking Package
                                        </label>
                                        <div class="input-group mb-2">
                                            <select class="form-select staking-input staking-select" id="package"
                                                name="package" required>
                                                <option value="" selected disabled>Select Package</option>
                                                <option value="Package1" data-min="50" data-max="500"
                                                    data-rate="{{ $packageDistribution->package_1_rate }}"
                                                    data-cap="{{ $packageDistribution->capping }}"
                                                    data-trading-percent="{{ $packageDistribution->trading_wallet }}">$50 -
                                                    $500</option>
                                                <option value="Package2" data-min="600" data-max="5000"
                                                    data-rate="{{ $packageDistribution->package_2_rate }}"
                                                    data-cap="{{ $packageDistribution->capping }}"
                                                    data-trading-percent="{{ $packageDistribution->trading_wallet }}">$600
                                                    - $5000</option>
                                                <option value="Package3" data-min="6000" data-max=""
                                                    data-rate="{{ $packageDistribution->package_3_rate }}"
                                                    data-cap="{{ $packageDistribution->capping }}"
                                                    data-trading-percent="{{ $packageDistribution->trading_wallet }}">$6000
                                                    Above</option>
                                            </select>
                                        </div>
                                        @error('package')
                                            <span class="text-danger d-block mt-1"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Amount to Stake --}}
                                    <div class="col-md-12">
                                        <label class="staking-label" for="amount">
                                            <i class="fa-solid fa-dollar-sign me-1"></i> Staking Amount (USDT)
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"
                                                style="background: #08090C; border-color: rgba(245, 158, 11, 0.2); color: #F59E0B;"><i
                                                    class="fa-solid fa-dollar-sign"></i></span>
                                            <input type="number" step="any" class="form-control staking-input"
                                                id="amount" name="amount" placeholder="Select a package first" required
                                                min="50" value="{{ old('amount') }}">
                                        </div>
                                        <div id="amount-hint" class="mt-2" style="font-size: 12px; display: none;">
                                        </div>
                                        @error('amount')
                                            <span class="text-danger d-block mt-1"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Live Calculation Preview --}}
                                    <div class="col-md-12" id="preview-container" style="display: none;">
                                        <div class="p-3 rounded"
                                            style="background: rgba(12, 14, 20, 0.95); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 14px;">
                                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2"
                                                style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                                <span class="text-white-50 small"><i
                                                        class="fa-solid fa-arrow-right-from-bracket text-warning me-1"></i>
                                                    Deducted From Fund Wallet:</span>
                                                <strong id="preview-staked-amount" class="text-white">$0.00 USDT</strong>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2"
                                                style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                                <span class="text-white-50 small"><i
                                                        class="fa-solid fa-chart-line text-success me-1"></i> <span
                                                        id="preview-trading-label-percent">{{ $packageDistribution->trading_wallet }}%</span>
                                                    Credited to Trading Wallet:</span>
                                                <strong id="preview-trading-amount" class="text-success font-weight-bold"
                                                    style="font-size: 15px;"></strong>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2"
                                                style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                                <span class="text-white-50 small"><i
                                                        class="fa-solid fa-bolt text-warning me-1"></i> Estimated Daily
                                                    ROI:</span>
                                                <span id="preview-roi-rate" class="text-warning font-weight-bold">0% /
                                                    day</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-white-50 small"><i
                                                        class="fa-solid fa-trophy text-info me-1"></i> Maximum Capping
                                                    Return:</span>
                                                <strong id="preview-cap-amount" class="text-info font-weight-bold">$0.00
                                                    USDT</strong>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                {{-- Info Note --}}
                                <div class="staking-note mt-4">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <span>Staking package is deducted from your <strong>Fund Wallet</strong>.
                                        <strong><span
                                                class="dynamic-trading-percent">{{ $packageDistribution->trading_wallet }}%</span>
                                            of the amount</strong> is credited to your <strong>Trading Wallet</strong>.
                                        Multiple staking packages are supported — every package operates independently with
                                        its own individual returns, capping, and expiry.</span>
                                </div>

                                {{-- Footer Actions --}}
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4 pt-2"
                                    style="border-top: 1px solid rgba(255,255,255,0.06);">
                                    <span class="sk-secure-txt">
                                        <i class="fa-solid fa-shield-halved"></i> Internal Fund Wallet Transaction
                                    </span>
                                    <button type="submit" class="btn staking-btn" id="submitStakingBtn">
                                        <i class="fa-solid fa-bolt me-2"></i>Create Staking
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ---- Right: Info Sidebar ---- --}}
                <div class="col-xl-5 col-lg-5">
                    <div class="sk-info-panel">

                        {{-- Stat: Fund Wallet --}}
                        <div class="sk-stat-card mb-3">
                            <div class="sk-stat-top">
                                <div class="sk-stat-label">Fund Wallet</div>
                                <div class="sk-stat-icon green"><i class="fa-solid fa-wallet"></i></div>
                            </div>
                            <div class="sk-stat-value">${{ number_format((float) ($data['p2p_wallet'] ?? 0), 2) }}</div>
                            <div class="sk-stat-sub">Available balance • Deducted on staking</div>
                        </div>



                        {{-- How it Works --}}
                        <div class="sk-howto-card">
                            <div class="sk-howto-title">
                                <i class="fa-solid fa-lightbulb"></i> How Staking &amp; Trading Works
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">1</div>
                                <div class="sk-step-text">
                                    <strong>Deposit USDT into Fund Wallet</strong>
                                    Deposit USDT (BEP-20) via MetaMask into your Fund Wallet.
                                </div>
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">2</div>
                                <div class="sk-step-text">
                                    <strong>Stake Package</strong>
                                    Select your package tier and enter your amount. The full package amount is deducted from
                                    your Fund Wallet.
                                </div>
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">3</div>
                                <div class="sk-step-text">
                                    <strong><span
                                            class="dynamic-trading-percent">{{ $packageDistribution->trading_wallet }}%</span>
                                        Credited to Trading Wallet</strong>
                                    <span
                                        class="dynamic-trading-percent">{{ $packageDistribution->trading_wallet }}%</span>
                                    of
                                    your staked amount is credited to your Trading Wallet for
                                    {{ $packageDistribution->lock_days }} days.
                                </div>
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">4</div>
                                <div class="sk-step-text">
                                    <strong>Earn Daily ROI</strong>
                                    Receive automated daily trading profits up to dynamic admin-configured maximum returns!
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>{{-- /row --}}

            @if (isset($activeStakings) && $activeStakings->count() > 0)
                {{-- Active Staking Packages Section --}}
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card staking-card">
                            <div
                                class="card-header pb-0 border-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="sk-card-title d-flex align-items-center">
                                    <div class="sk-title-icon me-3"><i class="fa-solid fa-list-check"></i></div>
                                    <div>
                                        My Active Staking Packages
                                        <span>Every package has individual returns, capping, and expiry</span>
                                    </div>
                                </div>
                                <a href="{{ route('Staking.details') }}"
                                    class="btn btn-sm btn-outline-warning text-uppercase px-3"
                                    style="font-size: 11px; border-radius: 8px;">
                                    <i class="fa-solid fa-clock-rotate-left me-1"></i> Full History
                                </a>
                            </div>
                            <div class="card-body pt-3">
                                <div class="table-responsive">
                                    <table class="table table-dark table-hover mb-0" style="background: transparent;">
                                        <thead>
                                            <tr
                                                style="border-bottom: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.6); font-size: 12px;">
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Package Range</th>
                                                <th>Invested</th>
                                                <th>Txnid</th>
                                                <th>Installments</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-size: 13px;">
                                            @foreach ($activeStakings as $idx => $stk)
                                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                                                    <td class="text-white-50">{{ $idx + 1 }}</td>
                                                    <td>
                                                        <small
                                                            class="d-block text-white-50">{{ $stk->created_at ? $stk->created_at->format('d M Y') : '' }}</small>
                                                    </td>
                                                    <td>
                                                        @if ($stk->package == 'Package1')
                                                            <span class="text-white font-weight-bold">$50 - $500</span>
                                                        @elseif ($stk->package == 'Package2')
                                                            <span class="text-white font-weight-bold">$600 - $5000</span>
                                                        @else
                                                            <span class="text-white font-weight-bold">$6000</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-warning font-weight-bold">
                                                        ${{ number_format((float) $stk->invest_amount, 2) }}</td>
                                                    <td class="text-warning font-weight-bold">
                                                        {{ $stk->txnid }}</td>
                                                    <td>{{ $stk->installments }}</td>
                                                    <td>
                                                        @if ($stk->status === 'Active')
                                                            <span class="badge"
                                                                style="background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 6px; padding: 4px 8px;">Active</span>
                                                        @else
                                                            <span class="badge"
                                                                style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; padding: 4px 8px;">Expired</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3 text-center" style="font-size: 12px; color: rgba(255,255,255,0.4);">
                                    <i class="fa-solid fa-circle-info me-1"></i> Only the latest 3 entries are shown here.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const packageSelect = document.getElementById('package');
            const amountInput = document.getElementById('amount');
            const amountHint = document.getElementById('amount-hint');
            const previewContainer = document.getElementById('preview-container');
            const previewStaked = document.getElementById('preview-staked-amount');
            const previewTrading = document.getElementById('preview-trading-amount');
            const previewRoi = document.getElementById('preview-roi-rate');
            const previewCap = document.getElementById('preview-cap-amount');
            const submitBtn = document.getElementById('submitStakingBtn');
            const walletBalance = parseFloat("{{ (float) ($data['p2p_wallet'] ?? 0) }}");

            function updateCalculations() {
                if (!packageSelect || !packageSelect.value) {
                    if (previewContainer) previewContainer.style.display = 'none';
                    if (amountInput) amountInput.placeholder = 'Select a package first';
                    return;
                }

                const selectedOption = packageSelect.options[packageSelect.selectedIndex];
                const min = parseFloat(selectedOption.getAttribute('data-min')) || 50;
                const max = selectedOption.getAttribute('data-max') ? parseFloat(selectedOption.getAttribute(
                    'data-max')) : null;
                let rate = parseFloat(selectedOption.getAttribute('data-rate')) || 0;
                let cap = parseFloat(selectedOption.getAttribute('data-cap')) || 0;
                let tradingPercent = parseFloat(selectedOption.getAttribute('data-trading-percent')) || 0;

                // Dynamically update all mentions of trading wallet percent on the page
                document.querySelectorAll('.dynamic-trading-percent').forEach(function(el) {
                    el.textContent = `${tradingPercent}%`;
                });
                const tradingLabelPercent = document.getElementById('preview-trading-label-percent');
                if (tradingLabelPercent) {
                    tradingLabelPercent.textContent = `${tradingPercent}%`;
                }

                const rangeText = max ? `Between ${min} and ${max} USDT` : `Minimum ${min} USDT`;
                amountInput.placeholder = `Enter amount (${rangeText})`;

                const val = parseFloat(amountInput.value);

                if (!amountInput.value || isNaN(val)) {
                    if (amountHint) {
                        amountHint.style.display = 'block';
                        amountHint.style.color = '#F59E0B';
                        amountHint.textContent = `Allowed range: ${rangeText}`;
                    }
                    if (previewContainer) previewContainer.style.display = 'none';
                    return;
                }

                // Validation check
                let isValid = true;
                let validationMsg = '';

                if (val < min || (max !== null && val > max)) {
                    isValid = false;
                    validationMsg = `Amount must be ${rangeText}.`;
                } else if (val > walletBalance) {
                    isValid = false;
                    validationMsg = `Amount exceeds available Fund Wallet balance ($${walletBalance.toFixed(2)}).`;
                }

                if (!isValid) {
                    amountHint.style.display = 'block';
                    amountHint.style.color = '#EF4444';
                    amountHint.textContent = validationMsg;
                    previewContainer.style.display = 'none';
                    return;
                }

                // Show valid state & live calculation
                amountHint.style.display = 'block';
                amountHint.style.color = '#10B981';
                amountHint.textContent = `Valid staking amount. Ready to stake from Fund Wallet.`;

                const tradingWalletCredit = (val * (tradingPercent / 100)).toFixed(2);
                const maxCapReturn = (val * (cap / 100)).toFixed(2);

                previewStaked.textContent = `$${val.toFixed(2)} USDT`;
                previewTrading.textContent = `$${tradingWalletCredit} USDT (${tradingPercent}%)`;
                previewRoi.textContent = `${rate}% Daily ROI`;

                if (cap === 0) {
                    previewCap.textContent = `No Capping`;
                } else {
                    previewCap.textContent = `$${maxCapReturn} USDT (${cap}% Max)`;
                }
                previewContainer.style.display = 'block';
            }

            if (packageSelect) {
                packageSelect.addEventListener('change', updateCalculations);
            }
            if (amountInput) {
                amountInput.addEventListener('input', updateCalculations);
            }
        });
    </script>
@endsection
