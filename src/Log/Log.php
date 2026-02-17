<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Log;

final class Log
{
    public function __construct(
        public readonly mixed $level,
        public readonly \Stringable|string $message,
        /** @var array<mixed> */
        public readonly array $context = [],
    ) {}
}
