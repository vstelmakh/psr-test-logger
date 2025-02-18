<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger;

class Criteria implements \Stringable
{
    /** @var array<string> */
    private array $criteria = [];

    public function add(string $criterion): void
    {
        if ($criterion === '') {
            throw new \DomainException('Criterion must not be empty.');
        }

        $this->criteria[] = $criterion;
    }

    public function __toString(): string
    {
        return implode(' and ', $this->criteria);
    }
}
