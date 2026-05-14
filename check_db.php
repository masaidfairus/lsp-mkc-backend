<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$table = 'assessment_registrations';
$columns = Schema::getColumnListing($table);
$dbName = DB::getDatabaseName();

echo "Current Database: $dbName\n";
echo "Columns in $table:\n";
print_r($columns);
