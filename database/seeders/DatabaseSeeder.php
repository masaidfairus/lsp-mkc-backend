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

        User::create([
            'role' => 'admin',
            'name' => 'BUDIMAN',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => 'admin123',
        ]);
    }
}
