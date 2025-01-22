<?php

namespace App\ClientChannels\SignalR;

use App\ClientChannels\SignalR\Entities\UserClient;
use App\Models\Enums\UserStatus;
use App\Models\User;
use App\Services\WebSocketServer;
use Carbon\Carbon;
use DateInterval;
use DateTime;
use DateTimeInterface;
use Illuminate\Support\Facades\Cache;
use function PHPUnit\Framework\isNull;

trait MethodInvoker
{
    public function UserChanged($args)
    {

    }

    public function UserList()
    {
        $users = User::all();
        $usersClient = [];
        foreach ($users as $user) {
            $usersClient[] = UserClient::makeFromUserWithoutToken($user);
        }
        $message = [
            'type' => 1,
            'target' => 'UserList',
            'arguments' => [
                $usersClient,
                now()->format('Y-m-d\TH:i:s.uP')
            ]
        ];
        $this->sendMessage($message);
    }

    public function User(User $user): void
    {
        $userClient = UserClient::makeFromUser($user);
        $message = [
            'type' => 1,
            'target' => 'User',
            'arguments' => [
                $userClient,
                now()->format('Y-m-d\TH:i:s.uP')
            ]
        ];
        $this->sendMessage($message);
    }

    public function Login($args): void
    {
        $token = $args[0];

        if (!empty($token)) {
            $user = User::where('token', $token)->first();
            if ($user !== null) {

                if ($user->status === UserStatus::OFFLINE) {
                    $user->changeStatus(UserStatus::ONLINE);
                }

                $this->User($user);

                WebSocketServer::setUserForClient($user->id, $this->client->connection);
            }
        }
    }

    public function Register($args): void
    {
        $token = $args[0];
        $name = $args[1];

        if (!empty($token)) {
            $user = User::where('token', $token)->first();
            if ($user !== null) {
                return;
            }


            $user = User::create(['name' => $name, 'token' => $token]);

            if ($user !== null) {

                $user->changeStatus(UserStatus::ONLINE);
                $this->User($user);

                WebSocketServer::setUserForClient($user->id, $this->client->connection);
            }
        }

    }

    public function SetStatusViaTimer($args)
    {
        $id = $args[0]->id;

        $user = User::findOrFail($id);
        $status = $this->getStatusEnumByInt($args[0]->status);

        $user->changeStatus($status, $args[0]->expireInterval);
    }
}
