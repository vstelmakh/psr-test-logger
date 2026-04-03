<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Tests\Assert;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\AssertionFailedError;
use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\Assert\HasNoLogsAsserter;
use PHPUnit\Framework\TestCase;
use VStelmakh\PsrTestLogger\Log\Collection;
use VStelmakh\PsrTestLogger\Log\Log;

class HasNoLogsAsserterTest extends TestCase
{
    public function testAssertEmptyNoCriteria(): void
    {
        $logs = new Collection();

        $asserter = new HasNoLogsAsserter();
        $asserter->assert($logs);
        $assertCount = Assert::getCount();
        self::assertSame(1, $assertCount);
    }

    public function testAssertNotEmptyNoCriteriaNoMessage(): void
    {
        $logs = new Collection();
        $logs->add(new Log(LogLevel::INFO, 'Test message.'));

        $asserter = new HasNoLogsAsserter();

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that has no logs.');
        $asserter->assert($logs);
    }

    public function testAssertNotEmptyNoCriteriaWithMessage(): void
    {
        $logs = new Collection();
        $logs->add(new Log(LogLevel::INFO, 'Test message.'));

        $asserter = new HasNoLogsAsserter('Custom failure message.');

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Custom failure message.' . "\n" . 'Failed asserting that has no logs.');
        $asserter->assert($logs);
    }

    public function testAssertNotEmptyWithCriteriaNoMessage(): void
    {
        $logs = new Collection();
        $logs->add(new Log(LogLevel::INFO, 'Test message.'));

        $asserter = new HasNoLogsAsserter();
        $asserter->addCriterion('CRITERION_1');

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that has no logs matching CRITERION_1.');

        $asserter->assert($logs);
    }

    public function testAssertNotEmptyWithCriteriaWithMessage(): void
    {
        $logs = new Collection();
        $logs->add(new Log(LogLevel::INFO, 'Test message.'));

        $asserter = new HasNoLogsAsserter('Custom failure message.');
        $asserter->addCriterion('CRITERION_1');

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Custom failure message.' . "\n" . 'Failed asserting that has no logs matching CRITERION_1.');
        $asserter->assert($logs);
    }

    public function testAssertNotEmptyChainedCriteriaWithMessage(): void
    {
        $asserter = new HasNoLogsAsserter('Custom failure message.');

        $logs1 = new Collection();
        $asserter->addCriterion('CRITERION_1');
        $asserter->assert($logs1);

        $logs2 = new Collection();
        $logs2->add(new Log(LogLevel::INFO, 'Test message.'));
        $asserter->addCriterion('CRITERION_2');

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Custom failure message.' . "\n" . 'Failed asserting that has no logs matching CRITERION_1 and CRITERION_2.');
        $asserter->assert($logs2);
    }
}
