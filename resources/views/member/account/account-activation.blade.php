@extends('member.layouts.main')
@section('title', 'Account Activation')
@section('container')

<style>
    /* =============================================
       ACCOUNT ACTIVATION PAGE — Bull Trading Theme Match
       Colors: #08090C obsidian · #F59E0B gold · #EF4444 red · #10B981 green
    ============================================= */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .act-page {
        font-family: 'Inter', sans-serif;
        min-height: calc(100vh - 120px);
        display: flex; align-items: flex-start; padding-top: 10px;
    }

    /* ---- Hero Banner (success state) ---- */
    .act-success-banner {
        position: relative; overflow: hidden;
        border-radius: 18px 18px 0 0;
        padding: 32px 36px;
        background: linear-gradient(135deg, #0C0F17 0%, #1A1408 40%, #362203 80%, #683F06 120%);
        border: 1px solid rgba(245, 158, 11, 0.35);
        border-bottom: none;
        color: #fff;
    }
    .act-success-banner::before {
        content: ''; position: absolute;
        width: 280px; height: 280px; right: -50px; top: -110px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.25) 0%, transparent 70%);
        animation: actGlowPulse 4s ease-in-out infinite;
    }
    .act-success-banner::after {
        content: ''; position: absolute;
        width: 180px; height: 180px; right: -20px; top: -70px;
        border: 28px solid rgba(245, 158, 11, 0.08); border-radius: 50%;
    }
    @keyframes actGlowPulse {
        0%, 100% { opacity: 0.5; transform: scale(1); }
        50%       { opacity: 1;   transform: scale(1.1); }
    }

    .act-check-circle {
        width: 56px; height: 56px; flex-shrink: 0; border-radius: 50%;
        background: rgba(16, 185, 129, 0.15);
        border: 2px solid rgba(16, 185, 129, 0.40);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 24px rgba(16, 185, 129, 0.35);
        position: relative; z-index: 1;
    }
    .act-check-circle i { color: #10B981; font-size: 20px; }

    .act-success-banner h3 {
        position: relative; z-index: 1;
        font-size: 22px; font-weight: 800; margin: 0 0 4px 0;
        text-shadow: 0 2px 12px rgba(0,0,0,0.5);
        background: linear-gradient(135deg, #FFFFFF 30%, #FDE68A 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .act-success-banner .act-sub {
        position: relative; z-index: 1;
        color: rgba(255,255,255,0.75); font-size: 13.5px;
    }

    /* ---- Success Body ---- */
    .act-success-body {
        background: #0C0E14;
        border: 1px solid rgba(245, 158, 11, 0.20);
        border-top: none;
        border-radius: 0 0 18px 18px;
        padding: 28px 36px;
    }
    .act-status-label {
        color: #94A3B8; font-size: 11px; font-weight: 700;
        letter-spacing: 1.5px; text-transform: uppercase; margin-bottom: 6px;
    }
    .act-status-value {
        color: #F8FAFC; font-size: 20px; font-weight: 800; margin-bottom: 4px;
    }
    .act-verified-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 18px; border-radius: 50px;
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.35);
        color: #10B981; font-size: 13px; font-weight: 700;
    }
    .act-verified-badge i { font-size: 12px; }
    .act-msg-text { color: #94A3B8; font-size: 13.5px; margin-top: 14px; }

    /* ---- Form Card (Temp state) ---- */
    .act-form-card {
        background: #0C0E14;
        border: 1px solid rgba(245, 158, 11, 0.20);
        border-radius: 18px;
        box-shadow: 0 24px 60px rgba(0,0,0,0.55), 0 0 20px rgba(245, 158, 11, 0.05);
        overflow: hidden;
    }
    .act-form-card .act-card-header {
        border-bottom: 1px solid rgba(245, 158, 11, 0.15);
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(239, 68, 68, 0.04));
        padding: 20px 28px;
        display: flex; align-items: center; gap: 14px;
    }
    .act-icon-box {
        width: 42px; height: 42px; border-radius: 11px; flex-shrink: 0;
        background: linear-gradient(135deg, #F59E0B, #D97706);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 18px rgba(245, 158, 11, 0.35);
    }
    .act-icon-box i { color: #08090C; font-size: 17px; }
    .act-card-title { color: #F8FAFC; font-size: 17px; font-weight: 700; margin: 0; }
    .act-card-sub { color: #94A3B8; font-size: 12px; margin-top: 2px; }

    .act-form-body { padding: 28px; }

    /* Labels */
    .act-label {
        color: #F59E0B; font-size: 11px; font-weight: 700;
        letter-spacing: 0.6px; text-transform: uppercase;
        margin-bottom: 8px; display: block;
    }
    /* Inputs */
    .act-input {
        background: #08090C !important;
        border: 1px solid rgba(245, 158, 11, 0.20) !important;
        border-radius: 10px !important; color: #F8FAFC !important;
        min-height: 50px; font-size: 14.5px; font-weight: 500;
        padding: 0 16px !important;
        transition: border-color .25s ease, box-shadow .25s ease !important;
    }
    .act-input::placeholder { color: #64748B !important; }
    .act-input:focus {
        border-color: #F59E0B !important;
        box-shadow: 0 0 0 3.5px rgba(245, 158, 11, 0.20) !important;
        background: rgba(245, 158, 11, 0.04) !important;
        outline: none !important;
    }
    .act-input[readonly] { color: #94A3B8 !important; cursor: default; }

    /* Submit Button */
    .act-btn {
        min-height: 50px; border: none; border-radius: 12px;
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        color: #08090C; padding: 0 30px; font-size: 14.5px; font-weight: 800;
        box-shadow: 0 8px 28px rgba(245, 158, 11, 0.35);
        transition: all .3s ease; position: relative; overflow: hidden;
    }
    .act-btn:hover {
        background: linear-gradient(135deg, #FBBF24 0%, #B45309 100%);
        box-shadow: 0 12px 36px rgba(245, 158, 11, 0.50);
        transform: translateY(-1px); color: #08090C;
    }
    .act-btn:active { transform: translateY(0); }

    /* Footer divider row */
    .act-footer {
        border-top: 1px solid rgba(245, 158, 11, 0.15);
        padding-top: 20px; margin-top: 8px;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
    }
    .act-secure { color: #94A3B8; font-size: 12.5px; display: flex; align-items: center; gap: 6px; }
    .act-secure i { color: #10B981; }

    /* Alerts */
    .act-page .alert-danger {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.35);
        color: #FCA5A5; border-radius: 12px;
    }
    .act-page .text-danger { color: #EF4444 !important; font-size: 12px; }
</style>

<div class="content-body act-page">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-9 col-12">

                @if (session()->has('failedMsg'))
                    <div class="alert alert-danger mb-4" role="alert">
                        <i class="fa-solid fa-circle-xmark me-2"></i>{{ session('failedMsg') }}
                    </div>
                @endif

                @if ($data['status'] == 'Temp')
                    {{-- ============ ACTIVATION FORM ============ --}}
                    <div class="act-form-card">
                        <div class="act-card-header">
                            <div class="act-icon-box">
                                <i class="fa-solid fa-unlock-keyhole"></i>
                            </div>
                            <div>
                                <div class="act-card-title">Account Activation</div>
                                <div class="act-card-sub">Activate your account to access all features</div>
                            </div>
                        </div>

                        <div class="act-form-body">
                            <form action="{{ route('accountActivation') }}" method="post" class="myForm">
                                @csrf

                                <div class="mb-4">
                                    <label class="act-label" for="memberid">
                                        <i class="fa-solid fa-id-badge me-1"></i> Member ID
                                    </label>
                                    <input type="text" class="form-control act-input" id="memberid"
                                        name="memberid" value="{{ $data['memberid'] }}" readonly>
                                    @error('memberid')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="act-label" for="amount">
                                        <i class="fa-solid fa-dollar-sign me-1"></i> Activation Amount
                                    </label>
                                    <input type="text" class="form-control act-input" id="amount"
                                        name="amount" value="30" readonly>
                                    @error('amount')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="act-label" for="wallet">
                                        <i class="fa-solid fa-wallet me-1"></i> Main Wallet Balance
                                    </label>
                                    <input type="text" class="form-control act-input" id="wallet"
                                        name="wallet" value="{{ $data['p2p_wallet'] }}" readonly>
                                    @error('wallet')
                                        <span class="text-danger d-block mt-1">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="act-footer">
                                    <span class="act-secure">
                                        <i class="fa-solid fa-shield-halved"></i> Secure transaction
                                    </span>
                                    <button type="submit" class="btn act-btn" id="regBtn">
                                        <i class="fa-solid fa-bolt me-2"></i>Activate Account
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                @else
                    {{-- ============ SUCCESS CARD ============ --}}
                    <div class="border-0 overflow-hidden" style="border-radius:18px;">

                        {{-- Animated gradient banner --}}
                        <div class="act-success-banner">
                            <div class="d-flex align-items-center gap-3">
                                <div class="act-check-circle">
                                    <i class="fa fa-check"></i>
                                </div>
                                <div style="position:relative;z-index:1;">
                                    <h3>Account Activation Successful</h3>
                                    <div class="act-sub">Account activation is successfully.</div>
                                </div>
                            </div>
                        </div>

                        {{-- Status body --}}
                        <div class="act-success-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div>
                                    <div class="act-status-label">Status</div>
                                    <div class="act-status-value">Active</div>
                                </div>
                                <div class="act-verified-badge">
                                    <i class="fa-solid fa-circle-check"></i> Verified
                                </div>
                            </div>
                            <div class="act-msg-text">
                                {{ session('successMsg') ?? 'Account activated successfully.' }}
                            </div>
                        </div>

                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection

