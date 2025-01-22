<?php

namespace App\ClientChannels\SignalR;

use App\ClientChannels\Channel;
use App\ClientChannels\IChannel;
use App\Events\WebSocket\OnDisconnect;
use App\Events\WebSocket\OnMessageSent;
use App\Models\Client;
use App\Models\Enums\UserStatus;
use App\Services\WebSocketServer;
use PHPUnit\Framework\MockObject\Method;
use Psr\Http\Message\RequestInterface;
use stdClass;
use WebSocket\Message\Text;
use WebSocket\Server;

class SignalR implements IChannel
{
    use MethodInvoker;

    public Client $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function onReceivedMessage(Text $text): void
    {
        $msgParts = explode("\u{001e}", $text->getContent());
        foreach ($msgParts as $msgPart) {

            if (empty($msgPart)) {
                continue;
            }

            $msg = json_decode($msgPart);

            if (isset($msg->protocol, $msg->version) && $msg->protocol === 'json' && $msg->version === 1) {

                $this->sendMessage('{}');

                return;
            }
            switch ($msg->type) {
                case 1:
                    $this->invokeMethod($msg);
                    break;
                case 6:
                    $this->ping();
                    break;
            }
        }

    }

    public function onSentMessage()
    {
    }

    public function sendMessage(array|string $message): void
    {
        if (is_array($message)) {
            $message = json_encode($message);
        }
        $message .= chr(30);
        $text = new Text($message);
        $this->client->connection->send($text);
//        OnMessageSent::dispatch($message);
    }

    public function sendUsers(): void
    {
        $this->UserList();
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

    private function invokeMethod(stdClass $msg)
    {
        $method = $msg->target;
        if (method_exists(MethodInvoker::class, $method)) {
            $this->$method($msg->arguments);
        }
    }

    private function ping()
    {
        $this->sendMessage(['type' => 6]);
    }

    private function getStatusEnumByInt(int $status): UserStatus
    {
        return match ($status) {
            0 => UserStatus::ONLINE,
            1 => UserStatus::BUSY,
            2 => UserStatus::IDLE,
            3 => UserStatus::AFK,
            4 => UserStatus::OFFLINE,
        };
    }
}
