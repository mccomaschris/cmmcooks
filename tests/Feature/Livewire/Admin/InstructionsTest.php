<?php

use App\Models\Instruction;
use App\Models\Recipe;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = createAdmin();
    $this->recipe = Recipe::factory()->create();
});

test('displays existing instructions', function () {
    Instruction::factory()->create([
        'recipe_id' => $this->recipe->id,
        'name' => 'Preheat oven',
        'position' => 1,
    ]);

    Livewire::actingAs($this->admin)
        ->test('admin.instructions.instructions', ['recipe' => $this->recipe])
        ->assertSee('Preheat oven');
});

test('can add instruction', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.instructions.instructions', ['recipe' => $this->recipe])
        ->set('name', 'Mix ingredients')
        ->set('note', 'be gentle')
        ->call('add');

    $this->assertDatabaseHas('instructions', [
        'recipe_id' => $this->recipe->id,
        'name' => 'Mix ingredients',
        'note' => 'be gentle',
    ]);
});

test('add clears form fields', function () {
    Livewire::actingAs($this->admin)
        ->test('admin.instructions.instructions', ['recipe' => $this->recipe])
        ->set('name', 'Mix ingredients')
        ->set('note', 'test note')
        ->call('add')
        ->assertSet('name', '')
        ->assertSet('note', '');
});

test('can remove instruction', function () {
    $instruction = Instruction::factory()->create([
        'recipe_id' => $this->recipe->id,
        'position' => 1,
    ]);

    Livewire::actingAs($this->admin)
        ->test('admin.instructions.instructions', ['recipe' => $this->recipe])
        ->call('remove', $instruction->id);

    $this->assertDatabaseMissing('instructions', ['id' => $instruction->id]);
});

test('can sort instructions', function () {
    $instruction1 = Instruction::factory()->create([
        'recipe_id' => $this->recipe->id,
        'position' => 1,
    ]);
    $instruction2 = Instruction::factory()->create([
        'recipe_id' => $this->recipe->id,
        'position' => 2,
    ]);

    Livewire::actingAs($this->admin)
        ->test('admin.instructions.instructions', ['recipe' => $this->recipe])
        ->call('sort', $instruction1->id, 2);

    expect($instruction1->fresh()->position)->toBe(2);
});

test('new instruction gets next position', function () {
    Instruction::factory()->create([
        'recipe_id' => $this->recipe->id,
        'position' => 3,
    ]);

    Livewire::actingAs($this->admin)
        ->test('admin.instructions.instructions', ['recipe' => $this->recipe])
        ->set('name', 'New Step')
        ->call('add');

    $this->assertDatabaseHas('instructions', [
        'recipe_id' => $this->recipe->id,
        'name' => 'New Step',
        'position' => 4,
    ]);
});
