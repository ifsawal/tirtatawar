<?php

namespace Database\Seeders;

use App\Models\Master\Pencatatan;
use App\Models\Master\Tagihan;
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
                'akhir' => 20,
                'pemakaian' => 20,
                'bulan' => 1,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
            [
                'awal' => 20,
                'akhir' => 44,
                'pemakaian' => 24,
                'bulan' => 2,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
            [
                'awal' => 44,
                'akhir' => 60,
                'pemakaian' => 26,
                'bulan' => 3,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
            [
                'awal' => 60,
                'akhir' => 90,
                'pemakaian' => 20,
                'bulan' => 7,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
            [
                'awal' => 90,
                'akhir' => 120,
                'pemakaian' => 30,
                'bulan' => 8,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
        ]);

        Tagihan::insert([
            [
                'pencatatan_id' => 4,
                'jumlah' => 34000,
                'diskon' => 0,
                'denda' => 0,
                'biaya' => 7500,
                'subtotal' => 41500,
                'total' => 41500,
                'status_bayar' => 'N',
                'sistem_bayar' => NULL,
            ],
            [
                'pencatatan_id' => 5,
                'jumlah' => 68000,
                'diskon' => 0,
                'denda' => 0,
                'biaya' => 7500,
                'subtotal' => 75500,
                'total' => 75500,
                'status_bayar' => 'N',
                'sistem_bayar' => NULL,
            ],
            [
                'pencatatan_id' => 1,
                'jumlah' => 34000,
                'diskon' => 0,
                'denda' => 0,
                'biaya' => 7500,
                'total' => 41500,
                'subtotal' => 41500,
                'status_bayar' => 'N',
                'sistem_bayar' => NULL,
            ],
            [
                'pencatatan_id' => 2,
                'jumlah' => 47600,
                'diskon' => 0,
                'denda' => 0,
                'biaya' => 7500,
                'subtotal' => 55100,
                'total' => 55100,
                'status_bayar' => 'N',
                'sistem_bayar' => NULL,
            ],
            [
                'pencatatan_id' => 3,
                'jumlah' => 54400,
                'diskon' => 0,
                'denda' => 0,
                'biaya' => 7500,
                'subtotal' => 61900,
                'total' => 61900,
                'status_bayar' => 'N',
                'sistem_bayar' => NULL,
            ],
        ]);
    }
}
