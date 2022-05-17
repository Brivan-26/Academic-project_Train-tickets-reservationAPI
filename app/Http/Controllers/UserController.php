<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\DetailedTravelResource;
use App\Http\Controllers\BaseController ;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\ReviewRepository;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{   private $userRepository;
    private $reviewRepository;
    public function __construct(UserRepository $userRepository, ReviewRepository $reviewRepository)
    {
        $this->userRepository = $userRepository;
        $this->reviewRepository = $reviewRepository;
    }
    public function get_authUser() {
        if(Auth::check()) {
            return $this->sendResponse(new UserResource(auth()->user()), 'Succefully retreived the authenticated user!');
        }
        return $this->sendError("Unatuthenticated");
    }

    public function update_infos(Request $request)
    {
        $response = $this->userRepository->update_userInfos($request);
        if ($response['success']){
            return $this->sendResponse(new UserResource($response['data']),
            "Account info updated successfully");
        }
        return $this->sendError("Something went wrong",$response['errors']);
    }

    public function update_password(Request $request)
    {
        $response = $this->userRepository->update_userPassword($request);
        if ($response['success']){
            return $this->sendResponse(new UserResource($response['data']),
            "Password updated successfully");
        }
        return $this->sendError("Something went wrong",$response['errors']);
    }
    
    public function review_add(Request $request, $id){
        $response = $this->reviewRepository->add_reviewByRequest($request, $id);
        if($response['success']){
            return $this->sendResponse(new ReviewResource($response['data']),
            "Review added successfully");
        }
        return $this->sendError("Something went wrong !",$response['errors']);
    }

    public function get_personnalTravels()
    {
        $response = $this->userRepository->get_travelsHistory();
        return $this->sendResponse(DetailedTravelResource::Collection($response['data']), 
        "Personnal travels retreived successfully");
    }
    public function reset_password(Request $request){
        $response = $this->userRepository->reset_userPassword($request);
        if ($response['success']){
            return $this->sendResponse(new UserResource($response['data']),
            "Password reset successfully");
        }
        return $this->sendError("Something went wrong",$response['errors']);
    }
}
