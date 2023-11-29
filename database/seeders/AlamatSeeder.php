<?php

namespace Database\Seeders;

use App\Models\Master\Desa;
use App\Models\Master\Provinsi;
use Illuminate\Database\Seeder;
use App\Models\Master\Kabupaten;
use App\Models\Master\Kecamatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AlamatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Provinsi::insert([
            'provinsi' => 'Aceh',
        ]);

        Kabupaten::insert([[
            'kabupaten' => 'Aceh Tengah',
            'provinsi_id' => 1
        ], [
            'kabupaten' => 'Gayo Lues',
            'provinsi_id' => 1
        ]]);

        Kecamatan::insert([
            [ //1
                'kecamatan' => 'Pegasing',
                'kabupaten_id' => 1
            ],
            [ //2
                'kecamatan' => 'Bebesen',
                'kabupaten_id' => 1
            ],

            [ //3
                'kecamatan' => 'Lut Tawar',
                'kabupaten_id' => 1
            ],
            [ //4
                'kecamatan' => 'Kebayakan',
                'kabupaten_id' => 1
            ],

            [ //5
                'kecamatan' => 'Celala',
                'kabupaten_id' => 1
            ],


        ]);

        Desa::insert([
            ['desa' => 'Bebesen', 'kecamatan_id' => 2],
            ['desa' => 'Blang Kolak I', 'kecamatan_id' => 2],
            ['desa' => 'Blang Kolak II', 'kecamatan_id' => 2],
            ['desa' => 'Empus Talu', 'kecamatan_id' => 2],
            ['desa' => 'Kebet', 'kecamatan_id' => 2],
            ['desa' => 'Kemili', 'kecamatan_id' => 2],
            ['desa' => 'Keramat Mupakat', 'kecamatan_id' => 2],
            ['desa' => 'Lemah Burbana', 'kecamatan_id' => 2],
            ['desa' => 'Mongal', 'kecamatan_id' => 2],
            ['desa' => 'Nunang Antara', 'kecamatan_id' => 2],
            ['desa' => 'Pendere Saril', 'kecamatan_id' => 2],
            ['desa' => 'Sadong Juru Mudi', 'kecamatan_id' => 2],
            ['desa' => 'Simpang Empat', 'kecamatan_id' => 2],
            ['desa' => 'Tan Saril', 'kecamatan_id' => 2],
            ['desa' => 'Atu Gajah Reje Guru', 'kecamatan_id' => 2],
            ['desa' => 'Atu Tulu', 'kecamatan_id' => 2],
            ['desa' => 'Bahgie', 'kecamatan_id' => 2],
            ['desa' => 'Blang Gele', 'kecamatan_id' => 2],
            ['desa' => 'Bur Biah', 'kecamatan_id' => 2],
            ['desa' => 'Daling', 'kecamatan_id' => 2],
            ['desa' => 'Gele Lah', 'kecamatan_id' => 2],
            ['desa' => 'Lelabu', 'kecamatan_id' => 2],
            ['desa' => 'Mah Bengi', 'kecamatan_id' => 2],
            ['desa' => 'Tensaren', 'kecamatan_id' => 2],
            ['desa' => 'Ulu Nuih', 'kecamatan_id' => 2],
            ['desa' => 'Umang', 'kecamatan_id' => 2],




            ['desa' => 'Arul Gading', 'kecamatan_id' => 5],
            ['desa' => 'Berawang Gading', 'kecamatan_id' => 5],
            ['desa' => 'Blang Kekumur', 'kecamatan_id' => 5],
            ['desa' => 'Celala', 'kecamatan_id' => 5],
            ['desa' => 'Cibro', 'kecamatan_id' => 5],
            ['desa' => 'Kuyun', 'kecamatan_id' => 5],
            ['desa' => 'Kuyun Toa', 'kecamatan_id' => 5],
            ['desa' => 'Kuyun Uken', 'kecamatan_id' => 5],
            ['desa' => 'Makmur', 'kecamatan_id' => 5],
            ['desa' => 'Melala', 'kecamatan_id' => 5],
            ['desa' => 'Paya Kolak', 'kecamatan_id' => 5],
            ['desa' => 'Ramung Ara', 'kecamatan_id' => 5],
            ['desa' => 'Sepakat', 'kecamatan_id' => 5],
            ['desa' => 'Tanoh Depet', 'kecamatan_id' => 5],
            ['desa' => 'Uning Berawang Ramung', 'kecamatan_id' => 5],


            ['desa' => 'Jongok Meluem', 'kecamatan_id' => 4],
            ['desa' => 'Lot Kala', 'kecamatan_id' => 4],
            ['desa' => 'Mendale', 'kecamatan_id' => 4],
            ['desa' => 'Pedekok', 'kecamatan_id' => 4],
            ['desa' => 'Pinangan', 'kecamatan_id' => 4],
            ['desa' => 'Bukit Sama', 'kecamatan_id' => 4],
            ['desa' => 'Kelupak Mata', 'kecamatan_id' => 4],
            ['desa' => 'Paya Reje Tamidelem', 'kecamatan_id' => 4],
            ['desa' => 'Paya Tumpi', 'kecamatan_id' => 4],
            ['desa' => 'Bukit', 'kecamatan_id' => 4],
            ['desa' => 'Gunung Balohen', 'kecamatan_id' => 4],
            ['desa' => 'Paya Tumpi Baru', 'kecamatan_id' => 4],


            ['desa' => 'Asir-asir', 'kecamatan_id' => 3],
            ['desa' => 'Asir-asir Asia', 'kecamatan_id' => 3],
            ['desa' => 'Bale Atu', 'kecamatan_id' => 3],
            ['desa' => 'Bujang', 'kecamatan_id' => 3],
            ['desa' => 'Hakim Bale', 'kecamatan_id' => 3],
            ['desa' => 'Kute Nireje', 'kecamatan_id' => 3],
            ['desa' => 'Takengon Barat', 'kecamatan_id' => 3],
            ['desa' => 'Takengon Timur', 'kecamatan_id' => 3],
            ['desa' => 'Gunung Suku', 'kecamatan_id' => 3],
            ['desa' => 'Kenawat', 'kecamatan_id' => 3],
            ['desa' => 'Pedemun One-one', 'kecamatan_id' => 3],
            ['desa' => 'Rawe', 'kecamatan_id' => 3],
            ['desa' => 'Toweren Antara', 'kecamatan_id' => 3],
            ['desa' => 'Toweren Toa', 'kecamatan_id' => 3],
            ['desa' => 'Toweren Uken', 'kecamatan_id' => 3],

            ['desa' => 'Arul Badak', 'kecamatan_id' => 1],
            ['desa' => 'Berawang Baro', 'kecamatan_id' => 1],
            ['desa' => 'Gelelungi', 'kecamatan_id' => 1],
            ['desa' => 'Ie Reulop', 'kecamatan_id' => 1],
            ['desa' => 'Kayu Kul', 'kecamatan_id' => 1],
            ['desa' => 'Kedelah', 'kecamatan_id' => 1],
            ['desa' => 'Kung', 'kecamatan_id' => 1],
            ['desa' => 'Kute Lintang', 'kecamatan_id' => 1],
            ['desa' => 'Lelumu', 'kecamatan_id' => 1],
            ['desa' => 'Paya Jeget', 'kecamatan_id' => 1],
            ['desa' => 'Pedekok', 'kecamatan_id' => 1],
            ['desa' => 'Pegasing', 'kecamatan_id' => 1],
            ['desa' => 'Pepalang', 'kecamatan_id' => 1],
            ['desa' => 'Simpang Kelaping', 'kecamatan_id' => 1],
            ['desa' => 'Tebuk', 'kecamatan_id' => 1],
            ['desa' => 'Terang Ulen', 'kecamatan_id' => 1],
            ['desa' => 'Ujung Gele', 'kecamatan_id' => 1],
            ['desa' => 'Wih Ilang', 'kecamatan_id' => 1],
            ['desa' => 'Wih Nareh', 'kecamatan_id' => 1],
            ['desa' => 'Wih Lah', 'kecamatan_id' => 1],
            ['desa' => 'Jejem', 'kecamatan_id' => 1],
            ['desa' => 'Jurusen', 'kecamatan_id' => 1],
            ['desa' => 'Kala Pegasing', 'kecamatan_id' => 1],
            ['desa' => 'Linung Ayu', 'kecamatan_id' => 1],
            ['desa' => 'Panangan Mata', 'kecamatan_id' => 1],
            ['desa' => 'Pantan Musara', 'kecamatan_id' => 1],
            ['desa' => 'Wih Terjun', 'kecamatan_id' => 1],
            ['desa' => 'Belang Bebangka', 'kecamatan_id' => 1],
            ['desa' => 'Uning', 'kecamatan_id' => 1],
            ['desa' => 'Uring', 'kecamatan_id' => 1],
            ['desa' => 'Suka Damai', 'kecamatan_id' => 1],













        ]);
    }
}
