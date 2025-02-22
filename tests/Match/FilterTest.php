<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Tests\Match;

use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\Log\Collection;
use VStelmakh\PsrTestLogger\Log\Log;
use VStelmakh\PsrTestLogger\Match\Filter;
use PHPUnit\Framework\TestCase;
use VStelmakh\PsrTestLogger\Match\Matcher;

class FilterTest extends TestCase
{
    #[DataProvider('getProvider')]
    public function testGet(string $method, array $expected): void
    {
        $logs = new Collection();
        $logs->add(new Log(LogLevel::DEBUG, 'Debug'));
        $logs->add(new Log(LogLevel::INFO, 'Info'));
        $logs->add(new Log(LogLevel::NOTICE, 'Notice'));
        $logs->add(new Log(LogLevel::WARNING, 'Warning'));
        $logs->add(new Log(LogLevel::ERROR, 'Error'));
        $logs->add(new Log(LogLevel::CRITICAL, 'Critical'));
        $logs->add(new Log(LogLevel::ALERT, 'Alert'));
        $logs->add(new Log(LogLevel::EMERGENCY, 'Emergency'));

        $assert = new Filter($logs);
        /** @var Matcher $matcher */
        $matcher = $assert->{$method}();
        $actual = $matcher->getLogs();
        self::assertEquals($expected, $actual);
    }

    public static function getProvider(): array
    {
        return [
            ['getAll', [
                new Log(LogLevel::DEBUG, 'Debug'),
                new Log(LogLevel::INFO, 'Info'),
                new Log(LogLevel::NOTICE, 'Notice'),
                new Log(LogLevel::WARNING, 'Warning'),
                new Log(LogLevel::ERROR, 'Error'),
                new Log(LogLevel::CRITICAL, 'Critical'),
                new Log(LogLevel::ALERT, 'Alert'),
                new Log(LogLevel::EMERGENCY, 'Emergency'),
            ]],
            ['getDebug', [new Log(LogLevel::DEBUG, 'Debug')]],
            ['getInfo', [new Log(LogLevel::INFO, 'Info')]],
            ['getNotice', [new Log(LogLevel::NOTICE, 'Notice')]],
            ['getWarning', [new Log(LogLevel::WARNING, 'Warning')]],
            ['getError', [new Log(LogLevel::ERROR, 'Error')]],
            ['getCritical', [new Log(LogLevel::CRITICAL, 'Critical')]],
            ['getAlert', [new Log(LogLevel::ALERT, 'Alert')]],
            ['getEmergency', [new Log(LogLevel::EMERGENCY, 'Emergency')]],
        ];
    }
}
