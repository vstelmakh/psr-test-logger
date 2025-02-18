<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger;

class Matcher
{
    public function __construct(
        private readonly LogCollection $logs,
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

    private function assertHasMatches(LogCollection $logs): void
    {
        if (class_exists(\PHPUnit\Framework\Assert::class, false)) {
            $hasLogs = !$logs->isEmpty();

            \PHPUnit\Framework\Assert::assertTrue(
                $hasLogs,
                sprintf('No logs matching %s.', $this->criteria),
            );
        }
    }
}
