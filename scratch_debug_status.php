<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$reg = App\Models\AssessmentRegistration::whereHas('candidate', function($q) {
    $q->where('name', 'like', '%Ibnu Althaf%');
})->first();

echo "Certificate: "; var_dump($reg->certificate);
echo "Final Result: "; var_dump($reg->final_result);
echo "Status: "; var_dump($reg->status);
