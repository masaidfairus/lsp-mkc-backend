<?php

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\SignatureRequest;
use App\Models\AssessmentRegistration;

$regId = 27;
$reg = AssessmentRegistration::find($regId);

if (!$reg) {
    echo "Registration not found\n";
    exit(1);
}

// Find assessor's signed request
$assessorReq = SignatureRequest::where('registration_id', $regId)
    ->whereIn('document_label', ['AK.01', 'FR.AK.01'])
    ->whereIn('role', ['Asesor', 'asesor'])
    ->where('status', 'signed')
    ->first();

if (!$assessorReq) {
    echo "Assessor signature request not found or not signed for registration 27.\n";
    exit(0);
}

echo "Found signed assessor request: ID={$assessorReq->id}, Label={$assessorReq->document_label}\n";

// Check if Asesi request exists
$cleanLabel = str_replace('FR.', '', $assessorReq->document_label);
$possibleLabels = [$cleanLabel, 'FR.' . $cleanLabel];

$asesiReq = SignatureRequest::where('registration_id', $regId)
    ->where('user_id', $reg->user_id)
    ->where('role', 'asesi')
    ->whereIn('document_label', $possibleLabels)
    ->first();

if (!$asesiReq) {
    $created = SignatureRequest::create([
        'registration_id' => $regId,
        'user_id'         => $reg->user_id,
        'document_label'  => $assessorReq->document_label,
        'status'          => 'pending',
        'signable_type'   => $assessorReq->signable_type,
        'signable_id'     => $assessorReq->signable_id,
        'role'            => 'asesi',
    ]);
    echo "Created Asesi signature request: ID={$created->id}, Label={$created->document_label}\n";
} else {
    echo "Asesi signature request already exists: ID={$asesiReq->id}, Status={$asesiReq->status}\n";
}
