<?php

namespace App\Http\Repositories;

use App\Models\Travel;
//use App\Http\Controllers\NotificationsController as Notif;
use App\Http\Repositories\NotificationsRepository as Notif;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jobs\RenderTravelCompleted;

Class TravelRepository
{
    public function all()
    {

        return Travel::where('status', '!=','completed')->get();
    }

    public function createByRequest($request)
    {
        $response = [];
            $validator = Validator::make($request->all(), [
            'departure_station' => 'required',
            'departure_time' => 'required',
            'arrival_station' => 'required',
            'distance' => 'required',
            'estimated_duration' => 'required',
            'description' => 'required',
            "firstClass_limitPlaces" => 'required',
            "secondClass_limitPlaces" => 'required',
            'stations' => 'required'
        ]);

        if($validator->fails()){
            $response["success"] = false;
            $response["errors"] = $validator->errors();
            return $response;
        }

        $travel = Travel::create([
            'departure_station' => $request->departure_station,
            'departure_time' => $request->departure_time,
            'arrival_station' => $request->arrival_station,
            'distance' => $request->distance,
            'estimated_duration' => $request->estimated_duration,
            'description' => $request->description,
            "status" => "pending"
        ]);
        $travel->refresh();

        $travel->classe()->create([
            "firstClass_limitPlaces" => $request->firstClass_limitPlaces,
            "secondClass_limitPlaces" => $request->secondClass_limitPlaces,
        ]);

        foreach($request->stations as $station){
            $travel->stations()->attach($station["id"], [
                'arrival_time' => $station["arrival_time"],
                'firstClass_price' => $station["firstClass_price"],
                'secondClass_price' => $station["secondClass_price"],
            ]);
        }

        $arriv = new Carbon($request->stations[count($request->stations)-1]["arrival_time"]);
        $travel->refresh();
        RenderTravelCompleted::dispatch($travel)
                    ->delay($arriv)
                    ->addHours(-2);

        $response["success"] = true;
        $response["data"] = $travel;
        return $response;
    }

    public function updateByRequest(Request $request, $travelId) {
        $response = [];
        $travel = Travel::find($travelId);
        if($travel!=null) {
            $validator = Validator::make($request->all(), [
                'departure_time' => 'required',
                'estimated_duration' => 'required',
            ]);
            if($validator->fails()) {
                $response["success"] = false;
                $response["errors"] = $validator->errors();
                return $response;
            } else {
                if($request->departure_time < $travel->departure_time){
                    $response['success'] = false;
                    $response['errors'] = "Cannot update departure time to a preceding date";
                    return $response;
                }
                $travel->departure_time = $request->departure_time;
                $travel->estimated_duration = $request->estimated_duration;
                $travel->status = "delayed";
                $message = Notif::sendMessage0("Travel delayed to {$request->departure_time}",
                                          "the user was notified");

                $travel->save();
                $response['notified'] = $message;
                $response["success"] = true;
                $response["data"] = $travel;
                return $response;
            }
        }else {
            $response["success"] = false;
            $response["errors"] = "Travel can not be found!";
            return $response;
        }
    }

    public function cancelByRequest($travelId){
        $travel = Travel::find($travelId);
        if($travel==null){
            $response['success'] = false;
            $response['errors'] = "No travel found";
            return $response;
        }
        foreach($travel->tickets as $ticket){
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $stripe->refunds->create([
                'charge' => $ticket->payment_token,
            ]);
            }
            $message = Notif::sendMessage0("Travel cancelled, refunded",
                                        "the user was notified");
            $response['notified'] = $message;
            $travel->status = "cancelled";
            $travel->departure_time = null;
            $travel->save();
            $response["success"] = true;
            $response["data"] = $travel;
            return $response;
        }

    public function deleteById($travelId)
    {
        $response = [];
        $travel = Travel::find($travelId);
        if($travel) {
            $travel->delete();
            $response["success"] = true;
            $response["data"] = $travel;
            return $response;
        }else {
            $response["success"] = false;
            $response["errors"] = "Travel can not be found!";
            return $response;
        }
        return $travel;

    }

    // public function getFiveTravels()
    // {
    //     $response = [];
    //     $travels = Travel::where("status", "completed")->where()->->get(5);
    // }

    public function travelOfTheDay(){
        $response = [];
        $id = auth('sanctum')->id();
        $todayTravels = collect();
        $validatorTravels = Travel::where('validator_id', $id)->get();
        $Today = date("Y-m-d", strtotime(now()));
        foreach($validatorTravels as $travel){
            $travelTime = date("Y-m-d", strtotime($travel->departure_time));
            if($travelTime==$Today){
                $todayTravels->push($travel);
             }
        }
        if($todayTravels!=null){
            $response = [
                'success' => true,
                'data' => $todayTravels
            ];
        } else {
            $response = [
                'success' => false,
                'errors' => "No travels found for today"
            ];
        }
        return $response;
    }
}
