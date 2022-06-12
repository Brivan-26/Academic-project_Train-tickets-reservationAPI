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

    public function getByTicketsByTravelId($id)
    {
        $response = [];
        $travel = Travel::find($id);
        $id = auth('sanctum')->id();
        if($id!=$travel->validator_id){
            $response["success"] = false;
            $response["errors"] = "This travel isn't assigned to this validator";
            return $response;
        }
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

    public function getRevenue(){
        $response = [];
        $tickets = Ticket::all();
        $data = [
            "January" => 0,
            "February" => 0,
            "March" => 0,
            "April" => 0,
            "May" => 0,
            "June" => 0,
            "July" => 0,
            "August" => 0,
            "September" => 0,
        ];
        foreach($tickets as $ticket){
            $month = date("F", strtotime($ticket->created_at));
            $data[$month] +=1;
        }
        $response["success"] = true;
        $reponse["data"] = $data;
        return $reponse;
    }
}
