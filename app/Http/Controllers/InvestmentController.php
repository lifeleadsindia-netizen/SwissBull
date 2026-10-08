<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PackageDistribution;
use App\Models\PackagePlan;
use App\Models\StakingDetail;
use App\Models\TradingWalletSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InvestmentController extends Controller
{
    public function createStaking()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['packagePlans'] = PackagePlan::where('status', 'Active')->orderBy('min_amount', 'asc')->get();

        return view('member.investment.create-investment', $result);
    }

    public function createInvestment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'memberid' => 'required',
            'package' => 'required',
            'amount' => 'required|numeric|gte:50',
        ], [
            'memberid.required' => 'Member ID is required.',
            'package.required' => 'Please select a staking package.',
            'amount.required' => 'Please enter staking amount.',
            'amount.numeric' => 'Staking amount must be a valid number.',
            'amount.gte' => 'Minimum staking amount is 50 USDT.',
        ]);

        if ($validator->fails()) {
            $msg = $validator->errors()->first();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 422);
            }
            session()->flash('failedMsg', $msg);

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $memberid = session('MEMBER_ID') ?: $request->memberid;
        $rawPackage = trim((string) $request->input('package'));
        $amount = (float) $request->input('amount');

        // Normalize package value
        $normalizedPackage = match ($rawPackage) {
            '50-500', '50 - 500', 'Package 1' => '50-500',
            '600-5000', '600 - 5000', 'Package 2' => '600-5000',
            '6000+', '6000 and above', 'Package 3' => '6000+',
            default => $rawPackage,
        };

        $member = MemberDetail::where('memberid', $memberid)->first();

        if (! $member) {
            $msg = 'Invalid member.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 422);
            }
            session()->flash('failedMsg', $msg);

            return redirect()->back();
        }

        if ($member->status === 'Blocked') {
            $msg = 'Your account has been blocked. Please contact support.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 422);
            }
            session()->flash('failedMsg', $msg);

            return redirect()->back();
        }

        // Fetch dynamic package plan configuration if available
        $plan = PackagePlan::findByRange($normalizedPackage);
        if (! $plan) {
            $plan = PackagePlan::where('package_range', $normalizedPackage)
                ->orWhere('name', $normalizedPackage)
                ->first();
        }

        if ($plan && $plan->status !== 'Active') {
            $msg = 'Selected package is currently inactive. Please choose an active package.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 422);
            }
            session()->flash('failedMsg', $msg);

            return redirect()->back();
        }

        // Validate package range rules strictly
        $isValidRange = false;
        $rangeErrorMsg = '';

        if ($plan) {
            $min = (float) $plan->min_amount;
            $max = $plan->max_amount !== null ? (float) $plan->max_amount : null;

            if ($amount >= $min && ($max === null || $amount <= $max)) {
                $isValidRange = true;
            } else {
                if ($max !== null) {
                    $rangeErrorMsg = "For package {$plan->name}, staking amount must be between {$min} and {$max} USDT.";
                } else {
                    $rangeErrorMsg = "For package {$plan->name}, staking amount must be at least {$min} USDT.";
                }
            }
        } else {
            // Fallback to static boundaries
            if ($normalizedPackage === '50-500') {
                if ($amount >= 50 && $amount <= 500) {
                    $isValidRange = true;
                } else {
                    $rangeErrorMsg = 'For package 50 - 500, staking amount must be between 50 and 500 USDT.';
                }
            } elseif ($normalizedPackage === '600-5000') {
                if ($amount >= 600 && $amount <= 5000) {
                    $isValidRange = true;
                } else {
                    $rangeErrorMsg = 'For package 600 - 5000, staking amount must be between 600 and 5000 USDT.';
                }
            } elseif ($normalizedPackage === '6000+') {
                if ($amount >= 6000) {
                    $isValidRange = true;
                } else {
                    $rangeErrorMsg = 'For package 6000 and above, staking amount must be at least 6000 USDT.';
                }
            } else {
                $rangeErrorMsg = 'Please select a valid staking package.';
            }
        }

        if (! $isValidRange) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $rangeErrorMsg], 422);
            }
            session()->flash('failedMsg', $rangeErrorMsg);

            return redirect()->back();
        }

        // Lock verification
        $lockError = null;
        if (! $member->canPurchasePackage($lockError)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $lockError], 422);
            }
            session()->flash('failedMsg', $lockError);

            return redirect()->back();
        }

        // Staking balance verification: Staking is funded strictly from Fund Wallet (p2p_wallet)
        if ((float) $member->p2p_wallet < $amount) {
            $msg = 'Insufficient Fund Wallet (P2P Wallet) balance. Available: $'.number_format((float) $member->p2p_wallet, 2);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 422);
            }
            session()->flash('failedMsg', $msg);

            return redirect()->back();
        }

        $distributionConfig = PackageDistribution::getDistributionConfig();
        $tradingWalletPercent = $plan ? (float) ($plan->trading_wallet_percent ?: 70.0) : (float) ($distributionConfig['p2p_wallet'] ?? 70.0);
        $returnPercent = $plan ? (float) ($plan->return_percent ?: 5.0) : 5.0;
        $maxReturnPercent = $plan ? (float) ($plan->max_return_percent ?: 200.0) : 200.0;
        $lockDays = $plan ? (int) ($plan->lock_days ?: 90) : 90;
        $durationDays = $plan ? (int) ($plan->duration_days ?: 1200) : 1200;

        $tradingWalletAmount = round($amount * ($tradingWalletPercent / 100), 2);
        $maxEarning = round($amount * ($maxReturnPercent / 100), 2);
        $orderId = 'OD'.time().rand(10, 99);
        $txnid = 'STK/'.date('YmdHis').rand(100, 999);

        try {
            DB::transaction(function () use (
                $memberid,
                $amount,
                $normalizedPackage,
                $orderId,
                $txnid,
                $tradingWalletAmount,
                $returnPercent,
                $maxReturnPercent,
                $maxEarning,
                $lockDays,
                $durationDays
            ) {
                $member = MemberDetail::where('memberid', $memberid)->lockForUpdate()->first();
                if (! $member) {
                    throw new \InvalidArgumentException('Invalid Member ID.');
                }

                if ((float) $member->p2p_wallet < $amount) {
                    throw new \InvalidArgumentException('Insufficient Fund Wallet (P2P Wallet) balance.');
                }

                $sponsorid = $member->sponsorid;
                $name = $member->name;

                // 1. Create Package Detail Record
                $packageDetail = new PackageDetail;
                $packageDetail->memberid = $memberid;
                $packageDetail->package_type = 'Investment Package';
                $packageDetail->package_range = $normalizedPackage;
                $packageDetail->package_value = $amount;
                $packageDetail->invest_amount = $amount;
                $packageDetail->trading_wallet_amount = $tradingWalletAmount;
                $packageDetail->order_id = $orderId;
                $packageDetail->txnid = $txnid;
                $packageDetail->payment_mode = 'P2P Wallet';
                $packageDetail->status = 'Active';
                $packageDetail->activated_at = now();
                $packageDetail->expires_at = now()->addDays($durationDays);
                $packageDetail->return_percent = $returnPercent;
                $packageDetail->total_earning = 0.00;
                $packageDetail->max_earning = $maxEarning;
                $packageDetail->max_return_percent = $maxReturnPercent;
                $packageDetail->lock_days = $lockDays;
                $packageDetail->lock_applied_at = now();
                $packageDetail->locked_until = $lockDays > 0 ? now()->addDays($lockDays) : null;
                $packageDetail->save();

                // 2. Create Staking Detail Record
                $stakingDetail = new StakingDetail;
                $stakingDetail->memberid = $memberid;
                $stakingDetail->invest_date = now();
                $stakingDetail->invest_amount = $amount;
                $stakingDetail->package = $normalizedPackage;
                $stakingDetail->txnid = $txnid;
                $stakingDetail->order_id = $orderId;
                $stakingDetail->installments = 0;
                $stakingDetail->total_installments = $durationDays;
                $stakingDetail->rate = $returnPercent;
                $stakingDetail->capping_percent = $maxReturnPercent;
                $stakingDetail->max_amount = $maxEarning;
                $stakingDetail->total_earned = 0.00;
                $stakingDetail->status = 'Active';
                $stakingDetail->activated_at = now();
                $stakingDetail->save();

                // 3. Update Member Wallets:
                // Deduct 100% from Fund Wallet (p2p_wallet)
                $oldP2P = (float) $member->p2p_wallet;
                $member->p2p_wallet = $oldP2P - $amount;

                // Credit 70% to Trading Wallet (trading_wallet)
                $oldTradingWallet = (float) $member->trading_wallet;
                $member->trading_wallet = $oldTradingWallet + $tradingWalletAmount;

                // 4. Update Member Status & Self Biz
                $member->package = $amount;
                $member->self_biz = ((float) $member->self_biz) + $amount;
                $member->team_biz = ((float) $member->team_biz) + $amount;
                $member->daily_team_biz = ((float) $member->daily_team_biz) + $amount;

                if (in_array($member->status, ['Temp', 'Deactive'])) {
                    $member->status = 'Active';
                    $member->activated_at = $member->activated_at ?? now();
                }

                $setting = TradingWalletSetting::getActiveSetting();
                $withdrawalPercent = (float) ($setting->withdrawal_percent ?? 100.00);
                $member->applyTradingWalletLock($lockDays, $withdrawalPercent);
                $member->save();

                // 5. Wallet Ledgers:
                // Deduct full amount from P2P Wallet (credit = deduction)
                p2pwalletTransfer(
                    $memberid,
                    $amount,
                    'credit',
                    $oldP2P,
                    'Staking Package',
                    "{$amount} USDT deducted from Fund Wallet for {$normalizedPackage} package staking"
                );

                // Add 70% to Trading Wallet (debit = addition)
                walletTransfer(
                    $memberid,
                    $tradingWalletAmount,
                    'debit',
                    $oldTradingWallet,
                    'Trading Wallet',
                    "{$tradingWalletAmount} USDT (70% of {$amount} USDT staking) credited to Trading Wallet"
                );

                // 6. Referral Bonus & Team Investment Share
                if ($sponsorid && $sponsorid !== 'Root') {
                    directIncome($sponsorid, $memberid, $name, $amount, 'Referral Bonus');
                    levelIncome($sponsorid, $memberid, $name, $amount, 'Daily Team Investment Share');
                    team_biz_update($sponsorid, $amount);
                }
            });
        } catch (\InvalidArgumentException $e) {
            $msg = $e->getMessage();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 422);
            }
            session()->flash('failedMsg', $msg);

            return redirect()->back();
        } catch (\Throwable $e) {
            $msg = 'An error occurred while creating staking: '.$e->getMessage();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 500);
            }
            session()->flash('failedMsg', $msg);

            return redirect()->back();
        }

        $successMsg = 'Staking package activated successfully! 70% ($'.number_format($tradingWalletAmount, 2).') has been credited to your Trading Wallet.';
        session()->flash('successMsg', $successMsg);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => $successMsg,
            ]);
        }

        return redirect()->back();
    }

    public function StakingDetails()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['investment_data'] = StakingDetail::where('memberid', $memberid)->orderBy('created_at', 'desc')->get();

        return view('member.investment.investment-details', $result);
    }
}
