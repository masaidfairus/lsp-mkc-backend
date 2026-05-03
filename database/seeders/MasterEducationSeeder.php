<?php

namespace Database\Seeders;

use App\Models\MasterEducation;
use Illuminate\Database\Seeder;

class MasterEducationSeeder extends Seeder
{
    public function run(): void
    {
        $levels = ['SD', 'SMP', 'SMA/SMK', 'D3', 'D4/S1', 'S2', 'S3'];
        foreach ($levels as $level) {
            MasterEducation::updateOrCreate(['title' => $level]);
        }
    }
}
