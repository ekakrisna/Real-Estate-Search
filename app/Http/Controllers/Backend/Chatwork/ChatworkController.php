<?php

namespace App\Http\Controllers\Backend\Chatwork;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

/**
 * this class was used at test develop.
 * It is not used current system.
 */
class ChatworkController extends Controller
{
    const API_URL_SEND_MESSAGE = 'https://api.chatwork.com/v2/rooms/%d/messages';
    const API_URL_READ_ALLROOM = 'https://api.chatwork.com/v2/rooms';

    public function index()
    {
        $apiToken = \config('chatwork.grune_api_key');

        // FOR GET ROOM ID READ ROOM 
        $headers = ['headers' => ['X-ChatWorkToken' => $apiToken]];
        $client = new Client($headers);
        $url = self::API_URL_READ_ALLROOM;
        $request = $client->request('GET', $url, ['verify' => false]);
        $data = json_decode($request->getBody(), true); // returns an array        
        dd($data);
    }

    public function message()
    {
        $data['page_title']     = "Send Message";        
        return view('backend.chatwork.index.index', $data);
    }

    public function send(Request $request)
    {
        $apiToken = \config('chatwork.grune_api_key');
        $response = new \stdClass;
        $headers = ['headers' => ['X-ChatWorkToken' => $apiToken], 'form_params' => ['body' => $request->message]];
        // 163941623 ID Yoga Pratama
        $client = new Client($headers);
        $url = sprintf(self::API_URL_SEND_MESSAGE, '229023270');
        $request = $client->request('POST', $url, ['verify' => false]);
        if ($request) {
            $response->status = "success";
        }
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }
}
