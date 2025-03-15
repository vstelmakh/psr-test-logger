<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Tests\Match;

use PHPUnit\Framework\Attributes\DataProvider;
use VStelmakh\PsrTestLogger\Match\ContextFormatter;
use PHPUnit\Framework\TestCase;

class ContextFormatterTest extends TestCase
{
    private const LONG_STRING = 'In computing, logging is the act of keeping a log of events that occur in a'
        . ' computer system, such as problems, errors or just information on current operations. These events may occur'
        . ' in the operating system or in other software. A message or log entry is recorded for each such event. These'
        . ' log messages can then be used to monitor and understand the operation of the system, to debug problems, or'
        . ' during an audit. Logging is particularly important in multi-user software, to have a central overview of'
        . ' the operation of the system.';

    private const LONG_STRING_TRUNCATED = 'In computing, logging is the act of keeping a log of events that occur in a'
        . ' computer system, such as problems, errors or just information on current o…';

    #[DataProvider('formatDataProvider')]
    public function testFormat(array $context, string $expected): void
    {
        $actual = ContextFormatter::format($context);
        self::assertSame($expected, $actual);
    }

    public static function formatDataProvider(): array
    {
        return [
            [[1 => 2], '[1: 2]'],
            [['string' => 'string'], '[string: string]'],
            [['int' => 32767], '[int: 32767]'],
            [['float' => 3.1415926535], '[float: 3.1415926535]'],
            [['bool_true' => true], '[bool_true: true]'],
            [['bool_false' => false], '[bool_false: false]'],
            [['null' => null], '[null: null]'],
            [['object' => new \stdClass()], '[object: {object}]'],
            [['array' => ['a' => 34, 'b' => 'value', 'nested' => [1, 2]]], '[array: [a: 34, b: value, nested: [0: 1, 1: 2]]]'],
            [['string' => 'some string', 'object1' => new \stdClass(), 'object2' => new \stdClass()], '[string: some string, object1: {object}, object2: {object}]'],
            [['long_string' => self::LONG_STRING], '[long_string: ' . self::LONG_STRING_TRUNCATED . ']'],
        ];
    }
}
