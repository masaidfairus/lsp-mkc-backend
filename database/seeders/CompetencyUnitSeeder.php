<?php

namespace Database\Seeders;

use App\Models\CompetencyUnit;
use Illuminate\Database\Seeder;

class CompetencyUnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['scheme_id' => 1, 'unit_code' => 'J.620100.001.01', 'unit_title' => 'Menggunakan Teknologi Web'],
            ['scheme_id' => 1, 'unit_code' => 'J.620100.002.01', 'unit_title' => 'Membuat Halaman Web Statis'],
            ['scheme_id' => 1, 'unit_code' => 'J.620100.003.01', 'unit_title' => 'Membuat Halaman Web Dinamis'],
            // tambahkan unit lainnya sesuai skema...
        ];

        CompetencyUnit::insert($units);
    }
}