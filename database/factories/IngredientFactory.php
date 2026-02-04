<?php

namespace Database\Factories;

use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngredientFactory extends Factory
{
    protected $model = Ingredient::class;

    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'name' => fake()->words(3, true),
            'amount' => fake()->optional()->randomElement(['1 cup', '2 tbsp', '1/2 tsp', '3 oz', '1 lb']),
            'note' => fake()->optional()->sentence(),
            'position' => fake()->numberBetween(1, 100),
        ];
    }
}
