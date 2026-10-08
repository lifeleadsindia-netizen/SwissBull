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
                                                                onclick="startCountdown({{ $list->locked_until ? $list->locked_until->timestamp * 1000 : 0 }}, 'Entry #{{ $i }} - {{ $list->memberid }}')" 
                                                                title="Check Timer">
                                                            <i class="fa-regular fa-clock"></i> Timer
                                                        </button>

                                                        @if ($list->status != 'Deactive')
                                                            @if (isset($trading_settings) && $trading_settings->status == 'Off')
                                                                <span class="btn btn-secondary disabled" style="cursor: not-allowed;" title="Withdrawal is currently disabled">Withdraw Now</span>
                                                            @else
                                                                <span href="javascript:void(0)"
                                                                    onclick="requestTradingWithdrawal({{ $list->id }}, {{ $list->trading_wallet_amount }})"
                                                                    class="btn btn-primary">Withdraw Now</span>
                                                            @endif
                                                        @else
                                                             <span class="btn btn-secondary disabled" style="cursor: not-allowed;" title="Withdrawal is currently disabled">Withdraw Now</span>
                                                        @endif
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

    function startCountdown(targetTimeMs, entryName) {
        document.querySelector('.timer-container').style.display = 'block';
        document.getElementById('timer-entry-id').innerText = 'Showing timer for: ' + entryName;
        
        clearInterval(countdownInterval);
        
        if (!targetTimeMs) {
            document.getElementById('unlock-timer').innerText = "Unlocked / Ready";
            return;
        }

        const updateTimer = () => {
            let now = new Date().getTime();
            let distance = targetTimeMs - now;

            if (distance <= 0) {
                clearInterval(countdownInterval);
                document.getElementById('unlock-timer').innerText = "Unlocked / Ready";
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
            startCountdown({{ $firstItem->locked_until ? $firstItem->locked_until->timestamp * 1000 : 0 }}, 'Entry #1 - {{ $firstItem->memberid }}');
        @endif
    });

    function requestTradingWithdrawal(stakingId, amount) {
        if (amount <= 0) {
            Swal.fire('Error', 'No trading balance available to withdraw.', 'error');
            return;
        }

        Swal.fire({
            title: 'Confirm Withdrawal',
            text: 'Are you sure you want to withdraw $' + amount.toFixed(2) + ' from your Trading Wallet?',
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
                if (result.value.code == 1) {
                    Swal.fire('Success', result.value.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', result.value.message || 'Validation failed.', 'error');
                }
            }
        });
    }
</script>
@endsection
