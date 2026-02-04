<?php

use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Instruction;
use App\Models\Recipe;
use Livewire\Livewire;

test('displays recipe name', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create(['name' => 'Test Recipe']);

    Livewire::actingAs($admin)
        ->test('admin.recipes.recipe', ['recipe' => $recipe])
        ->assertSee('Test Recipe');
});

test('displays ingredient count', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create();
    Ingredient::factory()->count(3)->create([
        'recipe_id' => $recipe->id,
        'position' => 1,
    ]);

    Livewire::actingAs($admin)
        ->test('admin.recipes.recipe', ['recipe' => $recipe->load('ingredients')])
        ->assertSee('3');
});

test('displays instruction count', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create();
    Instruction::factory()->count(5)->create([
        'recipe_id' => $recipe->id,
        'position' => 1,
    ]);

    Livewire::actingAs($admin)
        ->test('admin.recipes.recipe', ['recipe' => $recipe->load('instructions')])
        ->assertSee('5');
});

test('displays categories', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create();
    $category = Category::factory()->create(['name' => 'Appetizer']);
    $recipe->categories()->attach($category);

    Livewire::actingAs($admin)
        ->test('admin.recipes.recipe', ['recipe' => $recipe->load('categories')])
        ->assertSee('Appetizer');
});
