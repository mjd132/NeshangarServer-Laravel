<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OnUpdateUserEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct()
    {
    }

    public function broadcastOn()
    {
        return new Channel('');
    }

    public function broadcastWith(): array
    {
        $user1 = new User();
        $user1->name = 'John Doe';
        $user2 = new User();
        $user2->name = 'Jane Doe Two';
        return [
            $user1,
            $user2
        ];
    }
}
