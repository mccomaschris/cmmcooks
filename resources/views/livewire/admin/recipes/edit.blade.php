<?php

use App\Models\Recipe;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts::admin')]
#[Title('All recipes')]
class extends Component
{
    public Recipe $recipe;

    public $name = '';

    public $description = '';

    public $notes = '';

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('recipes')->ignore($this->recipe->id),
            ],
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }

    public function mount(Recipe $recipe)
    {
        $this->recipe = $recipe;
        $this->name = $recipe->name;
        $this->description = $recipe->description;
        $this->notes = $recipe->notes;
    }

    public function update()
    {
        $this->validate();

        $this->recipe->update([
            'name' => $this->name,
            'description' => $this->description,
            'notes' => $this->notes,
        ]);

        Flux::toast('Recipe updated successfully!');
    }
}; ?>

<div>
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Edit recipes</flux:heading>
            <flux:subheading>You can make updates and changes to this recipe.</flux:subheading>
        </div>
    </div>

    <flux:separator class="my-8" />

    <div class="flex flex-col lg:flex-row gap-4 lg:gap-6  min-h-screen">
        <flux:navlist class="w-64 sticky top-8 self-start">
            <flux:navlist.item href="#details">Details</flux:navlist.item>
            <flux:navlist.item href="#ingredients">Ingredients</flux:navlist.item>
            <flux:navlist.item href="#steps">Steps</flux:navlist.item>
            <flux:navlist.item href="#categories">Categories</flux:navlist.item>
        </flux:navlist>

        <div class="">
            <div>
                <div>
                    <flux:heading size="lg" id="details">Recipe details</flux:heading>
                    <flux:subheading>Update the recipe details below.</flux:subheading>

                    <form wire:submit="update" class="space-y-6 mt-6">
                        <flux:input label="Name" wire:model="name" />

                        <flux:textarea rows="2" label="Description" wire:model="description" />

                        <div class="flex justify-end">
                            <flux:button type="submit" variant="primary">Save recipe</flux:button>
                        </div>
                    </form>
                </div>

                <flux:separator class="my-8" />

                <livewire:admin.ingredients.ingredients :recipe="$recipe" />

                <flux:separator class="my-8" />

                <livewire:admin.instructions.instructions :recipe="$recipe" />

                <flux:separator class="my-8" />

                <livewire:admin.categories.categories :recipe="$recipe" />
            </div>
        </div>
    </div>
</div>
