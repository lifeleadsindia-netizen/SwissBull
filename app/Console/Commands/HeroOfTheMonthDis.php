<?php

namespace App\Console\Commands;

use App\Models\HeroOfTheMonthReward;
use App\Models\MemberDetail;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:hero-of-the-month-dis')]
#[Description('Command description')]
class HeroOfTheMonthDis extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $max = monthlyMaxBiz();
        $totalMember = count($max['memberid']);

        foreach ($max['memberid'] as $memberid) {
            $value = MemberDetail::where('memberid', $memberid)->first();
            $direct_biz = $value->direct_biz;
            $share = $direct_biz / $totalMember;

            $new = new HeroOfTheMonthReward;
            $new->month = date('M', strtotime('-1 month'));
            $new->memberid = $memberid;
            $new->direct_business = $direct_biz;
            $new->total_members = $totalMember;
            $new->amount = $share;
            $new->save();

            $wallet = $value->wallet;
            $value->wallet += $share;
            $value->save();

            walletTransfer(
                $memberid,
                $share,
                'debit',
                $wallet,
                'Hero of the month Reward',
                'Hero of the month Reward Amount Added into wallet.'
            );
        }
    }
}
