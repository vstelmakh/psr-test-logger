<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Assert;

use VStelmakh\PsrTestLogger\Log\Collection;

/**
 * @internal
 */
class HasLogsAsserter implements AsserterInterface
{
    /** @var array<string> */
    private array $criteria = [];
    private string $message;

    public function __construct(string $message = '')
    {
        $this->message = $message;
    }

    public function assert(Collection $logs): void
    {
        $logs->isEmpty() ? PHPUnitAssertProxy::fail($this->getMessage()) : PHPUnitAssertProxy::success();
    }

    public function addCriterion(string $criterion): void
    {
        $this->criteria[] = $criterion;
    }

    private function getMessage(): string
    {
        $prefix = $this->message !== '' ? $this->message . PHP_EOL : '';

        if (empty($this->criteria)) {
            return sprintf('%sFailed asserting that has logs.', $prefix);
        }

        $criteria = implode(' and ', $this->criteria);
        return sprintf('%sFailed asserting that has logs matching %s.', $prefix, $criteria);
    }
}
