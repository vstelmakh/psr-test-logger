<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Assert;

use VStelmakh\TestLogger\Log\Log;
use VStelmakh\TestLogger\Log\Collection;

class Matcher
{
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
        $this->criteria->add(sprintf('level "%s"', $level));
        $matches = $this->logs->filter(fn (Log $log) => $log->level === $level);
        $this->assertHasMatches($matches);
        return new self($matches, $this->criteria);
    }

    public function withMessage(\Stringable|string $message): self
    {
        $this->criteria->add(sprintf('message "%s"', $message));
        $matches = $this->logs->filter(fn (Log $log) => (string) $log->message === (string) $message);
        $this->assertHasMatches($matches);
        return new self($matches, $this->criteria);
    }

    private function assertHasMatches(Collection $logs): void
    {
        $logs->assertNotEmpty(sprintf('No logs matching %s.', $this->criteria));
    }
}
