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
            [
                'kecamatan' => 'Pegasing',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Bebesen',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Linge',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Atu Lintang',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Bintang',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Lut Tawar',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Kebayakan',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Bies',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Kute Panang',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Silih Nara',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Ketol',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Celala',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Rusip Antara',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Jagong Jeget',
                'kabupaten_id' => 1
            ],
            [
                'kecamatan' => 'Blangkejeren',
                'kabupaten_id' => 2
            ]
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


            ['desa' => 'Atu Lintang', 'kecamatan_id' => 4],
            ['desa' => 'Berawang Dewal', 'kecamatan_id' => 4],
            ['desa' => 'Delung Sekinel', 'kecamatan_id' => 4],
            ['desa' => 'Geragang', 'kecamatan_id' => 4],
            ['desa' => 'Jamat', 'kecamatan_id' => 4],
            ['desa' => 'Lumut', 'kecamatan_id' => 4],
            ['desa' => 'Merah Mege', 'kecamatan_id' => 4],
            ['desa' => 'Tanoh Abu', 'kecamatan_id' => 4],

            ['desa' => 'Aru Latong', 'kecamatan_id' => 8],
            ['desa' => 'Atang Jungket', 'kecamatan_id' => 8],
            ['desa' => 'Bies Baru', 'kecamatan_id' => 8],
            ['desa' => 'Bies Penentanan', 'kecamatan_id' => 8],
            ['desa' => 'Bies Mulie', 'kecamatan_id' => 8],
            ['desa' => 'Karang Bayur', 'kecamatan_id' => 8],
            ['desa' => 'Lenga', 'kecamatan_id' => 8],
            ['desa' => 'Pucuk Deku', 'kecamatan_id' => 8],
            ['desa' => 'Simpang Lukup Badak', 'kecamatan_id' => 8],
            ['desa' => 'Simpang Uning Niken', 'kecamatan_id' => 8],
            ['desa' => 'Tebes Lues', 'kecamatan_id' => 8],
            ['desa' => 'Uning Pegantungen', 'kecamatan_id' => 8],

            ['desa' => 'Atu Payung', 'kecamatan_id' => 5],
            ['desa' => 'Bale Nosar', 'kecamatan_id' => 5],
            ['desa' => 'Bamil Nosar', 'kecamatan_id' => 5],
            ['desa' => 'Bewang', 'kecamatan_id' => 5],
            ['desa' => 'Dedamar', 'kecamatan_id' => 5],
            ['desa' => 'Genuren', 'kecamatan_id' => 5],
            ['desa' => 'Kala Bintang', 'kecamatan_id' => 5],
            ['desa' => 'Kala Segi Bintang', 'kecamatan_id' => 5],
            ['desa' => 'Kejurun Syiah Utama', 'kecamatan_id' => 5],
            ['desa' => 'Kelitu', 'kecamatan_id' => 5],
            ['desa' => 'Kuala I Bintang', 'kecamatan_id' => 5],
            ['desa' => 'Kuala II', 'kecamatan_id' => 5],
            ['desa' => 'Linung Bulen I', 'kecamatan_id' => 5],
            ['desa' => 'Linung Bulen II', 'kecamatan_id' => 5],
            ['desa' => 'Mengaya', 'kecamatan_id' => 5],
            ['desa' => 'Mode Nosar', 'kecamatan_id' => 5],
            ['desa' => 'Serule', 'kecamatan_id' => 5],
            ['desa' => 'Wakil Jalil', 'kecamatan_id' => 5],
            ['desa' => 'Wihlah Setie', 'kecamatan_id' => 5],


            ['desa' => 'Arul Gading', 'kecamatan_id' => 12],
            ['desa' => 'Berawang Gading', 'kecamatan_id' => 12],
            ['desa' => 'Blang Kekumur', 'kecamatan_id' => 12],
            ['desa' => 'Celala', 'kecamatan_id' => 12],
            ['desa' => 'Cibro', 'kecamatan_id' => 12],
            ['desa' => 'Kuyun', 'kecamatan_id' => 12],
            ['desa' => 'Kuyun Toa', 'kecamatan_id' => 12],
            ['desa' => 'Kuyun Uken', 'kecamatan_id' => 12],
            ['desa' => 'Makmur', 'kecamatan_id' => 12],
            ['desa' => 'Melala', 'kecamatan_id' => 12],
            ['desa' => 'Paya Kolak', 'kecamatan_id' => 12],
            ['desa' => 'Ramung Ara', 'kecamatan_id' => 12],
            ['desa' => 'Sepakat', 'kecamatan_id' => 12],
            ['desa' => 'Tanoh Depet', 'kecamatan_id' => 12],
            ['desa' => 'Uning Berawang Ramung', 'kecamatan_id' => 12],


            ['desa' => 'Berawang Dewal', 'kecamatan_id' => 14],
            ['desa' => 'Bukit Sari', 'kecamatan_id' => 14],
            ['desa' => 'Bukit Kemuning', 'kecamatan_id' => 14],
            ['desa' => 'Gegarang', 'kecamatan_id' => 14],
            ['desa' => 'Jeget Ayu', 'kecamatan_id' => 14],
            ['desa' => 'Jagong Jeget', 'kecamatan_id' => 14],
            ['desa' => 'Merah Said', 'kecamatan_id' => 14],
            ['desa' => 'Paya Dedep', 'kecamatan_id' => 14],
            ['desa' => 'Paya Tungel', 'kecamatan_id' => 14],
            ['desa' => 'Telege Sari', 'kecamatan_id' => 14],


            ['desa' => 'Jongok Meluem', 'kecamatan_id' => 7],
            ['desa' => 'Lot Kala', 'kecamatan_id' => 7],
            ['desa' => 'Mendale', 'kecamatan_id' => 7],
            ['desa' => 'Pedekok', 'kecamatan_id' => 7],
            ['desa' => 'Pinangan', 'kecamatan_id' => 7],
            ['desa' => 'Bukit Sama', 'kecamatan_id' => 7],
            ['desa' => 'Kelupak Mata', 'kecamatan_id' => 7],
            ['desa' => 'Paya Reje Tamidelem', 'kecamatan_id' => 7],
            ['desa' => 'Paya Tumpi', 'kecamatan_id' => 7],
            ['desa' => 'Bukit', 'kecamatan_id' => 7],
            ['desa' => 'Gunung Balohen', 'kecamatan_id' => 7],
            ['desa' => 'Paya Tumpi Baru', 'kecamatan_id' => 7],

            ['desa' => 'Bah', 'kecamatan_id' => 11],
            ['desa' => 'Belang Mancung', 'kecamatan_id' => 11],
            ['desa' => 'Bintang Pepara', 'kecamatan_id' => 11],
            ['desa' => 'Burlah', 'kecamatan_id' => 11],
            ['desa' => 'Buter', 'kecamatan_id' => 11],
            ['desa' => 'Cang Duri', 'kecamatan_id' => 11],
            ['desa' => 'Gelumpang Payung', 'kecamatan_id' => 11],
            ['desa' => 'Jaluk', 'kecamatan_id' => 11],
            ['desa' => 'Kala Ketol', 'kecamatan_id' => 11],
            ['desa' => 'Karang Ampar', 'kecamatan_id' => 11],
            ['desa' => 'Kekuyang', 'kecamatan_id' => 11],
            ['desa' => 'Kute Gelime', 'kecamatan_id' => 11],
            ['desa' => 'Pantan Penyo', 'kecamatan_id' => 11],
            ['desa' => 'Pantan Reduk', 'kecamatan_id' => 11],
            ['desa' => 'Pondok Balik', 'kecamatan_id' => 11],
            ['desa' => 'Rejewali', 'kecamatan_id' => 11],
            ['desa' => 'Serempah', 'kecamatan_id' => 11],

            ['desa' => 'Atu Gogop', 'kecamatan_id' => 9],
            ['desa' => 'Balik', 'kecamatan_id' => 9],
            ['desa' => 'Buter Balik', 'kecamatan_id' => 9],
            ['desa' => 'Dedingin', 'kecamatan_id' => 9],
            ['desa' => 'Kute Panang', 'kecamatan_id' => 9],
            ['desa' => 'Lukup Sbun', 'kecamatan_id' => 9],
            ['desa' => 'Pantan Sile', 'kecamatan_id' => 9],
            ['desa' => 'Ratawali', 'kecamatan_id' => 9],
            ['desa' => 'Segene Balik', 'kecamatan_id' => 9],
            ['desa' => 'Tapak Moge', 'kecamatan_id' => 9],
            ['desa' => 'Tawar Miko', 'kecamatan_id' => 9],
            ['desa' => 'Tawardi', 'kecamatan_id' => 9],
            ['desa' => 'Timang Rasa', 'kecamatan_id' => 9],
            ['desa' => 'Wih Nongkal', 'kecamatan_id' => 9],

            ['desa' => 'Asir-asir', 'kecamatan_id' => 6],
            ['desa' => 'Asir-asir Asia', 'kecamatan_id' => 6],
            ['desa' => 'Bale Atu', 'kecamatan_id' => 6],
            ['desa' => 'Bujang', 'kecamatan_id' => 6],
            ['desa' => 'Hakim Bale', 'kecamatan_id' => 6],
            ['desa' => 'Kute Nireje', 'kecamatan_id' => 6],
            ['desa' => 'Takengon Barat', 'kecamatan_id' => 6],
            ['desa' => 'Takengon Timur', 'kecamatan_id' => 6],
            ['desa' => 'Gunung Suku', 'kecamatan_id' => 6],
            ['desa' => 'Kenawat', 'kecamatan_id' => 6],
            ['desa' => 'Pedemun One-one', 'kecamatan_id' => 6],
            ['desa' => 'Rawe', 'kecamatan_id' => 6],
            ['desa' => 'Toweren Antara', 'kecamatan_id' => 6],
            ['desa' => 'Toweren Toa', 'kecamatan_id' => 6],
            ['desa' => 'Toweren Uken', 'kecamatan_id' => 6],

            ['desa' => 'Antara', 'kecamatan_id' => 3],
            ['desa' => 'Arul Item', 'kecamatan_id' => 3],
            ['desa' => 'Delung Sekinel', 'kecamatan_id' => 3],
            ['desa' => 'Despot Linge', 'kecamatan_id' => 3],
            ['desa' => 'Gelampang Gading', 'kecamatan_id' => 3],
            ['desa' => 'Gemboyah', 'kecamatan_id' => 3],
            ['desa' => 'Gewat', 'kecamatan_id' => 3],
            ['desa' => 'Ise-Ise', 'kecamatan_id' => 3],
            ['desa' => 'Jamat', 'kecamatan_id' => 3],
            ['desa' => 'Kemerleng', 'kecamatan_id' => 3],
            ['desa' => 'Kute Baru', 'kecamatan_id' => 3],
            ['desa' => 'Kute Keramil', 'kecamatan_id' => 3],
            ['desa' => 'Kute Rayang', 'kecamatan_id' => 3],
            ['desa' => 'Kute Reje', 'kecamatan_id' => 3],
            ['desa' => 'Kute Riyem', 'kecamatan_id' => 3],
            ['desa' => 'Kute Robel', 'kecamatan_id' => 3],
            ['desa' => 'Mungkur', 'kecamatan_id' => 3],
            ['desa' => 'Linge', 'kecamatan_id' => 3],
            ['desa' => 'Lumut', 'kecamatan_id' => 3],
            ['desa' => 'Owaq', 'kecamatan_id' => 3],
            ['desa' => 'Pantan Reduk', 'kecamatan_id' => 3],
            ['desa' => 'Pantan Nangka', 'kecamatan_id' => 3],
            ['desa' => 'Penarun', 'kecamatan_id' => 3],
            ['desa' => 'Reje Payung', 'kecamatan_id' => 3],
            ['desa' => 'Simpang Tige Uning', 'kecamatan_id' => 3],
            ['desa' => 'Umang', 'kecamatan_id' => 3],

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

            ['desa' => 'Arul Pertik', 'kecamatan_id' => 13],
            ['desa' => 'Atu Singkih', 'kecamatan_id' => 13],
            ['desa' => 'Kerawang', 'kecamatan_id' => 13],
            ['desa' => 'Kuala Rawa', 'kecamatan_id' => 13],
            ['desa' => 'Lut Jaya', 'kecamatan_id' => 13],
            ['desa' => 'Mekar Maju', 'kecamatan_id' => 13],
            ['desa' => 'Merandeh Paya', 'kecamatan_id' => 13],
            ['desa' => 'Pantan Bener', 'kecamatan_id' => 13],
            ['desa' => 'Pantan Tengah', 'kecamatan_id' => 13],
            ['desa' => 'Paya Tampu', 'kecamatan_id' => 13],
            ['desa' => 'Pilar', 'kecamatan_id' => 13],
            ['desa' => 'Pilar Jaya', 'kecamatan_id' => 13],
            ['desa' => 'Pilar Wih Kiri', 'kecamatan_id' => 13],
            ['desa' => 'Rusip', 'kecamatan_id' => 13],
            ['desa' => 'Tanjung', 'kecamatan_id' => 13],
            ['desa' => 'Tirmi Ara', 'kecamatan_id' => 13],


            ['desa' => 'Arul Gele', 'kecamatan_id' => 10],
            ['desa' => 'Arul Kumer', 'kecamatan_id' => 10],
            ['desa' => 'Arul Kumer Barat', 'kecamatan_id' => 10],
            ['desa' => 'Arul Kumer Selatan', 'kecamatan_id' => 10],
            ['desa' => 'Arul Kumer Timur', 'kecamatan_id' => 10],
            ['desa' => 'Arul Putih', 'kecamatan_id' => 10],
            ['desa' => 'Arul Relem', 'kecamatan_id' => 10],
            ['desa' => 'Bius Utama', 'kecamatan_id' => 10],
            ['desa' => 'Burni Bius', 'kecamatan_id' => 10],
            ['desa' => 'Burni Bius Baru', 'kecamatan_id' => 10],
            ['desa' => 'Genting Gerbang', 'kecamatan_id' => 10],
            ['desa' => 'Gunung Singit', 'kecamatan_id' => 10],
            ['desa' => 'Jerata', 'kecamatan_id' => 10],
            ['desa' => 'Mekar Indah', 'kecamatan_id' => 10],
            ['desa' => 'Mulie Jadi', 'kecamatan_id' => 10],
            ['desa' => 'Paya Beke', 'kecamatan_id' => 10],
            ['desa' => 'Paya Pelu', 'kecamatan_id' => 10],
            ['desa' => 'Pepayungen Angkup', 'kecamatan_id' => 10],
            ['desa' => 'Rebe Gedung', 'kecamatan_id' => 10],
            ['desa' => 'Remesen', 'kecamatan_id' => 10],
            ['desa' => 'Reremal', 'kecamatan_id' => 10],
            ['desa' => 'Rutih', 'kecamatan_id' => 10],
            ['desa' => 'Sanehen', 'kecamatan_id' => 10],
            ['desa' => 'Semelit Mutiara', 'kecamatan_id' => 10],
            ['desa' => 'Simpang Kemili', 'kecamatan_id' => 10],
            ['desa' => 'Tenebuk Kampung Baru', 'kecamatan_id' => 10],
            ['desa' => 'Terang Engon', 'kecamatan_id' => 10],
            ['desa' => 'Wih Bersih', 'kecamatan_id' => 10],
            ['desa' => 'Wih Pesam', 'kecamatan_id' => 10],
            ['desa' => 'Wih Porak', 'kecamatan_id' => 10],
            ['desa' => 'Wih Sagi Indah', 'kecamatan_id' => 10],
            ['desa' => 'Wihni Bakong', 'kecamatan_id' => 10],
            ['desa' => 'Wihni Durin', 'kecamatan_id' => 10],








        ]);
    }
}
