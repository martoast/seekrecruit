<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClientSeeder::class,     // must run first — users + positions FK to clients
            UserSeeder::class,
            PositionSeeder::class,
            ApplicationSeeder::class,
            ReferralSeeder::class,
        ]);
    }
}
