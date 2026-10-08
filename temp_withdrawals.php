<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$reqs = \App\Models\WithdrawalRequest::orderBy('id', 'desc')->take(5)->get();
foreach($reqs as $r) {
    echo $r->memberid . ': ' . $r->gross_amount . ' (' . $r->type . ')' . "\n";
}
