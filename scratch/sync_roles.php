<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

$adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
$asesorRole = Role::firstOrCreate(['name' => 'asesor', 'guard_name' => 'web']);

$allPermissions = Permission::all();

$adminPermissions = [];
$asesorPermissions = [];

$excludedForBoth = ['user', 'role', 'master', 'signature', 'shield', 'work::group', 'performance::criterion', 'competency::element', 'competency::unit', 'scheme', 'document::list'];

foreach ($allPermissions as $permission) {
    $name = $permission->name;
    
    $isExcluded = false;
    foreach ($excludedForBoth as $ex) {
        if (str_contains($name, $ex)) {
            $isExcluded = true;
            break;
        }
    }

    if ($isExcluded) {
        continue;
    }

    // Admin: Read all documents, Update AssessmentRegistration
    if (str_starts_with($name, 'view_') || str_starts_with($name, 'view_any_')) {
        $adminPermissions[] = $name;
    }
    if ($name === 'update_assessment::registration') {
        $adminPermissions[] = $name;
    }

    // Asesor: Read and Update AssessmentRegistration
    if (str_contains($name, 'assessment::registration')) {
        if (str_starts_with($name, 'view_') || str_starts_with($name, 'view_any_') || str_starts_with($name, 'update_')) {
            $asesorPermissions[] = $name;
        }
    } 
    // Asesor: Read AK.04
    elseif (str_contains($name, 'ak04')) {
        if (str_starts_with($name, 'view_') || str_starts_with($name, 'view_any_')) {
            $asesorPermissions[] = $name;
        }
    }
    // Asesor: Read and Update (Insert) for the rest
    else {
        if (str_starts_with($name, 'view_') || str_starts_with($name, 'view_any_') || str_starts_with($name, 'create_') || str_starts_with($name, 'update_')) {
            $asesorPermissions[] = $name;
        }
    }
}

$adminRole->syncPermissions($adminPermissions);
$asesorRole->syncPermissions($asesorPermissions);

// Create a dummy Admin user if one doesn't exist just so we can test.
$adminUser = User::firstOrCreate(
    ['email' => 'admin@example.com'],
    [
        'name' => 'Administrator',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]
);
$adminUser->assignRole('admin');

echo "Roles and permissions synced successfully.\n";

