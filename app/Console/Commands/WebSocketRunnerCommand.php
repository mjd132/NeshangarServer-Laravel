<?php

namespace App\Console\Commands;

use App\Client\ClientManager;
use App\Events\WebSocket\OnDisconnect;
use App\Events\WebSocket\OnHandshake;
use App\Events\WebSocket\OnMessageReceived;
use App\Models\Enums\UserStatus;
use App\Models\User;
use App\Services\WebSocketServer;
use App\WebSocket\Middleware\HandshakeValidatorMiddleware;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use TypeError;
use WebSocket\Connection;
use WebSocket\Exception\CloseException;
use WebSocket\Message\Message;
use WebSocket\Middleware\CloseHandler;
use WebSocket\Middleware\PingResponder;
use WebSocket\Server;

class WebSocketRunnerCommand extends Command
{
    protected $signature = 'websocket:start {--D|debug} {--L|logger}';

    protected $description = 'Start websocket server';


    protected WebSocketServer $wss;

    public function __construct(WebSocketServer $webSocketServer, protected ClientManager $clientManager)
    {
        parent::__construct();
        $this->wss = $webSocketServer;
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        $isDebug = $this->option('debug');
        $isLogger = $this->option('logger');

        $logger = Log::channel(config('logging.default'));
        $isLogger ? $this->wss->setLogger($logger) : null;

        $this->setOfflineStatusForAllUsers();

        $this->wss
            ->addMiddleware(new HandshakeValidatorMiddleware())
            ->addMiddleware(new CloseHandler())
            ->addMiddleware(new PingResponder())
            ->onHandshake(function (Server $server, Connection $connection, RequestInterface $request, ResponseInterface $response) use ($logger, $isDebug) {
                if ($isDebug) {
                    $logger->info("A client trying to handshake");
                }
                OnHandshake::dispatch($connection, $request, $response);
            })
            ->onText(function (Server $server, Connection $connection, Message $message) use ($logger, $isDebug) {
                $text = $message->getContent();

                if ($isDebug) {
                    $logger->info("Message Received ({$connection->getRemoteName()}): {$text}");
                }

                OnMessageReceived::dispatch($message, $connection);
            })
            ->onDisconnect(function (Server $server, Connection $connection) use ($logger, $isDebug) {
                if ($isDebug) {
                    $logger->info("({$connection->getRemoteName()}): Disconnected!");
                }
                OnDisconnect::dispatch($connection);
            })->onError(function (Server $server, Connection|null $connection, Exception|TypeError $e) use ($logger, $isDebug) {
                if ($isDebug && $connection !== null) {
                    $logger->info("({$connection->getRemoteName()}): Disconnected!");
                }
                if ($connection !== null && $e->getMessage() === 'Connection has unexpectedly closed') {

                    OnDisconnect::dispatch($connection);
                }
            });

        $isLogger ? $logger->info('WebSocket server configuration:', [
            'port' => $this->wss->getPort(),
            'scheme' => $this->wss->getScheme(),
            'timeout' => $this->wss->getTimeout(),
            'frame_size' => $this->wss->getFrameSize(),
            'ssl' => $this->wss->isSsl(),
        ]) : null;

        $this->wss->start();

    }

    private function setOfflineStatusForAllUsers()
    {
        User::query()->update(['status' => UserStatus::OFFLINE, 'expired_at' => null]);
    }
}
