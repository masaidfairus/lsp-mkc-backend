<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('name', 'like', '%Ibnu%')->first();
if ($user) {
    $regs = App\Models\AssessmentRegistration::where('user_id', $user->id)->get();
    foreach ($regs as $reg) {
        echo "Registration ID: " . $reg->id . " - Scheme: " . ($reg->scheme->name ?? 'N/A') . "\n";
        $sigs = App\Models\SignatureRequest::where('registration_id', $reg->id)
            ->whereIn('document_label', ['AK.02', 'FR.AK.02'])
            ->get(['role', 'status']);
        echo json_encode($sigs) . "\n";
    }
}
