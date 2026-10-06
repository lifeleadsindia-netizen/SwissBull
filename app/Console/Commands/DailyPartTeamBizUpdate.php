<?php

namespace App\Console\Commands;

use App\Models\MemberDetail;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:daily-part-team-biz-update')]
#[Description('Command description')]
class DailyPartTeamBizUpdate extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $update = MemberDetail::where('status', '!=', 'Temp')->update(['part_daily_team_biz' => 0, 'daily_team_biz' => 0]);
    }
}
