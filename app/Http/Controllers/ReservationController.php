<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel;
use App\Http\Resources\TravelResource;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReservationController extends BaseController
{
    /**
     * Encapsulation of passengers' number incrementation
     */
    public function PassNumberInc($travelId, Request $request){
        $travel = Travel::find($travelId);
        $i=false;
        foreach($travel->stations as $station){
            if($station->id==$request->boarding_station){
                $i=true;
            }
            if($station->id==$request->landing_station){
                break;
            }
            if($i){
                $value = DB::table('station_travel')->where('travel_id', $travelId)
                                                    ->where('station_id', $station->id)->value('passengers_on_board');
                $value+=$request->nb;
                DB::table('station_travel')->where('travel_id', $travelId)
                                           ->where('station_id', $station->id)->update([
                    'passengers_on_board' => $value
                ]);
                if($request->classe=='F'){
                    $value = DB::table('station_travel')->where('travel_id', $travelId)
                                                        ->where('station_id', $station->id)->value('firstClass_passengers_on_board');
                    $value+=$request->nb;
                    DB::table('station_travel')->where('travel_id', $travelId)
                                               ->where('station_id', $station->id)->update([
                        'firstClass_passengers_on_board' => $value
                    ]);
                }
                else if($request->classe=='S'){
                    $value = DB::table('station_travel')->where('travel_id', $travelId)
                                                        ->where('station_id', $station->id)->value('secondClass_passengers_on_board');
                    $value+=$request->nb;
                    DB::table('station_travel')->where('travel_id', $travelId)
                                               ->where('station_id', $station->id)->update([
                        'secondClass_passengers_on_board' => $value
                    ]);
                }
            }

        }
    }
    /**
     * Verifies eligibility for a ticket and returns the number of available seats
     *
     *
     */
    public function AvAndP(Request $request){
        $validator = Validator::make($request->all(), [
            'classe' => 'required',
            'boarding_station' => 'required',
            'landing_station' => 'required',
            'travelId' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError("Validation of data failed",$validator->errors());
        }
        $travel = Travel::find($request->travelId);
        $Path = False;
        $Seats = 0;
        if($request->classe=="F"){
            foreach($travel->stations as $station){
                if($station->id == $request->boarding_station){
                    $Path = True;
                }
                if($station->id == $request->landing_station){
                    break;
                }
                if($Path){
                    $places = DB::table('station_travel')->where('travel_id', $request->travelId)
                                                         ->where('station_id', $station->id)->value('firstClass_passengers_on_board');
                    $Diff = $travel->classe->firstClass_limitPlaces - $places;
                    if($Diff==0){
                        return $this->sendError("No Places Available");
                    }
                    else if($station->id == $request->boarding_station){
                        $Seats = $Diff;
                    }
                    else if($Diff < $Seats){
                        $Seats = $Diff;
                    }
                }
            }
            return $this->sendResponse([
                                        "Available" => True,
                                        "Seats" => $Seats
                                    ], "There are {$Seats} Places Available");
        }
        else if ($request->classe=="S"){
            foreach($travel->stations as $station){
                if($station->id == $request->boarding_station){
                    $Path = True;
                }
                if($station->id == $request->landing_station){
                    break;
                }
                if($Path){
                    $places = DB::table('station_travel')->where('travel_id', $request->travelId)
                                                         ->where('station_id', $station->id)->value('secondClass_passengers_on_board');
                    $Diff = $travel->classe->secondClass_limitPlaces - $places;
                    if($Diff==0){
                        return $this->sendError("No Places Available");
                    }
                    else if($station->id == $request->boarding_station){
                        $Seats = $Diff;
                    }
                    else if($Diff < $Seats){
                        $Seats = $Diff;
                    }
                }
            }
            return $this->sendResponse([
                                        "Available" => True,
                                        "Seats" => $Seats
                                    ], "There are {$Seats} Places Available");
        }
    }
    /**
    * Returns an array of travels passing by the path provided by the user
    *
    */
    public function PassThroughTravels(Request $request){
        $Travels = [];
        foreach(Travel::where('status','pending') as $travel){
            $departure = false;
            $arrival = false;
            foreach($travel->stations as $station){
                if($station==$request->input('departure_station')){
                    $departure = true;
                }
                if($station==$request->input('arrival_station') && $departure){
                    $arrival = true;
                    break;
                }
            }
            if ($arrival){
                $Travels[] = new TravelResource($travel);
            }
        }
        return $Travels;
    }

    public function NbStations(Travel $travel, Request $request){
        $i=0;
        $Nb=0;
        foreach($travel->stations as $station){
            if($station->id==$request->boarding_station){
                $i=1;
            }
            if($i==1){
                $Nb++;
            }
            if($station->id==$request->landing_station){
                break;
            }
        }
        return $Nb;
    }

    /**
     * Returns prices for a specific path within a travel
     * For the sake of simplicity, we'll suppose that every two stations have an equal distance
     * depending on the overall travel distance. Although some inconsistencies may appear, the
     * logic will remain correct
     */
    public function pricing($travelId, Request $request){
        $Travel = Travel::find($travelId);
        $nbStations = $this->NbStations($Travel, $request);
        $distance = ($Travel->distance) / (count($Travel->stations) - 1);
        $prices = [
            'F' =>  (int)($distance * ($nbStations - 1) * 400),
            'S' =>  (int)($distance * ($nbStations - 1) * 300)
        ];
        return $prices;
    }
}
