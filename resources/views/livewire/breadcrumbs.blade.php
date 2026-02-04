<?php

use Livewire\Component;

new class extends Component
{
    public array $breadcrumbs = [];

    public function mount()
    {
        $this->generateBreadcrumbs();
    }

    private function generateBreadcrumbs()
    {
        // Get the current path (excluding query parameters)
        $segments = request()->segments();

        // If there are no segments (i.e., homepage), don't set breadcrumbs
        if (empty($segments)) {
            return;
        }

        $path = '';

        // Start with the home breadcrumb
        $this->breadcrumbs[] = ['name' => 'Home', 'href' => url('/')];

        foreach ($segments as $segment) {
            $path .= '/' . $segment;
            $this->breadcrumbs[] = [
                'name' => ucfirst(str_replace('-', ' ', $segment)), // Convert slug to readable name
                'href' => url($path),
            ];
        }
    }
}; ?>

<div class="mb-10">
    <flux:breadcrumbs>
        @foreach($breadcrumbs as $index => $breadcrumb)
            @if ($loop->last)
                <flux:breadcrumbs.item>{{ $breadcrumb['name'] }}</flux:breadcrumbs.item>
            @else
                <flux:breadcrumbs.item href="{{ $breadcrumb['href'] }}">
                    {{ $breadcrumb['name'] }}
                </flux:breadcrumbs.item>
            @endif
        @endforeach
    </flux:breadcrumbs>
</div>
