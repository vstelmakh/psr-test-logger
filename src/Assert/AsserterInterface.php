<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Assert;

use VStelmakh\TestLogger\Log\Collection;

/**
 * @internal
 */
interface AsserterInterface
{
    /**
     * Assert provided collection in compliance with asserter requirements.
     *
     * @param Collection $logs
     * @param string|null $criterion
     * @return void
     */
    public function assert(Collection $logs, ?string $criterion): void;
}
