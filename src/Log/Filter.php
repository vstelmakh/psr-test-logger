<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Log;

use Psr\Log\LogLevel;
use VStelmakh\TestLogger\Assert\Matcher;

class Filter
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Collection $logs,
    ) {}

    public function getAll(): Matcher
    {
        return new Matcher($this->logs);
    }

    public function getDebug(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::DEBUG);
    }

    public function getInfo(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::INFO);
    }

    public function getNotice(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::NOTICE);
    }

    public function getWarning(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::WARNING);
    }

    public function getError(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::ERROR);
    }

    public function getCritical(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::CRITICAL);
    }

    public function getAlert(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::ALERT);
    }

    public function getEmergency(): Matcher
    {
        return $this->getAll()->withLevel(LogLevel::EMERGENCY);
    }
}
