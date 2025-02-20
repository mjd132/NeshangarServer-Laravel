<?php

namespace App\Providers;

use App\ClientChannels\ChannelManager;
use App\Logging\SanitizeSensitiveData;
use App\Services\ClientService;
use App\Services\WebSocketServer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use WebSocket\Server;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Log::getLogger()->pushProcessor(new SanitizeSensitiveData());

        $this->app->singleton(ChannelManager::class, function ($app) {
            return new ChannelManager();
        });

        $this->app->singleton(ClientService::class, function ($app) {
            return new ClientService(new ChannelManager());
        });

        $this->app->singleton(Server::class, fn() => new WebSocketServer());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
