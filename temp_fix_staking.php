<?php

use App\Models\StakingDetail;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Revert the staking detail row for member MW1234567 to 35.00
$staking = StakingDetail::where('memberid', 'MW1234567')->where('id', 2)->first();
if ($staking) {
    $staking->trading_wallet_amount = 35.00;
    $staking->save();
    echo "Restored staking ID 2 trading_wallet_amount to 35.00\n";
}
