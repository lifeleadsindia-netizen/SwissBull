<?php

namespace App\Console\Commands;

use App\Services\StakingRoiService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:daily-income-dis {date? : Optional calculation date in Y-m-d format}')]
#[Description('Calculate and distribute daily staking ROI with dynamic rate and capping')]
class DailyIncomeDis extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(StakingRoiService $service): int
    {
        $targetDate = $this->argument('date');
        $dateStr = $targetDate ?: today()->toDateString();
        $this->info("Starting daily staking ROI distribution for date: {$dateStr}");

        $result = $service->processDailyRoi($targetDate);

        $this->info("Completed: Processed {$result['processed']}, Credited {$result['credited']}, Deactivated {$result['deactivated']}, Skipped {$result['skipped']}, Total Paid: \${$result['total_amount']}");

        return Command::SUCCESS;
    }
}
