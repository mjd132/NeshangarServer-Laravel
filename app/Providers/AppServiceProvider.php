<?php

namespace App\Providers;

use App\ClientChannels\ChannelManager;
use App\Services\ClientService;
use App\Services\WebSocketServer;
use Illuminate\Support\ServiceProvider;
use WebSocket\Server;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ChannelManager::class, function ($app) {
            return new ChannelManager();
        });

        $this->app->singleton(ClientService::class, function ($app) {
            return new ClientService(new ChannelManager());
        });

        $this->app->singleton(Server::class, function ($app) {
            return new WebSocketServer();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
