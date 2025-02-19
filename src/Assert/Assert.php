<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Assert;

use Psr\Log\LogLevel;
use VStelmakh\TestLogger\Log\Collection;

class Assert
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Collection $logs,
        private readonly string $message = '',
    ) {}

    public function hasLogs(): Matcher
    {
        return new Matcher($this->logs, new HasLogsAsserter($this->message));
    }

    public function hasDebug(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::DEBUG);
    }

    public function hasInfo(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::INFO);
    }

    public function hasNotice(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::NOTICE);
    }

    public function hasWarning(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::WARNING);
    }

    public function hasError(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::ERROR);
    }

    public function hasCritical(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::CRITICAL);
    }

    public function hasAlert(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::ALERT);
    }

    public function hasEmergency(): Matcher
    {
        return $this->hasLogs()->withLevel(LogLevel::EMERGENCY);
    }
}
