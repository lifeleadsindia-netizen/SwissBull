<?php

namespace App\Console\Commands;

use App\Models\MemberDetail;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:monthly-direct-biz-update')]
#[Description('Command description')]
class MonthlyDirectBizUpdate extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $update = MemberDetail::where('status', '!=', 'Temp')->update(['direct_biz' => 0]);
    }
}
