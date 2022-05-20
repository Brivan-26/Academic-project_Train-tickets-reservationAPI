<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Ticket;

class PDFController extends Controller
{
    public function downloadTicketAsPDF(){
        $tickets = Ticket::all();
        $data = [
            'tickets' => $tickets
        ];
        $pdf = PDF::loadView('pdf', $data);
        if(count($tickets)>1){
            return $pdf->download('Tickets', '.pdf');
        } else {
            return $pdf->download('Ticket', '.pdf');
        }

    }
}
