<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Assert;

use VStelmakh\PsrTestLogger\Log\Collection;

/**
 * @internal
 */
class NullAsserter implements AsserterInterface
{
    public function assert(Collection $logs): void
    {
        // do nothing
    }

    public function addCriterion(string $criterion): void
    {
        // do nothing
    }
}
