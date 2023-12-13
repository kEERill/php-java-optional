<?php

namespace Keerill\Optional\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Keerill\Optional\Optional;

/**
 * @author serhatozdal
 * @author kEERill
 */
class OptionalTest extends TestCase
{
    public function test_is_present_return_false_when_value_is_empty()
    {
        $this->assertFalse(Optional::ofEmpty()->isPresent());
    }

    public function test_or_else_not_execute_when_value_not_empty()
    {
        $optional = Optional::of('value')
            ->orElse('value is empty');

        $this->assertEquals('value', $optional);
    }

    public function test_or_else_return_new_value_when_value_is_empty()
    {
        $optional = Optional::ofEmpty()
            ->orElse('value is empty');

        $this->assertEquals('value is empty', $optional);
    }

    public function test_or_else_throw_not_call_throw_when_value_not_empty()
    {
        $optional = Optional::of('value')
            ->orElseThrow(fn () => new InvalidArgumentException());

        $this->assertEquals('value', $optional);
    }

    public function test_or_else_throw_exception_when_value_is_empty()
    {
        $this->expectException(InvalidArgumentException::class);

        Optional::ofEmpty()
            ->orElseThrow(fn () => new InvalidArgumentException());
    }

    public function test_filter_value_when_value_is_empty()
    {
        $optional = Optional::ofEmpty()
            ->filter(fn ($a) => (int) $a)
            ->get();

        $this->assertNull($optional);
    }

    public function test_filter_when_value_is_not_empty()
    {
        $optional = Optional::of('value')
            ->filter(fn (string $a) => is_int($a))
            ->get();

        $this->assertNull($optional);
    }

    public function test_make_optional_when_value_is_empty()
    {
        $this->assertEquals(Optional::ofEmpty(), Optional::ofNullable(null));
    }

    public function test_if_present_function_execute_when_value_is_not_empty()
    {
        Optional::of(5)
            ->ifPresent(fn () => $this->assertTrue(true));
    }

    public function test_if_present_function_not_execute_when_value_is_empty()
    {
        Optional::ofEmpty()
            ->ifPresent(fn () => $this->fail('Not execute if value is empty'));

        $this->assertTrue(true);
    }

    public function test_map()
    {
        $optional = Optional::of(5)
            ->map(fn ($a) => $a * 2)
            ->get();

        Optional::of(5)
            ->map(fn (int $a): int => "$a")
            ->get();

        $this->assertEquals(10, $optional);
    }

    public function test_flat_map_when_value_is_not_empty()
    {
        $optional = Optional::of(5)
            ->flatMap(fn(int $a) =>  Optional::of($a * 2))
            ->get();

        $this->assertEquals(10, $optional);
    }

    public function test_flat_map_when_value_is_empty()
    {
        $optional = Optional::of(5)
            ->flatMap(fn (int $a) => null)
            ->flatMap(fn ($a) => (int) $a)
            ->get();

        $this->assertNull($optional);
    }

    public function test_or_else_get_function_not_execute_when_value_is_not_empty()
    {
        $optional = Optional::of(5)
            ->orElseGet(fn() => 10);

        $this->assertEquals(5, $optional);
    }

    public function test_or_else_get_function_execute_when_value_is_empty()
    {
        $optional = Optional::ofEmpty()
            ->orElseGet(fn () => 10);

        $this->assertEquals(10, $optional);
    }
}