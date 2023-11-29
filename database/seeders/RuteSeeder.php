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
            [ //1
                'rute' => 'SPAM Bukit Origon',
                'pdam_id' => 1,
            ],
            [ //2
                'rute' => 'SPAM IKK Lut Tawar',
                'pdam_id' => 1,
            ],
            [ //3
                'rute' => 'IPA Lelabu',
                'pdam_id' => 1,
            ],
            [ //4
                'rute' => 'SPAM IKK Pegasing',
                'pdam_id' => 1,
            ],
            [ //5
                'rute' => 'Sumber Air Lelemu IKK Pegasing',
                'pdam_id' => 1,
            ],
            [ //6
                'rute' => 'SPAM IKK Celala',
                'pdam_id' => 1,
            ],
            [ //7
                'rute' => 'Sumber Air Arul Pestak IKK Lut Tawar',
                'pdam_id' => 1,
            ],
        ]);
    }
}
