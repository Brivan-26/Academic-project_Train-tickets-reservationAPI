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
        $validator = Validator::make($request->all(), [
            'name' => 'required', 
            'wilaya' => 'required'
        ]);
        if($validator->fails()) {
            return null;
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
                    return null;
                }
                $file->move('uploads/stations', $fileName);                
                Station_photo::create([
                    'station_id' => $station->id,
                    'photo_url' =>'uploads/stations/'.$fileName
                ]);
            } 
        }

        return $station;
    }

    public function updateByRequest($request, $id) {
        $station = Station::find($id);
        if(!$station) {
            return null;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'wilaya' => 'required'
        ]);
        if($validator->fails()) {
            return null;
        }
        $station->name = $request->name; $station->wilaya = $request->wilaya;
        $station->save();
        return $station;
        // Station photos update!!!
    }


    public function deleteById($id)
    {
        $station = Station::find($id);
        if(!$station) {
            return null;
        }
        $station->delete();
        return $station;
    }

    public function restoreById($id)
    {
        $station = Station::onlyTrashed()->where('id', $id)->first();
        if(!$station) {
            return null;
        }
        $station->restore();
        return $station;
    }

    public function destroyById($id)
    {
        $station = Station::onlyTrashed()->where('id', $id)->first();
        if(!$station) {
            return null;
        }
        $station->forceDelete();
        return $station;
    }
    
}
