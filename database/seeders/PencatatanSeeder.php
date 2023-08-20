<?php

namespace Database\Seeders;

use App\Models\Master\Pencatatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PencatatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pencatatan::insert([
            [
                'awal' => 0,
                'akhir' => 1000,
                'pemakaian' => 1000,
                'bulan' => 1,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
            [
                'awal' => 1000,
                'akhir' => 9000,
                'pemakaian' => 8000,
                'bulan' => 2,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
            [
                'awal' => 9000,
                'akhir' => 9500,
                'pemakaian' => 500,
                'bulan' => 3,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
        ]);
    }
}
