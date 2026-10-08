<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$all = \App\Models\MemberDetail::where('trading_wallet', '>', 0)->orWhere('p2p_wallet', '>', 0)->get(['memberid', 'trading_wallet', 'p2p_wallet']);
foreach($all as $a) {
    echo $a->memberid . ': p2p=' . $a->p2p_wallet . ', trading=' . $a->trading_wallet . "\n";
}
