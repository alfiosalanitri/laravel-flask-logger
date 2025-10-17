<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Flask Log Channel Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration defines the custom "flask" log channel that sends
    | logs to a Flask server via HTTP. You can override these values using
    | environment variables or by publishing this file to your config folder.
    |
    | Example Flask log server:
    | https://github.com/alfiosalanitri/flask-log-monitor
    |
    */

    'channel' => [

        /*
        |--------------------------------------------------------------------------
        | Log Driver
        |--------------------------------------------------------------------------
        |
        | This channel uses Laravel's Monolog driver with a custom handler
        | (FlaskLogHandler) to send log messages over HTTP.
        |
        */
        'driver' => 'monolog',

        /*
        |--------------------------------------------------------------------------
        | Custom Handler
        |--------------------------------------------------------------------------
        |
        | The Monolog handler class responsible for sending logs to Flask.
        |
        */
        'handler' => \AlfioSalanitri\FlaskLogger\FlaskLogHandler::class,

        /*
        |--------------------------------------------------------------------------
        | Log Level
        |--------------------------------------------------------------------------
        |
        | The minimum log level at which messages will be sent to Flask.
        | Possible values: debug, info, notice, warning, error, critical, alert, emergency.
        |
        */
        'level' => env('LOG_FLASK_LEVEL', 'debug'),

        /*
        |--------------------------------------------------------------------------
        | Flask Server URL
        |--------------------------------------------------------------------------
        |
        | The base URL of the Flask server that receives the log messages.
        | By default, it points to localhost on port 5000.
        |
        */
        'url' => env('LOG_FLASK_URL', 'http://localhost:5000'),

        /*
        |--------------------------------------------------------------------------
        | Authentication Token
        |--------------------------------------------------------------------------
        |
        | If your Flask server requires authentication, you can provide
        | a token here. It will be sent using the "Authorization: Bearer"
        | HTTP header in every log request.
        |
        */
        'token' => env('LOG_FLASK_TOKEN', ''),
    ],

];
