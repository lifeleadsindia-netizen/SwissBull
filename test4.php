<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$x = App\Models\StakingDetail::find(1);
echo 'Locked: ' . ($x->isLocked() ? 'yes' : 'no') . ', Until: ' . $x->locked_until . "\n";
