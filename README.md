# Laravel Flask Logger

A log handler for Laravel that sends logs to a Flask server started with [https://github.com/alfiosalanitri/flask-log-monitor](https://github.com/alfiosalanitri/flask-log-monitor).

## Installation

```bash
composer require alfiosalanitri/laravel-flask-logger
```

## Configuration

You can publish the configuration file:

```bash
php artisan vendor:publish --tag=config
```

Or use the environment variables directly:

```env
LOG_CHANNEL=stack
LOG_STACK=flask,daily
LOG_FLASK_URL=http://localhost:5000
LOG_FLASK_TOKEN=secret
```

## Usage

```php
Log::channel('flask')->info('Test log sent to Flask!');
```

## License

MIT License © Alfio Salanitri