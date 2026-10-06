@extends('member.layouts.main')
@section('title', 'Partnership Investment')
@section('container')
    @include('member.partnership._styles')

    <div class="content-body staking-page">
        <div class="container-fluid py-4 py-md-5">

            {{-- Hero Banner --}}
            <div class="staking-hero mb-4 mb-md-5">
                <div class="staking-eyebrow">Partnership Section</div>
                <h1>Create a new Partnership Investment</h1>
                <p>Choose a partnership package to start earning passive rewards and grow your balance automatically.</p>
                <div class="sk-hero-chips">
                    <div class="sk-chip"><i class="fa-solid fa-shield-halved"></i> Secure & Insured</div>
                    <div class="sk-chip"><i class="fa-solid fa-bolt"></i> Instant Activation</div>
                    <div class="sk-chip"><i class="fa-solid fa-lock"></i> Funds Protected</div>
                </div>
            </div>

            <div class="row g-4">

                {{-- Left: Form Card --}}
                <div class="col-xl-7 col-lg-7">
                    @if (session()->has('successMsg'))
                        <div class="alert alert-success mb-4" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>{{ session('successMsg') }}
                        </div>
                    @endif
                    @if (session()->has('failedMsg'))
                        <div class="alert alert-danger mb-4" role="alert">
                            <i class="fa-solid fa-circle-xmark me-2"></i>{{ session('failedMsg') }}
                        </div>
                    @endif

                    <div class="staking-card">
                        {{-- Card Header --}}
                        <div class="card-header">
                            <div class="sk-card-icon">
                                <i class="fa-solid fa-handshake"></i>
                            </div>
                            <div>
                                <div class="card-title">
                                    Create Partnership Investment
                                    <span>Fill in the details below to start earning</span>
                                </div>
                            </div>
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body">
                            <form action="{{ route('partCreateInvest') }}" method="post">
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
                                            <span class="text-danger d-block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Investment Package --}}
                                    <div class="col-md-12">
                                        <label class="staking-label" for="amount">
                                            <i class="fa-solid fa-cubes me-1"></i> Investment Package
                                        </label>
                                        <div class="input-group">
                                            <select class="form-control staking-input" id="amount" name="amount"
                                                style="appearance: none; background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%2398A2C3%22 stroke-width=%222%22%3e%3cpolyline points=%226 9 12 15 18 9%22%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.85rem center; background-size: 1.3em 1.3em; padding-right: 2.5rem;">
                                                <option value="">Select Investment Package</option>
                                                @if ($data['partnership_package'] == 0)
                                                    <option value="100">🥉 BRONZE - $100</option>
                                                @elseif ($data['partnership_package'] == 100)
                                                    <option value="200">🥈 SILVER - $200</option>
                                                @elseif ($data['partnership_package'] == 200)
                                                    <option value="500">🥇 GOLD - $500</option>
                                                @elseif ($data['partnership_package'] == 500)
                                                    <option value="1000">💜 PLATINUM - $1000</option>
                                                @elseif ($data['partnership_package'] == 1000)
                                                    <option value="5000">💎 DIAMOND - $5000</option>
                                                @endif
                                            </select>
                                        </div>
                                        @error('amount')
                                            <span class="text-danger d-block mt-1">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Fund Wallet --}}
                                    <div class="col-md-12">
                                        <label class="staking-label" for="wallet">
                                            <i class="fa-solid fa-wallet me-1"></i> Fund Wallet Balance
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input class="form-control staking-input" id="wallet" name="wallet"
                                                type="text" placeholder="Enter wallet"
                                                value="{{ number_format($data['p2p_wallet'], 2) }}" readonly>
                                        </div>
                                    </div>

                                </div>

                                {{-- Info Note --}}
                                <div class="staking-note mt-4">
                                    <i class="fa-solid fa-circle-info"></i>
                                    <span>Your Partnership Investment amount remains locked until maturity. Review all partnership investment details before confirming.</span>
                                </div>

                                {{-- Action Row --}}
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mt-4">
                                    <span class="sk-secure-txt">
                                        <i class="fa-solid fa-shield-halved"></i> Secure wallet transaction
                                    </span>
                                    <button type="submit" class="btn staking-btn">
                                        <i class="fa-solid fa-bolt me-2"></i>Create Investment
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Right: Sidebar Info Cards --}}
                <div class="col-xl-5 col-lg-5">
                    <div class="d-flex flex-column gap-4">

                        {{-- Stat: Available Balance --}}
                        <div class="sk-stat-card">
                            <div class="sk-stat-top">
                                <div class="sk-stat-label">Available Balance</div>
                                <div class="sk-stat-icon green"><i class="fa-solid fa-wallet"></i></div>
                            </div>
                            <div class="sk-stat-value">${{ number_format($data['p2p_wallet'], 2) }}</div>
                            <div class="sk-stat-sub">Fund wallet • Ready for investment</div>
                        </div>

                        {{-- How it Works --}}
                        <div class="sk-howto-card">
                            <div class="sk-howto-title">
                                <i class="fa-solid fa-lightbulb"></i> How Partnership Investment Works
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">1</div>
                                <div class="sk-step-text">
                                    <strong>Select Package</strong>
                                    Choose an available partnership investment package based on your current tier.
                                </div>
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">2</div>
                                <div class="sk-step-text">
                                    <strong>Funds Allocated</strong>
                                    Your investment funds are securely allocated into the partnership pool.
                                </div>
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">3</div>
                                <div class="sk-step-text">
                                    <strong>Earn Returns</strong>
                                    Returns are generated automatically and credited to your income wallet.
                                </div>
                            </div>

                            <div class="sk-step">
                                <div class="sk-step-num">4</div>
                                <div class="sk-step-text">
                                    <strong>Maturity & Withdrawal</strong>
                                    Upon maturity, your investment principal and returns are fully available.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
