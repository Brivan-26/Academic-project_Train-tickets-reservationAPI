<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class TravelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i<=5; $i++) {
            DB::table('travels')->insert([
                'arrival_station' => 1, 
                'departure_station' => 5,
                'departure_time' => Carbon::now()->toDateTimeString(),
                'distance' => 12500,
                'estimated_duration' => 300,
                'description' => 'test',
                'status' => 'pending',
                'created_at' => Carbon::now()->toDateTimeString()
            ]);
        }
    }
}
