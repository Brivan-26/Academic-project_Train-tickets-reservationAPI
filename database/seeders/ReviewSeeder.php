<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i<=5; $i++) {
            DB::table('reviews')->insert([
                'user_id' => '1', 
                'travel_id' => '1',
                'passenger_name' => 'test test',
                'content' => "best travel evr",
                'rating' => 5,
            ]);
        }
    }
}
