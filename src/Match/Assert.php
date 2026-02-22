<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Match;

use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\Assert\HasLogsAsserter;
use VStelmakh\PsrTestLogger\Log\Collection;

final class Assert
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Collection $logs,
        private readonly string $message = '',
    ) {}

    /**
     * Assert that the logger contains (any) logs.
     *
     * @return Matcher
     */
    public function hasLog(): Matcher
    {
        return new Matcher($this->logs, new HasLogsAsserter($this->message));
    }

    /**
     * Assert that the logger contains logs with the level "debug".
     *
     * @return Matcher
     */
    public function hasDebug(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::DEBUG);
    }

    /**
     * Assert that the logger contains logs with the level "info".
     *
     * @return Matcher
     */
    public function hasInfo(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::INFO);
    }

    /**
     * Assert that the logger contains logs with the level "notice".
     *
     * @return Matcher
     */
    public function hasNotice(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::NOTICE);
    }

    /**
     * Assert that the logger contains logs with the level "warning".
     *
     * @return Matcher
     */
    public function hasWarning(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::WARNING);
    }

    /**
     * Assert that the logger contains logs with the level "error".
     *
     * @return Matcher
     */
    public function hasError(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::ERROR);
    }

    /**
     * Assert that the logger contains logs with the level "critical".
     *
     * @return Matcher
     */
    public function hasCritical(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::CRITICAL);
    }

    /**
     * Assert that the logger contains logs with the level "alert".
     *
     * @return Matcher
     */
    public function hasAlert(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::ALERT);
    }

    /**
     * Assert that the logger contains logs with the level "emergency".
     *
     * @return Matcher
     */
    public function hasEmergency(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::EMERGENCY);
    }
}
