<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Log;

/**
 * @internal
 */
class Collection
{
    /** @var array<Log> */
    private array $logs = [];

    /**
     * Add log record to collection.
     *
     * @param Log $log
     * @return void
     */
    public function add(Log $log): void
    {
        $this->logs[] = $log;
    }

    /**
     * Returns count of log records in collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->logs);
    }

    /**
     * Returns "true" if collection is empty, otherwise "false".
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->logs);
    }

    /**
     * Returns all collection records as array.
     *
     * @return array<Log>
     */
    public function toArray(): array
    {
        return $this->logs;
    }

    /**
     * Filter collection with provided callback.
     *
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
