<?php
namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\NotificationsController as Notif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

Class UserRepository
{
    public static function all()
    {
        $user = User::all()->reject(function($user) {
            return ($user->deleted_at != NULL || $user->id == auth()->user()->id);
        });

        return $user;
    }

    public function update_userInfos($request){
        $response = [];
        $id = auth()->user()->id ;
        $validator = Validator::make($request->all(), [
            'phone_number' => [
                'required','string',
                Rule::unique('users')->ignore($id),
            ],
            'first_name' => ['required','string','min:4','max:10'],
            'last_name' => ['required','string','min:4','max:10'],
            'image' => 'required|image:jpeg,png,jpg,gif,svg'
        ]);
        if($validator->fails()){
            $response['success'] = false ;
            $response['errors'] = $validator->errors();
        }
        else{
            $auth = User::find($id);
            $auth->phone_number = $request->phone_number;
            $auth->first_name = $request->first_name;
            $auth->last_name = $request->last_name;

            if($auth->profile_img){
                Storage::disk('public')->delete($auth->profile_img);
            };
            $path = Storage::disk('public')->put('users/avatars', $request->file('image'));
            $auth->profile_img = $path;
            
            $auth->save();
            $response['success'] = true ;
            $response['data'] = $auth;
        }
        return $response;
    }

    public function update_userPassword($request){
        $id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|current_password',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
        ]);
        if($validator->fails()){
            $response['success'] = false ;
            $response['errors'] = $validator->errors();
        }
        else{
            $auth = User::find($id);
            $auth->password = Hash::make($request->password);
            $auth->save();
            $response['success'] = true;
            $response['data'] =$auth;
        }
        return $response;
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

    public function validationPIN(){
        Notif::sendPin("Validation PIN", "validation");
    }

    public function validateAccount(Request $request){
        $response=[];
        $givenPin = $request->pin;
        $id = auth('sanctum')->id();
        $user = User::find($id);
        if($request->cookie('validation')!=null){
            if($givenPin==$request->cookie('validation')){
                $user->account_confirmed = 1;
                $user->save();
                $response['success'] = true;
                $response['data'] = $user;
            } else {
                $response['success'] = false;
                $response['error'] = "Wrong PIN";
            }
        } else{
            $response['success'] = false;
            $response['error'] = "Some error occured";
        }
        return $response;
    }

    public function get_travelsHistory(){
        $user = User::find(auth()->user()->id);
        return [
            'succes' => true,
            'data' => $user->travels()
        ];
    }
    public function passwordPIN(){
        return Notif::sendPin("PR PIN", "password_pin");
    }

    public function PINconfirmation(Request $request){
        $givenPin = $request->password_pin;
        if($request->cookie('password_pin')!=null){
            if($request->cookie('password_pin')==$givenPin){
                return True;
            } else {
                return False;
            }
        }
        return False;
    }

    public function reset_userPassword(Request $request){
        $id = auth()->user()->id;
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8',
            'confirm_new_password' => 'required|string|same:new_password',
        ]);
        if($validator->fails()){
            $response['success'] = false ;
            $response['errors'] = $validator->errors();
        }
        else{
            $auth = User::find($id);
            $auth->password = Hash::make($request->new_password);
            $auth->save();
            $response['success'] = true;
            $response['data'] =$auth;
        }
        return $response;
    }
}

