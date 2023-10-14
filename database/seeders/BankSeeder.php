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
                'aktif' => "Y",
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
                'aktif' => "Y",
            ],
            [
                'vendor' => 'flip',
                'kode' => 'bca',
                'nama' => "BCA",
                'jenis' => "virtual_account",
                'biaya' => "4440",
                'aktif' => "Y",
            ],
            [
                'vendor' => 'flip',
                'kode' => 'mandiri',
                'nama' => "Mandiri",
                'jenis' => "virtual_account",
                'biaya' => "3330",
                'aktif' => "Y",
            ],
            [
                'vendor' => 'flip',
                'kode' => 'bni',
                'nama' => "BNI",
                'jenis' => "virtual_account",
                'biaya' => "3330",
                'aktif' => "Y",
            ],
            [
                'vendor' => 'flip',
                'kode' => 'ovo',
                'nama' => "OVO",
                'jenis' => "wallet_account",
                'biaya' => "3.5",
                'aktif' => "N",
            ],
            [
                'vendor' => 'flip',
                'kode' => 'qris',
                'nama' => "QRIS",
                'jenis' => "wallet_account",
                'biaya' => "0.7",
                'aktif' => "N",
            ],
            [
                'vendor' => 'flip',
                'kode' => 'linkaja',
                'nama' => "LinkAja",
                'jenis' => "wallet_account",
                'biaya' => "3.5",
                'aktif' => "N",
            ],


        ]);
    }
}
