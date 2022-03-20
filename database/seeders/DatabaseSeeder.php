<?php

namespace Database\Seeders;

use App\Models\Support_ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TravelSeeder::class);
        $this->call(StationSeeder::class);
        $this->call(Station_photoSeeder::class);
    }
}
