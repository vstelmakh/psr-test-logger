<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Assert;

use VStelmakh\PsrTestLogger\Log\Collection;

/**
 * @internal
 */
class NullAsserter implements AsserterInterface
{
    public function assert(Collection $logs, ?string $criterion): void
    {
        // do nothing
    }
}
