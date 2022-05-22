<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;

use App\Http\Repositories\ReservationRepository;

class ReservationController extends BaseController
{
    private $reservationRepository;
    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function AvAndP(Request $request){
        $response = $this->reservationRepository->AvAndP0($request);
        if($response['success']){
            $places = $response['data'];
            return $this->sendResponse($places, "There are {$places} places available");
        } else {
            return $this->sendError($response['errors']);
        }
    }

    public function PassThroughTravels(Request $request){
        $response = $this->reservationRepository->PassThroughTravels0($request);
        if($response['success']){
            return $this->sendResponse($response['data'], "Travels found for this route");
        } else {
            return $this->sendError($response['errors']);
        }
    }

    public function AllTravels(){
        return $this->reservationRepository->AllTravels0();
    }

    public function pricing($travelId, $boarding, $landing){
        $response = $this->reservationRepository->pricing0($travelId, $boarding, $landing);
        if($response['success']){
            return $this->sendResponse($response['data'], "Prices for the given travel");
        } else {
            return $this->sendError($response['errors']);
        }
    }
}
