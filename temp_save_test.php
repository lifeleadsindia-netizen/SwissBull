<?php

use App\Models\MemberDetail;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$mem = MemberDetail::where('memberid', 'MW1234567')->first();
echo 'Before: '.$mem->trading_wallet."\n";
$mem->trading_wallet = max(0, $mem->trading_wallet - 70);
$mem->save();
echo 'After: '.$mem->trading_wallet."\n";

$mem2 = MemberDetail::where('memberid', 'MW1234567')->first();
echo 'DB: '.$mem2->trading_wallet."\n";
