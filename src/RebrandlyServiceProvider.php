<?php

namespace Spotawheel\Rebrandly;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use Spotawheel\Rebrandly\Client\RebrandlyClient;


class RebrandlyServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/rebrandly.php' => config_path('rebrandly.php')]);
    }
    
    public function register()
    {
        $this->app->bind('rebrandly', function () {
            return new RebrandlyClient(
                new Client(), 
                $this->app->make('config')->get('rebrandly.api_key', ''), 
                $this->app->make('config')->get('rebrandly.api_url', '')
            );
        });
    }

    public function provides()
    {
        return [RebrandlyClient::class, 'rebrandly'];
    }

}
