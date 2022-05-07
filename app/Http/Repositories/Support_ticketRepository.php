<?php
namespace App\Http\Repositories;

use App\Models\User;
use App\Models\Support_ticket;
use App\Models\Support_tickets_answer;

class Support_ticketRepository{

    private function is_supportTicketOwner(Support_ticket $ticket){

        return (auth()->user()->id==$ticket->user_id||auth()->user()->id==$ticket->assigned_to);
    }

    public function all(){
        return Support_ticket::all();
    }

    public function createByRequest($request){
        $response = [];
        $ticket =Support_ticket::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'user_id' => auth()->user()->id,
            'is_active' => 0,
            'assigned_to' => null
        ]);
        $response['success'] = true;
        $response['data'] = $ticket;
        return $response;
    }

    public function assignById($id){
        $reponse = [];
        $ticket = Support_ticket::find($id);
        if(!$ticket){
            $reponse['success'] = false ;
            $reponse['errors'] = "No support found with specified id !";
        }
        else if($ticket->assigned_to){
            $reponse['success'] = false ;
            $reponse['errors'] = "Support ticket already assigned !";
        }
        else {
            $ticket->assigned_to = auth()->user()->id;
            $ticket->save();
            $reponse['success'] = true ;
            $reponse['data'] = $ticket;
        }
        return $reponse;
    }

    public function render_inactiveById($id){
        $response = [];
        $ticket = Support_ticket::find($id);
        if(!$ticket){
            $reponse['success'] = false ;
            $reponse['errors'] = "No support found with specified id !";
        }
        else if($ticket->assigned_to != auth()->user()->id){
            $reponse['success'] = false ;
            $reponse['errors'] = "U dont have rights for this action";
        }
        else {
            $ticket->is_active = false;
            $ticket->save();
            $reponse['success'] = true ;
            $reponse['data'] = $ticket;
        }
        return $reponse;
    }

    public function get_supportTickets(){
        return auth()->user()->support_tickets;
    }
    
    public function add_answerByRequest($request, $id){
        $ticket = Support_ticket::find($id);
        if(!$ticket){
            $reponse['success'] = false ;
            $reponse['errors'] = "No support found with specified id !";
        }
        else if(! $this->is_supportTicketOwner($ticket) ){
            $reponse['success'] = false ;
            $reponse['errors'] = "U dont have rights for this action";
        }
        else{   
            Support_tickets_answer::create([
                'support_ticket_id' => $id,
                'from' => auth()->user()->id,
                'to' => $request['to'],
                'content' => $request['content']
            ]);
            $reponse['success'] = true ;
            $reponse['data'] = $ticket->answers;
        }
        return $reponse;
    }

    public function get_answersById($id){
        $ticket = Support_ticket::find($id);
        if(!$ticket){
            $reponse['success'] = false ;
            $reponse['errors'] = "No support found with specified id !";
        }
        else if(! $this->is_supportTicketOwner($ticket) ){
            $reponse['success'] = false ;
            $reponse['errors'] = "U dont have rights for this action";
        }
        else{
            $reponse['success'] = true ;
            $reponse['data'] = $ticket->answers;
        }
        return $reponse;
    }

}