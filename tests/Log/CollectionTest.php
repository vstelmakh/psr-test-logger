<?php

declare(strict_types=1);

namespace VStelmakh\TestLogger\Tests\Log;

use Psr\Log\LogLevel;
use VStelmakh\TestLogger\Log\Collection;
use PHPUnit\Framework\TestCase;
use VStelmakh\TestLogger\Log\Log;

class CollectionTest extends TestCase
{
    public function testCount(): void
    {
        $collection = new Collection();
        $actual = $collection->count();
        self::assertSame(0, $actual);

        $collection->add(new Log(LogLevel::INFO, 'Test message'));
        $collection->add(new Log(LogLevel::INFO, 'Test message'));
        $actual = $collection->count();
        self::assertSame(2, $actual);
    }

    public function testIsEmpty(): void
    {
        $collection = new Collection();
        $actual = $collection->isEmpty();
        self::assertTrue($actual);

        $collection->add(new Log(LogLevel::INFO, 'Test message'));
        $actual = $collection->isEmpty();
        self::assertFalse($actual);
    }

    public function testToArray(): void
    {
        $collection = new Collection();
        $collection->add(new Log(LogLevel::DEBUG, 'Debug message'));
        $collection->add(new Log(LogLevel::INFO, 'Info message'));

        $expected = [
            new Log(LogLevel::DEBUG, 'Debug message'),
            new Log(LogLevel::INFO, 'Info message')
        ];

        $actual = $collection->toArray();
        self::assertEquals($expected, $actual);
    }

    public function testFilter(): void
    {
        $collection = new Collection();
        $collection->add(new Log(LogLevel::DEBUG, 'Debug message'));
        $collection->add(new Log(LogLevel::INFO, 'Info message'));

        $expected = [new Log(LogLevel::INFO, 'Info message')];

        $callback = fn(Log $log) => $log->level === LogLevel::INFO;
        $actual = $collection->filter($callback)->toArray();

        self::assertEquals($expected, $actual);
    }
}
