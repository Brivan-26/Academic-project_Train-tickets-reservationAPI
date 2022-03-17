<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Travel;
use App\Models\Station;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i =1; $i<=20; $i++) {
            $randomUser = User::inRandomOrder()->first();
            $randomTravel = Travel::inRandomOrder()->first();
            $randomStation1 = Station::inRandomOrder()->first();
            $randomStation2 = Station::inRandomOrder()->first();
            Ticket::create([
                'user_id' => $randomUser->id,
                'travel_id' => $randomTravel->id,
                'passenger_name' => 'passenger',
                'travel_class' => 'A',
                'payment_method' => 'stripe',
                'payment_token' => Str::random(11),
                'validated' => 0,
                'boarding_station' => $randomStation1->id,
                'landing_station' => $randomStation2->id,
                'price' => 1000
            ]);
        }
    }
}
