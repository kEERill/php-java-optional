<?php

namespace Keerill\Optional;

use InvalidArgumentException;
use Throwable;

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
     * @return Optional
     */
    public static function ofEmpty(): self
    {
        return new self(null);
    }

    /**
     * @param T $value
     * @return Optional<T>
     */
    public static function of($value): Optional
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
     * @return T|null
     */
    public function get()
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
     * @template E
     * @param callable(T): E $mapper
     * @return Optional<E>
     */
    public function map(callable $mapper): Optional
    {
        return $this->isPresent()
            ? self::ofNullable($mapper($this->value)) : self::ofEmpty();
    }

    /**
     * @deprecated
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
    public function orElse($other)
    {
        return $this->isPresent()
            ? $this->value : $other;
    }

    /**
     * @param callable(): T $other
     * @return T
     */
    public function orElseGet(callable $other)
    {
        return $this->isPresent()
            ? $this->value : $other();
    }

    /**
     * @template E
     * @param callable(): E $exceptionSupplier
     * @return T
     *
     * @throws E
     */
    public function orElseThrow(callable $exceptionSupplier)
    {
        if ($this->isPresent()) {
            return $this->value;
        }

        throw $exceptionSupplier();
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
