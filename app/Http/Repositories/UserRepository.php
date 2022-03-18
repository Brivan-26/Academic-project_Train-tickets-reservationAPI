<?php
namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
Class UserRepository 
{
    public function all()
    {
        $user = User::all()->reject(function($user) {
            return ($user->deleted_at != NULL || $user->id == auth()->user()->id);
        });

        return $user;
    }

    public function deleteById($id)
    {
        $response = [];
        $user = User::find($id);
        if($user) {
            $user->delete();
            $response["success"] = true;
            $response["data"] = $user;
            return $response;
        }
        $response["success"] = false;
        $response["errors"] = "User can not be found!";
        return $response;
    }

    public function restoreById($id)
    {
        $response = [];
        $user = User::onlyTrashed()->where('id', $id)->first();
        if($user) {
            $user->restore();
            $response["success"] = true;
            $response["data"] = $user;
            return $response;
        }
        $response["success"] = false;
        $response["errors"] = "User can not be found!";
        return $response;
    }

    public function destoryById($id)
    {
        $response = [];
        $user = User::onlyTrashed()->where('id',$id)->first();
        if($user) {
            $user->forceDelete();
            $response["success"] = true;
            $response["data"] = $user;
            return $response;
        }
        $response["success"] = false;
        $response["errors"] = "User can not be found!";
        return $response;
    }

    public function upgradeRole($request, $id)
    {
        $response = [];
        $validator = Validator::make($request->all(), [
            'role' => 'exists:roles,id'
        ]);

        if($validator->fails()) {
            $response["success"] = false;
            $response["errors"] = $validator->errors();
            return $response;
        }
        
        $user = User::find($id);
        if($user) {
            $user->role_id = $request->role;
            $user->save();
            $response["success"] = true;
            $response["data"] = $user;
            return $response;
        }
        $response["success"] = false;
        $response["errors"] = "User can not be found!";
        return $response;
    }
}

