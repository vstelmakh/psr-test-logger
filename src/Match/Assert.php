<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Match;

use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\Assert\HasLogsAsserter;
use VStelmakh\PsrTestLogger\Assert\HasNoLogsAsserter;
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

    /**
     * Assert that the logger contains no logs matching the applied filters.
     *
     * @return Matcher
     */
    public function hasNoLog(): Matcher
    {
        return new Matcher($this->logs, new HasNoLogsAsserter($this->message));
    }

    /**
     * Assert that the logger contains no logs with the level "debug" matching the applied filters.
     *
     * @return Matcher
     */
    public function hasNoDebug(): Matcher
    {
        return $this->hasNoLog()->withLevel(LogLevel::DEBUG);
    }

    /**
     * Assert that the logger contains no logs with the level "info" matching the applied filters.
     *
     * @return Matcher
     */
    public function hasNoInfo(): Matcher
    {
        return $this->hasNoLog()->withLevel(LogLevel::INFO);
    }

    /**
     * Assert that the logger contains no logs with the level "notice" matching the applied filters.
     *
     * @return Matcher
     */
    public function hasNoNotice(): Matcher
    {
        return $this->hasNoLog()->withLevel(LogLevel::NOTICE);
    }

    /**
     * Assert that the logger contains no logs with the level "warning" matching the applied filters.
     *
     * @return Matcher
     */
    public function hasNoWarning(): Matcher
    {
        return $this->hasNoLog()->withLevel(LogLevel::WARNING);
    }

    /**
     * Assert that the logger contains no logs with the level "error" matching the applied filters.
     *
     * @return Matcher
     */
    public function hasNoError(): Matcher
    {
        return $this->hasNoLog()->withLevel(LogLevel::ERROR);
    }

    /**
     * Assert that the logger contains no logs with the level "critical" matching the applied filters.
     *
     * @return Matcher
     */
    public function hasNoCritical(): Matcher
    {
        return $this->hasNoLog()->withLevel(LogLevel::CRITICAL);
    }

    /**
     * Assert that the logger contains no logs with the level "alert" matching the applied filters.
     *
     * @return Matcher
     */
    public function hasNoAlert(): Matcher
    {
        return $this->hasNoLog()->withLevel(LogLevel::ALERT);
    }

    /**
     * Assert that the logger contains no logs with the level "emergency" matching the applied filters.
     *
     * @return Matcher
     */
    public function hasNoEmergency(): Matcher
    {
        return $this->hasNoLog()->withLevel(LogLevel::EMERGENCY);
    }
}
