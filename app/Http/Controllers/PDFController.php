<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Ticket;

class PDFController extends Controller
{
    public function downloadTicketAsPDF($ticketId){
        $tickets = Ticket::all()->where('id','>', $ticketId);
        $data = [
            'tickets' => $tickets
        ];

        $pdf = PDF::loadView('pdf', $data);
        return $pdf->download('Ticket.pdf');
    }
}
