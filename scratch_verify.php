<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$u = User::where('email', 'asesor.filament@gmail.com')->first();
if ($u) {
    echo "Name: " . $u->name . "\n";
    echo "Email: " . $u->email . "\n";
    echo "email_verified_at: " . ($u->email_verified_at ?? 'NULL') . "\n";
    echo "status: " . ($u->status ?? 'NULL') . "\n";
    echo "roles: " . $u->roles->pluck('name')->join(',') . "\n";
    
    // Test password
    echo "password check: " . (Hash::check('asesor123', $u->password) ? 'OK' : 'FAIL') . "\n";
} else {
    echo "User NOT found!\n";
}
