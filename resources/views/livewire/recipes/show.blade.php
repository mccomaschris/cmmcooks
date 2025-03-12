<?php

use Livewire\Volt\Component;
use App\Models\Recipe;

new class extends Component {
    public Recipe $recipe;
}; ?>

<div>
    <flux:heading size="xl">{{  $recipe->name }}</flux:heading>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div>
            <flux:heading size="lg" :accent="true">Ingredients</flux:heading>
            <ul class="mt-4 rounded-xl border border-zinc-100 p-6">
                @foreach($recipe->ingredients as $ingredient)
                    <li class="mb-3 last:mb-0 text-zinc-600">{{ $ingredient->name }}</li>
                @endforeach
            </ul>
        </div>
        <div>
            <flux:heading size="lg" :accent="true">Instructions</flux:heading>
            <ol class="mt-4 rounded-xl border border-zinc-100 p-6">
                @foreach($recipe->instructions as $instruction)
                    <li class="mb-6 last:mb-0 text-zinc-600">
                        <flux:heading>Step {{ $loop->iteration }}</flux:heading>
                        <div class="mt-2">{{ $instruction->name }}</div>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
