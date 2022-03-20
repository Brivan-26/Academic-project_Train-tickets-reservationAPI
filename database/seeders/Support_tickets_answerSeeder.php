<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Support_ticket;

class Support_tickets_answerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        for($j=1; $j<6; $j++){
            $ticket = Support_ticket::find($j);
            for ($i=0; $i<5; $i++){
                DB::table('support_tickets_answers')->insert([
                    'support_ticket_id' => $j,
                    'from' => $ticket->user_id,
                    'to' => $ticket->assigned_to,
                   'content' =>'msg from '.$ticket->user_id.' to '.$ticket->assigned_to
                ]);
            }
        }
    }
}
