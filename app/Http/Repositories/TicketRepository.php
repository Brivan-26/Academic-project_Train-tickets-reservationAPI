<?php
namespace App\Http\Repositories;

use App\Models\Ticket;
use App\Models\Travel;
use Illuminate\Http\Request;

class TicketRepository {
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

    public function validateTicket(Request $request){
        $response = [];
        $tickets = Travel::find($request->travelId)->tickets;
        $ticket = $tickets->where("qrcode_token", $request->qrcode_token)->first();
        if($ticket==null){
            $response['success'] = false;
            $response['errors'] = "No such ticket";
        } else if($ticket->validated==false){
            $ticket->validated = true;
            $ticket->save();
            $response['success'] = true;
            $response['data'] = $ticket;
        } else {
            $response['success'] = false;
            $response['errors'] = "Ticket already validated";
        }
        return $response;
    }
}
