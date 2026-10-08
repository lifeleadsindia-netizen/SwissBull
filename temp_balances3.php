<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$all = \App\Models\MemberDetail::get(['memberid', 'trading_wallet', 'p2p_wallet']);
foreach($all as $a) {
    if ($a->trading_wallet > 0 || $a->p2p_wallet > 0) {
        echo $a->memberid . ': p2p=' . $a->p2p_wallet . ', trading=' . $a->trading_wallet . "\n";
    }
}
echo "Done.\n";
