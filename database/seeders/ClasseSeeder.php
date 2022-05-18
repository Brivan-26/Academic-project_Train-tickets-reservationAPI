<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Travel;
class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(Travel::all() as $travel) {

            DB::table('classes')->insert([
                    'travel_id' => $travel->id,
                    'firstClass_limitPlaces' => 300,
                    'secondClass_limitPlaces' => 400,
            ]);
        }
    }
}
