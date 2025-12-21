<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\SignWell\SignWellService;

class SignwellServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SignWellService::class, function () {
            return new SignWellService(
                config('services.signwell.api_key'),
                config('services.signwell.base_url')
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/services.php' => config_path('services.php'),
        ], 'config');
    }
}
