<?php

use App\Models\Recipe;

test('home page has no breadcrumbs', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertDontSee('Home</');
});

test('recipe page shows breadcrumbs', function () {
    $recipe = Recipe::factory()->create(['name' => 'Test Recipe']);

    $response = $this->get("/recipes/{$recipe->slug}");

    $response->assertStatus(200);
    $response->assertSee('Home');
    $response->assertSee('Recipes');
});
