<?php

use App\Models\Instruction;
use App\Models\Recipe;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

new class extends Component
{
    public Recipe $recipe;

    public $name = '';

    public $note = '';

    public $editingName = '';

    public $editingNote = '';

    public function mount(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }

    public function add()
    {
        $this->query()->create([
            'name' => $this->name,
            'note' => $this->note,
            'position' => $this->query()->max('position') + 1,
        ]);

        $this->reset(['name', 'note']);

        Flux::toast('Instruction added successfully!');
    }

    public function startUpdating($id)
    {
        $instruction = Instruction::findOrFail($id);

        $this->editingName = $instruction->name;
        $this->editingNote = $instruction->note;

        Flux::modal('instruction-edit')->show();
    }

    public function remove($id)
    {
        $instruction = Instruction::findOrFail($id);

        $this->move($instruction, 9999999);

        $instruction->delete();

        Flux::modal('instruction-remove-' . $id)->close();

        Flux::toast('Instruction successfully deleted.');
    }

    public function sort($item, $position)
    {
        $instruction = $this->query()->findOrFail($item);

        $this->move($instruction, $position);
    }

    protected function query()
    {
        return $this->recipe->instructions();
    }

    protected function move($instruction, $position)
    {
        DB::transaction(function () use ($instruction, $position) {
            $current = $instruction->position;
            $after = $position;

            // If there was no position change, don't shift...
            if ($current === $after) {
                return;
            }

            // Move the target todo out of the position stack...
            $instruction->update(['position' => -1]);

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
            $instruction->update(['position' => $after]);
        });
    }
}; ?>

<div>
    <div class="mb-6">
        <flux:heading size="xl" :accent="true" id="steps">Steps</flux:heading>
    </div>

    <flux:table class="!whitespace-normal">
		<flux:table.columns>
			<flux:table.column>Step Instruction</flux:table.column>
			<flux:table.column></flux:table.column>
		</flux:table.columns>

		<flux:table.rows x-sort="$wire.sort($item, $position)">
			@foreach($recipe->instructions as $instruction)
                <livewire:admin.instructions.instruction :instruction="$instruction" :key="$instruction->id" />
            @endforeach
		</flux:table.rows>
	</flux:table>

    <flux:separator variant="subtle" class="my-8" />

    <flux:heading size="lg" :accent="true">Add Step</flux:heading>

    <form wire:submit="add" class="mt-8 space-y-6">
        <flux:textarea wire:model="name" label="Step instruction" rows="auto" />
        <flux:textarea wire:model="note" label="Step notes" rows="auto" />
        <div class="flex justify-end">
            <flux:button type="submit">Add step</flux:button>
        </div>
    </form>
</div>
