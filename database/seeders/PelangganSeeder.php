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
        $pelanggan->lat = "4.629504850910602";
        $pelanggan->long = "96.844175600882";
        $pelanggan->pdam_id = 1;
        $pelanggan->desa_id = 3;
        $pelanggan->user_id = 1;
        $pelanggan->save();


        Pelanggan::insert([
            [
                'nama' => 'Iwan Darita',
                'nik' => "445565656",
                'lat' => "4.629504850910602",
                'long' => "96.844175600882",
                'pdam_id' => 1,
                'desa_id' => 3,
                'user_id' => 1,
            ],
            [
                'nama' => 'Sulstri B',
                'nik' => "445565656",
                'lat' => "4.629504850910602",
                'long' => "96.844175600882",
                'pdam_id' => 1,
                'desa_id' => 3,
                'user_id' => 1,
            ],

        ]);
    }
}
