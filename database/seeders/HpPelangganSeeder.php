<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Master\HpPelanggan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HpPelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HpPelanggan::insert([
            [
                'nohp' => '085360040805',
                'aktif' => 'Y',
                'pelanggan_id' => 1,
            ],
            [
                'nohp' => '085360055555',
                'aktif' => 'Y',
                'pelanggan_id' => 1,
            ],
            [
                'nohp' => '085360055777',
                'aktif' => 'Y',
                'pelanggan_id' => 1,
            ],
            [
                'nohp' => '082304086565',
                'aktif' => 'Y',
                'pelanggan_id' => 2,
            ],
        ]);
    }
}
