<?php

namespace AlfioSalanitri\FlaskLogger;

use Illuminate\Support\Facades\Http;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class FlaskLogHandler extends AbstractProcessingHandler
{
    protected string $url;
    protected string $token;

    public function __construct($level = Level::Debug, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        $this->url = config('flask-logger.channel.url') . '/log';
        $this->token = config('flask-logger.channel.token');
    }

    protected function write(array|LogRecord $record): void
    {
        try {
            Http::withToken($this->token)
                ->timeout(2)
                ->post($this->url, [
                    'level' => $record['level_name'],
                    'message' => $record['message'],
                ]);
        } catch (\Throwable $e) {
            \Log::channel('daily')->debug('Error sending log to Flask: ' . $e->getMessage());
        }
    }
}
