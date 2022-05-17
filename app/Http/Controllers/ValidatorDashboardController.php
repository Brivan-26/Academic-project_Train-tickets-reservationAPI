<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\TicketRepository;
use App\Http\Repositories\TravelRepository;
use App\Http\Resources\TicketResource;
use App\Http\Resources\TravelResource;

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

    public function validate_ticket($id){
        $response = $this->ticketRepository->validateById($id);
        if($response['success']){
            return $this->sendResponse(new TicketResource($response['data']),
                                        "Ticket validated successfully");
        }
        else{
            return $this->sendError("Something went wrong",$response['errors']);
        }
    }

    public function get_todayTravels(){
        $response = $this->travelRepository->travelsOfTheDay();
        if($response['success']){
            return $this->sendResponse(TravelResource::collection($response['data']),
                                        "Travels retreived successfully");
        }
        else{
            return $this->sendError("Something went wrong",$response['errors']);
        }
    }
}
