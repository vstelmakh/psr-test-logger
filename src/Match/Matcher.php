<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Match;

use VStelmakh\TestLogger\Assert\AsserterInterface;
use VStelmakh\TestLogger\Log\Collection;
use VStelmakh\TestLogger\Log\Log;

class Matcher
{
    /**
     * @internal
     */
    public function __construct(
        private Collection $logs,
        private readonly AsserterInterface $asserter,
    ) {
        $this->asserter->assert($this->logs, null);
    }

    /**
     * @return array<Log>
     */
    public function getLogs(): array
    {
        return $this->logs->toArray();
    }

    public function withLevel(mixed $level): self
    {
        $criterion = sprintf('level "%s"', $level);
        $callback = fn(Log $log) => $log->level === $level;
        return $this->match($criterion, $callback);
    }

    public function withMessage(string $message): self
    {
        $criterion = sprintf('message "%s"', $message);
        $callback = fn(Log $log) => (string) $log->message === $message;
        return $this->match($criterion, $callback);
    }

    public function withMessageContains(string $needle): self
    {
        $criterion = sprintf('message contains "%s"', $needle);
        $callback = fn(Log $log) => str_contains((string) $log->message, $needle);
        return $this->match($criterion, $callback);
    }

    public function withMessageContainsIgnoreCase(string $needle): self
    {
        $criterion = sprintf('message contains ignore case "%s"', $needle);
        $callback = fn(Log $log) => mb_stripos((string) $log->message, $needle, 0, 'UTF-8') !== false;
        return $this->match($criterion, $callback);
    }

    public function withMessageStartsWith(string $prefix): self
    {
        $criterion = sprintf('message starts with "%s"', $prefix);
        $callback = fn(Log $log) => str_starts_with((string) $log->message, $prefix);
        return $this->match($criterion, $callback);
    }

    /**
     * @param callable(Log): bool $callback
     */
    private function match(string $criterion, callable $callback): self
    {
        $this->logs = $this->logs->filter($callback);
        $this->asserter->assert($this->logs, $criterion);
        return $this;
    }
}
