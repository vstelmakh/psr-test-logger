<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger;

class LogCollection
{
    /** @var array<Log> */
    private array $logs = [];

    public function add(Log $log): void
    {
        $this->logs[] = $log;
    }

    public function count(): int
    {
        return count($this->logs);
    }

    public function isEmpty(): bool
    {
        return empty($this->logs);
    }

    public function toArray(): array
    {
        return $this->logs;
    }

    /**
     * @param callable(Log): bool $callback
     */
    public function filter(callable $callback): self
    {
        $collection = new self();

        foreach ($this->logs as $log) {
            $isMatch = $callback($log);

            if ($isMatch) {
                $collection->add($log);
            }
        }

        return $collection;
    }
}
