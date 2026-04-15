<?php

namespace Database\Seeders;

use App\Models\Scheme;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Symfony\Component\Clock\now;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([   
            SchemeSeeder::class,
            DocumentSeeder::class,
        ]);

        $users = [
            [
                'role' => 'admin',
                'name' => 'BUDIMAN',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => 'admin123',
            ],
            [
                'role' => 'superadmin',
                'name' => 'Ibnu',
                'email' => 'ibnualdbest@gmail.com',
                'email_verified_at' => now(),
                'password' => 'Adm!n123',
            ],
            [
                'role' => 'superadmin',
                'name' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'email_verified_at' => now(),
                'password' => 'superadmin123',
            ],
            [
                'role' => 'asesor',
                'name' => 'asesorbiasa',
                'email' => 'asesorbiasa@gmail.com',
                'email_verified_at' => now(),
                'password' => 'asesorbiasa123',
            ],
            [
                'role' => 'admin',
                'name' => 'adminbiasa',
                'email' => 'adminbiasa@gmail.com',
                'email_verified_at' => now(),
                'password' => 'adminbiasa123',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
