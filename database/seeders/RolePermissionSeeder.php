<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Ambil semua permission yang sudah digenerate shield
        $aplPermissions     = Permission::where('name', 'like', '%_apl%')->pluck('name');
        $akPermissions      = Permission::where('name', 'like', '%_ak%')->pluck('name');
        $userPermissions    = Permission::where('name', 'like', '%_user%')->pluck('name');
        $shieldPermissions  = Permission::where('name', 'like', '%_role%')
                                ->orWhere('name', 'like', '%_permission%')
                                ->pluck('name');

        // Admin — hanya APL
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($aplPermissions);

        // Asesor — hanya AK
        $asesor = Role::firstOrCreate(['name' => 'asesor', 'guard_name' => 'web']);
        $asesor->syncPermissions($akPermissions);

        // Asesi — tidak ada akses Filament
        Role::firstOrCreate(['name' => 'asesi', 'guard_name' => 'web']);

        // Superadmin — semua permission
        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $superadmin->syncPermissions(Permission::all());
    }
}