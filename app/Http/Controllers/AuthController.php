<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use App\Http\Repositories\UserRepository;
use App\Http\Resources\UserResource;
use function PHPUnit\Framework\isEmpty;

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

        $user = User::where(['first_name' => $request->first_name, 
                            'last_name' => $request->last_name])
                            ->first();
        if($user){
            return $this->sendError("A user with the same first and last name already exists");
        }
        $user = User::create([
            'phone_number' => $request->phone_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => Hash::make($request->password),
            'role_id' => Role::where('name','passenger')->first()->id,
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

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return $this->sendError("Validation of data failed",$validator->errors());
        }

        $user = User::where('phone_number',$request->phone_number)->first();
        if(!$user || !Hash::check($request->password,$user->password)){
            return $this->sendError("No user found with the specified data");
        }

        $token = $user->createToken('myapptoken')->plainTextToken;
        $result = [
            'user' => new UserResource($user),
            'token' => $token
        ];
        return $this->sendResponse($result,'Login succesfull');

    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return $this->sendResponse([],'Logged out succesfully');
    }
}
