<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PackageDetail;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    public function activation()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();

        return view('member.account.account-activation')->with($result);
    }

    public function accountActivation(Request $request)
    {
        $request->validate([
            'memberid' => 'required',
            'amount' => 'required',
        ]);
        $memberid = session('MEMBER_ID');
        $amount = 30;

        $member = MemberDetail::where('memberid', $memberid)->first();
        $name = $member->name;
        $sponsorid = $member->sponsorid;
        if (! $member) {
            session()->flash('failedMsg', 'Member not found.');

            return redirect()->back();
        }

        if ($member->p2p_wallet < $amount) {
            session()->flash('failedMsg', 'Insufficient wallet balance.');

            return redirect()->back();
        }

        if ($member->status == 'Temp') {
            $member->status = 'Active';
            $member->activated_at = now();
            updateDownline($sponsorid);
            updateUpline($sponsorid, $memberid);
            team_update($sponsorid);
        }

        $new = new PackageDetail;
        $new->memberid = $memberid;
        $new->package_type = 'Account Activation';
        $new->package_value = $amount;
        $new->payment_mode = 'Fund Wallet';
        $new->txnid = 'A/'.date('YmdHis');
        $new->status = 'Accepted';
        $new->save();

        $member->activation_amount = $amount;
        $wallet = $member->p2p_wallet;
        $member->p2p_wallet -= $amount;
        $member->save();

        levelIncome($sponsorid, $memberid, $name, $amount, 'Account Activation');
        walletTransfer($memberid, $amount, 'credit', $wallet, 'Activate account', '$ '.$amount.' deducted for account activation.');

        session()->flash('successMsg', 'Account activated successfully.');

        return redirect()->back();
    }

    public function activationDetail()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['package_data'] = PackageDetail::where('memberid', $memberid)->where('package_type', 'Account Activation')->get();

        return view('member.account.packages-details')->with($result);
    }
}
