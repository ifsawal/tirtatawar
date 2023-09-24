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
            [
                'awal' => 9500,
                'akhir' => 9502,
                'pemakaian' => 2,
                'bulan' => 7,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
            [
                'awal' => 9502,
                'akhir' => 9504,
                'pemakaian' => 2,
                'bulan' => 8,
                'tahun' => 2023,
                'pelanggan_id' => 1,
                'user_id' => 1,
            ],
        ]);

        Tagihan::insert([
            [
                'pencatatan_id' => 4,
                'jumlah' => 15000,
                'diskon' => 0,
                'denda' => 0,
                'biaya' => 10000,
                'subtotal' => 15000,
                'total' => 15000,
                'status_bayar' => 'N',
                'sistem_bayar' => NULL,
            ],
            [
                'pencatatan_id' => 5,
                'jumlah' => 15000,
                'diskon' => 0,
                'denda' => 0,
                'biaya' => 10000,
                'subtotal' => 15000,
                'total' => 15000,
                'status_bayar' => 'N',
                'sistem_bayar' => NULL,
            ],
            [
                'pencatatan_id' => 1,
                'jumlah' => 1000,
                'diskon' => 0,
                'denda' => 0,
                'biaya' => 10000,
                'total' => 15000,
                'subtotal' => 15000,
                'status_bayar' => 'N',
                'sistem_bayar' => NULL,
            ],
            [
                'pencatatan_id' => 2,
                'jumlah' => 8000,
                'diskon' => 0,
                'denda' => 0,
                'biaya' => 10000,
                'subtotal' => 8000,
                'total' => 8000,
                'status_bayar' => 'N',
                'sistem_bayar' => NULL,
            ],
            [
                'pencatatan_id' => 3,
                'jumlah' => 500,
                'diskon' => 0,
                'denda' => 0,
                'biaya' => 10000,
                'subtotal' => 500,
                'total' => 500,
                'status_bayar' => 'N',
                'sistem_bayar' => NULL,
            ],
        ]);
    }
}
