<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Tests\Match;

use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Log\LogLevel;
use VStelmakh\PsrTestLogger\Log\Collection;
use VStelmakh\PsrTestLogger\Log\Log;
use VStelmakh\PsrTestLogger\Match\Assert;
use PHPUnit\Framework\TestCase;

class AssertTest extends TestCase
{
    #[DataProvider('hasProvider')]
    public function testHas(string $method, ?string $level, bool $isSuccess): void
    {
        $logs = new Collection();
        if ($level !== null) {
            $logs->add(new Log($level, 'Test message.'));
        }

        $message = 'Custom assertation error message.';

        if (!$isSuccess) {
            $this->expectException(AssertionFailedError::class);
            $this->expectExceptionMessage($message);
        }

        $assert = new Assert($logs, $message);
        $assert->{$method}();
    }

    public static function hasProvider(): array
    {
        return [
            ['hasLogs', null, false],
            ['hasLogs', LogLevel::INFO, true],
            ['hasDebug', LogLevel::INFO, false],
            ['hasDebug', LogLevel::DEBUG, true],
            ['hasInfo', LogLevel::DEBUG, false],
            ['hasInfo', LogLevel::INFO, true],
            ['hasNotice', LogLevel::NOTICE, true],
            ['hasNotice', LogLevel::INFO, false],
            ['hasWarning', LogLevel::WARNING, true],
            ['hasWarning', LogLevel::INFO, false],
            ['hasError', LogLevel::ERROR, true],
            ['hasError', LogLevel::INFO, false],
            ['hasCritical', LogLevel::CRITICAL, true],
            ['hasCritical', LogLevel::INFO, false],
            ['hasAlert', LogLevel::ALERT, true],
            ['hasAlert', LogLevel::INFO, false],
            ['hasEmergency', LogLevel::EMERGENCY, true],
            ['hasAlert', LogLevel::INFO, false],
        ];
    }
}
