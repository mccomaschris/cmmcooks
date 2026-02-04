<?php

use App\Models\Instruction;
use App\Models\Recipe;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = createAdmin();
    $this->recipe = Recipe::factory()->create();
    $this->instruction = Instruction::factory()->create([
        'recipe_id' => $this->recipe->id,
        'name' => 'Original Step',
        'note' => 'some note',
        'position' => 1,
    ]);
});

test('displays instruction data', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.instructions.instruction', ['instruction' => $this->instruction])
        ->assertSee('Original Step');
});

test('component has correct initial state', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.instructions.instruction', ['instruction' => $this->instruction])
        ->assertSet('name', 'Original Step')
        ->assertSet('note', 'some note');
});

test('can update instruction', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.instructions.instruction', ['instruction' => $this->instruction])
        ->set('name', 'Updated Step')
        ->set('note', 'updated note')
        ->call('update');

    $this->assertDatabaseHas('instructions', [
        'id' => $this->instruction->id,
        'name' => 'Updated Step',
        'note' => 'updated note',
    ]);
});

test('name is required', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.instructions.instruction', ['instruction' => $this->instruction])
        ->set('name', '')
        ->call('update')
        ->assertHasErrors(['name' => 'required']);
});
