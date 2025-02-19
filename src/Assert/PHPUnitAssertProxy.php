<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Assert;

/**
 * @internal
 */
class PHPUnitAssertProxy
{
    private const PHPUNIT_ASSERT = \PHPUnit\Framework\Assert::class;

    public static function success(): void
    {
        if (self::hasPhpUnit()) {
            self::PHPUNIT_ASSERT::assertTrue(true);
        }
    }

    public static function fail(string $message): never
    {
        if (self::hasPhpUnit()) {
            self::PHPUNIT_ASSERT::fail($message);
        }

        throw new \RuntimeException($message);
    }

    private static function hasPhpUnit(): bool
    {
        return class_exists(self::PHPUNIT_ASSERT, false);
    }
}
