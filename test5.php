<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$x = App\Models\StakingDetail::find(1);
echo 'activation_date: ' . $x->activation_date . "\n";
echo 'created_at: ' . $x->created_at . "\n";
echo 'activated_at: ' . $x->activated_at . "\n";
