<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Assert;

/**
 * @internal
 */
final class PHPUnitAssertProxy
{
    private const PHPUNIT_ASSERT = \PHPUnit\Framework\Assert::class;

    /**
     * Assertation successful.
     *
     * @return void
     */
    public static function success(): void
    {
        if (self::hasPhpUnit()) {
            self::PHPUNIT_ASSERT::assertTrue(true);
        }
    }

    /**
     * Assertation failure.
     *
     * @param string $message Assertation error message.
     * @return never
     */
    public static function fail(string $message): never
    {
        if (self::hasPhpUnit()) {
            self::PHPUNIT_ASSERT::fail($message);
        }

        throw new \RuntimeException($message);
    }

    private static function hasPhpUnit(): bool
    {
        return class_exists(self::PHPUNIT_ASSERT, true);
    }
}
