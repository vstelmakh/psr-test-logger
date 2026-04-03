<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Assert;

use VStelmakh\PsrTestLogger\Log\Collection;

/**
 * @internal
 */
final class HasNoLogsAsserter implements AsserterInterface
{
    /** @var array<string> */
    private array $criteria = [];
    private readonly string $message;

    public function __construct(string $message = '')
    {
        $this->message = $message;
    }

    #[\Override]
    public function assert(Collection $logs): void
    {
        $logs->isEmpty() ? PHPUnitAssertProxy::success() : PHPUnitAssertProxy::fail($this->getMessage());
    }

    #[\Override]
    public function addCriterion(string $criterion): void
    {
        $this->criteria[] = $criterion;
    }

    private function getMessage(): string
    {
        $prefix = $this->message !== '' ? $this->message . PHP_EOL : '';

        if ($this->criteria === []) {
            return sprintf('%sFailed asserting that has no logs.', $prefix);
        }

        $criteria = implode(' and ', $this->criteria);
        return sprintf('%sFailed asserting that has no logs matching %s.', $prefix, $criteria);
    }
}
