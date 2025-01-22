<?php

namespace App\Services;

use App\ClientChannels\ChannelManager;
use App\ClientChannels\Exceptions\ChannelNotFoundException;
use App\Events\User\UserConnected;
use App\Events\User\UserStatusChanged;
use App\Events\WebSocket\OnHandshake;
use App\Models\Client;
use App\Models\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Psr\Http\Message\RequestInterface;
use WebSocket\Connection;
use WebSocket\Server;

class ClientService
{
    protected array $clientConnectionsMap;

    public function __construct(protected ChannelManager $channelManager)
    {
//        $this->clientConnectionsMap = json_decode(Cache::get('client_connections'));
    }

    public function __destruct()
    {
//        Cache::put('client_connections', json_encode($this->clientConnectionsMap));
    }

    public function getClients()
    {

    }

    /**
     * @param Connection $connection
     * @param RequestInterface $request
     * @return void
     * @throws ChannelNotFoundException
     */
    public function registerClientConnection(Connection $connection, RequestInterface $request): void
    {
        $channel = $this->channelManager->getChannelAddressByHeaders($request);
        $clientName = $connection->getRemoteName();
        $client = WebSocketServer::createClient($channel, $connection);
    }

    public function disconnectClient(Connection $connection): void
    {
        $client = WebSocketServer::getClientByConnection($connection);
        if ($client !== null && $client->userId !== null) {
            $countOfUserClient = WebSocketServer::countByUserId($client->userId);

            WebSocketServer::deleteClient($connection);

            if ($countOfUserClient === 1) {
                User::findOrFail($client->userId)->changeStatus(UserStatus::OFFLINE);
            }
        }
    }

    /**
     * @throws ChannelNotFoundException
     */
    public function sendUsersListToClients(): void
    {
        $usersList = User::all()->toArray();
        $clients = WebSocketServer::getClients();
        foreach ($clients as $client) {
            ChannelManager::getChannelByClient($client)->sendUsers($usersList);
        }
    }

    public function createClient(Client $client)
    {

    }

    public function changeClientStatus(Client $client)
    {

    }

    public function getClientByConnectionId(string $connectionId)
    {

    }

    public function getClientByConnection(Connection $connection)
    {

    }

    public function removeClient(string $connectionId)
    {

    }

    public function updateClient(string $connectionId, Client $newClient)
    {

    }

    private function getUser(array $token)
    {
        return User::where('token', $token)->first();
    }

}
