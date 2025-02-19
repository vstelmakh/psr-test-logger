<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Assert;

use VStelmakh\TestLogger\Log\Log;
use VStelmakh\TestLogger\Log\Collection;

class Matcher
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Collection $logs,
        private readonly Criteria $criteria = new Criteria(),
    ) {}

    /**
     * @return array<Log>
     */
    public function getMatches(): array
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
        $matches = $this->logs->filter($callback);
        $matches->assertNotEmpty(sprintf('No logs matching %s.', $this->criteria));
        return new self($matches, $this->criteria);
    }
}
