<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Controllers\BaseController ;
use App\Http\Repositories\UserRepository;
use Auth;

class UserController extends BaseController
{   private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
}
