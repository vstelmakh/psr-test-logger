<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Tests;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\LogLevel;
use VStelmakh\TestLogger\Log\Log;
use VStelmakh\TestLogger\TestLogger;
use PHPUnit\Framework\TestCase;

class TestLoggerTest extends TestCase
{
    public function testLog(): void
    {
        $logger = new TestLogger();

        $logger->log(LogLevel::INFO, 'This is "info" message.');
        $logger->log(LogLevel::ERROR, 'This is "error" message.', ['some' => 'data']);
        $logger->log(100, 'This is "100" message.');
        $objectLevel = new \stdClass();
        $objectLevel->property = 'value';
        $logger->log($objectLevel, 'This is "object" message.', ['other' => 'data']);

        $actual = $logger->getLogs();
        $expected = [
            new Log(LogLevel::INFO, 'This is "info" message.'),
            new Log(LogLevel::ERROR, 'This is "error" message.', ['some' => 'data']),
            new Log(100, 'This is "100" message.'),
            new Log($objectLevel, 'This is "object" message.', ['other' => 'data']),
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

    public function testAssert(): void
    {
        $loggerWithLogs = new TestLogger();
        $loggerWithLogs->info('This is test message.');
        $loggerWithLogs->assert()->hasLogs();

        $message = 'Custom assertation error message.';
        $loggerWithoutLogs = new TestLogger();
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage($message);
        $loggerWithoutLogs->assert($message)->hasLogs();
    }

    public function testFilter(): void
    {
        $logger = new TestLogger();
        $logger->info('This is test message.');

        $actual = $logger->filter()->getInfo()->getLogs();
        $expected = [new Log(LogLevel::INFO, 'This is test message.')];
        self::assertEquals($expected, $actual);

        $actual = $logger->filter()->getError()->getLogs();
        $expected = [];
        self::assertEquals($expected, $actual);
    }
}
