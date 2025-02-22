<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Tests\Assert;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\Assert\HasLogsAsserter;
use PHPUnit\Framework\TestCase;
use VStelmakh\PsrTestLogger\Log\Collection;
use VStelmakh\PsrTestLogger\Log\Log;

class HasLogsAsserterTest extends TestCase
{
    public function testAssertNotEmptyNoCriteria(): void
    {
        $logs = new Collection();
        $logs->add(new Log(LogLevel::INFO, 'Test message.'));

        $asserter = new HasLogsAsserter();
        $asserter->assert($logs);
        $assertCount = Assert::getCount();
        self::assertSame(1, $assertCount);
    }

    public function testAssertEmptyNoCriteriaNoMessage(): void
    {
        $logs = new Collection();
        $asserter = new HasLogsAsserter();
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that has logs.');
        $asserter->assert($logs);
    }

    public function testAssertEmptyNoCriteriaWithMessage(): void
    {
        $logs = new Collection();
        $asserter = new HasLogsAsserter('Custom failure message.');
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Custom failure message.' . "\n" . 'Failed asserting that has logs.');
        $asserter->assert($logs);
    }

    public function testAssertEmptyWithCriteriaNoMessage(): void
    {
        $logs = new Collection();
        $asserter = new HasLogsAsserter();
        $asserter->addCriterion('CRITERION_1');
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that has logs matching CRITERION_1.');
        $asserter->assert($logs);
    }

    public function testAssertEmptyWithCriteriaWithMessage(): void
    {
        $logs = new Collection();
        $asserter = new HasLogsAsserter('Custom failure message.');
        $asserter->addCriterion('CRITERION_1');
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Custom failure message.' . "\n" . 'Failed asserting that has logs matching CRITERION_1.');
        $asserter->assert($logs);
    }

    public function testAssertEmptyChainedCriteriaWithMessage(): void
    {
        $asserter = new HasLogsAsserter('Custom failure message.');

        $logs1 = new Collection();
        $logs1->add(new Log(LogLevel::INFO, 'Test message.'));
        $asserter->addCriterion('CRITERION_1');
        $asserter->assert($logs1);

        $logs2 = new Collection();
        $asserter->addCriterion('CRITERION_2');
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Custom failure message.' . "\n" . 'Failed asserting that has logs matching CRITERION_1 and CRITERION_2.');
        $asserter->assert($logs2);
    }
}
