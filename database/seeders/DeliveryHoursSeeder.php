<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryHoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed data for delivery_hours table
        $deliveryHours = [
            ['HRS_TIME' => '09:00:00'],
            ['HRS_TIME' => '12:00:00'],
            ['HRS_TIME' => '15:00:00'],
        ];
        DB::table('delivery_hours')->insert($deliveryHours);
    }
}
