<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Match;

/**
 * @internal
 */
class ContextFormatter
{
    private const VALUE_MAX_LENGTH = 150;
    private const ELLIPSIS = '…';

    /**
     * @param array<mixed> $context
     */
    public static function format(array $context): string
    {
        $formattedContext = [];

        foreach ($context as $key => $value) {
            $formattedValue = self::getAsString($value);
            $formattedContext[] = sprintf('%s: %s', $key, $formattedValue);
        }

        return sprintf('[%s]', implode(', ', $formattedContext));
    }

    private static function getAsString(mixed $value): string
    {
        $type = gettype($value);

        return match ($type) {
            'string', 'double', 'integer' => self::truncate((string) $value),
            'boolean' => $value ? 'true' : 'false',
            'NULL' => 'null',
            'array' => self::format($value),
            default => sprintf('{%s}', $type),
        };
    }

    private static function truncate(string $value): string
    {
        if (mb_strlen($value) > self::VALUE_MAX_LENGTH) {
            return mb_substr($value, 0, self::VALUE_MAX_LENGTH) . self::ELLIPSIS;
        }

        return $value;
    }
}
