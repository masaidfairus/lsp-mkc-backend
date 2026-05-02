<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SignatureUserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role utama ada
        Role::firstOrCreate(['name' => 'asesor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'validator', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'penyusun', 'guard_name' => 'web']);

        $password = Hash::make('password');

        // 1. 2 user dengan role asesor dan validator
        for ($i = 1; $i <= 2; $i++) {
            $user = User::firstOrCreate(
                ['email' => "asesor.validator{$i}@lspmkc.id"],
                [
                    'name' => "Asesor Validator $i",
                    'password' => $password,
                    'registration_number' => "MET.000.VAL.00{$i}"
                ]
            );
            $user->syncRoles(['asesor', 'validator']);
        }

        // 2. 2 user dengan role asesor dan penyusun
        for ($i = 1; $i <= 2; $i++) {
            $user = User::firstOrCreate(
                ['email' => "asesor.penyusun{$i}@lspmkc.id"],
                [
                    'name' => "Asesor Penyusun $i",
                    'password' => $password,
                    'registration_number' => "MET.000.PEN.00{$i}"
                ]
            );
            $user->syncRoles(['asesor', 'penyusun']);
        }

        // 3. 1 user dengan role asesor saja
        $user3 = User::firstOrCreate(
            ['email' => "asesor.biasa@lspmkc.id"],
            [
                'name' => "Asesor Biasa",
                'password' => $password,
                'registration_number' => "MET.000.ASE.001"
            ]
        );
        $user3->syncRoles(['asesor']);
    }
}
