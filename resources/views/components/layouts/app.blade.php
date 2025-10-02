<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'CMM Cooks' }}</title>

		<link rel="preconnect" href="https://fonts.bunny.net">
		<link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

		<link rel="stylesheet" href="{{ twcss('/css/cmmcooks.css') }}">
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/sort@3.x.x/dist/cdn.min.js"></script>
		@fluxAppearance
    </head>
    <body>
		<div>
            <flux:header container class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
                <flux:spacer />
				<flux:brand href="#" logo="/cmmcooks.svg" name="CMM Cooks!" class="max-lg:hidden dark:hidden" />
                <flux:spacer />
			</flux:header>

			<flux:main container>
				<div class="max-w-xl lg:max-w-4xl mx-auto">
					<livewire:breadcrumbs />
					{{ $slot }}
				</div>
			</flux:main>
		</div>

		@persist('toast')
			<flux:toast />
		@endpersist

		@fluxScripts
    </body>
</html>
