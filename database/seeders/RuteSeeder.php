<?php

namespace Database\Seeders;

use App\Models\Master\Rute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RuteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rute::insert([
            [
                'rute' => 'Sumber 1 KKL',
                'pdam_id' => 1,
                ],
            [
                'rute' => 'Sumber 2 CCD',
                'pdam_id' => 1,            ],
        ]);
    }
}
