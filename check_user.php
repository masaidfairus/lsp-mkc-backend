<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('name', 'like', '%Asesor Biasa%')->first();
if ($user) {
    echo "Name: " . $user->name . "\n";
    echo "ID: " . $user->id . "\n";
    echo "Role Column: " . $user->role . "\n";
    echo "Spatie Roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
} else {
    echo "User not found\n";
}
