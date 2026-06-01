<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AssessmentRegistration;
use App\Models\AssessmentPlanning;
use App\Models\AssessmentReport;
use App\Models\Ak03;
use App\Models\SignatureRequest;
use Illuminate\Support\Facades\DB;

echo "--- STARTING VERIFICATION TEST ---\n";

$reg = AssessmentRegistration::whereNotNull('assessor_id')->latest()->first();
if (!$reg) {
    echo "❌ Error: No registration with assessor assigned found in database.\n";
    exit(1);
}

echo "Using Registration ID: {$reg->id}, Assessor ID: {$reg->assessor_id}\n";

DB::beginTransaction();
try {
    // 1. Test AssessmentPlanning (MAPA.01 / MAPA.02 / AK.01 / AK.07)
    echo "\n1. Testing AssessmentPlanning (MAPA.01)...\n";
    $planning = AssessmentPlanning::updateOrCreate(
        ['registration_id' => $reg->id],
        ['mapa01_data' => ['test' => 'data']]
    );

    $sigPlanning = SignatureRequest::where([
        'registration_id' => $reg->id,
        'user_id' => $reg->assessor_id,
        'document_label' => 'MAPA.01',
        'role' => 'Asesor'
    ])->first();

    if ($sigPlanning) {
        echo "✅ Success: SignatureRequest for MAPA.01 automatically spawned!\n";
    } else {
        echo "❌ Failure: SignatureRequest for MAPA.01 was NOT spawned.\n";
    }

    // 2. Test AssessmentReport (AK.05)
    echo "\n2. Testing AssessmentReport (AK.05)...\n";
    $report = AssessmentReport::updateOrCreate(
        ['registration_id' => $reg->id, 'document_type' => 'FR.AK.05'],
        ['main_data' => ['test' => 'data']]
    );

    $sigReport = SignatureRequest::where([
        'registration_id' => $reg->id,
        'user_id' => $reg->assessor_id,
        'document_label' => 'AK.05',
        'role' => 'Asesor'
    ])->first();

    if ($sigReport) {
        echo "✅ Success: SignatureRequest for AK.05 automatically spawned!\n";
    } else {
        echo "❌ Failure: SignatureRequest for AK.05 was NOT spawned.\n";
    }

    // 3. Test Ak03
    echo "\n3. Testing Ak03 (AK.03 result)...\n";
    $ak03Result = Ak03::updateOrCreate(
        ['registration_id' => $reg->id],
        ['main_data' => ['test' => 'data']]
    );

    $sigAk03 = SignatureRequest::where([
        'registration_id' => $reg->id,
        'user_id' => $reg->assessor_id,
        'document_label' => 'AK.03',
        'role' => 'Asesor'
    ])->first();

    if ($sigAk03) {
        echo "✅ Success: SignatureRequest for AK.03 automatically spawned!\n";
    } else {
        echo "❌ Failure: SignatureRequest for AK.03 was NOT spawned.\n";
    }

} catch (\Exception $e) {
    echo "❌ Error occurred during test: " . $e->getMessage() . "\n";
} finally {
    DB::rollBack();
    echo "\n--- VERIFICATION TEST FINISHED (DATABASE ROLLED BACK) ---\n";
}
