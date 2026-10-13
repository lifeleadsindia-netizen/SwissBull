<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PackageDistribution;
use App\Models\PackagePlan;
use App\Models\StakingDetail;
use App\Services\PepeRewardService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvestmentController extends Controller
{
    public function createStaking()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();

        $result['packageDistribution'] = PackageDistribution::find(1);
        $result['activeStakings'] = StakingDetail::where('memberid', $memberid)->orderBy('created_at', 'desc')->take(3)->get();

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

        $memberid = session('MEMBER_ID');
        $package = $request->package;
        $amount = $request->amount;

        $member = MemberDetail::where('memberid', $memberid)->first();

        if (! $member) {
            session()->flash('failedMsg', 'Invalid member.');

            return redirect()->back();
        }

        $member = MemberDetail::where('memberid', $memberid)->first();

        if (! $member) {
            session()->flash('failedMsg', 'Invalid member.');

            return redirect()->back();
        }

        if ($member->status === 'Blocked') {
            session()->flash('failedMsg', 'Your account has been blocked. Please contact support.');

            return redirect()->back();
        }

        $sponsorid = $member->sponsorid;
        $name = $member->name;

        if ($package == 'Package1') {
            if ($amount < 50 || $amount > 500) {
                return redirect()->back()->with('failedMsg', 'For package 50 - 500, staking amount must be between 50 and 500 USDT.');
            }
        } elseif ($package === 'Package2') {
            if ($amount < 600 || $amount > 5000) {
                return redirect()->back()->with('failedMsg', 'For package 600 - 5000, staking amount must be between 600 and 5000 USDT.');
            }
        } elseif ($package === 'Package3') {
            if ($amount < 6000) {
                return redirect()->back()->with('failedMsg', 'For package 6000 and above, staking amount must be at least 6000 USDT.');
            }
        } else {
            return redirect()->back()->with('failedMsg', 'Please select a valid staking package.');
        }

        if ($member->p2p_wallet < $amount) {
            session()->flash('failedMsg', 'Insufficient Fund Wallet balance. Available: $'.number_format((float) $member->p2p_wallet, 2));

            return redirect()->back();
        }

        $packagedistribution = PackageDistribution::find(1);
        $trading_wallet = $packagedistribution->trading_wallet * $amount / 100;

        $orderId = 'OD'.time().rand(10, 99);
        $txnid = 'STK/'.date('YmdHis').rand(100, 999);

        $stakingDetail = new StakingDetail;
        $stakingDetail->memberid = $memberid;
        $stakingDetail->invest_date = now();
        $stakingDetail->invest_amount = $amount;
        $stakingDetail->package = $package;
        $stakingDetail->trade_percentage = $packagedistribution->trading_wallet;
        $stakingDetail->trading_wallet_amount = $trading_wallet;
        $stakingDetail->txnid = $txnid;
        $stakingDetail->order_id = $orderId;
        $stakingDetail->installments = 0;
        $stakingDetail->status = 'Active';
        $stakingDetail->save();

        $member->package_name = $package;
        $member->package = $amount;
        $member->self_biz += $amount;
        $wallet = $member->p2p_wallet;
        $member->p2p_wallet -= $amount;
        $member->team_biz += $amount;
        $member->daily_team_biz += $amount;
        $member->trading_wallet += $trading_wallet;
        if ($member->status == 'Temp') {
            $member->status = 'Active';
            $member->activated_at = $member->activated_at ?? now();
            updateDownline($sponsorid);
        }
        $member->save();

        // Rule 3: Direct referral activation awards 500 PEPE tokens to sponsor
        if ($member->status === 'Active' && ! empty($sponsorid)) {
            PepeRewardService::awardDirectActivationReward($sponsorid, $member);
        }

        walletTransfer(
            $memberid,
            $amount,
            'credit',
            $wallet,
            'Staking Created',
            'Staking amount deducted from wallet'
        );

        sponsorBizUpdate($sponsorid, $amount);
        directIncome($sponsorid, $memberid, $name, $amount, 'Direct Income', $levelPercentages = 0);
        team_biz_update($sponsorid, $amount);
        session()->flash('successMsg', 'Staking has been created successfully.');

        return redirect()->back();
    }

    public function StakingDetails()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();

        $defaultLockDays = PackageDistribution::getDefaultLockDays();
        $result['defaultLockDays'] = $defaultLockDays;

        $stakings = StakingDetail::where('memberid', $memberid)->orderBy('created_at', 'desc')->get();
        foreach ($stakings as $stk) {
            $stkPlan = $stk->package ? PackagePlan::findByRange($stk->package) : null;
            if (! $stkPlan && (float) $stk->invest_amount > 0) {
                $stkPlan = PackagePlan::findForAmount((float) $stk->invest_amount);
            }

            $stkLockDays = $stkPlan && (int) $stkPlan->lock_days > 0
                ? (int) $stkPlan->lock_days
                : (int) $defaultLockDays;

            $stkActivatedAt = $stk->created_at ? Carbon::parse($stk->created_at) : ($stk->invest_date ? Carbon::parse($stk->invest_date) : null);
            $stkLockedUntil = ($stkActivatedAt && $stkLockDays > 0) ? $stkActivatedAt->copy()->addDays($stkLockDays) : null;

            $stkIsLocked = false;
            $stkRemainingSeconds = 0;
            $stkRemainingDays = 0;

            if ($stkLockedUntil && now()->lt($stkLockedUntil)) {
                $stkIsLocked = true;
                $stkRemainingSeconds = (int) now()->diffInSeconds($stkLockedUntil, false);
                $stkRemainingDays = (int) ceil($stkRemainingSeconds / 86400);
            }

            $stkRemSecs = max(0, $stkRemainingSeconds);
            $stkDays = (int) floor($stkRemSecs / 86400);
            $stkHours = (int) floor(($stkRemSecs % 86400) / 3600);
            $stkMins = (int) floor(($stkRemSecs % 3600) / 60);
            $stkSec = (int) ($stkRemSecs % 60);

            $pkgRangeLabel = $stkPlan?->name ?? ($stk->package ?? 'Standard');
            if ($stk->package === 'Package1') {
                $pkgRangeLabel = 'Package 1 ($50 - $500)';
            } elseif ($stk->package === 'Package2') {
                $pkgRangeLabel = 'Package 2 ($600 - $5000)';
            } elseif ($stk->package === 'Package3') {
                $pkgRangeLabel = 'Package 3 ($6000+)';
            }

            $stk->package_range = $pkgRangeLabel;
            $stk->lock_days = $stkLockDays;
            $stk->is_locked = $stkIsLocked;
            $stk->lock_status_label = $stkIsLocked ? 'Fund Locked' : 'Fund Unlocked';
            $stk->remaining_lock_days = $stkRemainingDays;
            $stk->remaining_lock_seconds = $stkRemainingSeconds;
            $stk->remaining_lock_formatted = $stkIsLocked
                ? "{$stkDays}d ".sprintf('%02d', $stkHours).'h '.sprintf('%02d', $stkMins).'m '.sprintf('%02d', $stkSec).'s'
                : 'Unlocked';
            $stk->unlock_datetime = $stkLockedUntil ? $stkLockedUntil->format('d M Y, H:i') : '-';
            $stk->unlock_timestamp = $stkLockedUntil ? $stkLockedUntil->timestamp * 1000 : null;
        }

        $result['investment_data'] = $stakings;

        return view('member.investment.investment-details', $result);
    }
}
