<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('captcha', function ($app) {
            $options = $app['config']['captcha.options'] ?? [];
            $options['handler'] = new \App\Support\CaptchaSocketHandler();

            return new \Anhskohbo\NoCaptcha\NoCaptcha(
                $app['config']['captcha.secret'],
                $app['config']['captcha.sitekey'],
                $options
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}

