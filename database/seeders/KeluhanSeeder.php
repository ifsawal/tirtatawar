<?php

namespace Database\Seeders;

use App\Models\Keluhan\Keluhan;
use App\Models\Keluhan\Proses;
use App\Models\Keluhan\Tim;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeluhanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Keluhan::insert([
        //     [
        //         "pelanggan_id" => 1,
        //         "keluhan" => "Tolong air tidak datang",
        //         "status" => 'selesai',
        //         'created_at' => "2023-9-2 10:10:00",
        //     ],
        //     [
        //         "pelanggan_id" => 1,
        //         "keluhan" => "Tolong air tidak datang gak datang bneraran",
        //         "status" => 'proses',
        //         "created_at" => "2023-9-8 10:10:00"

        //     ],
        //     [
        //         "pelanggan_id" => 1,
        //         "keluhan" => "Tolong air tidak datang gak datang bneraran",
        //         "status" => 'proses',
        //         "created_at" => "2023-9-10 10:10:00",

        //     ],
        // ]);

        // Proses::insert([
        //     [
        //         'keluhan_id' => 1,
        //         'proses' => 'tim sudah diturunkan',
        //         'user_id' => 1,
        //     ],
        //     [
        //         'keluhan_id' => 1,
        //         'proses' => 'Selesai',
        //         'user_id' => 1,

        //     ],
        //     [
        //         'keluhan_id' => 2,
        //         'proses' => 'Tim sudah diturunkan',
        //         'user_id' => 1,

        //     ],
        // ]);
        // Tim::insert([
        //     [
        //         'keluhan_id' => 1,
        //         'user_id' => 1,
        //         'status' => 'ketua',
        //     ],
        //     [
        //         'keluhan_id' => 2,
        //         'user_id' => 1,
        //         'status' => 'ketua',
        //     ],

        // ]);
    }
}
