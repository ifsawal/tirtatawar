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
                'golongan' => 'Rumah Tangga',
                'harga' => 1000,
                'biaya' => 10000,
                'pdam_id' => 1,
            ],
            [
                'golongan' => 'Bisnis Kecil',
                'harga' => 1500,
                'biaya' => 10000,
                'pdam_id' => 1,
            ],

        ]);
    }
}
