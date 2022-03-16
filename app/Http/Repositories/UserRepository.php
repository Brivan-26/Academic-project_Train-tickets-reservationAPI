<?php
namespace App\Http\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

Class UserRepository 
{
    public function all()
    {
        $query = User::all()->reject(function($user) {
            return ($user->id == auth()->user()->id);
        });

        return $query;
    }

    public function update_userInfos($request, $id){
        $auth = User::find($id);
        if(!$auth){
            return null;
        }
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
    
    public function update_userPassword($request, $id){
        $auth = User::find($id);
        if(!$auth){
            return null;
        }
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
        $query = User::find($id);
        if($query) {
            $query->delete();
        }
        return $query;
    }

    public function restoreById($id)
    {
        $query = User::onlyTrashed()->where('id', $id)->first();
        if($query) {
            $query->restore();
        }
        return $query;
    }

    public function destoryById($id)
    {
        $query = User::onlyTrashed()->where('id',$id)->first();
        if($query) {
            $query->forceDelete();
        }
        return $query;
    }
}

