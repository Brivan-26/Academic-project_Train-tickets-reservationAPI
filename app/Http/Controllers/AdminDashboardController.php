<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource as UserResource;
use App\Http\Controllers\BaseController as BaseController;
class AdminDashboardController extends BaseController
{
    public function index()
    {
        $users = User::all()->reject(function($user) {
            return ($user->deleted_at != NULL || $user->id == auth()->user()->id);
        });

        return $this->sendResponse(UserResource::collection($users), 'succefully logged the necessary data!');
    }

    public function delete_user($id) {
        $user = User::find($id);
        if($user) {
            $user->delete();
            return $this->sendResponse(new UserResource($user), 'User deleted succefully');
        }else {
            return $this->sendError('User can not be found!');
        }
    }
    
    public function restore_user($id) {
        $user = User::onlyTrashed()->where('id', $id)->first();
        if($user) {
            $user->restore();
            return $this->sendResponse(new UserResource($user), 'User restored succefully');
        }else {
            return $this->sendError('User can not be found!');
        }
    }

    public function destory_user($id) {
        $user = User::onlyTrashed()->where('id',$id)->first();
        if($user) {
            $user->forceDelete();
            return $this->sendResponse(new UserResource($user), 'User permananly deleted succefully');
        }else {
            return $this->sendError('User can not be found!');
        }
    }
}
