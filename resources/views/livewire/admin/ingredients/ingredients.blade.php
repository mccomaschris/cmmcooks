<?php

use App\Models\Recipe;
use App\Models\Ingredient;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;

new class extends Component {
    public Recipe $recipe;

    public $name = '';
    public $amount = '';
    public $note = '';

    public function mount(Recipe $recipe) {
        $this->recipe = $recipe;
    }

    public function add()
    {
        $this->query()->create([
            'name' => $this->pull('name'),
            'amount' => $this->pull('amount'),
            'note' => $this->pull('note'),
            'position' => $this->query()->max('position') + 1,
        ]);

        Flux::toast('Ingredient added successfully!');
    }

    public function remove($id)
    {
		$ingredient = Ingredient::findOrFail($id);

        $this->move($ingredient, 9999999);

        $ingredient->delete();

        Flux::modal('ingredient-remove-'.$id)->close();

		Flux::toast('Ingredient successfully deleted.');
    }

    public function sort($item, $position)
    {
        $ingredient = $this->query()->findOrFail($item);

        $this->move($ingredient, $position);
    }

    protected function query()
    {
        return $this->recipe->ingredients();
    }

    protected function move($ingredient, $position)
    {
        DB::transaction(function () use ($ingredient, $position) {
            $current = $ingredient->position;
            $after = $position;

            // If there was no position change, don't shift...
            if ($current === $after) return;

            // Move the target todo out of the position stack...
            $ingredient->update(['position' => -1]);

            // Grab the shifted block and shift it up or down...
            $block = $this->query()->whereBetween('position', [
                min($current, $after),
                max($current, $after),
            ]);

            $needToShiftBlockUpBecauseDraggingTargetDown = $current < $after;

            $needToShiftBlockUpBecauseDraggingTargetDown
                ? $block->decrement('position')
                : $block->increment('position');

            // Place target back in position stack...
            $ingredient->update(['position' => $after]);
        });
    }
}; ?>

<div>
    <div class="mb-6">
        <flux:heading size="xl" :accent="true" id="ingredients">Ingredients</flux:heading>
    </div>

    <flux:table class="!whitespace-normal">
		<flux:table.columns>
			<flux:table.column>Ingredient</flux:table.column>
			<flux:table.column>Amount</flux:table.column>
			<flux:table.column></flux:table.column>
		</flux:table.columns>

		<flux:table.rows x-sort="$wire.sort($item, $position)">
			@foreach($recipe->ingredients as $ingredient)
                <livewire:admin.ingredients.ingredient :ingredient="$ingredient" :key="$ingredient->id" />
            @endforeach
		</flux:table.rows>
	</flux:table>

    <flux:separator variant="subtle" class="my-8" />

    <flux:heading size="lg" :accent="true">Add Ingredient</flux:heading>

    <form wire:submit="add" class="mt-8 space-y-6">
        <flux:input wire:model="amount" label="Amount/Measurement" />

        <flux:textarea wire:model="name" label="Ingredient" rows="auto" />

        <flux:textarea wire:model="note" label="Step notes" rows="auto" />
        <div class="flex justify-end">
            <flux:button type="submit">Add ingredient</flux:button>
        </div>
    </form>
</div>
