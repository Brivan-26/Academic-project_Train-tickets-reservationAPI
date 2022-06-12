<?php

namespace App\Http\Repositories;

use Illuminate\Http\Request;
use App\Models\Travel;
use App\Http\Resources\DetailedTravelResource;
use App\Http\Resources\TravelResource;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Station;
use PackageVersions\FallbackVersions;

class ReservationRepository
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
                $value+=count($request->passengers);
                DB::table('station_travel')->where('travel_id', $travelId)
                                           ->where('station_id', $station->id)->update([
                    'passengers_on_board' => $value
                ]);
                if($request->classe=='F'){
                    $value = DB::table('station_travel')->where('travel_id', $travelId)
                                                        ->where('station_id', $station->id)->value('firstClass_passengers_on_board');
                    $value+=count($request->passengers);
                    DB::table('station_travel')->where('travel_id', $travelId)
                                               ->where('station_id', $station->id)->update([
                        'firstClass_passengers_on_board' => $value
                    ]);
                }
                else if($request->classe=='S'){
                    $value = DB::table('station_travel')->where('travel_id', $travelId)
                                                        ->where('station_id', $station->id)->value('secondClass_passengers_on_board');
                    $value+=count($request->passengers);
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
    public function AvAndP0(Request $request){
        $response = [];
        $validator = Validator::make($request->all(), [
            'classe' => 'required',
            'boarding_station' => 'required',
            'landing_station' => 'required',
            'travelId' => 'required'
        ]);
        if($validator->fails()){
            //return $this->sendError("Validation of data failed",$validator->errors());
            $response['success'] = false;
            $response['errors'] = $validator->errors();
            return $response;
        }
        $travel = Travel::find($request->travelId);
        if($travel==null){
            $response['success'] = false;
            $response['errors'] = "This travel was not found";
            return $response;
        }
        $Path = False;
        $Seats = 0;
        if($request->classe=="F"){
            foreach($travel->stations as $station){
                if($station->id == $request->boarding_station){
                    $Path = True;
                }
                if($station->id == $travel->arrival_station && $station->id != $request->landing_station){
                    $response['success'] = false;
                    $response['errors'] = "This travel doesn't go that far";
                    return $response;
                }
                if($station->id == $request->landing_station){
                    break;
                }
                if($Path){
                    $places = DB::table('station_travel')->where('travel_id', $request->travelId)
                                                         ->where('station_id', $station->id)->value('firstClass_passengers_on_board');
                    $Diff = $travel->classe->firstClass_limitPlaces - $places;
                    if($Diff==0){
                        //return $this->sendError("No Places Available");
                        $response['success'] = false;
                        $response['errors'] = "No Places Available";
                        return $response;
                    }
                    else if($station->id == $request->boarding_station){
                        $Seats = $Diff;
                    }
                    else if($Diff < $Seats){
                        $Seats = $Diff;
                    }
                }
            }
            if($Path){
                //return $this->sendResponse($Seats, "There are {$Seats} Places Available");
                $response['success'] = true;
                $response['data'] = $Seats;
            } else {
                $response['success'] = false;
                $response['errors'] = "No such route in this travel";
            }
        }
        else if ($request->classe=="S"){
            foreach($travel->stations as $station){
                if($station->id == $request->boarding_station){
                    $Path = True;
                }
                if($station->id == $travel->arrival_station && $station->id != $request->landing_station){
                    $response['success'] = false;
                    $response['errors'] = "This travel doesn't go that far";
                    return $response;
                }
                if($station->id == $request->landing_station){
                    break;
                }
                if($Path){
                    $places = DB::table('station_travel')->where('travel_id', $request->travelId)
                                                         ->where('station_id', $station->id)->value('secondClass_passengers_on_board');
                    $Diff = $travel->classe->secondClass_limitPlaces - $places;
                    if($Diff==0){
                        //return $this->sendError("No Places Available");
                        $response['success'] = false;
                        $response['errors'] = "No Places Available";
                        return $response;
                    }
                    else if($station->id == $request->boarding_station){
                        $Seats = $Diff;
                    }
                    else if($Diff < $Seats){
                        $Seats = $Diff;
                    }
                }
            }
            if($Path){
                //return $this->sendResponse($Seats, "There are {$Seats} Places Available");
                $response['success'] = true;
                $response['data'] = $Seats;
            } else {
                $response['success'] = false;
                $response['errors'] = "No such route in this travel";
            }
        }
        return $response;
    }
    /**
    * Returns an array of travels passing by the path provided by the user
    *
    */
    public function PassThroughTravels0(Request $request){
        $depStation = Station::where('name', $request->depStation)->first();
        $arvStation = Station::where('name', $request->arvStation)->first();
        $Travels = [];
        $response = [];
        foreach(Travel::all()->where('status','pending') as $travel){
            if(date("Y-m-d", strtotime($travel->departure_time)) != $request->depTime){
                //return $this->sendError("No travels found for this date");
                $response['success'] = false;
                $response['errors'] = "No travels found for this date";
                return $response;
            }
            $departure = false;
            $arrival = false;
            foreach($travel->stations as $station){
                if($station->id == $depStation->id){
                    $departure = true;
                }
                if($station->id == $arvStation->id && $departure){
                    $arrival = true;
                    break;
                }
            }
            if ($arrival){
                $Travels[] = new DetailedTravelResource($travel);
            }
        }
        if($Travels){
            //return $this->sendResponse($Travels, "Travels found for this route");
            $response['success'] = true;
            $response['data'] = $Travels;
        } else {
            //return $this->sendError("No travels found for this route");
            $response['success'] = false;
            $response['errors'] = "No travels found for this route";
        }
        return $response;
    }

    public function AllTravels0(){

        //return $this->sendResponse(DetailedTravelResource::collection(Travel::all()),
          //      "All travels retreived successfully");
        return DetailedTravelResource::collection(Travel::all());
    }


    public function NbStations(Travel $travel, $boarding, $landing){
        $i=0;
        $Nb=0;
        foreach($travel->stations as $station){

            if($station->id==$boarding){
                $i=1;
            }

            if($i==1){
                $Nb++;
            }
            if($station->id==$landing){
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
    public function pricing0($travelId, $boarding, $landing){
        $response = [];
        $Travel = Travel::find($travelId);
        if($Travel==null){
            $response['success']=false;
            $response['errors']="No such travel";
            return $response;
        }
        $nbStations = $this->NbStations($Travel, $boarding, $landing);
        if($nbStations==0){
            $response['success']=false;
            $response['errors']="No such route for this travel";
            return $response;
        }
        $distance = ($Travel->distance) / (count($Travel->stations) - 1);
        $prices = [
            'F' =>  (int)($distance * ($nbStations - 1) * 400),
            'S' =>  (int)($distance * ($nbStations - 1) * 300)
        ];
        //return $prices;
        $response['success']=true;
        $response['data']=$prices;
        return $response;
    }
}
?>
