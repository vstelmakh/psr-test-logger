<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Tests\Match;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\Assert\AsserterInterface;
use VStelmakh\PsrTestLogger\Log\Collection;
use VStelmakh\PsrTestLogger\Log\Log;
use VStelmakh\PsrTestLogger\Match\Matcher;
use PHPUnit\Framework\TestCase;

class MatcherTest extends TestCase
{
    private MockObject&AsserterInterface $asserter;

    public function testConstruct(): void
    {
        $logs = [
            new Log(LogLevel::INFO, 'Info message'),
            new Log(LogLevel::ERROR, 'Error message'),
        ];
        $this->expectAsserterCall($logs, null);

        $matcher = $this->createMatcher($logs);
        $actual = $matcher->getLogs();
        self::assertEquals($logs, $actual);
    }

    public function testWithLevel(): void
    {
        $logs = [
            new Log(LogLevel::INFO, 'Info message'),
            new Log(LogLevel::ERROR, 'Error message'),
        ];
        $matcher = $this->createMatcher($logs);

        $expected = [new Log(LogLevel::INFO, 'Info message')];
        $this->expectAsserterCall($expected, 'level "info"');
        $match = $matcher->withLevel(LogLevel::INFO);

        $matcherLogs = $matcher->getLogs();
        self::assertSame($logs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $match->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected match logs result.');
    }

    public function testWithMessage(): void
    {
        $logs = [
            new Log(LogLevel::INFO, 'Info message'),
            new Log(LogLevel::ERROR, 'Error message'),
        ];
        $matcher = $this->createMatcher($logs);

        $expected = [new Log(LogLevel::ERROR, 'Error message')];
        $this->expectAsserterCall($expected, 'message "Error message"');
        $match = $matcher->withMessage('Error message');

        $matcherLogs = $matcher->getLogs();
        self::assertSame($logs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $match->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected match logs result.');
    }

    public function testWithMessageContains(): void
    {
        $logs = [
            new Log(LogLevel::INFO, 'Info message'),
            new Log(LogLevel::WARNING, 'This is warning message'),
            new Log(LogLevel::ERROR, 'Error message'),
        ];
        $matcher = $this->createMatcher($logs);

        $expected = [new Log(LogLevel::WARNING, 'This is warning message')];
        $this->expectAsserterCall($expected, 'message contains "warning"');
        $match = $matcher->withMessageContains('warning');

        $matcherLogs = $matcher->getLogs();
        self::assertSame($logs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $match->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected match logs result.');
    }

    public function testWithMessageContainsIgnoreCase(): void
    {
        $logs = [
            new Log(LogLevel::INFO, 'Info message'),
            new Log(LogLevel::ERROR, 'Error message'),
        ];
        $matcher = $this->createMatcher($logs);

        $expected = [new Log(LogLevel::ERROR, 'Error message')];
        $this->expectAsserterCall($expected, 'message contains ignore case "eRRoR"');
        $match = $matcher->withMessageContainsIgnoreCase('eRRoR');

        $matcherLogs = $matcher->getLogs();
        self::assertSame($logs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $match->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected match logs result.');
    }

    public function testWithMessageStartsWith(): void
    {
        $logs = [
            new Log(LogLevel::INFO, 'Info message'),
            new Log(LogLevel::ERROR, 'Error message'),
        ];
        $matcher = $this->createMatcher($logs);

        $expected = [new Log(LogLevel::ERROR, 'Error message')];
        $this->expectAsserterCall($expected, 'message starts with "Error"');
        $match = $matcher->withMessageStartsWith('Error');

        $matcherLogs = $matcher->getLogs();
        self::assertSame($logs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $match->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected match logs result.');
    }

    public function testWithMessageMatches(): void
    {
        $logs = [
            new Log(LogLevel::INFO, 'Info message'),
            new Log(LogLevel::ERROR, 'Error message'),
        ];
        $matcher = $this->createMatcher($logs);

        $expected = [new Log(LogLevel::ERROR, 'Error message')];
        $this->expectAsserterCall($expected, 'message matches "/^error/iu"');
        $match = $matcher->withMessageMatches('/^error/iu');

        $matcherLogs = $matcher->getLogs();
        self::assertSame($logs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $match->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected match logs result.');
    }

    public function testWithContextSameAs(): void
    {
        $object1 = new \stdClass();
        $object1->value = 1;

        $object2 = new \stdClass();
        $object2->value = 1;

        $logs = [
            new Log(LogLevel::INFO, 'Info message', ['data' => $object1]),
            new Log(LogLevel::ERROR, 'Error message', ['data' => $object2]),
        ];
        $matcher = $this->createMatcher($logs);

        $expected = [new Log(LogLevel::ERROR, 'Error message', ['data' => $object2])];
        $this->expectAsserterCall($expected, 'context same as [data: {object}]');
        $match = $matcher->withContextSameAs(['data' => $object2]);

        $matcherLogs = $matcher->getLogs();
        self::assertSame($logs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $match->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected match logs result.');
    }

    public function testWithContextEqualTo(): void
    {
        $object1 = new \stdClass();
        $object1->value = 1;

        $object2 = new \stdClass();
        $object2->value = 2;

        $object3 = new \stdClass();
        $object3->value = 2;

        $logs = [
            new Log(LogLevel::INFO, 'Info message', ['data' => $object1]),
            new Log(LogLevel::ERROR, 'Error message', ['data' => $object2]),
        ];
        $matcher = $this->createMatcher($logs);

        $expected = [new Log(LogLevel::ERROR, 'Error message', ['data' => $object2])];
        $this->expectAsserterCall($expected, 'context equal to [data: {object}]');
        $match = $matcher->withContextEqualTo(['data' => $object3]);

        $matcherLogs = $matcher->getLogs();
        self::assertSame($logs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $match->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected match logs result.');
    }

    public function testWithContextContains(): void
    {
        $logs = [
            new Log(LogLevel::INFO, 'Info message', ['data' => 'value info']),
            new Log(LogLevel::ERROR, 'Error message', ['data' => 'value error']),
        ];
        $matcher = $this->createMatcher($logs);

        $expected = [new Log(LogLevel::ERROR, 'Error message', ['data' => 'value error'])];
        $this->expectAsserterCall($expected, 'context contains [data: {string}]');
        $match = $matcher->withContextContains('data', 'value error');

        $matcherLogs = $matcher->getLogs();
        self::assertSame($logs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $match->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected match logs result.');
    }

    public function testWithCallback(): void
    {
        $logs = [
            new Log(LogLevel::INFO, 'Info message'),
            new Log(LogLevel::ERROR, 'Error message'),
        ];
        $matcher = $this->createMatcher($logs);

        $expected = [new Log(LogLevel::INFO, 'Info message')];
        $this->expectAsserterCall($expected, 'callback');
        $callback = static fn(Log $log) => $log->level === LogLevel::INFO;
        $match = $matcher->withCallback($callback);

        $matcherLogs = $matcher->getLogs();
        self::assertSame($logs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $match->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected match logs result.');
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->asserter = $this->createMock(AsserterInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->asserter);
    }

    /**
     * @param array<Log> $logs
     */
    private function createMatcher(array $logs): Matcher
    {
        $collection = new Collection();
        foreach ($logs as $log) {
            $collection->add($log);
        }

        return new Matcher($collection, $this->asserter);
    }

    /**
     * @param array<Log> $logs
     */
    private function expectAsserterCall(array $logs, ?string $criterion): void
    {
        $collection = new Collection();
        foreach ($logs as $log) {
            $collection->add($log);
        }

        if ($criterion !== null) {
            $this->asserter
                ->expects($this->once())
                ->method('addCriterion')
                ->with(
                    self::callback(function ($value) use ($criterion): bool {
                        self::assertEquals($criterion, $value, 'Unexpected criterion provided to asserter.');
                        return true;
                    }),
                );
        }

        $this->asserter
            ->expects($this->once())
            ->method('assert')
            ->with(
                self::callback(function ($value) use ($collection): bool {
                    self::assertEquals($collection, $value, 'Unexpected collection provided to asserter.');
                    return true;
                }),
            );
    }
}
