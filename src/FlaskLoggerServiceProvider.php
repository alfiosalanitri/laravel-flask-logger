<?php

namespace AlfioSalanitri\FlaskLogger;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

class FlaskLoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/flask-logger.php',
            'flask-logger'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/flask-logger.php' => config_path('flask-logger.php'),
        ], 'config');

        $this->app['log']->extend('flask', function ($app, array $config = []) {
            $merged = array_merge(config('flask-logger.channel'), $config);
            return new Logger('flask', [new FlaskLogHandler($merged['level'])]);
        });
    }
}
