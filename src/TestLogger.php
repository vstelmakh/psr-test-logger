<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger;

use Psr\Log\AbstractLogger;

class TestLogger extends AbstractLogger
{
    private LogCollection $logs;

    public function __construct()
    {
        $this->logs = new LogCollection();
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $log = new Log($level, $message, $context);
        $this->logs->add($log);
    }

    public function assert(): Assert
    {
        return new Assert($this->logs);
    }
}
