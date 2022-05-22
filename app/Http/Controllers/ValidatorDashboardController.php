<?php

namespace App\Http\Controllers;

use App\Http\Repositories\TicketRepository;
use App\Http\Repositories\TravelRepository;
use App\Http\Resources\TicketResource;
use App\Http\Resources\DetailedTravelResource;
use Illuminate\Http\Request;

class ValidatorDashboardController extends BaseController
{
    private $ticketRepository;
    private $travelRepository;
    public function __construct(TicketRepository $ticketRepository,
                                TravelRepository $travelRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->travelRepository = $travelRepository;
    }

    public function get_travelTickets($id){
        $response = $this->ticketRepository->getByTravelId($id);
        if($response['success']){
            return $this->sendResponse(TicketResource::collection($response['data']),
                                        "Tickets retreived successfully");
        }
        else{
            return $this->sendError("Something went wrong",$response['errors']);
        }
    }

    public function validateTicket(Request $request)
    {
        $response = $this->ticketRepository->validateTicket($request);
        if ($response['success']) {
            return $this->sendResponse(new TicketResource($response['data']),
                    'TICKET_VALIDATED_SUCCESSFULLY');
        }
        return $this->sendError('SOMETHING_WENT_WRONG', $response['errors']);
    }

    public function get_todayTravels(Request $request){
        $response = $this->travelRepository->travelOfTheDay($request);
        if($response['success']) {
            return $this->sendResponse(DetailedTravelResource::collection($response['data']),
                                        "Travels retreived successfully");
        }
        else {
            return $this->sendError("Something went wrong",$response['errors']);
        }
    }
}
