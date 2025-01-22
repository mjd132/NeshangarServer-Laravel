<?php

namespace App\Listeners\WebSocket;

use App\ClientChannels\ChannelManager;
use App\ClientChannels\Exceptions\ChannelNotFoundException;
use App\ClientChannels\IChannel;
use App\Events\WebSocket\OnMessageReceived;
use App\Models\Client;
use App\Services\WebSocketServer;

class OnMessageReceivedListener
{

    /**
     * Handle the event.
     * @param OnMessageReceived $event
     * @throws \Exception
     */
    public function handle(OnMessageReceived $event): void
    {
//        $this->channelManager->getChannel($event->client);
        $client = WebSocketServer::getClientByConnection($event->connection);
        if ($client == null) {
            throw new \Exception('Client not found');
        }
        /** @var IChannel $channel */
        $channel = $client->channelAddress;
        $channel = new $channel($client);
        $channel->onReceivedMessage($event->message, $client);
    }

}
