<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource as UserResource;
use App\Http\Resources\TravelResource as TravelResource;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Repositories\UserRepository;
class AdminDashboardController extends BaseController
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

}
