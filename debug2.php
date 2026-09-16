<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SignatureRequest;
use App\Models\User;

echo "=== All Signature Requests (asesi role) ===\n";
$reqs = SignatureRequest::where('role', 'asesi')->get();
foreach ($reqs as $req) {
    $user = User::find($req->user_id);
    echo "ID: {$req->id}, user_id: {$req->user_id}, user_name: {$user?->name}, doc: {$req->document_label}, status: {$req->status}, reg_id: {$req->registration_id}\n";
}

echo "\n=== Users ===\n";
$users = User::all(['id', 'name', 'email']);
foreach ($users as $u) {
    echo "ID: {$u->id}, Name: {$u->name}, Email: {$u->email}\n";
}
