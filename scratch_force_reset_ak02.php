<?php
/**
 * FORCE RESET AK.02 for Registration #17
 * Deletes the assessment_result AND cascades to delete signature_requests
 * so the flow can start fresh from "Menunggu Asesor"
 */
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$regId = 17;

echo "=== Force Reset AK.02 for Registration #$regId ===\n\n";

$ak02 = App\Models\AssessmentResult::where('registration_id', $regId)
    ->where('document_type', 'FR.AK.02')
    ->first();

if (!$ak02) {
    echo "✅ No AK.02 result found — already clean.\n";
} else {
    echo "Found AK.02 result ID: {$ak02->id}\n";
    echo "Deleting via Eloquent (triggers cascade delete hook)...\n";
    $ak02->delete(); // This triggers static::deleting() → cascades signature_requests
    echo "✅ AK.02 result deleted.\n";
}

// Verify cleanup
$remaining = App\Models\SignatureRequest::where('registration_id', $regId)
    ->whereIn('document_label', ['AK.02', 'FR.AK.02'])
    ->count();

echo "\nRemaining AK.02 signature_requests: $remaining\n";

if ($remaining === 0) {
    echo "✅ CASCADE DELETE WORKED — all stale signature requests cleaned up.\n";
    echo "\n📋 AK.02 STATUS IS NOW: waiting_assessor\n";
    echo "   Next step: Asesor membuat AK.02 di Filament.\n";
} else {
    echo "⚠️  Some signature requests still remain:\n";
    $sigs = App\Models\SignatureRequest::where('registration_id', $regId)
        ->whereIn('document_label', ['AK.02', 'FR.AK.02'])
        ->get(['id', 'role', 'status']);
    echo json_encode($sigs, JSON_PRETTY_PRINT) . "\n";
    // Manual cleanup
    App\Models\SignatureRequest::where('registration_id', $regId)
        ->whereIn('document_label', ['AK.02', 'FR.AK.02'])
        ->delete();
    echo "✅ Manually cleaned up.\n";
}

echo "\n=== Reset Complete ===\n";
