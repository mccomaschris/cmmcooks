<?php

use Livewire\Volt\Component;
use App\Models\Recipe;

new class extends Component {
    public $name = '';

    public $rules = [
        'name' => 'required|string|max:255',
    ];

	public function save()
    {
        $this->validate();

        $recipe = Recipe::create(
            $this->form->all()
        );

        return $this->redirect(route('admin.recipes.edit', $recipe));
    }
}; ?>

<div>
	<flux:modal.trigger name="add-recipe">
		<flux:button icon="plus" variant="primary">Add Recipe</flux:button>
	</flux:modal.trigger>

	<flux:modal name="add-recipe" class="md:w-96">
		<form wire:submit="save">
			<div class="space-y-6">
				<div>
					<flux:heading size="lg">Create recipe</flux:heading>
					<flux:subheading>Once created you can add ingredients, instructions and a description.</flux:subheading>
				</div>

				<flux:input wire:model="name" label="Recipe name" />

				<div class="flex">
					<flux:spacer />

					<flux:button type="submit" variant="primary">Save recipe</flux:button>
				</div>
			</div>
		</form>
	</flux:modal>
</div>
