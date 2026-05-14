<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sigs = App\Models\SignatureRequest::whereIn('document_label', ['AK.02', 'FR.AK.02'])->latest()->take(5)->get(['id', 'registration_id', 'document_label', 'role', 'status']);
echo json_encode($sigs, JSON_PRETTY_PRINT);
