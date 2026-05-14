<?php
use App\Models\Appeal;
use App\Models\AssessmentRegistration;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/plain');
echo "Checking Appeals...\n";
echo "Total Appeals in DB: " . Appeal::count() . "\n";
echo "IDs in appeals table: " . Appeal::pluck('registration_id')->implode(', ') . "\n";
echo "Checking Registration ID 1: " . (AssessmentRegistration::find(1) ? "Exists" : "Not Found") . "\n";
echo "Checking Appeal for Registration ID 1: " . (Appeal::where('registration_id', 1)->first() ? "Exists" : "Not Found") . "\n";
