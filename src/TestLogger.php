<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger;

use Psr\Log\AbstractLogger;
use VStelmakh\PsrTestLogger\Log\Collection;
use VStelmakh\PsrTestLogger\Log\Log;
use VStelmakh\PsrTestLogger\Match\Assert;
use VStelmakh\PsrTestLogger\Match\Filter;

class TestLogger extends AbstractLogger
{
    private Collection $logs;

    public function __construct()
    {
        $this->logs = new Collection();
    }

    /**
     * @param $level
     * @param \Stringable|string $message
     * @param array<mixed> $context
     * @return void
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $log = new Log($level, $message, $context);
        $this->logs->add($log);
    }

    /**
     * Returns all logs containing in logger.
     *
     * @return array<Log>
     */
    public function getLogs(): array
    {
        return $this->logs->toArray();
    }

    /**
     * Fluent interface to assert logger contains logs with specified conditions.
     *
     * @param string $message Custom assertation error message.
     * @return Assert
     */
    public function assert(string $message = ''): Assert
    {
        return new Assert(clone $this->logs, $message);
    }

    /**
     * Fluent interface to filter logs by conditions.
     *
     * @return Filter
     */
    public function filter(): Filter
    {
        return new Filter(clone $this->logs);
    }
}
