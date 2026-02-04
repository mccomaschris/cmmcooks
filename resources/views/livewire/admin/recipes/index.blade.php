<?php

use App\Models\Recipe;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new
#[Layout('layouts::admin')]
#[Title('All recipes')]
class extends Component
{
    use WithPagination;

    public function remove($id)
    {
        Recipe::findOrFail($id)->delete();

        $this->resetPage();

        Flux::modal('recipe-remove')->close();

        Flux::toast('Recipe deleted successfully.');
    }

    public function with(): array
    {
        return [
            'recipes' => Recipe::orderby('created_at', 'desc')->paginate(10),
        ];
    }
}; ?>

<div>
	<div class="flex items-center justify-between">
		<div>
			<flux:heading size="xl">All recipes</flux:heading>
			<flux:subheading>Here are all the recipes currently on the site.</flux:subheading>
		</div>
		<livewire:admin.recipes.create />
	</div>

	<flux:separator variant="subtle" class="my-8" />

	<flux:table :paginate="$recipes">
		<flux:table.columns>
			<flux:table.column>Recipe</flux:table.column>
			<flux:table.column>Total Ingredients</flux:table.column>
			<flux:table.column>Total Steps</flux:table.column>
			<flux:table.column>Categories</flux:table.column>
			<flux:table.column></flux:table.column>
		</flux:table.columns>

		<flux:table.rows>
			@foreach ($recipes as $recipe)
				<livewire:admin.recipes.recipe :$recipe :key="$recipe->id" />
			@endforeach
		</flux:table.rows>
	</flux:table>
</div>
