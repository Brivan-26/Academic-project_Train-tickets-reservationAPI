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
        $this->call(StationSeeder::class);
        $this->call(TravelSeeder::class);
        $this->call(Station_photoSeeder::class);
        $this->call(ReviewSeeder::class);
        $this->call(TravelStationSeeder::class);
        $this->call(ClasseSeeder::class);
        $this->call(TicketSeeder::class);
    }
}
