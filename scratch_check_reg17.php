<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$reg = App\Models\AssessmentRegistration::with('scheme')->find(17);
echo "Scheme: " . ($reg->scheme->name ?? 'N/A') . "\n";
echo "Candidate: " . ($reg->candidate->name ?? 'N/A') . "\n";
echo "Asesor: " . ($reg->assessor->name ?? 'N/A') . "\n";
