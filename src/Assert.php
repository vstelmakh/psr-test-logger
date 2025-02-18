<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger;

use Psr\Log\LogLevel;

class Assert
{
    public function __construct(
        private readonly LogCollection $logs,
    ) {}

    public function hasLogs(): Matcher
    {
        $matcher = new Matcher($this->logs);
        $this->assertHasLogs();
        return $matcher;
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

    private function assertHasLogs(): void
    {
        if (!$this->logs->isEmpty()) {
            if (class_exists(\PHPUnit\Framework\Assert::class, false)) {
                \PHPUnit\Framework\Assert::assertTrue(true);
            }

            return;
        }

        $message = 'Logger has no logs.';

        if (class_exists(\PHPUnit\Framework\Assert::class, false)) {
            \PHPUnit\Framework\Assert::fail($message);
        } else {
            throw new \RuntimeException($message);
        }
    }
}
