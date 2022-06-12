<?php

namespace App\Http\Repositories;

class NotificationsRepository
{
    public static function sendMessage0($text, $answer){
        $response1 = [];
        $basic  = new \Vonage\Client\Credentials\Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
        $client = new \Vonage\Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS("213561471402", "Ticketus", $text)
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            $response1 = [
                'success' => true,
                'data' => $answer
            ];
        } else {
            $response1['success'] = false;
            $response1['errors'] = " The message failed with status: {$message->getStatus()}";
        }
        return $response1;
    }

    public static function sendPin0($text, $type){
        $pin = rand(100000, 999999);
        setcookie($type, $pin);
        return (new self)->sendMessage0("{$text}:{$pin}", "The PIN code was sent");
        
    }
}
