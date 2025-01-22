<?php

namespace App\Models\Enums;

enum UserStatus: string
{
    case ONLINE = 'Online';
    case BUSY = 'Busy';
    case IDLE = 'Idle';
    case AFK = 'AFK';
    case OFFLINE = 'Offline';

    public static function getIndex(UserStatus $status): int
    {
        $cases = self::cases();
        return array_search($status, $cases, true);
    }
}
