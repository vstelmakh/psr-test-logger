<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\Log\Log;
use VStelmakh\PsrTestLogger\TestLogger;
use PHPUnit\Framework\TestCase;

class TestLoggerTest extends TestCase
{
    public function testLog(): void
    {
        $logger = new TestLogger();

        $logger->log(LogLevel::INFO, 'This is "info" message.');
        $logger->log(LogLevel::ERROR, 'This is "error" message.', ['some' => 'data']);
        $logger->log(100, 'This is "100" message.');

        $actual = $logger->getLogs();
        $expected = [
            new Log(LogLevel::INFO, 'This is "info" message.'),
            new Log(LogLevel::ERROR, 'This is "error" message.', ['some' => 'data']),
            new Log(100, 'This is "100" message.'),
        ];

        self::assertEquals($expected, $actual);
    }

    #[DataProvider('logLevelProvider')]
    public function testLogLevel(string $method, string $level): void
    {
        $logger = new TestLogger();
        $logger->{$method}('This is test message.', ['some' => 'data']);

        $actual = $logger->getLogs();
        $expected = [new Log($level, 'This is test message.', ['some' => 'data'])];
        self::assertEquals($expected, $actual);
    }

    public static function logLevelProvider(): array
    {
        return [
            ['debug', LogLevel::DEBUG],
            ['info', LogLevel::INFO],
            ['notice', LogLevel::NOTICE],
            ['warning', LogLevel::WARNING],
            ['error', LogLevel::ERROR],
            ['critical', LogLevel::CRITICAL],
            ['alert', LogLevel::ALERT],
            ['emergency', LogLevel::EMERGENCY],
        ];
    }

    public function testAssertSuccess(): void
    {
        $logger = new TestLogger();
        $logger->info('This is test message.');
        $logger->assert()->hasLog();
    }

    public function testAssertFail(): void
    {
        $logger = new TestLogger();
        $logger->info('This is test message.');

        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage(
            'Custom assertation error message.'
            . PHP_EOL
            . 'Failed asserting that has logs matching level "info" and message "This is test message." and callback.',
        );

        $logger
            ->assert('Custom assertation error message.')
            ->hasInfo()
            ->withMessage('This is test message.')
            ->withCallback(fn($log) => false);
    }

    public function testAssertMultiple(): void
    {
        $logger = new TestLogger();
        $logger->info('This is info message.', ['data' => 'info data']);
        $logger->error('This is error message.', ['data' => 'error data']);

        $assertHasLog = $logger->assert()->hasLog();

        $assertHasLog
            ->withMessageContains('info message')
            ->withContextContainsEqualTo('data', 'info data');

        $assertHasLog
            ->withMessageContains('error message')
            ->withContextContainsEqualTo('data', 'error data');
    }

    public function testFilterSuccess(): void
    {
        $logger = new TestLogger();
        $logger->info('This is test message.');

        $actual = $logger->filter()->getInfo()->getLogs();
        $expected = [new Log(LogLevel::INFO, 'This is test message.')];
        self::assertEquals($expected, $actual);
    }

    public function testFilterFail(): void
    {
        $logger = new TestLogger();
        $logger->info('This is test message.');

        $actual = $logger->filter()->getAll()->withLevel(LogLevel::ERROR)->getLogs();
        $expected = [];
        self::assertEquals($expected, $actual);
    }

    public function testFilterMultiple(): void
    {
        $logger = new TestLogger();
        $logger->info('This is info message.', ['data' => 'info data']);
        $logger->error('This is error message.', ['data' => 'error data']);

        $filterAll = $logger->filter()->getAll();
        $expected = [
            new Log(LogLevel::INFO, 'This is info message.', ['data' => 'info data']),
            new Log(LogLevel::ERROR, 'This is error message.', ['data' => 'error data']),
        ];
        $actual = $filterAll->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected filter all logs result.');

        $expected = [new Log(LogLevel::INFO, 'This is info message.', ['data' => 'info data'])];
        $actual = $filterAll
            ->withMessageContains('info message')
            ->withContextContainsEqualTo('data', 'info data')
            ->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected filter info log result.');

        $expected = [new Log(LogLevel::ERROR, 'This is error message.', ['data' => 'error data'])];
        $actual = $filterAll
            ->withMessageContains('error message')
            ->withContextContainsEqualTo('data', 'error data')
            ->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected filter error log result.');

        $expected = [];
        $actual = $filterAll
            ->withLevel(LogLevel::CRITICAL)
            ->withMessageContains('error message')
            ->withContextContainsEqualTo('data', 'error data')
            ->getLogs();
        self::assertEquals($expected, $actual, 'Unexpected filter critical log result.');
    }
}
