<?php
namespace App\Http\Repositories;

use App\Models\Travel;
use App\Models\Review;

Class ReviewRepository {
    
    public function add_reviewByRequest($request, $id){
        $response = [];
        $travel = Travel::find($id);
        if(!$travel){
            $response['success'] = false ;
            $response['errors'] = "No travel found with specified id";
        }
        else{
            $review = $travel->reviews()
                    ->where('passenger_name',$request['passenger_name'])
                    ->where('user_id',auth()->user()->id)
                    ->first();
            if ($review){
                $response['success'] = false ;
                $response['errors'] = "Review for this passenger already added";
            }
            else{
                $ticket = $travel->tickets()
                        ->where('passenger_name',$request['passenger_name'])
                        ->where('user_id',auth()->user()->id)
                        ->first();
                if(!$ticket){
                    $response['success'] = false ;
                    $response['errors'] = "Cant add a review for a non bought ticket";
                }
                elseif(!$ticket->validated){
                    $response['success'] = false ;
                    $response['errors'] = "Cant add a review for a non validated ticket";
                }
                else{
                    $review = Review::create([
                        'travel_id' => $id,
                        'user_id' => auth()->user()->id,
                        'passenger_name'=>$request['passenger_name'],
                        'content'=>$request['content'],
                        'rating'=>$request['rating']
                    ]);
                    $response['success'] = true ;
                    $response['data'] = $review;
                }
            }
        }
        return $response;
    }

    public function get_reviewsByTravelId($id){
        $response = [];
        $travel = Travel::find($id);
        if(!$travel){
            $response['success'] = false ;
            $response['errors'] = "No travel found with specified id";
        }
        else{
            $response['success'] = true ;
            $response['data'] = $travel->reviews;
        }
        return $response;
    }
}