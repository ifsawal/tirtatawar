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
                'golongan' => 'Golongan Sosial 1',   //1
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'pajak' => 10,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Golongan Sosial 2',  //2
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'pajak' => 10,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'RT Rumah Sederhana',  //3
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'pajak' => 10,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'RT Rumah Mewah',  //4
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'pajak' => 10,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Instansi Pemerintahan',  //5
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'pajak' => 10,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Niaga Kecil',  //6
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'pajak' => 10,
                'denda' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Niaga Menengah',  //7
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'pajak' => 10,
                'denda' => 10000,

                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Niaga Besar',  //8
                'jenis' => 'm3',
                'harga' => 0,
                'biaya' => 7500,
                'pajak' => 10,
                'denda' => 10000,

                'pdam_id' => 1,
            ],


        ]);
    }
}
