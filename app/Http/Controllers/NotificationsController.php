<?php

namespace App\Http\Controllers;

class NotificationsController extends Controller
{
    public static function sendMessage($text, $answer){
        $basic  = new \Vonage\Client\Credentials\Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
        $client = new \Vonage\Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS("213674771817", "Ticketus", $text)
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            return $answer;
        } else {
            return "The message failed with status: " . $message->getStatus() . "\n";
        }
    }

    public static function sendPin($text, $type){
        $pin = rand(100000, 999999);
        setcookie($type, $pin);
        $message = self::sendMessage("{$text}:{$pin}", "The PIN code was sent");
        return [$message];
    }
}
