<?php

use Livewire\Volt\Component;
use App\Models\Recipe;

new class extends Component {
	public Recipe $recipe;

	public function mount(Recipe $recipe)
	{
		$this->recipe = $recipe;
	}
}; ?>

<flux:table.row :key="$recipe->id">
	<flux:table.cell>
        <flux:link href="{{ route('admin.recipes.edit', [$recipe]) }}">
            {{  $recipe->name }}
        </flux:link>
    </flux:table.cell>
	<flux:table.cell>{{ $recipe->ingredients_count }}</flux:table.cell>
	<flux:table.cell>{{ $recipe->steps_count }}</flux:table.cell>
	<flux:table.cell>
        @foreach($recipe->categories as $category)
            <flux:badge>{{ $category->name }}</flux:badge>
        @endforeach
    </flux:table.cell>
	<flux:table.cell>
        <flux:dropdown align="end" offset="-15">
            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" inset="top bottom" />

            <flux:menu class="min-w-32">
                <flux:menu.item href="{{ route('recipes.show', [$recipe]) }}" icon="document-magnifying-glass">View</flux:menu.item>
                <flux:menu.item href="{{ route('admin.recipes.edit', [$recipe]) }}" icon="pencil-square">Edit</flux:menu.item>
				<flux:modal.trigger :name="'recipe-remove-'.$recipe->id">
					<flux:menu.item icon="trash" variant="danger">Remove</flux:menu.item>
				</flux:modal.trigger>
            </flux:menu>
        </flux:dropdown>

        <flux:modal :name="'recipe-remove-'.$recipe->id" class="min-w-[22rem]">
            <form class="space-y-6" wire:submit="$parent.remove({{ $recipe->id }})">
                <div>
                    <flux:heading size="lg">Remove recipe?</flux:heading>

                    <flux:subheading>
                        <p>You're about to delete this recipe.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:subheading>
                </div>

                <div class="flex space-x-2">
                    <flux:spacer />

                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="danger">Remove recipe</flux:button>
                </div>
            </form>
        </flux:modal>
    </flux:table.cell>
</flux:table.row>
