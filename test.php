<?php 
require __DIR__.'/vendor/autoload.php'; 
$app = require_once __DIR__.'/bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$reg = App\Models\AssessmentRegistration::find(13); 
echo 'EMAIL: ' . $reg->candidate->email . ' | ID_TYPE: ' . $reg->candidate->identity_type;
