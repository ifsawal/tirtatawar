<?php

namespace Database\Seeders;

use App\Models\Master\Pdam;
use App\Models\Master\Pelanggan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggan =  new Pelanggan;
        $pelanggan->nama = "Sawaludin";
        $pelanggan->nik = "11224546456";
        $pelanggan->kk = "4455662665";
        $pelanggan->golongan_id = 1;
        $pelanggan->lat = "4.629504850910602";
        $pelanggan->long = "96.844175600882";
        $pelanggan->nolama = 11122254;
        $pelanggan->pdam_id = 1;
        $pelanggan->desa_id = 3;
        $pelanggan->user_id = 1;
        $pelanggan->user_id_penyetuju = 1;
        $pelanggan->save();


        Pelanggan::insert([
            [
                'nama' => 'Iwan Darita',
                'nik' => "445565656",
                'kk' => "456456215",
                'golongan_id' => 1,

                'lat' => "",
                'long' => "",
                'pdam_id' => 1,
                'desa_id' => 3,
                'user_id' => 1,
                'user_id_penyetuju' => 1,
            ],
            [
                'nama' => 'Sulstri B',
                'nik' => "445565656",
                'kk' => "445565656",
                'golongan_id' => 1,
                'lat' => "4.629504850910602",
                'long' => "96.844175600882",
                'pdam_id' => 1,
                'desa_id' => 3, //kemili
                'user_id' => 1,
                'user_id_penyetuju' => 1,
            ],
            [
                'nama' => 'Ucok B',
                'nik' => "3435",
                'kk' => "22245666",
                'golongan_id' => 2,
                'lat' => "4.629504850910602",
                'long' => "96.844175600882",
                'pdam_id' => 1,
                'desa_id' => 3, //kemili
                'user_id' => 1,
                'user_id_penyetuju' => 1,
            ],
            [
                'nama' => 'Arwin',
                'nik' => "445565656",
                'kk' => "445565656",
                'golongan_id' => 1,
                'lat' => "",
                'long' => "",
                'pdam_id' => 1,
                'desa_id' => 1,  //pegasing
                'user_id' => 1,
                'user_id_penyetuju' => 1,
            ],

        ]);
    }
}
