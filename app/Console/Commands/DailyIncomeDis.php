<?php

namespace App\Console\Commands;

use App\Models\MemberDetail;
use App\Models\PackageDistribution;
use App\Models\StakingDetail;
use App\Models\StakingIncome;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:daily-income-dis')]
#[Description('Distribute daily trading profit / ROI to active staking packages')]
class DailyIncomeDis extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Monthly Income (Runs Daily Mon-Fri)
        if (date('D') != 'Sun' && date('D') != 'Mon') {
            $stakings = StakingDetail::where('status', 'Active')->get();
            foreach ($stakings as $value) {
                $memberid = $value->memberid;
                $staking_id = $value->id;
                $installment = $value->installments + 1;

                $packageDis = PackageDistribution::find(1);
                if ($value->package == 'Package1') {
                    $rate = $packageDis->package_1_rate;
                } elseif ($value->package == 'Package2') {
                    $rate = $packageDis->package_2_rate;
                } elseif ($value->package == 'Package3') {
                    $rate = $packageDis->package_3_rate;
                } else {
                    $rate = 0;
                }
                $tradingWalletAmount = $value->trading_wallet_amount;

                $stak_amount = ($tradingWalletAmount / 100 * $rate) / 30;
                if (rand(0, 1) == 1) {
                    $stak_amount += $stak_amount / 100 * rand(1, 10);
                } else {
                    $stak_amount -= $stak_amount / 100 * rand(1, 10);
                }
                $memUpdate = MemberDetail::where('memberid', $memberid)->first();
                if (! $memUpdate) {
                    continue;
                }

                $cap = $packageDis->capping;
                if ($cap == 0) {
                    $amount = $stak_amount;
                    $balance = 0;
                } else {
                    $capValue = ($tradingWalletAmount / 100) * $cap;
                    $achieved = StakingIncome::where([['status', 'Paid'], ['staking_id', $staking_id], ['memberid', $memberid]])->sum('amount');
                    $balance = $capValue - $achieved;
                    if ($balance <= $stak_amount) {
                        $amount = $balance;
                    } else {
                        $amount = $stak_amount;
                    }
                }

                if ($amount > 0.00) {

                    $qry = new StakingIncome;
                    $qry->date = date('Y-m-d');
                    $qry->staking_id = $value->id;
                    $qry->memberid = $memberid;
                    $qry->total_investment = $tradingWalletAmount;
                    $qry->rate = $rate;
                    $qry->amount = $amount;
                    $qry->installment = $installment;
                    $qry->status = 'Paid';
                    $qry->save();

                    $update = StakingDetail::where('id', $value->id)->first();
                    $update->installments += 1;
                    $update->invest_date = date('Y-m-d');
                    $isDeactivated = false;
                    if ($cap != 0 && $balance <= $stak_amount) {
                        $memUpdate->trading_wallet -= $tradingWalletAmount;
                        $update->status = 'Deactive';
                        $isDeactivated = true;
                    }
                    $update->save();

                    $wallet = $memUpdate->wallet;
                    $memUpdate->wallet += $amount;

                    // If this staking package is deactivated, check if any other active staking remains for this member
                    if ($isDeactivated) {
                        $hasOtherActive = StakingDetail::where('memberid', $memberid)
                            ->where('status', 'Active')
                            ->exists();

                        if (! $hasOtherActive) {
                            $memUpdate->status = 'Deactive';
                        }
                    }

                    $memUpdate->save();

                    walletTransfer($memberid, $amount, 'debit', $wallet, 'Trading Profit', 'Trading profit amount has been transfered into wallet.');

                } else {
                    $value->status = 'Deactive';
                    $value->save();

                    $hasOtherActive = StakingDetail::where('memberid', $memberid)
                        ->where('status', 'Active')
                        ->exists();

                    if (! $hasOtherActive && $memUpdate) {
                        $memUpdate->status = 'Deactive';
                        $memUpdate->save();
                    }
                }
            }
        }
    }
}
