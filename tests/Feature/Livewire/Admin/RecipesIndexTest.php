<?php

use App\Models\Recipe;
use App\Models\User;
use Livewire\Livewire;

test('admin recipes index requires authentication', function () {
    $response = $this->get('/admin/recipes');

    $response->assertStatus(401);
});

test('admin recipes index requires admin email', function () {
    $user = User::factory()->create(['email' => 'user@example.com']);

    $response = $this->actingAs($user)->get('/admin/recipes');

    $response->assertStatus(401);
});

test('admin can access recipes index', function () {
    $admin = createAdmin();

    $response = $this->actingAs($admin)->get('/admin/recipes');

    $response->assertStatus(200);
});

test('displays recipes list', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create(['name' => 'Test Recipe']);

    $response = $this->actingAs($admin)->get('/admin/recipes');

    $response->assertSee('Test Recipe');
});

test('can remove recipe', function () {
    $admin = createAdmin();
    $recipe = Recipe::factory()->create();

    Livewire::actingAs($admin)
        ->test('admin.recipes.index')
        ->call('remove', $recipe->id);

    $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
});

test('pagination works', function () {
    $admin = createAdmin();
    Recipe::factory()->count(15)->create();

    Livewire::actingAs($admin)
        ->test('admin.recipes.index')
        ->assertSee('Next');
});
