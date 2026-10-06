<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

function checkWaNumber($mobile)
{
    $clean = ltrim(preg_replace('/[^0-9]/', '', $mobile), '0');
    if (! str_starts_with($clean, '91') && strlen($clean) == 10) {
        $clean = '91'.$clean;
    }

    $url = "https://wa.me/{$clean}";
    try {
        $response = Http::withoutVerifying()->timeout(5)->withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ])->get($url);

        $body = $response->body();

        $ogTitle = '';
        $ogDesc = '';
        if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']+)["\']/i', $body, $m1)) {
            $ogTitle = $m1[1];
        }
        if (preg_match('/<meta[^>]+property=["\']og:description["\'][^>]+content=["\']([^"\']+)["\']/i', $body, $m2)) {
            $ogDesc = $m2[1];
        }

        echo "Mobile: {$mobile} (Clean: {$clean})\n";
        echo "OG Title: {$ogTitle}\n";
        echo 'OG Desc: '.substr($ogDesc, 0, 50)."\n";

        // Check if generic fallback
        $isRegistered = ! ($ogTitle === 'Share on WhatsApp' && str_contains($ogDesc, 'WhatsApp Messenger: More than 2 billion people'));
        echo 'Registered on WhatsApp: '.($isRegistered ? 'YES (Valid)' : 'NO (Not on WhatsApp)')."\n";
        echo "----------------------------------------\n";
    } catch (Exception $e) {
        echo 'Error: '.$e->getMessage()."\n";
    }
}

checkWaNumber('6569858745');  // Unregistered fake number from image2
checkWaNumber('15551234567'); // Fake US 555 number
checkWaNumber('919876543210'); // Valid registered number
checkWaNumber('919823487123'); // Test number
