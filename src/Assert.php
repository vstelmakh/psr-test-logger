<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger;

use Psr\Log\LogLevel;

class Assert
{
    public function __construct(
        private readonly LogCollection $logs,
    ) {}

    public function hasLog(): Matcher
    {
        $matcher = new Matcher($this->logs);
        $this->assertHasLogs();
        return $matcher;
    }

    public function hasDebug(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::DEBUG);
    }

    public function hasInfo(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::INFO);
    }

    public function hasNotice(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::NOTICE);
    }

    public function hasWarning(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::WARNING);
    }

    public function hasError(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::ERROR);
    }

    public function hasCritical(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::CRITICAL);
    }

    public function hasAlert(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::ALERT);
    }

    public function hasEmergency(): Matcher
    {
        return $this->hasLog()->withLevel(LogLevel::EMERGENCY);
    }

    private function assertHasLogs(): void
    {
        if (class_exists(\PHPUnit\Framework\Assert::class, false)) {
            $hasLogs = !$this->logs->isEmpty();
            \PHPUnit\Framework\Assert::assertTrue($hasLogs, 'Logger has no logs.');
        }
    }
}
