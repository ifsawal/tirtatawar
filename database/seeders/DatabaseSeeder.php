<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Illuminate\Database\Seeder;
use Database\Seeders\BankSeeder;
use Database\Seeders\RuteSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AlamatSeeder;
use Database\Seeders\WilayahSeeder;
use Database\Seeders\GoldetilSeeder;
use Database\Seeders\GolonganSeeder;
use Database\Seeders\WiljalanSeeder;
use Database\Seeders\PelangganSeeder;
use Database\Seeders\PencatatanSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call([AlamatSeeder::class]);
        $this->call([PdamSeeder::class]);
        $this->call([UserSeeder::class]);
        $this->call([GolonganSeeder::class]);
        $this->call([RuteSeeder::class]);
        $this->call([WiljalanSeeder::class]);
        // $this->call([PelangganSeeder::class]);
        $this->call([PelangganDataAsliSeeder::class]);
        $this->call([PelangganDataAslike2Seeder::class]);
        $this->call([HpPelangganSeeder::class]);
        $this->call([PencatatanSeeder::class]);
        $this->call([GoldetilSeeder::class]);
        $this->call([WilayahSeeder::class]);
        $this->call([BankSeeder::class]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
