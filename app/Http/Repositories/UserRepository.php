<?php
namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
Class UserRepository
{
    public function all()
    {
        $user = User::all()->reject(function($user) {
            return ($user->deleted_at != NULL || $user->id == auth()->user()->id);
        });

        return $user;
    }

    public function update_userInfos($request){
        $id = auth()->user()->id;
        $auth = User::find($id);
        $validator = Validator::make($request->all(), [
            'phone_number' => [
                'required','string',
                Rule::unique('users')->ignore($id),
            ],
            'first_name' => ['required','string','min:4','max:10'],
            'last_name' => ['required','string','min:4','max:10'],
        ]);
        if($validator->fails()){
            return null;
        }
        $user = User::where(['first_name' => $request->first_name,
                            'last_name' => $request->last_name])
                            ->where('id','!=',$id)
                            ->first();
        if($user){
            return null;
        }
        $auth->phone_number = $request->phone_number;
        $auth->first_name = $request->first_name;
        $auth->last_name = $request->last_name;
        $auth->save();
        return $auth;
    }

    public function update_userPassword($request){
        $id = auth()->user()->id;
        $auth = User::find($id);
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
        ]);
        if($validator->fails()){
            return null;
        }
        if(!Hash::check($request->current_password,$auth->password)){
            return null;
        }
        $auth->password = Hash::make($request->password);
        $auth->save();
        return $auth;
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

