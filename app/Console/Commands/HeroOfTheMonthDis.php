<?php

namespace App\Console\Commands;

use App\Services\HeroOfTheMonthService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:hero-of-the-month-dis {month? : Optional target month in YYYY-MM format}')]
#[Description('Distribute 2% Hero of the Month pool to top Level-1 direct referral volume achievers')]
class HeroOfTheMonthDis extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(HeroOfTheMonthService $service): int
    {
        $targetMonth = $this->argument('month');
        $this->info('Starting Hero of the Month distribution'.($targetMonth ? " for month: {$targetMonth}" : ' for previous month'));

        $result = $service->processMonthlyDistribution($targetMonth);

        if ($result['status'] === 'success') {
            $this->info("Successfully distributed \${$result['total_pool']} to {$result['winner_count']} winner(s) (\${$result['prize_per_winner']} each) for {$result['month']}.");
        } else {
            $this->warn("Distribution status: {$result['status']}. Message: {$result['message']}");
        }

        return Command::SUCCESS;
    }
}
