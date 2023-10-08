<?php

namespace Database\Seeders;

use App\Models\Master\Jenisbayar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisbayarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jenisbayar::insert([
            [
                "kegunaan" => "Pendaftaran Baru",
                "aktif" => "Y",
                "jumlah" => 500000,
                "tgl_aktif" => "2023-10-1",
                'user_id' => 1,
            ],
            [
                "kegunaan" => "Aktif Sambungan Lama",
                "aktif" => "Y",
                "jumlah" => 500000,
                "tgl_aktif" => "2023-10-1",
                'user_id' => 1

            ],

        ]);
    }
}
