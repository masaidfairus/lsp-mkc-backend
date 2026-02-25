<?php

namespace Database\Seeders;

use App\Models\Scheme;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
        ]);

        User::factory()->create([
            'role' => 'admin',
            'name' => 'BUDIMAN',
            'email' => 'admin@admin.com',
            'password' => 'admin123',
        ]);
    }
}
