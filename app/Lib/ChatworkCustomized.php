<?php

namespace App\Lib;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Log;

class ChatworkCustomized
{
    const API_URL_SEND_MESSAGE = 'https://api.chatwork.com/v2/rooms/%d/messages';
    const API_URL_READ_ALLROOM = 'https://api.chatwork.com/v2/rooms';

    /**
     * embeed template text to publisher site name  then send message text to specific chat room.
     *
     * @param [String] $roomID : It is used to determine the chatroom id.
     * @param [Int] $messageContent : send message of content.
     * @return void
     */
    public function sendMessage($roomID, $messageContent)
    {
        $apiToken = \config('chatwork.grune_api_key');
        Log::info("Will send message to chatwork roomID:$roomID apiToken:$apiToken");

        $response = new \stdClass;
        $headers = ['headers' => ['X-ChatWorkToken' => $apiToken], 'form_params' => ['body' => $messageContent]];
        $client = new Client($headers);
        $url = sprintf(self::API_URL_SEND_MESSAGE, $roomID);
        $request = $client->request('POST', $url, ['verify' => false]);
        if ($request) {
            $response->status = "success";
        }
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }
}
