<?php

namespace AlfioSalanitri\FlaskLogger;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

/**
 * Class FlaskLoggerServiceProvider
 *
 * Registers the Flask log channel and merges it automatically
 * into the existing Laravel logging configuration.
 *
 * This allows developers to use `Log::channel('flask')` or
 * include `flask` in their LOG_STACK environment variable
 * without manually editing `config/logging.php`.
 */
class FlaskLoggerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 1. Merge the package’s base configuration file.
        //    This ensures that config('flask-logger') is available.
        $this->mergeConfigFrom(
            __DIR__ . '/../config/flask-logger.php',
            'flask-logger'
        );

        // 2. Allow publishing of the config file for user customization.
        //    Users can run `php artisan vendor:publish --tag=config`
        //    to publish the file to config/flask-logger.php.
        $this->publishes([
            __DIR__ . '/../config/flask-logger.php' => config_path('flask-logger.php'),
        ], 'config');

        // 3. Merge the "flask" channel into Laravel's native logging configuration.
        //    This makes the channel available globally, even during bootstrap.
        $this->app->booting(function () {
            $loggingConfig = $this->app['config']->get('logging.channels', []);

            // Only add the channel if it doesn't already exist
            if (!array_key_exists('flask', $loggingConfig)) {
                $loggingConfig['flask'] = config('flask-logger.channel');
                $this->app['config']->set('logging.channels', $loggingConfig);
            }
        });

        // 4. Register the custom "flask" driver with Laravel’s LogManager.
        //    This defines how Laravel should create and handle the Flask channel.
        $this->app['log']->extend('flask', function ($app, array $config = []) {
            $merged = array_merge(config('flask-logger.channel'), $config);
            return new Logger('flask', [new FlaskLogHandler($merged['level'])]);
        });
    }
}
