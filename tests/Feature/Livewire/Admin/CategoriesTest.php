<?php

use App\Models\Category;
use App\Models\Recipe;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = createAdmin();
    $this->recipe = Recipe::factory()->create();
});

test('displays all categories', function () {
    Category::factory()->create(['name' => 'Breakfast']);
    Category::factory()->create(['name' => 'Dinner']);

    Livewire::actingAs($this->admin)
        ->test('admin.categories.categories', ['recipe' => $this->recipe])
        ->assertSee('Breakfast')
        ->assertSee('Dinner');
});

test('loads recipe categories', function () {
    $category = Category::factory()->create(['name' => 'Dessert']);
    $this->recipe->categories()->attach($category);

    Livewire::actingAs($this->admin)
        ->test('admin.categories.categories', ['recipe' => $this->recipe])
        ->assertSet('categories', [$category->id]);
});

test('can sync categories', function () {
    $category1 = Category::factory()->create();
    $category2 = Category::factory()->create();

    Livewire::actingAs($this->admin)
        ->test('admin.categories.categories', ['recipe' => $this->recipe])
        ->set('categories', [$category1->id, $category2->id]);

    expect($this->recipe->fresh()->categories->contains($category1))->toBeTrue();
    expect($this->recipe->fresh()->categories->contains($category2))->toBeTrue();
});

test('can remove all categories', function () {
    $category = Category::factory()->create();
    $this->recipe->categories()->attach($category);

    Livewire::actingAs($this->admin)
        ->test('admin.categories.categories', ['recipe' => $this->recipe])
        ->set('categories', []);

    expect($this->recipe->fresh()->categories)->toBeEmpty();
});
