<?php

use App\Models\Ingredient;
use App\Models\Instruction;
use App\Models\Recipe;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::admin')]
#[Title('Create recipe')]
class extends Component
{
    public $name = '';

    public $ingredients = '';

    public $instructions = '';

    public $rules = [
        'name' => 'required|string|max:255',
        'ingredients' => 'nullable|string',
        'instructions' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();

        $recipe = Recipe::create([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
        ]);

        foreach ($this->parseLines($this->ingredients) as $position => $line) {
            Ingredient::create([
                'recipe_id' => $recipe->id,
                'name' => $line,
                'position' => $position + 1,
            ]);
        }

        foreach ($this->parseLines($this->instructions) as $position => $line) {
            Instruction::create([
                'recipe_id' => $recipe->id,
                'name' => $line,
                'position' => $position + 1,
            ]);
        }

        return $this->redirect(route('admin.recipes.edit', $recipe));
    }

    private function parseLines(string $text): array
    {
        if (trim($text) === '') {
            return [];
        }

        return array_values(
            array_filter(
                array_map(function ($line) {
                    $line = trim($line);
                    $line = preg_replace('/^(\d+[\.\)]\s*|[-*\x{2022}]\s*)/u', '', $line);

                    return trim($line);
                }, explode("\n", $text)),
                fn ($line) => $line !== '',
            )
        );
    }
}; ?>

<div>
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Create recipe</flux:heading>
            <flux:subheading>Add a new recipe with ingredients and instructions.</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-8" />

    <form wire:submit="save" class="space-y-6 max-w-lg">
        <flux:input wire:model="name" label="Recipe name" />

        <flux:textarea wire:model="ingredients" label="Ingredients" rows="8" placeholder="Paste your ingredient list, one per line" />

        <flux:textarea wire:model="instructions" label="Instructions" rows="8" placeholder="Paste your instructions, one per line" />

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">Create recipe</flux:button>
        </div>
    </form>
</div>
