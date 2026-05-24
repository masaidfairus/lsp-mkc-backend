<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'asesor.filament@gmail.com')->first();
if ($user) {
    // Set as plain text so Laravel's "hashed" cast will hash it exactly once
    $user->password = 'asesor123';
    $user->save();
    echo "Password successfully set to plain 'asesor123' and auto-hashed once!\n";
} else {
    echo "User not found!\n";
}
