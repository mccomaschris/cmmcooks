
<?php

use App\Models\Ingredient;
use Livewire\Component;

new class extends Component
{
    public Ingredient $ingredient;

    public $name = '';

    public $amount = '';

    public $note = '';

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'amount' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
        ];
    }

    public function mount(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
        $this->name = $ingredient->name;
        $this->amount = $ingredient->amount;
        $this->note = $ingredient->note;
    }

    public function update()
    {
        $this->validate();

        $this->ingredient->update([
            'name' => $this->name,
            'amount' => $this->amount,
            'note' => $this->note,
        ]);

        Flux::modal('ingredient-edit-' . $this->ingredient->id)->close();

        Flux::toast('Ingredient updated successfully.');
    }
}; ?>

<flux:table.row x-sort:item="{{ $ingredient->id }}">
    <flux:table.cell x-sort:handle class="cursor-grabbing">
        {{ $ingredient->name }}
    </flux:table.cell>

    <flux:table.cell x-sort:handle class="cursor-grabbing">
        {{ $ingredient->amount }}
    </flux:table.cell>

    <flux:table.cell>
        <flux:dropdown align="end" offset="-15">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom" />

            <flux:menu class="min-w-32">
                <flux:modal.trigger :name="'ingredient-edit-'.$ingredient->id">
                    <flux:menu.item icon="pencil-square">Edit</flux:menu.item>
                </flux:modal.trigger>

                <flux:modal.trigger :name="'ingredient-remove-'.$ingredient->id">
                    <flux:menu.item icon="trash" variant="danger">Remove</flux:menu.item>
                </flux:modal.trigger>
            </flux:menu>
        </flux:dropdown>

        <flux:modal :name="'ingredient-remove-'.$ingredient->id" class="min-w-[22rem]">
            <form class="space-y-6" wire:submit="$parent.remove({{ $ingredient->id }})">
                <div>
                    <flux:heading size="lg">Remove ingredient?</flux:heading>

                    <flux:subheading>
                        <p>You're about to delete this ingredient.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:subheading>
                </div>

                <div class="flex space-x-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="danger">Remove ingredient</flux:button>
                </div>
            </form>
        </flux:modal>

        <flux:modal :name="'ingredient-edit-'.$ingredient->id" class="md:w-96" variant="flyout">
            <form wire:submit="update" class="space-y-6">
                <div>
                    <flux:heading size="lg">Edit ingredient</flux:heading>
                    <flux:subheading>Update an ingredient on the site.</flux:subheading>
                </div>

                <flux:input wire:model="amount" label="Amount/Measurement" />

                <flux:textarea wire:model="name" label="Ingredient" rows="auto" />

                <flux:textarea wire:model="note" label="Step notes" rows="auto" />

                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Update ingredient</flux:button>
                </div>
            </form>
        </flux:modal>
    </flux:table.cell>
</flux:table.row>
