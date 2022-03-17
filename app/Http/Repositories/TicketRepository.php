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
        $ticket = Ticket::find($ticketId);
        if($ticket) {
            return $ticket;
        }
        return null;
    }
}