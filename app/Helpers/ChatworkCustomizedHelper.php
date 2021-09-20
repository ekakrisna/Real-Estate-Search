<?php

use App\Lib\ChatworkCustomized;
use Carbon\Carbon;

/**
 * Send meessage via chatwork.
 * It is used from controller if layout of publisher site is brokend and data format is changed.
 */
if (!function_exists('sendMessageViaChatworkCustomID')) {
    function sendMessageViaChatworkCustomID($roomID, $messageContent)
    {
        $chatworkInstance = new ChatworkCustomized();
        $chatworkInstance->sendMessage($roomID, $messageContent);
    }

    /**
     * $message: [required]
     * $error: [optional] Take over error object if you get it in try-catch.
     */
    function sendMessageOfErrorReport($message, $error = null)
    {
        if ($error) {
            $error_message = $message . " Error: " . $error->getMessage();
        } else {
        }
        $error_message = $message;
        Log::info(Carbon::now() . " - " . $error_message);
        // Group id '204191276' : "G-RealEstateSearch" group
        sendMessageViaChatworkCustomID('204191276', $error_message);
    }
}
    // ----------------------------------------------------------------------
