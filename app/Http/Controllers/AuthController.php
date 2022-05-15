<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|unique:users',
            'first_name' => 'required|string|min:4|max:10',
            'last_name' => 'required|string|min:4|max:10',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|same:password',
        ]);
        if($validator->fails()){
            return $this->sendError("Validation of data failed",$validator->errors());
        }

        $user = User::create([
            'phone_number' => $request->phone_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => Hash::make($request->password),
            'role_id' => Role::firstWhere('name','passenger')->id,
            'account_confirmed' => false
        ]);
        $token = $user->createToken('myapptoken')->plainTextToken;
        $result = [
            'user' => new UserResource($user),
            'token' => $token
        ];
        return $this->sendResponse($result,'Registration succesfull');

    }

    public function login(Request $request){
            /*$validator = Validator::make($request->all(), [
                'phone_number' => 'required',
                'password' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError("Validation of data failed",$validator->errors());
            }*/
            $validator = $request->validate([
                "phone_number" => 'required',
                "password" => 'required'
            ]);
            $user = User::where('phone_number',$request->phone_number)->first();
            if(Auth::attempt($validator)){
                $token = $user->createToken('myapptoken')->plainTextToken;
                $result = [
                    'user' => new UserResource($user),
                    'token' => $token
                ];
                return $this->sendResponse($result,'Login succesfull');
            }
            else{
                return $this->sendError("No user found with the specified data");
            }
        }
    public function logout(){
            /** @var \App\Models\User $user */
            $id=auth('sanctum')->id();
            $user = User::find($id);
            $user->tokens()->delete();
        return $this->sendResponse([],'Logged out succesfully');
    }
}
