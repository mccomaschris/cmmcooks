<?php

use Livewire\Volt\Component;
use App\Models\Category;
use App\Models\Recipe;

new class extends Component {
    public Recipe $recipe;
    public $categories = [];

    public function mount(Recipe $recipe)
    {
        $this->recipe = $recipe;
        $this->categories = $recipe->categories->pluck('id')->toArray();
    }

    public function updatedCategories()
    {
        $this->recipe->categories()->sync($this->categories);
    }

    public function with(): array
    {
        return [
            'allCategories' => Category::orderby('name', 'asc')->get(),
        ];
    }
}; ?>

<div>
    <div class="mb-6">
        <flux:heading size="xl" :accent="true" id="categories">Categories</flux:heading>
    </div>

    <flux:checkbox.group wire:model.live="categories" label="Categories">
        @foreach($allCategories as $category)
            <flux:checkbox label="{{ $category->name }}" value="{{ $category->id }}" />
        @endforeach
    </flux:checkbox.group>
</div>
