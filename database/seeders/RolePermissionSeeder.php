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

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $asesor = Role::firstOrCreate(['name' => 'asesor', 'guard_name' => 'web']);
        $validator = Role::firstOrCreate(['name' => 'validator', 'guard_name' => 'web']);
        $penyusun = Role::firstOrCreate(['name' => 'penyusun', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'asesi', 'guard_name' => 'web']);
        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);

        // Custom permission
        Permission::firstOrCreate(['name' => 'upload_certificate_assessment_registration', 'guard_name' => 'web']);

        $allPermissions = Permission::all();
        
        $adminPermissions = collect();
        $asesorPermissions = collect();

        foreach ($allPermissions as $perm) {
            $name = $perm->name;

            // Superadmin gets everything (handled at the end)

            // 1. Hak akses read semua dokumen
            if (str_starts_with($name, 'view_') || str_starts_with($name, 'view_any_')) {
                if (preg_match('/(assessment_registration|assessment::registration|apl|ak|appeal|ia|mapa|va|assessment_instrument|competency|scheme|signature::inbox|work_group|work::group|criterion|element|document::list|master::education|user::signature)/', $name)) {
                    $adminPermissions->push($name);
                }
            }
            // 2. Hak akses update APL.01 (AssessmentRegistration) untuk verifikasi admin
            if (str_contains($name, 'assessment_registration') && str_starts_with($name, 'update_')) {
                $adminPermissions->push($name);
            }

            // 3. Hak akses penuh (CRUD) untuk Mastering Data bagi admin
            if (preg_match('/(scheme|competency::unit|competency::element|performance::criterion|work::group|document::list|master::education|user::signature)/', $name)) {
                $adminPermissions->push($name);
            }

            // --- ASESOR LOGIC ---
            $isAk04 = str_contains($name, 'appeal') || str_contains($name, 'ak04');
            $isApl = str_contains($name, 'assessment_registration') || str_contains($name, 'assessment::registration') || str_contains($name, 'apl');
            $isAk = str_contains($name, 'ak0') || str_contains($name, 'ak_') || str_contains($name, 'ak::');
            $isIa = str_contains($name, 'fr_ia') || str_contains($name, 'fr::ia') || str_contains($name, 'ia0') || str_contains($name, 'ia1');
            $isMapa = str_contains($name, 'mapa');
            $isVa = str_contains($name, 'fr_va') || str_contains($name, 'fr::va');
            $isSigInbox = str_contains($name, 'signature::inbox');
            
            // Hak akses view untuk semua dokumen terkait asesmen
            if (str_starts_with($name, 'view_') || str_starts_with($name, 'view_any_')) {
                if ($isAk04 || $isApl || $isAk || $isIa || $isMapa || $isVa || $isSigInbox) {
                    $asesorPermissions->push($name);
                }
            }
            
            // Hak akses update untuk APL.02 (AssessmentRegistration)
            if ((str_contains($name, 'assessment_registration') || str_contains($name, 'assessment::registration')) && str_starts_with($name, 'update_')) {
                $asesorPermissions->push($name);
            }

            // Hak akses Insert, Update, & Delete untuk semua dokumen selain APL.01, APL.02
            if (str_starts_with($name, 'create_') || str_starts_with($name, 'update_') || str_starts_with($name, 'delete_') || str_starts_with($name, 'delete_any_')) {
                if ($isAk || $isIa || $isMapa || $isVa || $isSigInbox) {
                    $asesorPermissions->push($name);
                }
            }
        }
        
        $adminPermissions->push('upload_certificate_assessment_registration');
        
        $admin->syncPermissions($adminPermissions);
        $asesor->syncPermissions($asesorPermissions);
        $superadmin->syncPermissions(Permission::all());
    }
}