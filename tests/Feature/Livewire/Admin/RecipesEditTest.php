<?php

use App\Models\Recipe;
use Livewire\Livewire;

test('admin can access edit page', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create();

    $response = $this->actingAs($admin)->get("/admin/recipes/{$recipe->slug}/edit");

    $response->assertStatus(200);
});

test('edit page shows recipe data', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create([
        'name' => 'My Recipe',
        'description' => 'A great recipe',
    ]);

    Livewire::actingAs($admin)
        ->test('admin.recipes.edit', ['recipe' => $recipe])
        ->assertSet('name', 'My Recipe')
        ->assertSet('description', 'A great recipe');
});

test('can update recipe', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create(['name' => 'Old Name']);

    Livewire::actingAs($admin)
        ->test('admin.recipes.edit', ['recipe' => $recipe])
        ->set('name', 'New Name')
        ->call('update');

    $this->assertDatabaseHas('recipes', [
        'id' => $recipe->id,
        'name' => 'New Name',
    ]);
});

test('name is required', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create();

    Livewire::actingAs($admin)
        ->test('admin.recipes.edit', ['recipe' => $recipe])
        ->set('name', '')
        ->call('update')
        ->assertHasErrors(['name' => 'required']);
});

test('name must be unique except self', function () {
    $admin = createAdmin();
    $recipe1 = Recipe::factory()->create(['name' => 'Recipe One']);
    $recipe2 = Recipe::factory()->create(['name' => 'Recipe Two']);

    Livewire::actingAs($admin)
        ->test('admin.recipes.edit', ['recipe' => $recipe2])
        ->set('name', 'Recipe One')
        ->call('update')
        ->assertHasErrors(['name' => 'unique']);
});

test('can keep same name', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create(['name' => 'My Recipe']);

    Livewire::actingAs($admin)
        ->test('admin.recipes.edit', ['recipe' => $recipe])
        ->set('name', 'My Recipe')
        ->call('update')
        ->assertHasNoErrors();
});
