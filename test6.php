<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$columns = Illuminate\Support\Facades\Schema::getColumnListing('trading_wallet_settings');
print_r($columns);
