<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Assert;

use VStelmakh\TestLogger\Log\Collection;

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

    public function assert(Collection $logs, ?string $criterion): void
    {
        if ($criterion !== null) {
            $this->criteria[] = $criterion;
        }

        $logs->isEmpty() ? PHPUnitAssertProxy::fail($this->getMessage()) : PHPUnitAssertProxy::success();
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
