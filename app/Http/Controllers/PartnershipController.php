<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PartnershipDetail;
use Illuminate\Http\Request;

class PartnershipController extends Controller
{
    public function partCreateInvestment()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();

        return view('member.partnership.create-investment', $result);
    }

    public function partCreateInvest(Request $request)
    {
        $request->validate([
            'memberid' => 'required',
            'amount' => 'required|numeric',
        ]);

        $memberid = session('MEMBER_ID');
        $amount = $request->amount;

        $member = MemberDetail::where('memberid', $memberid)->first();
        $sponsorid = $member->sponsorid;
        if (! $member) {
            session()->flash('failedMsg', 'Invalid member.');

            return redirect()->back();
        }

        if ($amount < 100) {
            session()->flash('failedMsg', 'Amount must be at least $100.');

            return redirect()->back();
        }

        // if ($amount % 10 != 0) {
        //     session()->flash('failedMsg', 'Your value is not multiple of $10');
        //     return redirect()->back();
        // }

        if ($member->p2p_wallet < $amount) {
            session()->flash('failedMsg', 'Insufficient wallet balance.');

            return redirect()->back();
        }

        // if (!Hash::check($txn_password, $member->txn_password)) {
        //     session()->flash('failedMsg', 'Security Pin is incorrect.');
        //     return redirect()->back();
        // }

        if ($amount == 100) {
            $rate = 2;
            $capping = 3;
            $rank = 'Bronze';
        } elseif ($amount == 200) {
            $rate = 4;
            $capping = 5;
            $rank = 'Silver';
        } elseif ($amount == 500) {
            $rate = 6;
            $capping = 7;
            $rank = 'Gold';
        } elseif ($amount == 1000) {
            $rate = 8;
            $capping = 8;
            $rank = 'Platinum';
        } elseif ($amount == 5000) {
            $rate = 10;
            $capping = 10;
            $rank = 'Diamond';
        } else {
            session()->flash('failedMsg', 'Invalid amount.');

            return redirect()->back();
        }

        $existingEntry = PartnershipDetail::where('memberid', $memberid)
            ->where('status', 'Active')
            ->first();

        if ($existingEntry) {
            $existingEntry->status = 'Deactive';
            $existingEntry->save();
        }

        $staking = new PartnershipDetail;
        $staking->memberid = $memberid;
        $staking->invest_date = now();
        $staking->invest_amount = $amount;
        $staking->installments = 0;
        $staking->capping_x = $capping;
        $staking->capping = $capping * $amount;
        $staking->rank = $rank;
        $staking->rate = $rate;
        $staking->status = 'Active';
        $staking->save();

        $member->partnership_package = $amount;
        $member->partnership_rank = $rank;
        $member->partnership_self_biz += $amount;
        $member->partnership_team_biz += $amount;
        $member->part_daily_team_biz += $amount;
        $wallet = $member->p2p_wallet;
        $member->p2p_wallet -= $amount;
        $member->save();

        walletTransfer(
            $memberid,
            $amount,
            'credit',
            $wallet,
            'Partnership Investment Created',
            'Partnership Investment amount deducted from fund wallet'
        );

        part_team_biz_update($sponsorid, $amount);
        session()->flash('successMsg', 'Partnership Investment has been created successfully.');

        return redirect()->back();
    }

    public function investmentDetails()
    {
        $memberid = session('MEMBER_ID');
        $result['data'] = MemberDetail::where('memberid', $memberid)->first();
        $result['investment_data'] = PartnershipDetail::where('memberid', $memberid)->get();

        return view('member.partnership.investment-details', $result);
    }
}
