<?php

namespace Keerill\Optional;

use InvalidArgumentException;

/**
 * @author serhatozdal
 * @template T
 */
final class Optional
{
    /**
     * @var T
     */
    private mixed $value;

    /**
     * @param T $value
     */
    private function __construct(mixed $value)
    {
        $this->value = $value;
    }

    /**
     * @return Optional<null>
     */
    public static function ofEmpty(): self
    {
        return new self(null);
    }

    /**
     * @param T $value
     * @return Optional<T>
     */
    public static function of(mixed $value): Optional
    {
        return new self(self::requireNonNull($value));
    }

    /**
     * @param T $value
     * @return Optional<T>
     */
    public static function ofNullable(mixed $value): Optional
    {
        return $value === null ? self::ofEmpty() : self::of($value);
    }

    /**
     * @return T
     */
    public function get(): mixed
    {
        return $this->value;
    }

    public function isPresent(): bool
    {
        return $this->value !== null;
    }

    /**
     * @param callable(T): mixed $consumer
     * @return void
     */
    public function ifPresent(callable $consumer): void
    {
        if ($this->isPresent()) {
            $consumer($this->value);
        }
    }

    /**
     * @param callable(T): bool $predicate
     * @return Optional<T>
     */
    public function filter(callable $predicate): Optional
    {
        if ($this->isPresent()) {
            return $predicate($this->value) ?
                $this : self::ofEmpty();
        }

        return $this;
    }

    /**
     * @param callable(T): T $mapper
     * @return Optional<T>
     */
    public function map(callable $mapper): Optional
    {
        return $this->isPresent()
            ? self::ofNullable($mapper($this->value)) : self::ofEmpty();
    }

    /**
     * @param callable(T): T $mapper
     * @return Optional<T>
     */
    public function flatMap(callable $mapper): Optional
    {
        if (!$this->isPresent()) {
            return self::ofEmpty();
        }

        $value = ($value = $mapper($this->value)) instanceof Optional
            ? $value->get() : $value;

        return self::ofNullable($value);
    }

    /**
     * @param T $other
     * @return T
     */
    public function orElse(mixed $other): mixed
    {
        return $this->isPresent()
            ? $this->value : $other;
    }

    /**
     * @param callable(T): T $other
     * @return T
     */
    public function orElseGet(callable $other)
    {
        return $this->isPresent()
            ? $this->value : $other();
    }

    /**
     * @param callable $exceptionSupplier
     * @return T
     */
    public function orElseThrow(callable $exceptionSupplier)
    {
        return $this->isPresent()
            ? $this->value : $exceptionSupplier();
    }

    /**
     * @param T $obj
     * @return T
     */
    private static function requireNonNull(mixed $obj): mixed
    {
        if (null === $obj) {
            throw new InvalidArgumentException("variable can not be null!");
        }

        return $obj;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->isPresent() ?
            sprintf("Optional[%s]", $this->value) : "Optional.ofEmpty";
    }
}
