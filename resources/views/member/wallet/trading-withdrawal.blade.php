@extends('member.layouts.main')
@section('title', 'Trading Withdrawal')
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
                    <div class="col-12">
                        <div class="inc-card">
                            <div class="inc-card-header">
                                <div class="inc-header-icon"><i class="fa-solid fa-chart-line"></i></div>
                                <div>
                                    <div class="inc-header-title">Trading Withdrawal</div>
                                    <div class="inc-header-sub">Trading Withdrawal Details</div>
                                </div>
                            </div>
                            
                            <!-- Timer Section -->
                            <div class="timer-container text-center py-3 mb-3 mx-4" style="display: none; background: rgba(0, 0, 0, 0.2); border: 1px solid #ffc107; border-radius: 8px;">
                                <h5 class="text-warning mb-2"><i class="fa-regular fa-clock"></i> Unlock Timer</h5>
                                <div id="unlock-timer" style="font-size: 1.8rem; font-weight: 700; color: #fff; letter-spacing: 1px;">
                                    00d 00h 00m 00s
                                </div>
                                <small class="text-muted" id="timer-entry-id"></small>
                            </div>

                            <div class="inc-table-shell">
                                <table id="example3" class="inc-table display dataTable no-footer">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Date</th>
                                            <th>Memberid</th>
                                            {{-- <th>Package</th> --}}
                                            <th>Invest Amount</th>
                                            {{-- <th>Rate</th> --}}
                                            <th>Trading Amount</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @php $i = 1;  @endphp
                                        @foreach ($staking_details as $list)
                                            @php
                                                $adminStatusOn = isset($trading_settings) && $trading_settings->isOn();
                                                $targetTimeMs = $list->locked_until ? $list->locked_until->timestamp * 1000 : 0;
                                                $isLockActive = $list->isLocked();
                                                $hasValidExpiry = ($list->locked_until !== null);
                                                $isDeactive = ($list->status === 'Deactive');
                                                $hasBalance = ((float) $list->trading_wallet_amount > 0);

                                                $canWithdraw = $adminStatusOn && !$isLockActive && $hasValidExpiry && !$isDeactive && $hasBalance;

                                                $disabledReason = '';
                                                if ($isDeactive) {
                                                    $disabledReason = 'Package is deactivated';
                                                } elseif (!$adminStatusOn) {
                                                    $disabledReason = 'Trading Wallet withdrawals are currently OFF';
                                                } elseif (!$hasValidExpiry) {
                                                    $disabledReason = 'Lock expiry information unavailable';
                                                } elseif ($isLockActive) {
                                                    $disabledReason = 'Lock period active (' . $list->remainingLockDays() . ' day(s) remaining)';
                                                } elseif (!$hasBalance) {
                                                    $disabledReason = 'No trading balance available';
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $i }}</td>
                                                 <td>{{ date('d-m-Y', strtotime($list['created_at']))}}<br>{{ date('H:i:s', strtotime($list['created_at']))}}</td>
                                                <td>{{ $list->memberid }}</td>
                                                {{-- <td>{{ $list->package }}</td> --}}
                                                <td>${{ number_format($list->invest_amount, 2) }}</td>
                                                {{-- <td>{{ $list->rate }}%</td> --}}
                                                <td>${{ number_format($list->trading_wallet_amount, 2) }}</td>
                                                <td>
                                                    @if ($list->status == 'Active')
                                                        <label class="btn btn-success">{{ $list->status }}</label>
                                                    @elseif ($list->status == 'Completed')
                                                        <label class="btn btn-info">{{ $list->status }}</label>
                                                    @else
                                                        <label class="btn btn-danger">{{ $list->status }}</label>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                                onclick="startCountdown({{ $targetTimeMs }}, 'Entry #{{ $i }} - {{ $list->memberid }}', {{ $list->id }})" 
                                                                title="Check Timer">
                                                            <i class="fa-regular fa-clock"></i> Timer
                                                        </button>

                                                        <div class="withdraw-btn-container" 
                                                             id="withdraw-container-{{ $list->id }}"
                                                             data-staking-id="{{ $list->id }}"
                                                             data-target-time="{{ $targetTimeMs }}"
                                                             data-amount="{{ (float) $list->trading_wallet_amount }}"
                                                             data-status="{{ $list->status }}"
                                                             data-has-expiry="{{ $hasValidExpiry ? '1' : '0' }}">
                                                            @if ($canWithdraw)
                                                                <span href="javascript:void(0)"
                                                                    onclick="requestTradingWithdrawal({{ $list->id }}, {{ $list->trading_wallet_amount }})"
                                                                    class="btn btn-primary btn-withdraw"
                                                                    title="Withdraw Now">Withdraw Now</span>
                                                            @else
                                                                <span class="btn btn-secondary disabled btn-withdraw"
                                                                    style="cursor: not-allowed;"
                                                                    title="{{ $disabledReason }}">Withdraw Now</span>
                                                            @endif
                                                        </div>
                                                    </div>
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
    </div>
    <!--**********************************
        Content body end
    ***********************************-->

<script>
    let countdownInterval;
    let adminTradingStatus = "{{ isset($trading_settings) && $trading_settings->isOn() ? 'on' : 'off' }}";

    function revalidateAdminStatus(callback) {
        $.ajax({
            url: '{{ route("tradingWalletValidate") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                withAmount: 0
            },
            success: function(res) {
                if (res && res.admin_status) {
                    adminTradingStatus = res.admin_status;
                }
                if (typeof callback === 'function') {
                    callback();
                }
            },
            error: function() {
                if (typeof callback === 'function') {
                    callback();
                }
            }
        });
    }

    function isRowEligible(container) {
        let targetTimeMs = parseInt(container.getAttribute('data-target-time') || 0);
        let status = container.getAttribute('data-status');
        let hasExpiry = container.getAttribute('data-has-expiry') === '1';
        let amount = parseFloat(container.getAttribute('data-amount') || 0);

        if (status === 'Deactive') {
            return { eligible: false, reason: 'Package is deactivated' };
        }

        if (amount <= 0) {
            return { eligible: false, reason: 'No trading balance available' };
        }

        if (adminTradingStatus !== 'on') {
            return { eligible: false, reason: 'Trading Wallet withdrawals are currently OFF' };
        }

        if (!hasExpiry || targetTimeMs <= 0) {
            return { eligible: false, reason: 'Lock expiry information unavailable' };
        }

        let now = new Date().getTime();
        if (targetTimeMs > now) {
            let diffSec = Math.ceil((targetTimeMs - now) / 1000);
            let days = Math.ceil(diffSec / 86400);
            return { eligible: false, reason: 'Lock period active (' + days + ' day(s) remaining)' };
        }

        return { eligible: true, reason: '' };
    }

    function updateContainerButton(container) {
        let check = isRowEligible(container);
        let stakingId = container.getAttribute('data-staking-id');
        let amount = container.getAttribute('data-amount');
        let currentBtn = container.querySelector('.btn-withdraw');

        if (check.eligible) {
            if (!currentBtn || currentBtn.classList.contains('disabled')) {
                container.innerHTML = '<span href="javascript:void(0)" onclick="requestTradingWithdrawal(' + stakingId + ', ' + amount + ')" class="btn btn-primary btn-withdraw" title="Withdraw Now">Withdraw Now</span>';
            }
        } else {
            if (!currentBtn || !currentBtn.classList.contains('disabled') || currentBtn.getAttribute('title') !== check.reason) {
                container.innerHTML = '<span class="btn btn-secondary disabled btn-withdraw" style="cursor: not-allowed;" title="' + check.reason + '">Withdraw Now</span>';
            }
        }
    }

    function updateAllWithdrawalButtons() {
        document.querySelectorAll('.withdraw-btn-container').forEach(function(container) {
            updateContainerButton(container);
        });
    }

    function startCountdown(targetTimeMs, entryName, stakingId) {
        document.querySelector('.timer-container').style.display = 'block';
        document.getElementById('timer-entry-id').innerText = 'Showing timer for: ' + entryName;
        
        clearInterval(countdownInterval);
        
        if (!targetTimeMs) {
            document.getElementById('unlock-timer').innerText = "Unlocked / Ready";
            revalidateAdminStatus(function() {
                updateAllWithdrawalButtons();
            });
            return;
        }

        const updateTimer = () => {
            let now = new Date().getTime();
            let distance = targetTimeMs - now;

            if (distance <= 0) {
                clearInterval(countdownInterval);
                document.getElementById('unlock-timer').innerText = "Unlocked / Ready";
                revalidateAdminStatus(function() {
                    updateAllWithdrawalButtons();
                });
                return;
            }

            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('unlock-timer').innerText = days + "d " + hours + "h " + minutes + "m " + seconds + "s";
        };

        updateTimer(); // Trigger immediately
        countdownInterval = setInterval(updateTimer, 1000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        @if (isset($staking_details) && count($staking_details) > 0)
            @php $firstItem = $staking_details->first(); @endphp
            startCountdown({{ $firstItem->locked_until ? $firstItem->locked_until->timestamp * 1000 : 0 }}, 'Entry #1 - {{ $firstItem->memberid }}', {{ $firstItem->id }});
        @endif

        updateAllWithdrawalButtons();

        // Check for countdown expirations every second
        setInterval(function() {
            let now = new Date().getTime();
            let hasNewlyExpired = false;
            document.querySelectorAll('.withdraw-btn-container').forEach(function(container) {
                let targetTimeMs = parseInt(container.getAttribute('data-target-time') || 0);
                let hasExpiry = container.getAttribute('data-has-expiry') === '1';
                let btn = container.querySelector('.btn-withdraw');
                if (hasExpiry && targetTimeMs > 0 && targetTimeMs <= now && btn && btn.classList.contains('disabled')) {
                    let title = btn.getAttribute('title') || '';
                    if (title.indexOf('Lock period active') !== -1) {
                        hasNewlyExpired = true;
                    }
                }
            });

            if (hasNewlyExpired) {
                revalidateAdminStatus(function() {
                    updateAllWithdrawalButtons();
                });
            }
        }, 1000);

        // Periodically revalidate Admin status in background every 15 seconds
        setInterval(function() {
            revalidateAdminStatus(function() {
                updateAllWithdrawalButtons();
            });
        }, 15000);
    });

    // Handle DataTables pagination and search redraws
    $(document).on('draw.dt', '#example3', function() {
        updateAllWithdrawalButtons();
    });

    function requestTradingWithdrawal(stakingId, amount) {
        if (amount <= 0) {
            Swal.fire('Error', 'No trading balance available to withdraw.', 'error');
            return;
        }

        if (adminTradingStatus !== 'on') {
            Swal.fire('Error', 'Trading Wallet withdrawals are currently disabled by administration.', 'error');
            updateAllWithdrawalButtons();
            return;
        }

        Swal.fire({
            title: 'Confirm Withdrawal',
            text: 'Are you sure you want to withdraw $' + Number(amount).toFixed(2) + ' from your Trading Wallet?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Withdraw',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return $.ajax({
                    url: '{{ route("initiateTradingWithdrawal") }}',
                    type: 'POST',
                    data: {
                        staking_id: stakingId,
                        amount: amount,
                        _token: '{{ csrf_token() }}'
                    }
                }).catch(error => {
                    Swal.showValidationMessage(
                        error.responseJSON && error.responseJSON.message ? error.responseJSON.message : 'Request failed'
                    );
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value && result.value.code == 1) {
                    Swal.fire('Success', result.value.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    let errMsg = (result.value && result.value.message) ? result.value.message : 'Validation failed.';
                    Swal.fire('Error', errMsg, 'error');
                    revalidateAdminStatus(function() {
                        updateAllWithdrawalButtons();
                    });
                }
            }
        });
    }
</script>
@endsection
