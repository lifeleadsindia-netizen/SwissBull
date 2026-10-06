<?php

namespace App\Console\Commands;

use App\Models\MemberDetail;
use App\Models\StakingDetail;
use App\Models\StakingIncome;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:daily-income-dis')]
#[Description('Command description')]
class DailyIncomeDis extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
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
            if ($installment >= $total_installments) {
                $value->status = 'Deactive';
                $value->save();
            }
            $stak_amount = $value->invest_amount / 100 * $rate;

            $memUpdate = MemberDetail::where('memberid', $memberid)->first();
            $capping = stakingCapping($memberid, $staking_id);
            $achieved = StakingIncome::where([['status', 'Paid'], ['memberid', $memberid]])->sum('amount');
            $balance = $capping - $achieved;
            if ($balance <= $stak_amount) {
                $amount = $balance;
            } else {
                $amount = $stak_amount;
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
                $update->invest_date = date('Y-m-d', strtotime('+30 days'));
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
}
