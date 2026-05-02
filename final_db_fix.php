<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Memulai perbaikan database...\n";

$tableName = 'assessment_registrations';

if (!Schema::hasTable($tableName)) {
    die("Error: Tabel $tableName tidak ditemukan!\n");
}

Schema::table($tableName, function (Blueprint $table) use ($tableName) {
    if (!Schema::hasColumn($tableName, 'status')) {
        echo "- Menambahkan kolom 'status'...\n";
        $table->string('status')->default('apl01_draft')->after('tuk');
    }

    if (!Schema::hasColumn($tableName, 'status_apl01')) {
        echo "- Menambahkan kolom 'status_apl01'...\n";
        $table->string('status_apl01')->nullable()->after('status');
    }

    if (!Schema::hasColumn($tableName, 'status_apl02')) {
        echo "- Menambahkan kolom 'status_apl02'...\n";
        $table->string('status_apl02')->nullable()->after('status_apl01');
    }
});

echo "\nSukses! Semua kolom sudah dipastikan ada di database.\n";
echo "Sekarang kamu bisa mencoba klik tombol 'Terima' lagi.\n";
