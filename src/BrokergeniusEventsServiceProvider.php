<?php

namespace Mubin\Events;

use Illuminate\Support\ServiceProvider;

class BrokergeniusEventsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/bgevents.php' => config_path('bgevents.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('Mubin\Events\EventsController');
    }
}
