<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;


class NotificationsController extends Controller
{
    public static function sendMessage($text, $response){
        $basic  = new \Vonage\Client\Credentials\Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
        $client = new \Vonage\Client($basic);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS("213674771817", "Ticketus", $text)
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            return $response;
        } else {
            return "The message failed with status: " . $message->getStatus() . "\n";
        }
    }

    public static function sendPin(){
        $pin = rand(100000, 999999);
        setcookie('pin', $pin);
        $message = self::sendMessage("PIN code: {$pin}", "The PIN code was sent");
        return [$message];
    }
}
