<?php

namespace App\Lib;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class Chatwork
{
    const API_URL_SEND_MESSAGE = 'https://api.chatwork.com/v2/rooms/%d/messages';
    const API_URL_READ_ALLROOM = 'https://api.chatwork.com/v2/rooms';

    /**
     * embeed template text to publisher site name  then send message text to specific chat room.
     *
     * @param [String] $publisherName : It is used embeed to template text.
     * @param [Int] $messageContent : send message of content.
     * @return void
     */
    public function sendMessage($message)
    {
        $apiToken = \config('chatwork.grune_api_key');
        $response = new \stdClass;
        $headers = ['headers' => ['X-ChatWorkToken' => $apiToken], 'form_params' => ['body' => $message]];
        $client = new Client($headers);
        $url = sprintf(self::API_URL_SEND_MESSAGE, '229023270');
        $request = $client->request('POST', $url, ['verify' => false]);
        if ($request) {
            $response->status = "success";
        }
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }
}
