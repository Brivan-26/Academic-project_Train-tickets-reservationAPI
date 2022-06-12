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
        DB::table('travels')->insert([
            'arrival_station' => 5, //beni mansour bejaia
            'departure_station' => 2, //tlemcen
            'departure_time' => Carbon::create(2022, 06, 15, 14, 30, 00)->toDateTimeString(),
            'distance' => 700,
            'estimated_duration' => 300,
            'description' => '',
            'status' => 'pending',
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
        DB::table('travels')->insert([
            'arrival_station' => 3, //oran
            'departure_station' => 7, //gare agha alger
            'departure_time' => Carbon::create(2022, 06, 20, 20, 30, 00)->toDateTimeString(),
            'distance' => 400,
            'estimated_duration' => 180,
            'description' => '',
            'status' => 'pending',
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
        DB::table('travels')->insert([
            'arrival_station' => 1, //setif
            'departure_station' => 9, //souk ahras
            'departure_time' => Carbon::create(2022, 07, 01, 10, 00, 00)->toDateTimeString(),
            'distance' => 350,
            'estimated_duration' => 120,
            'description' => 'test',
            'status' => 'pending',
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
        DB::table('travels')->insert([
            'arrival_station' => 4, // bejaia
            'departure_station' => 7, //gare agha alger
            'departure_time' => Carbon::create(2022, 06, 23, 17, 30, 00)->toDateTimeString(),
            'distance' => 250,
            'estimated_duration' => 100,
            'description' => 'test',
            'status' => 'pending',
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
        DB::table('travels')->insert([
            'arrival_station' => 4, //bejaia
            'departure_station' => 8, // ramdane djamel annaba
            'departure_time' => Carbon::create(2022, 06, 30, 20, 00, 00)->toDateTimeString(),
            'distance' => 150,
            'estimated_duration' => 70,
            'description' => 'test',
            'status' => 'pending',
            'created_at' => Carbon::now()->toDateTimeString()
        ]);
    }
}
