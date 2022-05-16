<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\Travel;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Repositories\ReviewRepository;
use App\Http\Resources\ReviewResource;

class TravelController extends BaseController
{
    private $reviewRepository;
    public function __construct(ReviewRepository $reviewRepository){
        $this->reviewRepository = $reviewRepository;
    }

    public function review_add(Request $request, $id){
        $response = $this->reviewRepository->add_reviewByRequest($request, $id);
        if($response['success']){
            return $this->sendResponse(ReviewResource::collection($response['data']),
            "Review added successfully");
        }
        return $this->siendError("Something went wrong !",$response['errors']);
    }
    
    public function reviews_get($id){
        $response = $this->reviewRepository->get_reviewsByTravelId($id);
        if($response['success']){
            return $this->sendResponse(ReviewResource::collection($response['data']),
            "Reviews retreived successfully");
        }
        return $this->sendError("Something went wrong !",$response['errors']);
    }
}
