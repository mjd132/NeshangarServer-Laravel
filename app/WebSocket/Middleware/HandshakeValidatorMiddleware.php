<?php
declare(strict_types=1);

namespace App\WebSocket\Middleware;

use WebSocket\Connection;
use WebSocket\Message\Message;
use WebSocket\Middleware\MiddlewareInterface;
use WebSocket\Middleware\ProcessIncomingInterface;
use WebSocket\Middleware\ProcessStack;

class HandshakeValidatorMiddleware implements MiddlewareInterface, ProcessIncomingInterface
{
    public function __toString(): string
    {
        return 'HandshakeValidatorMiddleware';
    }

    public function processIncoming(ProcessStack $stack, Connection $connection): Message
    {
        $request = $connection->getHandshakeRequest();

        $connectionHeader = $request->getHeaderLine('Connection');
        $upgradeHeader = $request->getHeaderLine('Upgrade');

        if (empty($request->getHeaders()) || strtolower($connectionHeader) !== 'upgrade' || strtolower($upgradeHeader) !== 'websocket') {
            // Log invalid handshake attempt
            logger()->warning('Invalid handshake attempt', [
                'X-Real-IP' => $request->getHeaders()['X-Real-IP'] ?? null,
                'Connection' => $connectionHeader,
                'Upgrade' => $upgradeHeader,
            ]);

            // Close the connection with a 426 status code
            $connection->close(426, 'Upgrade Required');
            throw new \RuntimeException('Invalid WebSocket handshake');
        }

        // Proceed with the next middleware
        return $stack->handleIncoming();
    }
}
