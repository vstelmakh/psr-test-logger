<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Tests\Assert;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use VStelmakh\TestLogger\Assert\PHPUnitAssertProxy;
use PHPUnit\Framework\TestCase;

class PHPUnitAssertProxyTest extends TestCase
{
    public function testSuccess(): void
    {
        PHPUnitAssertProxy::success();
        $assertCount = Assert::getCount();
        self::assertSame(1, $assertCount);
    }

    public function testFail(): void
    {
        $message = 'Custom assert failed message.';
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($message);
        PHPUnitAssertProxy::fail($message);
    }
}
