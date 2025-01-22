<?php

use App\ClientChannels\SignalR\SignalR;

return [

    'port' => env('WEBSOCKET_PORT', 8080),
    'host' => env('WEBSOCKET_HOST', '127.0.0.1'),

    'channels' => [
        'signalr' => [
            'class' => SignalR::class,
        ]
    ]
];
