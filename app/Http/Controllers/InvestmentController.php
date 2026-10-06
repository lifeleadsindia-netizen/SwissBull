<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\StakingDetail;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
    public function createStaking()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();

        return view('member.investment.create-investment', $result);
    }

    public function createInvestment(Request $request)
    {
        $request->validate([
            'memberid' => 'required',
            'amount' => 'required|numeric',
        ]);

        $memberid = session('MEMBER_ID');
        $amount = $request->amount;
        $txn_password = $request->txn_password;

        $member = MemberDetail::where('memberid', $memberid)->first();

        if (! $member) {
            session()->flash('failedMsg', 'Invalid member.');

            return redirect()->back();
        }

        $sponsorid = $member->sponsorid;
        $name = $member->name;

        // Allowed staking package amounts matching select options
        $validPackages = [20, 30, 70, 150, 300, 600, 1200, 2500, 5000, 8000, 10000, 25000];
        $intAmount = (int) $amount;

        if (! in_array($intAmount, $validPackages) || (float) $amount != $intAmount) {
            session()->flash('failedMsg', 'Invalid staking package selected.');

            return redirect()->back();
        }

        $currentPackage = (int) $member->package;

        if ($currentPackage >= 25000) {
            session()->flash('failedMsg', 'All staking packages have already been completed.');

            return redirect()->back();
        }

        $packageProgression = [
            0 => 20,
            20 => 30,
            30 => 70,
            70 => 150,
            150 => 300,
            300 => 600,
            600 => 1200,
            1200 => 2500,
            2500 => 5000,
            5000 => 8000,
            8000 => 10000,
            10000 => 25000,
        ];

        $expectedPackage = $packageProgression[$currentPackage] ?? null;
        if (! $expectedPackage) {
            foreach ($validPackages as $pkg) {
                if ($currentPackage < $pkg) {
                    $expectedPackage = $pkg;
                    break;
                }
            }
        }

        if ($intAmount !== $expectedPackage) {
            session()->flash('failedMsg', 'Invalid package. Your next eligible staking package is $'.$expectedPackage.'.');

            return redirect()->back();
        }

        if ($member->p2p_wallet < $amount) {
            session()->flash('failedMsg', 'Insufficient wallet balance.');

            return redirect()->back();
        }

        if ($member->status != 'Active') {
            session()->flash('failedMsg', 'Your Account is not Active. Please activate your account to withdraw money');

            return redirect()->back();
        }

        // Monthly Rate;
        $rate = 5;

        $staking = new StakingDetail;
        $staking->memberid = $memberid;
        $staking->invest_date = date('Y-m-d', strtotime('+30 days'));
        $staking->invest_amount = $amount;
        $staking->installments = 0;
        $staking->total_installments = 40;
        $staking->package = $amount;
        $staking->rate = $rate;
        $staking->status = 'Active';
        $staking->save();

        $member->package = $amount;
        $member->self_biz += $amount;
        $wallet = $member->p2p_wallet;
        $member->p2p_wallet -= $amount;
        $member->team_biz += $amount;
        $member->daily_team_biz += $amount;
        $member->save();

        walletTransfer(
            $memberid,
            $amount,
            'credit',
            $wallet,
            'Staking Created',
            'Staking amount deducted from wallet'
        );

        levelIncome($sponsorid, $memberid, $name, $amount, 'Stacking Activation');
        team_biz_update($sponsorid, $amount);
        session()->flash('successMsg', 'Staking has been created successfully.');

        return redirect()->back();
    }

    public function StakingDetails()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['investment_data'] = StakingDetail::where('memberid', $memberid)->get();

        return view('member.investment.investment-details', $result);
    }
}
