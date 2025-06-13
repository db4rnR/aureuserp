<?php

namespace Webkul\Website\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Website\Models\Page;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Webkul\Website\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Page::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        ];
    }
}
