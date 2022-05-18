<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TravelStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=1; $i<10; $i++){
            DB::table('station_travel')->insert([
                'travel_id' => 1,
                'station_id' => $i,
                'passengers_on_board' => 0,
                'firstClass_price' => 500,
                'secondClass_price' => 250,
                'firstClass_passengers_on_board' => 0,
                'secondClass_passengers_on_board' => 0,
                'arrival_time' => Carbon::now()->toDateTimeString(),
            ]);
        }
    }
}
