# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

CMM Cooks is a personal recipe management application built with Laravel 12, Livewire 4, and Tailwind CSS 4. Authenticated admins manage recipes through a Livewire-powered admin interface; the public can browse and view recipes. a

## Common Commands

### Development

```bash
composer dev          # Runs server, queue, logs (Pail), and Tailwind watcher concurrently
npm run dev           # Tailwind CSS watch mode (standalone)
npm run build         # Build production CSS
```

### Testing

```bash
php artisan test                           # Run all tests (Pest)
php artisan test --filter=SiteIndexTest     # Run a single test file
php artisan test --filter="it can search"   # Run a specific test by name
```

### Linting

```bash
./vendor/bin/pint            # Fix code style (Laravel Pint, preset: laravel)
./vendor/bin/pint --test     # Check code style without fixing
```

Pint config (`pint.json`): uses `laravel` preset with `concat_space` rule set to `"one"` (spaces around `.` operator).

## Architecture

### Livewire Single-File Components

All UI is built with Livewire 4 single-file components (PHP + Blade in one `.blade.php` file). There are **no traditional controllers**. Components live in `resources/views/livewire/`:

- `site/index` — Public homepage with live recipe search
- `recipes/show` — Public recipe detail page
- `admin/recipes/index` — Admin recipe list with pagination
- `admin/recipes/create` — Recipe creation modal
- `admin/recipes/edit` — Full recipe editor (parent component)
- `admin/recipes/recipe` — Table row component for recipe list
- `admin/ingredients/ingredients` — Ingredient list manager with drag-and-drop sorting
- `admin/ingredients/ingredient` — Single ingredient row (edit/delete)
- `admin/instructions/instructions` — Instruction list manager with drag-and-drop sorting
- `admin/instructions/instruction` — Single instruction row (edit/delete)
- `admin/categories/categories` — Category checkbox assignment
- `breadcrumbs` — Auto-generated breadcrumbs from URL segments

### Layouts

Two layouts in `resources/views/components/layouts/`:
- `app.blade.php` — Public layout
- `admin.blade.php` — Admin layout (same structure, different max-width)

### Models & Relationships

- **Recipe** — `hasMany` ingredients (ordered by position), `hasMany` instructions (ordered by position), `belongsToMany` categories, `belongsToMany` similarRecipes (self-referential). Uses slug for route model binding.
- **Category** — `belongsToMany` recipes. Uses slug for route model binding.
- **Ingredient** — `belongsTo` recipe. Has position field for ordering.
- **Instruction** — `belongsTo` recipe. Has position field for ordering.

### Authentication & Authorization

- **Google OAuth only** via Laravel Socialite — no email/password login
- **Email whitelist** authorization: `ADMIN_ALLOWED_EMAILS` env var (comma-separated), accessed via `config('app.allowed_admin_emails')`
- **IsAdmin middleware** (`app/Http/Middleware/IsAdmin.php`) protects all `/admin` routes
- Login flow: `/login` → Google OAuth → callback checks whitelist → 401 or login

### Routing (`routes/web.php`)

All routes are in a single file:
- `/` — Public recipe index
- `/recipes/{recipe}` — Public recipe show (slug-based)
- `/login`, `/google/redirect`, `/google/callback` — OAuth flow
- `/admin/recipes` — Admin CRUD (protected by IsAdmin middleware)

### Sortable Items

Ingredients and instructions use position-based ordering with drag-and-drop (Alpine.js `x-sort`). The reordering logic uses DB transactions with a "shift block" algorithm to handle position swaps.

### Helper Functions

`app/helpers.php` (autoloaded via composer): `twcss($path)` — cache-busting for Tailwind CSS files.

### CSS Build

Tailwind CSS is compiled via `@tailwindcss/cli` directly (not Vite bundling). Source: `resources/css/cmmcooks.css` → Output: `public/css/cmmcooks.css`.

### Backup System

Custom artisan command `backup:database` — dumps MySQL, uploads to DigitalOcean Spaces (S3-compatible), sends email notification.

## Testing

Tests use **Pest PHP** with Laravel plugin. Test helper `createAdmin()` (defined in `tests/Pest.php`) creates an admin user and sets the allowed emails config. Tests use in-memory SQLite (`phpunit.xml`).

Feature tests are organized by Livewire component under `tests/Feature/Livewire/`. Factories exist for Recipe, Category, Ingredient, Instruction, and User.

## Third-Party Services

- **Google OAuth**: `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI`
- **DigitalOcean Spaces** (S3-compatible): Used for database backups via `do_spaces` disk
- **Livewire Flux Pro**: Premium UI components, requires private composer repo (`composer.fluxui.dev`)
- **Spatie Laravel Backup**: Installed but custom backup command is used instead
