<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Assert;

use VStelmakh\TestLogger\Log\Collection;

interface AsserterInterface
{
    public function assert(Collection $logs, ?string $criterion): void;
}
