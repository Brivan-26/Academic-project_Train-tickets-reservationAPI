<?php
namespace App\Http\Repositories;
use App\Models\Ticket;
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
}