<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$mem = \App\Models\MemberDetail::where('memberid', 'MW1234567')->first();
if ($mem) {
    echo "p2p_wallet: " . $mem->p2p_wallet . "\n";
    echo "trading_wallet: " . $mem->trading_wallet . "\n";
    
    // Fix the balance: give back $35 to p2p and deduct $35 from trading (if it applies)
    // First, let's just see current balances.
}
