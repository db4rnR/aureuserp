<?php

declare(strict_types=1);

namespace Webkul\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Inventory\Models\Move;
use Webkul\Security\Models\User;

/**
 * @extends Factory<Move>
 */
final class MoveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Move::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'sort' => fake()->randomNumber(),
            'creator_id' => User::factory(),
        ];
    }
}
