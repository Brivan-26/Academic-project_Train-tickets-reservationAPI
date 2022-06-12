<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Travel;
use App\Http\Repositories\ReservationRepository;
class TravelStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $res = new ReservationRepository();
        for($i=1; $i<6; $i++){
            $travel = Travel::find($i);
            if($i==1){
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => $travel->departure_station,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => 6,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => 7,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => $travel->arrival_station,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                foreach($travel->stations as $station){
                    $id = $station->id;
                    $prices = $res->pricing0($i, $travel->departure_station, $id);
                    DB::table('station_travel')->where('travel_id', $i)
                                               ->where('station_id', $station->id)->update([
                        'firstClass_price' => $prices['data']['F'] / 100,
                        'secondClass_price' => $prices['data']['S'] / 100,
                        'arrival_time' => $res->arrivalTime($i, $travel->departure_station, $id)
                    ]);
                }
            }
            else if($i==2){
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => $travel->departure_station,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => 6,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => $travel->arrival_station,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                foreach($travel->stations as $station){
                    $id = $station->id;
                    $prices = $res->pricing0($i, $travel->departure_station, $id);
                    DB::table('station_travel')->where('travel_id', $i)
                                               ->where('station_id', $station->id)->update([
                        'firstClass_price' => $prices['data']['F'] / 100,
                        'secondClass_price' => $prices['data']['S'] / 100,
                        'arrival_time' => $res->arrivalTime($i, $travel->departure_station, $id)
                    ]);
                }
            }
            else if ($i==3){
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => $travel->departure_station,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => 8,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => 10,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => $travel->arrival_station,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                foreach($travel->stations as $station){
                    $id = $station->id;
                    $prices = $res->pricing0($i, $travel->departure_station, $id);
                    DB::table('station_travel')->where('travel_id', $i)
                                               ->where('station_id', $station->id)->update([
                        'firstClass_price' => $prices['data']['F'] / 100,
                        'secondClass_price' => $prices['data']['S'] / 100,
                        'arrival_time' => $res->arrivalTime($i, $travel->departure_station, $id)
                    ]);
                }
            }
            else if($i==4){
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => $travel->departure_station,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => 5,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => $travel->arrival_station,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                foreach($travel->stations as $station){
                    $id = $station->id;
                    $prices = $res->pricing0($i, $travel->departure_station, $id);
                    DB::table('station_travel')->where('travel_id', $i)
                                               ->where('station_id', $station->id)->update([
                        'firstClass_price' => $prices['data']['F'] / 100,
                        'secondClass_price' => $prices['data']['S'] / 100,
                        'arrival_time' => $res->arrivalTime($i, $travel->departure_station, $id)
                    ]);
                }
            }
            else if($i==5){
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => $travel->departure_station,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => 10,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => 1,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => 5,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                DB::table('station_travel')->insert([
                    'travel_id' => $travel->id,
                    'station_id' => $travel->arrival_station,
                    'passengers_on_board' => 0,
                    'firstClass_price' => 0,
                    'secondClass_price' => 0,
                    'firstClass_passengers_on_board' => 0,
                    'secondClass_passengers_on_board' => 0,
                    'arrival_time' => Carbon::now()->toDateTimeString(),
                ]);
                foreach($travel->stations as $station){
                    $id = $station->id;
                    $prices = $res->pricing0($i, $travel->departure_station, $id);
                    DB::table('station_travel')->where('travel_id', $i)
                                               ->where('station_id', $station->id)->update([
                        'firstClass_price' => $prices['data']['F'] / 100,
                        'secondClass_price' => $prices['data']['S'] / 100,
                        'arrival_time' => $res->arrivalTime($i, $travel->departure_station, $id)
                    ]);
                }
            }
        }
    }
}
