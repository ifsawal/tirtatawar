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
                'rute' => 'SPAM Bukit Origon',
                'pdam_id' => 1,
            ],
            [
                'rute' => 'SPAM IKK Lut Tawar',
                'pdam_id' => 1,
            ],
            [
                'rute' => 'IPA Lelabu',
                'pdam_id' => 1,
            ],
            [
                'rute' => 'SPAM IKK Pegasing',
                'pdam_id' => 1,
            ],
            [
                'rute' => 'Sumber Air Lelemu IKK Pegasing',
                'pdam_id' => 1,
            ],
            [
                'rute' => 'SPAM IKK Celala',
                'pdam_id' => 1,
            ],
            [
                'rute' => 'Sumber Air Arul Pestak IKK Lut Tawar',
                'pdam_id' => 1,
            ],
        ]);
    }
}
