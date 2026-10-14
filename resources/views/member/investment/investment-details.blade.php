@extends('member.layouts.main')
@section('title', 'Staking History')
@section('container')
    @include('member.income._income-styles')

    <!--**********************************
                                Content body start
                            ***********************************-->
    <div class="content-body inc-page">
        <div class="container-fluid py-4">
            <!-- row -->
            <div class="row">
                <div class="col-12">
                    <div class="inc-card">
                        <div class="inc-card-header">
                            <div class="inc-header-icon"><i class="fa-solid fa-sack-dollar"></i></div>
                            <div>
                                <div class="inc-header-title">Staking History</div>
                                <div class="inc-header-sub">Current staking investment summary</div>
                            </div>
                        </div>
                        <div class="inc-table-shell">
                            <table id="example3" class="inc-table display dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Package Range</th>
                                        <th>Trading Amount</th>
                                        <th>Lock Period</th>
                                        <th>Unlock Date</th>
                                        <th>Remaining Timer</th>
                                        <th>Lock Status</th>
                                        <th>Total Earning</th>
                                        <th>Max Earning</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1;  @endphp
                                    @foreach ($investment_data as $list)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>
                                                <small
                                                    class="d-block text-white-50">{{ $list->created_at ? $list->created_at->format('d M Y, H:i') : '' }}</small>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-white font-weight-bold">{{ $list->package_range ?? $list->package }}</span>
                                            </td>
                                            <td class="text-warning font-weight-bold">
                                                ${{ number_format((float) $list->trading_wallet_amount, 2) }}</td>
                                            <td>
                                                <span class="badge"
                                                    style="background: rgba(245, 158, 11, 0.15); color: #FBBF24; border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 6px; padding: 4px 8px;">
                                                    <i
                                                        class="fa-regular fa-clock me-1"></i>{{ $list->lock_days ?? ($defaultLockDays ?? 0) }}
                                                    Days
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-white-50"><i
                                                        class="fa-regular fa-calendar-check text-info me-1"></i>{{ $list->unlock_datetime ?? '-' }}</small>
                                            </td>
                                            <td>
                                                <div class="staking-timer-wrapper"
                                                    data-unlock-timestamp="{{ $list->unlock_timestamp }}"
                                                    data-remaining-seconds="{{ (int) ($list->remaining_lock_seconds ?? 0) }}"
                                                    data-locked="{{ $list->is_locked ? 1 : 0 }}">
                                                    @if ($list->is_locked && ($list->remaining_lock_seconds ?? 0) > 0)
                                                        <span class="badge font-monospace staking-timer-badge"
                                                            style="background: rgba(239, 68, 68, 0.15); color: #F87171; border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 6px; padding: 4px 8px; letter-spacing: 0.5px;">
                                                            <i class="fa-solid fa-stopwatch me-1 text-danger"></i><span
                                                                class="staking-timer-text">{{ $list->remaining_lock_formatted ?? 'Calculating...' }}</span>
                                                        </span>
                                                    @else
                                                        <span class="badge font-monospace staking-timer-badge"
                                                            style="background: rgba(16, 185, 129, 0.15); color: #34D399; border: 1px solid rgba(16, 185, 129, 0.35); border-radius: 6px; padding: 4px 8px;">
                                                            <i class="fa-solid fa-lock-open me-1"></i><span
                                                                class="staking-timer-text">Unlocked</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge staking-row-lock-badge"
                                                    style="background: {{ $list->is_locked ? 'rgba(239, 68, 68, 0.15)' : 'rgba(16, 185, 129, 0.15)' }}; color: {{ $list->is_locked ? '#EF4444' : '#10B981' }}; border: 1px solid {{ $list->is_locked ? 'rgba(239, 68, 68, 0.3)' : 'rgba(16, 185, 129, 0.3)' }}; border-radius: 6px; padding: 4px 8px;">
                                                    <i
                                                        class="fa-solid {{ $list->is_locked ? 'fa-lock' : 'fa-lock-open' }} me-1"></i>
                                                    {{ $list->is_locked ? 'Fund Locked' : 'Fund Unlocked' }}
                                                </span>
                                            </td>
                                            <td class="text-warning font-weight-bold">
                                                ${{ totalEarning($list->id) }}</td>
                                            <td>
                                                @if ( maxEarning($list->id) == 'No Limit')
                                                    <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 6px; padding: 4px 8px;">No Limit</span>
                                                @else
                                                    <span>${{ maxEarning($list->id) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($list->status === 'Active')
                                                    <span class="badge"
                                                        style="background: rgba(16, 185, 129, 0.15); color: #10B981; border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 6px; padding: 4px 8px;">Active</span>
                                                @else
                                                    <span class="badge"
                                                        style="background: rgba(239, 68, 68, 0.15); color: #EF4444; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; padding: 4px 8px;">Expired</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @php $i++;  @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--**********************************
                                Content body end
                            ***********************************-->

    <script>
        (function() {
            function startStakingHistoryTimers() {
                var wrappers = document.querySelectorAll('.staking-timer-wrapper[data-locked="1"]');
                if (!wrappers || wrappers.length === 0) return;

                var items = [];
                wrappers.forEach(function(wrap) {
                    var remSecs = wrap.getAttribute('data-remaining-seconds');
                    var unlockTs = wrap.getAttribute('data-unlock-timestamp');
                    var target = null;
                    if (remSecs && Number(remSecs) > 0) {
                        target = Date.now() + (Number(remSecs) * 1000);
                    } else if (unlockTs && Number(unlockTs) > 0) {
                        target = Number(unlockTs);
                    }
                    if (target) {
                        items.push({
                            element: wrap,
                            target: target,
                            textEl: wrap.querySelector('.staking-timer-text')
                        });
                    }
                });

                if (items.length === 0) return;

                function tickHistoryStakings() {
                    var now = Date.now();
                    var activeCount = 0;

                    items.forEach(function(item) {
                        var diff = item.target - now;
                        if (diff <= 0) {
                            item.element.setAttribute('data-locked', '0');
                            var badge = item.element.querySelector('.staking-timer-badge');
                            if (badge) {
                                badge.style.background = 'rgba(16, 185, 129, 0.15)';
                                badge.style.color = '#34D399';
                                badge.style.borderColor = 'rgba(16, 185, 129, 0.35)';
                                badge.innerHTML =
                                    '<i class="fa-solid fa-lock-open me-1"></i><span class="staking-timer-text">Unlocked</span>';
                            }
                            var row = item.element.closest('tr');
                            if (row) {
                                var rowBadge = row.querySelector('.staking-row-lock-badge');
                                if (rowBadge) {
                                    rowBadge.style.background = 'rgba(16, 185, 129, 0.15)';
                                    rowBadge.style.color = '#10B981';
                                    rowBadge.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                                    rowBadge.innerHTML =
                                        '<i class="fa-solid fa-lock-open me-1"></i> Fund Unlocked';
                                }
                            }
                        } else {
                            activeCount++;
                            var totalSecs = Math.floor(diff / 1000);
                            var days = Math.floor(totalSecs / 86400);
                            var hours = Math.floor((totalSecs % 86400) / 3600);
                            var minutes = Math.floor((totalSecs % 3600) / 60);
                            var seconds = totalSecs % 60;
                            var str = days + 'd ' + String(hours).padStart(2, '0') + 'h ' + String(minutes)
                                .padStart(2, '0') + 'm ' + String(seconds).padStart(2, '0') + 's';
                            if (item.textEl) {
                                item.textEl.innerText = str;
                            }
                        }
                    });

                    return activeCount;
                }

                tickHistoryStakings();
                var stakingInterval = setInterval(function() {
                    var remaining = tickHistoryStakings();
                    if (remaining === 0) {
                        clearInterval(stakingInterval);
                    }
                }, 1000);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', startStakingHistoryTimers);
            } else {
                startStakingHistoryTimers();
            }
        })();
    </script>
@endsection
