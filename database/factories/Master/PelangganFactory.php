<?php

namespace Database\Factories\Master;

use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Master\Pelanggan>
 */
class PelangganFactory extends Factory
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
            'nama' => $faker->name(),
            'nik' => $faker->nik(),
            'kk' => $faker->nik(),
            'lat' => $faker->latitude($min = -90, $max = 90),
            'long' => $faker->longitude($min = -180, $max = 180),
            'email' => $faker->userName() . $faker->numberBetween(1, 900000) . '@gmail.com',
            'password' => bcrypt(1234),
            'pdam_id' => 1,
            'desa_id' => $faker->randomDigitNotNull(),
            'user_id' => 1,
            'golongan_id' => $faker->numberBetween(1, 8),
            'wiljalan_id' => $faker->numberBetween(1, 25),
            'rute_id' => $faker->numberBetween(1, 7),
            'kode' => $faker->numberBetween(1, 9000),

        ];
    }
}
