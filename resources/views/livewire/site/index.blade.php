<?php

use Livewire\Volt\Component;
use App\Models\Recipe;

new
#[Title('CMM Cooks | Recipes')]
class extends Component {
	public string $search = '';

	public function with(): array
    {
		$recipes = Recipe::query();

		if ($this->search) {
			$recipes->where('name', 'like', "%{$this->search}%")->orderby('name', 'asc');
		} else {
			$recipes->inRandomOrder();
		}

        return [
            'recipes' => $recipes->get(),
        ];
    }
}; ?>

<div class="">
    <div class="w-full flex flex-col gap-8">
        <input type="text" wire:model.live="search"
               class="w-full p-4 text-lg lg:text-xl font-semibold bg-white placeholder:text-gray-500 rounded-lg ring-4 ring-gray-900/20 text-emerald-500 appearance-none focus-visible:outline-none focus:ring-emerald-500"
               placeholder="Search for recipes...">
        <div class="text-left">
            <h2 class="text-xl font-bold text-zinc-800">Recipes</h2>
            <ul class="mt-2">
                @foreach($recipes as $recipe)
                    <li class="py-1">
                        <a href="{{ route('recipes.show', [$recipe] )}}" class="underline hover:no-underline text-emerald-500 hover:text-emerald-600">{{ $recipe->name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
