<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PackageDistribution;
use App\Models\StakingDetail;
use App\Services\PepeRewardService;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function createStaking()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['packageDistribution'] = PackageDistribution::find(1);
        $result['activeStakings'] = StakingDetail::where('memberid', $memberid)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('member.investment.create-investment', $result);
    }

    public function createInvestment(Request $request)
    {
        $request->validate([
            'memberid' => 'required',
            'amount' => 'required|numeric',
            'package' => 'required',
        ]);

        $memberid = session('MEMBER_ID');
        $amount = (float) $request->amount;
        $package = $request->package;

        $member = MemberDetail::where('memberid', $memberid)->first();
        if (! $member) {
            session()->flash('failedMsg', 'Invalid member account.');

            return redirect()->back();
        }

        $sponsorid = $member->sponsorid;

        if ($package === 'Package1') {
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
        // $member->direct_biz += $amount;
        $wallet = $member->p2p_wallet;
        $member->p2p_wallet -= $amount;
        $member->team_biz += $amount;
        // $member->daily_team_biz += $amount;
        $member->trading_wallet += $trading_wallet;

        // Transition status to Active if previously Temp (first activation) or Deactive (re-activation)
        if ($member->status === 'Temp') {
            $member->status = 'Active';
            $member->activated_at = $member->activated_at ?? now();
            updateDownline($sponsorid);
        } elseif ($member->status === 'Deactive') {
            $member->status = 'Active';
        }
        $member->save();

        // Direct referral activation awards 500 PEPE tokens to sponsor
        if ($member->status === 'Active' && ! empty($sponsorid)) {
            PepeRewardService::awardDirectActivationReward($sponsorid, $member);
        }

        walletTransfer(
            $memberid,
            $amount,
            'credit',
            $wallet,
            'Staking Created',
            'Staking amount deducted from fund wallet'
        );

        sponsorBizUpdate($sponsorid, $amount);
        directIncome($sponsorid, $memberid, $member->name, $amount);
        session()->flash('successMsg', 'Staking package has been created successfully.');

        return redirect()->back();
    }

    public function StakingDetails()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $stakings = StakingDetail::where('memberid', $memberid)->orderBy('created_at', 'desc')->get();
        $result['investment_data'] = $stakings;

        return view('member.investment.investment-details', $result);
    }
}
