<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Match;

use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\Assert\NullAsserter;
use VStelmakh\PsrTestLogger\Log\Collection;

final class Filter
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Collection $logs,
    ) {}

    /**
     * Filter all logs.
     *
     * @return Matcher
     */
    public function getAll(): Matcher
    {
        return new Matcher($this->logs, new NullAsserter());
    }

    /**
     * Filter logs with level "debug".
     *
     * @return Matcher
     */
    public function getDebug(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::DEBUG);
    }

    /**
     * Filter logs with level "info".
     *
     * @return Matcher
     */
    public function getInfo(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::INFO);
    }

    /**
     * Filter logs with level "notice".
     *
     * @return Matcher
     */
    public function getNotice(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::NOTICE);
    }

    /**
     * Filter logs with level "warning".
     *
     * @return Matcher
     */
    public function getWarning(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::WARNING);
    }

    /**
     * Filter logs with level "error".
     *
     * @return Matcher
     */
    public function getError(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::ERROR);
    }

    /**
     * Filter logs with level "critical".
     *
     * @return Matcher
     */
    public function getCritical(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::CRITICAL);
    }

    /**
     * Filter logs with level "alert".
     *
     * @return Matcher
     */
    public function getAlert(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::ALERT);
    }

    /**
     * Filter logs with level "emergency".
     *
     * @return Matcher
     */
    public function getEmergency(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::EMERGENCY);
    }
}
