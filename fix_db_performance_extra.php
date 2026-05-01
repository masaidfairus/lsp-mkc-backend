<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Mempercepat tabel pendukung...\n";

$tables = [
    'self_assessments' => 'registration_id',
    'apl01_data' => 'registration_id',
    'apl02_portfolios' => 'registration_id',
    'assessment_plannings' => 'registration_id',
];

foreach ($tables as $table => $column) {
    if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
        echo "- Mengindex $table ($column)...\n";
        try {
            Schema::table($table, function (Blueprint $bp) use ($column) {
                $bp->index($column);
            });
        } catch (\Exception $e) { echo "  (Sudah ada/Gagal)\n"; }
    }
}

echo "\nSemua tabel penting sudah dioptimasi!\n";
