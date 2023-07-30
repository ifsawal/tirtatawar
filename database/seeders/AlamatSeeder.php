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
        Provinsi::insert([
            'provinsi' => 'Aceh',
        ]);

        Kabupaten::insert([[
            'kabupaten' => 'Aceh Tengah',
            'provinsi_id' => 1
        ], [
            'kabupaten' => 'Gayo Lues',
            'provinsi_id' => 1
        ]]);

        Kecamatan::insert([
            [
                'kecamatan' => 'Pegasing',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Bebesen',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Blangkejeren',
                'kabupaten_id' => 2
            ]
        ]);

        Desa::insert([
            [
                'desa' => 'Pegasing',
                'kecamatan_id' => 1
            ],
            [
                'desa' => 'Simpang Kelaping',
                'kecamatan_id' => 1
            ],
            [
                'desa' => 'Kemili',
                'kecamatan_id' => 2
            ],
            [
                'desa' => 'Durin',
                'kecamatan_id' => 3
            ]
        ]);
    }
}
