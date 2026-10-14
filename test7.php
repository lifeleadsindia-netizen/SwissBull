<?php

use App\Models\TradingWalletSetting;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$s = TradingWalletSetting::getActiveSetting();
echo 'Status: '.$s->status."\n";
