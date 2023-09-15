<?php

namespace Database\Seeders;

use App\Models\Master\Golongan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GolonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Golongan::insert([
            [
                'golongan' => 'Golongan Sosial 1',
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Golongan Sosial 2',
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'RT Rumah Sederhana',
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'RT Rumah Mewah',
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Instansi Pemerintahan',
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Niaga Kecil',
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Niaga Menengah',
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'denda' => 10000,

                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Niaga Besar',
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'denda' => 10000,

                'pdam_id' => 1,
            ],


        ]);
    }
}
