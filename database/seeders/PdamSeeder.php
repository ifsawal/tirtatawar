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
            'ttd' => 'Takengon',
            'lat' => '4.619110883343515',
            'long' => '96.84816010785435',
            'kabupaten_id' => 1,
        ]);
    }
}
