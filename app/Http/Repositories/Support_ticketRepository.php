<?php
namespace App\Http\Repositories;

use App\Models\User;
use App\Models\Support_ticket;
use App\Models\Support_tickets_answer;

class Support_ticketRepository{


    private function is_supportTicketOwner($id){
        $ticket = Support_ticket::find($id);
        if (!$ticket){
            return false;
        }
        return (auth()->user()->id==$ticket->user_id||auth()->user()->id==$ticket->assigned_to);
    }
    public function all(){
        return $support_tickets = Support_ticket::all();
    }

    public function createByRequest($request){
        $ticket =Support_ticket::create([
            'description' => $request['description'],
            'user_id' => auth()->user()->id,
            'is_active' => 0,
            'assigned_to' => null
        ]);
        return $ticket;
    }

    public function assignById($id){
        $ticket = Support_ticket::find($id);
        if(!$ticket){
            return null;
        }
        if($ticket->assigned_to){
            return null;
        }
        $ticket->assigned_to = auth()->user()->id;
        $ticket->save();
        return $ticket;
    }

    public function render_inactiveById($id){
        if(!$this->is_supportTicketOwner($id)){
            return null;
        }
        $ticket = Support_ticket::find($id);
        $ticket->is_active = 0;
        $ticket->save();
        return $ticket;
    }

    public function get_supportTickets(){
        return auth()->user()->support_tickets;
    }
    
    public function add_answerByRequest($request, $id){
        if(!$this->is_supportTicketOwner($id)){
            return null;
        }
        $ticket = Support_ticket::find($id);
        $answer = Support_tickets_answer::create([
            'support_ticket_id' => $id,
            'from' => auth()->user()->id,
            'to' => $request['to_id'],
            'content' => $request['content']
        ]); 
        return $this->get_answersById($id);
    }

    public function get_answersById($id){
        if(!$this->is_supportTicketOwner($id)){
            return null;
        }
        $ticket = Support_ticket::find($id);
        return $ticket->answers;
    }

}