<?php

namespace App\Console\Commands;

use App\Models\MemberDetail;
use App\Models\PartnershipDetail;
use App\Models\PartnershipIncome;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:partnership-income-dis')]
#[Description('Command description')]
class PartnershipIncomeDis extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
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
}
