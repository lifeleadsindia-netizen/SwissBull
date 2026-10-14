<?php

use App\Models\MemberDetail;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$all = MemberDetail::get(['memberid', 'trading_wallet', 'p2p_wallet']);
foreach ($all as $a) {
    if ($a->trading_wallet > 0 || $a->p2p_wallet > 0) {
        echo $a->memberid.': p2p='.$a->p2p_wallet.', trading='.$a->trading_wallet."\n";
    }
}
echo "Done.\n";
