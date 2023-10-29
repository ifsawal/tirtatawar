<?php

namespace Database\Seeders;

use App\Models\Master\Daftarkeluhan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaftarkeluhanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Daftarkeluhan::insert([
            [
                'keluhan' => 'Hari ini air tidak datang',
            ],
            [
                'keluhan' => 'air tidak datang 2 hari lebih',
            ],
            [
                'keluhan' => 'air tidak datang seminggu lebih',
            ],
            [
                'keluhan' => 'Piva depan rumah bocor',
            ],

        ]);
    }
}
