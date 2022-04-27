<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel;
use App\Http\Resources\TravelResource;

class ReservationTools extends BaseController
{
    /**
     * Encapsulation of passengers' number incrementation
     */
    public function PassNumberInc(Travel $travel, Request $request){
        $i=0;
        foreach($travel->stations as $station){
            if($station==$request->input("boarding_station")){
                $i=1;
            }
            if($station==$request->input("landing_station")){
                break;
            }
            if($i==1){
                $station->pivot->passengers_on_board++;
            }
        }
    }
    /**
     * Verifies eligibility for a ticket and returns the number of available seats
     *
     *
     */
    public function AvAndP(Travel $travel, Request $request){
        $Path = False;
        $Seats = 0;
        if($request->input("classe")=="First Class"){
            foreach($travel->stations as $station){
                if($station == $request->boarding_station){
                    $Path = True;
                }
                if($station == $request->landing_station){
                    break;
                }
                if($Path && $station->pivot->firstClass_passengers_on_board = $travel->classe->firstClass_limitPlaces){
                   return $this->sendError("No Places Available");
                }
                else if($Path && $station->pivot->firstClass_passengers_on_board < $travel->classe->firstClass_limitPlaces){
                    $Diff = $travel->classe->firstClass_limitPlaces - $station->pivot->firstClass_passengers_on_board;
                    if($Diff < $Seats){
                        $Seats = $Diff;
                    }
                }
            }
            return $this->sendResponse([
                                        "Available" => True,
                                        "Seats" => $Seats
                                    ], "There are {$Seats} Places Available");
        }
        else {
            foreach($travel->stations as $station){
                if($station==$request->boarding_station){
                    $Path = True;
                }
                if($station == $request->landing_station){
                    break;
                }
                if($Path==1 && $station->pivot->secondClass_passengers_on_board = $travel->classe->secondClass_limitPlaces){
                    return $this->sendError("No Places Available");
                }
                else if($Path==1 && $station->pivot->secondClass_passengers_on_board < $travel->classe->secondClass_limitPlaces){
                    $Diff = $travel->classe->secondClass_limitPlaces - $station->pivot->secondClass_passengers_on_board;
                    if($Diff < $Seats){
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
}
