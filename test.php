<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$registration = App\Models\AssessmentRegistration::latest()->first();
if (!$registration) {
    echo "No registration found\n";
    exit;
}
echo "Testing AK.04...\n";
try {
    echo "Asesor: " . ($registration->assessor->name ?? 'None') . "\n";
} catch (\Exception $e) {
    echo "AK.04 Error: " . $e->getMessage() . "\n";
}

echo "Testing AK.03...\n";
try {
    $data = [
        'header' => [
            'certification_scheme' => $registration->scheme?->name ?? '',
            'scheme_code'          => $registration->scheme?->code ?? '',
            'tuk'                  => $registration->tuk ?? '',
            'assessor_name'        => $registration->assessor?->name ?? '',
            'assessee_name'        => $registration->candidate?->name ?? '',
            'assessment_date_start' => now()->toDateString(),
            'assessment_date_end'   => now()->toDateString(),
        ]
    ];
    echo "AK.03 Success\n";
} catch (\Exception $e) {
    echo "AK.03 Error: " . $e->getMessage() . "\n";
} catch (\Error $e) {
    echo "AK.03 Fatal Error: " . $e->getMessage() . "\n";
}
