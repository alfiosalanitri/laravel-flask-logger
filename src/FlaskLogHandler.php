<?php

namespace AlfioSalanitri\FlaskLogger;

use Illuminate\Support\Facades\Http;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * Class FlaskLogHandler
 *
 * A custom Monolog handler that sends log messages to a Flask server
 * via HTTP. This handler is used by the "flask" log channel registered
 * through the FlaskLoggerServiceProvider.
 *
 * Example Flask server implementation:
 * https://github.com/alfiosalanitri/flask-log-monitor
 */
class FlaskLogHandler extends AbstractProcessingHandler
{
    /**
     * The base URL of the Flask server.
     */
    protected string $url;

    /**
     * The authentication token used in HTTP requests.
     */
    protected string $token;

    /**
     * Create a new FlaskLogHandler instance.
     *
     * @param  int|Level  $level   The minimum log level at which this handler will be triggered.
     * @param  bool       $bubble  Whether the messages that are handled can bubble up the stack.
     */
    public function __construct($level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        // Build the Flask endpoint URL and token from the package config.
        $this->url = config('flask-logger.channel.url') . '/log';
        $this->token = config('flask-logger.channel.token');
    }

    /**
     * Write the record down to the log.
     *
     * Sends the log message to the Flask server as a JSON payload.
     * In case of connection failure or timeout, the error is logged
     * to the local "daily" channel instead of throwing an exception.
     *
     * @param  array|LogRecord  $record  The log record to write.
     * @return void
     */
    protected function write(array|LogRecord $record): void
    {
        try {
            Http::withToken($this->token)
                ->timeout(2)
                ->post($this->url, [
                    'level' => $record['level_name'],
                    'message' => $record['message'],
                    'context' => isset($record['context']) ? $record['context'] : [],
                ]);
        } catch (\Throwable $e) {
            // If sending to Flask fails, log the error locally to avoid breaking the app.
            \Log::channel('daily')->debug('Error sending log to Flask: ' . $e->getMessage());
        }
    }
}
