<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger;

use Psr\Log\AbstractLogger;
use VStelmakh\TestLogger\Log\Collection;
use VStelmakh\TestLogger\Log\Log;
use VStelmakh\TestLogger\Match\Assert;
use VStelmakh\TestLogger\Match\Filter;

class TestLogger extends AbstractLogger
{
    private Collection $logs;

    public function __construct()
    {
        $this->logs = new Collection();
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $log = new Log($level, $message, $context);
        $this->logs->add($log);
    }

    public function getLogs(): array
    {
        return $this->logs->toArray();
    }

    public function assert(string $message = ''): Assert
    {
        return new Assert($this->logs, $message);
    }

    public function filter(): Filter
    {
        return new Filter($this->logs);
    }
}
