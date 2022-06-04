<?php

namespace App\Http\Controllers;

use App\Http\Repositories\TicketRepository;
use App\Http\Repositories\TravelRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Resources\TicketResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\DetailedTravelResource;
use Illuminate\Http\Request;


class ValidatorDashboardController extends BaseController
{
    private $ticketRepository;
    private $travelRepository;
    private $userRepository;
    public function __construct(TicketRepository $ticketRepository,
                                TravelRepository $travelRepository, 
                                UserRepository $userRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->travelRepository = $travelRepository;
        $this->userRepository = $userRepository;
    }

    public function get_travelTickets($id){
        $response = $this->ticketRepository->getByTicketsByTravelId($id);
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

    public function get_todayTravels(){
        $response = $this->travelRepository->travelOfTheDay();
        if($response['success']) {
            return $this->sendResponse(DetailedTravelResource::collection($response['data']),
                                        "Travels retreived successfully");
        }
        else {
            return $this->sendError("Something went wrong",$response['errors']);
        }
    }

    public function update_infosNoPic(Request $request)
    {
        $response = $this->userRepository->update_userInfosNoPic($request);
        if ($response['success']){
            return $this->sendResponse(new UserResource($response['data']),
            "Account info updated successfully");
        }
        return $this->sendError("Something went wrong",$response['errors']);
    }
}
