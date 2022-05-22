<?php

namespace App\Http\Repositories;

class NotificationsRepository
{
    public function sendMessage0($text, $answer){
        $response1 = [];
        $basic  = new \Vonage\Client\Credentials\Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
        $client = new \Vonage\Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS("213674771817", "Ticketus", $text)
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            $response1 = [
                'success' => true,
                'data' => $answer
            ];
        } else {
            $response1 =[
                'success' => false,
                'errors' =>"The message failed with status: " . $message->getStatus() . "\n"
            ];
        }
        return $response1;
    }

    public function sendPin0($text, $type){
        $pin = rand(100000, 999999);
        setcookie($type, $pin);
        $response = $this->sendMessage0("{$text}:{$pin}", "The PIN code was sent");
        return $response;
    }
}
