@extends('member.layouts.main')
@section('title', 'PEPE Tokens Redeem History')
@section('container')

    <div class="content-body">
        <div class="container-fluid pt-2">
            <!-- Page Header -->
            <div class="page-titles mb-3">
                <div class="welcome-text">
                    <h4 class="text-white font-weight-bold mb-1">
                        <i class="fas fa-history text-success me-2"></i> PEPE Tokens Redeem History
                    </h4>
                    <p class="mb-0 text-muted" style="font-size: 13px;">
                        Track your PEPE token redemption requests to your BEP-20 wallet address and admin processing status.
                    </p>
                </div>
                <div class="justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/member/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/member/whatsapp/referral-details') }}">WhatsApp
                                Referral</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Redeem History</a></li>
                    </ol>
                </div>
            </div>

            <!-- Summary Stat Cards -->
            <div class="row g-3 mb-4 text-center">
                <!-- Current PEPE Wallet -->
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3 h-100"
                        style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(0, 230, 118, 0.35); border-radius: 14px; box-shadow: 0 4px 20px rgba(0, 230, 118, 0.08);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-start">
                                <small class="text-white-50 d-block mb-1"
                                    style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Current PEPE
                                    Wallet</small>
                                <h3 class="font-weight-bold mb-0" id="pepeWalletDisplay" style="color: #00e676;">
                                    {{ number_format($walletBalance ?? ($data['pepe_wallet'] ?? 0), 0) }} <span
                                        style="font-size: 15px;">PEPE</span>
                                </h3>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="background: rgba(0, 230, 118, 0.15); width: 48px; height: 48px;">
                                <i class="fas fa-wallet text-success fa-lg"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2"
                            style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                            <small class="text-muted" style="font-size: 11px;">Redeemed Balance</small>
                            <span class="badge bg-success text-dark font-weight-bold" style="font-size: 10px;">Active</span>
                        </div>
                    </div>
                </div>

                <!-- Available to Redeem -->
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3 h-100"
                        style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(245, 158, 11, 0.35); border-radius: 14px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-start">
                                <small class="text-white-50 d-block mb-1"
                                    style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Available to
                                    Redeem</small>
                                <h3 class="text-white font-weight-bold mb-0">
                                    {{ number_format($availablePepe, 0) }} <span
                                        style="font-size: 15px; color: #F59E0B;">PEPE</span>
                                </h3>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="background: rgba(245, 158, 11, 0.15); width: 48px; height: 48px;">
                                <i class="fas fa-coins text-warning fa-lg"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2"
                            style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                            <small class="text-muted" style="font-size: 11px;">From WhatsApp Promos</small>

                        </div>
                    </div>
                </div>

                <!-- Total Redeemed (Approved) -->
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3 h-100"
                        style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 14px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-start">
                                <small class="text-white-50 d-block mb-1"
                                    style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Total
                                    Redeemed</small>
                                <h3 class="text-white font-weight-bold mb-0">
                                    {{ number_format($totalRedeemed, 0) }} <span
                                        style="font-size: 15px; color: #00e676;">PEPE</span>
                                </h3>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="background: rgba(40, 167, 69, 0.15); width: 48px; height: 48px;">
                                <i class="fas fa-check-double text-success fa-lg"></i>
                            </div>
                        </div>
                        <div class="text-start mt-2 pt-2" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                            <small class="text-muted" style="font-size: 11px;">Credited to PEPE wallet</small>
                        </div>
                    </div>
                </div>

                <!-- Total Redemptions -->
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="card p-3 h-100"
                        style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 14px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-start">
                                <small class="text-white-50 d-block mb-1"
                                    style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Total
                                    Redemptions</small>
                                <h3 class="text-white font-weight-bold mb-0">{{ number_format($totalRequests) }}</h3>
                            </div>
                            <div class="rounded-circle p-3 d-flex align-items-center justify-content-center"
                                style="background: rgba(245, 158, 11, 0.15); width: 48px; height: 48px;">
                                <i class="fas fa-list-alt text-warning fa-lg"></i>
                            </div>
                        </div>
                        <div class="text-start mt-2 pt-2" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                            <small class="text-muted" style="font-size: 11px;">All completed redemptions</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- History Table Card -->
            <div class="row">
                <div class="col-12">
                    <div class="card"
                        style="background: #0C0E14; border: 1px solid rgba(245, 158, 11, 0.18); border-radius: 16px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.55);">
                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2"
                            style="border-bottom: 1px solid rgba(245, 158, 11, 0.15); padding: 18px 24px;">
                            <div class="d-flex align-items-center">
                                <div class="p-2 rounded me-3"
                                    style="background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3);">
                                    <i class="fas fa-file-invoice-dollar text-success fa-lg"></i>
                                </div>
                                <div>
                                    <h4 class="card-title text-white mb-0" style="font-size: 18px; font-weight: 700;">
                                        Redemption Records
                                    </h4>
                                    <small class="text-muted" style="font-size: 12px;">Detailed ledger of all PEPE Token
                                        BEP-20 payouts</small>
                                </div>
                            </div>

                            <!-- Filter & Action Buttons -->
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                {{-- <div class="btn-group btn-group-sm" role="group" aria-label="Status Filters">
                                    <a href="{{ route('member.pepe.redeemHistory') }}"
                                        class="btn {{ empty($status) ? 'btn-success text-dark font-weight-bold' : 'btn-outline-secondary text-white' }}"
                                        style="font-size: 12px;">
                                        All ({{ $totalRequests }})
                                    </a>
                                    <a href="{{ route('member.pepe.redeemHistory', ['status' => 'Pending']) }}"
                                        class="btn {{ $status === 'Pending' ? 'btn-warning text-dark font-weight-bold' : 'btn-outline-secondary text-white' }}"
                                        style="font-size: 12px;">
                                        Pending ({{ $pendingCount }})
                                    </a>
                                    <a href="{{ route('member.pepe.redeemHistory', ['status' => 'Approved']) }}"
                                        class="btn {{ $status === 'Approved' ? 'btn-success text-dark font-weight-bold' : 'btn-outline-secondary text-white' }}"
                                        style="font-size: 12px;">
                                        Approved
                                    </a>
                                    <a href="{{ route('member.pepe.redeemHistory', ['status' => 'Cancelled']) }}"
                                        class="btn {{ $status === 'Cancelled' ? 'btn-danger font-weight-bold' : 'btn-outline-secondary text-white' }}"
                                        style="font-size: 12px;">
                                        Rejected
                                    </a>
                                </div> --}}


                                <button type="button" class="btn btn-sm font-weight-bold text-dark ms-2"
                                    style="background: linear-gradient(135deg, #F59E0B 0%, #10B981 100%); border-radius: 6px; border: none; font-size: 12px; padding: 6px 14px;">
                                    <a href="{{ url('member/dashboard') }}" style="color: #08090C !important; text-decoration: none;"> <i class="fas fa-plus-circle me-1"></i> New
                                        Redeem </a>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0"
                                    style="color: #e2e8f0; font-size: 13px;">
                                    <thead>
                                        <tr class="text-center"
                                            style="background: rgba(255, 255, 255, 0.04); color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                                            <th class="py-3" style="width: 60px;">#</th>
                                            <th class="py-3">Request ID</th>
                                            <th class="py-3">Redeem Amount</th>
                                            <th class="py-3 text-start" style="min-width: 200px;">BEP-20 Wallet Address
                                            </th>
                                            <th class="py-3" style="min-width: 170px;">TXN Hash</th>
                                            <th class="py-3">Status</th>
                                            {{-- <th class="py-3">Request Date</th> --}}
                                            <th class="py-3">Completed Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($redeemRequests as $index => $row)
                                            <tr class="text-center"
                                                style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                                <td class="text-muted">{{ $redeemRequests->firstItem() + $index }}</td>
                                                <td>
                                                    <span class="badge"
                                                        style="background: rgba(15, 23, 42, 0.8); border: 1px solid rgba(0, 230, 118, 0.3); color: #00e676; font-family: monospace; font-size: 12px; padding: 5px 8px;">
                                                        {{ $row->request_id }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-inline-flex align-items-center">
                                                        <span class="badge p-2"
                                                            style="background: rgba(0, 230, 118, 0.15); color: #00e676; font-size: 13px; font-weight: 700;">
                                                            <i class="fas fa-coins me-1"></i>
                                                            {{ number_format($row->gross_amount, 0) }} PEPE
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-start">
                                                    @if ($row->wallet_address)
                                                        <div class="d-flex align-items-center">
                                                            <span class="font-monospace text-white-50"
                                                                style="font-size: 12px;"
                                                                title="{{ $row->wallet_address }}">
                                                                {{ substr($row->wallet_address, 0, 8) }}...{{ substr($row->wallet_address, -6) }}
                                                            </span>
                                                            <button type="button"
                                                                class="btn btn-link btn-sm text-info p-0 ms-2"
                                                                onclick="copyToClipboard('{{ $row->wallet_address }}')"
                                                                title="Copy Wallet Address">
                                                                <i class="far fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($row->txnid)
                                                        <div class="d-inline-flex align-items-center">
                                                            <span class="font-monospace text-info"
                                                                style="font-size: 12px;" title="{{ $row->txnid }}">
                                                                {{ substr($row->txnid, 0, 6) }}...{{ substr($row->txnid, -4) }}
                                                            </span>
                                                            <button type="button"
                                                                class="btn btn-link btn-sm text-info p-0 ms-2"
                                                                onclick="copyToClipboard('{{ $row->txnid }}')"
                                                                title="Copy TXN Hash">
                                                                <i class="far fa-copy"></i>
                                                            </button>
                                                            @if (str_starts_with($row->txnid, '0x'))
                                                                <a href="{{ rtrim($pepeSettings->explorer_url ?? 'https://bscscan.com', '/') }}/tx/{{ $row->txnid }}"
                                                                    target="_blank" class="ms-1 text-success"
                                                                    title="View on BscScan">
                                                                    <i class="fas fa-external-link-alt fa-xs"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @elseif($row->status === 'Approved')
                                                        <span class="badge bg-success text-white"
                                                            style="font-size: 11px;">Paid by Admin</span>
                                                    @elseif($row->status === 'Pending')
                                                        <span class="badge bg-secondary text-white-50"
                                                            style="font-size: 11px;">
                                                            <i class="fas fa-spinner fa-spin me-1"></i> Awaiting Transfer
                                                        </span>
                                                    @else
                                                        <span class="text-muted">--</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($row->status === 'Approved')
                                                        <span class="badge"
                                                            style="background: rgba(40, 167, 69, 0.2); border: 1px solid #28a745; color: #4ade80; font-size: 12px; padding: 6px 12px; border-radius: 20px;">
                                                            <i class="fas fa-check-circle me-1"></i> Approved
                                                        </span>
                                                    @elseif ($row->status === 'Cancelled')
                                                        <span class="badge"
                                                            style="background: rgba(220, 53, 69, 0.2); border: 1px solid #dc3545; color: #f87171; font-size: 12px; padding: 6px 12px; border-radius: 20px;"
                                                            title="Tokens refunded to your PEPE wallet">
                                                            <i class="fas fa-times-circle me-1"></i> Rejected (Refunded)
                                                        </span>
                                                    @else
                                                        <span class="badge"
                                                            style="background: rgba(255, 193, 7, 0.2); border: 1px solid #ffc107; color: #fde047; font-size: 12px; padding: 6px 12px; border-radius: 20px;">
                                                            <i class="fas fa-clock me-1"></i> Pending Approval
                                                        </span>
                                                    @endif
                                                </td>
                                                {{-- <td>
                                                    <small style="color: #cbd5e1;">
                                                        <i class="far fa-calendar-alt me-1 text-white-50"></i>
                                                        {{ $row->request_date ? date('d-m-Y', strtotime($row->request_date)) : ($row->created_at ? $row->created_at->format('d-m-Y h:i A') : '--') }}
                                                    </small>
                                                </td> --}}
                                                <td>
                                                    @if ($row->created_at)
                                                        <small style="color: #cbd5e1;">
                                                            <i class="far fa-calendar-check me-1 text-success"></i>
                                                            {{ date('d-m-Y h:i A', strtotime($row->created_at)) }}
                                                        </small>
                                                    @elseif($row->status === 'Approved' && $row->created_at)
                                                        <small style="color: #cbd5e1;">
                                                            <i class="far fa-calendar-check me-1 text-success"></i>
                                                            {{ $row->created_at->format('d-m-Y h:i A') }}
                                                        </small>
                                                    @elseif($row->status === 'Cancelled' && $row->created_at)
                                                        <small class="text-danger">
                                                            <i class="far fa-calendar-times me-1"></i>
                                                            {{ $row->created_at->format('d-m-Y h:i A') }}
                                                        </small>
                                                    @else
                                                        <span class="text-muted">--</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <div class="p-4"
                                                        style="background: rgba(255, 255, 255, 0.02); border-radius: 12px;">
                                                        <div class="rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3"
                                                            style="background: rgba(0, 230, 118, 0.1); width: 64px; height: 64px;">
                                                            <i class="fas fa-coins text-success fa-2x"></i>
                                                        </div>
                                                        <h5 class="text-white font-weight-bold mb-2">No PEPE Redemption
                                                            Records Found</h5>
                                                        <p class="text-muted mb-3"
                                                            style="font-size: 13px; max-width: 450px; margin: 0 auto;">
                                                            @if (!empty($status))
                                                                There are no {{ strtolower($status) }} PEPE redeem requests
                                                                matching your filter.
                                                            @else
                                                                You haven't submitted any PEPE token redemption requests
                                                                yet. Send daily WhatsApp promotional messages to earn PEPE
                                                                tokens and redeem them here!
                                                            @endif
                                                        </p>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            @if (!empty($status))
                                                                <a href="{{ route('member.pepe.redeemHistory') }}"
                                                                    class="btn btn-sm btn-outline-secondary text-white">
                                                                    Clear Filter
                                                                </a>
                                                            @else
                                                                <a href="{{ url('/member/dashboard') }}"
                                                                    class="btn btn-sm btn-success text-dark font-weight-bold">
                                                                    <i class="fab fa-whatsapp me-1"></i> Earn PEPE Tokens
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($redeemRequests->hasPages())
                                <div class="d-flex justify-content-between align-items-center p-3"
                                    style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                                    <small class="text-muted">
                                        Showing {{ $redeemRequests->firstItem() }} to {{ $redeemRequests->lastItem() }} of
                                        {{ $redeemRequests->total() }} entries
                                    </small>
                                    <div>
                                        {{ $redeemRequests->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 & Ethers.js Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/5.7.2/ethers.umd.js"></script> --}}

    <!-- SweetAlert2 Scripts for Instant Redemption & Clipboard -->
    <script>
        function copyToClipboard(text) {
            if (!navigator.clipboard) {
                var textArea = document.createElement("textarea");
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Copied to Clipboard!',
                            text: text,
                            timer: 1500,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end'
                        });
                    }
                } catch (err) {
                    // ignore
                }
                document.body.removeChild(textArea);
                return;
            }
            navigator.clipboard.writeText(text).then(function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied to Clipboard!',
                        text: text,
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            });
        }

        // window.pepeDappSettings = {
        //     contract_address: "{{ $pepeSettings->contract_address ?? '0x25d887Ce7a35172C62FeBFD67a1856F20FaEbB00' }}",
        //     claim_contract_address: "{{ $pepeSettings->claim_contract_address ?? '' }}",
        //     token_symbol: "{{ $pepeSettings->token_symbol ?? 'PEPE' }}",
        //     token_name: "{{ $pepeSettings->token_name ?? 'PEPE BEP-20' }}",
        //     token_decimals: {{ (int) ($pepeSettings->token_decimals ?? 18) }},
        //     chain_id: {{ (int) ($pepeSettings->chain_id ?? 56) }},
        //     network_name: "{{ $pepeSettings->network_name ?? 'BNB Smart Chain (BEP20)' }}",
        //     rpc_url: "{{ $pepeSettings->rpc_url ?? 'https://bsc-dataseed.binance.org/' }}",
        //     explorer_url: "{{ rtrim($pepeSettings->explorer_url ?? 'https://bscscan.com', '/') }}",
        //     gas_limit: {{ (int) ($pepeSettings->gas_limit ?? 180000) }},
        //     min_redeem: {{ (float) ($pepeSettings->min_redeem ?? 1) }},
        //     is_active: {{ $pepeSettings->is_active ?? true ? 'true' : 'false' }},
        //     token_abi: {!! json_encode(json_decode($pepeSettings->getEffectiveAbi())) !!},
        //     claim_contract_abi: {!! json_encode(json_decode($pepeSettings->getEffectiveClaimAbi())) !!}
        // };

        // function getActiveWeb3Provider() {
        //     if (typeof window.ethereum !== 'undefined') {
        //         if (window.ethereum.providers && window.ethereum.providers.length > 0) {
        //             return window.ethereum.selectedProvider || window.ethereum.providers[0];
        //         }
        //         return window.ethereum;
        //     }
        //     if (typeof window.trustwallet !== 'undefined') return window.trustwallet;
        //     if (typeof window.okxwallet !== 'undefined') return window.okxwallet;
        //     if (typeof window.BinanceChain !== 'undefined') return window.BinanceChain;
        //     if (typeof window.bitkeep !== 'undefined' && window.bitkeep.ethereum) return window.bitkeep.ethereum;
        //     if (typeof window.tokenpocket !== 'undefined' && window.tokenpocket.ethereum) return window.tokenpocket
        //     .ethereum;
        //     return null;
        // }

        // function getWalletBrandName(provider) {
        //     if (!provider) return 'Web3 Wallet';
        //     if (provider.isTrust || provider.isTrustWallet) return 'Trust Wallet';
        //     if (provider.isMetaMask && !provider.isBraveWallet && !provider.isTokenPocket) return 'MetaMask';
        //     if (provider.isOkxWallet || provider.isOKExWallet) return 'OKX Wallet';
        //     if (provider.isBinance || provider.isBinanceChain) return 'Binance Wallet';
        //     if (provider.isCoinbaseWallet) return 'Coinbase Wallet';
        //     if (provider.isTokenPocket) return 'TokenPocket';
        //     if (provider.isBitKeep || provider.isBitget) return 'Bitget Wallet';
        //     if (provider.isMathWallet) return 'Math Wallet';
        //     return 'Web3 Wallet';
        // }

        // window.openPepeRedeemPrompt = function() {
        //     var settings = window.pepeDappSettings || {};
        //     if (settings.is_active === false) {
        //         Swal.fire({
        //             icon: 'warning',
        //             title: 'Maintenance in Progress',
        //             text: 'PEPE Token redemption is currently undergoing scheduled maintenance. Please check back shortly.',
        //             confirmButtonColor: '#00e676'
        //         });
        //         return;
        //     }

        //     var currentBalance = Math.max({{ (float) ($availablePepe ?? 0) }},
        //         {{ (float) ($walletBalance ?? ($data['pepe_wallet'] ?? 0)) }});
        //     var walletAddress = "{{ $data['member_wallet'] ?? ($data['wallet_address'] ?? session('address')) }}";
        //     var minRedeem = settings.min_redeem || 1;

        //     if (typeof Swal === 'undefined') {
        //         alert('Loading components, please try again in a second.');
        //         return;
        //     }

        //     if (currentBalance <= 0) {
        //         Swal.fire({
        //             icon: 'info',
        //             title: 'No PEPE Tokens to Redeem',
        //             text: 'You have 0 PEPE tokens available to redeem. Promote your referral link via WhatsApp daily to earn 1,000 PEPE tokens!',
        //             confirmButtonColor: '#00e676'
        //         });
        //         return;
        //     }

        //     Swal.fire({
        //         title: '<span style="color: #00e676; font-weight: 700;"><i class="fas fa-coins me-1"></i> DApp PEPE Token Redemption</span>',
        //         html: `
    //             <div class="text-start p-2" style="font-size: 13px;">
    //                 <div class="mb-3 p-3 rounded text-white" style="background: rgba(15, 23, 42, 0.95); border: 1px solid rgba(0, 230, 118, 0.35);">
    //                     <div class="d-flex justify-content-between align-items-center mb-1">
    //                         <span class="text-white-50" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Available to Redeem:</span>
    //                         <span class="badge" style="background: rgba(243, 186, 47, 0.2); color: #f3ba2f; border: 1px solid rgba(243, 186, 47, 0.4); font-size: 10px;">BEP-20 (BSC)</span>
    //                     </div>
    //                     <strong style="color: #00e676; font-size: 20px;">` + Number(currentBalance)
        //             .toLocaleString() + ` ` + (settings.token_symbol || 'PEPE') + `</strong>
    //                 </div>

    //                 <div class="mb-3">
    //                     <label class="font-weight-bold text-white mb-1">Target BEP-20 Wallet Address:</label>
    //                     <input type="text" id="swal_pepe_wallet" class="form-control" value="` + (walletAddress ||
        //                 '') +
        //             `" placeholder="0x..." readonly style="font-family: monospace; font-size: 12px; background: rgba(255, 255, 255, 0.08); color: #00e676; cursor: not-allowed; border: 1px solid rgba(0, 230, 118, 0.4);">
    //                     <small class="text-white-50"><i class="fas fa-lock me-1"></i> Tokens will be redeemed directly to this address via your connected Web3 wallet on BNB Smart Chain.</small>
    //                 </div>

    //                 <div class="mb-3">
    //                     <label class="font-weight-bold text-white mb-1">Tokens to Redeem:</label>
    //                     <input type="number" id="swal_pepe_amount" class="form-control font-weight-bold text-success" value="` +
        //             currentBalance + `" max="` + currentBalance + `" min="` + minRedeem + `" step="any" style="font-size: 14px; font-weight: 600;">
    //                     <small class="text-muted">Enter tokens to redeem (min: ` + minRedeem + ` ` + (settings
        //                 .token_symbol || 'PEPE') +
        //             `)</small>
    //                 </div>

    //                 <div class="p-2 rounded bg-dark border border-secondary text-start" style="font-size: 12px;">
    //                     <span class="text-info"><i class="fas fa-network-wired me-1"></i> <strong>Network:</strong> ` +
        //             (settings.network_name ||
        //                 'BNB Smart Chain (BEP20)') +
        //             `</span><br>
    //                     <span class="text-white-50"><i class="fas fa-file-contract me-1"></i> Token Contract: <code class="text-success small">` +
        //             (
        //                 settings.contract_address ? (settings.contract_address.substring(0, 8) + '...' +
        //                     settings.contract_address.substring(settings.contract_address.length - 6)) : '') + `</code></span>
    //                 </div>
    //             </div>
    //         `,
        //         showCancelButton: true,
        //         confirmButtonText: '<i class="fas fa-bolt me-1"></i> Confirm & Claim via Wallet',
        //         cancelButtonText: 'Cancel',
        //         confirmButtonColor: '#00e676',
        //         cancelButtonColor: '#d33',
        //         focusConfirm: false,
        //         preConfirm: () => {
        //             var amountEl = document.getElementById('swal_pepe_amount');
        //             var walletEl = document.getElementById('swal_pepe_wallet');
        //             var amount = amountEl ? parseFloat(amountEl.value) : 0;
        //             var targetWallet = walletEl ? walletEl.value.trim() : '';

        //             if (!targetWallet || targetWallet.length < 10) {
        //                 Swal.showValidationMessage(
        //                     'Please enter a valid BEP-20 wallet address (starting with 0x).');
        //                 return false;
        //             }
        //             if (!amount || isNaN(amount) || amount <= 0) {
        //                 Swal.showValidationMessage('Please enter a valid amount greater than 0.');
        //                 return false;
        //             }
        //             if (amount > currentBalance) {
        //                 Swal.showValidationMessage('Amount cannot exceed available tokens of ' + Number(
        //                     currentBalance).toLocaleString() + ' ' + (settings.token_symbol ||
        //                     'PEPE') + '.');
        //                 return false;
        //             }
        //             if (amount < minRedeem) {
        //                 Swal.showValidationMessage('Minimum redeem amount is ' + minRedeem + ' ' + (settings
        //                     .token_symbol || 'PEPE') + '.');
        //                 return false;
        //             }
        //             return {
        //                 amount: amount,
        //                 wallet_address: targetWallet
        //             };
        //         }
        //     }).then((result) => {
        //         if (result.isConfirmed && result.value) {
        //             window.submitPepeRedeem(result.value.amount, result.value.wallet_address);
        //         }
        //     });
        // };

        // window.submitPepeRedeem = async function(amount, walletAddress) {
        //     var config = window.pepeDappSettings || {};

        //     // 1. Check Web3 provider (supports MetaMask, Trust Wallet, OKX, Binance, mobile wallets)
        //     const activeProvider = getActiveWeb3Provider();
        //     if (!activeProvider) {
        //         Swal.fire({
        //             icon: 'warning',
        //             title: 'Web3 Wallet Required',
        //             html: '<p class="text-white">To claim real PEPE tokens to your BEP-20 address, please open this DApp in your <b>Web3 Wallet</b> (Trust Wallet, MetaMask, Binance Web3, OKX, etc.) or install a wallet browser extension.</p>',
        //             confirmButtonColor: '#00e676'
        //         });
        //         return;
        //     }

        //     const walletName = getWalletBrandName(activeProvider);

        //     try {
        //         // 2. Request Wallet connection
        //         Swal.fire({
        //             title: 'Connecting to ' + walletName + '...',
        //             html: '<div class="p-2 text-center"><div class="spinner-border text-success mb-2" role="status"></div><p class="text-white mb-0">Requesting Web3 wallet connection in ' +
        //                 walletName + '...</p></div>',
        //             allowOutsideClick: false,
        //             showConfirmButton: false,
        //             didOpen: () => {
        //                 Swal.showLoading();
        //             }
        //         });

        //         const accounts = await activeProvider.request({
        //             method: 'eth_requestAccounts'
        //         });
        //         if (!accounts || accounts.length === 0) {
        //             throw new Error('Please connect your ' + walletName + ' account to continue.');
        //         }
        //         const activeWallet = accounts[0];

        //         // 3. Ensure BNB Smart Chain (Chain ID 56)
        //         const targetChainId = config.chain_id || 56;
        //         const targetChainIdHex = '0x' + targetChainId.toString(16);
        //         const currentChainId = await activeProvider.request({
        //             method: 'eth_chainId'
        //         });

        //         if (currentChainId !== targetChainIdHex) {
        //             Swal.fire({
        //                 title: 'Switching Network...',
        //                 html: '<div class="p-2 text-center"><div class="spinner-border text-success mb-2" role="status"></div><p class="text-white mb-0">Switching network to <b>' +
        //                     (config.network_name || 'BNB Smart Chain') + '</b> in ' + walletName +
        //                     '...</p></div>',
        //                 allowOutsideClick: false,
        //                 showConfirmButton: false,
        //                 didOpen: () => {
        //                     Swal.showLoading();
        //                 }
        //             });

        //             try {
        //                 await activeProvider.request({
        //                     method: 'wallet_switchEthereumChain',
        //                     params: [{
        //                         chainId: targetChainIdHex
        //                     }]
        //                 });
        //             } catch (switchError) {
        //                 if (switchError.code === 4902) {
        //                     await activeProvider.request({
        //                         method: 'wallet_addEthereumChain',
        //                         params: [{
        //                             chainId: targetChainIdHex,
        //                             chainName: config.network_name || 'BNB Smart Chain',
        //                             nativeCurrency: {
        //                                 name: 'BNB',
        //                                 symbol: 'BNB',
        //                                 decimals: 18
        //                             },
        //                             rpcUrls: [config.rpc_url ||
        //                                 'https://bsc-dataseed.binance.org/'],
        //                             blockExplorerUrls: [config.explorer_url ||
        //                                 'https://bscscan.com']
        //                         }]
        //                     });
        //                 } else {
        //                     throw switchError;
        //                 }
        //             }
        //         }

        //         // 4. Request cryptographically signed claim voucher from backend
        //         Swal.fire({
        //             title: 'Preparing Claim Authorization...',
        //             html: '<div class="p-2 text-center"><div class="spinner-border text-success mb-2" role="status"></div><p class="text-white mb-0">Authorizing claim voucher with cryptographic signature...</p></div>',
        //             allowOutsideClick: false,
        //             showConfirmButton: false,
        //             didOpen: () => {
        //                 Swal.showLoading();
        //             }
        //         });

        //         const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector(
        //             'meta[name="csrf-token"]').content : '{{ csrf_token() }}';
        //         const voucherRes = await fetch('{{ route('member.pepe.prepareClaim') }}', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'Accept': 'application/json',
        //                 'X-CSRF-TOKEN': csrfToken
        //             },
        //             body: JSON.stringify({
        //                 amount: amount,
        //                 wallet_address: activeWallet
        //             })
        //         });

        //         const voucherData = await voucherRes.json().catch(() => ({
        //             success: false,
        //             message: 'Server returned error ' + voucherRes.status
        //         }));
        //         if (!voucherRes.ok || !voucherData.success) {
        //             throw new Error(voucherData.message || 'Failed to prepare claim voucher.');
        //         }

        //         const v = voucherData.voucher;
        //         const isContractClaim = voucherData.mode === 'contract_claim' && v.contract_address;
        //         const provider = new ethers.providers.Web3Provider(activeProvider);
        //         const signer = provider.getSigner();

        //         let onChainTxHash = '';
        //         let explorerTxUrl = '';

        //         if (isContractClaim) {
        //             // 5A. Smart Contract Claim execution on BNB Smart Chain
        //             Swal.fire({
        //                 title: '<span style="color: #00e676;"><i class="fas fa-wallet me-2"></i>Confirm in Wallet</span>',
        //                 html: `
    //                     <div class="text-start p-2" style="font-size: 13px;">
    //                         <p class="text-white mb-2">Please approve the claim transaction in your <b>` +
        //                     walletName + `</b> to receive your PEPE tokens directly from the Smart Contract.</p>
    //                         <div class="p-2 rounded bg-dark border border-secondary mb-2" style="font-size: 12px;">
    //                             <span class="text-white-50">Claiming:</span> <strong class="text-success">` +
        //                     Number(v.amount).toLocaleString() + ` ` + (v.token_symbol || 'PEPE') + `</strong><br>
    //                             <span class="text-white-50">Recipient:</span> <code class="text-white small">` +
        //                     activeWallet.substring(0, 8) + '...' + activeWallet.substring(activeWallet
        //                         .length - 6) +
        //                     `</code><br>
    //                             <span class="text-white-50">Distributor:</span> <code class="text-success small">` + v
        //                     .contract_address.substring(0, 8) + '...' + v.contract_address.substring(v
        //                         .contract_address.length - 6) + `</code>
    //                         </div>
    //                         <small class="text-muted"><i class="fas fa-info-circle me-1"></i>A small BNB Smart Chain network gas fee will be paid via your wallet.</small>
    //                     </div>
    //                 `,
        //                 allowOutsideClick: false,
        //                 showConfirmButton: false,
        //                 didOpen: () => {
        //                     Swal.showLoading();
        //                 }
        //             });

        //             const claimContractAbi = voucherData.claim_contract_abi || config.claim_contract_abi;
        //             const contract = new ethers.Contract(v.contract_address, claimContractAbi, signer);

        //             const tx = await contract.claim(
        //                 v.amount_raw,
        //                 v.nonce,
        //                 v.deadline,
        //                 v.signature, {
        //                     gasLimit: v.gas_limit || 180000
        //                 }
        //             );

        //             explorerTxUrl = (config.explorer_url || 'https://bscscan.com').replace(/\/+$/, '') + '/tx/' + tx
        //                 .hash;
        //             Swal.fire({
        //                 title: 'Claiming on BNB Smart Chain...',
        //                 html: `
    //                     <div class="p-2 text-center">
    //                         <div class="spinner-border text-success mb-3" role="status"></div>
    //                         <p class="text-white mb-2"><b>Transaction Submitted to Blockchain!</b></p>
    //                         <p class="text-white-50 small mb-2">Waiting for on-chain block confirmation...</p>
    //                         <code class="text-success small d-block text-truncate mb-3" style="font-size: 11px;">` +
        //                     tx.hash + `</code>
    //                         <a href="` + explorerTxUrl + `" target="_blank" class="btn btn-sm btn-outline-info font-weight-bold">
    //                             <i class="fas fa-external-link-alt me-1"></i> Track on BscScan
    //                         </a>
    //                     </div>
    //                 `,
        //                 allowOutsideClick: false,
        //                 showConfirmButton: false,
        //                 didOpen: () => {
        //                     Swal.showLoading();
        //                 }
        //             });

        //             await tx.wait(1);
        //             onChainTxHash = tx.hash;
        //         } else {
        //             // 5B. Web3 Wallet Cryptographic Confirmation (Universal across Trust Wallet, MetaMask, OKX, etc. with 0 gas fee)
        //             Swal.fire({
        //                 title: '<span style="color: #00e676;"><i class="fas fa-wallet me-2"></i>Confirm in Wallet</span>',
        //                 html: `
    //                     <div class="text-start p-2" style="font-size: 13px;">
    //                         <p class="text-white mb-2">Please approve the redemption authorization in your <b>` +
        //                     walletName + `</b>.</p>
    //                         <div class="p-2 rounded bg-dark border border-secondary mb-2" style="font-size: 12px;">
    //                             <span class="text-white-50">Redeeming:</span> <strong class="text-success">` +
        //                     Number(v.amount).toLocaleString() + ` ` + (v.token_symbol || 'PEPE') +
        //                     `</strong><br>
    //                             <span class="text-white-50">Recipient Wallet:</span> <code class="text-white small">` + activeWallet.substring(0, 8) + '...' + activeWallet.substring(
        //                         activeWallet.length - 6) +
        //                     `</code>
    //                         </div>
    //                         <small class="text-success"><i class="fas fa-shield-alt me-1"></i>Cryptographic wallet verification via ` +
        //                     walletName + `.</small>
    //                     </div>
    //                 `,
        //                 allowOutsideClick: false,
        //                 showConfirmButton: false,
        //                 didOpen: () => {
        //                     Swal.showLoading();
        //                 }
        //             });

        //             const signMsg = v.sign_message || ("Authorize PEPE Token Redemption\nAmount: " + v.amount +
        //                 " PEPE\nRecipient: " + activeWallet + "\nNonce: " + v.nonce);
        //             const userSignature = await signer.signMessage(signMsg);

        //             onChainTxHash = ethers.utils.keccak256(ethers.utils.toUtf8Bytes(userSignature + ':' + v.nonce));
        //             explorerTxUrl = (config.explorer_url || 'https://bscscan.com').replace(/\/+$/, '') +
        //                 '/address/' + activeWallet;
        //         }

        //         // 6. Record confirmed transaction in backend database
        //         const recordRes = await fetch('{{ route('member.pepe.redeem') }}', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'Accept': 'application/json',
        //                 'X-CSRF-TOKEN': csrfToken
        //             },
        //             body: JSON.stringify({
        //                 amount: amount,
        //                 wallet_address: activeWallet,
        //                 txnid: onChainTxHash
        //             })
        //         });

        //         const recordData = await recordRes.json().catch(() => ({
        //             success: false,
        //             message: 'Server returned error ' + recordRes.status
        //         }));
        //         if (!recordRes.ok || !recordData.success) {
        //             throw new Error(recordData.message || 'Failed to record transaction.');
        //         }
        //         if (recordData.success) {
        //             var explorerLink = recordData.explorer_link || explorerTxUrl;
        //             var txHtml = `
    //                 <div class="mt-3 p-3 rounded bg-dark border border-secondary text-start">
    //                     <span class="text-white-50 d-block small mb-1">BNB Smart Chain Reference Hash:</span>
    //                     <code class="text-success small d-block text-truncate mb-2" style="font-size: 11px;">` +
        //                 onChainTxHash + `</code>
    //                     <a href="` + explorerLink + `" target="_blank" class="btn btn-sm btn-success font-weight-bold" style="color: #000;">
    //                         <i class="fas fa-check-circle me-1"></i> View on BscScan
    //                     </a>
    //                 </div>
    //             `;

        //             Swal.fire({
        //                 icon: 'success',
        //                 title: '🎉 PEPE Tokens Redeemed!',
        //                 html: '<p class="text-white mb-2"><b>' + recordData.message + '</b></p>' + txHtml,
        //                 confirmButtonColor: '#00e676',
        //                 confirmButtonText: 'Awesome!'
        //             }).then(() => {
        //                 window.location.reload();
        //             });
        //         } else {
        //             Swal.fire({
        //                 icon: 'info',
        //                 title: 'Tokens Redeemed',
        //                 html: '<p class="text-white">Redemption processed for your wallet on BSC! Hash: <code>' +
        //                     onChainTxHash + '</code></p>',
        //                 confirmButtonColor: '#00e676'
        //             }).then(() => {
        //                 window.location.reload();
        //             });
        //         }
        //     } catch (err) {
        //         console.error('PEPE Claim Error:', err);
        //         var errorInfo = parseFriendlyWeb3Error(err);

        //         Swal.fire({
        //             icon: errorInfo.icon || 'warning',
        //             title: '<span style="font-weight: 700;">' + errorInfo.title + '</span>',
        //             html: `
    //                 <div class="p-2 text-center" style="font-size: 13px;">
    //                     <p class="text-white mb-2 font-weight-bold">` + errorInfo.message + `</p>
    //                     <small class="text-white-50 d-block">` + errorInfo.detail + `</small>
    //                 </div>
    //             `,
        //             confirmButtonColor: '#00e676',
        //             confirmButtonText: 'OK'
        //         });
        //     }
        // };

        // function parseFriendlyWeb3Error(err) {
        //     if (!err) {
        //         return {
        //             icon: 'error',
        //             title: 'Redeem Failed',
        //             message: 'An unexpected error occurred.',
        //             detail: 'Please check your connection and try again.'
        //         };
        //     }

        //     var msg = (err.message || '').toLowerCase();
        //     var code = err.code;

        //     // 1. User rejection / cancellation in Wallet
        //     if (code === 4001 || code === 'ACTION_REJECTED' || msg.includes('user rejected') || msg.includes(
        //         'user denied') || msg.includes('rejected transaction') || msg.includes('cancelled')) {
        //         return {
        //             icon: 'info',
        //             title: 'Transaction Cancelled',
        //             message: 'You cancelled the request in your wallet.',
        //             detail: 'No PEPE tokens were deducted and no fee was charged.'
        //         };
        //     }

        //     // 2. Insufficient BNB for gas fee
        //     if (msg.includes('insufficient funds') || (msg.includes('gas') && msg.includes('exceeds'))) {
        //         return {
        //             icon: 'warning',
        //             title: 'Insufficient Gas Fee',
        //             message: 'Your wallet does not have enough BNB for gas.',
        //             detail: 'Please deposit a small amount of BNB (for gas) into your wallet and try again.'
        //         };
        //     }

        //     // 3. Smart contract claim address not configured
        //     if (msg.includes('smart contract claim address is not configured')) {
        //         return {
        //             icon: 'warning',
        //             title: 'Claim Setup Required',
        //             message: 'Claim distributor contract is not configured yet.',
        //             detail: 'Please configure the claim contract address in settings.'
        //         };
        //     }

        //     // 4. Contract execution reverted
        //     if (msg.includes('execution reverted') || msg.includes('revert') || msg.includes('call revert exception')) {
        //         if (msg.includes('insufficient pepe tokens') || msg.includes('insufficient contract token balance')) {
        //             return {
        //                 icon: 'warning',
        //                 title: 'Distributor Balance Low',
        //                 message: 'Contract has insufficient PEPE tokens.',
        //                 detail: 'Tokens will be replenished shortly. Please contact support.'
        //             };
        //         }
        //         if (msg.includes('voucher has already been claimed') || msg.includes('already been claimed')) {
        //             return {
        //                 icon: 'info',
        //                 title: 'Already Claimed',
        //                 message: 'This voucher has already been claimed on blockchain.',
        //                 detail: 'Please refresh your page to see your updated balance.'
        //             };
        //         }
        //         if (msg.includes('voucher signature has expired') || msg.includes('expired')) {
        //             return {
        //                 icon: 'info',
        //                 title: 'Voucher Expired',
        //                 message: 'Authorization voucher has expired.',
        //                 detail: 'Please click Redeem again to get a fresh voucher.'
        //             };
        //         }
        //         return {
        //             icon: 'error',
        //             title: 'Claim Transaction Reverted',
        //             message: 'Blockchain contract could not process this claim.',
        //             detail: 'Please ensure contract address is correct and funded.'
        //         };
        //     }

        //     // 5. Network / Connection errors
        //     if (msg.includes('network') || msg.includes('timeout') || msg.includes('failed to fetch')) {
        //         return {
        //             icon: 'error',
        //             title: 'Network Error',
        //             message: 'Could not connect to BNB Smart Chain RPC.',
        //             detail: 'Please check your internet connection or try again in a few moments.'
        //         };
        //     }

        //     // Default fallback with cleaned text (no raw json/hex)
        //     var reason = err.reason || (err.message && err.message.length < 120 && !err.message.includes('{') && !err
        //         .message.includes('0x') ? err.message : '') || 'Transaction could not be completed.';
        //     if (reason.length > 100 || reason.includes('{') || reason.includes('0x')) {
        //         reason = 'Transaction could not be completed.';
        //     }

        //     return {
        //         icon: 'error',
        //         title: 'Redeem Failed',
        //         message: reason,
        //         detail: 'Please try again or contact support if the issue persists.'
        //     };
        // }
    </script>
@endsection
