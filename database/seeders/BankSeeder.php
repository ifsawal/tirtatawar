<?php

namespace Database\Seeders;

use App\Models\Master\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bank::insert([
            [
                'vendor' => 'flip',
                'kode' => 'bsm',
                'nama' => "BSI",
                'jenis' => "virtual_account",
                'biaya' => "3330",
                'ppn' => 11,
                'aktif' => "Y",
                'ket' => "Aplikasi BSI<br>
                1. Pilih menu Pembayaran.<br>
                2. Pilih menu E-Commerce.<br>
                3. Pilih “DOKU”.<br>
                4. Pilih nomor rekening.<br>
                5. Masukkan nomor bayar. Nomor bayar adalah nomor Virtual Account <br>
                6. Pastikan jumlah transfernya sudah sesuai<br>
                7. Masukkan PIN BSI Mobile.<br>
                8. Transaksi selesai.<br>",
            ],
            // [
            //     'kode' => 'aceh',
            //     'nama' => "Bank Aceh",
            //     'jenis' => 1,
            // ],
            [
                'vendor' => 'flip',
                'kode' => 'bri',
                'nama' => "BRI",
                'jenis' => "virtual_account",
                'biaya' => "3330",
                'ppn' => 11,
                'aktif' => "Y",
                'ket' => NULL,
            ],
            [
                'vendor' => 'flip',
                'kode' => 'bca',
                'nama' => "BCA",
                'jenis' => "virtual_account",
                'biaya' => "4440",
                'ppn' => 11,
                'aktif' => "Y",
                'ket' => NULL,
            ],
            [
                'vendor' => 'flip',
                'kode' => 'mandiri',
                'nama' => "Mandiri",
                'jenis' => "virtual_account",
                'biaya' => "3330",
                'ppn' => 11,
                'aktif' => "Y",
                'ket' => NULL,
            ],
            [
                'vendor' => 'flip',
                'kode' => 'bni',
                'nama' => "BNI",
                'jenis' => "virtual_account",
                'biaya' => "3330",
                'ppn' => 11,
                'aktif' => "Y",
                'ket' => NULL,
            ],
            [
                'vendor' => 'flip',
                'kode' => 'ovo',
                'nama' => "OVO",
                'jenis' => "wallet_account",
                'biaya' => "3.5",
                'ppn' => 11,
                'aktif' => "N",
                'ket' => NULL,
            ],
            [
                'vendor' => 'flip',
                'kode' => 'qris',
                'nama' => "QRIS",
                'jenis' => "wallet_account",
                'biaya' => "0.7",
                'ppn' => 0,
                'aktif' => "N",
                'ket' => NULL,
            ],
            [
                'vendor' => 'flip',
                'kode' => 'linkaja',
                'nama' => "LinkAja",
                'jenis' => "wallet_account",
                'biaya' => "3.5",
                'ppn' => 11,
                'aktif' => "N",
                'ket' => NULL,
            ],


        ]);
    }
}
