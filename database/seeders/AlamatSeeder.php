<?php

namespace Database\Seeders;

use App\Models\Master\Desa;
use App\Models\Master\Provinsi;
use Illuminate\Database\Seeder;
use App\Models\Master\Kabupaten;
use App\Models\Master\Kecamatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AlamatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Provinsi::create([
            'provinsi' => 'Aceh',
        ]);

        Kabupaten::create([
            'kabupaten' => 'Aceh Tengah',
            'provinsi_id' => 1
        ]);
        Kabupaten::create([
            'kabupaten' => 'Gayo Lues',
            'provinsi_id' => 1
        ]);
        Kecamatan::create([
            'kecamatan' => 'Pegasing',
            'kabupaten_id' => 1
        ]);
        Kecamatan::create([
            'kecamatan' => 'Bebesen',
            'kabupaten_id' => 1
        ]);
        Kecamatan::create([
            'kecamatan' => 'Blangkejeren',
            'kabupaten_id' => 2
        ]);
        Desa::create([
            'desa' => 'Pegasing',
            'kecamatan_id' => 1
        ]);
        Desa::create([
            'desa' => 'Simpang Kelaping',
            'kecamatan_id' => 1
        ]);
        Desa::create([
            'desa' => 'Durin',
            'kecamatan_id' => 2
        ]);
    }
}
