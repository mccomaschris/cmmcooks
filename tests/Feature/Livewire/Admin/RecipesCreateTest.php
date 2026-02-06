<?php

use App\Models\Recipe;
use Livewire\Livewire;

test('can create recipe', function () {
    $admin = createAdmin();

    Livewire::actingAs($admin)
        ->test('admin.recipes.create')
        ->set('name', 'New Recipe')
        ->call('save')
        ->assertRedirect();

    $this->assertDatabaseHas('recipes', ['name' => 'New Recipe']);
});

test('name is required', function () {
    $admin = createAdmin();

    Livewire::actingAs($admin)
        ->test('admin.recipes.create')
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name' => 'required']);
});

test('name max length', function () {
    $admin = createAdmin();

    Livewire::actingAs($admin)
        ->test('admin.recipes.create')
        ->set('name', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['name' => 'max']);
});

test('can bulk create ingredients', function () {
    $admin = createAdmin();

    Livewire::actingAs($admin)
        ->test('admin.recipes.create')
        ->set('name', 'Bulk Recipe')
        ->set('ingredients', "Salt\nPepper\nGarlic")
        ->call('save')
        ->assertRedirect();

    $recipe = Recipe::where('name', 'Bulk Recipe')->first();

    expect($recipe->ingredients)->toHaveCount(3);
    expect($recipe->ingredients[0]->name)->toBe('Salt');
    expect($recipe->ingredients[1]->name)->toBe('Pepper');
    expect($recipe->ingredients[2]->name)->toBe('Garlic');
});

test('can bulk create instructions', function () {
    $admin = createAdmin();

    Livewire::actingAs($admin)
        ->test('admin.recipes.create')
        ->set('name', 'Bulk Recipe')
        ->set('instructions', "Preheat oven\nMix ingredients\nBake for 30 minutes")
        ->call('save')
        ->assertRedirect();

    $recipe = Recipe::where('name', 'Bulk Recipe')->first();

    expect($recipe->instructions)->toHaveCount(3);
    expect($recipe->instructions[0]->name)->toBe('Preheat oven');
    expect($recipe->instructions[1]->name)->toBe('Mix ingredients');
    expect($recipe->instructions[2]->name)->toBe('Bake for 30 minutes');
});

test('bulk ingredients assigns sequential positions', function () {
    $admin = createAdmin();

    Livewire::actingAs($admin)
        ->test('admin.recipes.create')
        ->set('name', 'Position Recipe')
        ->set('ingredients', "First\nSecond\nThird")
        ->call('save');

    $recipe = Recipe::where('name', 'Position Recipe')->first();

    expect($recipe->ingredients[0]->position)->toBe(1);
    expect($recipe->ingredients[1]->position)->toBe(2);
    expect($recipe->ingredients[2]->position)->toBe(3);
});

test('bulk parsing strips bullet prefixes', function () {
    $admin = createAdmin();

    Livewire::actingAs($admin)
        ->test('admin.recipes.create')
        ->set('name', 'Bullet Recipe')
        ->set('ingredients', "- Salt\n* Pepper\n• Garlic")
        ->call('save');

    $recipe = Recipe::where('name', 'Bullet Recipe')->first();

    expect($recipe->ingredients[0]->name)->toBe('Salt');
    expect($recipe->ingredients[1]->name)->toBe('Pepper');
    expect($recipe->ingredients[2]->name)->toBe('Garlic');
});

test('bulk parsing strips numbered prefixes', function () {
    $admin = createAdmin();

    Livewire::actingAs($admin)
        ->test('admin.recipes.create')
        ->set('name', 'Numbered Recipe')
        ->set('instructions', "1. Preheat oven\n2. Mix ingredients\n3) Bake")
        ->call('save');

    $recipe = Recipe::where('name', 'Numbered Recipe')->first();

    expect($recipe->instructions[0]->name)->toBe('Preheat oven');
    expect($recipe->instructions[1]->name)->toBe('Mix ingredients');
    expect($recipe->instructions[2]->name)->toBe('Bake');
});

test('bulk parsing skips blank lines', function () {
    $admin = createAdmin();

    Livewire::actingAs($admin)
        ->test('admin.recipes.create')
        ->set('name', 'Blanks Recipe')
        ->set('ingredients', "Salt\n\n\nPepper\n  \nGarlic")
        ->call('save');

    $recipe = Recipe::where('name', 'Blanks Recipe')->first();

    expect($recipe->ingredients)->toHaveCount(3);
});

test('recipe can be created without ingredients or instructions', function () {
    $admin = createAdmin();

    Livewire::actingAs($admin)
        ->test('admin.recipes.create')
        ->set('name', 'Empty Recipe')
        ->set('ingredients', '')
        ->set('instructions', '')
        ->call('save')
        ->assertRedirect();

    $recipe = Recipe::where('name', 'Empty Recipe')->first();

    expect($recipe->ingredients)->toHaveCount(0);
    expect($recipe->instructions)->toHaveCount(0);
});
