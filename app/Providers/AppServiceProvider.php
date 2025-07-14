<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View; // NOUVEAU : Import de la facade View
use App\Http\ViewComposers\UnreadMessageCountComposer; // NOUVEAU : Import de votre View Composer

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // NOUVEAU : Enregistrement de votre View Composer
        View::composer('layouts.app', UnreadMessageCountComposer::class);
    }
}
