<?php

namespace App\ClientChannels\Exceptions;

use Exception;

class ChannelNotFoundException extends Exception
{

    public function __construct(string $channelName)
    {
        parent::__construct("Channel {$channelName} not found!");
    }
}
