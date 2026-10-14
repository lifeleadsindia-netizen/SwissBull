<?php

use App\Models\MemberDetail;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$mem = MemberDetail::where('memberid', 'MW1234567')->first();
if ($mem) {
    // The user had a pending $70 withdrawal that didn't deduct from trading_wallet because of the accessor.
    // Let's manually deduct it.
    $mem->trading_wallet = max(0, $mem->trading_wallet - 70.00);
    $mem->save();
    echo 'Deducted $70. New balance: '.$mem->trading_wallet."\n";
}
