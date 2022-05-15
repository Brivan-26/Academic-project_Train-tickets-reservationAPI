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

    public function reset_password(Request $request){
        $response = $this->userRepository->reset_userPassword($request);
        if ($response['success']){
            return $this->sendResponse(new UserResource($response['data']),
            "Password reset successfully");
        }
        return $this->sendError("Something went wrong",$response['errors']);
    }
}
