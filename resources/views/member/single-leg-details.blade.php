@extends('member.layouts.main')
@section('title', 'Single Leg Downline Details')
@section('container')
    @include('member.income._income-styles')

    <style>
        /* Single Leg Custom Styling — Bull Trading Palette */
        .sl-grid-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .sl-stat-card {
            background: #0C0E14;
            border: 1px solid rgba(245, 158, 11, 0.15);
            border-radius: 18px;
            padding: 22px 24px;
            position: relative;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.55);
        }

        .sl-stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(245, 158, 11, 0.15);
            border-color: rgba(245, 158, 11, 0.40);
        }

        .sl-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #F59E0B, #EF4444);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sl-stat-card:hover::before {
            opacity: 1;
        }

        .sl-stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .sl-stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
        }

        .sl-stat-icon.blue {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.35);
            color: #08090C;
        }

        .sl-stat-icon.purple {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.35);
            color: #fff;
        }

        .sl-stat-icon.cyan {
            background: linear-gradient(135deg, #10B981, #059669);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
            color: #fff;
        }

        .sl-stat-icon.emerald {
            background: linear-gradient(135deg, #10B981, #059669);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.35);
        }

        .sl-stat-title {
            color: #94A3B8;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .sl-stat-value {
            color: #F8FAFC;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0;
            display: flex;
            align-items: baseline;
            gap: 6px;
        }

        .sl-stat-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* Milestone Box */
        .sl-milestone-box {
            background: linear-gradient(135deg, #0C0F17 0%, #1A1408 50%, #2A1904 100%);
            border: 1px solid rgba(245, 158, 11, 0.30);
            border-radius: 18px;
            padding: 22px 28px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.55);
        }

        .sl-milestone-box::after {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.18) 0%, transparent 70%);
            pointer-events: none;
        }

        .sl-progress-bar-wrap {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            height: 10px;
            overflow: hidden;
            margin: 12px 0 6px 0;
            position: relative;
        }

        .sl-progress-bar-fill {
            height: 100%;
            border-radius: 10px;
            background: linear-gradient(90deg, #F59E0B, #10B981);
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 12px rgba(16, 185, 129, 0.5);
        }

        .sl-member-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.04);
            padding: 4px 10px 4px 6px;
            border-radius: 30px;
            border: 1px solid rgba(245, 158, 11, 0.15);
        }

        .sl-member-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #F59E0B, #D97706);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #08090C;
        }

        .sl-rank-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            background: rgba(245, 158, 11, 0.15);
            color: #FBBF24;
            border: 1px solid rgba(245, 158, 11, 0.35);
        }

        .inc-badge.blocked,
        .inc-badge.unpaid,
        .inc-badge.deactive {
            background: rgba(239, 68, 68, 0.15);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.35);
        }
    </style>

    <div class="content-body inc-page">
        <div class="container-fluid py-4">

            {{-- Hero Header --}}
            <div class="inc-hero">
                <div class="inc-eyebrow"><i class="fa-solid fa-network-wired"></i> Global Single Leg Network</div>
                <h1><i class="fa-solid fa-account-tree me-2"></i>Single Leg Downline Details</h1>
                <p>Every member worldwide who activated their account after you is placed directly in your universal Single
                    Leg line.</p>
            </div>

            @php
                $isActivated = !empty($data->activated_at) && $data->status === 'Active';
                $currentRank = (int) ($data->rank_status ?? 0);
                $selfStaking = (float) ($data->self_biz ?? 0);

                // Single Leg Milestone Table
                $milestones = [
                    1 => ['staking' => 0, 'commission' => 2, 'team' => 25],
                    2 => ['staking' => 0, 'commission' => 5, 'team' => 100],
                    3 => ['staking' => 0, 'commission' => 9, 'team' => 300],
                    4 => ['staking' => 20, 'commission' => 18, 'team' => 750],
                    5 => ['staking' => 50, 'commission' => 40, 'team' => 1750],
                    6 => ['staking' => 120, 'commission' => 100, 'team' => 4750],
                    7 => ['staking' => 270, 'commission' => 250, 'team' => 11250],
                    8 => ['staking' => 570, 'commission' => 500, 'team' => 26250],
                    9 => ['staking' => 1170, 'commission' => 1000, 'team' => 66250],
                    10 => ['staking' => 2370, 'commission' => 2000, 'team' => 166250],
                    11 => ['staking' => 4870, 'commission' => 5000, 'team' => 366250],
                    12 => ['staking' => 9870, 'commission' => 10000, 'team' => 766250],
                    13 => ['staking' => 17870, 'commission' => 15000, 'team' => 1366250],
                    14 => ['staking' => 27870, 'commission' => 20000, 'team' => 2166250],
                    15 => ['staking' => 52870, 'commission' => 50000, 'team' => 3166250],
                ];

                $nextLevel = min(15, $currentRank + 1);
                $targetTeam = $milestones[$nextLevel]['team'] ?? 25;
                $targetStaking = $milestones[$nextLevel]['staking'] ?? 0;
                $targetReward = $milestones[$nextLevel]['commission'] ?? 0;
                $teamProgress = $targetTeam > 0 ? min(100, round(($totalActiveMembers / $targetTeam) * 100, 1)) : 100;
            @endphp

            {{-- Stat Summary Cards --}}
            <div class="sl-grid-stats">
                {{-- Total Single Leg Team --}}
                <div class="sl-stat-card">
                    <div class="sl-stat-top">
                        <span class="sl-stat-title">Your Single Leg Team</span>
                        <div class="sl-stat-icon blue">
                            <i class="fa-solid fa-users-rays"></i>
                        </div>
                    </div>
                    <div class="sl-stat-value">
                        {{ number_format($totalMembers) }}
                        <span class="sl-stat-badge"
                            style="background: rgba(245, 158, 11, 0.15); color: #FBBF24;">Worldwide</span>
                    </div>
                </div>

                {{-- My Activation Date --}}
                <div class="sl-stat-card">
                    <div class="sl-stat-top">
                        <span class="sl-stat-title">My Activation Date</span>
                        <div class="sl-stat-icon purple">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="sl-stat-value" style="font-size: 18px; line-height: 1.4;">
                        @if (!empty($data->activated_at))
                            {{ date('d M Y, h:i A', strtotime($data->activated_at)) }}
                        @else
                            <span class="text-warning">Pending Activation</span>
                        @endif
                    </div>
                </div>

                {{-- Self Staking / Package --}}
                <div class="sl-stat-card">
                    <div class="sl-stat-top">
                        <span class="sl-stat-title">My Staking</span>
                        <div class="sl-stat-icon cyan">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <div class="sl-stat-value">
                        ${{ number_format($selfStaking, 2) }}
                        <span class="sl-stat-badge"
                            style="background: rgba(16, 185, 129, 0.15); color: #10B981;">Active</span>
                    </div>
                </div>

                {{-- Current Single Leg Stage --}}
                {{-- <div class="sl-stat-card">
                <div class="sl-stat-top">
                    <span class="sl-stat-title">Current Stage</span>
                    <div class="sl-stat-icon emerald">
                        <i class="fa-solid fa-award"></i>
                    </div>
                </div>
                <div class="sl-stat-value">
                    Stage {{ $currentRank }}
                    <span class="sl-stat-badge" style="background: rgba(16, 185, 129, 0.15); color: #34D399;">
                        Level {{ $currentRank }}/15
                    </span>
                </div>
            </div> --}}
            </div>

            {{-- Next Stage Milestone Box --}}
            @if ($currentRank < 15)
                <div class="sl-milestone-box">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                        <div>
                            <span class="text-uppercase font-weight-bold"
                                style="color: #F59E0B; font-size: 12px; letter-spacing: 1.5px;">
                                <i class="fa-solid fa-bullseye me-1"></i> Next Milestone: Stage {{ $nextLevel }}
                            </span>
                            <h5 class="text-white font-weight-bold mb-0 mt-1">
                                Team Target: {{ number_format($targetTeam) }} Active Members &bull; Reward:
                                ${{ number_format($targetReward, 2) }}
                            </h5>
                        </div>
                        <div class="text-right mt-2 mt-md-0">
                            <span class="sl-rank-tag">
                                <i class="fa-solid fa-trophy text-warning"></i> Next Reward:
                                ${{ number_format($targetReward, 2) }}
                            </span>
                        </div>
                    </div>

                    <div class="sl-progress-bar-wrap">
                        <div class="sl-progress-bar-fill" style="width: {{ $teamProgress }}%;"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 12.5px;">
                        <span>Progress: <strong>{{ number_format($totalActiveMembers) }}</strong> of
                            <strong>{{ number_format($targetTeam) }}</strong> members required
                            ({{ $teamProgress }}%)</span>
                        <span>Required Self Staking: <strong>${{ number_format($targetStaking, 2) }}</strong> (Current:
                            ${{ number_format($selfStaking, 2) }})</span>
                    </div>
                </div>
            @endif

            {{-- Downline Members Table Card --}}
            <div class="inc-card">
                <div class="inc-card-header justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="inc-header-icon"><i class="fa-solid fa-users-viewfinder"></i></div>
                        <div>
                            <div class="inc-header-title">Single Leg Downline Members</div>
                            <div class="inc-header-sub">Chronological list of all members activated after your activation
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ url('member/income/single-leg-income') }}" class="btn btn-sm btn-outline-primary"
                            style="border-radius: 10px; font-weight: 600; font-size: 12.5px;">
                            <i class="fa-solid fa-receipt me-1"></i> View Single Leg Income
                        </a>
                    </div>
                </div>

                <div class="inc-table-shell">
                    <table id="example3" class="inc-table display dataTable no-footer">
                        <thead>
                            <tr>
                                <th>Position</th>
                                <th>Member</th>
                                <th>Name</th>
                                {{-- <th>Staking</th>
                                <th>Activation Date</th> --}}
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $pos = 1; @endphp
                            @forelse ($membersDetails as $member)
                                @php
                                    $initials = strtoupper(substr($member->name ?? ($member->memberid ?? 'M'), 0, 1));
                                @endphp
                                <tr>
                                    <td data-order="{{ $pos }}">
                                        <span class="font-weight-bold" style="color: #98A2C3;">#{{ $pos }}</span>
                                    </td>
                                    <td>
                                        <div class="sl-member-pill">
                                            <span class="sl-member-avatar">{{ $initials }}</span>
                                            <span class="font-weight-bold text-white">{{ $member->memberid }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: #E2EAF4; font-weight: 500;">
                                            {{ !empty($member->name) ? $member->name : '-' }}
                                        </span>
                                    </td>
                                    {{-- <td>
                                        <span class="font-weight-bold" style="color: #F59E0B;">
                                            ${{ number_format((float) ($member->self_biz ?? 0), 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: #CBD5E1;">
                                            @if (!empty($member->activated_at))
                                                <i class="fa-regular fa-clock me-1 text-muted"></i>
                                                {{ date('d-m-Y h:i A', strtotime($member->activated_at)) }}
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </td> --}}
                                    <td data-order="{{ $member->status === 'Active' ? 1 : 2 }}">
                                        @if ($member->status === 'Active')
                                            <span class="inc-badge paid">
                                                <i class="fa-solid fa-circle-check"></i> Active
                                            </span>
                                        @elseif ($member->status === 'Temp')
                                            <span class="inc-badge pending">
                                                <i class="fa-solid fa-circle-question"></i> Inactive
                                            </span>
                                        @else
                                            <span class="inc-badge unpaid blocked">
                                                <i class="fa-solid fa-circle-xmark"></i> {{ $member->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @php $pos++; @endphp
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <i class="fa-solid fa-users-slash text-muted"
                                                style="font-size: 40px; margin-bottom: 12px;"></i>
                                            <h6 class="text-white font-weight-bold">No Single Leg Members Found Yet</h6>
                                            <p class="text-muted small mb-0">
                                                @if (!$isActivated)
                                                    Please activate your account to start receiving downline members into
                                                    your single leg!
                                                @else
                                                    New members joining after your activation timestamp will appear here
                                                    automatically.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
