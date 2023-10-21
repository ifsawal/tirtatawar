<?php

namespace Database\Seeders;

use App\Models\Master\Wiljalan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WiljalanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Wiljalan::insert([
            [
                'id' => 1,
                'jalan' => 'Lebe Kadir',
                'user_id' => 18,
                'mulai' => 0,
                'akhir' => 0,
                'pdam_id' => 1,
            ],

            ['id' => 2,  'jalan' => 'Belang Kolak 1', 'user_id' =>  16, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 3,  'jalan' => 'Damar', 'user_id' =>           16, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 4,  'jalan' => 'Bale Atu', 'user_id' =>        16, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 5,  'jalan' => 'Tetunjung', 'user_id' =>       15, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 6,  'jalan' => 'Pasar Inpres', 'user_id' =>    18, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 7,  'jalan' => 'Laut Tawar', 'user_id' =>      15, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 8,  'jalan' => 'Terminal', 'user_id' =>        NULL, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,], //

            ['id' => 9,  'jalan' => 'Pahlawan', 'user_id' =>        18, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 10,  'jalan' => 'Boom', 'user_id' =>           15, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 11,  'jalan' => 'Nunang 1001/Gelengang', 'user_id' =>      15, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 12,  'jalan' => 'Sengeda', 'user_id' =>        11, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 13,  'jalan' => 'Lentik', 'user_id' =>         14, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 14,  'jalan' => 'Lot Kala', 'user_id' =>       12, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 15,  'jalan' => 'Jongok', 'user_id' =>         18, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 16,  'jalan' => 'Bukit', 'user_id' =>          12, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 17,  'jalan' => 'Perumnas', 'user_id' =>       18, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 18,  'jalan' => 'Gunung Balohen', 'user_id' => 12, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 19,  'jalan' => 'Tansaril', 'user_id' =>       17, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 20,  'jalan' => 'Yossudarso', 'user_id' =>     19, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 21,  'jalan' => 'Asrama Merah', 'user_id' =>   16, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 22,  'jalan' => 'Asrama Kompi', 'user_id' =>   18, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 23,  'jalan' => 'PGA/MAN 2', 'user_id' =>      16, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 24,  'jalan' => 'Lemah', 'user_id' =>          21, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 25,  'jalan' => 'Reje Bukit', 'user_id' =>     11, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 26,  'jalan' => 'Lembaga', 'user_id' =>        22, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 27,  'jalan' => 'Bebesen', 'user_id' =>        22, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 28,  'jalan' => 'Simpang Empat', 'user_id' =>  15, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 29,  'jalan' => 'Umah Opat', 'user_id' =>      15, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 30,  'jalan' => 'Lelabu', 'user_id' =>         11, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 31,  'jalan' => 'Mesir', 'user_id' =>          22, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 32,  'jalan' => 'Time Ruang', 'user_id' =>     14, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 33,  'jalan' => 'Kemili', 'user_id' =>         20, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 34,  'jalan' => 'Mahkamah', 'user_id' =>       18, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 35,  'jalan' => 'Kampung Baru', 'user_id' =>   18, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 36,  'jalan' => 'Mersa', 'user_id' =>          18, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 37,  'jalan' => 'SPG', 'user_id' =>            20, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 38,  'jalan' => 'Sudirman', 'user_id' =>       20, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 39,  'jalan' => 'Buntul Temil', 'user_id' =>   20, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 40,  'jalan' => 'Malem Dewa', 'user_id' =>         20, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 41,  'jalan' => 'Pedemun', 'user_id' =>        21, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 42,  'jalan' => 'One-One', 'user_id' =>        21, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 43,  'jalan' => 'Kampung Asia', 'user_id' =>   20, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 44,  'jalan' => 'Dedalu', 'user_id' =>         20, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 45,  'jalan' => 'Buntul Bujang', 'user_id' =>  20, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 46,  'jalan' => 'Asir-Asir Bawah', 'user_id' => 20, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 47,  'jalan' => 'Kampung Bale', 'user_id' =>   21, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 48,  'jalan' => 'Kedelah', 'user_id' =>        24, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,], //
            ['id' => 49,  'jalan' => 'Belang Bebangka', 'user_id' => 24, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,], //
            ['id' => 50,  'jalan' => 'Pendere', 'user_id' =>        22, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 51,  'jalan' => 'Jalan Betung', 'user_id' =>   25, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,], //
            ['id' => 52,  'jalan' => 'Jalan Berawang Gading', 'user_id' =>      25, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,], //
            ['id' => 53,  'jalan' => 'Jalan Angkup', 'user_id' =>   25, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,], //
            ['id' => 54,  'jalan' => 'Sengeda 2', 'user_id' =>      NULL, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],
            ['id' => 55,  'jalan' => 'Sengeda 3', 'user_id' =>      NULL, 'mulai' => 0, 'akhir' => 0, 'pdam_id' => 1,],


        ]);
    }
}
