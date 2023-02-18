<?php

namespace Database\Factories;

use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Category::class;

    //Генерация слцчайных данных
    public function definition() : array
    {
        return [
            'title' => ucfirst($this->faker->words(2, true)),
            'on_home_page' => $this->faker->boolean(),
            'sorting' => $this->faker->numberBetween(1,999),
        ];
    }
}
