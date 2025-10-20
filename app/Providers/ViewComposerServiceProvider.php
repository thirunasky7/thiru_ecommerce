<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\StoreMenuComposer;
use App\View\Composers\AdminLanguageComposer;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('themes.*', StoreMenuComposer::class);
        View::composer('admin.*', AdminLanguageComposer::class);
    }
}
