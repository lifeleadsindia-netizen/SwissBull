<?php

namespace App\Console\Commands;

use App\Models\LevelIncome;
use App\Models\MemberDetail;
use App\Models\StakingIncome;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:team-trading-profit')]
#[Description('Command description')]
class TeamTradingProfit extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $incomes = StakingIncome::where([['status', 'Paid'], ['level_status', 0]])->whereDate('created_at', date('Y-m-d'))->limit(50)->get();
        foreach ($incomes as $value) {
            $memberid = $value['memberid'];
            $name = getName($memberid);
            $package = $value['amount'];
            $sponsorid = getSponsorid($memberid);
            $limit = 11;
            for ($i = 1; $i < $limit; $i++) {
                $level = $i;
                $rate = teamLevelRate($level);
                $amount = $package * $rate / 100;

                if ($sponsorid != 'Root') {
                    $var = MemberDetail::where('memberid', $sponsorid)->first();
                    $status = $var->status;
                    $name = $var->name;
                    $downline = $var->downline;

                    if ($amount > 0 && $status == 'Active') {

                        $insert = new LevelIncome;
                        $insert->memberid = $sponsorid;
                        $insert->level = $level;
                        $insert->level_id = $memberid;
                        $insert->amount = $amount;
                        $insert->package = $package;
                        $insert->name = $name;
                        $insert->rate = $rate;
                        $insert->downline = $downline;
                        $insert->type = 'Team Trading Profit';
                        $insert->status = 'Paid';
                        $insert->save();

                        $wallet = $var->wallet;
                        $var->wallet += $amount;
                        $var->save();

                        walletTransfer($sponsorid, $amount, 'debit', $wallet, 'Team Trading Profit Income', ''.$i.' Level Team Trading Profit Income Amount Added into wallet.');
                    }

                    $sponsorid = $var['sponsorid'];
                }
            }
            $update = StakingIncome::where('id', $value->id)->update(['level_status' => 1]);
        }
    }
}
