<?php

namespace Database\Factories\Master;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Pencatatan>
 */
class PencatatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $faker = FakerFactory::create('id_ID');
        return [

            'awal' => 0,
            'akhir' => 8,
            'pemakaian' => 8,
            'bulan' => 10,
            'tahun' => 2023,
            'pelanggan_id' => $faker->name(),
            'user_id_perubahan' => $faker->name(),


        ];
    }
}
