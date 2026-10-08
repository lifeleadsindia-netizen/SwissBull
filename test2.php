<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$s = App\Models\StakingDetail::where('memberid', 'MW1234567')->get();
foreach($s as $x) {
    echo 'ID: ' . $x->id . ', amount: ' . $x->invest_amount . ', invest_date: ' . $x->getRawOriginal('invest_date') . ', created_at: ' . $x->getRawOriginal('created_at') . "\n";
}
