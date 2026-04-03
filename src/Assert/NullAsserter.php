<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Assert;

use VStelmakh\PsrTestLogger\Log\Collection;

/**
 * @internal
 */
final class NullAsserter implements AsserterInterface
{
    #[\Override]
    public function assert(Collection $logs): void
    {
        // do nothing
    }

    #[\Override]
    public function addCriterion(string $criterion): void
    {
        // do nothing
    }
}
