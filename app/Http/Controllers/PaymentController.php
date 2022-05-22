<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\BaseController as BaseController;
use App\Http\Repositories\ReservationRepository as Tool;
use App\Http\Repositories\PaymentRepository;

class PaymentController extends BaseController
{
    private $paymentRepository;
    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function createPayment(Request $request){
        $response = $this->paymentRepository->create($request);
        if($response['success']){
            return $this->sendResponse($response['data'], "Payment made successfully");
        } else {
            return $this->sendError('Something went wrong', $response['errors']);
        }
    }
}
