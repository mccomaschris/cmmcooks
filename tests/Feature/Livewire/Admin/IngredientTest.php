<?php

use App\Models\Ingredient;
use App\Models\Recipe;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = createAdmin();
    $this->recipe = Recipe::factory()->create();
    $this->ingredient = Ingredient::factory()->create([
        'recipe_id' => $this->recipe->id,
        'name' => 'Original Name',
        'amount' => '1 cup',
        'note' => 'some note',
        'position' => 1,
    ]);
});

test('displays ingredient data', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.ingredients.ingredient', ['ingredient' => $this->ingredient])
        ->assertSee('Original Name')
        ->assertSee('1 cup');
});

test('component has correct initial state', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.ingredients.ingredient', ['ingredient' => $this->ingredient])
        ->assertSet('name', 'Original Name')
        ->assertSet('amount', '1 cup')
        ->assertSet('note', 'some note');
});

test('can update ingredient', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.ingredients.ingredient', ['ingredient' => $this->ingredient])
        ->set('name', 'Updated Name')
        ->set('amount', '2 cups')
        ->call('update');

    $this->assertDatabaseHas('ingredients', [
        'id' => $this->ingredient->id,
        'name' => 'Updated Name',
        'amount' => '2 cups',
    ]);
});

test('name is required', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.ingredients.ingredient', ['ingredient' => $this->ingredient])
        ->set('name', '')
        ->call('update')
        ->assertHasErrors(['name' => 'required']);
});
