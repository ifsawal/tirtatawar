<?php

namespace Database\Seeders;

use App\Models\Master\Goldetil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoldetilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Goldetil::insert([
            [
                'nama' => 'Pertama',
                'awal_meteran' => 0,
                'akhir_meteran' => 10,
                'harga' => 2100,
                'golongan_id' => 1,
            ],
            [
                'nama' => 'Kedua',
                'awal_meteran' => 10,
                'akhir_meteran' => 0,
                'harga' => 2270,
                'golongan_id' => 1,
            ],
            [
                'nama' => 'Pertama',
                'awal_meteran' => 0,
                'akhir_meteran' => 10,
                'harga' => 2100,
                'golongan_id' => 2,
            ],
            [
                'nama' => 'Kedua',
                'awal_meteran' => 10,
                'akhir_meteran' => 0,
                'harga' => 2270,
                'golongan_id' => 2,
            ],
            [
                'nama' => 'Pertama',
                'awal_meteran' => 0,
                'akhir_meteran' => 10,
                'harga' => 2500,
                'golongan_id' => 3,
            ],
            [
                'nama' => 'Kedua',
                'awal_meteran' => 10,
                'akhir_meteran' => 0,
                'harga' => 3400,
                'golongan_id' => 3,
            ],
            [
                'nama' => 'Pertama',
                'awal_meteran' => 0,
                'akhir_meteran' => 10,
                'harga' => 5364,
                'golongan_id' => 4,
            ],
            [
                'nama' => 'Kedua',
                'awal_meteran' => 10,
                'akhir_meteran' => 0,
                'harga' => 7509,
                'golongan_id' => 4,
            ],
            [
                'nama' => 'Pertama',
                'awal_meteran' => 0,
                'akhir_meteran' => 10,
                'harga' => 5364,
                'golongan_id' => 5,
            ],
            [
                'nama' => 'Kedua',
                'awal_meteran' => 10,
                'akhir_meteran' => 0,
                'harga' => 7509,
                'golongan_id' => 5,
            ],
            [
                'nama' => 'Pertama',
                'awal_meteran' => 0,
                'akhir_meteran' => 10,
                'harga' => 5364,
                'golongan_id' => 6,
            ],
            [
                'nama' => 'Kedua',
                'awal_meteran' => 10,
                'akhir_meteran' => 0,
                'harga' => 7509,
                'golongan_id' => 6,
            ],
            [
                'nama' => 'Pertama',
                'awal_meteran' => 0,
                'akhir_meteran' => 10,
                'harga' => 5364,
                'golongan_id' => 7,
            ],
            [
                'nama' => 'Kedua',
                'awal_meteran' => 10,
                'akhir_meteran' => 0,
                'harga' => 7509,
                'golongan_id' => 7,
            ],
            [
                'nama' => 'Pertama',
                'awal_meteran' => 0,
                'akhir_meteran' => 10,
                'harga' => 7938,
                'golongan_id' => 8,
            ],
            [
                'nama' => 'Kedua',
                'awal_meteran' => 10,
                'akhir_meteran' => 0,
                'harga' => 9655,
                'golongan_id' => 8,
            ],

        ]);
    }
}
