<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Station;
use App\Models\Station_photo;

class Station_photoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i<=30; $i++) {
            $randomStation = Station::inRandomOrder()->first();
            Station_photo::create([
                'station_id' => $randomStation->id,
                'photo_url' => 'random url'.$i
            ]);
        }
    }
}
