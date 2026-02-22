<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Log;

/**
 * @internal
 */
final class Collection
{
    /** @var array<Log> */
    private array $logs = [];

    /**
     * Add log record to a collection.
     *
     * @param Log $log
     * @return void
     */
    public function add(Log $log): void
    {
        $this->logs[] = $log;
    }

    /**
     * Returns count of log records in a collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->logs);
    }

    /**
     * Returns "true" if a collection is empty, otherwise "false".
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->logs);
    }

    /**
     * Returns all collection records as an array.
     *
     * @return array<Log>
     */
    public function toArray(): array
    {
        return $this->logs;
    }

    /**
     * Filter the collection with the provided callback.
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
