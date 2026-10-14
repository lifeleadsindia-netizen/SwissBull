<?php

use App\Models\WithdrawalRequest;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$reqs = WithdrawalRequest::orderBy('id', 'desc')->take(5)->get();
foreach ($reqs as $r) {
    echo $r->memberid.': '.$r->gross_amount.' ('.$r->type.')'."\n";
}
