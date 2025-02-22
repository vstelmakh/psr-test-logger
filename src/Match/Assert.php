<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Match;

use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\Assert\HasLogsAsserter;
use VStelmakh\PsrTestLogger\Log\Collection;

class Assert
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Collection $logs,
        private readonly string $message = '',
    ) {}

    /**
     * Assert logger contains logs.
     *
     * @return Matcher
     */
    public function hasLogs(): Matcher
    {
        return new Matcher($this->logs, new HasLogsAsserter($this->message));
    }

    /**
     * Assert logger contains logs with level "debug".
     *
     * @return Matcher
     */
    public function hasDebug(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::DEBUG);
    }

    /**
     * Assert logger contains logs with level "info".
     *
     * @return Matcher
     */
    public function hasInfo(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::INFO);
    }

    /**
     * Assert logger contains logs with level "notice".
     *
     * @return Matcher
     */
    public function hasNotice(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::NOTICE);
    }

    /**
     * Assert logger contains logs with level "warning".
     *
     * @return Matcher
     */
    public function hasWarning(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::WARNING);
    }

    /**
     * Assert logger contains logs with level "error".
     *
     * @return Matcher
     */
    public function hasError(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::ERROR);
    }

    /**
     * Assert logger contains logs with level "critical".
     *
     * @return Matcher
     */
    public function hasCritical(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::CRITICAL);
    }

    /**
     * Assert logger contains logs with level "alert".
     *
     * @return Matcher
     */
    public function hasAlert(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::ALERT);
    }

    /**
     * Assert logger contains logs with level "emergency".
     *
     * @return Matcher
     */
    public function hasEmergency(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::EMERGENCY);
    }
}
