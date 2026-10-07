<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\ImportFund;
use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PackageDistribution;
use App\Models\PackagePlan;
use App\Models\StakingDetail;
use App\Models\TradingWalletSetting;
use App\Models\WalletTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FundController extends Controller
{
    // public function importfund()
    // {
    //     $memberid = session('MEMBER_ID');
    //     $result['data'] = MemberDetail::where('memberid', $memberid)->first();
    //     return view('member.fund.import-fund')->with($result);
    // }

    public function importfund()
    {
        $memberid = session('MEMBER_ID');
        $country = session('country');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['country'] = Country::where('name', $country)->first();
        $result['addfundData'] = ImportFund::where([['memberid', $memberid], ['added_by', 'User']])->orderBy('created_at', 'desc')->get();
        $result['packagePlans'] = PackagePlan::where('status', 'Active')->orderBy('min_amount', 'asc')->get();

        return view('member.fund.import-flt')->with($result);
    }

    public function impfundhis(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['addfundData'] = ImportFund::where([['memberid', $memberid], ['added_by', 'User']])->orderBy('created_at', 'desc')->get();

        return view('member.fund.import-fund-history')->with($result);
    }

    public function addFund(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'memberid' => 'required',
            'package' => 'required',
            'amount' => 'required|numeric|gte:50',
            'txnid' => 'required',
        ], [
            'memberid.required' => 'Member ID is required.',
            'package.required' => 'Please select a deposit package.',
            'amount.required' => 'Please enter deposit amount.',
            'amount.numeric' => 'Deposit amount must be a valid number.',
            'amount.gte' => 'Minimum deposit amount is 50 USDT.',
            'txnid.required' => 'Transaction ID is required.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }
            session()->flash('FailedMsg', $validator->errors()->first());

            return redirect()->back();
        }

        $rawPackage = trim((string) $request->input('package'));
        $amount = (float) $request->input('amount');
        $txnid = trim((string) $request->input('txnid'));
        $memberid = trim((string) $request->input('memberid'));

        // Normalize package value
        $normalizedPackage = match ($rawPackage) {
            '50-500', '50 - 500' => '50-500',
            '600-5000', '600 - 5000' => '600-5000',
            '6000+', '6000 and above' => '6000+',
            default => null,
        };

        if ($normalizedPackage === null) {
            $msg = 'Please select a valid deposit package.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $msg,
                ], 422);
            }
            session()->flash('FailedMsg', $msg);

            return redirect()->back();
        }

        // Check for duplicate transaction ID to prevent double-crediting
        $alreadyProcessed = ImportFund::where('txnid', $txnid)->exists()
            || PackageDetail::where('txnid', $txnid)->exists()
            || StakingDetail::where('txnid', $txnid)->exists();

        if ($alreadyProcessed) {
            $msg = 'This transaction ID has already been processed.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $msg,
                ], 422);
            }
            session()->flash('FailedMsg', $msg);

            return redirect()->back();
        }

        // Fetch dynamic package plan configuration if available
        $plan = PackagePlan::findByRange($normalizedPackage);
        if ($plan && $plan->status !== 'Active') {
            $msg = 'Selected package is currently inactive. Please choose an active package.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $msg,
                ], 422);
            }
            session()->flash('FailedMsg', $msg);

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
                    $rangeErrorMsg = "For package {$plan->name}, deposit amount must be between {$min} and {$max} USDT.";
                } else {
                    $rangeErrorMsg = "For package {$plan->name}, deposit amount must be at least {$min} USDT.";
                }
            }
        } else {
            // Fallback to static boundaries
            if ($normalizedPackage === '50-500') {
                if ($amount >= 50 && $amount <= 500) {
                    $isValidRange = true;
                } else {
                    $rangeErrorMsg = 'For package 50 - 500, deposit amount must be between 50 and 500 USDT.';
                }
            } elseif ($normalizedPackage === '600-5000') {
                if ($amount >= 600 && $amount <= 5000) {
                    $isValidRange = true;
                } else {
                    $rangeErrorMsg = 'For package 600 - 5000, deposit amount must be between 600 and 5000 USDT.';
                }
            } elseif ($normalizedPackage === '6000+') {
                if ($amount >= 6000) {
                    $isValidRange = true;
                } else {
                    $rangeErrorMsg = 'For package 6000 and above, deposit amount must be at least 6000 USDT.';
                }
            }
        }

        if (! $isValidRange) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $rangeErrorMsg,
                ], 422);
            }
            session()->flash('FailedMsg', $rangeErrorMsg);

            return redirect()->back();
        }

        $distributionConfig = PackageDistribution::getDistributionConfig();
        $p2pWalletPercent = $plan ? (float) ($plan->trading_wallet_percent ?: 70.0) : (float) ($distributionConfig['p2p_wallet'] ?? 70.0);
        $returnPercent = $plan ? (float) ($plan->return_percent ?: 5.0) : 5.0;
        $maxReturnPercent = $plan ? (float) ($plan->max_return_percent ?: 200.0) : 200.0;
        $lockDays = $plan ? (int) ($plan->lock_days ?: 30) : 30;
        $durationDays = $plan ? (int) ($plan->duration_days ?: 1200) : 1200;

        $tradingWalletAmount = round($amount * ($p2pWalletPercent / 100), 2);
        $maxEarning = round($amount * ($maxReturnPercent / 100), 2);
        $orderId = 'OD'.time().rand(10, 99);

        try {
            DB::transaction(function () use (
                $memberid,
                $amount,
                $normalizedPackage,
                $txnid,
                $orderId,
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

                // 1. Create Deposit Record
                $importFund = new ImportFund;
                $importFund->memberid = $memberid;
                $importFund->orderid = $orderId;
                $importFund->package = $normalizedPackage;
                $importFund->amount = $amount;
                $importFund->txnid = $txnid;
                $importFund->type = 'Add';
                $importFund->mode = 'Online';
                $importFund->status = 'Approved';
                $importFund->added_by = 'User';
                $importFund->wallet_type = 'Wallet';
                $importFund->save();

                // 2. Create Package Detail / Investment Record
                $packageDetail = new PackageDetail;
                $packageDetail->memberid = $memberid;
                $packageDetail->package_type = 'Investment Package';
                $packageDetail->package_range = $normalizedPackage;
                $packageDetail->package_value = $amount;
                $packageDetail->invest_amount = $amount;
                $packageDetail->trading_wallet_amount = $tradingWalletAmount;
                $packageDetail->order_id = $orderId;
                $packageDetail->txnid = $txnid;
                $packageDetail->payment_mode = 'USDT (BEP-20)';
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

                // 2b. Create / Activate Staking Detail Record (Phase 2 + Phase 3 Staking ROI)
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

                // 3. Update Member Trading Wallet (70% Allocation) and Member Status
                $oldWallet = (float) $member->p2p_wallet;
                $member->p2p_wallet = $oldWallet + $tradingWalletAmount;
                $member->package = $amount;
                $member->self_biz = ((float) $member->self_biz) + $amount;

                if (in_array($member->status, ['Temp', 'Deactive'])) {
                    $member->status = 'Active';
                    $member->activated_at = $member->activated_at ?? now();
                }

                $setting = TradingWalletSetting::getActiveSetting();
                $withdrawalPercent = (float) ($setting->withdrawal_percent ?? 100.00);
                $member->applyTradingWalletLock($lockDays, $withdrawalPercent);
                $member->save();

                // 4. Record Wallet Ledger Entry (70% Trading Wallet allocation)
                p2pwalletTransfer(
                    $memberid,
                    $tradingWalletAmount,
                    'debit',
                    $oldWallet,
                    'Fund Added',
                    "{$amount} USDT deposit: 70% ({$tradingWalletAmount} USDT) allocated to Trading Wallet"
                );
            });
        } catch (\InvalidArgumentException $e) {
            $msg = $e->getMessage();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 422);
            }
            session()->flash('FailedMsg', $msg);

            return redirect()->back();
        } catch (\Throwable $e) {
            $msg = 'An error occurred while processing your deposit: '.$e->getMessage();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $msg], 500);
            }
            session()->flash('FailedMsg', $msg);

            return redirect()->back();
        }

        session()->flash('successMsg', 'Your requested funds have been imported successfully.');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Your requested funds have been imported successfully.',
            ]);
        }

        return redirect()->back();
    }

    public function p2pwallet(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $memberid = $result['data']['memberid'];
        $result['passdata'] = WalletTransfer::where([['memberid', $result['data']['memberid']], ['walletType', 'P2P Fund Transfer']])->orderBy('created_at', 'desc')->get();

        return view('member.p2p.p2p-wallet')->with($result);
    }

    public function p2pHistory(Request $request)
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $memberid = $result['data']['memberid'];
        $result['passdata'] = WalletTransfer::where([['memberid', $result['data']['memberid']], ['walletType', 'P2P Fund Transfer']])->orderBy('created_at', 'desc')->get();

        return view('member.p2p.p2p-history')->with($result);
    }

    public function fundTransfer()
    {
        $result['data'] = MemberDetail::find(session('MEMBER_ID'));
        $result['passdata'] = WalletTransfer::where([['memberid', $result['data']['memberid']], ['walletType', 'P2P Fund Transfer']])->orderBy('created_at', 'desc')->get();

        return view('member.p2p.fund_transfer')->with($result);
    }

    public function fundTrans(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'member' => 'required',
        ]);
        $memberid = strtoupper($request->post('member'));
        $val_id = MemberDetail::where('memberid', $memberid)->first();
        if ($val_id && $val_id->status != 'Blocked') {

            $sendData = MemberDetail::where('memberid', session('MEMBER_ID'))->first();
            $senderid = $sendData->memberid;
            $var = MemberDetail::where('memberid', $senderid)->first();
            $txn_password = $request->post('txn_password');
            $amount = $request->post('amount');
            if ($amount < 0) {
                session()->flash('failedMsg', 'Negative value is not allowed');

                return redirect()->back();
            }

            if ($val_id->status == 'Deactive') {
                session()->flash('failedMsg', 'Entered memberid is deactive. Amount cannot be transferred to deactive account');

                return redirect()->back();
            }

            if ($var->status == 'Deactive') {
                session()->flash('failedMsg', 'Your account status is deactive. Please contact system admin');

                return redirect()->back();
            }

            if ($amount > $var['wallet']) {
                session()->flash('failedMsg', 'You do not have enough balance to transfer this amount ');

                return redirect()->back();
            }
            if ($memberid == $senderid) {
                session()->flash('otpMsg', 'You can not transfer money to your own account');

                return redirect()->back();
            } else {

                // sending Money
                $qry = MemberDetail::where('memberid', $memberid)->first();
                $wallet = $qry->wallet;
                $qry->wallet += $amount;
                $qry->save();
                p2pwalletTransfer($memberid, $amount, 'debit', $wallet, 'Fund Transfer', '$ '.$amount.' has been transferred to your p2p wallet by '.$senderid.'');

                $query = MemberDetail::where('memberid', $senderid)->first();
                $senderwallet = $query->wallet;
                $query->wallet -= $amount;
                $query->save();

                p2pwalletTransfer($senderid, $amount, 'credit', $senderwallet, 'Fund Transfer', '$ '.$amount.' has been transferred by you to '.$memberid.' p2p wallet');

                // $trans = new P2pFundTransfers();
                // $trans->senderid = $senderid;
                // $trans->amount = $amount;
                // $trans->deduction = 0;
                // $trans->net_amount = $amount;
                // $trans->receiverid = $memberid;
                // $trans->save();

                session()->flash('successMsg', 'Amount has been transferred successfully');

                return redirect()->back();
            }
        } else {
            session()->flash('failedMsg', 'Entered Memberid is either blocked or unavailable');

            return redirect()->back();
        }
    }
}
