<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Tests;

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
}
