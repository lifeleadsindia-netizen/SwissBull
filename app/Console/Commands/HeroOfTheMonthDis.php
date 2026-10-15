<?php

namespace App\Console\Commands;

use App\Models\HeroOfTheMonthReward;
use App\Models\MemberDetail;
use App\Models\StakingDetail;
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
        // Step 1: Pichle month ke top direct business wale members nikalna
        $max = monthlyMaxBiz();

        // Agar koi eligible member nahi mila to yahin se wapas ho jaye
        if (empty($max['memberid'])) {
            return;
        }

        $totalMember = count($max['memberid']);
        if ($totalMember == 0) {
            return;
        }

        // Step 2: Pichle month (Last Month) ka Year aur Month nikalna
        $lastMonthYear = date('Y', strtotime('last month'));
        $lastMonthMonth = date('m', strtotime('last month'));

        // Step 3: StakingDetail table se pichle month ka total investment nikalna
        $totalInvestment = StakingDetail::whereYear('created_at', $lastMonthYear)
            ->whereMonth('created_at', $lastMonthMonth)
            ->sum('invest_amount');

        // Agar pichle month koi investment nahi hua to return kar jaye
        if ($totalInvestment <= 0) {
            return;
        }

        // Step 4: Total investment ka 2% nikalna (Pool Amount)
        $poolAmount = ($totalInvestment * 2) / 100;

        // Step 5: Har member ka barabar share (hissa) nikalna
        $share = round($poolAmount / $totalMember, 2);

        // Step 6: Sabhi winner members me reward distribute karna
        foreach ($max['memberid'] as $memberid) {
            $value = MemberDetail::where([['memberid', $memberid], ['status', 'Active']])->first();
            if (! $value) {
                continue;
            }

            $direct_biz = $value->direct_biz;

            // 1. HeroOfTheMonthReward table me record save karna
            $new = new HeroOfTheMonthReward;
            $new->month = date('Y-m');
            $new->memberid = $memberid;
            $new->direct_business = $direct_biz;
            $new->total_members = $totalMember;
            $new->amount = $share;
            $new->status = 'Paid';
            $new->save();

            // 2. Member ke wallet me share add karna
            $wallet = $value->wallet;
            $value->wallet += $share;
            $value->save();

            // 3. Wallet ledger transfer entry save karna
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
