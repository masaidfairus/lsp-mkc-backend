<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Appeal;
use App\Models\AssessmentRegistration;

echo "--- APPEALS ---\n";
foreach (Appeal::all() as $a) {
    echo "Appeal ID: {$a->id} RegID: {$a->registration_id}\n";
}

echo "\n--- REGISTRATIONS (First 10) ---\n";
foreach (AssessmentRegistration::take(10)->get() as $r) {
    echo "Reg ID: {$r->id} Candidate: " . ($r->candidate?->name ?? 'N/A') . " APL01: " . ($r->status_apl01?->value ?? 'N/A') . " APL02: " . ($r->status_apl02?->value ?? 'N/A') . "\n";
}
