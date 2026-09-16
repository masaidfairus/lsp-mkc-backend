<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sig = \App\Models\UserSignature::find(11);
if ($sig) {
    echo "ID: " . $sig->id . "\n";
    echo "Name: " . $sig->name . "\n";
    echo "Shape Length: " . strlen($sig->signature_shape) . "\n";
    echo "Shape Preview: " . substr($sig->signature_shape, 0, 100) . "...\n";
} else {
    echo "Record not found\n";
}
