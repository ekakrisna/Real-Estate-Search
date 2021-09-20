<?php

use App\Lib\Chatwork;

/**
 * Send meessage via chatwork.
 * It is used from controller if layout of publisher site is brokend and data format is changed.
 */
if (!function_exists('sendMessageViaChatwork')) {
    function sendMessageViaChatwork($message)
    {
        $chatworkInstance = new Chatwork();
        $chatworkInstance->sendMessage($message);
    }
    function sendMessageViaChatworkScrapingReport($publisherName, $messageContent)
    {
        $chatworkInstance = new Chatwork();
        $chatworkInstance->sendMessage("サイト名 : " . $publisherName . ' ' . $messageContent);
    }
}
    // ----------------------------------------------------------------------
