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
     * Assert if logger contains logs fulfilling the matching conditions.
     *
     * @param string $message Custom assertation error message.
     * @return Assert
     */
    public function assert(string $message = ''): Assert
    {
        return new Assert($this->logs, $message);
    }

    /**
     * Filter logs containing in logger that fulfilling the matching conditions.
     *
     * @return Filter
     */
    public function filter(): Filter
    {
        return new Filter($this->logs);
    }
}
