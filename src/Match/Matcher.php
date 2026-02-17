<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Match;

use VStelmakh\PsrTestLogger\Assert\AsserterInterface;
use VStelmakh\PsrTestLogger\Log\Collection;
use VStelmakh\PsrTestLogger\Log\Log;

final class Matcher
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Collection $logs,
        private readonly AsserterInterface $asserter,
    ) {
        $this->asserter->assert($this->logs);
    }

    /**
     * Return logs that matching previously applied filters.
     *
     * @return array<Log>
     */
    public function getLogs(): array
    {
        return $this->logs->toArray();
    }

    /**
     * Match logs with specified log level.
     *
     * @param mixed $level
     * @return self
     */
    public function withLevel(mixed $level): self
    {
        $criterion = sprintf('level "%s"', $level);
        $callback = fn(Log $log) => $log->level === $level;
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs with specified message.
     *
     * @param string $message
     * @return self
     */
    public function withMessage(string $message): self
    {
        $criterion = sprintf('message "%s"', $message);
        $callback = fn(Log $log) => (string) $log->message === $message;
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs with message contains substring.
     *
     * @param string $needle
     * @return self
     */
    public function withMessageContains(string $needle): self
    {
        $criterion = sprintf('message contains "%s"', $needle);
        $callback = fn(Log $log) => str_contains((string) $log->message, $needle);
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs with message contains substring (case-insensitive).
     *
     * @param string $needle
     * @return self
     */
    public function withMessageContainsIgnoreCase(string $needle): self
    {
        $criterion = sprintf('message contains ignore case "%s"', $needle);
        $callback = fn(Log $log) => mb_stripos((string) $log->message, $needle, 0, 'UTF-8') !== false;
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs with message starts with prefix.
     *
     * @param string $prefix
     * @return self
     */
    public function withMessageStartsWith(string $prefix): self
    {
        $criterion = sprintf('message starts with "%s"', $prefix);
        $callback = fn(Log $log) => str_starts_with((string) $log->message, $prefix);
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs with message matching regular expression.
     *
     * @param string $pattern RegEx pattern. Example value: "/^error/i".
     * @return self
     */
    public function withMessageMatches(string $pattern): self
    {
        $criterion = sprintf('message matches "%s"', $pattern);
        $callback = fn(Log $log) => preg_match($pattern, (string) $log->message) === 1;
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs with context as provided one, using loose comparison (==).
     *
     * @param array<mixed> $context
     * @return self
     */
    public function withContextEqualTo(array $context): self
    {
        $formattedContext = ContextFormatter::format($context);
        $criterion = sprintf('context equal to %s', $formattedContext);
        $callback = fn(Log $log) => $log->context == $context;
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs with context as provided one, using strict comparison (===).
     *
     * @param array<mixed> $context
     * @return self
     */
    public function withContextSameAs(array $context): self
    {
        $formattedContext = ContextFormatter::format($context);
        $criterion = sprintf('context same as %s', $formattedContext);
        $callback = fn(Log $log) => $log->context === $context;
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs with context contains key-value pair, using loose comparison (==).
     *
     * @param mixed $key
     * @param mixed $value
     * @return self
     */
    public function withContextContainsEqualTo(mixed $key, mixed $value): self
    {
        $formattedContext = ContextFormatter::format([$key => $value]);
        $criterion = sprintf('context contains equal to %s', $formattedContext);
        $callback = fn(Log $log) => isset($log->context[$key]) && $log->context[$key] == $value;
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs with context contains key-value pair, using strict comparison (===).
     *
     * @param mixed $key
     * @param mixed $value
     * @return self
     */
    public function withContextContainsSameAs(mixed $key, mixed $value): self
    {
        $formattedContext = ContextFormatter::format([$key => $value]);
        $criterion = sprintf('context contains same as %s', $formattedContext);
        $callback = fn(Log $log) => isset($log->context[$key]) && $log->context[$key] === $value;
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs by callback. Callback example:
     * ```
     * function (Log $log): bool {
     *     return $log->level === LogLevel::INFO;
     * }
     * ```
     *
     * @param callable(Log): bool $callback Return "true" on match, otherwise "false".
     * @return self
     */
    public function withCallback(callable $callback): self
    {
        $criterion = 'callback';
        return $this->match($criterion, $callback);
    }

    /**
     * @param callable(Log): bool $callback
     */
    private function match(string $criterion, callable $callback): self
    {
        $logs = $this->logs->filter($callback);
        $asserter = clone $this->asserter;
        $asserter->addCriterion($criterion);
        return new self($logs, $asserter);
    }
}
