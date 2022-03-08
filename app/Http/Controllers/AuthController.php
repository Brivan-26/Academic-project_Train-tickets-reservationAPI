<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;

use function PHPUnit\Framework\isEmpty;

class AuthController extends Controller
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
            return response()->json([
                "succes" => false,
                "errors" => $validator->errors()
            ]);
        }

        $user = User::where(['first_name' => $request->first_name, 
                            'last_name' => $request->last_name])
                            ->get()->first();
        if($user){
            return response()->json([
                "succes" => false,
                "errors" => ["name" => "A user already exits with the same first name and last name"],
            ]);
        }

        $user = User::create([
            'phone_number' => $request->phone_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'password' => Hash::make($request->password),
            'role_id' => 1, //Role::where('name','passenger')->id
            'account_confirmed' => false
        ]);
        $token = $user->createToken('myapptoken')->plainTextToken;
        return response()->json([
            'succes' => true,
            'token' => $token
        ]);

    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'password' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                "succes" => false,
                "errors" => $validator->errors()
            ]);
        }

        $user = User::where('phone_number',$request->phone_number)->first();
        if(!$user){
            return response()->json([
                'succes' => false,
                'errors' => ["phone_number" => 'No user found with the specified phone number']
            ]);
        }

        if(!Hash::check($request->password,$user->password)){
            return response()->json([
                'succes' => false,
                'errors' => ["password" => 'The entered password is incorrect']
            ]);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;
        return response()->json([
            'succes' => true,
            'token' => $token
        ]);

    }

    public function logout(){

        auth()->user()->tokens()->delete();
        return response()->json([
            'succes' => true
        ]);
        
    }
}
