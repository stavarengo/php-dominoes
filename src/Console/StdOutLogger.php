<?php

declare(strict_types=1);


namespace Console;


use Psr\Log\LoggerInterface;

class StdOutLogger implements LoggerInterface
{

    /**
     * @var resource
     */
    private $stream;

    public function __construct()
    {
        $this->stream = fopen('php://output', 'a');
        register_shutdown_function(fn() => fclose($this->stream));
    }

    public function emergency($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function alert($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function critical($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function error($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function warning($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function notice($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function info($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function debug($message, array $context = [])
    {
        $this->log(__FUNCTION__, $message, $context);
    }

    public function log($level, $message, array $context = [])
    {
        $line = sprintf('[%s] %s', strtoupper($level), $message);
        $line ??= json_encode($context, JSON_PRETTY_PRINT);

        fwrite($this->stream, $line . PHP_EOL);
    }
}