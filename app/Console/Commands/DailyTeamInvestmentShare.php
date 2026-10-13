<?php

namespace App\Console\Commands;

use App\Models\DailyTeamShareIncome;
use App\Models\MemberDetail;
use App\Models\StakingDetail;
use App\Models\UplineMember;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:daily-team-investment-share')]
#[Description('Command description')]
class DailyTeamInvestmentShare extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $members = MemberDetail::where('status', 'Active')
            ->where('downline', '>=', 4)
            ->get();

        foreach ($members as $value) {
            $memberid = $value->memberid;
            $downline = (int) $value->downline;
            // $dailyTeamBiz = (float) $value->daily_team_biz;

            if ($memberid != 'Root') {
                $limit = 11;
                for ($i = 1; $i < $limit; $i++) {
                    $level = $i;
                    $levelData = dailyTeamLevelRate($level);
                    $requiredDirects = (int) ($levelData['direct'] ?? 4);
                    $rate = (int) ($levelData['rate'] ?? 1);

                    if ($downline < $requiredDirects) {
                        break;
                    }

                    $downlineMemberIds = UplineMember::where('upline_'.$level, $memberid)->pluck('memberid')->toArray();
                    if (count($downlineMemberIds) == 0) {
                        break;
                    }
                    $dailyTeamBiz = StakingDetail::whereIn('memberid', $downlineMemberIds)->where('invest_date', date('Y-m-d', strtotime('-1 day')))->sum('trading_wallet_amount');

                    $amount = $dailyTeamBiz * $rate / 100;

                    if ($amount > 0) {

                        $new = new DailyTeamShareIncome;
                        $new->memberid = $memberid;
                        $new->level = $level;
                        $new->total_members = count($downlineMemberIds);
                        $new->rate = $rate;
                        $new->downline = $downline;
                        $new->daily_team_biz = $dailyTeamBiz;
                        $new->amount = $amount;
                        $new->type = 'Daily Team Investment Share';
                        $new->status = 'Paid';
                        $new->save();

                        $wallet = $value->wallet;
                        $value->wallet += $amount;
                        $value->save();

                        walletTransfer(
                            $memberid,
                            $amount,
                            'debit',
                            $wallet,
                            'Daily Team Investment Share Income',
                            'Level '.$level.' Daily Team Investment Share Income Amount Added into wallet.'
                        );

                    } else {
                        break;
                    }
                }
            }
        }
    }
}
