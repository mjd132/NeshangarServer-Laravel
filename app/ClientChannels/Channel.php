<?php

namespace App\ClientChannels;

use App\Models\Client;
use App\Services\WebSocketServer;
use Psr\Http\Message\RequestInterface;
use WebSocket\Message\Text;

class Channel
{

    public function onReceivedMessage(Text $text, Client $client)
    {

    }

    public function onSentMessage()
    {

    }

    public function onClose()
    {

    }

    public function onHandshake(RequestInterface $request)
    {

    }

    public static function checkMessageStructure(string $message)
    {

    }

    public function sendMessage(Client|WebSocketServer $receiver, array|string $message)
    {

    }

    public function sendUsers()
    {

    }
}
