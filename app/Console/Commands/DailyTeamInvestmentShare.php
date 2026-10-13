<?php

namespace App\Console\Commands;

use App\Models\DailyTeamShareIncome;
use App\Models\MemberDetail;
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
            ->where('daily_team_biz', '>', 0)
            ->get();

        foreach ($members as $value) {
            $memberid = $value->memberid;
            $downline = (int) $value->downline;
            // $dailyTeamBiz = (float) $value->daily_team_biz;

            if ($memberid != 'Root') {
                $limit = 11;
                for ($i = 1; $i < $limit; $i++) {
                    $level = $i;

                    $downlineMemberIds = UplineMember::where('upline_'.$level, $memberid)->pluck('memberid');
                    $levelData = MemberDetail::whereIn('memberid', $downlineMemberIds)->first();
                    if (! $levelData) {
                        continue;
                    }
                    $dailyTeamBiz = $levelData['daily_team_biz'];
                    $levelId = $levelData->memberid;
                    $levelName = getName($levelId);
                    $levelData = dailyTeamLevelRate($level);
                    $requiredDirects = (int) ($levelData['direct'] ?? 4);
                    $rate = (int) ($levelData['rate'] ?? 4);
                    $amount = $dailyTeamBiz * $rate / 100;

                    if ($amount > 0) {

                        $new = new DailyTeamShareIncome;
                        $new->memberid = $memberid;
                        $new->name = $levelName;
                        $new->level = $level;
                        $new->level_id = $levelId;
                        $new->rate = $rate;
                        $new->downline = $downline;
                        $new->daily_team_biz = $dailyTeamBiz;
                        $new->amount = $amount;
                        $new->type = 'Daily Team Investment Share';
                        if ($downline >= $requiredDirects) {
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
                            $new->status = 'Flushed';
                            $new->save();
                        }

                        $inc = MemberDetail::where('memberid', $memberid)->first();
                        $memberid = $inc['memberid'];
                    } else {
                        break;
                    }
                }
            }
        }
    }
}
