<?php

declare(strict_types=1);

use Pest\Attributes\DataProvider;
use Pest\Attributes\Description;
use Pest\Attributes\Group;
use Pest\Attributes\Test;

/**
 * Example of a unit test using attributes instead of PHPDoc comments.
 */
#[Test]
#[Group('unit')]
#[Description('Test that basic arithmetic operations work correctly')]
function basic_arithmetic_operations(): void
{
    expect(1 + 1)->toBe(2);
    expect(5 - 3)->toBe(2);
    expect(2 * 3)->toBe(6);
    expect(10 / 2)->toBe(5);
}

#[Test]
#[Group('unit')]
#[Description('Test string operations')]
function string_operations(): void
{
    expect('hello world')->toBe('hello world');
    expect(mb_strtoupper('hello'))->toBe('HELLO');
    expect(mb_strlen('hello'))->toBe(5);
}

#[Test]
#[Group('unit')]
#[Description('Test array operations')]
function array_operations(): void
{
    $array = [1, 2, 3];
    expect($array)->toHaveCount(3);
    expect($array)->toContain(2);

    $array[] = 4;
    expect($array)->toHaveCount(4);
    expect($array[3])->toBe(4);
}

#[Test]
#[Group('unit')]
#[DataProvider('calculator_data_provider')]
#[Description('Test calculator operations with data provider')]
function calculator_operations($a, $b, $expected, $operation): void
{
    $result = match ($operation) {
        'add' => $a + $b,
        'subtract' => $a - $b,
        'multiply' => $a * $b,
        'divide' => $a / $b,
    };

    expect($result)->toBe($expected);
}

function calculator_data_provider(): array
{
    return [
        'addition' => [5, 3, 8, 'add'],
        'subtraction' => [10, 4, 6, 'subtract'],
        'multiplication' => [3, 4, 12, 'multiply'],
        'division' => [10, 2, 5, 'divide'],
    ];
}
