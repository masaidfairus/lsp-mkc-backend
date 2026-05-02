<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Menambahkan Index ke tabel signature_requests...\n";

$tableName = 'signature_requests';

Schema::table($tableName, function (Blueprint $table) use ($tableName) {
    // Cek dan tambah index untuk registration_id
    if (Schema::hasColumn($tableName, 'registration_id')) {
        echo "- Mengindex registration_id...\n";
        // Kita gunakan try-catch kalau-kalau index sudah ada
        try { $table->index('registration_id'); } catch (\Exception $e) { echo "  (Sudah ada)\n"; }
    }

    // Cek dan tambah index untuk user_id
    if (Schema::hasColumn($tableName, 'user_id')) {
        echo "- Mengindex user_id...\n";
        try { $table->index('user_id'); } catch (\Exception $e) { echo "  (Sudah ada)\n"; }
    }

    // Cek dan tambah index untuk document_label
    if (Schema::hasColumn($tableName, 'document_label')) {
        echo "- Mengindex document_label...\n";
        try { $table->index('document_label'); } catch (\Exception $e) { echo "  (Sudah ada)\n"; }
    }
    
    // Cek dan tambah index untuk status
    if (Schema::hasColumn($tableName, 'status')) {
        echo "- Mengindex status...\n";
        try { $table->index('status'); } catch (\Exception $e) { echo "  (Sudah ada)\n"; }
    }
});

echo "\nSukses! Performa database sekarang seharusnya jauh lebih cepat.\n";
