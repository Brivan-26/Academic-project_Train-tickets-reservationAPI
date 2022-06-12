<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Station;
use Illuminate\Support\Facades\DB;
class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stations')->insert([   //1
            'name' => 'Gare de Setif',
            'wilaya' => 'Setif'
        ]);
        DB::table('stations')->insert([
            'name' => "Gare de l'Université de Tlemcen", //2
            'wilaya' => 'Tlemcen'
        ]);
        DB::table('stations')->insert([ //3
            'name' => 'Gare de Oran',
            'wilaya' => 'Oran'
        ]);
        DB::table('stations')->insert([   //4
            'name' => 'Gare de Bejaia',
            'wilaya' => 'Bejaia'
        ]);
        DB::table('stations')->insert([  //5
            'name' => 'Gare de Beni Mansour',
            'wilaya' => 'Bejaia'
        ]);
        DB::table('stations')->insert([  //6
            'name' => "Gare d'Alger",
            'wilaya' => 'Alger'
        ]);
        DB::table('stations')->insert([  //7
            'name' => "Gare de l'Agha",
            'wilaya' => 'Alger'
        ]);
        DB::table('stations')->insert([  //8
            'name' => 'Gare de Ramdane Djamel',
            'wilaya' => 'Annaba'
        ]);
        DB::table('stations')->insert([ //9
            'name' => 'Gare de Souk-Ahras',
            'wilaya' => 'Souk-Ahras'
        ]);
        DB::table('stations')->insert([  //10
            'name' => 'Gare de Constantine',
            'wilaya' => 'Constantine'
        ]);
    }
}
