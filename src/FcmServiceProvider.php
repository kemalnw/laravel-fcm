<?php

namespace Kemalnw\Fcm;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;

class FcmServiceProvider extends BaseServiceProvider
{
    /**
     * Register the config for publishing
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/fcm.php' => config_path('fcm.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('fcm', Fcm::class);
        Notification::resolved(function (ChannelManager $service) {
            $service->extend('fcm', function () {
                return new FcmChannel();
            });
        });
    }
}
