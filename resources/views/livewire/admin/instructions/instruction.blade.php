
<?php

use App\Models\Instruction;
use Livewire\Volt\Component;

new class extends Component {
    public Instruction $instruction;

    public $name = '';
	public $note = '';

	public function rules()
    {
        return [
            'name' => ['required', 'string'],
			'note' => ['nullable', 'string'],
        ];
    }

    public function mount(Instruction $instruction)
    {
        $this->instruction = $instruction;
        $this->name = $instruction->name;
        $this->note = $instruction->note;
    }

    public function update()
    {
        $this->validate();

        $this->instruction->update([
			'name' => $this->name,
			'note' => $this->note,
        ]);

        $this->modal('instruction-edit-' . $this->instruction->id)->close();

		Flux::toast('Instruction updated successfully.');
    }
}; ?>

<flux:table.row x-sort:item="{{ $instruction->id }}">
    <flux:table.cell x-sort:handle class="cursor-grabbing">
        {{ $instruction->name }}
    </flux:table.cell>

    <flux:table.cell>
        <flux:dropdown align="end" offset="-15">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom" />

            <flux:menu class="min-w-32">
                <flux:modal.trigger :name="'instruction-edit-'.$instruction->id">
                    <flux:menu.item icon="pencil-square">Edit</flux:menu.item>
                </flux:modal.trigger>

                <flux:modal.trigger :name="'instruction-remove-'.$instruction->id">
                    <flux:menu.item icon="trash" variant="danger">Remove</flux:menu.item>
                </flux:modal.trigger>
            </flux:menu>
        </flux:dropdown>

        <flux:modal :name="'instruction-remove-'.$instruction->id" class="min-w-[22rem]">
            <form class="space-y-6" wire:submit="$parent.remove({{ $instruction->id }})">
                <div>
                    <flux:heading size="lg">Remove instruction?</flux:heading>

                    <flux:subheading>
                        <p>You're about to delete this instruction.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:subheading>
                </div>

                <div class="flex space-x-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="danger">Remove instruction</flux:button>
                </div>
            </form>
        </flux:modal>

        <flux:modal :name="'instruction-edit-'.$instruction->id" class="md:w-96" variant="flyout">
            <form wire:submit="update" class="space-y-6">
                <div>
                    <flux:heading size="lg">Edit instruction step</flux:heading>
                    <flux:subheading>Update an instruction step on the site.</flux:subheading>
                </div>

                <flux:textarea wire:model="name" label="Name" rows="auto" />

                <flux:textarea wire:model="note" label="Notes" rows="auto" />

                <div class="flex">
                    <flux:spacer />

                    <flux:button type="submit" variant="primary">Update instruction</flux:button>
                </div>
            </form>
        </flux:modal>
    </flux:table.cell>
</flux:table.row>
