<?php

namespace Baijunyao\LaravelFlash;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Baijunyao\LaravelFlash\Middleware\LaravelFlash;

class FlashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/resources/statics' => public_path('statics'),
        ], 'public');
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware(LaravelFlash::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
