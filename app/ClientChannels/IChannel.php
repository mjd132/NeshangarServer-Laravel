<?php

namespace App\ClientChannels;

use App\Models\Client;
use App\Services\WebSocketServer;
use Psr\Http\Message\RequestInterface;
use WebSocket\Message\Text;

interface IChannel
{
    public function onReceivedMessage(Text $text);

    public function onSentMessage();

    public function sendMessage(array|string $message);

    public function sendUsers();

    public function onClose();

    public function onHandshake(RequestInterface $request);

    public static function checkMessageStructure(string $message);

}
