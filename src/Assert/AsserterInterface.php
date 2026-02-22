<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Assert;

use VStelmakh\PsrTestLogger\Log\Collection;

/**
 * @internal
 */
interface AsserterInterface
{
    /**
     * Assert a provided collection in compliance with asserter requirements.
     *
     * @param Collection $logs
     * @return void
     */
    public function assert(Collection $logs): void;

    /**
     * Add a matching criterion to the matcher.
     *
     * @param string $criterion
     * @return void
     */
    public function addCriterion(string $criterion): void;
}
