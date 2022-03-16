<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource as UserResource;
use App\Http\Resources\TravelResource as TravelResource;
use App\Http\Resources\StationResource as StationResource;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\TravelRepository;
use App\Http\Repositories\StationRepository;
class AdminDashboardController extends BaseController
{
    private $userRepository;
    private $travelRepository;
    private $stationRepository;
    public function __construct(UserRepository $userRepository, TravelRepository $travelRepository, StationRepository $stationRepository)
    {
        $this->userRepository = $userRepository;
        $this->travelRepository = $travelRepository;
        $this->stationRepository = $stationRepository;
    }

    public function index()
    {
        $users = $this->userRepository->all();
        return $this->sendResponse(UserResource::collection($users), 'succefully logged the necessary data!');
    }

    public function delete_user($id) 
    {
        $user = $this->userRepository->deleteById($id);
        if($user) {
            return $this->sendResponse(new UserResource($user), 'User deleted succefully');
        }else {
            return $this->sendError('User can not be found!');
        }
    }
    
    public function restore_user($id)
    {
        $user = $this->userRepository->restoreById($id);
        if($user) {
            return $this->sendResponse(new UserResource($user), 'User restored succefully');
        }else {
            return $this->sendError('User can not be found!');
        }
    }

    public function destory_user($id) 
    {
        $user = $this->userRepository->destoryById($id);
        if($user) {
            return $this->sendResponse(new UserResource($user), 'User permananly deleted succefully');
        }else {
            return $this->sendError('User can not be found!');
        }
    }

    public function upgradeRole_user(Request $request, $id)
    {
        $user = $this->userRepository->upgradeRole($request, $id);
        if($user) {
            return $this->sendResponse(new UserResource($user), 'User role is succefully updated');
        }else {
            return $this->sendError('Something went wrong!');
        }
    }


    public function travels()
    {
        $travels = $this->travelRepository->all();
        return $this->sendResponse(TravelResource::collection($travels), 'Travels succefully retreived!');
    }

    public function travel_create(Request $request)
    {
        $travel = $this->travelRepository->createByRequest($request);
        if($travel) {
            return $this->sendResponse(new TravelResource($travel), 'Succefully created the travel!');
        }else {
            return $this->sendError('Something went wrong!');
        }
    }

    public function travel_update(Request $request, $id)
    {
        $travel = $this->travelRepository->updateByRequest($request, $id);
        if($travel) {
            return $this->sendResponse(new TravelResource($travel), 'Travel succefully updated!');
        }else {
            return $this->sendError('Something went wrong!');
        }
    }

    public function travel_delete($id)
    {
        $travel = $this->travelRepository->deleteById($id);
        if($travel) {
            return $this->sendResponse(new TravelResource($travel), 'Travel succefully deleted!');
        }else {
            return $this->sendError('Something went wrong!');
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
        $station = $this->stationRepository->updateByRequest($request, $id);
        if($station) {
            return $this->sendResponse(new StationResource($station), 'Station is succefully updated!');
        }else {
            return $this->sendError('Something went wrong!');
        }

    }

    public function station_delete($id) {
        $station = $this->stationRepository->deleteById($id);
        if($station) {
            return $this->sendResponse(new StationResource($station), 'Station is succefully deleted!');
        }else {
            return $this->sendError('Something went wrong!');
        }
    }
   
    public function station_restore($id)
    {
        $station = $this->stationRepository->restoreById($id);
        if($station) {
            return $this->sendResponse(new StationResource($station), 'Station is sucefully restored!');
        }else {
            return $this->sendError('Something went wrong!');
        }
    }

    public function station_destroy($id) {
        $station = $this->stationRepository->destroyById($id);
        if($station) {
            return $this->sendResponse(new StationResource($station), 'Station is succefully permanantly deleted!');
        }else {
            return $this->sendError('Something went wrong!');
        }
    }

}
