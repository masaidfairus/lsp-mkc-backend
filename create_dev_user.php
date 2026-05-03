<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'dev.admin@lspmkc.id';
$password = 'password';

$user = User::updateOrCreate(
    ['email' => $email],
    [
        'name' => 'Dev Admin',
        'password' => Hash::make($password),
        'role' => 'superadmin',
        'email_verified_at' => now(),
    ]
);

echo "User $email created successfully with password '$password'.\n";
