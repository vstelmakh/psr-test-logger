<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Assert;

use VStelmakh\TestLogger\Log\Log;
use VStelmakh\TestLogger\Log\Collection;

class Matcher
{
    private Collection $logs;
    private readonly Criteria $criteria;
    private readonly bool $isAssert;

    /**
     * @internal
     */
    public function __construct(Collection $logs, bool $isAssert) {
        $this->logs = $logs;
        $this->criteria = new Criteria();
        $this->isAssert = $isAssert;

        $this->assertNotEmpty();
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
        $callback = fn (Log $log) => $log->level === $level;
        return $this->match($criterion, $callback);
    }

    public function withMessage(\Stringable|string $message): self
    {
        $criterion = sprintf('message "%s"', $message);
        $callback = fn (Log $log) => (string) $log->message === (string) $message;
        return $this->match($criterion, $callback);
    }

    /**
     * @param callable(Log): bool $callback
     */
    private function match(string $criterion, callable $callback): self
    {
        $this->criteria->add($criterion);
        $this->logs = $this->logs->filter($callback);
        $this->assertNotEmpty();
        return $this;
    }

    private function assertNotEmpty(): void
    {
        if ($this->isAssert) {
            $message = sprintf('No logs matching %s.', $this->criteria) ?: 'Logger has no logs.';
            $this->logs->isEmpty() ? Proxy::fail($message) : Proxy::success();
        }
    }
}
