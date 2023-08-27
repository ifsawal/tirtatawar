<?php

namespace Database\Seeders;

use App\Models\Master\Wilayah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Wilayah::insert([
            [
                'wilayah' => 'Wilayah 1',
                'user_id' => 1,
                'desa_id' => 1,
                'mulai' => 0,
                'akhir' => 0,
                'akhir' => 1,
                'pdam_id' => 1,


            ],
            [
                'wilayah' => 'Wilayah 2',
                'user_id' => 2,
                'desa_id' => 2,
                'mulai' => 0,
                'akhir' => 0,
                'akhir' => 1,
                'pdam_id' => 1,
            ],
            [
                'wilayah' => 'Wilayah 3',
                'user_id' => 3,
                'desa_id' => 3,
                'mulai' => 0,
                'akhir' => 0,
                'akhir' => 1,
                'pdam_id' => 1,
            ],
        ]);
    }
}
