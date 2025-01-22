<?php

namespace App\ClientChannels;

use App\ClientChannels\Exceptions\ChannelNotFoundException;
use App\Models\Client;
use Psr\Http\Message\RequestInterface;
use WebSocket\Message\Text;

class ChannelManager
{
    protected array $channels = [];

    public function __construct()
    {
        $this->channels = config('websocket.channels');
    }

    /**
     * @param string $channelName
     * @return mixed
     * @throws ChannelNotFoundException
     */
    public function getChannel(string $channelName): mixed
    {
        if (array_key_exists($channelName, $this->channels)) {
            return $this->channels[$channelName]['class']::getChannel();
        }
        throw new ChannelNotFoundException($channelName);
    }

    /**
     * @param string $message
     * @return IChannel|null
     * @throws ChannelNotFoundException
     */
    public function getChannelByAnalyzeMessageStructure(string $message): IChannel|null
    {

        foreach ($this->channels as $channelName => $channel) {
            if ($channel['class']::checkMessageStructure($message)) {
                return $this->getChannel($channelName);
            }
        }

        return null;
    }

    /**
     * @param RequestInterface $request
     * @return IChannel|null
     * @throws ChannelNotFoundException
     */
    public function getChannelAddressByHeaders(RequestInterface $request): string|null
    {
        if (empty($request->getHeader('client'))) {
            return null;
        }
        $channelName = $request->getHeader('client')[0] ?? null;

        if ($channelName === null) {
            throw new ChannelNotFoundException($channelName);
        }
        return $this->channels[$channelName]['class'];
    }

    public static function getChannelByClient(Client $client): IChannel|null
    {
        $channelName = $client->channelAddress;
        if ($channelName === null) {
            throw new ChannelNotFoundException($channelName);
        }
        return new $channelName($client);
    }
}
