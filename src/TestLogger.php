<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger;

use Psr\Log\AbstractLogger;
use VStelmakh\TestLogger\Assert\Assert;
use VStelmakh\TestLogger\Log\Filter;
use VStelmakh\TestLogger\Log\Log;
use VStelmakh\TestLogger\Log\Collection;

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

    public function assert(): Assert
    {
        return new Assert($this->logs);
    }

    public function filter(): Filter
    {
        return new Filter($this->logs);
    }
}
