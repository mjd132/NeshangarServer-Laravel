<?php

namespace App\ClientChannels\SignalR\Entities\Enums;

enum TypeEnum: int
{
    case INVOCATION = 1;
    case STREAM_ITEM = 2;
    case COMPLETION = 3;
    case PING = 6;
    case CLOSE = 7;
}
