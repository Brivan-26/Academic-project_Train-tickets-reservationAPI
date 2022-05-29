<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource as UserResource;
use App\Http\Resources\ReviewResource as ReviewResource;
use App\Http\Resources\TravelResource as TravelResource;
use App\Http\Resources\StationResource as StationResource;
use App\Http\Resources\TicketResource as TicketResource;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\ReviewRepository;
use App\Http\Repositories\TravelRepository;
use App\Http\Repositories\StationRepository;
use App\Http\Repositories\TicketRepository;
class AdminDashboardController extends BaseController
{
    private $userRepository;
    private $travelRepository;
    private $stationRepository;
    private $ticketRepository;
    private $reviewRepository;

    public function __construct(UserRepository $userRepository,
                TravelRepository $travelRepository, StationRepository $stationRepository,
                TicketRepository $ticketRepository, ReviewRepository $reviewRepository)
    {
        $this->userRepository = $userRepository;
        $this->travelRepository = $travelRepository;
        $this->stationRepository = $stationRepository;
        $this->ticketRepository = $ticketRepository;
        $this->reviewRepository = $reviewRepository;

    }

    public function index()
    {
        $users = $this->userRepository->all();
        return $this->sendResponse(UserResource::collection($users), 'succefully logged the necessary data!');
    }

    public function delete_user($id)
    {
        $response = $this->userRepository->deleteById($id);
        if($response["success"]) {
            return $this->sendResponse(UserResource::collection($response["data"]), 'User deleted succefully');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }
    }

    public function restore_user($id)
    {
        $response = $this->userRepository->restoreById($id);
        if($response["success"]) {
            return $this->sendResponse(new UserResource($response["data"]), 'User restored succefully');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }
    }

    public function destory_user($id)
    {
        $response = $this->userRepository->destoryById($id);
        if($response["success"]) {
            return $this->sendResponse(new UserResource($response["data"]), 'User permananly deleted succefully');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }
    }

    public function upgradeRole_user(Request $request, $id)
    {
        $response = $this->userRepository->upgradeRole($request, $id);
        if($response["success"]) {
            return $this->sendResponse(new UserResource($response["data"]), 'User role is succefully updated');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }
    }


    public function travels()
    {
        $travels = $this->travelRepository->all();
        return $this->sendResponse(TravelResource::collection($travels), 'Travels succefully retreived!');
    }

    public function travel_create(Request $request)
    {
        $response = $this->travelRepository->createByRequest($request);
        if($response["success"]) {
            return $this->sendResponse(new TravelResource($response["data"]), 'Succefully created the travel!');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }
    }

    public function travel_update(Request $request, $id)
    {
        $response = $this->travelRepository->updateByRequest($request, $id);
        if($response["success"]) {
            return $this->sendResponse(new TravelResource($response["data"]), 'Travel succefully updated!');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }
    }

    public function travel_delete($id)
    {
        $response = $this->travelRepository->deleteById($id);
        if($response["success"]) {
            return $this->sendResponse(new TravelResource($response["data"]), 'Travel succefully deleted!');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }
    }

    public function stations()
    {
        $stations = $this->stationRepository->all();
        return $this->sendResponse(StationResource::collection($stations), 'Succefully retreived all the stations!');
    }

    public function station_create(Request $request)
    {
        $station = $this->stationRepository->createByRequest($request);
        if($station) {
            return $this->sendResponse(new StationResource($station), 'The station is succefully created!');
        }else {
            return $this->sendError('Something went wrong!');
        }
    }

    public function station_update(Request $request, $id)
    {
        $response = $this->stationRepository->updateByRequest($request, $id);
        if($response["success"]) {
            return $this->sendResponse(new StationResource($response["data"]), 'Station is succefully updated!');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }

    }

    public function station_delete($id) {
        $response = $this->stationRepository->deleteById($id);
        if($response["success"]) {
            return $this->sendResponse(new StationResource($response["data"]), 'Station is succefully deleted!');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }
    }

    public function station_restore($id)
    {
        $response = $this->stationRepository->restoreById($id);
        if($response["success"]) {
            return $this->sendResponse(new StationResource($response["data"]), 'Station is sucefully restored!');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }
    }

    public function station_destroy($id) {
        $response = $this->stationRepository->destroyById($id);

        if($response["success"]) {
            return $this->sendResponse(new StationResource($response["data"]), 'Station is succefully permanantly deleted!');
        }else {
            return $this->sendError('Something went wrong!', $response["errors"]);
        }
    }


    public function tickets()
    {
        $tickets = $this->ticketRepository->all();
        if($tickets) {
            return $this->sendResponse(TicketResource::collection($tickets), 'Tickets succefully retrieved!');
        }else {
            return $this->sendError('Something went wrong!');
        }
    }

    public function tickets_nonExpired()
    {
        $tickets = $this->ticketRepository->getTicketsNonExpired();

        if($tickets) {
            return $this->sendResponse(TicketResource::collection($tickets), 'Succefully retreived tickets!');
        }else {
            return $this->sendError('Something went wrong!');
        }
    }

    public function ticket_get($id)
    {
        $response = $this->ticketRepository->getById($id);
        if($response["success"]) {
            return $this->sendResponse(new TicketResource($response["data"]), 'Succefully retrieved the ticket!');
        }else {
            return $this->sendError('Something went wrong!',$response["errors"]);
        }
    }

    public function reviews_get($id){
        $response = $this->reviewRepository->get_reviewsByTravelId($id);
        if($response['success']){
            return $this->sendResponse(ReviewResource::collection($response['data']),
            "Reviews retreived successfully");
        }
        return $this->sendError("Something went wrong !",$response['errors']);
    }

    public function cancel_travel($travelId){
        $response = $this->travelRepository->cancelByRequest($travelId);
        if($response['success']){
            return $this->sendResponse(new TravelResource($response['data']),
                                        "Travel deleted successfully");
        } else {
            return $this->sendError("Something went wrong", $response['errors']);
        }
    }
}
