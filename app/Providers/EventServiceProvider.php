<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\LogUserLogin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [
            LogUserLogin::class,
        ],
    ];

    /**
     * The events and their handlers.
     *
     * @var array
     */
    protected $subscribe = [];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}