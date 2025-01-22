<?php

namespace App\ClientChannels\SignalR\Entities;

use App\Models\Enums\UserStatus;
use App\Models\User;
use DateInterval;
use DateTime;

class UserClient
{
    public int $id;
    public string $name;
    public string|null $token;
    public int $status;
    public string|null $expireInterval = null;
    public string|null $expiredAt = null;
    public DateTime|null $statusUpdatedAt = null;
    public DateTime|null $lastOnlineAt = null;
    public DateInterval|null $remainingTime = null;
    public string|null $remainingTimeString = null;

    public static function makeFromUserWithoutToken(User $user): self
    {
        $userClient = new self();
        $userClient->id = $user->id;
        $userClient->name = $user->name;
        $userClient->status = UserStatus::getIndex($user->status);
        $userClient->expireInterval = $user->getExpirationInterval() !== null
            ? UserClient::convertDateIntervalToTimeSpanFormat($user->getExpirationInterval())
            : null;
        $userClient->expiredAt = $user->getExpiredAt()?->format('Y-m-d\TH:i:s.uP');
        $userClient->statusUpdatedAt = null;
        $userClient->lastOnlineAt = null;
        $userClient->remainingTime = null;
        $userClient->remainingTimeString = null;
        return $userClient;
    }

    public static function makeFromUser(User $user): self
    {
        $userClient = new self();
        $userClient->id = $user->id;
        $userClient->name = $user->name;
        $userClient->token = $user->token;
        $userClient->status = UserStatus::getIndex($user->status);
        $userClient->expireInterval = null;
        $userClient->expiredAt = null;
        $userClient->statusUpdatedAt = null;
        $userClient->lastOnlineAt = null;
        $userClient->remainingTime = null;
        $userClient->remainingTimeString = null;
        return $userClient;
    }

    public static function convertDateIntervalToTimeSpanFormat(DateInterval $interval)
    {
        $days = ($interval->y * 365) + ($interval->m * 30) + $interval->d;

        return sprintf('%d.%02d:%02d:%02d', $days, $interval->h, $interval->i, $interval->s);
    }

}
