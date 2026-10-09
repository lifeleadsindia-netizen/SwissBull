<?php

namespace App\Http\Controllers;

use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PackageDistribution;
use App\Models\PartnershipDetail;
use App\Models\PartnershipIncome;
use App\Models\StakingDetail;
use App\Models\StakingIncome;

class TempController extends Controller
{
    public function dailyIncomeDis()
    {
        // Monthly Income(Run Daily)
        $stakings = StakingDetail::where('status', 'Active')->get();
        // $stakings = StakingDetail::where([['status', 'Active'], ['invest_date', date('Y-m-d H:i:s')]])->get();
        foreach ($stakings as $value) {
            $memberid = $value->memberid;
            $staking_id = $value->id;
            $total_installments = $value->total_installments;
            $installment = $value->installments + 1;
            $rate = $value->rate;
            // if ($installment >= $total_installments) {
            //     $value->status = 'Deactive';
            //     $value->save();
            // }
            $stak_amount = ($value->invest_amount / 100 * $rate) / 30;
            if (rand(0, 1) == 1) {
                $stak_amount += $stak_amount / 100 * rand(1, 10);
            } else {
                $stak_amount -= $stak_amount / 100 * rand(1, 10);
            }

            $memUpdate = MemberDetail::where('memberid', $memberid)->first();
            $capping = PackageDistribution::find(1);
            $cap = $capping->capping;
            if ($cap == 0) {
                $amount = $stak_amount;
                $balance = 0;
            } else {
                $capping = $stak_amount / 100 * $capping;
                $achieved = StakingIncome::where([['status', 'Paid'], ['memberid', $staking_id]])->sum('amount');
                $balance = $capping - $achieved;
                if ($balance <= $stak_amount) {
                    $amount = $balance;
                } else {
                    $amount = $stak_amount;
                }
            }

            if ($amount > 0) {

                $qry = new StakingIncome;
                $qry->date = date('Y-m-d');
                $qry->memberid = $memberid;
                $qry->total_investment = $value->invest_amount;
                $qry->rate = $rate;
                $qry->amount = $amount;
                $qry->installment = $installment;
                $qry->status = 'Paid';
                $qry->save();

                $update = StakingDetail::where('id', $value->id)->first();
                $update->installments += 1;
                $update->invest_date = date('Y-m-d');
                if ($balance <= $stak_amount) {
                    $update->status = 'Deactive';
                }
                $update->save();

                $wallet = $memUpdate->wallet;
                $memUpdate->wallet += $amount;
                $memUpdate->save();

                walletTransfer($memberid, $amount, 'debit', $wallet, 'Staking Income', 'Staking Income amount has been transfered into wallet.');

                if ($installment == $total_installments) {
                    $value->status = 'Deactive';
                    $value->save();
                }
            } else {
                $value->status = 'Deactive';
                $value->save();
            }
        }
    }

    public function dailyPartTeamBizUpdate()
    {
        $update = MemberDetail::where('status', '!=', 'Temp')->update(['part_daily_team_biz' => 0, 'daily_team_biz' => 0]);
    }

    public function partnershipIncomeDis()
    {
        $stakings = PartnershipDetail::where('status', 'Active')->get();
        foreach ($stakings as $value) {
            $memberid = $value->memberid;
            $rank = $value->rank;
            $achieved = $value->achieved;
            $capping = $value->capping;
            $installment = $value->installments + 1;
            $rate = $value->rate;
            $invest_id = $value->id;

            // $achieved = PartnershipIncome::where([['status', 'Paid'], ['memberid', $memberid], ['invest_id', $invest_id]])->sum('amount');
            if ($achieved >= $capping) {
                $value->status = 'Deactive';
                $value->save();
            }

            $memUpdate = MemberDetail::where('memberid', $memberid)->first();
            $stack_dailyTeamBiz = $memUpdate->daily_team_biz;
            $part_dailyTeamBiz = $memUpdate->part_daily_team_biz;
            $dailyTeamBiz = $stack_dailyTeamBiz + $part_dailyTeamBiz;

            if ($dailyTeamBiz <= 0) {
                continue;
            }
            $_amount = $dailyTeamBiz / 100 * $rate;
            $balance = $capping - $achieved;
            if ($balance <= $_amount) {
                $amount = $balance;
            } else {
                $amount = $_amount;
            }

            if ($amount > 0) {

                $update = PartnershipDetail::where('id', $invest_id)->first();
                $update->installments += 1;
                $update->achieved = $achieved + $amount;
                if ($balance <= $_amount) {
                    $update->status = 'Deactive';
                }
                $update->save();

                $qry = new PartnershipIncome;
                $qry->date = date('Y-m-d');
                $qry->invest_id = $invest_id;
                $qry->memberid = $memberid;
                $qry->total_investment = $value->invest_amount;
                $qry->rate = $rate;
                $qry->rank = $rank;
                $qry->daily_team_biz = $dailyTeamBiz;
                $qry->achieved = $update->achieved;
                $qry->amount = $amount;
                $qry->installment = $installment;
                $qry->status = 'Paid';
                $qry->save();

                $wallet = $memUpdate->wallet;
                $memUpdate->wallet += $amount;
                $memUpdate->save();

                walletTransfer($memberid, $amount, 'debit', $wallet, 'Partnership Income', 'Partnership Income amount has been transfered into wallet.');
            } else {
                $value->status = 'Deactive';
                $value->save();
            }
        }
    }

    //  use for testing purpose only
    public function addTempMember($Sponsorid)
    {
        for ($i = 1; $i <= 50; $i++) {

            $memberid = userId();

            $var = new MemberDetail;
            $var->memberid = $memberid;
            $var->member_wallet = 'xyz';
            $var->sponsorid = $Sponsorid;
            $var->name = 'test'.$i;
            $var->email = 'test'.$i.'@gmail.com';
            $var->mobile = '0123456789';
            $var->phonecode = '+91';
            $var->country = 'India';
            $var->status = 'Temp';
            $var->p2p_wallet = 10000;
            $var->status = 'Active';
            $var->activated_at = now();
            $var->activation_amount = 30;
            $var->save();
            updateDownline($Sponsorid);
            updateUpline($Sponsorid, $memberid);
            team_update($Sponsorid);

            $new = new PackageDetail;
            $new->memberid = $memberid;
            $new->package_type = 'Account Activation';
            $new->package_value = 30;
            $new->payment_mode = 'Fund Wallet';
            $new->txnid = 'A/'.date('YmdHis');
            $new->status = 'Accepted';
            $new->save();

            $Sponsorid = $var->memberid;

            sleep(1);

        }
    }
}
