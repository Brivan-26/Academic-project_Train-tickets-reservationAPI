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
        $ticket = $this->support_ticketRepository->createByRequest($request);
        return $this->sendResponse(new Support_ticketResource($ticket),
                                    "Support ticket created successfully");
    }

    public function supportTicket_assign($id){
        $ticket = $this->support_ticketRepository->assignById($id);
        if(!$ticket){
            return $this->sendError("Something went wrong");
        }
        return $this->sendResponse(new Support_ticketResource($ticket),
                                    "Support ticket assigned successfully");
    }


    public function supportTicket_disable($id){
        $ticket = $this->support_ticketRepository->render_inactiveById($id);
        if(!$ticket){
            return $this->sendError("Something went wrong");
        }
        return $this->sendResponse(new Support_ticketResource($ticket),
                                    "Support ticket disabled successfully");
    }

    public function supportTickets_get(){
        $tickets = $this->support_ticketRepository->get_supportTickets();
        return $this->sendResponse(Support_ticketResource::collection($tickets),
                                    "Support ticket retreived successfully");
    }

    public function supportTicketAnswer_add(Request $request, $id){
        $answers = $this->support_ticketRepository->add_answerByRequest($request, $id);
        if(!$answers){
            return $this->sendError("Something went wrong");
        }
        return $this->sendResponse(Support_tickets_answerResource::collection($answers),
                                    "answer added successfully");
    }

    public function supportTicketAnswers_get($id){
        $answers = $this->support_ticketRepository->get_answersById($id);
        if(!$answers){
            return $this->sendError("Something went wrong");
        }
        return $this->sendResponse(Support_tickets_answerResource::collection($answers),
                                    "answers retreived successfully");
    }

}
