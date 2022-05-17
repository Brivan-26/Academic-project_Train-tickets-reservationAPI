<?php
namespace App\Http\Repositories;
use App\Models\Ticket;
use App\Models\Travel;

Class TicketRepository {
    public function all()
    {
        return Ticket::all();
    }

    public function getTicketsNonExpired()
    {
        $tickets = Ticket::whereHas('travel', function($q){
            $q->where('status', '!=', 'completed');
         })->get();
        
         return $tickets;
    }

    public function getById($ticketId)
    {
        $response = [];
        $ticket = Ticket::find($ticketId);
        if($ticket) {
            $response["success"] = true;
            $response["data"] = $ticket;
            return $response;
        }
        $response["success"] = false;
        $response["errors"] = 'Ticket not found!';
        return $response;
    }

    public function getByTravelId($id)
    {
        $response = [];
        $travel = Travel::find($id);
        if(!$travel) {
            $response["success"] = false;
            $response["errors"] = 'No travel found with specified id';
        }
        else{
            $response["success"] = true;
            $response["data"] = $travel->tickets;
        }
        return $response;
    }

    public function validateById($id){
        $response = [];
        $ticket = Ticket::find($id);
        if(!$ticket) {
            $response["success"] = false;
            $response["errors"] = 'No ticket found with specified id';
        }
        else{
            if($ticket->validated){
                $response["success"] = false;
                $response["errors"] = 'Ticket already validated';            
            }
            else{
                $ticket->validated = true;
                $ticket->save();
                $response["success"] = true;
                $response["data"] = $ticket;
            }
        }
        return $response;
    }
}