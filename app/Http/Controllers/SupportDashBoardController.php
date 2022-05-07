<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repositories\Support_ticketRepository;
use App\Http\Resources\Support_ticketResource;
use App\Http\Resources\Support_tickets_answerResource;

class SupportDashBoardController extends BaseController
{   
    private $support_ticketRepository;
    public function __construct(Support_ticketRepository $support_ticketRepository){
        $this->support_ticketRepository = $support_ticketRepository;
    }

    public function index(){
        $tickets =$this->support_ticketRepository->all();
        return $this->sendResponse(Support_ticketResource::collection($tickets),
                                    "Support tickets retreived successfully");
    }

    public function supportTicket_create(Request $request){
        $response = $this->support_ticketRepository->createByRequest($request);
        return $this->sendResponse(new Support_ticketResource($response['data']),
                                    "Support ticket created successfully");
    }

    public function supportTicket_assign($id){
        $response = $this->support_ticketRepository->assignById($id);
        if($response['success']){
            return $this->sendResponse(new Support_ticketResource($response['data']),
            "Support ticket assigned successfully");
        }
        return $this->sendError("Something went wrong !",$response['errors']);
    }

    public function supportTicket_disable($id){
        $response = $this->support_ticketRepository->render_inactiveById($id);
        if($response['success']){
            return $this->sendResponse(new Support_ticketResource($response['data']),
            "Support ticket disabled successfully");
        }
        return $this->sendError("Something went wrong !",$response['errors']);
    }

    public function supportTickets_get(){
        $tickets = $this->support_ticketRepository->get_supportTickets();
        return $this->sendResponse(Support_ticketResource::collection($tickets),
                                    "Personnal support tickets retreived successfully");
    }

    public function supportTicketAnswer_add(Request $request, $id){
        $response = $this->support_ticketRepository->add_answerByRequest($request, $id);
        if($response['success']){
            return $this->sendResponse(Support_tickets_answerResource::collection($response['data']),
            "answer added successfully");
        }
        return $this->sendError("Something went wrong !",$response['errors']);
    }

    public function supportTicketAnswers_get($id){
        $response = $this->support_ticketRepository->get_answersById($id);
        if($response['success']){
            return $this->sendResponse(Support_tickets_answerResource::collection($response['data']),
            "Support ticket answers retreived successfully");
        }
        return $this->sendError("Something went wrong !",$response['errors']);
    }

}
