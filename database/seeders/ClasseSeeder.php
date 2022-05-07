<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('classes')->insert([
                'travel_id' => 1,
                'firstClass_limitPlaces' => 300,
                'secondClass_limitPlaces' => 400,
                'firstClass_vacancies' => 100,
                'secondClass_vacancies' => 100
        ]);
    }
}
