<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class Support_ticketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i<5; $i++) {
            DB::table('support_tickets')->insert([
                'user_id' => rand(1,20),
                'assigned_to' => rand(22,26),
                'description' => 'support_ticket'.$i,
                'title' => 'support_ticket'.$i,
            ]);
        }
        DB::table('support_tickets')->insert([
            'user_id' => rand(1,20),
            'description' => 'support_ticket6',
            'title' => 'support_ticket7',
        ]);
    }
}
