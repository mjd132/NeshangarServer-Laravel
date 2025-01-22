<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use WebSocket\Connection;

class Client
{
    public int|null $userId = null;
    public string $channelAddress;
    public string|null $channelName = null;
    public Connection $connection;
//    protected $fillable = [
//        'user_id',
//        'connection_name',
//        'channel_name',
//        'token'
//    ];
//
//    public function setConnectionInstance(Connection $connection): void
//    {
//        $this->connectionInstance = $connection;
//    }
//
//    public function getConnectionInstance() :Connection
//    {
//        return $this->connectionInstance;
//    }
//
//    public function user(): BelongsTo
//    {
//        return $this->belongsTo(User::class);
//    }
}
