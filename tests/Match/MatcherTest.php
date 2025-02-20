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
        $this->logs->add(new Log(LogLevel::ERROR, 'Error message'));
        $this->logs->add(new Log(LogLevel::INFO, 'Info message'));

        $expected = [new Log(LogLevel::ERROR, 'Error message')];
        $this->expectAsserterCall($expected, 'message contains "Error"');

        $actual = $this->matcher->withMessageContains('Error')->getLogs();
        self::assertEquals($expected, $actual);
    }

    public function testWithMessageContainsIgnoreCase(): void
    {
        $this->logs->add(new Log(LogLevel::ERROR, 'Error message'));
        $this->logs->add(new Log(LogLevel::INFO, 'Info message'));

        $expected = [new Log(LogLevel::ERROR, 'Error message')];
        $this->expectAsserterCall($expected, 'message contains ignore case "ERROR"');

        $actual = $this->matcher->withMessageContainsIgnoreCase('ERROR')->getLogs();
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
            ->with($collection, $criterion);
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
