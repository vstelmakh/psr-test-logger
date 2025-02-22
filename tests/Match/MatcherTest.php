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
    private Collection $logs;
    private MockObject&AsserterInterface $asserter;
    private Matcher $matcher;

    public function testConstruct(): void
    {
        $expected = $this->logs->toArray();
        $this->expectAsserterCall($expected, null);

        $matcher = new Matcher($this->logs, $this->asserter);
        $actual = $matcher->getLogs();
        self::assertEquals($expected, $actual);
    }

    public function testWithLevel(): void
    {
        $expected = [$this->getSampleLog(LogLevel::INFO)];
        $this->expectAsserterCall($expected, 'level "info"');
        $matcher = $this->matcher->withLevel(LogLevel::INFO);
        $this->assertMatcherResult($matcher, $expected);
    }

    public function testWithMessage(): void
    {
        $expected = [$this->getSampleLog(LogLevel::ERROR)];
        $this->expectAsserterCall($expected, 'message "error: This is sample "error" message."');
        $matcher = $this->matcher->withMessage('error: This is sample "error" message.');
        $this->assertMatcherResult($matcher, $expected);
    }

    public function testWithMessageContains(): void
    {
        $expected = [$this->getSampleLog(LogLevel::WARNING)];
        $this->expectAsserterCall($expected, 'message contains "warning"');
        $matcher = $this->matcher->withMessageContains('warning');
        $this->assertMatcherResult($matcher, $expected);
    }

    public function testWithMessageContainsIgnoreCase(): void
    {
        $expected = [$this->getSampleLog(LogLevel::CRITICAL)];
        $this->expectAsserterCall($expected, 'message contains ignore case "CriTiCAL"');
        $matcher = $this->matcher->withMessageContainsIgnoreCase('CriTiCAL');
        $this->assertMatcherResult($matcher, $expected);
    }

    public function testWithMessageStartsWith(): void
    {
        $expected = [$this->getSampleLog(LogLevel::ERROR)];
        $this->expectAsserterCall($expected, 'message starts with "error"');
        $matcher = $this->matcher->withMessageStartsWith('error');
        $this->assertMatcherResult($matcher, $expected);
    }

    public function testWithMessageMatches(): void
    {
        $expected = [$this->getSampleLog(LogLevel::ERROR)];
        $this->expectAsserterCall($expected, 'message matches "/^error:/iu"');
        $matcher = $this->matcher->withMessageMatches('/^error:/iu');
        $this->assertMatcherResult($matcher, $expected);
    }

    public function testWithContext(): void
    {
        $sampleLog = $this->getSampleLog(LogLevel::ERROR);
        $expected = [$sampleLog];
        $this->expectAsserterCall($expected, 'context [error: {string}, string: {string}, int: {integer}]');
        $matcher = $this->matcher->withContext($sampleLog->context);
        $this->assertMatcherResult($matcher, $expected);
    }

    public function testWithContextContains(): void
    {
        $expected = [$this->getSampleLog(LogLevel::ERROR)];
        $this->expectAsserterCall($expected, 'context contains [error: {string}]');
        $matcher = $this->matcher->withContextContains('error', 'level data');
        $this->assertMatcherResult($matcher, $expected);
    }

    public function testWithCallback(): void
    {
        $expected = [$this->getSampleLog(LogLevel::INFO)];
        $this->expectAsserterCall($expected, 'callback');
        $callback = static fn(Log $log) => $log->level === LogLevel::INFO;
        $matcher = $this->matcher->withCallback($callback);
        $this->assertMatcherResult($matcher, $expected);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->logs = $this->getInitialLogs();
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

    private function getInitialLogs(): Collection
    {
        $logs = new Collection();
        $logs->add($this->getSampleLog(LogLevel::DEBUG));
        $logs->add($this->getSampleLog(LogLevel::INFO));
        $logs->add($this->getSampleLog(LogLevel::WARNING));
        $logs->add($this->getSampleLog(LogLevel::ERROR));
        $logs->add($this->getSampleLog(LogLevel::CRITICAL));
        $logs->add(new Log(LogLevel::ERROR, 'Other error message.'));
        return $logs;
    }

    private function getSampleLog(mixed $level): Log
    {
        return new Log(
            $level,
            sprintf('%s: This is sample "%s" message.', $level, $level),
            [
                $level => 'level data',
                'string' => 'string data',
                'int' => 123,
            ],
        );
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

    private function assertMatcherResult(Matcher $matcher, array $expected): void
    {
        $initialLogs = $this->getInitialLogs()->toArray();
        $matcherLogs = $this->matcher->getLogs();
        self::assertEquals($initialLogs, $matcherLogs, 'Initial matcher logs modified.');

        $actual = $matcher->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected matcher logs result.');
    }
}
