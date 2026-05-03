<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\AssessmentRegistration;
use App\Models\User;

$user = User::where('name', 'Ibnu')->first();
if (!$user) {
    echo "User Ibnu not found\n";
    exit;
}

echo "User ID: " . $user->id . "\n";
$regs = AssessmentRegistration::where('user_id', $user->id)->get();
echo "Registration count: " . $regs->count() . "\n";

foreach ($regs as $reg) {
    echo "ID: " . $reg->id . ", Scheme ID: " . ($reg->scheme_id ?? 'NULL') . ", Status: " . $reg->status . "\n";
    if ($reg->aplData) {
        echo "  - APL Data found\n";
    } else {
        echo "  - NO APL Data\n";
    }
}
