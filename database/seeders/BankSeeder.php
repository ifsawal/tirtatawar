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
                'kode' => 'bsm',
                'nama' => "BSI",
                'jenis' => "virtual_account",
                'biaya' => "3330",
            ],
            // [
            //     'kode' => 'aceh',
            //     'nama' => "Bank Aceh",
            //     'jenis' => 1,
            // ],
            [
                'kode' => 'bri',
                'nama' => "BRI",
                'jenis' => "virtual_account",
                'biaya' => "3330",
            ],
            [
                'kode' => 'bca',
                'nama' => "BCA",
                'jenis' => "virtual_account",
                'biaya' => "4440",
            ],
            [
                'kode' => 'mandiri',
                'nama' => "Mandiri",
                'jenis' => "virtual_account",
                'biaya' => "3330",
            ],
            [
                'kode' => 'bni',
                'nama' => "BNI",
                'jenis' => "virtual_account",
                'biaya' => "3330",
            ],
            [
                'kode' => 'ovo',
                'nama' => "OVO",
                'jenis' => "wallet_account",
                'biaya' => "3.5",
            ],
            [
                'kode' => 'qris',
                'nama' => "QRIS",
                'jenis' => "wallet_account",
                'biaya' => "0.7",
            ],
            [
                'kode' => 'linkaja',
                'nama' => "LinkAja",
                'jenis' => "wallet_account",
                'biaya' => "3.5",
            ],


        ]);
    }
}
