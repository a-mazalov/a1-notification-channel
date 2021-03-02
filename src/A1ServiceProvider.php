<?php

namespace A1\Channel;

use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class A1ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('a1.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'a1');

        $this->app->singleton('A1Client', function () {
            return new A1Client;
        });

        // Add new channel
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('a1', function ($app) {
                return new A1Channel(
                    $this->app->make(A1Client::class)
                );
            });
        });
    }
}
