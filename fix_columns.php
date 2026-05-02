<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$table = 'assessment_registrations';

Schema::table($table, function (Blueprint $table) {
    if (!Schema::hasColumn('assessment_registrations', 'status_apl01')) {
        echo "Adding status_apl01...\n";
        $table->string('status_apl01')->default('not_started')->index()->after('tuk');
    }
    if (!Schema::hasColumn('assessment_registrations', 'status_apl02')) {
        echo "Adding status_apl02...\n";
        $table->string('status_apl02')->default('not_started')->index()->after('status_apl01');
    }
});

echo "Done fixing columns.\n";
