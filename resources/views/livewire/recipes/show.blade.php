<?php

use App\Models\Recipe;
use Livewire\Component;

new class extends Component
{
    public Recipe $recipe;
}; ?>

<div>
    <div class="flex items-center justify-between">
        <flux:heading size="xl">{{  $recipe->name }}</flux:heading>

        <div x-data="{
            wakeLock: null,
            active: false,
            async toggle() {
                if (this.active) {
                    await this.wakeLock?.release();
                    this.wakeLock = null;
                    this.active = false;
                } else {
                    try {
                        this.wakeLock = await navigator.wakeLock.request('screen');
                        this.active = true;
                        this.wakeLock.addEventListener('release', () => {
                            this.active = false;
                            this.wakeLock = null;
                        });
                    } catch (e) {}
                }
            }
        }"
        x-on:visibilitychange.window="if (active && document.visibilityState === 'visible') { toggle(); toggle(); }"
        >
            <flux:switch x-on:click="toggle()" ::checked="active" label="Keep screen on" />
        </div>
    </div>

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
