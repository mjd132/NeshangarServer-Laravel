<?php

namespace App\Services;

use App\Events\User\UserStatusExpiredEvent;
use App\Models\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use WebSocket\Connection;
use WebSocket\Server;

class WebSocketServer extends Server
{
    private static array $clients;

    public function __construct()
    {
        $port = config('websocket.port');
        parent::__construct($port);
    }

    public static function createClient(string $channelAddress, Connection $connection, int|null $userId = null): Client
    {
        $client = new Client();
        $client->userId = $userId;
        $client->channelAddress = $channelAddress;
        $client->connection = $connection;
        self::$clients[$connection->getRemoteName()] = $client;

        return $client;
    }

    public static function setUserForClient(int $userId, Connection $connection): void
    {
        $client = self::getClientByConnection($connection);
        $client->userId = $userId;
    }

    public static function getClientByConnection(Connection $connection): Client|null
    {
        return self::$clients[$connection->getRemoteName()];
    }

    public static function deleteClient(Connection $connection): void
    {
        unset(self::$clients[$connection->getRemoteName()]);
    }

    public static function countByUserId(int $userId): int
    {
        $filteredClients = array_filter(self::$clients, function ($client) use ($userId) {
            return $client->userId === $userId;
        });
        return count($filteredClients);
    }

    public static function getClients(): array
    {
        return self::$clients;
    }

    public function buildClient(Connection $connection): Client
    {
        return new Client(base64_encode($connection->getRemoteName()), $connection, 'signalr');
    }

    public function sendMessage(Client|null $client, $data)
    {

    }
}
