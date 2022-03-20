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
        $user = $this->userRepository->update_userInfos($request);
        if ($user){
            return $this->sendResponse(new UserResource($user), "Account updated successfully");
        }
        return $this->sendError("Something went wrong");
    }

    public function update_password(Request $request)
    {
        $user = $this->userRepository->update_userPassword($request);
        if ($user){
            return $this->sendResponse(new UserResource($user), "Password updated successfully");
        }
        return $this->sendError("Something went wrong");
    }
}
