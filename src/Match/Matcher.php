<?php

declare(strict_types=1);

namespace VStelmakh\PsrTestLogger\Match;

use VStelmakh\PsrTestLogger\Assert\AsserterInterface;
use VStelmakh\PsrTestLogger\Log\Collection;
use VStelmakh\PsrTestLogger\Log\Log;

class Matcher
{
    /**
     * @internal
     */
    public function __construct(
        private Collection $logs,
        private readonly AsserterInterface $asserter,
    ) {
        $this->asserter->assert($this->logs, null);
    }

    /**
     * Return logs matching conditions.
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
     * Match logs with context matching provided one.
     *
     * @param array<mixed> $context
     * @return self
     */
    public function withContext(array $context): self
    {
        $normalizedContext = [];
        foreach ($context as $key => $value) {
            $normalizedContext[] = sprintf('%s: {%s}', $key, gettype($value));
        }

        $criterion = sprintf('context [%s]', implode(', ', $normalizedContext));
        $callback = fn(Log $log) => $log->context === $context;
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs with context contains key-value pair.
     *
     * @param mixed $key
     * @param mixed $value
     * @return self
     */
    public function withContextContains(mixed $key, mixed $value): self
    {
        $criterion = sprintf('context contains [%s: {%s}]', $key, gettype($value));
        $callback = fn(Log $log) => isset($log->context[$key]) && $log->context[$key] === $value;
        return $this->match($criterion, $callback);
    }

    /**
     * Match logs by callback. Callback example:
     * ```
     * fn(Log $log) => $log->level === LogLevel::INFO;
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
        $this->logs = $this->logs->filter($callback);
        $this->asserter->assert($this->logs, $criterion);
        return $this;
    }
}
