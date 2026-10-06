<?php

use App\Http\Controllers\WhatsappReferralController;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$controller = new WhatsappReferralController;
$reflector = new ReflectionClass($controller);
$method = $reflector->getMethod('isValidWhatsappMobile');
$method->setAccessible(true);

$testNumbers = [
    '9690915678' => true,  // Real number starting with 969091 (from user's screenshot)
    '9823487123' => true,  // Valid number
    '1234567890' => false, // Fake sequential
    '9999999999' => false, // Fake repeated
    '0000000000' => false, // Fake zero
    '1231231234' => false, // Fake repeated series
];

foreach ($testNumbers as $num => $expected) {
    $res = $method->invoke($controller, $num);
    echo "Number: {$num} | Valid: ".($res ? 'YES' : 'NO').' | Expected: '.($expected ? 'YES' : 'NO').' | Result: '.($res === $expected ? 'SUCCESS' : 'FAIL')."\n";
}
