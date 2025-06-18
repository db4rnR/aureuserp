<?php

declare(strict_types=1);

namespace Webkul\Purchase\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Purchase\Models\RequisitionLine;
use Webkul\Security\Models\User;

/**
 * @extends Factory<RequisitionLine>
 */
final class RequisitionLineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RequisitionLine::class;

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
