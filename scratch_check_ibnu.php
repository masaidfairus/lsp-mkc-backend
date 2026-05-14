<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('name', 'like', '%Ibnu%')->first();
if ($user) {
    $reg = App\Models\AssessmentRegistration::where('user_id', $user->id)->latest()->first();
    if ($reg) {
        echo "Registration ID: " . $reg->id . "\n";
        $sigs = App\Models\SignatureRequest::where('registration_id', $reg->id)
            ->whereIn('document_label', ['AK.02', 'FR.AK.02'])
            ->get(['id', 'role', 'status']);
        echo json_encode($sigs, JSON_PRETTY_PRINT);
    } else {
        echo "No registration found for user Ibnu";
    }
} else {
    echo "User Ibnu not found";
}
