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
                'akhir' => 10,
                'pemakaian' => 10,
                'bulan' => 1,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
            [
                'awal' => 10,
                'akhir' => 15,
                'pemakaian' => 5,
                'bulan' => 2,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
            [
                'awal' => 15,
                'akhir' => 20,
                'pemakaian' => 5,
                'bulan' => 3,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
        ]);
    }
}
