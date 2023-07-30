<?php

namespace Database\Seeders;

use App\Models\Master\Pdam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PdamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pdam::create([
            'pdam' => 'PDAM Tirta Tawar',
            'nama' => 'PDAM Tirta Tawar Aceh Tengah',
            'kabupaten_id' => 1,
        ]);
    }
}
