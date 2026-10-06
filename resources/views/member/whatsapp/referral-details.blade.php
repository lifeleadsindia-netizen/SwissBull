@extends('member.layouts.main')
@section('title', 'Promotion Airdrop History')
@section('container')

    <div class="content-body">
        <div class="container-fluid pt-2">
            <!-- Page Header -->
            <div class="page-titles mb-3">
                <div class="welcome-text">
                    <h4 class="text-white font-weight-bold mb-1">Referral Details &amp; PEPE Airdrop History</h4>
                    <p class="mb-0 text-muted" style="font-size: 13px;">Track your complete promotional earnings, WhatsApp messages, direct registration &amp; activation PEPE rewards.</p>
                </div>
                <div class="justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/member/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Referral Details</a></li>
                    </ol>
                </div>
            </div>

            <!-- Summary Stats Cards (3 Rules + Lifetime Total) -->
            <div class="row g-3 mb-4">
                <!-- Rule 1: WhatsApp Messages -->
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3 h-100" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(0, 230, 118, 0.3); border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge" style="background: rgba(0, 230, 118, 0.15); color: #00e676; font-size: 11px;">
                                <i class="fab fa-whatsapp me-1"></i> Rule 1
                            </span>
                            <span class="badge {{ ($waTodayCount ?? 0) >= 10 ? 'bg-success' : 'bg-warning text-dark' }}" style="font-size: 11px;">
                                Today: {{ $waTodayCount ?? 0 }}/10
                            </span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-white-50 d-block" style="font-size: 11px; text-transform: uppercase;">WhatsApp Messages</small>
                                <h3 class="text-white font-weight-bold mb-0">{{ number_format($msgCount ?? 0) }}</h3>
                                <small class="text-success font-weight-bold">{{ number_format($msgTokens ?? 0, 0) }} PEPE</small>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(0, 230, 118, 0.15); width: 44px; height: 44px;">
                                <i class="fas fa-paper-plane text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rule 2: Direct Registrations -->
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3 h-100" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(13, 202, 240, 0.3); border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge" style="background: rgba(13, 202, 240, 0.15); color: #0dcaf0; font-size: 11px;">
                                <i class="fas fa-user-plus me-1"></i> Rule 2
                            </span>
                            <small class="text-info" style="font-size: 11px;">500 PEPE / signup</small>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-white-50 d-block" style="font-size: 11px; text-transform: uppercase;">Direct Registrations</small>
                                <h3 class="text-white font-weight-bold mb-0">{{ number_format($regCount ?? 0) }}</h3>
                                <small class="text-info font-weight-bold">{{ number_format($regTokens ?? 0, 0) }} PEPE</small>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(13, 202, 240, 0.15); width: 44px; height: 44px;">
                                <i class="fas fa-users text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rule 3: Direct Activations -->
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3 h-100" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge" style="background: rgba(255, 193, 7, 0.15); color: #ffc107; font-size: 11px;">
                                <i class="fas fa-bolt me-1"></i> Rule 3
                            </span>
                            <small class="text-warning" style="font-size: 11px;">500 PEPE / active</small>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-white-50 d-block" style="font-size: 11px; text-transform: uppercase;">Direct Activations</small>
                                <h3 class="text-white font-weight-bold mb-0">{{ number_format($actCount ?? 0) }}</h3>
                                <small class="text-warning font-weight-bold">{{ number_format($actTokens ?? 0, 0) }} PEPE</small>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(255, 193, 7, 0.15); width: 44px; height: 44px;">
                                <i class="fas fa-bolt text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grand Total PEPE Earned & Wallet -->
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3 h-100" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(12, 28, 56, 0.95) 100%); border: 1px solid #00e676; border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-success text-dark font-weight-bold" style="font-size: 11px;">
                                <i class="fas fa-coins me-1"></i> Lifetime Earned
                            </span>
                            <a href="{{ route('member.pepe.redeemHistory') }}" class="text-white-50 small text-decoration-none">
                                <i class="fas fa-history me-1"></i> Redeems
                            </a>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-white-50 d-block" style="font-size: 11px; text-transform: uppercase;">Total PEPE Earned</small>
                                <h3 class="font-weight-bold mb-0" style="color: #00e676;">{{ number_format($totalPepeEarned ?? 0, 0) }}</h3>
                                <small class="text-white-50">Wallet: <strong class="text-white">{{ number_format($member->pepe_wallet ?? 0, 0) }} PEPE</strong></small>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(0, 230, 118, 0.25); width: 44px; height: 44px;">
                                <i class="fas fa-wallet fa-lg" style="color: #00e676;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rules Overview Banner -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="p-3 rounded" style="background: linear-gradient(135deg, rgba(0, 230, 118, 0.08) 0%, rgba(12, 28, 56, 0.85) 100%); border: 1px solid rgba(0, 230, 118, 0.35);">
                        <div class="row g-2 align-items-center text-center text-md-start">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                                    <span class="badge rounded-circle p-2" style="background: rgba(0, 230, 118, 0.2); color: #00e676;">
                                        <i class="fas fa-paper-plane"></i>
                                    </span>
                                    <div>
                                        <strong class="text-white d-block" style="font-size: 13px;">Rule 1: WhatsApp Messages</strong>
                                        <small class="text-success">500 PEPE / msg (Max 10 messages = 5,000 PEPE/day)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                                    <span class="badge rounded-circle p-2" style="background: rgba(13, 202, 240, 0.2); color: #0dcaf0;">
                                        <i class="fas fa-user-plus"></i>
                                    </span>
                                    <div>
                                        <strong class="text-white d-block" style="font-size: 13px;">Rule 2: Direct Registration</strong>
                                        <small class="text-info">500 PEPE on Direct Referral Sign-up (inactive)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-md-start">
                                    <span class="badge rounded-circle p-2" style="background: rgba(255, 193, 7, 0.2); color: #ffc107;">
                                        <i class="fas fa-bolt"></i>
                                    </span>
                                    <div>
                                        <strong class="text-white d-block" style="font-size: 13px;">Rule 3: Direct Activation</strong>
                                        <small class="text-warning">500 PEPE on Direct Referral Activation</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unified Reward History Card with Filter Pills -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h4 class="card-title text-white mb-0">
                                    <i class="fas fa-history text-success me-2"></i> PEPE Airdrop Rewards History
                                </h4>
                                <small class="text-muted">Filtered records showing rewards earned across all 3 promotion rules</small>
                            </div>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <a href="{{ route('member.pepe.redeemHistory') }}" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-wallet me-1"></i> Redeem History
                                </a>
                                <a href="{{ url('/member/dashboard') }}" class="btn btn-sm btn-success" style="background-color: #00e676; border-color: #00e676; color: #000; font-weight: 600;">
                                    <i class="fab fa-whatsapp me-1"></i> Send Today's Message
                                </a>
                            </div>
                        </div>

                        <!-- Filter Tabs / Pills -->
                        <div class="card-body pb-0 pt-3">
                            <ul class="nav nav-pills gap-2 flex-wrap" style="border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 12px;">
                                <li class="nav-item">
                                    <a class="nav-link {{ ($selectedType ?? 'all') === 'all' ? 'active' : '' }}"
                                       href="{{ route('member.whatsapp.referralDetails', ['type' => 'all']) }}"
                                       style="{{ ($selectedType ?? 'all') === 'all' ? 'background: #00e676; color: #000; font-weight: bold;' : 'background: rgba(255,255,255,0.06); color: #fff;' }}">
                                        <i class="fas fa-th-list me-1"></i> All Rewards ({{ ($msgCount ?? 0) + ($regCount ?? 0) + ($actCount ?? 0) }})
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ ($selectedType ?? '') === 'message' ? 'active' : '' }}"
                                       href="{{ route('member.whatsapp.referralDetails', ['type' => 'message']) }}"
                                       style="{{ ($selectedType ?? '') === 'message' ? 'background: #00e676; color: #000; font-weight: bold;' : 'background: rgba(255,255,255,0.06); color: #fff;' }}">
                                        <i class="fab fa-whatsapp me-1 text-success"></i> Rule 1: Messages ({{ $msgCount ?? 0 }})
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ ($selectedType ?? '') === 'direct_registration' ? 'active' : '' }}"
                                       href="{{ route('member.whatsapp.referralDetails', ['type' => 'direct_registration']) }}"
                                       style="{{ ($selectedType ?? '') === 'direct_registration' ? 'background: #0dcaf0; color: #000; font-weight: bold;' : 'background: rgba(255,255,255,0.06); color: #fff;' }}">
                                        <i class="fas fa-user-plus me-1 text-info"></i> Rule 2: Registrations ({{ $regCount ?? 0 }})
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ ($selectedType ?? '') === 'direct_activation' ? 'active' : '' }}"
                                       href="{{ route('member.whatsapp.referralDetails', ['type' => 'direct_activation']) }}"
                                       style="{{ ($selectedType ?? '') === 'direct_activation' ? 'background: #ffc107; color: #000; font-weight: bold;' : 'background: rgba(255,255,255,0.06); color: #fff;' }}">
                                        <i class="fas fa-bolt me-1 text-warning"></i> Rule 3: Activations ({{ $actCount ?? 0 }})
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Table -->
                        <div class="card-body pt-3">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" style="color: #e2e8f0;">
                                    <thead>
                                        <tr class="text-center" style="background: rgba(255, 255, 255, 0.08); color: #ffffff;">
                                            <th style="width: 60px;">S.No</th>
                                            <th style="width: 220px;">Reward Rule / Type</th>
                                            <th>Beneficiary / Reference Details</th>
                                            <th style="width: 150px;">Reward Amount</th>
                                            <th style="width: 120px;">Status</th>
                                            <th style="width: 180px;">Date &amp; Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rewardLogs as $index => $log)
                                            <tr>
                                                <td class="text-center">{{ $rewardLogs->firstItem() + $index }}</td>
                                                <td class="text-center">
                                                    @if(($log->reward_type ?? '') === 'direct_registration')
                                                        <span class="badge py-2 px-2" style="background: rgba(13, 202, 240, 0.2); color: #0dcaf0; border: 1px solid rgba(13, 202, 240, 0.4); font-size: 11px;">
                                                            <i class="fas fa-user-plus me-1"></i> Rule 2: Direct Registration
                                                        </span>
                                                    @elseif(($log->reward_type ?? '') === 'direct_activation')
                                                        <span class="badge py-2 px-2" style="background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.4); font-size: 11px;">
                                                            <i class="fas fa-bolt me-1"></i> Rule 3: Direct Activation
                                                        </span>
                                                    @else
                                                        <span class="badge py-2 px-2" style="background: rgba(0, 230, 118, 0.2); color: #00e676; border: 1px solid rgba(0, 230, 118, 0.4); font-size: 11px;">
                                                            <i class="fab fa-whatsapp me-1"></i> Rule 1: WhatsApp Message
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(($log->reward_type ?? '') === 'direct_registration')
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-user text-info me-2"></i>
                                                            <div>
                                                                <strong class="text-white">{{ $log->description ?: 'Direct Referral: '.$log->referred_member_id }}</strong>
                                                                @if($log->referred_member_id)
                                                                    <small class="text-white-50 d-block">Member ID: {{ $log->referred_member_id }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @elseif(($log->reward_type ?? '') === 'direct_activation')
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-bolt text-warning me-2"></i>
                                                            <div>
                                                                <strong class="text-white">{{ $log->description ?: 'Direct Activation: '.$log->referred_member_id }}</strong>
                                                                @if($log->referred_member_id)
                                                                    <small class="text-white-50 d-block">Activated ID: {{ $log->referred_member_id }}</small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="d-flex align-items-center">
                                                            <i class="fab fa-whatsapp text-success me-2"></i>
                                                            <div>
                                                                <strong class="text-white">{{ $log->mobile_number }}</strong>
                                                                <small class="text-white-50 d-block">{{ $log->description ?: 'Promotional WhatsApp Message' }}</small>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success py-1 px-2" style="font-size: 13px; font-weight: 700;">
                                                        +{{ number_format($log->reward_amount, 0) }} PEPE
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                        <i class="fas fa-check-circle me-1"></i> Credited
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <small style="color: #cbd5e1;">
                                                        <i class="far fa-clock me-1"></i> {{ $log->created_at ? $log->created_at->format('d-m-Y h:i A') : '-' }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @empty
                                            <!-- Fallback to referrals if rewardLogs is empty for older records -->
                                            @if(($selectedType ?? 'all') === 'all' && isset($referrals) && count($referrals) > 0)
                                                @foreach($referrals as $rIdx => $rRow)
                                                    <tr>
                                                        <td class="text-center">{{ $referrals->firstItem() + $rIdx }}</td>
                                                        <td class="text-center">
                                                            <span class="badge py-2 px-2" style="background: rgba(0, 230, 118, 0.2); color: #00e676; border: 1px solid rgba(0, 230, 118, 0.4); font-size: 11px;">
                                                                <i class="fab fa-whatsapp me-1"></i> Rule 1: WhatsApp Message
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <i class="fab fa-whatsapp text-success me-2"></i>
                                                                <div>
                                                                    <strong class="text-white">{{ $rRow->mobile_number }}</strong>
                                                                    <small class="text-white-50 d-block">Promotional WhatsApp Message</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-success py-1 px-2" style="font-size: 13px; font-weight: 700;">
                                                                +{{ number_format($rRow->reward_amount, 0) }} PEPE
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-info-subtle text-info border border-info-subtle">
                                                                <i class="fas fa-check-circle me-1"></i> {{ $rRow->status }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <small style="color: #cbd5e1;">
                                                                <i class="far fa-clock me-1"></i> {{ $rRow->created_at ? $rRow->created_at->format('d-m-Y h:i A') : '-' }}
                                                            </small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center py-5 text-muted">
                                                        <i class="far fa-folder-open fa-3x mb-3 d-block opacity-50"></i>
                                                        <h5>No rewards history found</h5>
                                                        <p class="small text-white-50 mb-3">
                                                            @if(($selectedType ?? '') === 'message')
                                                                Send up to 10 WhatsApp messages per day from your dashboard to earn 500 PEPE per message.
                                                            @elseif(($selectedType ?? '') === 'direct_registration')
                                                                Share your referral link. Each direct registration earns 500 PEPE tokens.
                                                            @elseif(($selectedType ?? '') === 'direct_activation')
                                                                When your direct referrals activate, you earn 500 PEPE tokens per activation.
                                                            @else
                                                                Start sharing your link or sending promotional messages to earn PEPE tokens.
                                                            @endif
                                                        </p>
                                                        <a href="{{ url('/member/dashboard') }}" class="btn btn-sm btn-success">
                                                            <i class="fab fa-whatsapp me-1"></i> Send Promotional Message
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($rewardLogs->hasPages())
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $rewardLogs->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
