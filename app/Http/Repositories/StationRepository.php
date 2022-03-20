<?php
namespace App\Http\Repositories;

use App\Models\Station;
use App\Models\Station_photo;
use Illuminate\Support\Facades\Validator;

class StationRepository {

    public function all()
    {
    
        $stations = Station::all();
        return $stations;
    }

    public function createByRequest($request)
    {
        $response = [];
        $validator = Validator::make($request->all(), [
            'name' => 'required', 
            'wilaya' => 'required'
        ]);
        if($validator->fails()) {
            $response["success"] = false;
            $response["errors"] = $validator->errors();
            return $response;
        }
        $station = Station::create([
            'name' => $request->name,
            'wilaya' => $request->wilaya
        ]);
        if($request->hasFile('photos')) {
            $allowedfileExtension=['jpg','png'];
            $files = $request->file('photos');
            foreach($files as $file) {
                $fileName = $file->getClientOriginalName().$station->id;
                $fileExtension = $file->getClientOriginalExtension();
                $check = in_array($fileExtension, $allowedfileExtension);
                if(!check) {
                    $response["success"] = false;
                    $response["errors"] = "file extension must be jpg or png";
                    return $response;
                }
                $file->move('uploads/stations', $fileName);                
                Station_photo::create([
                    'station_id' => $station->id,
                    'photo_url' =>'uploads/stations/'.$fileName
                ]);
            } 
        }
        $response["success"] = true;
        $response["data"] = $station;
        return $response;
    }

    public function updateByRequest($request, $id) {
        $response = [];
        $station = Station::find($id);
        if(!$station) {
            $response["success"] = false;
            $response["errors"] = "station can not be found!";
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'wilaya' => 'required'
        ]);
        if($validator->fails()) {
            $response["success"] = false;
            $response["errors"] = $validator->errors();
            return $response;
        }
        $station->name = $request->name; $station->wilaya = $request->wilaya;
        $station->save();
        $response["success"] = true;
        $response["data"] = $station;
        return $response;
        // Station photos update!!!
    }


    public function deleteById($id)
    {
        $response = [];
        $station = Station::find($id);
        if(!$station) {
            $response["success"] = false;
            $response["errors"] = "Station can not be found!";
            return $response;
        }
        $station->delete();
        $response["success"] = true;
        $response["data"] = $station;
        return $response;
    }

    public function restoreById($id)
    {
        $response = [];
        $station = Station::onlyTrashed()->where('id', $id)->first();
        if(!$station) {
            $response["success"] = false;
            $response["errors"] = "Station can not be found!";
            return $response;
        }
        $station->restore();
        $response["success"] = true;
        $response["data"] = $station;
        return $response;
    }

    public function destroyById($id)
    {
        $response = [];
        $station = Station::onlyTrashed()->where('id', $id)->first();
        if(!$station) {
            $response["success"] = false;
            $response["errors"] = "Station can not be found!";
            return $response;
        }
        $station->forceDelete();
        $response["success"] = true;
        $response["data"] = $station;
        return $response;
    }
    
}
