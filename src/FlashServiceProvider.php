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
        // 发布静态资源文件
        $this->publishes([
            __DIR__.'/resources/statics' => public_path('statics'),
        ], 'public');

        // 发布配置项
        $this->publishes([
            __DIR__.'/config/flash.php' => config_path('flash.php'),
        ]);

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
