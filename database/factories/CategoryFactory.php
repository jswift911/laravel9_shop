<?php

namespace Database\Factories;

use App\Models\Category;
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

    //Генерация слцчайных данных
    public function definition() : array
    {
        return [
            'title' => ucfirst($this->faker->words(2, true)),
        ];
    }
}