<?php

namespace Webkul\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCombinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'                 => 1,
            'product_attribute_id'       => 1,
            'product_attribute_value_id' => 1,
        ];
    }
}
