<?php

use App\Models\Ingredient;
use App\Models\Recipe;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = createAdmin();
    $this->recipe = Recipe::factory()->create();
});

test('displays existing ingredients', function () {
    Ingredient::factory()->create([
        'recipe_id' => $this->recipe->id,
        'name' => 'Sugar',
        'amount' => '1 cup',
        'position' => 1,
    ]);

    Livewire::actingAs($this->admin)
        ->test('admin.ingredients.ingredients', ['recipe' => $this->recipe])
        ->assertSee('Sugar')
        ->assertSee('1 cup');
});

test('can add ingredient', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.ingredients.ingredients', ['recipe' => $this->recipe])
        ->set('name', 'Flour')
        ->set('amount', '2 cups')
        ->set('note', 'sifted')
        ->call('add');

    $this->assertDatabaseHas('ingredients', [
        'recipe_id' => $this->recipe->id,
        'name' => 'Flour',
        'amount' => '2 cups',
        'note' => 'sifted',
    ]);
});

test('add clears form fields', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.ingredients.ingredients', ['recipe' => $this->recipe])
        ->set('name', 'Flour')
        ->set('amount', '2 cups')
        ->call('add')
        ->assertSet('name', '')
        ->assertSet('amount', '')
        ->assertSet('note', '');
});

test('can remove ingredient', function () {
    $ingredient = Ingredient::factory()->create([
        'recipe_id' => $this->recipe->id,
        'position' => 1,
    ]);

    Livewire::actingAs($this->admin)
        ->test('admin.ingredients.ingredients', ['recipe' => $this->recipe])
        ->call('remove', $ingredient->id);

    $this->assertDatabaseMissing('ingredients', ['id' => $ingredient->id]);
});

test('can sort ingredients', function () {
    $ingredient1 = Ingredient::factory()->create([
        'recipe_id' => $this->recipe->id,
        'position' => 1,
    ]);
    $ingredient2 = Ingredient::factory()->create([
        'recipe_id' => $this->recipe->id,
        'position' => 2,
    ]);

    Livewire::actingAs($this->admin)
        ->test('admin.ingredients.ingredients', ['recipe' => $this->recipe])
        ->call('sort', $ingredient1->id, 2);

    expect($ingredient1->fresh()->position)->toBe(2);
});

test('new ingredient gets next position', function () {
    Ingredient::factory()->create([
        'recipe_id' => $this->recipe->id,
        'position' => 5,
    ]);

    Livewire::actingAs($this->admin)
        ->test('admin.ingredients.ingredients', ['recipe' => $this->recipe])
        ->set('name', 'New Ingredient')
        ->call('add');

    $this->assertDatabaseHas('ingredients', [
        'recipe_id' => $this->recipe->id,
        'name' => 'New Ingredient',
        'position' => 6,
    ]);
});
