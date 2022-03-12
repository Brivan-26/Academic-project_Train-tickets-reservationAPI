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
        $validator = Validator::make($request->all(), [
            'departure_station' => 'required|string',
            'departure_time' => 'required',
            'arrival_station' => 'required',
            'distance' => 'required',
            'estimated_duration' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return null;
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

        return $travel;
    }

    public function updateByRequest($request, $travelId) {
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
                return null;
            }else {
                $travel->departure_station = $request->departure_station;
                $travel->departure_time = $request->departure_time;
                $travel->arrival_station = $request->arrival_station;
                $travel->distance = $request->distance;
                $travel->estimated_duration = $request->estimated_duration;
                $travel->description = $request->description;
                $travel->save();
                return $travel;
            }
        }else {
            return BaseController->sendError('travel can not be found!', 404);
        }
    }

    public function deleteById($travelId)
    {
        $travel = Travel::find($travelId);
        if($travel) {
            $travel->delete();
            return $travel;
        }
    }

    
}