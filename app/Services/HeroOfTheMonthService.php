<?php

namespace App\Services;

use App\Models\HeroOfTheMonthReward;
use App\Models\MemberDetail;
use App\Models\PackageDistribution;
use App\Models\StakingDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HeroOfTheMonthService
{
    /**
     * Process Hero of the Month distribution for a given month.
     *
     * Rules:
     * - All investments of the month: 2% pool
     * - Distributed on monthly 1st day (defaults to previous month if null)
     * - Highest Level-1/direct-referral investment volume winner
     * - Equal distribution in case of a tie
     *
     * @param  string|null  $targetMonth  Target month in YYYY-MM format (e.g. "2026-09")
     * @return array Result summary
     */
    public function processMonthlyDistribution(?string $targetMonth = null): array
    {
        $monthStr = $targetMonth ?: Carbon::now()->subMonth()->format('Y-m');

        // Prevent duplicate distribution for the same month (idempotency)
        $alreadyDistributed = HeroOfTheMonthReward::where('month', $monthStr)->exists();
        if ($alreadyDistributed) {
            return [
                'status' => 'skipped',
                'month' => $monthStr,
                'message' => "Hero of the Month already distributed for {$monthStr}.",
                'winners' => HeroOfTheMonthReward::where('month', $monthStr)->get()->toArray(),
            ];
        }

        $startDate = Carbon::createFromFormat('Y-m', $monthStr)->startOfMonth()->toDateTimeString();
        $endDate = Carbon::createFromFormat('Y-m', $monthStr)->endOfMonth()->toDateTimeString();

        // 1. Calculate total investment volume across all members during the target month
        $totalInvestment = (float) StakingDetail::whereBetween('created_at', [$startDate, $endDate])->sum('invest_amount');

        // 2. Pool percentage (from PackageDistribution default 2.0%)
        $distribution = PackageDistribution::first();
        $poolPercent = $distribution && (float) $distribution->hero_of_the_month > 0
            ? (float) $distribution->hero_of_the_month
            : 2.00;

        $totalPool = round($totalInvestment * ($poolPercent / 100), 2);

        if ($totalInvestment <= 0 || $totalPool <= 0) {
            return [
                'status' => 'no_investments',
                'month' => $monthStr,
                'total_investment' => $totalInvestment,
                'total_pool' => 0.00,
                'message' => "No investment volume found for {$monthStr}.",
                'winners' => [],
            ];
        }

        // 3. Find top Level-1 direct referral business for each sponsor during the target month
        $sponsorBusinesses = StakingDetail::whereBetween('staking_details.created_at', [$startDate, $endDate])
            ->join('member_details', 'staking_details.memberid', '=', 'member_details.memberid')
            ->whereNotNull('member_details.sponsorid')
            ->where('member_details.sponsorid', '!=', 'Root')
            ->groupBy('member_details.sponsorid')
            ->selectRaw('member_details.sponsorid as sponsorid, SUM(staking_details.invest_amount) as direct_business')
            ->orderByDesc('direct_business')
            ->get();

        if ($sponsorBusinesses->isEmpty()) {
            return [
                'status' => 'no_directs',
                'month' => $monthStr,
                'total_investment' => $totalInvestment,
                'total_pool' => $totalPool,
                'message' => "No direct referral investments found for {$monthStr}.",
                'winners' => [],
            ];
        }

        $maxDirectBusiness = (float) $sponsorBusinesses->first()->direct_business;

        if ($maxDirectBusiness <= 0) {
            return [
                'status' => 'zero_business',
                'month' => $monthStr,
                'total_investment' => $totalInvestment,
                'total_pool' => $totalPool,
                'message' => "Highest direct referral business was 0 for {$monthStr}.",
                'winners' => [],
            ];
        }

        // 4. Identify all winners (handles ties with equal distribution)
        $winningSponsors = $sponsorBusinesses->filter(function ($item) use ($maxDirectBusiness) {
            return (float) $item->direct_business == $maxDirectBusiness;
        })->values();

        $winnerCount = $winningSponsors->count();
        $prizePerWinner = round($totalPool / $winnerCount, 2);

        $results = [];

        DB::transaction(function () use ($winningSponsors, $monthStr, $maxDirectBusiness, $totalPool, $poolPercent, $winnerCount, $prizePerWinner, &$results) {
            foreach ($winningSponsors as $winnerData) {
                $member = MemberDetail::where('memberid', $winnerData->sponsorid)
                    ->lockForUpdate()
                    ->first();

                if (! $member) {
                    continue;
                }

                // A. Credit member wallet
                $oldWallet = (float) $member->wallet;
                $member->wallet = $oldWallet + $prizePerWinner;
                $member->save();

                // B. Create ledger record
                walletTransfer(
                    $member->memberid,
                    $prizePerWinner,
                    'debit',
                    $oldWallet,
                    'Hero of the Month',
                    "Hero of the Month reward for {$monthStr} (Pool: \${$totalPool}, {$winnerCount} winners)."
                );

                // C. Create HeroOfTheMonthReward record
                $reward = HeroOfTheMonthReward::create([
                    'month' => $monthStr,
                    'memberid' => $member->memberid,
                    'direct_business' => $maxDirectBusiness,
                    'total_pool' => $totalPool,
                    'pool_percentage' => $poolPercent,
                    'total_winners' => $winnerCount,
                    'prize_amount' => $prizePerWinner,
                    'status' => 'Paid',
                ]);

                $results[] = [
                    'memberid' => $member->memberid,
                    'direct_business' => $maxDirectBusiness,
                    'prize_amount' => $prizePerWinner,
                ];
            }
        });

        return [
            'status' => 'success',
            'month' => $monthStr,
            'total_investment' => $totalInvestment,
            'pool_percentage' => $poolPercent,
            'total_pool' => $totalPool,
            'winner_count' => $winnerCount,
            'prize_per_winner' => $prizePerWinner,
            'winners' => $results,
        ];
    }
}
