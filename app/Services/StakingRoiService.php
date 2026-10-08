<?php

namespace App\Services;

use App\Models\MemberDetail;
use App\Models\PackageDetail;
use App\Models\PackagePlan;
use App\Models\RoiLevelIncome;
use App\Models\StakingDetail;
use App\Models\StakingIncome;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StakingRoiService
{
    /**
     * Process daily ROI for all active staking packages.
     *
     * @param  string|null  $targetDate  Date for which ROI is being calculated (defaults to today: Y-m-d)
     * @return array Summary of processed, credited, deactivated, skipped records and total payout
     */
    public function processDailyRoi(?string $targetDate = null): array
    {
        $dateStr = $targetDate ?: Carbon::today()->toDateString();

        $stats = [
            'date' => $dateStr,
            'processed' => 0,
            'credited' => 0,
            'deactivated' => 0,
            'skipped' => 0,
            'total_amount' => 0.00,
        ];

        // 1. Fetch only active staking packages
        $stakings = StakingDetail::where('status', 'Active')->get();

        foreach ($stakings as $staking) {
            $stats['processed']++;

            // 2. Same-day idempotency check: prevent duplicate credit on repeat scheduler runs
            $alreadyCreditedToday = StakingIncome::where('staking_id', $staking->id)
                ->whereDate('date', $dateStr)
                ->exists();

            if ($alreadyCreditedToday) {
                $stats['skipped']++;

                continue;
            }

            if ($staking->last_roi_at && Carbon::parse($staking->last_roi_at)->isSameDay($dateStr)) {
                $stats['skipped']++;

                continue;
            }

            // 3. Dynamic rate and capping from active PackagePlan (admin configured)
            $rate = $staking->getDailyRate();
            $cappingPercent = $staking->getCappingPercent();
            $maxAmount = $staking->getMaxRoiAmount();
            $totalEarned = $staking->getTotalEarned();

            // 4. Check remaining eligible maximum ROI and duration installments
            $remainingCap = max(0.00, round($maxAmount - $totalEarned, 2));
            $isInstallmentsComplete = ($staking->total_installments > 0 && (int) $staking->installments >= (int) $staking->total_installments);

            if ($remainingCap <= 0.00 || $isInstallmentsComplete) {
                // Maximum cap or duration has already been reached — deactivate package automatically
                $staking->status = 'Deactive';
                $staking->deactivated_at = $staking->deactivated_at ?? now();
                $staking->total_earned = $totalEarned;
                $staking->save();

                if ($staking->txnid) {
                    PackageDetail::where('txnid', $staking->txnid)
                        ->where('status', 'Active')
                        ->update(['status' => 'Expired']);
                }

                $stats['deactivated']++;

                continue;
            }

            // 5. Calculate daily ROI amount
            $dailyRoi = round((float) $staking->invest_amount * ($rate / 100), 2);

            // 6. Partial final ROI protection: credit only remaining eligible cap
            $eligibleRoi = min($dailyRoi, $remainingCap);

            if ($eligibleRoi <= 0.00) {
                $staking->status = 'Deactive';
                $staking->deactivated_at = $staking->deactivated_at ?? now();
                $staking->save();
                $stats['deactivated']++;

                continue;
            }

            // 7. Atomic distribution with row lock and database transaction
            DB::transaction(function () use (
                $staking,
                $dateStr,
                $rate,
                $cappingPercent,
                $maxAmount,
                $totalEarned,
                $eligibleRoi,
                &$stats
            ) {
                $member = MemberDetail::where('memberid', $staking->memberid)
                    ->lockForUpdate()
                    ->first();

                if (! $member) {
                    return;
                }

                // Check again inside locked transaction to prevent concurrent race condition
                $duplicateInTx = StakingIncome::where('staking_id', $staking->id)
                    ->whereDate('date', $dateStr)
                    ->exists();

                if ($duplicateInTx) {
                    $stats['skipped']++;

                    return;
                }

                $installmentNumber = (int) $staking->installments + 1;

                // A. Insert StakingIncome ledger record
                $income = new StakingIncome;
                $income->staking_id = $staking->id;
                $income->date = $dateStr;
                $income->memberid = $staking->memberid;
                $income->total_investment = $staking->invest_amount;
                $income->rate = $rate;
                $income->amount = $eligibleRoi;
                $income->installment = $installmentNumber;
                $income->status = 'Paid';
                $income->save();

                // B. Credit Member Main Wallet
                $oldWallet = (float) $member->wallet;
                $member->wallet = $oldWallet + $eligibleRoi;
                $member->save();

                // C. Record Wallet Ledger Entry
                walletTransfer(
                    $staking->memberid,
                    $eligibleRoi,
                    'debit',
                    $oldWallet,
                    'Staking Income',
                    "Daily Staking ROI installment #{$installmentNumber} credited."
                );

                // D. Update StakingDetail cumulative tracking
                $newTotalEarned = round($totalEarned + $eligibleRoi, 2);
                $staking->installments = $installmentNumber;
                $staking->total_earned = $newTotalEarned;
                $staking->rate = $rate;
                $staking->capping_percent = $cappingPercent;
                $staking->max_amount = $maxAmount;
                $staking->last_roi_at = Carbon::parse($dateStr.' '.now()->format('H:i:s'));
                $staking->invest_date = $dateStr;

                // E. Automatic Package Deactivation when cap reached
                $isCapReached = ($newTotalEarned >= $maxAmount);
                $isInstallmentsComplete = ($staking->total_installments > 0 && $installmentNumber >= $staking->total_installments);

                if ($isCapReached || $isInstallmentsComplete) {
                    $staking->status = 'Deactive';
                    $staking->deactivated_at = Carbon::parse($dateStr.' '.now()->format('H:i:s'));
                    $stats['deactivated']++;
                }

                $staking->save();

                // F. Keep matching PackageDetail synchronized
                if ($staking->txnid) {
                    $pkg = PackageDetail::where('txnid', $staking->txnid)->first();
                    if ($pkg) {
                        $pkg->total_earning = round(((float) $pkg->total_earning) + $eligibleRoi, 2);
                        if ($isCapReached || $pkg->total_earning >= $pkg->max_earning) {
                            $pkg->status = 'Expired';
                        }
                        $pkg->save();
                    }
                }

                $stats['credited']++;
                $stats['total_amount'] += $eligibleRoi;

                // G. Team Trading Profit: L1–L10: 5%, 5%, 4%, 4%, 3%, 3%, 2%, 2%, 1%, 1% on daily profit (min 4-decimal precision)
                $this->distributeTeamTradingProfit($staking->memberid, $member->name ?? $staking->memberid, $eligibleRoi, $dateStr);
            });
        }

        return $stats;
    }

    /**
     * Distribute Team Trading Profit to uplines (L1–L10: 5%, 5%, 4%, 4%, 3%, 3%, 2%, 2%, 1%, 1%).
     * Daily trading profit par calculate hoga, minimum 4-decimal precision ke saath.
     *
     * @return array<int, array>
     */
    public function distributeTeamTradingProfit(string $memberId, string $memberName, float $dailyRoi, string $dateStr): array
    {
        $levelRates = [
            1 => 5.00,
            2 => 5.00,
            3 => 4.00,
            4 => 4.00,
            5 => 3.00,
            6 => 3.00,
            7 => 2.00,
            8 => 2.00,
            9 => 1.00,
            10 => 1.00,
        ];

        $distributed = [];
        $currentMember = MemberDetail::where('memberid', $memberId)->first();
        if (! $currentMember || empty($currentMember->sponsorid) || $currentMember->sponsorid === 'Root') {
            return $distributed;
        }

        $sponsorId = $currentMember->sponsorid;

        for ($level = 1; $level <= 10; $level++) {
            if (empty($sponsorId) || $sponsorId === 'Root') {
                break;
            }

            $sponsor = MemberDetail::where('memberid', $sponsorId)->first();
            if (! $sponsor) {
                break;
            }

            $rate = $levelRates[$level] ?? 0.00;
            // Minimum 4-decimal precision per specification
            $amount = round($dailyRoi * ($rate / 100), 4);

            if ($amount > 0 && $sponsor->status === 'Active') {
                // Prevent duplicate record on same date for same sponsor, level and downline
                $alreadyPaid = RoiLevelIncome::where([
                    ['memberid', $sponsor->memberid],
                    ['level', $level],
                    ['level_id', $memberId],
                ])->whereDate('created_at', $dateStr)->exists();

                if (! $alreadyPaid) {
                    $roiLevel = new RoiLevelIncome;
                    $roiLevel->memberid = $sponsor->memberid;
                    $roiLevel->level = $level;
                    $roiLevel->level_id = $memberId;
                    $roiLevel->name = $memberName;
                    $roiLevel->type = 'Team Trading Profit';
                    $roiLevel->staking_income = $dailyRoi;
                    $roiLevel->rate = $rate;
                    $roiLevel->amount = $amount;
                    $roiLevel->status = 'Paid';
                    $roiLevel->created_at = Carbon::parse($dateStr.' '.now()->format('H:i:s'));
                    $roiLevel->save();

                    $oldWallet = (float) $sponsor->wallet;
                    $sponsor->wallet = $oldWallet + $amount;
                    $sponsor->save();

                    walletTransfer(
                        $sponsor->memberid,
                        $amount,
                        'debit',
                        $oldWallet,
                        'Team Trading Profit',
                        "Level {$level} Team Trading Profit from {$memberId} ($".number_format($dailyRoi, 4).' daily profit)'
                    );

                    $distributed[] = [
                        'level' => $level,
                        'sponsorid' => $sponsor->memberid,
                        'rate' => $rate,
                        'amount' => $amount,
                    ];
                }
            }

            $sponsorId = $sponsor->sponsorid;
        }

        return $distributed;
    }
}
