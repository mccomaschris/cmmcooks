<?php

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
