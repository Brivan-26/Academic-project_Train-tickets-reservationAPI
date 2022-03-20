<?php

namespace App\Http\Repositories;

use App\Models\Travel;
use Illuminate\Support\Facades\Validator;
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
            'departure_station' => 'required|string',
            'departure_time' => 'required',
            'arrival_station' => 'required',
            'distance' => 'required',
            'estimated_duration' => 'required',
            'description' => 'required',
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
        
        $response["success"] = true;
        $response["data"] = $travel;
        return $response;
    }

    public function updateByRequest($request, $travelId) {
        $response = [];
        $travel = Travel::find($travelId);
        if($travel) {
            $validator = Validator::make($request->all(), [
                'departure_station' => 'required|string',
                'departure_time' => 'required',
                'arrival_station' => 'required',
                'distance' => 'required',
                'estimated_duration' => 'required',
                'description' => 'required',
            ]);
            if($validator->fails()) {
                $response["success"] = false;
                $response["errors"] = $validator->errors();
                return $response;
            }else {
                $travel->departure_station = $request->departure_station;
                $travel->departure_time = $request->departure_time;
                $travel->arrival_station = $request->arrival_station;
                $travel->distance = $request->distance;
                $travel->estimated_duration = $request->estimated_duration;
                $travel->description = $request->description;
                $travel->save();
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

    
}