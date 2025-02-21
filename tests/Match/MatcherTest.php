<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Tests\Match;

use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LogLevel;
use VStelmakh\TestLogger\Assert\AsserterInterface;
use VStelmakh\TestLogger\Log\Collection;
use VStelmakh\TestLogger\Log\Log;
use VStelmakh\TestLogger\Match\Matcher;
use PHPUnit\Framework\TestCase;

class MatcherTest extends TestCase
{
    private Collection $logs;
    private MockObject&AsserterInterface $asserter;
    private Matcher $matcher;

    public function testWithLevel(): void
    {
        $this->logs->add(new Log(LogLevel::DEBUG, 'Debug message'));
        $this->logs->add(new Log(LogLevel::INFO, 'Info message'));

        $expected = [new Log(LogLevel::INFO, 'Info message')];
        $this->expectAsserterCall($expected, 'level "info"');

        $actual = $this->matcher->withLevel(LogLevel::INFO)->getLogs();
        self::assertEquals($expected, $actual);
    }

    public function testWithMessage(): void
    {
        $this->logs->add(new Log(LogLevel::ERROR, 'Error message'));
        $this->logs->add(new Log(LogLevel::INFO, 'Info message'));

        $expected = [new Log(LogLevel::ERROR, 'Error message')];
        $this->expectAsserterCall($expected, 'message "Error message"');

        $actual = $this->matcher->withMessage('Error message')->getLogs();
        self::assertEquals($expected, $actual);
    }

    public function testWithMessageContains(): void
    {
        $this->logs->add(new Log(LogLevel::ERROR, 'This is error message'));
        $this->logs->add(new Log(LogLevel::INFO, 'This is info message'));

        $expected = [new Log(LogLevel::ERROR, 'This is error message')];
        $this->expectAsserterCall($expected, 'message contains "error"');

        $actual = $this->matcher->withMessageContains('error')->getLogs();
        self::assertEquals($expected, $actual);
    }

    public function testWithMessageContainsIgnoreCase(): void
    {
        $this->logs->add(new Log(LogLevel::ERROR, 'This is error message'));
        $this->logs->add(new Log(LogLevel::INFO, 'This is info message'));

        $expected = [new Log(LogLevel::ERROR, 'This is error message')];
        $this->expectAsserterCall($expected, 'message contains ignore case "ERROR"');

        $actual = $this->matcher->withMessageContainsIgnoreCase('ERROR')->getLogs();
        self::assertEquals($expected, $actual);
    }

    public function testWithMessageStartsWith(): void
    {
        $this->logs->add(new Log(LogLevel::ERROR, 'Error message'));
        $this->logs->add(new Log(LogLevel::INFO, 'Info message'));

        $expected = [new Log(LogLevel::ERROR, 'Error message')];
        $this->expectAsserterCall($expected, 'message starts with "Error"');

        $actual = $this->matcher->withMessageStartsWith('Error')->getLogs();
        self::assertEquals($expected, $actual);
    }

    public function testWithMessageMatches(): void
    {
        $this->logs->add(new Log(LogLevel::ERROR, 'Error message'));
        $this->logs->add(new Log(LogLevel::INFO, 'Info message'));

        $expected = [new Log(LogLevel::ERROR, 'Error message')];
        $this->expectAsserterCall($expected, 'message matches "/^error/iu"');

        $actual = $this->matcher->withMessageMatches('/^error/iu')->getLogs();
        self::assertEquals($expected, $actual);
    }

    public function testWithContext(): void
    {
        $this->logs->add(new Log(LogLevel::ERROR, 'Error message', ['error' => 'data']));
        $this->logs->add(new Log(LogLevel::INFO, 'Info message', ['info' => 'value']));

        $expected = [new Log(LogLevel::ERROR, 'Error message', ['error' => 'data'])];
        $this->expectAsserterCall($expected, 'context [error: {string}]');

        $actual = $this->matcher->withContext(['error' => 'data'])->getLogs();
        self::assertEquals($expected, $actual);
    }

    public function testWithContextContains(): void
    {
        $this->logs->add(new Log(LogLevel::ERROR, 'Error message', ['error' => 'data']));
        $this->logs->add(new Log(LogLevel::INFO, 'Info message', ['info' => 'value', 'num' => 1]));

        $expected = [new Log(LogLevel::INFO, 'Info message', ['info' => 'value', 'num' => 1])];
        $this->expectAsserterCall($expected, 'context contains [num: {integer}]');

        $actual = $this->matcher->withContextContains('num', 1)->getLogs();
        self::assertEquals($expected, $actual);
    }

    public function testWithCallback(): void
    {
        $this->logs->add(new Log(LogLevel::DEBUG, 'Debug message'));
        $this->logs->add(new Log(LogLevel::INFO, 'Info message'));

        $expected = [new Log(LogLevel::INFO, 'Info message')];
        $this->expectAsserterCall($expected, 'callback');

        $callback = static fn(Log $log) => $log->level === LogLevel::INFO;
        $actual = $this->matcher->withCallback($callback)->getLogs();
        self::assertEquals($expected, $actual);
    }

    /**
     * @param array<Log> $logs
     */
    protected function expectAsserterCall(array $logs, string $criterion): void
    {
        $collection = new Collection();
        foreach ($logs as $log) {
            $collection->add($log);
        }

        $this->asserter
            ->expects($this->once())
            ->method('assert')
            ->with(
                self::callback(function ($value) use ($collection): bool {
                    self::assertEquals($collection, $value, 'Unexpected collection provided to asserter.');
                    return true;
                }),
                self::callback(function ($value) use ($criterion): bool {
                    self::assertEquals($criterion, $value, 'Unexpected criterion provided to asserter.');
                    return true;
                }),
            );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->logs = new Collection();
        $this->asserter = $this->createMock(AsserterInterface::class);
        $this->matcher = new Matcher($this->logs, $this->asserter);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset(
            $this->asserter,
            $this->matcher,
        );
    }
}
