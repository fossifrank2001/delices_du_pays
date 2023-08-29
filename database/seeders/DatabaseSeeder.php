<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\StatutSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            StatutSeeder::class,
            UserSeeder::class,
            DeliveryHoursSeeder::class,
            MenusSeeder::class
            // Ajoutez d'autres seeders ici si nécessaire
        ]);
    }
}
