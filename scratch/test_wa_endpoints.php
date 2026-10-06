<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Http;

function testEndpoints($number)
{
    $clean = preg_replace('/[^0-9]/', '', $number);
    if (! str_starts_with($clean, '91') && strlen($clean) == 10) {
        $clean = '91'.$clean;
    }

    echo "=== TESTING NUMBER: {$number} (Clean: {$clean}) ===\n";

    // Endpoint 1: api.whatsapp.com/send
    $url1 = "https://api.whatsapp.com/send/?phone={$clean}&text=hello";
    $res1 = Http::withoutVerifying()->timeout(5)->withHeaders([
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ])->get($url1);

    echo "URL 1: {$url1}\n";
    echo 'Status 1: '.$res1->status()."\n";
    $body1 = $res1->body();
    echo "Body 1 contains 'action=share': ".(str_contains($body1, 'action=share') ? 'YES' : 'NO')."\n";
    echo "Body 1 contains '_2z6r': ".(str_contains($body1, '_2z6r') ? 'YES' : 'NO')."\n";
    echo "Body 1 contains 'invalid': ".(str_contains(strtolower($body1), 'invalid') ? 'YES' : 'NO')."\n";

    // Endpoint 2: wa.me with mobile User-Agent (WhatsApp Android / iOS UA)
    $url2 = "https://wa.me/{$clean}";
    $res2 = Http::withoutVerifying()->timeout(5)->withHeaders([
        'User-Agent' => 'WhatsApp/2.23.20.76 A',
    ])->get($url2);
    echo "URL 2: {$url2} (Mobile UA)\n";
    echo 'Status 2: '.$res2->status()."\n";
    echo 'Body 2 length: '.strlen($res2->body())."\n";
    echo 'Body 2: '.substr($res2->body(), 0, 300)."\n";

    echo "--------------------------------------------------\n\n";
}

// Test numbers:
// 1. Fake number from image2: 6569858745
// 2. Real number from user screenshot: 9690915678 (or user's number)
// 3. Known real business/personal number: 919876543210
testEndpoints('6569858745');   // Unregistered fake number from image2
testEndpoints('919690915678'); // User screenshot number
testEndpoints('919876543210'); // Business number
testEndpoints('917012345678'); // Personal number
