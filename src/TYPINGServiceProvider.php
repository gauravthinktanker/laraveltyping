<?php

namespace Laraveltyping\Typing;

use Illuminate\Support\ServiceProvider;

class TYPINGServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        include __DIR__.'/routes/web.php';
        $this->app->make('Laraveltyping\Typing\TypingController');
        
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/views', 'typing');
        $this->loadViewsFrom(__DIR__.'/storage', 'typing');
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
        

    }
}
