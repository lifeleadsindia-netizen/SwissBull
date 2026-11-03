<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PackageDistribution;
use App\Models\StakingDetail;
use App\Models\StakingIncome;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddTradingProfitController extends Controller
{
    public function addTradeProfit()
    {
        $packageDis = PackageDistribution::find(1) ?? PackageDistribution::getActiveSetting();
        $globalCapping = $packageDis ? (float) $packageDis->capping : 0.0;

        $recentProfits = StakingIncome::where([['status', 'Paid'], ['added_by', 'Admin']])
            ->with('member')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        return view('admin.trade.add-trading-profit', compact('globalCapping', 'recentProfits'));
    }

    public function getMemberCapping(Request $request)
    {
        $memberid = trim((string) $request->post('memberid', ''));
        if ($memberid === '') {
            return response()->json([
                'status' => false,
                'message' => 'Please enter a Member ID.',
            ], 422);
        }

        $member = MemberDetail::where('memberid', $memberid)->first();
        if (! $member) {
            return response()->json([
                'status' => false,
                'message' => 'No member found with ID "' . $memberid . '". Please check the Member ID.',
            ], 404);
        }

        $tradingWalletAmount = (float) $member->trading_wallet;
        $packageDis = PackageDistribution::find(1) ?? PackageDistribution::getActiveSetting();
        $cap = $packageDis ? (float) $packageDis->capping : 0.0;

        $achieved = (float) StakingIncome::where([['status', 'Paid'], ['memberid', $memberid]])->sum('amount');

        if ($cap == 0) {
            $capValue = 0.0;
            $balance = 0.0;
            $isUnlimited = true;
            $remaining = 0.0;
            $percentUsed = 0.0;
        } else {
            $capValue = ($tradingWalletAmount / 100) * $cap;
            $balance = $capValue - $achieved;
            $isUnlimited = false;
            $remaining = max(0.0, round($balance, 2));
            $percentUsed = $capValue > 0 ? min(100.0, round(($achieved / $capValue) * 100, 1)) : 0.0;
        }

        $canAdd = $isUnlimited ? true : ($remaining >= 1.0);

        // Fetch all stakings for this member with per-staking capping calculations
        $stakingsQuery = StakingDetail::where('memberid', $memberid)->orderBy('id', 'desc')->get();
        $stakingsList = [];
        $totalStakingsCount = $stakingsQuery->count();
        $remainingStakingsCount = 0;

        foreach ($stakingsQuery as $stk) {
            $investAmt = (float) $stk->invest_amount;
            $twAmt = (float) ($stk->trading_wallet_amount > 0 ? $stk->trading_wallet_amount : ($investAmt * 0.70));

            if ($cap == 0) {
                $stkCapTotal = 0.0;
                $stkPaid = (float) StakingIncome::where([['status', 'Paid'], ['staking_id', $stk->id]])->sum('amount');
                $stkRem = 0.0;
                $stkPercentUsed = 0.0;
                $stkHasRemaining = ($stk->status === 'Active');
            } else {
                $stkCapTotal = round(($twAmt / 100) * $cap, 2);
                $stkPaid = (float) StakingIncome::where([['status', 'Paid'], ['staking_id', $stk->id]])->sum('amount');
                $stkRemCalc = max(0.0, round($stkCapTotal - $stkPaid, 2));
                // Per-staking remaining cannot exceed member's total remaining room
                $stkRem = min($stkRemCalc, $remaining);
                $stkPercentUsed = $stkCapTotal > 0 ? min(100.0, round(($stkPaid / $stkCapTotal) * 100, 1)) : 0.0;

                // When staking capping or member overall capping is exhausted (remaining <= 0), automatically deactivate it
                if (($stkRem <= 0.00 || $remaining <= 0.00) && $stk->status === 'Active') {
                    $stk->status = 'Deactive';
                    $stk->save();

                    if ($stk->txnid) {
                        PackageDetail::where('txnid', $stk->txnid)
                            ->where('status', 'Active')
                            ->update(['status' => 'Expired']);
                    }
                }

                $stkHasRemaining = ($stkRem >= 0.01 && $stk->status === 'Active');
            }

            if ($stkHasRemaining) {
                $remainingStakingsCount++;
            }

            $dateFormatted = $stk->created_at
                ? Carbon::parse($stk->created_at)->format('d M Y, h:i A')
                : ($stk->invest_date ? Carbon::parse($stk->invest_date)->format('d M Y') : 'N/A');

            $stakingsList[] = [
                'id' => $stk->id,
                'package' => $stk->package ?? 'Package',
                'order_id' => $stk->order_id ?? ('STK#' . $stk->id),
                'txnid' => $stk->txnid ?? 'N/A',
                'invest_amount' => round($investAmt, 2),
                'trading_wallet_amount' => round($twAmt, 2),
                'total_capping' => round($stkCapTotal, 2),
                'paid_profit' => round($stkPaid, 2),
                'remaining_capping' => round($stkRem, 2),
                'percent_used' => $stkPercentUsed,
                'status' => $stk->status ?? 'Active',
                'is_locked' => $stk->isLocked(),
                'remaining_lock_days' => $stk->remainingLockDays(),
                'date' => $dateFormatted,
                'has_remaining' => $stkHasRemaining,
            ];
        }

        return response()->json([
            'status' => true,
            'code' => 1,
            'member' => [
                'memberid' => $member->memberid,
                'name' => $member->name,
                'email' => $member->email ?? 'N/A',
                'mobile' => $member->mobile ?? 'N/A',
                'status' => $member->status,
                'wallet' => round((float) $member->wallet, 2),
                'trading_wallet' => round($tradingWalletAmount, 2),
                'activation_amount' => round((float) $member->activation_amount, 2),
                'package_name' => $member->package_name ?? $member->package ?? 'Standard',
            ],
            'capping' => [
                'capping_percent' => $cap,
                'total_capping' => round($capValue, 2),
                'achieved_profit' => round($achieved, 2),
                'remaining_capping' => $remaining,
                'percent_used' => $percentUsed,
                'is_unlimited' => $isUnlimited,
                'can_add_profit' => $canAdd,
                'trading_wallet' => round($tradingWalletAmount, 2),
                'wallet' => round((float) $member->wallet, 2),
            ],
            'stakings' => $stakingsList,
            'stakings_count' => $totalStakingsCount,
            'remaining_stakings_count' => $remainingStakingsCount,
        ]);
    }

    public function addTradingProfit(Request $request)
    {
        $request->validate([
            'memberid' => 'required',
            'amount' => 'required|gt:0',
            'staking_id' => 'nullable|integer',
        ]);
        $memberid = $request->post('memberid');
        $profit = (float) $request->post('amount');
        $stakingId = (int) $request->post('staking_id', 0);

        $memUpdate = MemberDetail::where('memberid', $memberid)->first();
        if (! $memUpdate) {
            session()->flash('failedMsg', 'Entered Memberid is not Available. Please change memberid');

            return redirect()->back();
        }
        if ($profit <= 0) {
            session()->flash('failedMsg', 'Invalid Input Entered');

            return redirect()->back();
        }

        $selectedStaking = null;
        if ($stakingId > 0) {
            $selectedStaking = StakingDetail::where([['id', $stakingId], ['memberid', $memberid]])->first();
        }

        $tradingWalletAmount = (float) $memUpdate->trading_wallet;
        $packageDis = PackageDistribution::find(1) ?? PackageDistribution::getActiveSetting();
        $cap = $packageDis ? (float) $packageDis->capping : 0.0;

        if ($cap == 0) {
            $amount = $profit;
            $balance = 0;
        } else {
            $capValue = ($tradingWalletAmount / 100) * $cap;
            $achieved = (float) StakingIncome::where([['status', 'Paid'], ['memberid', $memberid]])->sum('amount');
            $balance = $capValue - $achieved;
            if ($balance <= $profit) {
                $amount = $balance;
            } else {
                $amount = $profit;
            }
        }

        if ($selectedStaking && in_array($selectedStaking->status, ['Deactive', 'Expired'])) {
            session()->flash('failedMsg', 'Staking #' . $selectedStaking->id . ' (' . ($selectedStaking->package ?? 'Package') . ') is already Deactive / Capped.');

            return redirect()->back();
        }

        if ($selectedStaking && $cap > 0) {
            $stkInvest = (float) $selectedStaking->invest_amount;
            $stkTwAmt = (float) ($selectedStaking->trading_wallet_amount > 0 ? $selectedStaking->trading_wallet_amount : ($stkInvest * 0.70));
            $stkCapTotal = round(($stkTwAmt / 100) * $cap, 2);
            $stkPaid = (float) StakingIncome::where([['status', 'Paid'], ['staking_id', $selectedStaking->id]])->sum('amount');
            $stkRemaining = max(0.0, round($stkCapTotal - $stkPaid, 2));

            if ($profit > $stkRemaining) {
                session()->flash('failedMsg', 'Entered amount exceeds remaining capping ($' . number_format($stkRemaining, 2) . ') for Staking #' . $selectedStaking->id . '.');

                return redirect()->back();
            }

            if ($stkRemaining < 0.01) {
                session()->flash('failedMsg', 'Capping limit already exhausted for Staking #' . $selectedStaking->id . '.');

                return redirect()->back();
            }

            $amount = min($amount, $stkRemaining);
        }

        if ($cap > 0 && $profit > ($tradingWalletAmount / 100) * $cap) {
            session()->flash('failedMsg', 'Invalid amount entered. You can add only ' . $amount . ' amount as profit.');

            return redirect()->back();
        }

        if ($cap > 0 && $profit > $amount) {
            session()->flash('failedMsg', 'Capped Limit Reached. You can add only ' . $amount . ' amount as profit.');

            return redirect()->back();
        }

        if ($amount < 0.01) {
            session()->flash('failedMsg', 'Capped Limit Reached. You can not add more than the profit amount.');

            return redirect()->back();
        }

        $qry = new StakingIncome();
        $qry->date = date('Y-m-d');
        $qry->staking_id = $selectedStaking ? $selectedStaking->id : 0;
        $qry->memberid = $memberid;
        $qry->total_investment = $selectedStaking
            ? ((float) ($selectedStaking->trading_wallet_amount > 0 ? $selectedStaking->trading_wallet_amount : round($selectedStaking->invest_amount * 0.70, 2)))
            : 0;
        $qry->rate = 0;
        $qry->amount = $amount;
        $qry->installment = 0;
        $qry->level_status = 1;
        $qry->added_by = 'Admin';
        $qry->status = 'Paid';
        $qry->save();

        $wallet = $memUpdate->wallet;
        $memUpdate->wallet += $amount;
        $memUpdate->save();

        walletTransfer($memberid, $amount, 'debit', $wallet, 'Add Trading Profit', 'Add Trading Profit by admin amount has been transfered into wallet.');

        $isStakingDeactivated = false;

        // Auto-deactivate the targeted staking if its capping is now completely reached
        if ($selectedStaking && $cap > 0) {
            $stkInvest = (float) $selectedStaking->invest_amount;
            $stkTwAmt = (float) ($selectedStaking->trading_wallet_amount > 0 ? $selectedStaking->trading_wallet_amount : ($stkInvest * 0.70));
            $stkCapTotal = round(($stkTwAmt / 100) * $cap, 2);
            $stkPaidTotal = (float) StakingIncome::where([['status', 'Paid'], ['staking_id', $selectedStaking->id]])->sum('amount');
            $stkRemainingAfter = max(0.0, round($stkCapTotal - $stkPaidTotal, 2));

            if ($stkRemainingAfter <= 0.00) {
                $selectedStaking->status = 'Deactive';
                $selectedStaking->save();

                if ($selectedStaking->txnid) {
                    PackageDetail::where('txnid', $selectedStaking->txnid)
                        ->where('status', 'Active')
                        ->update(['status' => 'Expired']);
                }
                $isStakingDeactivated = true;
            }
        }

        // When member's overall capping reaches zero, or when any active staking has reached its capping limit, deactivate:
        if ($cap > 0) {
            $newAchieved = (float) StakingIncome::where([['status', 'Paid'], ['memberid', $memberid]])->sum('amount');
            $newMemberCapTotal = ($tradingWalletAmount / 100) * $cap;
            $isMemberCapExhausted = ($newAchieved >= $newMemberCapTotal);

            $otherActive = StakingDetail::where([['memberid', $memberid], ['status', 'Active']])->get();
            foreach ($otherActive as $mstk) {
                $mTw = (float) ($mstk->trading_wallet_amount > 0 ? $mstk->trading_wallet_amount : ($mstk->invest_amount * 0.70));
                $mCap = round(($mTw / 100) * $cap, 2);
                $mPaid = (float) StakingIncome::where([['status', 'Paid'], ['staking_id', $mstk->id]])->sum('amount');
                if ($isMemberCapExhausted || $mPaid >= $mCap) {
                    $mstk->status = 'Deactive';
                    $mstk->save();

                    if ($mstk->txnid) {
                        PackageDetail::where('txnid', $mstk->txnid)
                            ->where('status', 'Active')
                            ->update(['status' => 'Expired']);
                    }
                }
            }
        }

        // If member has no remaining active stakings, set member status to Deactive
        $hasActiveLeft = StakingDetail::where('memberid', $memberid)->where('status', 'Active')->exists();
        if (! $hasActiveLeft) {
            $member->status = 'Deactive';
            $member->save();
        }

        $successMsg = $selectedStaking
            ? 'Trading profit of $' . number_format($amount, 2) . ' added successfully for Staking #' . $selectedStaking->id . ' (' . ($selectedStaking->package ?? 'Package') . ').' . ($isStakingDeactivated ? ' Package has reached 100% capping and is now Deactive.' : '')
            : 'Trading profit added successfully.';

        session()->flash('successMsg', $successMsg);

        return redirect()->back();
    }

    public function addTradeProfitDetails(Request $request)
    {
        $memberId = trim((string) $request->query('member_id', ''));
        $query = StakingIncome::where([['status', 'Paid'], ['added_by', 'Admin']]);
        if ($memberId !== '') {
            $query->where('memberid', $memberId);
        }
        $filterMeta = $this->applyAdminDateFilter($query, $request, 'created_at', ['created_at' => 'created_at']);
        if ($memberId !== '') {
            $filterMeta['filterActive'] = true;
            $filterMeta['filterSummary'] = 'Member: ' . $memberId . (! empty($filterMeta['filterSummary']) ? ' | ' . $filterMeta['filterSummary'] : '');
        }
        $result = array_merge($filterMeta, [
            'data' => $query->orderby('created_at', 'desc')->get(),
            'pageTitle' => 'Trading Profit Details',
            'action' => url('hdgteyusjasget/trade/add-trading-profit-details'),
            'showMemberIdFilter' => true,
        ]);

        return view('admin.trade.add-trading-profit-details')->with($result);
    }

    private function applyAdminDateFilter($query, Request $request, string $defaultColumn, array $allowedDateColumns = []): array
    {
        $tz = config('app.timezone', 'UTC');
        $filterMode = $request->query('filter_mode');
        $filterDate = $request->query('filter_date');
        $preset = $request->query('preset');
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');
        $dateField = $request->query('date_field', $request->query('date_type'));

        // Safe column resolution using strict allowlist mapping
        $targetColumn = $defaultColumn;
        if (! empty($dateField) && isset($allowedDateColumns[$dateField])) {
            $targetColumn = $allowedDateColumns[$dateField];
        } elseif (! empty($dateField) && in_array($dateField, $allowedDateColumns, true)) {
            $targetColumn = $dateField;
        }

        $activeFilter = false;
        $summary = null;
        $errors = [];

        // Validate request parameters
        $validator = Validator::make($request->query(), [
            'filter_mode' => 'nullable|in:single,range,advanced',
            'filter_date' => 'nullable|date_format:Y-m-d',
            'preset' => 'nullable|in:today,yesterday,last_7_days,last_30_days,this_month,last_month,this_year,custom',
            'from_date' => 'nullable|date_format:Y-m-d',
            'to_date' => 'nullable|date_format:Y-m-d|after_or_equal:from_date',
            'date_field' => 'nullable|string',
            'date_type' => 'nullable|string',
            'member_id' => 'nullable|string|max:100',
        ], [
            'filter_date.date_format' => 'The filter date must be a valid date in YYYY-MM-DD format.',
            'from_date.date_format' => 'The From Date must be a valid date in YYYY-MM-DD format.',
            'to_date.date_format' => 'The To Date must be a valid date in YYYY-MM-DD format.',
            'to_date.after_or_equal' => 'The To Date cannot be earlier than the From Date.',
            'preset.in' => 'The selected preset is invalid.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return [
                'filterActive' => false,
                'filterSummary' => null,
                'filterErrors' => $errors,
                'dateOptions' => $allowedDateColumns,
                'defaultDateField' => $defaultColumn,
            ];
        }

        // Determine field display label for summary badge
        $fieldLabel = '';
        if (count($allowedDateColumns) > 1 && ! empty($dateField)) {
            $labels = [
                'payment_date' => 'Payment Date',
                'request_date' => 'Request Date',
                'updated_at' => 'Cancellation Date',
                'cancelled_date' => 'Cancellation Date',
                'created_at' => 'Date',
                'invest_date' => 'Invest Date',
            ];
            $fieldLabel = ($labels[$dateField] ?? ucfirst(str_replace('_', ' ', $dateField))) . ': ';
        }

        // 1. Basic Single Date Filter
        if (! empty($filterDate) && ($filterMode === 'single' || (empty($filterMode) && empty($preset) && empty($fromDate) && empty($toDate)))) {
            try {
                $parsed = Carbon::createFromFormat('Y-m-d', $filterDate, $tz);
                $start = $parsed->copy()->startOfDay()->toDateTimeString();
                $end = $parsed->copy()->endOfDay()->toDateTimeString();

                $query->whereBetween($targetColumn, [$start, $end]);
                $activeFilter = true;
                $summary = $fieldLabel . $parsed->format('d M Y');
            } catch (\Exception $e) {
                $errors[] = 'Invalid date format.';
            }
        }
        // 2. Advanced / Preset Filter
        elseif (! empty($preset) || ! empty($fromDate) || ! empty($toDate)) {
            $now = Carbon::now($tz);
            $start = null;
            $end = null;
            $label = null;

            switch ($preset) {
                case 'today':
                    $start = $now->copy()->startOfDay();
                    $end = $now->copy()->endOfDay();
                    $label = 'Today';
                    break;
                case 'yesterday':
                    $yesterday = Carbon::yesterday($tz);
                    $start = $yesterday->copy()->startOfDay();
                    $end = $yesterday->copy()->endOfDay();
                    $label = 'Yesterday';
                    break;
                case 'last_7_days':
                    // Today + 6 previous calendar days = 7 days inclusive
                    $start = $now->copy()->subDays(6)->startOfDay();
                    $end = $now->copy()->endOfDay();
                    $label = 'Last 7 Days (' . $start->format('d M') . ' – ' . $end->format('d M') . ')';
                    break;
                case 'last_30_days':
                    // Today + 29 previous calendar days = 30 days inclusive
                    $start = $now->copy()->subDays(29)->startOfDay();
                    $end = $now->copy()->endOfDay();
                    $label = 'Last 30 Days (' . $start->format('d M') . ' – ' . $end->format('d M') . ')';
                    break;
                case 'this_month':
                    $start = $now->copy()->startOfMonth()->startOfDay();
                    $end = $now->copy()->endOfMonth()->endOfDay();
                    $label = 'This Month (' . $now->format('M Y') . ')';
                    break;
                case 'last_month':
                    $lastMonth = $now->copy()->subMonth();
                    $start = $lastMonth->copy()->startOfMonth()->startOfDay();
                    $end = $lastMonth->copy()->endOfMonth()->endOfDay();
                    $label = 'Last Month (' . $lastMonth->format('M Y') . ')';
                    break;
                case 'this_year':
                    $start = $now->copy()->startOfYear()->startOfDay();
                    $end = $now->copy()->endOfYear()->endOfDay();
                    $label = 'This Year (' . $now->format('Y') . ')';
                    break;
                case 'custom':
                default:
                    if (! empty($fromDate) && ! empty($toDate)) {
                        try {
                            $fromParsed = Carbon::createFromFormat('Y-m-d', $fromDate, $tz);
                            $toParsed = Carbon::createFromFormat('Y-m-d', $toDate, $tz);

                            if ($toParsed->lt($fromParsed)) {
                                $errors[] = 'To Date cannot be earlier than From Date.';
                            } else {
                                $start = $fromParsed->copy()->startOfDay();
                                $end = $toParsed->copy()->endOfDay();
                                $label = $fromParsed->format('d M Y') . ' – ' . $toParsed->format('d M Y');
                            }
                        } catch (\Exception $e) {
                            $errors[] = 'Invalid date range provided.';
                        }
                    } elseif (! empty($fromDate) || ! empty($toDate)) {
                        $errors[] = 'Both From Date and To Date are required for custom range.';
                    }
                    break;
            }

            if ($start && $end && empty($errors)) {
                $query->whereBetween($targetColumn, [$start->toDateTimeString(), $end->toDateTimeString()]);
                $activeFilter = true;
                $summary = $fieldLabel . $label;
            }
        }

        return [
            'filterActive' => $activeFilter,
            'filterSummary' => $summary,
            'filterErrors' => $errors,
            'dateOptions' => $allowedDateColumns,
            'defaultDateField' => $defaultColumn,
        ];
    }
}
