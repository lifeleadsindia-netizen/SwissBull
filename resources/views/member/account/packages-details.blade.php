@extends('member.layouts.main')
@section('title', 'Package Details')
@section('container')

<style>
    /* =============================================
       PACKAGE DETAILS PAGE — Bull Trading Theme Match
       Colors: #08090C obsidian · #F59E0B gold · #EF4444 red · #10B981 green
    ============================================= */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    .pkg-page { font-family: 'Inter', sans-serif; }

    /* ---- Hero Banner ---- */
    .pkg-hero {
        position: relative; overflow: hidden;
        border-radius: 18px; padding: 28px 36px; color: #fff; margin-bottom: 24px;
        background: linear-gradient(135deg, #0C0F17 0%, #1A1408 40%, #362203 80%, #683F06 120%);
        border: 1px solid rgba(245, 158, 11, 0.35);
        box-shadow: 0 16px 50px rgba(0,0,0,0.65), 0 0 25px rgba(245, 158, 11, 0.15);
    }
    .pkg-hero::before {
        content: ''; position: absolute;
        width: 300px; height: 300px; right: -60px; top: -120px; border-radius: 50%;
        background: radial-gradient(circle, rgba(245, 158, 11, 0.25) 0%, transparent 70%);
        animation: pkgGlow 4s ease-in-out infinite;
    }
    .pkg-hero::after {
        content: ''; position: absolute;
        width: 200px; height: 200px; right: -25px; top: -75px;
        border: 28px solid rgba(245, 158, 11, 0.08); border-radius: 50%;
    }
    @keyframes pkgGlow {
        0%, 100% { opacity: 0.5; transform: scale(1); }
        50%       { opacity: 1;   transform: scale(1.1); }
    }
    .pkg-eyebrow {
        position: relative; z-index: 1;
        display: inline-flex; align-items: center; gap: 7px;
        color: #F59E0B; font-size: 11px; font-weight: 700;
        letter-spacing: 2px; text-transform: uppercase; margin-bottom: 8px;
    }
    .pkg-eyebrow::before {
        content: ''; display: inline-block;
        width: 16px; height: 2px; background: #F59E0B; border-radius: 2px;
    }
    .pkg-hero h1 {
        position: relative; z-index: 1;
        font-size: 26px; font-weight: 800; margin: 0 0 6px 0;
        text-shadow: 0 2px 12px rgba(0,0,0,0.5);
        background: linear-gradient(135deg, #FFFFFF 30%, #FDE68A 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .pkg-hero p {
        position: relative; z-index: 1;
        color: rgba(255,255,255,0.75); margin: 0; font-size: 13.5px;
    }

    /* ---- Table Card ---- */
    .pkg-card {
        background: #0C0E14;
        border: 1px solid rgba(245, 158, 11, 0.15);
        border-radius: 18px;
        box-shadow: 0 20px 55px rgba(0,0,0,0.55), 0 0 15px rgba(245, 158, 11, 0.05);
        overflow: hidden;
    }
    .pkg-card-header {
        border-bottom: 1px solid rgba(245, 158, 11, 0.15);
        background: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(239, 68, 68, 0.04));
        padding: 18px 28px;
        display: flex; align-items: center; gap: 13px;
    }
    .pkg-header-icon {
        width: 40px; height: 40px; border-radius: 10px; flex-shrink: 0;
        background: linear-gradient(135deg, #F59E0B, #D97706);
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 16px rgba(245, 158, 11, 0.35);
    }
    .pkg-header-icon i { color: #08090C; font-size: 15px; }
    .pkg-header-title { color: #F8FAFC; font-size: 16px; font-weight: 700; margin: 0; }
    .pkg-header-sub { color: #94A3B8; font-size: 12px; margin-top: 2px; }

    /* DataTable overrides */
    .pkg-card .dataTables_wrapper .dataTables_length label,
    .pkg-card .dataTables_wrapper .dataTables_filter label,
    .pkg-card .dataTables_wrapper .dataTables_info {
        color: #94A3B8 !important; font-size: 13px;
    }
    .pkg-card .dataTables_wrapper .dataTables_length select,
    .pkg-card .dataTables_wrapper .dataTables_filter input {
        background: #08090C !important;
        border: 1px solid rgba(245, 158, 11, 0.20) !important;
        border-radius: 8px !important; color: #F8FAFC !important;
        padding: 5px 10px !important; font-size: 13px !important;
    }
    .pkg-card .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #F59E0B !important;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.20) !important;
        outline: none !important;
    }

    /* Table styling */
    .pkg-table { width: 100% !important; border-collapse: collapse !important; }
    .pkg-table thead tr th {
        background: rgba(245, 158, 11, 0.08) !important;
        color: #F59E0B !important;
        font-size: 11px !important; font-weight: 700 !important;
        letter-spacing: 0.7px; text-transform: uppercase;
        padding: 14px 16px !important;
        border-bottom: 1px solid rgba(245, 158, 11, 0.18) !important;
        white-space: nowrap;
    }
    .pkg-table tbody tr td {
        color: #E2E8F0 !important;
        font-size: 13.5px !important;
        padding: 15px 16px !important;
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        vertical-align: middle !important;
    }
    .pkg-table tbody tr:last-child td { border-bottom: none !important; }
    .pkg-table tbody tr:hover td {
        background: rgba(245, 158, 11, 0.06) !important;
    }

    /* Status Badges */
    .pkg-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 13px; border-radius: 50px;
        font-size: 11.5px; font-weight: 700; border: none; cursor: default;
    }
    .pkg-badge.accepted {
        background: rgba(16, 185, 129, 0.15);
        color: #10B981;
        border: 1px solid rgba(16, 185, 129, 0.35);
    }
    .pkg-badge.rejected {
        background: rgba(239, 68, 68, 0.15);
        color: #EF4444;
        border: 1px solid rgba(239, 68, 68, 0.35);
    }

    /* Pagination */
    .pkg-card .dataTables_wrapper .dataTables_paginate .paginate_button {
        background: #08090C !important;
        border: 1px solid rgba(245, 158, 11, 0.20) !important;
        color: #94A3B8 !important;
        border-radius: 8px !important; margin: 0 3px !important;
        padding: 5px 12px !important; font-size: 13px !important;
    }
    .pkg-card .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .pkg-card .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: linear-gradient(135deg, #F59E0B, #D97706) !important;
        border-color: transparent !important; color: #08090C !important;
        font-weight: 700 !important;
    }

    /* No-data row */
    .pkg-table tbody td.dataTables_empty {
        color: #64748B !important; text-align: center; padding: 40px !important;
    }
</style>

<div class="content-body pkg-page">
    <div class="container-fluid py-4">

        {{-- Hero --}}
        <div class="pkg-hero">
            <div class="pkg-eyebrow">Account Details</div>
            <h1>Package Details</h1>
            <p>View all your activation packages and transaction history below.</p>
        </div>

        {{-- Table Card --}}
        <div class="row">
            <div class="col-12">
                <div class="pkg-card">
                    <div class="pkg-card-header">
                        <div class="pkg-header-icon">
                            <i class="fa-solid fa-box-archive"></i>
                        </div>
                        <div>
                            <div class="pkg-header-title">Package History</div>
                            <div class="pkg-header-sub">All activation transactions on your account</div>
                        </div>
                    </div>

                    <div class="p-3 p-md-4" style="overflow:auto">
                        <table id="example3" class="pkg-table display min-w850 dataTable no-footer">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Date</th>
                                    {{-- <th>Order Id</th> --}}
                                    <th>TransactionId</th>
                                    <th>Package Type</th>
                                    <th>Value</th>
                                    <th>Mode</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 1; @endphp
                                @foreach ($package_data as $list)
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ date('d-m-Y', strtotime($list['created_at'])) }}</td>
                                        {{-- <td>{{ $list['order_id'] }}</td> --}}
                                        <td>{{ $list['txnid'] }}</td>
                                        <td>{{ $list['package_type'] }}</td>
                                        <td>${{ $list['package_value'] }}</td>
                                        <td>{{ $list['payment_mode'] }}</td>
                                        <td>
                                            @if ($list['status'] == 'Accepted')
                                                <span class="pkg-badge accepted">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                    {{ $list['status'] }}
                                                </span>
                                            @else
                                                <span class="pkg-badge rejected">
                                                    <i class="fa-solid fa-circle-xmark"></i>
                                                    {{ $list['status'] }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

