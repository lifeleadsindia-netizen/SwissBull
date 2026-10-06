@extends('member.layouts.main')
@section('title', 'Start Staking')
@section('container')
    @include('member.investment._styles')
    <div class="content-body staking-page">
        <div class="container-fluid py-4 py-md-5">

            {{-- ===================== HERO ===================== --}}
            <div class="staking-hero mb-4 mb-md-5">
                <div class="staking-eyebrow">Grow your balance</div>
                <h1>Create a new Staking</h1>
                <p>Lock your funds and earn passive rewards. Your balance grows automatically every cycle.</p>
                <div class="sk-hero-chips">
                    <div class="sk-chip"><i class="fa-solid fa-shield-halved"></i> Secure &amp; Insured</div>
                    <div class="sk-chip"><i class="fa-solid fa-bolt"></i> Instant Activation</div>
                    <div class="sk-chip"><i class="fa-solid fa-lock"></i> Funds Protected</div>
                </div>
            </div>

            {{-- ===================== MAIN GRID ===================== --}}
            <div class="row g-4">

                {{-- ---- Left: Form ---- --}}
                <div class="col-xl-7 col-lg-7">

                    @if (session()->has('successMsg'))
                        <div class="alert alert-success mb-3" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('successMsg') }}
                        </div>
                    @endif
                    @if (session()->has('failedMsg'))
                        <div class="alert alert-danger mb-3" role="alert">
                            <i class="fa-solid fa-circle-xmark me-2"></i>{{ session('failedMsg') }}
                        </div>
                    @endif

                    <div class="staking-card">
                        {{-- Card Header --}}
                        <div class="card-header">
                            <div class="sk-card-icon">
                                <i class="fa-solid fa-dollar-sign"></i>
                            </div>
                            <div>
                                <div class="card-title">
                                    Create Staking
                                    <span>Fill in the details below to start earning</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body">
                            <form action="{{ route('createInvestment') }}" method="post">
                                @csrf
                                <div class="row g-4">

                                    {{-- Member ID --}}
                                    <div class="col-md-12">
                                        <label class="staking-label" for="memberid">
                                            <i class="fa-solid fa-id-badge me-1"></i> Member ID
                                        </label>
                                        <div class="input-group">
                                            <input class="form-control staking-input" id="memberid"
                                                value="{{ $data['memberid'] }}" name="memberid" type="text"
                                                placeholder="Enter memberID" readonly>
                                        </div>
                                        @error('memberid')
                                            <span class="text-danger d-block mt-1"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Amount --}}
                                    <div class="col-md-12">
                                        <label class="staking-label" for="amount">
                                            <i class="fa-solid fa-dollar-sign me-1"></i> Amount to Stake
                                        </label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fa-solid fa-dollar-sign"></i></span>
                                            <select class="form-select staking-input staking-select" id="amount"
                                                name="amount" required>
                                                <option value="">Select Package</option>
                                                @php
                                                    $currentPkg = (int) ($data['package'] ?? 0);
                                                    $packages = [
                                                        20,
                                                        30,
                                                        70,
                                                        150,
                                                        300,
                                                        600,
                                                        1200,
                                                        2500,
                                                        5000,
                                                        8000,
                                                        10000,
                                                        25000,
                                                    ];
                                                    $nextPkg = null;
                                                    if ($currentPkg == 0) {
                                                        $nextPkg = 20;
                                                    } elseif ($currentPkg == 20) {
                                                        $nextPkg = 30;
                                                    } elseif ($currentPkg == 30) {
                                                        $nextPkg = 70;
                                                    } elseif ($currentPkg == 70) {
                                                        $nextPkg = 150;
                                                    } elseif ($currentPkg == 150) {
                                                        $nextPkg = 300;
                                                    } elseif ($currentPkg == 300) {
                                                        $nextPkg = 600;
                                                    } elseif ($currentPkg == 600) {
                                                        $nextPkg = 1200;
                                                    } elseif ($currentPkg == 1200) {
                                                        $nextPkg = 2500;
                                                    } elseif ($currentPkg == 2500) {
                                                        $nextPkg = 5000;
                                                    } elseif ($currentPkg == 5000) {
                                                        $nextPkg = 8000;
                                                    } elseif ($currentPkg == 8000) {
                                                        $nextPkg = 10000;
                                                    } elseif ($currentPkg == 10000) {
                                                        $nextPkg = 25000;
                                                    } elseif ($currentPkg < 25000) {
                                                        foreach ($packages as $pkg) {
                                                            if ($currentPkg < $pkg) {
                                                                $nextPkg = $pkg;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                @if ($nextPkg)
                                                    <option value="{{ $nextPkg }}">💲 ${{ number_format($nextPkg) }}
                                                    </option>
                                                @else
                                                    <option value="" disabled>All Packages Amount Staked</option>
                                                @endif
                                            </select>
                                        </div>
                                        @error('amount')
                                            <span class="text-danger d-block mt-1"> {{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Fund Wallet --}}
                                    <div class="col-md-12">
                                        <label class="staking-label" for="wallet">
                                            <i class="fa-solid fa-wallet me-1"></i> Fund Wallet Balance
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa-solid fa-dollar-sign"></i></span>
                                            <input class="form-control staking-input" id="wallet" name="wallet"
                                                type="text" placeholder="Fund wallet"
                                                value="{{ number_format($data['p2p_wallet'], 2) }}" readonly>
                                        </div>
                                    </div>

                                </div>

                                {{-- Info Note --}}
                                <div class="staking-note mt-4">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <span>Your staking amount remains <strong>locked until maturity</strong>.
                                        Please review all details carefully before confirming your staking.</span>
                                </div>

                                {{-- Footer Actions --}}
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4 pt-2"
                                    style="border-top: 1px solid rgba(255,255,255,0.06);">
                                    <span class="sk-secure-txt">
                                        <i class="fa-solid fa-shield-halved"></i> Secure wallet transaction
                                    </span>
                                    <button type="submit" class="btn staking-btn">
                                        <i class="fa-solid fa-arrow-right me-2"></i>Create Staking
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
                        <div class="sk-stat-card">
                            <div class="sk-stat-top">
                                <div class="sk-stat-label">Available Balance</div>
                                <div class="sk-stat-icon green"><i class="fa-solid fa-wallet"></i></div>
                            </div>
                            <div class="sk-stat-value">${{ number_format($data['p2p_wallet'], 2) }}</div>
                            <div class="sk-stat-sub">Fund wallet • Ready to stake</div>
                        </div>

                        {{-- How it Works --}}
                        <div class="sk-howto-card">
                            <div class="sk-howto-title">
                                <i class="fa-solid fa-lightbulb"></i> How Staking Works
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">1</div>
                                <div class="sk-step-text">
                                    <strong>Enter Amount</strong>
                                    Enter the amount you wish to stake from your fund wallet balance.
                                </div>
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">2</div>
                                <div class="sk-step-text">
                                    <strong>Funds Locked</strong>
                                    Your staked funds are securely locked until the maturity date.
                                </div>
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">3</div>
                                <div class="sk-step-text">
                                    <strong>Earn Rewards</strong>
                                    Rewards are credited automatically to your income wallet each cycle.
                                </div>
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">4</div>
                                <div class="sk-step-text">
                                    <strong>Maturity &amp; Withdrawal</strong>
                                    Upon maturity, your principal and rewards become fully withdrawable.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>{{-- /row --}}
        </div>
    </div>
@endsection
