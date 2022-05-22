<?php

namespace App\Http\Controllers;

use App\Http\Repositories\NotificationsRepository;
use App\Http\Controllers\BaseController as BaseController;

class NotificationsController extends BaseController
{
    /*private $notificationsRepository;
    public function __construct(NotificationsRepository $notificationsRepository)
    {
        $this->notificationsRepository = $notificationsRepository;
    }*/

    public static function sendMessage($text, $answer){
        $notificationsRepository = new NotificationsRepository();
        $response = $notificationsRepository->sendMessage0($text, $answer);
        if($response['success']){
            return (new self)->sendResponse($response['data'], "message sent successfully");
        } else {
            return (new self)->sendError($response['errors']);
        }
    }

    public static function sendPin($text, $type){
        $notificationsRepository = new NotificationsRepository();
        $response = $notificationsRepository->sendPin0($text, $type);
        if($response['success']){
            return (new self)->sendResponse($response['data'], "PIN sent successfully");
        } else {
            return (new self)->sendError($response['errors']);
        }
    }
}
