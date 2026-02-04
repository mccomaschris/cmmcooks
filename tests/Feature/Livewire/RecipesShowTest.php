<?php

use App\Models\Ingredient;
use App\Models\Instruction;
use App\Models\Recipe;

test('recipe show page renders', function () {
    $recipe = Recipe::factory()->create();

    $response = $this->get("/recipes/{$recipe->slug}");

    $response->assertStatus(200);
});

test('displays recipe name', function () {
    $recipe = Recipe::factory()->create(['name' => 'Delicious Pasta']);

    $response = $this->get("/recipes/{$recipe->slug}");

    $response->assertSee('Delicious Pasta');
});

test('displays recipe ingredients', function () {
    $recipe = Recipe::factory()->create();
    Ingredient::factory()->create([
        'recipe_id' => $recipe->id,
        'name' => 'Fresh Tomatoes',
        'position' => 1,
    ]);

    $response = $this->get("/recipes/{$recipe->slug}");

    $response->assertSee('Fresh Tomatoes');
});

test('displays recipe instructions', function () {
    $recipe = Recipe::factory()->create();
    Instruction::factory()->create([
        'recipe_id' => $recipe->id,
        'name' => 'Preheat oven to 350F',
        'position' => 1,
    ]);

    $response = $this->get("/recipes/{$recipe->slug}");

    $response->assertSee('Preheat oven to 350F');
});
