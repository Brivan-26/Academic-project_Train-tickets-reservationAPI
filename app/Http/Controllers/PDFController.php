<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Ticket;
use App\Models\Travel;

class PDFController extends Controller
{
    public function downloadTicketAsPDF(Request $request){
        $id = auth('sanctum')->id();
        $tickets = collect();
        $travelTickets = Travel::find((int)$request->travel_id)->tickets;
        foreach($request->passengers as $passenger){
            $name = $passenger['first_name'].' '.$passenger['last_name'];
            foreach($travelTickets as $ticket){
                if($ticket->user_id!=$id){
                    continue;
                } else if($ticket->passenger_name==$name){
                        $tickets->push($ticket);
                }
            }
        }
        if(count($tickets)==0){
            return response()->json([
                'success'=> false,
                'message'=> 'No tickets found for specified data'
            ]);
        }
        $data = [
            'tickets' => $tickets
        ];

        $pdf = PDF::loadView('pdf', $data);
        if(count($tickets)>1){
            return $pdf->download('Tickets.pdf');
        } else {
            return $pdf->download('Ticket.pdf');
        }

    }
}
