<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$regId = 17;

echo "=== Cleanup stale AK.02 SignatureRequests for Registration #$regId ===\n\n";

// Check what AK.02 results exist
$ak02Results = App\Models\AssessmentResult::where('registration_id', $regId)
    ->where('document_type', 'FR.AK.02')
    ->get(['id', 'document_type', 'created_at']);

echo "AK.02 assessment_results: " . $ak02Results->count() . "\n";
foreach ($ak02Results as $r) {
    echo "  - ID: {$r->id}, created: {$r->created_at}\n";
}

// Check existing signature requests
$sigs = App\Models\SignatureRequest::where('registration_id', $regId)
    ->whereIn('document_label', ['AK.02', 'FR.AK.02'])
    ->get(['id', 'document_label', 'role', 'status', 'user_id']);

echo "\nAK.02 signature_requests: " . $sigs->count() . "\n";
foreach ($sigs as $s) {
    echo "  - ID: {$s->id}, label: {$s->document_label}, role: {$s->role}, status: {$s->status}\n";
}

if ($ak02Results->isEmpty() && $sigs->isNotEmpty()) {
    echo "\n⚠️  Stale signature requests found (AK.02 result deleted but sigs remain)!\n";
    echo "   Deleting " . $sigs->count() . " stale signature requests...\n";
    
    $deleted = App\Models\SignatureRequest::where('registration_id', $regId)
        ->whereIn('document_label', ['AK.02', 'FR.AK.02'])
        ->delete();
    
    echo "✅ Deleted $deleted stale signature requests.\n";
} elseif ($ak02Results->isNotEmpty()) {
    echo "\n✅ AK.02 result EXISTS — signature requests are valid, no cleanup needed.\n";
    echo "   If Asesor needs to reset, delete the AK.02 record from Filament first.\n";
} else {
    echo "\n✅ No stale data found — clean state.\n";
}

echo "\n=== Done ===\n";
