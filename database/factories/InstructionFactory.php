<?php

namespace Database\Factories;

use App\Models\Instruction;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructionFactory extends Factory
{
    protected $model = Instruction::class;

    public function definition(): array
    {
        return [
            'recipe_id' => Recipe::factory(),
            'name' => fake()->sentence(),
            'note' => fake()->optional()->sentence(),
            'position' => fake()->numberBetween(1, 100),
        ];
    }
}
