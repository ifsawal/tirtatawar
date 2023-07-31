<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AlamatSeeder;
use Database\Seeders\GolonganSeeder;
use Database\Seeders\PelangganSeeder;

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
        $this->call([PelangganSeeder::class]);
        
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
