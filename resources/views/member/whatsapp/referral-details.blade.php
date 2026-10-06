@extends('member.layouts.main')
@section('title', 'Referral Details')
@section('container')

    <div class="content-body">
        <div class="container-fluid pt-2">
            <!-- Page Header -->
            <div class="page-titles mb-3">
                <div class="welcome-text">
                    <h4 class="text-white font-weight-bold mb-1">Referral Details</h4>
                    <p class="mb-0 text-muted" style="font-size: 13px;">View your complete WhatsApp referral promotional history and earned PEPE Token rewards.</p>
                </div>
                <div class="justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/member/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Referral Details</a></li>
                    </ol>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-3 mb-4 text-center">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-start">
                                <small class="text-white-50 d-block mb-1" style="font-size: 12px; text-transform: uppercase;">Total Referrals</small>
                                <h3 class="text-white font-weight-bold mb-0">{{ number_format($totalReferrals) }}</h3>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(0, 230, 118, 0.15); width: 48px; height: 48px;">
                                <i class="fab fa-whatsapp text-success fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(0, 230, 118, 0.3); border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-start">
                                <small class="text-white-50 d-block mb-1" style="font-size: 12px; text-transform: uppercase;">Total PEPE Earned</small>
                                <h3 class="font-weight-bold mb-0" style="color: #00e676;">{{ number_format($totalPepeEarned, 0) }} PEPE</h3>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(0, 230, 118, 0.2); width: 48px; height: 48px;">
                                <i class="fas fa-coins text-success fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-start">
                                <small class="text-white-50 d-block mb-1" style="font-size: 12px; text-transform: uppercase;">Today's Referral</small>
                                <span class="badge {{ ($todayStatus === 'Shared Today' || $todayStatus === 'Used') ? 'bg-success' : 'bg-warning text-dark' }} font-weight-bold" style="font-size: 13px;">
                                    {{ ($todayStatus === 'Shared Today' || $todayStatus === 'Used') ? 'Shared Today' : 'Available' }}
                                </span>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(255, 193, 7, 0.15); width: 48px; height: 48px;">
                                <i class="fas fa-calendar-day text-warning fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-start">
                                <small class="text-white-50 d-block mb-1" style="font-size: 12px; text-transform: uppercase;">Last Referral Date</small>
                                <h6 class="text-white font-weight-bold mb-0" style="font-size: 13px;">
                                    {{ $lastReferralDate ? date('d-m-Y h:i A', strtotime($lastReferralDate)) : 'N/A' }}
                                </h6>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background: rgba(13, 202, 240, 0.15); width: 48px; height: 48px;">
                                <i class="fas fa-history text-info fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral History Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h4 class="card-title text-white mb-0">WhatsApp Referral History</h4>
                            <div class="d-flex gap-2">
                                <a href="{{ route('member.pepe.redeemHistory') }}" class="btn btn-sm btn-outline-success me-2">
                                    <i class="fas fa-history me-1"></i> PEPE Redeem History
                                </a>
                                <a href="{{ url('/member/dashboard') }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-plus-circle me-1"></i> Send New Referral
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" style="color: #e2e8f0;">
                                    <thead>
                                        <tr class="text-center" style="background: rgba(255, 255, 255, 0.08); color: #ffffff;">
                                            <th>S.No</th>
                                            <th>Mobile Number</th>
                                            <th>Reward Amount</th>
                                            <th>Status</th>
                                            <th>Date & Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($referrals as $index => $row)
                                            <tr>
                                                <td>{{ $referrals->firstItem() + $index }}</td>
                                                <td class="text-center">
                                                    <strong class="text-white">
                                                        <i class="fab fa-whatsapp text-success me-1"></i> {{ $row->mobile_number }}
                                                    </strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success" style="font-size: 13px;">
                                                        +{{ number_format($row->reward_amount, 0) }} PEPE
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-check-circle me-1"></i> {{ $row->status }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <small style="color: #cbd5e1;">
                                                        <i class="far fa-clock me-1"></i> {{ $row->created_at ? $row->created_at->format('d-m-Y h:i A') : '-' }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    <i class="far fa-folder-open fa-3x mb-3 d-block"></i>
                                                    No WhatsApp referrals sent yet. Go to your dashboard to send your daily referral and claim 1000 PEPE!
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($referrals->hasPages())
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $referrals->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
