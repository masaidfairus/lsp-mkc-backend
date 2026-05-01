<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AssessmentRegistration;

$reg = AssessmentRegistration::find(56);
if ($reg) {
    echo "Reg ID: " . $reg->id . "\n";
    echo "Assessor ID: " . $reg->assessor_id . "\n";
    echo "Assessor Name: " . ($reg->assessor?->name ?? 'N/A') . "\n";
} else {
    echo "Reg 56 not found\n";
}
