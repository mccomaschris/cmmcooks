<?php

use App\Models\Recipe;
use Livewire\Livewire;

test('home page renders', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('displays recipes', function () {
    $recipe = Recipe::factory()->create(['name' => 'Chocolate Cake']);

    $response = $this->get('/');

    $response->assertSee('Chocolate Cake');
});

test('search filters recipes', function () {
    Recipe::factory()->create(['name' => 'Chocolate Cake']);
    Recipe::factory()->create(['name' => 'Vanilla Ice Cream']);

    Livewire::test('site.index')
        ->set('search', 'Chocolate')
        ->assertSee('Chocolate Cake')
        ->assertDontSee('Vanilla Ice Cream');
});

test('empty search shows all recipes', function () {
    Recipe::factory()->create(['name' => 'Chocolate Cake']);
    Recipe::factory()->create(['name' => 'Vanilla Ice Cream']);

    Livewire::test('site.index')
        ->set('search', '')
        ->assertSee('Chocolate Cake')
        ->assertSee('Vanilla Ice Cream');
});
