<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger;

class Log
{
    public function __construct(
        public readonly mixed $level,
        public readonly \Stringable|string $message,
        public readonly array $context = [],
    ) {}
}
