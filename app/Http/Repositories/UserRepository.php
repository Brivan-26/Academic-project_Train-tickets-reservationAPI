<?php
namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
//use App\Http\Controllers\NotificationsController as Notif;
use App\Http\Repositories\NotificationsRepository as Notif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Station;
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

    public function update_userInfosNoPic($request){
        $response = [];
        $id = auth()->user()->id ;
        $validator = Validator::make($request->all(), [
            'phone_number' => [
                'required','string',
                Rule::unique('users')->ignore($id),
            ],
            'first_name' => ['required','string','min:4','max:10'],
            'last_name' => ['required','string','min:4','max:10'],
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
            $response["data"] = User::all();
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
        Notif::sendPin0("Validation PIN", "validation");
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

    public function passwordPIN(Request $request){
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required'
        ]);
        if($validator->fails()){
            $response['success'] = false;
            $response['errors'] = $validator->errors();
            return $response;
        }
        setcookie("phone_number", $request->phone_number);
        return Notif::sendPin0("PR PIN", "password_pin");
    }

    public function PINconfirmation(Request $request){
        $response = [];
        $givenPin = $request->password_pin;
        if($_COOKIE['password_pin']!=null){
            if($_COOKIE['password_pin']==$givenPin){
                $response['success'] = true;
                $response['data'] = $_COOKIE['password_pin'];
                return $response;
            } else {
                $response['success'] = false;
                $response['errors'] = "Wrong PIN";
                return $response;
            }
        }
        $response['success'] = false;
        $response['errors'] = "No PIN was sent/The Pin is now invalid";
        return $response;
    }

    public function reset_userPassword(Request $request){
        $phone_number = $_COOKIE["phone_number"];
        $user = User::where('phone_number', $phone_number)->first();
        $validator = Validator::make($request->all(), [
            'new_password' => 'required|string|min:8',
            'confirm_new_password' => 'required|string|same:new_password',
        ]);
        if($validator->fails()){
            $response['success'] = false ;
            $response['errors'] = $validator->errors();
        }
        else{
            $user->password = Hash::make($request->new_password);
            $user->save();
            $response['success'] = true;
            $response['data'] =$user;
        }
        return $response;
    }

    public function getLastJoined()
    {
        $response = [];
        $users = User::all();
        $response['success'] = true;
        $response['data'] = $users;
        return $response;
    }

    public function getBaseStats()
    {
        $response = [];
        $stats = [
            "users" => count(User::all()),
            "travels" => count(\App\Models\Travel::all()),
            "tickets" => count(\App\Models\Ticket::all()),
        ];
        $response['success'] = true;
        $response['data'] = $stats;
        return $response;
    }

    public function getStations(){
        $response = [];
        $stations = Station::all()->pluck('name');
        $response['success'] = true;
        $response['data'] = $stations;
        return $response;
    }
}

