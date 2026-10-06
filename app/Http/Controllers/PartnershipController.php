<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PartnershipDetail;
use App\Models\TradingWalletSetting;
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
        if (! $member) {
            session()->flash('failedMsg', 'Invalid member.');

            return redirect()->back();
        }

        $sponsorid = $member->sponsorid;

        if ($amount < 1000) {
            session()->flash('failedMsg', 'Minimum partnership package is 1,000 USDT.');

            return redirect()->back();
        }

        if (! empty($member->partnership_package) && (float) $amount <= (float) $member->partnership_package) {
            session()->flash('failedMsg', 'Only higher package will be applicable.');

            return redirect()->back();
        }

        if (! $member->canPurchasePackage($lockError)) {
            session()->flash('failedMsg', $lockError);

            return redirect()->back();
        }

        if ($member->p2p_wallet < $amount) {
            session()->flash('failedMsg', 'Insufficient wallet balance.');

            return redirect()->back();
        }

        if ($amount == 1000) {
            $rate = 2;
            $capping_x = 2;
            $capping = 2000;
            $rank = 'Silver';
        } elseif ($amount == 5000) {
            $rate = 4;
            $capping_x = 4;
            $capping = 20000;
            $rank = 'Gold';
        } elseif ($amount == 10000) {
            $rate = 6;
            $capping_x = 6;
            $capping = 60000;
            $rank = 'Platinum';
        } elseif ($amount == 25000) {
            $rate = 8;
            $capping_x = 8;
            $capping = 200000;
            $rank = 'Diamond';
        } else {
            session()->flash('failedMsg', 'Invalid partnership package amount.');

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
        $staking->capping_x = $capping_x;
        $staking->capping = $capping;
        $staking->rank = $rank;
        $staking->rate = $rate;
        $staking->status = 'Active';
        $staking->save();

        $setting = TradingWalletSetting::getActiveSetting();

        $pkg = new PackageDetail;
        $pkg->memberid = $memberid;
        $pkg->package_type = 'Partnership Package';
        $pkg->package_value = $amount;
        $pkg->payment_mode = 'Fund Wallet';
        $pkg->txnid = 'P/'.date('YmdHis');
        $pkg->status = 'Accepted';
        $pkg->applyLock($setting->lock_days);
        $pkg->save();

        $member->partnership_package = $amount;
        $member->partnership_rank = $rank;
        $member->partnership_self_biz += $amount;
        $member->partnership_team_biz += $amount;
        $member->part_daily_team_biz += $amount;
        $wallet = $member->p2p_wallet;
        $member->p2p_wallet -= $amount;
        $member->applyTradingWalletLock($setting->lock_days, $setting->withdrawal_percent);
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
        session()->flash('successMsg', 'Partnership Package has been activated successfully.');

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
