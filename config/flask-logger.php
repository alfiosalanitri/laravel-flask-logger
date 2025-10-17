<?php

return [

    'channel' => [
        'driver' => 'monolog',
        'handler' => \AlfioSalanitri\FlaskLogger\FlaskLogHandler::class,
        'level' => env('LOG_FLASK_LEVEL', 'debug'),
        'url' => env('LOG_FLASK_URL', 'http://localhost:5000'),
        'token' => env('LOG_FLASK_TOKEN', ''),
    ],

];
